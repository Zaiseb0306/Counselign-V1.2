<?php

namespace App\Controllers;

use App\Services\FollowUpAppointmentService;
use Exception;

/**
 * Example Controller Integration for Follow-Up Appointments
 * 
 * This example demonstrates how to integrate the FollowUpAppointmentService
 * into your controllers to replace the maintain_followup_sequence MySQL trigger.
 * 
 * IMPORTANT: Remove your MySQL trigger after implementing this service.
 * 
 * To drop the trigger, run:
 * DROP TRIGGER IF EXISTS maintain_followup_sequence;
 */
class ExampleFollowUpAppointmentController extends BaseController
{
    protected FollowUpAppointmentService $followUpAppointmentService;

    public function __construct()
    {
        $this->followUpAppointmentService = new FollowUpAppointmentService();
    }

    /**
     * Create a new follow-up appointment
     * 
     * This method replaces the maintain_followup_sequence trigger logic.
     * The service will automatically calculate and set the follow_up_sequence
     * based on the parent appointment's existing follow-ups.
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        // Get follow-up appointment data from request
        $data = [
            'counselor_id'          => $this->request->getPost('counselor_id'),
            'student_id'            => $this->request->getPost('student_id'),
            'parent_appointment_id' => $this->request->getPost('parent_appointment_id'),
            'preferred_date'        => $this->request->getPost('preferred_date'),
            'preferred_time'        => $this->request->getPost('preferred_time'),
            'consultation_type'     => $this->request->getPost('consultation_type'),
            'description'           => $this->request->getPost('description'),
            'reason'                => $this->request->getPost('reason'),
            'status'                => 'pending' // Default status
        ];

        try {
            // Use FollowUpAppointmentService instead of direct model insert
            // This will automatically calculate follow_up_sequence
            // TRIGGER LOGIC: If parent_appointment_id is not NULL,
            // set follow_up_sequence = MAX(existing) + 1 for that parent
            $followUpId = $this->followUpAppointmentService->createFollowUpAppointment($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Follow-up appointment created successfully',
                'follow_up_id' => $followUpId,
                'follow_up_sequence' => $data['follow_up_sequence'] ?? null
            ]);

        } catch (Exception $e) {
            // Handle validation errors
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Alternative: Integration with existing controller methods
     * 
     * If you have existing controller methods, you can integrate
     * the service by replacing direct model calls.
     * 
     * BEFORE (with trigger):
     * ```php
     * public function scheduleFollowUp()
     * {
     *     $data = $this->request->getPost();
     *     // follow_up_sequence would be set automatically by trigger
     *     $followUpModel = new FollowUpAppointmentModel();
     *     $followUpId = $followUpModel->insert($data);
     * }
     * ```
     * 
     * AFTER (with service):
     * ```php
     * public function scheduleFollowUp()
     * {
     *     $data = $this->request->getPost();
     *     // follow_up_sequence is calculated by the service
     *     $followUpService = new FollowUpAppointmentService();
     *     $followUpId = $followUpService->createFollowUpAppointment($data);
     * }
     * ```
     * 
     * Note: You don't need to pass follow_up_sequence in the data array.
     * The service will calculate it automatically if parent_appointment_id is provided.
     */
}
