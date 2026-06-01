<?php

namespace App\Services;

use App\Models\FollowUpAppointmentModel;
use Exception;

/**
 * Follow-Up Appointment Service
 * 
 * Handles follow-up appointment creation logic that was previously
 * implemented in MySQL triggers. This service replicates the exact
 * trigger behavior in CodeIgniter 4 application code.
 * 
 * TRIGGER: maintain_followup_sequence (BEFORE INSERT ON follow_up_appointments)
 */
class FollowUpAppointmentService
{
    protected FollowUpAppointmentModel $followUpAppointmentModel;

    public function __construct()
    {
        $this->followUpAppointmentModel = new FollowUpAppointmentModel();
    }

    /**
     * TRIGGER: maintain_followup_sequence (BEFORE INSERT ON follow_up_appointments)
     * Create a new follow-up appointment with automatic sequence calculation
     * 
     * Original trigger logic:
     * IF NEW.parent_appointment_id IS NOT NULL THEN
     *     SET NEW.follow_up_sequence = (
     *         SELECT COALESCE(MAX(follow_up_sequence), 0) + 1 
     *         FROM follow_up_appointments 
     *         WHERE parent_appointment_id = NEW.parent_appointment_id
     *     );
     * END IF;
     * 
     * @param array $data Follow-up appointment data
     * @return int The newly created follow-up appointment ID
     * @throws Exception If validation fails
     */
    public function createFollowUpAppointment(array $data): int
    {
        // Extract parent appointment ID
        $parentAppointmentId = $data['parent_appointment_id'] ?? null;

        // TRIGGER LOGIC: Only set sequence if parent_appointment_id is not NULL
        if ($parentAppointmentId !== null) {
            // Calculate next sequence number using the model's existing method
            // This replicates: SELECT COALESCE(MAX(follow_up_sequence), 0) + 1
            $nextSequence = $this->followUpAppointmentModel->getNextSequence($parentAppointmentId);
            
            // Set the follow_up_sequence in the data
            $data['follow_up_sequence'] = $nextSequence;
        }

        // Use transaction for atomic operation
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Insert the follow-up appointment
            $followUpId = $this->followUpAppointmentModel->insert($data);

            if ($followUpId === false) {
                throw new Exception('Failed to create follow-up appointment');
            }

            $db->transCommit();

            return $followUpId;

        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
