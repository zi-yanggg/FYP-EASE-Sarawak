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
        $data['user'] = $userModel->where(['user_id' => $userId, 'is_deleted' => 0])->first();
        return $this->render('admin/edit_profile', $data);
    }

    public function update_profile($userId)
    {
        $userModel = new User_model();
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]',
            'email'    => "required|valid_email|is_unique[user.email,user_id,{$userId}]",
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];
        // Handle profile picture
        $file = $this->request->getFile('profile_picture');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules['profile_picture'] = 'max_size[profile_picture,2048]|is_image[profile_picture]';
        }
        if ($file->isValid()) {
            $newName = $file->getRandomName();
            $uploadDir = FCPATH . 'assets/uploads/profiles/';
            if (! is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $file->move($uploadDir, $newName);
            $data['profile_picture'] = 'assets/uploads/profiles/' . $newName;

            // Delete old picture
            $oldUser = $userModel->find($userId);
            if (! empty($oldUser['profile_picture'])) {
                $oldPath = FCPATH . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $oldUser['profile_picture']);
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $userModel->update($userId, $data);

        // Update session data
        session()->set([
            'username' => $data['username'],
            'email'    => $data['email']
        ]);
        return redirect()->to('/profile')->with('success', 'Profile update successfully!');
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
        $userId = session()->get('id');
        $user = $userModel->find($userId);

        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
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

        return redirect()->to('/change_password')->with('success', 'Password changed successfully!');
    }
}