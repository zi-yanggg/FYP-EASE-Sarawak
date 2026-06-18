<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $access  = $session->get('access');
        $role    = $session->get('role');

        if (empty($access) || ! in_array((string) $role, ['0', '1'], true)) {
            if ($request->isAJAX()) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON(['error' => 'Authentication required.']);
            }

            return redirect()->to(base_url('login'));
        }

        // Invalidate sessions when the password has been reset from another device.
        $userId      = (int) $session->get('user_id');
        $sessionStamp = $session->get('security_stamp');
        if ($userId > 0) {
            $user    = (new \App\Models\User_model())->find($userId);
            $dbStamp = $user['security_stamp'] ?? null;

            if ($dbStamp !== null && $sessionStamp !== $dbStamp) {
                session()->destroy();

                if ($request->isAJAX()) {
                    return service('response')
                        ->setStatusCode(401)
                        ->setJSON(['error' => 'Session expired. Please log in again.']);
                }

                return redirect()->to(base_url('login'))
                    ->with('error', 'Your session has expired. Please log in again.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
