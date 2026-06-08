<?php

namespace App\Controllers;

use App\Models\User_model;

class Profile extends BaseController
{
    public function profile()
    { 
        return $this->render('admin/profile');
    }

    public function edit_profile($userId)
    {
        helper('form');
        $userModel = new User_model();
        $data['user']   = $userModel->where(['user_id' => $userId, 'is_deleted' => 0])->first();
        $data['errors'] = session()->getFlashdata('errors') ?? [];
        return $this->render('admin/edit_profile', $data);
    }

    public function update_profile($userId)
    {
        $userModel = new User_model();
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email'    => 'required|valid_email',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $dupEmail = $userModel
            ->where('email', $email)
            ->where('user_id !=', (int) $userId)
            ->where('is_deleted', 0)
            ->first();
        if ($dupEmail) {
            return redirect()->back()->withInput()->with('errors', [
                'email' => 'That email is already in use by another account.',
            ]);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];
        // Handle profile picture
        $file = $this->request->getFile('profile_picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $uploadDir = FCPATH . 'assets/uploads/profiles/';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $newName);
            $data['profile_picture'] = 'assets/uploads/profiles/' . $newName;

            // Delete old picture — field already stores the full relative path
            $oldUser = $userModel->find($userId);
            if (! empty($oldUser['profile_picture'])) {
                $oldPath = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $oldUser['profile_picture']);
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $userModel->update($userId, $data);

        session()->set([
            'username' => $data['username'],
            'email'    => $data['email'],
        ]);

        $picUpdated = isset($data['profile_picture']);
        return redirect()->to('/profile')->with('toast', [
            'title'   => $picUpdated ? 'Picture Updated' : 'Profile Updated',
            'icon'    => $picUpdated ? 'fas fa-camera'   : 'fas fa-user-check',
            'user_id' => (int) $userId,
            'username' => $data['username'],
            'email'   => $data['email'],
        ]);
    }

    public function change_password_form()
    {
        helper(['form', 'url']);
        return $this->render('admin/change_password');
    }

    public function change_password()
    {
        helper(['form', 'url']);

        $userModel = new User_model();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/]',
            'confirm_password' => 'required|matches[new_password]',
        ];
        $messages = [
            'new_password' => [
                'min_length'  => 'Password must be at least 8 characters.',
                'regex_match' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $userModel->update($userId, [
            'password' => $newPassword  // Will be hashed by beforeUpdate callback
        ]);

        return redirect()->to('/change_password')->with('toast', [
            'title'   => 'Password Changed',
            'icon'    => 'fas fa-key',
            'message' => 'Your password has been updated successfully.',
        ]);
    }
}