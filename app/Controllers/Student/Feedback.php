<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Services\AppointmentEmailService;
use App\Services\FeedbackQuestionsService;
use App\Services\SentimentAnalysisService;

class Feedback extends BaseController
{
    public function index()
    {
        // Check if user is logged in and is a student
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            return redirect()->to('/');
        }

        // Get appointment ID from URL parameter
        $appointmentId = $this->request->getGet('appointment_id');

        if (!$appointmentId) {
            return redirect()->to('student/my-appointments')->with('error', 'Invalid appointment ID');
        }

        // Verify the appointment belongs to the logged-in student and is in feedback_pending status
        $db = \Config\Database::connect();
        $studentId = session()->get('user_id_display') ?? session()->get('user_id');

        $appointment = $db->table('appointments')
            ->where('id', $appointmentId)
            ->where('student_id', $studentId)
            ->where('status', 'feedback_pending')
            ->get()
            ->getRowArray();

        if (!$appointment) {
            return redirect()->to('student/my-appointments')->with('error', 'Appointment not found or feedback not required');
        }

        // Check if feedback already submitted
        $existingFeedback = $db->table('student_feedback')
            ->where('appointment_id', $appointmentId)
            ->where('student_id', $studentId)
            ->get()
            ->getRowArray();

        if ($existingFeedback) {
            return redirect()->to('student/my-appointments')->with('info', 'Feedback already submitted for this appointment');
        }

        $feedbackService = new FeedbackQuestionsService();
        $feedbackService->ensureTables();
        $questions = $feedbackService->getActiveQuestions();

        if ($questions === []) {
            return redirect()->to('student/my-appointments')->with('error', 'Feedback form is not available yet. Please contact the administrator.');
        }

        return view('student/feedback', [
            'appointment' => $appointment,
            'appointmentId' => $appointmentId,
            'questions' => $questions
        ]);
    }

    public function submit()
    {
        // Check if user is logged in and is a student
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $appointmentId = $this->request->getPost('appointment_id');
        $studentId = session()->get('user_id_display') ?? session()->get('user_id');

        // Verify the appointment
        $db = \Config\Database::connect();
        $appointment = $db->table('appointments')
            ->where('id', $appointmentId)
            ->where('student_id', $studentId)
            ->where('status', 'feedback_pending')
            ->get()
            ->getRowArray();

        if (!$appointment) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid appointment']);
        }

        // Check if feedback already exists
        $existingFeedback = $db->table('student_feedback')
            ->where('appointment_id', $appointmentId)
            ->where('student_id', $studentId)
            ->get()
            ->getRowArray();

        if ($existingFeedback) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Feedback already submitted']);
        }

        $feedbackService = new FeedbackQuestionsService();
        $feedbackService->ensureTables();
        $questions = $feedbackService->getActiveQuestions();

        if ($questions === []) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No feedback questions are configured']);
        }

        $additionalComments = $this->request->getPost('additional_comments');

        $sentimentService = new SentimentAnalysisService();
        $sentimentAnalysis = $sentimentService->analyze($additionalComments);

        $ratings = [];
        foreach ($questions as $question) {
            $fieldName = (string) $question['field_name'];
            $value = $this->request->getPost($fieldName);

            if (!isset($value) || $value === '' || !is_numeric($value) || (int) $value < 1 || (int) $value > 5) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'All questions must be answered with a rating from 1-5']);
            }

            $ratings[$fieldName] = (int) $value;
        }

        $tableColumns = $feedbackService->getStudentFeedbackTableColumns();
        $feedbackData = [
            'appointment_id' => $appointmentId,
            'student_id' => $studentId,
            'counselor_id' => $appointment['counselor_preference'],
            'additional_comments' => $additionalComments,
            'sentiment_score' => $sentimentService->getScoreForStorage($additionalComments),
            'sentiment_label' => $sentimentAnalysis['label'],
            'status' => 'submitted',
        ];

        foreach ($ratings as $fieldName => $rating) {
            if (in_array($fieldName, $tableColumns, true)) {
                $feedbackData[$fieldName] = $rating;
            }
        }

        foreach (FeedbackQuestionsService::LEGACY_FIELDS as $legacyField) {
            if (!in_array($legacyField, $tableColumns, true)) {
                continue;
            }
            if (!isset($feedbackData[$legacyField])) {
                $feedbackData[$legacyField] = 3;
            }
        }

        $db->transStart();
        try {
            $db->table('student_feedback')->insert($feedbackData);
            $feedbackId = (int) $db->insertID();
            $feedbackService->saveResponses($feedbackId, $questions, $ratings);

            // Update appointment status to completed and student feedback status
            $db->table('appointments')
                ->where('id', $appointmentId)
                ->update([
                    'status' => 'completed',
                    'student_feedback_status' => 'Feedback Submitted'
                ]);

            $db->transComplete();

            // Get student information for notification
            $studentInfo = $db->table('student_personal_info')
                ->where('student_id', $studentId)
                ->get()
                ->getRowArray();

            $studentName = 'Student';
            if ($studentInfo) {
                $studentName = trim($studentInfo['last_name'] . ', ' . $studentInfo['first_name']);
            } else {
                $user = $db->table('users')
                    ->select('username')
                    ->where('user_id', $studentId)
                    ->get()
                    ->getRowArray();
                if ($user) {
                    $studentName = $user['username'];
                }
            }

            // Create notification for counselor
            $counselorId = $appointment['counselor_preference'];
            if ($counselorId) {
                $db->table('notifications')->insert([
                    'user_id' => $counselorId,
                    'type' => 'feedback',
                    'title' => 'New Student Feedback',
                    'message' => $studentName . ' has submitted feedback for their appointment on ' . date('F j, Y', strtotime($appointment['preferred_date'])),
                    'related_id' => $appointmentId,
                    'is_read' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // Send email notification to counselor
                $emailService = new AppointmentEmailService();
                $emailData = [
                    'student_name' => $studentName,
                    'student_id' => $studentId,
                    'appointment_date' => $appointment['preferred_date'],
                    'appointment_time' => $appointment['preferred_time'],
                    'consultation_type' => $appointment['consultation_type'],
                    'additional_comments' => $feedbackData['additional_comments']
                ];
                $emailService->sendFeedbackSubmissionNotification($counselorId, $emailData);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Thank you for your feedback! You can now schedule new appointments.'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to submit feedback: ' . $e->getMessage()]);
        }
    }
}