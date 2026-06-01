<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSentimentFieldsToFeedback extends Migration
{
    public function up()
    {
        $this->forge->addColumn('student_feedback', [
            'sentiment_score' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Sentiment score from -100 (very negative) to 100 (very positive)',
                'after' => 'additional_comments'
            ],
            'sentiment_label' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'neutral',
                'comment' => 'Sentiment label: positive, negative, or neutral',
                'after' => 'sentiment_score'
            ]
        ]);

        // Add index for faster sentiment-based queries
        $this->forge->addKey('sentiment_label');
    }

    public function down()
    {
        $this->forge->dropColumn('student_feedback', 'sentiment_score');
        $this->forge->dropColumn('student_feedback', 'sentiment_label');
    }
}
