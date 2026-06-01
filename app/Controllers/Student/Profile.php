<?php

namespace App\Controllers\Student;


use App\Helpers\SecureLogHelper;
use App\Models\UserModel;
use App\Helpers\UserActivityHelper;
use App\Controllers\BaseController;

class Profile extends BaseController
{
    private function resolveStudentUserId(): ?string
    {
        $userId = session()->get('user_id_display');
        if (!empty($userId)) {
            return $userId;
        }

        $numericId = session()->get('user_id');
        if (empty($numericId)) {
            return null;
        }

        $user = (new UserModel())->find($numericId);
        return $user['user_id'] ?? null;
    }

    private function ensureStudentAccess(): ?\CodeIgniter\HTTP\ResponseInterface
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
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

    public function getProfile()
    {
        if ($denied = $this->ensureStudentAccess()) {
            return $denied;
        }

        $user_id = $this->resolveStudentUserId();
        if (!$user_id) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid session data'])->setStatusCode(400);
        }

        $userModel = new UserModel();
        $user = $userModel->where('user_id', $user_id)->first();

        if ($user) {
            return $this->response->setJSON([
                'success' => true,
                'user_id' => $user['user_id'],
                'username' => $user['username'] ?? '',
                'courseYear' => $user['course_year'] ?? '',
                'email' => $user['email'],
                'role' => $user['role'],
                'profile_picture' => $this->normalizeProfilePictureUrl($user['profile_picture'] ?? null),
                'last_login' => $user['last_login'] ?? null,
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'User not found'])->setStatusCode(404);
    }

    public function profile()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'student') {
            return redirect()->to('/');
        }

        return view('student/student_profile');
    }

    public function updateProfile()
    {
        if ($denied = $this->ensureStudentAccess()) {
            return $denied;
        }

        $user_id = $this->resolveStudentUserId();
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

            if ($user['email'] !== $email) {
                $existingUser = $userModel
                    ->where('email', $email)
                    ->where('id !=', $user['id'])
                    ->first();
                if ($existingUser) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'This email is already registered to another account',
                    ]);
                }
            }

            $userModel->skipValidation(true)->update($user['id'], [
                'username' => $username,
                'email' => $email,
            ]);

            $activityHelper = new UserActivityHelper();
            $activityHelper->updateStudentActivity($user_id, 'update_profile');

            session()->set('username', $username);
            session()->set('email', $email);

            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'Student updateProfile error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

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

            if ($field === 'email' && $user['email'] !== $value) {
                $existingUser = $userModel
                    ->where('email', $value)
                    ->where('id !=', $user['id'])
                    ->first();
                if ($existingUser) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'This email is already registered to another account',
                    ]);
                }
            }

            $userModel->skipValidation(true)->update($user['id'], [$field => $value]);

            $activityHelper = new UserActivityHelper();
            $activityHelper->updateStudentActivity($userId, 'update_profile');

            session()->set($field, $value);

            return $this->response->setJSON([
                'success' => true,
                'message' => ucfirst($field) . ' updated successfully',
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Student updateAccountField error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Database error occurred']);
        }
    }

    public function updateProfilePicture()
    {
        if ($denied = $this->ensureStudentAccess()) {
            return $denied;
        }

        if (strtolower($this->request->getMethod()) === 'options') {
            return $this->response->setJSON(['success' => true]);
        }
        if (strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid method'])->setStatusCode(405);
        }

        try {
            $userId = $this->resolveStudentUserId();
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

            $newFileName = 'student_' . $userId . '_' . time() . '.' . $ext;
            $relativePath = 'Photos/profile_pictures/' . $newFileName;

            $userModel = new UserModel();
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

            $userModel->where('user_id', $userId)->set(['profile_picture' => $relativePath])->update();
            session()->set('profile_picture', $relativePath);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'picture_url' => $relativePath,
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'Student profile picture upload error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while uploading the picture']);
        }
    }
}
