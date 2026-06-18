<?php

namespace App\Controllers;

use App\Models\User_model;
class Login extends BaseController
{
    public function index()
    {
        return view('admin/login');
    }

    public function submit()
    {
        if (! $this->verifyTurnstile($this->request->getPost('cf-turnstile-response'))) {
            return redirect()->back()->with('error', 'Bot verification failed. Please try again.');
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = new User_model();
        $userData = $user->where([
            'email' => $email,
            'is_deleted' => 0
        ])->first();

        if ($userData && password_verify($password, $userData['password'])) {
            $cache = \Config\Services::cache();
            $cache->delete('login_fail_' . md5($this->request->getIPAddress()));

            $stamp = bin2hex(random_bytes(16));
            $user->update($userData['user_id'], ['security_stamp' => $stamp]);

            $session = session();
            $session->set([
                'user_id'        => $userData['user_id'],
                'username'       => $userData['username'],
                'email'          => $userData['email'],
                'access'         => 1,
                'role'           => $userData['role'],
                'security_stamp' => $stamp,
            ]);

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $user->update($userData['user_id'], [
                    'remember_token' => $token
                ]);

                // Set cookie for 7 days
                $cookie = cookie(
                    'remember_me',
                    $token,
                    [
                        'expires'  => time() + (60 * 60 * 24 * 7), // 7 days
                        'path'     => '/',
                        'domain'   => '',
                        'secure'   => ENVIRONMENT === 'production',
                        'httponly' => true,
                        'samesite' => 'Strict'
                    ]
                );
                $this->response->setCookie($cookie);
            } else {
                $this->response->deleteCookie('remember_me');
            }

            return redirect()->to(base_url('/admin'));
        } else {
            $cache   = \Config\Services::cache();
            $ip      = $this->request->getIPAddress();
            $key     = 'login_fail_' . md5($ip);
            $current = (int) ($cache->get($key) ?? 0);
            $cache->save($key, $current + 1, 900);

            return redirect()->back()->with('error', 'Incorrect username or password');
        }
    }


    /**
     * Verifies a Cloudflare Turnstile token server-side.
     * Fails closed: any error talking to Cloudflare is treated as a failed check.
     */
    private function verifyTurnstile(?string $token): bool
    {
        $secret = env('TURNSTILE_SECRET_KEY');

        if (empty($secret)) {
            // Not configured — don't block logins because of a missing key.
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $client   = \Config\Services::curlrequest();
            $response = $client->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'form_params' => [
                    'secret'   => $secret,
                    'response' => $token,
                    'remoteip' => $this->request->getIPAddress(),
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return (bool) ($result['success'] ?? false);
        } catch (\Throwable $e) {
            log_message('error', 'Turnstile verification failed: ' . $e->getMessage());
            return false;
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
