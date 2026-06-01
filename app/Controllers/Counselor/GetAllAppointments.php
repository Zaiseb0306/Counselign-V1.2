<?php

namespace App\Controllers\Counselor;


use App\Helpers\SecureLogHelper;
use App\Controllers\BaseController;

class GetAllAppointments extends BaseController
{
    public function index()
    {
        try {
            // Basic authentication check - only allow counselors
            $session = session();
            if (!$session->get('logged_in') || $session->get('role') !== 'counselor') {
                throw new \Exception('Unauthorized access');
            }

            $sessionUserId = session()->get('user_id');
            $sessionDisplayId = session()->get('user_id_display');
            $sessionEmail = session()->get('email');
            $timeRange = $this->request->getGet('timeRange') ?? 'weekly';

            $db = \Config\Database::connect();

            // Resolve the real counselor_id as stored in appointments.counselor_preference.
            // Some sessions keep an internal numeric id in user_id, while counselor_id is a string id.
            $resolvedCounselorId = null;
            $candidateIds = array_values(array_unique(array_filter([
                $sessionDisplayId,
                $sessionUserId
            ], static function ($v) {
                return $v !== null && $v !== '';
            })));

            if (!empty($candidateIds)) {
                $resolvedCounselorId = $db->table('counselors')
                    ->select('counselor_id')
                    ->whereIn('counselor_id', $candidateIds)
                    ->get()
                    ->getRowArray()['counselor_id'] ?? null;
            }

            // Fallback: if session user_id is the counselors.id PK
            if ($resolvedCounselorId === null && !empty($sessionUserId)) {
                $resolvedCounselorId = $db->table('counselors')
                    ->select('counselor_id')
                    ->where('id', $sessionUserId)
                    ->get()
                    ->getRowArray()['counselor_id'] ?? null;
            }

            // Build a robust list of possible counselor IDs that may map to this session.
            $counselorIds = [];
            if (!empty($resolvedCounselorId)) {
                $counselorIds[] = (string) $resolvedCounselorId;
            }
            if (!empty($sessionDisplayId)) {
                $counselorIds[] = (string) $sessionDisplayId;
            }

            // Resolve through users.id / users.user_id / users.email to counselors.counselor_id.
            $userBuilder = $db->table('users u')
                ->select('c.counselor_id')
                ->join('counselors c', 'c.counselor_id = u.user_id', 'inner');
            $hasUserFilter = false;

            if (!empty($sessionUserId)) {
                $escapedSessionUserId = $db->escape((string) $sessionUserId);
                $userBuilder->groupStart()
                    ->where("u.id = {$escapedSessionUserId}", null, false)
                    ->orWhere('u.user_id', (string) $sessionUserId)
                    ->groupEnd();
                $hasUserFilter = true;
            }
            if (!empty($sessionEmail)) {
                if ($hasUserFilter) {
                    $userBuilder->orWhere('u.email', (string) $sessionEmail);
                } else {
                    $userBuilder->where('u.email', (string) $sessionEmail);
                    $hasUserFilter = true;
                }
            }
            $mappedCounselors = $hasUserFilter ? $userBuilder->get()->getResultArray() : [];
            foreach ($mappedCounselors as $mappedCounselor) {
                if (!empty($mappedCounselor['counselor_id'])) {
                    $counselorIds[] = (string) $mappedCounselor['counselor_id'];
                }
            }

            $counselorIds = array_values(array_unique(array_filter($counselorIds)));
            if (empty($counselorIds)) {
                $counselorIds[] = (string) ($resolvedCounselorId ?? $sessionDisplayId ?? $sessionUserId);
            }

            // Kept for backward compatibility in parts of this controller.
            $userId = $counselorIds[0];

            // Get counselor name
            $counselorName = '';
            $counselorQuery = $db->table('counselors')
                ->select('name')
                ->where('counselor_id', $userId)
                ->get()
                ->getRowArray();
            if ($counselorQuery && !empty($counselorQuery['name'])) {
                $counselorName = $counselorQuery['name'];
            }

            // Base query for appointments
            $whereCounselor = count($counselorIds) === 1
                ? "appointments.counselor_preference = " . $db->escape($counselorIds[0])
                : "appointments.counselor_preference IN (" . implode(',', array_map([$db, 'escape'], $counselorIds)) . ")";

            $baseQuery = "SELECT
                        appointments.id,
                        appointments.student_id as user_id,
                        u.username,
                        CASE
                            WHEN COALESCE(spi.first_name, '') != '' OR COALESCE(spi.last_name, '') != ''
                            THEN CONCAT(COALESCE(spi.first_name, ''), ' ', COALESCE(spi.last_name, ''))
                            WHEN COALESCE(u.username, '') != ''
                            THEN u.username
                            ELSE appointments.student_id
                        END as student_name,
                        appointments.preferred_date as appointed_date,
                        appointments.preferred_time as appointed_time,
                        appointments.method_type,
                        'Individual Consultation' as consultation_type,
                        appointments.purpose,
                        appointments.description,
                        appointments.counselor_remarks,
                        COALESCE(NULLIF(sf.status, ''), NULLIF(appointments.student_feedback_status, ''), 'pending') as feedback_status,
                        sf.q1_ease_of_use,
                        sf.q2_satisfaction,
                        sf.q3_timeliness,
                        sf.q4_information_clarity,
                        sf.q5_staff_helpfulness,
                        sf.q6_technology_reliability,
                        sf.q7_privacy_confidence,
                        sf.q8_recommendation,
                        sf.q9_overall_experience,
                        sf.q10_future_use,
                        CASE
                            WHEN LOWER(COALESCE(appointments.status, '')) IN ('completed', 'feedback_pending')
                                 AND LOWER(REPLACE(REPLACE(COALESCE(NULLIF(sf.status, ''), NULLIF(appointments.student_feedback_status, ''), 'pending'), ' ', '_'), '-', '_')) NOT IN ('submitted', 'feedback_submitted')
                            THEN 1
                            ELSE 0
                        END as is_feedback_pending,
                        appointments.status,
                        appointments.reason,
                        appointments.counselor_preference,
                        c.name as counselor_name,
                        MONTH(appointments.preferred_date) as month
                      FROM appointments
                      LEFT JOIN student_feedback sf ON sf.appointment_id = appointments.id
                      LEFT JOIN student_personal_info spi ON spi.student_id = appointments.student_id
                      LEFT JOIN users u ON appointments.student_id = u.user_id
                      LEFT JOIN counselors c ON c.counselor_id = appointments.counselor_preference
                      WHERE {$whereCounselor}";

            // All appointments for the list view (no limit for proper chart data)
            $allAppointmentsQuery = $baseQuery . " ORDER BY appointments.preferred_date DESC";
            $allAppointments = $db->query($allAppointmentsQuery)->getResultArray();

            // Include completed/cancelled follow-up sessions, mapped to list schema
            // These are kept separate and only shown in the Follow-up tab
            $followUpsQuery = "SELECT
                    f.id,
                    f.student_id as user_id,
                    CASE 
                        WHEN COALESCE(spi.first_name, '') != '' OR COALESCE(spi.last_name, '') != '' 
                        THEN CONCAT(COALESCE(spi.first_name, ''), ' ', COALESCE(spi.last_name, ''))
                        WHEN COALESCE(u.username, '') != ''
                        THEN u.username
                        ELSE f.student_id
                    END as student_name,
                    f.preferred_date as appointed_date,
                    f.preferred_time as appointed_time,
                    'Online' as method_type,
                    'Individual Consultation' as consultation_type,
                    f.consultation_type as purpose,
                    f.description,
                    COALESCE(parent.counselor_remarks, '') as counselor_remarks,
                    COALESCE(c.name, 'No Preference') as counselor_name,
                    f.counselor_id as counselor_preference,
                    LOWER(f.status) as status,
                    f.reason as reason,
                    CASE WHEN sf.q1_ease_of_use IS NOT NULL THEN 'submitted' ELSE 'pending' END as feedback_status,
                    sf.q1_ease_of_use,
                    sf.q2_satisfaction,
                    sf.q3_timeliness,
                    sf.q4_information_clarity,
                    sf.q5_staff_helpfulness,
                    sf.q6_technology_reliability,
                    sf.q7_privacy_confidence,
                    sf.q8_recommendation,
                    sf.q9_overall_experience,
                    sf.q10_future_use,
                    0 as is_feedback_pending,
                    'Follow-up Session' as appointment_type,
                    'follow_up' as record_kind
                FROM follow_up_appointments f
                LEFT JOIN student_feedback sf ON sf.appointment_id = f.parent_appointment_id
                LEFT JOIN student_personal_info spi ON spi.student_id = f.student_id
                LEFT JOIN users u ON f.student_id = u.user_id
                LEFT JOIN appointments parent ON parent.id = f.parent_appointment_id
                LEFT JOIN counselors c ON c.counselor_id = f.counselor_id
                WHERE f.counselor_id IN (" . implode(',', array_map([$db, 'escape'], $counselorIds)) . ") AND f.status IN ('pending','completed')
                ORDER BY f.preferred_date DESC";

            $followUps = $db->query($followUpsQuery)->getResultArray();

            // Apply date filter for chart data based on timeRange
            $dateFilter = "";
            $startDateStr = null;
            $endDateStr = null;

            switch ($timeRange) {
                case 'daily':
                    $currentDate = new \DateTime();
                    $startDateStr = $currentDate->format('Y-m-d');
                    $endDateStr = $currentDate->format('Y-m-d');
                    $dateFilter = " AND appointments.preferred_date = " . $db->escape($startDateStr);
                    break;
                case 'weekly':
                    $currentDate = new \DateTime();
                    $startDate = clone $currentDate;
                    while ($startDate->format('N') != 1) { $startDate->modify('-1 day'); }
                    $startDate->modify('-28 days');
                    $endDate = clone $currentDate;
                    $startDateStr = $startDate->format('Y-m-d');
                    $endDateStr = $endDate->format('Y-m-d');
                    $dateFilter = " AND appointments.preferred_date >= '$startDateStr' AND appointments.preferred_date <= '$endDateStr'";
                    break;
                case 'monthly':
                    $currentYear = date('Y');
                    $startDateStr = $currentYear . '-01-01';
                    $endDateStr = $currentYear . '-12-31';
                    $dateFilter = " AND YEAR(appointments.preferred_date) = '$currentYear'";
                    break;
            }

            $chartQuery = $baseQuery . $dateFilter . " ORDER BY appointments.preferred_date ASC";
            $chartAppointments = $db->query($chartQuery)->getResultArray();

            // Include follow-up sessions in chart statistics for the same period
            $fuChartQuery = "SELECT
                    f.preferred_date as appointed_date,
                    LOWER(f.status) as status
                FROM follow_up_appointments f
                WHERE f.counselor_id IN (" . implode(',', array_map([$db, 'escape'], $counselorIds)) . ")
                AND f.status IN ('pending','completed')";
            if ($timeRange === 'monthly') {
                $fuChartQuery .= " AND YEAR(f.preferred_date) = YEAR(CURDATE())";
            } elseif (!empty($startDateStr) && !empty($endDateStr)) {
                $fuChartQuery .= " AND f.preferred_date >= " . $db->escape($startDateStr)
                    . " AND f.preferred_date <= " . $db->escape($endDateStr);
            }
            $followUpForCharts = $db->query($fuChartQuery)->getResultArray();
            foreach ($followUpForCharts as $fu) {
                $chartAppointments[] = [
                    'appointed_date' => $fu['appointed_date'],
                    'status' => $fu['status'],
                    'is_feedback_pending' => 0,
                    'feedback_status' => 'pending',
                ];
            }

            // Process appointments for statistics
            $dateFormat = ($timeRange === 'monthly') ? 'F' : 'Y-m-d';
            $stats = [];

            // Initialize dates based on time range
            if ($timeRange === 'daily' && $startDateStr && $endDateStr) {
                $currentDate = new \DateTime($startDateStr);
                $endDate = new \DateTime($endDateStr);
                while ($currentDate <= $endDate) {
                    $dateStr = $currentDate->format('Y-m-d');
                    $stats[$dateStr] = ['completed' => 0, 'approved' => 0, 'rejected' => 0, 'rescheduled' => 0, 'pending' => 0, 'feedback_pending' => 0];
                    $currentDate->modify('+1 day');
                }
                $response['weekInfo'] = [
                    'startDate' => $startDateStr,
                    'endDate' => $endDateStr,
                    'weekDays' => []
                ];
                $tempDate = new \DateTime($startDateStr);
                $endTempDate = new \DateTime($endDateStr);
                while ($tempDate <= $endTempDate) {
                    $response['weekInfo']['weekDays'][] = [
                        'date' => $tempDate->format('Y-m-d'),
                        'dayName' => $tempDate->format('l'),
                        'shortDayName' => $tempDate->format('D'),
                        'dayMonth' => $tempDate->format('M j')
                    ];
                    $tempDate->modify('+1 day');
                }
            } elseif ($timeRange === 'weekly' && $startDateStr && $endDateStr) {
                $currentDate = new \DateTime($startDateStr);
                $lastDate = new \DateTime($endDateStr);
                while ($currentDate->format('N') != 1) { $currentDate->modify('-1 day'); }
                while ($lastDate->format('N') != 7) { $lastDate->modify('+1 day'); }
                while ($currentDate <= $lastDate) {
                    $weekStart = $currentDate->format('Y-m-d');
                    $stats[$weekStart] = ['completed' => 0, 'approved' => 0, 'rejected' => 0, 'rescheduled' => 0, 'pending' => 0, 'feedback_pending' => 0];
                    $currentDate->modify('+7 days');
                }
                $response['weekRanges'] = [];
                foreach (array_keys($stats) as $weekStart) {
                    $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
                    $response['weekRanges'][] = [
                        'start' => $weekStart,
                        'end' => $weekEnd
                    ];
                }
            } elseif ($timeRange === 'monthly') {
                for ($i = 1; $i <= 12; $i++) {
                    $monthName = date('F', mktime(0, 0, 0, $i, 1));
                    $stats[$monthName] = ['completed' => 0, 'approved' => 0, 'rejected' => 0, 'rescheduled' => 0, 'pending' => 0, 'feedback_pending' => 0];
                }
            }

            $totalStats = ['completed' => 0, 'approved' => 0, 'rejected' => 0, 'rescheduled' => 0, 'pending' => 0, 'feedback_pending' => 0];
            $monthlyStats = array_fill(1, 12, ['completed' => 0, 'approved' => 0, 'rejected' => 0, 'rescheduled' => 0, 'pending' => 0, 'feedback_pending' => 0]);

            // Process chart data for time-series statistics (with date filter)
            foreach ($chartAppointments as $appointment) {
                $appointmentDate = new \DateTime($appointment['appointed_date']);
                $month = (int) $appointmentDate->format('n');

                if ($timeRange === 'weekly') {
                    while ($appointmentDate->format('N') != 1) {
                        $appointmentDate->modify('-1 day');
                    }
                    $date = $appointmentDate->format('Y-m-d');
                    if (!isset($stats[$date])) {
                        continue;
                    }
                } elseif ($timeRange === 'monthly') {
                    $date = $appointmentDate->format('F');
                    if (!isset($stats[$date])) {
                        continue;
                    }
                } else {
                    $date = $appointmentDate->format('Y-m-d');
                    if (!isset($stats[$date])) {
                        continue;
                    }
                }

                $status = strtolower($appointment['status'] ?? '');
                $feedbackStatus = strtolower(str_replace(['-', ' '], '_', $appointment['feedback_status'] ?? 'pending'));
                $isFeedbackSubmitted = in_array($feedbackStatus, ['submitted', 'feedback_submitted'], true);
                $isFeedbackPending = (int) ($appointment['is_feedback_pending'] ?? 0) === 1;

                if ($status === 'completed' && !$isFeedbackSubmitted) {
                    $status = 'feedback_pending';
                } elseif ($isFeedbackPending && $status !== 'feedback_pending') {
                    $status = 'feedback_pending';
                }

                if (in_array($status, ['completed', 'approved', 'rejected', 'rescheduled', 'pending', 'feedback_pending'], true)) {
                    $stats[$date][$status]++;
                    $monthlyStats[$month][$status]++;
                }
            }

            foreach ($stats as $stat) {
                foreach ($totalStats as $key => $value) {
                    $totalStats[$key] += (int) ($stat[$key] ?? 0);
                }
            }

            // Add follow-ups to monthly stats for monthly chart
            if ($timeRange === 'monthly') {
                foreach ($followUps as $followUp) {
                    $month = date('n', strtotime($followUp['appointed_date']));
                    $status = strtolower($followUp['status']);
                    if ($status === 'completed') {
                        $monthlyStats[$month]['completed']++;
                    } else if ($status === 'pending') {
                        $monthlyStats[$month]['pending']++;
                    }
                }
            }

            ksort($stats);
            $labels = array_keys($stats);
            $completed = [];
            $approved = [];
            $rejected = [];
            $rescheduled = [];
            $pending = [];
            $feedback_pending = [];

            foreach ($stats as $stat) {
                $completed[] = $stat['completed'];
                $approved[] = $stat['approved'];
                $rejected[] = $stat['rejected'];
                $rescheduled[] = $stat['rescheduled'];
                $pending[] = $stat['pending'];
                $feedback_pending[] = $stat['feedback_pending'];
            }

            $monthlyCompleted = [];
            $monthlyApproved = [];
            $monthlyRescheduled = [];
            $monthlyRejected = [];
            $monthlyPending = [];
            $monthlyFeedbackPending = [];

            for ($i = 1; $i <= 12; $i++) {
                $monthlyCompleted[] = $monthlyStats[$i]['completed'];
                $monthlyApproved[] = $monthlyStats[$i]['approved'];
                $monthlyRescheduled[] = $monthlyStats[$i]['rescheduled'];
                $monthlyRejected[] = $monthlyStats[$i]['rejected'];
                $monthlyPending[] = $monthlyStats[$i]['pending'];
                $monthlyFeedbackPending[] = $monthlyStats[$i]['feedback_pending'];
            }

            $response = [
                'success' => true,
                'counselorName' => $counselorName,
                'counselorIds' => $counselorIds,
                'appointments' => $allAppointments,
                'followUps' => $followUps,
                'labels' => $labels,
                'datasets' => [
                    'completed' => $completed,
                    'approved' => $approved,
                    'rejected' => $rejected,
                    'rescheduled' => $rescheduled,
                    'pending' => $pending,
                    'feedback_pending' => $feedback_pending
                ],
                'completed' => $completed,
                'approved' => $approved,
                'rejected' => $rejected,
                'rescheduled' => $rescheduled,
                'pending' => $pending,
                'feedback_pending' => $feedback_pending,
                'totalCompleted' => $totalStats['completed'],
                'totalApproved' => $totalStats['approved'],
                'totalRescheduled' => $totalStats['rescheduled'],
                'totalPending' => $totalStats['pending'],
                'totalFeedbackPending' => $totalStats['feedback_pending'],
                'monthlyCompleted' => $monthlyCompleted,
                'monthlyApproved' => $monthlyApproved,
                'monthlyRescheduled' => $monthlyRescheduled,
                'monthlyRejected' => $monthlyRejected,
                'monthlyPending' => $monthlyPending,
                'monthlyFeedbackPending' => $monthlyFeedbackPending
            ];

            if (!empty($startDateStr) && !empty($endDateStr)) {
                $response['startDate'] = $startDateStr;
                $response['endDate'] = $endDateStr;
            }

            log_message('info', 'GetAllAppointments::index called - returning ' . count($allAppointments) . ' appointments');
        } catch (\Exception $e) {
            log_message('error', 'GetAllAppointments error: ' . $e->getMessage());
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'appointments' => [],
                'labels' => [],
                'datasets' => [],
                'completed' => [],
                'approved' => [],
                'rejected' => [],
                'rescheduled' => [],
                'pending' => [],
                'feedback_pending' => [],
                'totalCompleted' => 0,
                'totalApproved' => 0,
                'totalRescheduled' => 0,
                'totalPending' => 0,
                'totalFeedbackPending' => 0,
                'monthlyCompleted' => array_fill(0, 12, 0),
                'monthlyApproved' => array_fill(0, 12, 0),
                'monthlyRescheduled' => array_fill(0, 12, 0),
                'monthlyRejected' => array_fill(0, 12, 0),
                'monthlyPending' => array_fill(0, 12, 0),
                'monthlyFeedbackPending' => array_fill(0, 12, 0)
            ];
        }

        // Ensure we always return a valid JSON response
        log_message('info', 'GetAllAppointments response: ' . json_encode($response));
        return $this->response->setJSON($response);
    }
}



