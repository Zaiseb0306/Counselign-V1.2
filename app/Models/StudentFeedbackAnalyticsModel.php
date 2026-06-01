<?php

namespace App\Models;

use App\Services\FeedbackQuestionsService;
use CodeIgniter\Model;

/**
 * Student Feedback Analytics Model
 * 
 * Handles statistical calculations for student feedback analysis
 * including weighted mean, frequency distribution, and interpretation
 */
class StudentFeedbackAnalyticsModel extends Model
{
    protected $table = 'student_feedback';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    private function getQuestionDefinitions(): array
    {
        return (new FeedbackQuestionsService())->getQuestionDefinitionsForAnalytics();
    }

    /**
     * Likert scale labels
     */
    private $likertScale = [
        1 => 'Strongly Disagree',
        2 => 'Disagree',
        3 => 'Neutral',
        4 => 'Agree',
        5 => 'Strongly Agree'
    ];

    /**
     * Interpretation scale
     */
    private $interpretationScale = [
        ['min' => 4.21, 'max' => 5.00, 'label' => 'Very Satisfied', 'color' => 'success'],
        ['min' => 3.41, 'max' => 4.20, 'label' => 'Satisfied', 'color' => 'primary'],
        ['min' => 2.61, 'max' => 3.40, 'label' => 'Neutral', 'color' => 'warning'],
        ['min' => 1.81, 'max' => 2.60, 'label' => 'Dissatisfied', 'color' => 'danger'],
        ['min' => 1.00, 'max' => 1.80, 'label' => 'Very Dissatisfied', 'color' => 'dark']
    ];

    /**
     * Get all feedback analytics data
     * 
     * @param array $filters Optional filters (date range, counselor_id, etc.)
     * @return array Complete analytics data
     */
    public function getAnalytics(array $filters = []): array
    {
        $db = \Config\Database::connect();
        
        // Build base query with filters
        $builder = $db->table($this->table)->where('status', 'submitted');
        
        if (!empty($filters['counselor_id'])) {
            $builder->where('counselor_id', $filters['counselor_id']);
        }
        
        if (!empty($filters['start_date'])) {
            $builder->where('submitted_at >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $builder->where('submitted_at <=', $filters['end_date']);
        }
        
        $feedbackData = $builder->get()->getResultArray();
        $feedbackService = new FeedbackQuestionsService();
        $feedbackData = $feedbackService->enrichFeedbacksWithResponses($feedbackData);
        $questions = $this->getQuestionDefinitions();

        $analytics = [];
        $overallSum = 0;
        $overallCount = 0;
        
        foreach ($questions as $field => $label) {
            $questionAnalytics = $this->calculateQuestionStats($feedbackData, $field, $label);
            $analytics[$field] = $questionAnalytics;
            
            $overallSum += $questionAnalytics['weighted_mean'] * $questionAnalytics['total_responses'];
            $overallCount += $questionAnalytics['total_responses'];
        }
        
        // Calculate overall mean
        $overallMean = $overallCount > 0 ? $overallSum / $overallCount : 0;
        
        return [
            'questions' => $analytics,
            'overall_mean' => round($overallMean, 2),
            'overall_interpretation' => $this->getInterpretation($overallMean),
            'total_feedbacks' => count($feedbackData),
            'generated_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Calculate statistics for a single question
     * 
     * @param array $feedbackData All feedback records
     * @param string $field Question field name
     * @param string $label Question label
     * @return array Question statistics
     */
    private function calculateQuestionStats(array $feedbackData, string $field, string $label): array
    {
        // Initialize frequency counts
        $frequency = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];
        
        $sum = 0;
        $count = 0;
        
        foreach ($feedbackData as $feedback) {
            if (isset($feedback[$field]) && $feedback[$field] >= 1 && $feedback[$field] <= 5) {
                $value = (int)$feedback[$field];
                $frequency[$value]++;
                $sum += $value;
                $count++;
            }
        }
        
        // Calculate weighted mean
        $weightedMean = $count > 0 ? $sum / $count : 0;
        
        return [
            'field' => $field,
            'label' => $label,
            'frequency' => $frequency,
            'total_responses' => $count,
            'weighted_mean' => round($weightedMean, 2),
            'interpretation' => $this->getInterpretation($weightedMean)
        ];
    }

    /**
     * Get interpretation based on weighted mean
     * 
     * @param float $mean Weighted mean value
     * @return array Interpretation data
     */
    private function getInterpretation(float $mean): array
    {
        foreach ($this->interpretationScale as $range) {
            if ($mean >= $range['min'] && $mean <= $range['max']) {
                return [
                    'label' => $range['label'],
                    'color' => $range['color']
                ];
            }
        }
        
        // Default fallback
        return [
            'label' => 'Neutral',
            'color' => 'warning'
        ];
    }

    /**
     * Get question labels
     * 
     * @return array Question labels
     */
    public function getQuestionLabels(): array
    {
        return $this->getQuestionDefinitions();
    }

    /**
     * Get Likert scale labels
     * 
     * @return array Likert scale labels
     */
    public function getLikertScale(): array
    {
        return $this->likertScale;
    }

    /**
     * Get analytics for a specific counselor
     * 
     * @param string $counselorId
     * @return array Analytics data for counselor
     */
    public function getCounselorAnalytics(string $counselorId): array
    {
        return $this->getAnalytics(['counselor_id' => $counselorId]);
    }

    /**
     * Get analytics for a date range
     * 
     * @param string $startDate
     * @param string $endDate
     * @return array Analytics data for date range
     */
    public function getDateRangeAnalytics(string $startDate, string $endDate): array
    {
        return $this->getAnalytics([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    /**
     * Get category means (grouping related questions)
     * 
     * @param array $analytics Full analytics data
     * @return array Category means
     */
    public function getCategoryMeans(array $analytics): array
    {
        $categoryMeans = [];
        $questionStats = $analytics['questions'] ?? [];

        if ($questionStats === []) {
            return $categoryMeans;
        }

        $means = [];
        foreach ($questionStats as $field => $stats) {
            $means[$field] = (float) ($stats['weighted_mean'] ?? 0);
        }

        $values = array_values($means);
        $overall = count($values) > 0 ? array_sum($values) / count($values) : 0;

        $categoryMeans['All Questions'] = [
            'mean' => round($overall, 2),
            'interpretation' => $this->getInterpretation($overall),
            'question_count' => count($means),
        ];

        return $categoryMeans;
    }

    /**
     * Get trend data over time (monthly)
     * 
     * @param int $months Number of months to include
     * @return array Monthly trend data
     */
    public function getMonthlyTrend(int $months = 12): array
    {
        $db = \Config\Database::connect();
        
        $trend = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-{$i} months"));
            $startDate = $date . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $monthlyData = $this->getDateRangeAnalytics($startDate, $endDate);
            
            $trend[] = [
                'month' => date('F Y', strtotime($startDate)),
                'overall_mean' => $monthlyData['overall_mean'],
                'total_feedbacks' => $monthlyData['total_feedbacks']
            ];
        }
        
        return $trend;
    }

    /**
     * Get sentiment analysis statistics
     * 
     * @param array $filters Optional filters (counselor_id, date range)
     * @return array Sentiment statistics
     */
    public function getSentimentStatistics(array $filters = []): array
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table($this->table)->where('status', 'submitted');
        
        if (!empty($filters['counselor_id'])) {
            $builder->where('counselor_id', $filters['counselor_id']);
        }
        
        if (!empty($filters['start_date'])) {
            $builder->where('submitted_at >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $builder->where('submitted_at <=', $filters['end_date']);
        }
        
        $feedbackData = $builder->get()->getResultArray();
        
        $positive = 0;
        $negative = 0;
        $neutral = 0;
        $totalScore = 0;
        $scores = [];
        
        foreach ($feedbackData as $feedback) {
            $score = $feedback['sentiment_score'] ?? 0;
            $label = $feedback['sentiment_label'] ?? 'neutral';
            
            $scores[] = $score;
            $totalScore += $score;
            
            switch ($label) {
                case 'positive':
                    $positive++;
                    break;
                case 'negative':
                    $negative++;
                    break;
                case 'neutral':
                    $neutral++;
                    break;
            }
        }
        
        $total = count($feedbackData);
        
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
                'median' => $total > 0 ? $this->calculateMedian($scores) : 0
            ]
        ];
    }

    /**
     * Get sentiment trend over time (monthly)
     * 
     * @param int $months Number of months to include
     * @return array Monthly sentiment trend data
     */
    public function getSentimentTrend(int $months = 12): array
    {
        $trend = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-{$i} months"));
            $startDate = $date . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $monthlyData = $this->getSentimentStatistics([
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
            
            $trend[] = [
                'month' => date('F Y', strtotime($startDate)),
                'average_score' => $monthlyData['average_score'],
                'positive_percentage' => $monthlyData['positive_percentage'],
                'negative_percentage' => $monthlyData['negative_percentage'],
                'neutral_percentage' => $monthlyData['neutral_percentage'],
                'total_feedbacks' => $monthlyData['total']
            ];
        }
        
        return $trend;
    }

    /**
     * Get feedback with negative sentiment (for review)
     * 
     * @param array $filters Optional filters
     * @return array Negative feedback records
     */
    public function getNegativeFeedback(array $filters = []): array
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table($this->table)
            ->where('status', 'submitted')
            ->where('sentiment_label', 'negative');
        
        if (!empty($filters['counselor_id'])) {
            $builder->where('counselor_id', $filters['counselor_id']);
        }
        
        if (!empty($filters['start_date'])) {
            $builder->where('submitted_at >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $builder->where('submitted_at <=', $filters['end_date']);
        }
        
        return $builder->orderBy('submitted_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Calculate median of an array
     */
    private function calculateMedian(array $arr): float
    {
        sort($arr);
        $count = count($arr);
        $mid = floor($count / 2);
        
        if ($count % 2) {
            return $arr[$mid];
        } else {
            return ($arr[$mid - 1] + $arr[$mid]) / 2;
        }
    }
}
