<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentOptionModel extends Model
{
    protected $table = 'appointment_options';
    protected $primaryKey = 'id';
    protected $allowedFields = ['option_type', 'option_value', 'is_active'];
    protected $useTimestamps = true;

    public function ensureTableExists(): bool
    {
        $db = \Config\Database::connect();

        if ($db->tableExists($this->table)) {
            return false;
        }

        $forge = \Config\Database::forge();
        $forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'option_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'option_value' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
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
        $forge->addKey('id', true);
        $forge->addUniqueKey(['option_type', 'option_value']);
        $forge->createTable($this->table, true);
        return true;
    }

    public function seedDefaults(): void
    {
        $this->ensureTableExists();

        $defaults = [
            'method_type' => ['In-person', 'Online (Video)', 'Online (Audio only)'],
            'purpose' => ['Counseling', 'Psycho-Social Support', 'Initial Interview'],
        ];

        foreach ($defaults as $type => $values) {
            foreach ($values as $value) {
                $exists = $this->where('option_type', $type)
                    ->where('option_value', $value)
                    ->first();

                if (!$exists) {
                    $this->insert([
                        'option_type' => $type,
                        'option_value' => $value,
                        'is_active' => 1,
                    ]);
                }
            }
        }
    }

    public function getOptionsByType(string $type): array
    {
        $wasCreated = $this->ensureTableExists();
        if ($wasCreated) {
            $this->seedDefaults();
        }

        return $this->where('option_type', $type)
            ->where('is_active', 1)
            ->orderBy('option_value', 'ASC')
            ->findAll();
    }
}
