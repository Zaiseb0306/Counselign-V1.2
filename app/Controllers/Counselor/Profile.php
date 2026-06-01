<?php

namespace App\Controllers\Counselor;


use App\Helpers\SecureLogHelper;
use App\Models\UserModel;
use App\Helpers\UserActivityHelper;
use App\Controllers\BaseController;

class Profile extends BaseController
{
    /**
     * Resolve business user_id (e.g. COUN-2025-xxxx) from session.
     */
    private function resolveCounselorUserId(): ?string
    {
        $session = session();
        $userId = $session->get('user_id_display');
        if (!empty($userId)) {
            return $userId;
        }

        $numericId = $session->get('user_id');
        if (empty($numericId)) {
            return null;
        }

        $user = (new UserModel())->find($numericId);
        return $user['user_id'] ?? null;
    }

    private function ensureCounselorAccess(): ?\CodeIgniter\HTTP\ResponseInterface
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') !== 'counselor') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized access',
            ])->setStatusCode(403);
        }

        return null;
    }

    private function normalizeProfilePictureUrl(?string $path): string
    {
        if (empty($path)) {
            return base_url('Photos/profile.png');
        }
        if (strpos($path, 'http') === 0) {
            return $path;
        }

        return base_url('/' . ltrim($path, '/'));
    }

    private function emailExistsForAnotherAccount(string $email, string $userId): bool
    {
        $db = \Config\Database::connect();

        if ($db->table('users')->where('email', $email)->where('user_id !=', $userId)->countAllResults(false) > 0) {
            return true;
        }

        $counselorFields = $db->getFieldNames('counselors');
        if (in_array('email', $counselorFields, true)) {
            return $db->table('counselors')
                ->where('email', $email)
                ->where('counselor_id !=', $userId)
                ->countAllResults(false) > 0;
        }

        return false;
    }

    /**
     * Get counselor profile data for sidebar/dashboard
     * Compatible with universal sidebar.js
     */
    public function get()
    {
        $session = session();

        if (!$session->get('logged_in') || $session->get('role') !== 'counselor') {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Unauthorized access'
            ])->setStatusCode(401);
        }

        $user_id = $session->get('user_id_display') ?? $session->get('user_id');
        if (!$user_id) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Invalid session data'
            ])->setStatusCode(400);
        }

        try {
            $db = \Config\Database::connect();
            
            // Get user data from users table
            $builder = $db->table('users');
            $builder->select('users.user_id, users.username, users.email, users.profile_picture, users.last_login');
            $builder->where('users.user_id', $user_id);
            $query = $builder->get();
            
            if ($user = $query->getRowArray()) {
                // Try to get counselor info for full name
                $counselorBuilder = $db->table('counselors');
                $counselorBuilder->select('name, degree, contact_number, address');
                $counselorBuilder->where('counselor_id', $user_id);
                $counselorInfo = $counselorBuilder->get()->getRowArray();
                
                // Add counselor-specific fields if available
                if ($counselorInfo) {
                    // Use 'name' field as the counselor's full name
                    $user['name'] = $counselorInfo['name'] ?? '';
                    
                    // Create full_name as alias for compatibility
                    if (!empty($counselorInfo['name']) && $counselorInfo['name'] !== 'N/A') {
                        $user['full_name'] = $counselorInfo['name'];
                    }
                    
                    // Add additional counselor details
                    $user['degree'] = $counselorInfo['degree'] ?? '';
                    $user['contact_number'] = $counselorInfo['contact_number'] ?? '';
                    $user['address'] = $counselorInfo['address'] ?? '';
                }
                
                // Add user_id_display for clarity
                $user['user_id_display'] = $session->get('user_id_display') ?? $user['user_id'];
                
                // Normalize profile picture URL
                if (!empty($user['profile_picture'])) {
                    // If it's already a full URL, keep it as is
                    if (strpos($user['profile_picture'], 'http') !== 0) {
                        // Make it a full URL
                        $relativePath = '/' . ltrim($user['profile_picture'], '/');
                        $user['profile_picture'] = base_url($relativePath);
                    }
                } else {
                    // Fallback to default profile picture
                    $user['profile_picture'] = base_url('Photos/profile.png');
                }
                
                log_message('debug', 'Counselor profile data fetched for sidebar: ' . json_encode($user));
                
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $user
                ]);
            } else {
                log_message('error', 'No counselor found with user_id: ' . $user_id);
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'User data not found'
                ])->setStatusCode(404);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Counselor profile error for sidebar: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Database error'
            ])->setStatusCode(500);
        }
    }

    /**
     * Get full counselor profile data (legacy method, kept for compatibility)
     */
    public function getProfile()
    {
        if ($denied = $this->ensureCounselorAccess()) {
            return $denied;
        }

        $user_id = $this->resolveCounselorUserId();
        if (!$user_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid session data'])->setStatusCode(400);
        }

        $userModel = new UserModel();
        $user = $userModel->where('user_id', $user_id)->first();

        if ($user) {
            $db = \Config\Database::connect();
            $counselor = $db->table('counselors')->where('counselor_id', $user['user_id'])->get()->getRowArray();

            $displayName = '';
            if ($counselor && !empty($counselor['name']) && $counselor['name'] !== 'N/A') {
                $displayName = $counselor['name'];
            }

            return $this->response->setJSON([
                'success' => true,
                'user_id' => $user['user_id'],
                'username' => $user['username'] ?? '',
                'email' => $user['email'],
                'name' => $displayName,
                'full_name' => $displayName,
                'role' => $user['role'],
                'last_login' => $user['last_login'] ?? null,
                'profile_picture' => $this->normalizeProfilePictureUrl($user['profile_picture'] ?? null),
                'counselor' => $counselor,
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'User not found'])->setStatusCode(404);
    }

    public function profile()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'counselor') {
            return redirect()->to('/');
        }

        return view('counselor/counselor_profile');
    }

    public function updatePersonalInfo()
    {
        if (strtolower($this->request->getMethod()) === 'options') {
            return $this->response->setJSON(['success' => true]);
        }

        if ($denied = $this->ensureCounselorAccess()) {
            return $denied;
        }

        $userId = $this->resolveCounselorUserId();
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid session data'])->setStatusCode(400);
        }

        $post = $this->request->getPost();

        $incoming = [
            'name' => trim($post['fullname'] ?? ''),
            'degree' => trim($post['degree'] ?? ''),
            'email' => trim($post['email'] ?? ''),
            'contact_number' => trim($post['contact'] ?? ''),
            'address' => trim($post['address'] ?? ''),
            'birthdate' => trim($post['birthdate'] ?? ''),
            'sex' => trim($post['sex'] ?? ''),
            'civil_status' => trim($post['civil_status'] ?? ''),
        ];

        // Enhanced validation
        $validationErrors = [];
        
        // Email validation
        if (!empty($post['email']) && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $validationErrors[] = 'Invalid email format';
        }
        
        if (!empty($post['email']) && $post['email'] !== 'N/A' && $this->emailExistsForAnotherAccount($post['email'], $userId)) {
            $validationErrors[] = 'This email is already registered to another account';
        }
        
        // Date validation for birthdate
        if (!empty($post['birthdate']) && $post['birthdate'] !== 'N/A') {
            $birthdate = \DateTime::createFromFormat('Y-m-d', $post['birthdate']);
            if (!$birthdate || $birthdate->format('Y-m-d') !== $post['birthdate']) {
                $validationErrors[] = 'Invalid birthdate format';
            }
        }
        
        // Contact number validation (basic format check)
        if (!empty($post['contact']) && $post['contact'] !== 'N/A') {
            $contact = preg_replace('/[^0-9+]/', '', $post['contact']);
            if (strlen($contact) < 10) {
                $validationErrors[] = 'Contact number must be at least 10 digits';
            }
        }
        
        if (!empty($validationErrors)) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => implode(', ', $validationErrors)
            ]);
        }

        $db = \Config\Database::connect();

        // Filter to existing columns to avoid SQL errors and handle default values
        $fieldNames = $db->getFieldNames('counselors');
        $data = [];
        foreach ($incoming as $key => $value) {
            if (in_array($key, $fieldNames, true)) {
                // Store 'N/A' values for first-time users, but skip empty strings for optional fields
                if ($value !== '' || $value === 'N/A') {
                    $data[$key] = $value;
                }
            }
        }

        try {
            $exists = $db->table('counselors')->where('counselor_id', $userId)->countAllResults(false) > 0;

            if ($exists) {
                $db->table('counselors')->where('counselor_id', $userId)->update($data);
                log_message('debug', 'Counselor personal info updated for user: ' . $userId);
            } else {
                $data['counselor_id'] = $userId;
                $db->table('counselors')->insert($data);
                log_message('debug', 'Counselor personal info inserted for user: ' . $userId);
            }

            if (!empty($incoming['email']) && $incoming['email'] !== 'N/A' && in_array('email', $fieldNames, true)) {
                $userModel = new UserModel();
                $user = $userModel->where('user_id', $userId)->first();
                if ($user) {
                    $userModel->skipValidation(true)->update($user['id'], ['email' => $incoming['email']]);
                    session()->set('email', $incoming['email']);
                }
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Counselor personal info save error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Database error occurred while saving personal information.'
            ]);
        }
    }

    public function updateProfile()
    {
        if ($denied = $this->ensureCounselorAccess()) {
            return $denied;
        }

        $user_id = $this->resolveCounselorUserId();
        if (!$user_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid session data'])->setStatusCode(400);
        }

        $field = $this->request->getPost('field');
        if ($field !== null && $field !== '') {
            return $this->updateAccountField($user_id, $field, trim((string) $this->request->getPost('value')));
        }

        $username = trim((string) $this->request->getPost('username'));
        $email = trim((string) $this->request->getPost('email'));

        if ($username === '' || $email === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid email format']);
        }

        $userModel = new UserModel();
        try {
            $user = $userModel->where('user_id', $user_id)->first();
            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
            }

            if ($user['email'] !== $email && $this->emailExistsForAnotherAccount($email, $user_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This email is already registered to another account',
                ]);
            }

            $userModel->skipValidation(true)->update($user['id'], [
                'username' => $username,
                'email' => $email,
            ]);

            $db = \Config\Database::connect();
            $counselorFields = $db->getFieldNames('counselors');
            if (in_array('email', $counselorFields, true)) {
                $counselorRow = $db->table('counselors')->where('counselor_id', $user_id)->get()->getRowArray();
                if ($counselorRow) {
                    $db->table('counselors')->where('counselor_id', $user_id)->update(['email' => $email]);
                }
            }

            $activityHelper = new UserActivityHelper();
            $activityHelper->updateCounselorActivity($user_id, 'update_profile');

            session()->set('username', $username);
            session()->set('email', $email);

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Counselor updateProfile error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

    /**
     * Single-field update (admin account-settings style).
     */
    private function updateAccountField(string $userId, string $field, string $value)
    {
        $allowed = ['username', 'email'];
        if (!in_array($field, $allowed, true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid field'])->setStatusCode(400);
        }
        if ($value === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Value is required']);
        }
        if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid email format']);
        }

        $userModel = new UserModel();
        try {
            $user = $userModel->where('user_id', $userId)->first();
            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
            }

            if ($field === 'email' && $user['email'] !== $value && $this->emailExistsForAnotherAccount($value, $userId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This email is already registered to another account',
                ]);
            }

            $userModel->skipValidation(true)->update($user['id'], [$field => $value]);

            if ($field === 'email') {
                $db = \Config\Database::connect();
                $counselorFields = $db->getFieldNames('counselors');
                if (in_array('email', $counselorFields, true)) {
                    $counselorRow = $db->table('counselors')->where('counselor_id', $userId)->get()->getRowArray();
                    if ($counselorRow) {
                        $db->table('counselors')->where('counselor_id', $userId)->update(['email' => $value]);
                    }
                }
            }

            $activityHelper = new UserActivityHelper();
            $activityHelper->updateCounselorActivity($userId, 'update_profile');

            session()->set($field, $value);

            return $this->response->setJSON([
                'success' => true,
                'message' => ucfirst($field) . ' updated successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Counselor updateAccountField error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

    public function updateProfilePicture()
    {
        $session = session();

        if (!$session->get('logged_in') || $session->get('role') !== 'counselor') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized access'])->setStatusCode(403);
        }

        // Allow OPTIONS preflight (and simply return OK)
        if (strtolower($this->request->getMethod()) === 'options') {
            return $this->response->setJSON(['success' => true]);
        }
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid method'])->setStatusCode(405);
        }

        try {
            $userId = $this->resolveCounselorUserId();
            if (!$userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid session data'])->setStatusCode(400);
            }
            $file = $this->request->getFile('profile_picture');
            if (!$file) {
                return $this->response->setJSON(['success' => false, 'message' => 'No file received']);
            }
            if (!$file->isValid()) {
                return $this->response->setJSON(['success' => false, 'message' => 'File upload error: ' . $file->getErrorString()]);
            }

            $ext = strtolower($file->getExtension());
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($ext, $allowed)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid file type. Allowed: JPG, JPEG, PNG, GIF']);
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return $this->response->setJSON(['success' => false, 'message' => 'File too large. Max 5MB']);
            }

            $uploadDir = FCPATH . 'Photos/profile_pictures/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = 'counselor_' . $userId . '_' . time() . '.' . $ext;
            $relativePath = 'Photos/profile_pictures/' . $newFileName;

            // Remove old picture if any and not default
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('user_id', $userId)->first();
            if ($user && !empty($user['profile_picture']) && $user['profile_picture'] !== 'Photos/profile.png') {
                $oldPath = FCPATH . str_replace(['..', './', '\\'], '', $user['profile_picture']);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            if (!$file->move($uploadDir, $newFileName)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to save uploaded file']);
            }

            // Update users table (source of truth)
            $userModel->where('user_id', $userId)->set(['profile_picture' => $relativePath])->update();
            $session->set('profile_picture', $relativePath);

            // If counselors table also has a profile_picture column, sync it for consistency
            try {
                $db = \Config\Database::connect();
                $counselorFields = $db->getFieldNames('counselors');
                if (in_array('profile_picture', $counselorFields, true)) {
                    $db->table('counselors')
                        ->where('counselor_id', $userId)
                        ->update(['profile_picture' => $relativePath]);
                }
            } catch (\Throwable $syncEx) {
                // Non-fatal: ignore if table/column doesn't exist
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'picture_url' => $relativePath,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Counselor picture upload error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Server error uploading file']);
        }
    }
}