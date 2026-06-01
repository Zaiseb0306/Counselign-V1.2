<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StudentFeedbackAnalyticsModel;
use App\Models\CounselorModel;
use App\Services\FeedbackQuestionsService;

/**
 * Feedback Analytics Controller
 * 
 * Handles student feedback analytics and descriptive statistics
 */
class FeedbackAnalytics extends BaseController
{
    private $analyticsModel;
    private $counselorModel;

    public function __construct()
    {
        $this->analyticsModel = new StudentFeedbackAnalyticsModel();
        $this->counselorModel = new CounselorModel();
    }

    /**
     * Display feedback analytics dashboard
     */
    public function index()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        // Get filters from request
        $counselorId = $this->request->getGet('counselor_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Build filters
        $filters = [];
        if (!empty($counselorId)) {
            $filters['counselor_id'] = $counselorId;
        }
        if (!empty($startDate)) {
            $filters['start_date'] = $startDate;
        }
        if (!empty($endDate)) {
            $filters['end_date'] = $endDate;
        }

        // Get analytics data
        $analytics = $this->analyticsModel->getAnalytics($filters);
        $categoryMeans = $this->analyticsModel->getCategoryMeans($analytics);
        $monthlyTrend = $this->analyticsModel->getMonthlyTrend(12);

        // Get all counselors for filter dropdown
        $counselors = $this->counselorModel->findAll();

        $data = [
            'analytics' => $analytics,
            'category_means' => $categoryMeans,
            'monthly_trend' => $monthlyTrend,
            'counselors' => $counselors,
            'filters' => $filters
        ];

        return view('admin/feedback_analytics', $data);
    }

    /**
     * Get analytics data as JSON (for AJAX requests)
     */
    public function getAnalyticsData()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }

        $counselorId = $this->request->getGet('counselor_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $filters = [];
        if (!empty($counselorId)) {
            $filters['counselor_id'] = $counselorId;
        }
        if (!empty($startDate)) {
            $filters['start_date'] = $startDate;
        }
        if (!empty($endDate)) {
            $filters['end_date'] = $endDate;
        }

        $analytics = $this->analyticsModel->getAnalytics($filters);
        $categoryMeans = $this->analyticsModel->getCategoryMeans($analytics);

        return $this->response->setJSON([
            'success' => true,
            'analytics' => $analytics,
            'category_means' => $categoryMeans
        ]);
    }

    /**
     * Export analytics data as PDF
     */
    public function exportPDF()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        // Get filters
        $counselorId = $this->request->getGet('counselor_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $filters = [];
        if (!empty($counselorId)) {
            $filters['counselor_id'] = $counselorId;
        }
        if (!empty($startDate)) {
            $filters['start_date'] = $startDate;
        }
        if (!empty($endDate)) {
            $filters['end_date'] = $endDate;
        }

        $analytics = $this->analyticsModel->getAnalytics($filters);
        $categoryMeans = $this->analyticsModel->getCategoryMeans($analytics);

        // Generate PDF (implementation depends on PDF library)
        // This is a placeholder for PDF generation logic
        $data = [
            'analytics' => $analytics,
            'category_means' => $categoryMeans,
            'filters' => $filters,
            'generated_at' => date('Y-m-d H:i:s')
        ];

        // Return PDF view or file
        return view('admin/feedback_analytics_pdf', $data);
    }

    /**
     * Export analytics data as Excel
     */
    public function exportExcel()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        // Get filters
        $counselorId = $this->request->getGet('counselor_id');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        $filters = [];
        if (!empty($counselorId)) {
            $filters['counselor_id'] = $counselorId;
        }
        if (!empty($startDate)) {
            $filters['start_date'] = $startDate;
        }
        if (!empty($endDate)) {
            $filters['end_date'] = $endDate;
        }

        $analytics = $this->analyticsModel->getAnalytics($filters);

        // Generate Excel file (implementation depends on Excel library)
        // This is a placeholder for Excel generation logic
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Excel export feature - implementation pending',
            'analytics' => $analytics
        ]);
    }

    /**
     * View all feedback with sentiment analysis
     */
    public function viewFeedback()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();
        
        // Get filters
        $counselorId = trim((string) $this->request->getGet('counselor_id'));
        $sentimentLabel = strtolower(trim((string) $this->request->getGet('sentiment_label')));
        $startDate = $this->normalizeDateInput($this->request->getGet('start_date'));
        $endDate = $this->normalizeDateInput($this->request->getGet('end_date'));

        // Build query
        $builder = $db->table('student_feedback sf')
            ->select('sf.*, a.preferred_date, a.preferred_time, a.consultation_type, 
                     u.username as student_name, c.name as counselor_name')
            ->join('appointments a', 'sf.appointment_id = a.id', 'left')
            ->join('users u', 'sf.student_id = u.user_id', 'left')
            ->join('counselors c', 'sf.counselor_id = c.counselor_id', 'left')
            ->where('sf.status', 'submitted')
            ->orderBy('sf.submitted_at', 'DESC');

        // Apply filters
        $this->applyFeedbackFilters($builder, $counselorId, $sentimentLabel, $startDate, $endDate);

        $feedbacks = $builder->get()->getResultArray();
        $feedbackService = new FeedbackQuestionsService();
        $feedbacks = $feedbackService->enrichFeedbacksWithResponses($feedbacks);
        $activeQuestions = $feedbackService->getActiveQuestions();

        // Keep sentiment cards and alert aligned with selected filters.
        $sentimentStats = $this->calculateSentimentStatsFromFeedbacks($feedbacks);
        $negativeFeedback = array_values(array_filter($feedbacks, static function (array $feedback): bool {
            return strtolower((string) ($feedback['sentiment_label'] ?? '')) === 'negative';
        }));

        // Get all counselors for filter
        $counselors = $this->counselorModel->findAll();

        return view('admin/view_feedback', [
            'feedbacks' => $feedbacks,
            'activeQuestions' => $activeQuestions,
            'sentiment_stats' => $sentimentStats,
            'negative_feedback' => $negativeFeedback,
            'counselors' => $counselors,
            'filters' => [
                'counselor_id' => $counselorId,
                'sentiment_label' => $sentimentLabel,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Get feedback data as JSON (for AJAX)
     */
    public function getFeedbackData()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 401);
        }

        $db = \Config\Database::connect();
        
        $counselorId = trim((string) $this->request->getGet('counselor_id'));
        $sentimentLabel = strtolower(trim((string) $this->request->getGet('sentiment_label')));
        $startDate = $this->normalizeDateInput($this->request->getGet('start_date'));
        $endDate = $this->normalizeDateInput($this->request->getGet('end_date'));

        $builder = $db->table('student_feedback sf')
            ->select('sf.*, a.preferred_date, a.preferred_time, a.consultation_type, 
                     u.username as student_name, c.name as counselor_name')
            ->join('appointments a', 'sf.appointment_id = a.id', 'left')
            ->join('users u', 'sf.student_id = u.user_id', 'left')
            ->join('counselors c', 'sf.counselor_id = c.counselor_id', 'left')
            ->where('sf.status', 'submitted')
            ->orderBy('sf.submitted_at', 'DESC');

        $this->applyFeedbackFilters($builder, $counselorId, $sentimentLabel, $startDate, $endDate);

        $feedbacks = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'feedbacks' => $feedbacks
        ]);
    }

    private function applyFeedbackFilters($builder, string $counselorId, string $sentimentLabel, ?string $startDate, ?string $endDate): void
    {
        if ($counselorId !== '') {
            $builder->where('sf.counselor_id', $counselorId);
        }

        if ($sentimentLabel !== '') {
            $builder->where('sf.sentiment_label', $sentimentLabel);
        }

        if (!empty($startDate)) {
            $builder->where('DATE(sf.submitted_at) >=', $startDate);
        }

        if (!empty($endDate)) {
            $builder->where('DATE(sf.submitted_at) <=', $endDate);
        }
    }

    /**
     * Normalize input date into Y-m-d, accepts Y-m-d and d/m/Y.
     */
    private function normalizeDateInput($date): ?string
    {
        $value = trim((string) $date);
        if ($value === '') {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            $parsed = \DateTime::createFromFormat('d/m/Y', $value);
            if ($parsed instanceof \DateTime) {
                return $parsed->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }

    private function calculateSentimentStatsFromFeedbacks(array $feedbacks): array
    {
        $positive = 0;
        $negative = 0;
        $neutral = 0;
        $totalScore = 0;
        $scores = [];

        foreach ($feedbacks as $feedback) {
            $label = strtolower((string) ($feedback['sentiment_label'] ?? 'neutral'));
            $score = (float) ($feedback['sentiment_score'] ?? 0);

            $totalScore += $score;
            $scores[] = $score;

            if ($label === 'positive') {
                $positive++;
            } elseif ($label === 'negative') {
                $negative++;
            } else {
                $neutral++;
            }
        }

        $total = count($feedbacks);

        return [
            'total' => $total,
            'positive' => $positive,
            'negative' => $negative,
            'neutral' => $neutral,
            'positive_percentage' => $total > 0 ? round(($positive / $total) * 100, 2) : 0,
            'negative_percentage' => $total > 0 ? round(($negative / $total) * 100, 2) : 0,
            'neutral_percentage' => $total > 0 ? round(($neutral / $total) * 100, 2) : 0,
            'average_score' => $total > 0 ? round($totalScore / $total, 2) : 0,
            'score_distribution' => [
                'min' => $total > 0 ? min($scores) : 0,
                'max' => $total > 0 ? max($scores) : 0,
                'median' => $total > 0 ? $this->calculateMedian($scores) : 0,
            ],
        ];
    }

    private function calculateMedian(array $arr): float
    {
        sort($arr);
        $count = count($arr);
        $mid = (int) floor($count / 2);

        if ($count % 2) {
            return (float) $arr[$mid];
        }

        return ((float) $arr[$mid - 1] + (float) $arr[$mid]) / 2;
    }
}
