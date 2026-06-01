<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFeedbackQuestionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'question_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'question_text' => [
                'type' => 'TEXT',
            ],
            'field_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('feedback_questions');
        
        // Insert default questions
        $data = [
            [
                'question_number' => 1,
                'question_text' => 'How easy was it to navigate the appointment scheduling system?',
                'field_name' => 'q1_ease_of_use',
                'sort_order' => 1,
            ],
            [
                'question_number' => 2,
                'question_text' => 'How satisfied are you with the overall counseling experience?',
                'field_name' => 'q2_satisfaction',
                'sort_order' => 2,
            ],
            [
                'question_number' => 3,
                'question_text' => 'How satisfied are you with the response time to your appointment request?',
                'field_name' => 'q3_timeliness',
                'sort_order' => 3,
            ],
            [
                'question_number' => 4,
                'question_text' => 'How clear was the information provided about counseling services?',
                'field_name' => 'q4_information_clarity',
                'sort_order' => 4,
            ],
            [
                'question_number' => 5,
                'question_text' => 'How helpful was the counseling staff in addressing your concerns?',
                'field_name' => 'q5_staff_helpfulness',
                'sort_order' => 5,
            ],
            [
                'question_number' => 6,
                'question_text' => 'How reliable was the technology used for online consultations?',
                'field_name' => 'q6_technology_reliability',
                'sort_order' => 6,
            ],
            [
                'question_number' => 7,
                'question_text' => 'How confident do you feel about the privacy of your personal information?',
                'field_name' => 'q7_privacy_confidence',
                'sort_order' => 7,
            ],
            [
                'question_number' => 8,
                'question_text' => 'How likely are you to recommend our counseling services to others?',
                'field_name' => 'q8_recommendation',
                'sort_order' => 8,
            ],
            [
                'question_number' => 9,
                'question_text' => 'How would you rate your overall experience with the counseling system?',
                'field_name' => 'q9_overall_experience',
                'sort_order' => 9,
            ],
            [
                'question_number' => 10,
                'question_text' => 'How likely are you to use our counseling services again in the future?',
                'field_name' => 'q10_future_use',
                'sort_order' => 10,
            ],
        ];
        
        $this->db->table('feedback_questions')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('feedback_questions');
    }
}
