<?php

namespace App\Services;

use Config\Database;

/**
 * Manages feedback_questions table setup, seeding, and response storage.
 */
class FeedbackQuestionsService
{
    /** Legacy student_feedback column names (q1–q10). */
    public const LEGACY_FIELDS = [
        'q1_ease_of_use',
        'q2_satisfaction',
        'q3_timeliness',
        'q4_information_clarity',
        'q5_staff_helpfulness',
        'q6_technology_reliability',
        'q7_privacy_confidence',
        'q8_recommendation',
        'q9_overall_experience',
        'q10_future_use',
    ];

    public function ensureTables(): void
    {
        $db = Database::connect();
        $forge = Database::forge();

        if (!$db->tableExists('feedback_questions')) {
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'question_number' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'question_text' => ['type' => 'TEXT'],
                'field_name' => ['type' => 'VARCHAR', 'constraint' => 100],
                'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
                'sort_order' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'default' => 0],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
                'updated_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->addUniqueKey('field_name');
            $forge->createTable('feedback_questions', true);
        }

        if (!$db->tableExists('student_feedback_responses')) {
            $forge->addField([
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'feedback_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'question_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'field_name' => ['type' => 'VARCHAR', 'constraint' => 100],
                'rating' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $forge->addKey('id', true);
            $forge->addKey(['feedback_id', 'question_id']);
            $forge->createTable('student_feedback_responses', true);
        }

        if ($db->table('feedback_questions')->countAllResults() === 0) {
            $this->seedDefaultQuestions();
        }
    }

    public function seedDefaultQuestions(): void
    {
        $db = Database::connect();
        $defaults = [
            [1, 'How easy was it to navigate the appointment scheduling system?', 'q1_ease_of_use', 1],
            [2, 'How satisfied are you with the overall counseling experience?', 'q2_satisfaction', 2],
            [3, 'How satisfied are you with the response time to your appointment request?', 'q3_timeliness', 3],
            [4, 'How clear was the information provided about counseling services?', 'q4_information_clarity', 4],
            [5, 'How helpful was the counseling staff in addressing your concerns?', 'q5_staff_helpfulness', 5],
            [6, 'How reliable was the technology used for online consultations?', 'q6_technology_reliability', 6],
            [7, 'How confident do you feel about the privacy of your personal information?', 'q7_privacy_confidence', 7],
            [8, 'How likely are you to recommend our counseling services to others?', 'q8_recommendation', 8],
            [9, 'How would you rate your overall experience with the counseling system?', 'q9_overall_experience', 9],
            [10, 'How likely are you to use our counseling services again in the future?', 'q10_future_use', 10],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($defaults as [$num, $text, $field, $order]) {
            $exists = $db->table('feedback_questions')->where('field_name', $field)->countAllResults();
            if ($exists > 0) {
                continue;
            }
            $db->table('feedback_questions')->insert([
                'question_number' => $num,
                'question_text' => $text,
                'field_name' => $field,
                'is_active' => 1,
                'sort_order' => $order,
                'created_at' => $now,
            ]);
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAllQuestions(): array
    {
        $this->ensureTables();

        return Database::connect()
            ->table('feedback_questions')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('question_number', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getActiveQuestions(): array
    {
        $this->ensureTables();

        return Database::connect()
            ->table('feedback_questions')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('question_number', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function generateFieldName(string $questionText, int $questionNumber): string
    {
        $slug = strtolower(trim($questionText));
        $slug = preg_replace('/[^a-z0-9]+/', '_', $slug) ?? '';
        $slug = trim($slug, '_');
        if ($slug === '') {
            $slug = 'question';
        }
        $slug = substr($slug, 0, 60);

        return 'q' . $questionNumber . '_' . $slug;
    }

    public function isLegacyField(string $fieldName): bool
    {
        return in_array($fieldName, self::LEGACY_FIELDS, true);
    }

    /**
     * @return list<string>
     */
    public function getStudentFeedbackTableColumns(): array
    {
        $db = Database::connect();
        if (!$db->tableExists('student_feedback')) {
            return [];
        }

        return $db->getFieldNames('student_feedback');
    }

    /**
     * Persist per-question ratings and map legacy columns when they exist.
     *
     * @param list<array<string, mixed>> $questions
     * @param array<string, int>         $ratings field_name => rating
     */
    public function saveResponses(int $feedbackId, array $questions, array $ratings): void
    {
        $db = Database::connect();
        $legacyColumns = $this->getStudentFeedbackTableColumns();
        $legacyUpdate = [];

        $rows = [];
        $now = date('Y-m-d H:i:s');

        foreach ($questions as $question) {
            $fieldName = (string) $question['field_name'];
            if (!isset($ratings[$fieldName])) {
                continue;
            }
            $rating = (int) $ratings[$fieldName];

            $rows[] = [
                'feedback_id' => $feedbackId,
                'question_id' => (int) $question['id'],
                'field_name' => $fieldName,
                'rating' => $rating,
                'created_at' => $now,
            ];

            if (in_array($fieldName, $legacyColumns, true)) {
                $legacyUpdate[$fieldName] = $rating;
            }
        }

        if ($rows !== []) {
            $db->table('student_feedback_responses')->insertBatch($rows);
        }

        if ($legacyUpdate !== []) {
            $db->table('student_feedback')->where('id', $feedbackId)->update($legacyUpdate);
        }
    }

    /**
     * Merge response rows into feedback records for display/analytics.
     *
     * @param list<array<string, mixed>> $feedbacks
     * @return list<array<string, mixed>>
     */
    public function enrichFeedbacksWithResponses(array $feedbacks): array
    {
        if ($feedbacks === []) {
            return $feedbacks;
        }

        $db = Database::connect();
        if (!$db->tableExists('student_feedback_responses')) {
            return $feedbacks;
        }

        $ids = array_column($feedbacks, 'id');
        $responses = $db->table('student_feedback_responses')
            ->whereIn('feedback_id', $ids)
            ->get()
            ->getResultArray();

        $byFeedback = [];
        foreach ($responses as $row) {
            $byFeedback[$row['feedback_id']][$row['field_name']] = (int) $row['rating'];
        }

        foreach ($feedbacks as &$feedback) {
            $fid = $feedback['id'];
            if (!isset($byFeedback[$fid])) {
                continue;
            }
            foreach ($byFeedback[$fid] as $field => $rating) {
                $feedback[$field] = $rating;
            }
        }

        return $feedbacks;
    }

    /**
     * Average rating across active questions for one feedback row.
     *
     * @param array<string, mixed> $feedback
     */
    public function calculateAverageRating(array $feedback, ?array $activeQuestions = null): float
    {
        $activeQuestions = $activeQuestions ?? $this->getActiveQuestions();
        $total = 0;
        $count = 0;

        foreach ($activeQuestions as $question) {
            $field = (string) $question['field_name'];
            if (isset($feedback[$field]) && is_numeric($feedback[$field])) {
                $total += (float) $feedback[$field];
                $count++;
            }
        }

        return $count > 0 ? round($total / $count, 2) : 0.0;
    }

    /**
     * @return array<string, string> field_name => question_text
     */
    public function getQuestionDefinitionsForAnalytics(): array
    {
        $questions = $this->getActiveQuestions();
        $map = [];
        foreach ($questions as $q) {
            $map[(string) $q['field_name']] = (string) $q['question_text'];
        }

        if ($map !== []) {
            return $map;
        }

        // Fallback if table empty
        return array_combine(self::LEGACY_FIELDS, [
            'How easy was it to navigate the appointment scheduling system?',
            'How satisfied are you with the overall counseling experience?',
            'How satisfied are you with the response time to your appointment request?',
            'How clear was the information provided about counseling services?',
            'How helpful was the counseling staff in addressing your concerns?',
            'How reliable was the technology used for online consultations?',
            'How confident do you feel about the privacy of your personal information?',
            'How likely are you to recommend our counseling services to others?',
            'How would you rate your overall experience with the counseling system?',
            'How likely are you to use our counseling services again in the future?',
        ]) ?: [];
    }
}
