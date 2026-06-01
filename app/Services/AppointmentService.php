<?php

namespace App\Services;

use App\Models\AppointmentModel;
use Exception;

/**
 * Appointment Service
 * 
 * Handles appointment creation and update logic that was previously
 * implemented in MySQL triggers. This service replicates the exact
 * trigger behavior in CodeIgniter 4 application code.
 * 
 * TRIGGER 1: BEFORE INSERT ON appointments
 * TRIGGER 2: BEFORE UPDATE ON appointments
 */
class AppointmentService
{
    protected AppointmentModel $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * TRIGGER 1: BEFORE INSERT ON appointments
     * Create a new appointment with trigger validation
     * 
     * Original trigger logic:
     * 1. Before inserting a new appointment:
     *    - Check appointments table for records where:
     *      - counselor_preference = NEW.counselor_preference
     *      - preferred_date = NEW.preferred_date
     *      - preferred_time = NEW.preferred_time
     *      - status IN ('pending', 'approved')
     *      - counselor_preference != 'No preference'
     * 2. If any record exists (COUNT > 0):
     *    - Stop the insert
     *    - Return: "Counselor already has an appointment at this time"
     * 
     * @param array $data Appointment data
     * @return int The newly created appointment ID
     * @throws Exception If validation fails
     */
    public function createAppointment(array $data): int
    {
        // Extract required fields for trigger validation
        $counselorPreference = $data['counselor_preference'] ?? 'No preference';
        $preferredDate = $data['preferred_date'];
        $preferredTime = $data['preferred_time'];

        // TRIGGER 1 VALIDATION: Check if counselor already has an appointment at this time
        if ($counselorPreference !== 'No preference') {
            $conflictCount = $this->appointmentModel->countCounselorConflictsForInsert(
                $counselorPreference,
                $preferredDate,
                $preferredTime
            );

            if ($conflictCount > 0) {
                // Exact trigger error message
                throw new Exception("Counselor already has an appointment at this time");
            }
        }

        // Use transaction for atomic operation
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Insert the appointment
            $appointmentId = $this->appointmentModel->insert($data);

            if ($appointmentId === false) {
                throw new Exception('Failed to create appointment');
            }

            $db->transCommit();

            return $appointmentId;

        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    /**
     * TRIGGER 2: BEFORE UPDATE ON appointments
     * Update an appointment with trigger validation
     * 
     * Original trigger logic:
     * Only run validation if any of these fields changed:
     * - counselor_preference
     * - preferred_date
     * - preferred_time
     * - consultation_type
     * 
     * For Individual Consultation:
     * 1. Check appointments table where:
     *    - counselor_preference = NEW.counselor_preference
     *    - preferred_date = NEW.preferred_date
     *    - preferred_time = NEW.preferred_time
     *    - status IN ('pending', 'approved')
     *    - counselor_preference != 'No preference'
     *    - id != current appointment id
     * 2. If count > 0:
     *    - Stop update
     *    - Return: "This time slot is already booked. Individual consultations require exclusive time slots."
     * 
     * For Group Consultation:
     * 1. Check for existing Individual Consultation where:
     *    - counselor_preference = NEW.counselor_preference
     *    - preferred_date = NEW.preferred_date
     *    - preferred_time = NEW.preferred_time
     *    - consultation_type = 'Individual Consultation'
     *    - status IN ('pending', 'approved')
     *    - counselor_preference != 'No preference'
     *    - id != current appointment id
     * 2. If count > 0:
     *    - Stop update
     *    - Return: "This time slot is already booked for individual consultation. Group consultations cannot share time slots with individual consultations."
     * 3. Count existing Group Consultations where:
     *    - counselor_preference = NEW.counselor_preference
     *    - preferred_date = NEW.preferred_date
     *    - preferred_time = NEW.preferred_time
     *    - consultation_type = 'Group Consultation'
     *    - status IN ('pending', 'approved')
     *    - counselor_preference != 'No preference'
     *    - id != current appointment id
     * 4. If group count >= 5:
     *    - Stop update
     *    - Return: "Group consultation slots are full for this time slot (maximum 5 participants)."
     * 
     * @param int $id Appointment ID to update
     * @param array $data Updated appointment data
     * @return bool True if update successful
     * @throws Exception If validation fails
     */
    public function updateAppointment(int $id, array $data): bool
    {
        // Get current appointment data for comparison
        $currentAppointment = $this->appointmentModel->getAppointmentForUpdate($id);

        if (!$currentAppointment) {
            throw new Exception('Appointment not found');
        }

        // TRIGGER 2: Only run validation if specific fields changed
        $fieldsToCheck = ['counselor_preference', 'preferred_date', 'preferred_time', 'consultation_type'];
        $shouldValidate = false;

        foreach ($fieldsToCheck as $field) {
            if (isset($data[$field]) && $data[$field] !== $currentAppointment[$field]) {
                $shouldValidate = true;
                break;
            }
        }

        // If none of the monitored fields changed, proceed with update
        if (!$shouldValidate) {
            return $this->appointmentModel->update($id, $data);
        }

        // Merge current data with new data for validation
        $newData = array_merge($currentAppointment, $data);
        $counselorPreference = $newData['counselor_preference'];
        $preferredDate = $newData['preferred_date'];
        $preferredTime = $newData['preferred_time'];
        $consultationType = $newData['consultation_type'] ?? null;

        // TRIGGER 2 VALIDATION: Only validate if counselor_preference is not 'No preference'
        if ($counselorPreference !== 'No preference') {
            // For Individual Consultation
            if ($consultationType === 'Individual Consultation') {
                $conflictCount = $this->appointmentModel->countIndividualConsultationConflicts(
                    $id,
                    $counselorPreference,
                    $preferredDate,
                    $preferredTime
                );

                if ($conflictCount > 0) {
                    // Exact trigger error message
                    throw new Exception("This time slot is already booked. Individual consultations require exclusive time slots.");
                }
            }
            // For Group Consultation
            elseif ($consultationType === 'Group Consultation') {
                // Check for existing Individual Consultation at this time slot
                $individualConflictCount = $this->appointmentModel->countIndividualConsultationConflictsForGroup(
                    $id,
                    $counselorPreference,
                    $preferredDate,
                    $preferredTime
                );

                if ($individualConflictCount > 0) {
                    // Exact trigger error message
                    throw new Exception("This time slot is already booked for individual consultation. Group consultations cannot share time slots with individual consultations.");
                }

                // Count existing Group Consultations
                $groupCount = $this->appointmentModel->countGroupConsultations(
                    $id,
                    $counselorPreference,
                    $preferredDate,
                    $preferredTime
                );

                if ($groupCount >= 5) {
                    // Exact trigger error message
                    throw new Exception("Group consultation slots are full for this time slot (maximum 5 participants).");
                }
            }
        }

        // Use transaction for atomic operation
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Update the appointment
            $result = $this->appointmentModel->update($id, $data);

            if ($result === false) {
                throw new Exception('Failed to update appointment');
            }

            $db->transCommit();

            return true;

        } catch (Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
