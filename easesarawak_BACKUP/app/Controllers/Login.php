<?php

namespace App\Controllers;

use App\Models\User_model;
use CodeIgniter\Cookie\Cookie;

class Login extends BaseController
{
    public function index()
    {
        return view('admin/login');
    }

    public function submit()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = new User_model();
        $userData = $user->where([
            'email' => $email,
            'is_deleted' => 0
        ])->first();

        if ($userData && password_verify($password, $userData['password'])) {
            $session = session();
            $session->set([
                'user_id' => $userData['user_id'],
                'username' => $userData['username'],
                'email'    => $userData['email'],
                'access'   => 1,
                'role'     => $userData['role']
            ]);

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $user->update($userData['user_id'], [
                    'remember_token' => $token
                ]);

                // Set cookie for 30 days
                $cookie = cookie(
                    'remember_me',
                    $token,
                    [
                        'expires'  => time() + (60 * 60 * 24 * 30), // 30 days
                        'path'     => '/',
                        'domain'   => '',
                        'secure'   => false, // Set to true if using HTTPS
                        'httponly' => true,
                        'samesite' => 'Lax'
                    ]
                );
                $this->response->setCookie($cookie);
            } else {
                $this->response->deleteCookie('remember_me');
            }

            return redirect()->to(base_url('/admin'));
        } else {
            return redirect()->back()->with('error', 'Incorrect username or password');
        }
    }


    public function logout()
    {
        $userId = session()->get('user_id');
        if ($userId) {
            $userModel = new User_model();
            $userModel->update($userId, ['remember_token' => null]);    
        }

        $this->response->deleteCookie('remember_me');
        session()->destroy();
        return redirect()->to(base_url('/login'));
    }
}
