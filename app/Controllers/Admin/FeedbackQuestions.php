<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\FeedbackQuestionsService;

class FeedbackQuestions extends BaseController
{
    private FeedbackQuestionsService $feedbackService;

    public function __construct()
    {
        $this->feedbackService = new FeedbackQuestionsService();
    }

    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('auth'));
        }

        try {
            $this->feedbackService->ensureTables();
            $questions = $this->feedbackService->getAllQuestions();
        } catch (\Throwable $e) {
            log_message('error', 'Feedback questions index: ' . $e->getMessage());
            $questions = [];
        }

        return view('admin/feedback_questions', [
            'questions' => $questions,
            'tableReady' => $questions !== [] || true,
        ]);
    }

    public function getAll()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        try {
            $this->feedbackService->ensureTables();
            $questions = $this->feedbackService->getAllQuestions();

            return $this->response->setJSON([
                'status' => 'success',
                'questions' => $questions,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Could not load questions: ' . $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    public function create()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $this->feedbackService->ensureTables();

        $questionText = trim((string) $this->request->getPost('question_text'));
        $fieldName = trim((string) $this->request->getPost('field_name'));
        $sortOrder = $this->request->getPost('sort_order');
        $isActive = (int) ($this->request->getPost('is_active') ?? 1);

        if ($questionText === '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Question text is required',
            ]);
        }

        $maxQuestionNumber = (int) ($db->table('feedback_questions')
            ->selectMax('question_number')
            ->get()
            ->getRow()
            ->question_number ?? 0);

        $nextQuestionNumber = $maxQuestionNumber + 1;

        if ($fieldName === '') {
            $fieldName = $this->feedbackService->generateFieldName($questionText, $nextQuestionNumber);
        }

        $existingField = $db->table('feedback_questions')
            ->where('field_name', $fieldName)
            ->countAllResults();

        if ($existingField > 0) {
            $fieldName = $this->feedbackService->generateFieldName($questionText . ' ' . $nextQuestionNumber, $nextQuestionNumber);
        }

        $maxSort = (int) ($db->table('feedback_questions')
            ->selectMax('sort_order')
            ->get()
            ->getRow()
            ->sort_order ?? 0);

        $data = [
            'question_number' => $nextQuestionNumber,
            'question_text' => $questionText,
            'field_name' => $fieldName,
            'sort_order' => $sortOrder !== null && $sortOrder !== '' ? (int) $sortOrder : $maxSort + 1,
            'is_active' => $isActive ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $db->table('feedback_questions')->insert($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Question added successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add question: ' . $e->getMessage(),
            ]);
        }
    }

    public function update($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $this->feedbackService->ensureTables();

        $questionText = trim((string) $this->request->getPost('question_text'));
        $sortOrder = $this->request->getPost('sort_order');
        $isActive = (int) ($this->request->getPost('is_active') ?? 1);

        if ($questionText === '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Question text is required',
            ]);
        }

        $existing = $db->table('feedback_questions')->where('id', $id)->get()->getRowArray();
        if (!$existing) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Question not found',
            ]);
        }

        $data = [
            'question_text' => $questionText,
            'sort_order' => $sortOrder !== null && $sortOrder !== '' ? (int) $sortOrder : (int) $existing['sort_order'],
            'is_active' => $isActive ? 1 : 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        try {
            $db->table('feedback_questions')->where('id', $id)->update($data);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Question updated successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update question: ' . $e->getMessage(),
            ]);
        }
    }

    public function delete($id)
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $this->feedbackService->ensureTables();

        $count = $db->table('feedback_questions')->countAllResults();
        if ($count <= 1) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'At least one feedback question must remain.',
            ]);
        }

        try {
            $db->table('feedback_questions')->where('id', $id)->delete();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Question deleted successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete question: ' . $e->getMessage(),
            ]);
        }
    }

    public function reorder()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        $db = \Config\Database::connect();
        $this->feedbackService->ensureTables();

        $questions = $this->request->getJSON(true);
        if (!is_array($questions)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid reorder payload',
            ]);
        }

        try {
            foreach ($questions as $index => $question) {
                $id = is_array($question) ? ($question['id'] ?? null) : ($question->id ?? null);
                if (!$id) {
                    continue;
                }
                $db->table('feedback_questions')
                    ->where('id', $id)
                    ->update(['sort_order' => $index + 1]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Questions reordered successfully',
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to reorder questions: ' . $e->getMessage(),
            ]);
        }
    }

    public function seedDefaults()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unauthorized access',
            ])->setStatusCode(401);
        }

        try {
            $this->feedbackService->ensureTables();
            $this->feedbackService->seedDefaultQuestions();
            $questions = $this->feedbackService->getAllQuestions();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Default questions loaded',
                'questions' => $questions,
            ]);
        } catch (\Throwable $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
