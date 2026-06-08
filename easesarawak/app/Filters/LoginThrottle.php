<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginThrottle implements FilterInterface
{
    private const MAX_ATTEMPTS  = 5;
    private const WINDOW_SECS   = 900; // 15 minutes
    private const LOCKOUT_SECS  = 900;

    public function before(RequestInterface $request, $arguments = null)
    {
        $cache = \Config\Services::cache();
        $ip    = $request->getIPAddress();
        $key   = 'login_fail_' . md5($ip);

        $failures = (int) ($cache->get($key) ?? 0);

        if ($failures >= self::MAX_ATTEMPTS) {
            return redirect()->to(base_url('login'))
                ->with('error', 'Too many failed login attempts. Please wait 15 minutes before trying again.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
