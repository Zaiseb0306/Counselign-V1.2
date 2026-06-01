<?php

namespace App\Controllers;

use App\Services\AppointmentService;
use Exception;

/**
 * Example Controller Integration
 * 
 * This example demonstrates how to integrate the AppointmentService
 * into your controllers to replace MySQL trigger logic.
 * 
 * IMPORTANT: Remove your MySQL triggers after implementing this service.
 * 
 * To drop the triggers, run:
 * DROP TRIGGER IF EXISTS before_insert_appointments;
 * DROP TRIGGER IF EXISTS before_update_appointments;
 */
class ExampleAppointmentController extends BaseController
{
    protected AppointmentService $appointmentService;

    public function __construct()
    {
        $this->appointmentService = new AppointmentService();
    }

    /**
     * Create a new appointment
     * 
     * This method replaces the BEFORE INSERT trigger logic.
     * The service will validate that the counselor doesn't already
     * have an appointment at the requested time.
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        // Get appointment data from request
        $data = [
            'student_id'          => $this->request->getPost('student_id'),
            'preferred_date'      => $this->request->getPost('preferred_date'),
            'preferred_time'      => $this->request->getPost('preferred_time'),
            'method_type'         => $this->request->getPost('method_type'),
            'consultation_type'   => $this->request->getPost('consultation_type'),
            'counselor_preference'=> $this->request->getPost('counselor_preference') ?? 'No preference',
            'description'         => $this->request->getPost('description'),
            'reason'              => $this->request->getPost('reason'),
            'purpose'             => $this->request->getPost('purpose'),
            'status'              => 'pending' // Default status
        ];

        try {
            // Use AppointmentService instead of direct model insert
            // This will execute TRIGGER 1 validation logic
            $appointmentId = $this->appointmentService->createAppointment($data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appointment created successfully',
                'appointment_id' => $appointmentId
            ]);

        } catch (Exception $e) {
            // Handle trigger validation errors
            // The exact trigger error messages will be caught here
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update an existing appointment
     * 
     * This method replaces the BEFORE UPDATE trigger logic.
     * The service will validate that:
     * - Individual consultations have exclusive time slots
     * - Group consultations don't conflict with individual consultations
     * - Group consultations don't exceed 5 participants per time slot
     * 
     * @param int $id Appointment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get updated appointment data from request
        $data = [
            'preferred_date'      => $this->request->getPost('preferred_date'),
            'preferred_time'      => $this->request->getPost('preferred_time'),
            'consultation_type'   => $this->request->getPost('consultation_type'),
            'counselor_preference'=> $this->request->getPost('counselor_preference'),
            'description'         => $this->request->getPost('description'),
            'reason'              => $this->request->getPost('reason'),
        ];

        // Remove null values to avoid overwriting with null
        $data = array_filter($data, function($value) {
            return $value !== null;
        });

        try {
            // Use AppointmentService instead of direct model update
            // This will execute TRIGGER 2 validation logic
            $this->appointmentService->updateAppointment($id, $data);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Appointment updated successfully'
            ]);

        } catch (Exception $e) {
            // Handle trigger validation errors
            // The exact trigger error messages will be caught here:
            // - "This time slot is already booked. Individual consultations require exclusive time slots."
            // - "This time slot is already booked for individual consultation. Group consultations cannot share time slots with individual consultations."
            // - "Group consultation slots are full for this time slot (maximum 5 participants)."
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
     * BEFORE (with triggers):
     * ```php
     * public function bookAppointment()
     * {
     *     $data = $this->request->getPost();
     *     $appointmentModel = new AppointmentModel();
     *     $appointmentId = $appointmentModel->insert($data);
     *     // Trigger would automatically validate
     * }
     * ```
     * 
     * AFTER (with service):
     * ```php
     * public function bookAppointment()
     * {
     *     $data = $this->request->getPost();
     *     $appointmentService = new AppointmentService();
     *     $appointmentId = $appointmentService->createAppointment($data);
     *     // Service validates instead of trigger
     * }
     * ```
     */
}
