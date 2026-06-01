<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentFeedbackResponsesTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('student_feedback_responses')) {
            return;
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'feedback_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'question_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'field_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['feedback_id', 'question_id']);
        $this->forge->addForeignKey('feedback_id', 'student_feedback', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('question_id', 'feedback_questions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_feedback_responses');
    }

    public function down()
    {
        $this->forge->dropTable('student_feedback_responses', true);
    }
}
