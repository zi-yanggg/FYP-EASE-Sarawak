<?php

namespace App\Controllers;

use App\Models\User_model;
use CodeIgniter\Email\Email;

class AuthController extends BaseController
{
    public function forgotPasswordForm()
    {
        helper(['form', 'url']);
        return view('auth/forgot_password');
    }

    public function forgotPassword()
    {
        helper(['form', 'url']);
        $email = $this->request->getPost('email');

        $rules = ['email' => 'required|valid_email'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userModel = new User_model();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'No account found with that email.');
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        $userModel->update($user['user_id'], [
            'reset_token' => $token,
            'reset_expires' => $expires
        ]);

        // Send email
        $resetLink = base_url("reset_password/{$token}");
        $message = view('emails/reset_password', ['resetLink' => $resetLink]);

        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage($message);

        if ($emailService->send()) {
            return redirect()->back()->with('success', 'Password reset link sent to your email.');
        } else {
            log_message('error', $emailService->printDebugger(['headers']));
            return redirect()->back()->with('error', 'Failed to send email. Try again.');
        }
    }

    public function resetPasswordForm($token)
    {
        helper(['form', 'url']);
        $userModel = new User_model();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect('forgot_password')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function resetPassword($token)
    {
        helper(['form', 'url']);
        $rules = [
            'password' => 'required|min_length[3]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        $userModel = new User_model();
        $user = $userModel->where('reset_token', $token)
            ->where('reset_expires >', date('Y-m-d H:i:s'))
            ->first();

        if (!$user) {
            return redirect('forgot_password')->with('error', 'Invalid or expired token.');
        }

        $userModel->update($user['user_id'], [
            'password' => $this->request->getPost('password'),
            'reset_token' => null,
            'reset_expires' => null
        ]);

        return redirect('login')->with('success', 'Password reset successfully. Please login.');
    }
}
