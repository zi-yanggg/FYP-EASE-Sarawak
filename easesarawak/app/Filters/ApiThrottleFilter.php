<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Simple per-IP rate limiter for public API endpoints.
 */
class ApiThrottleFilter implements FilterInterface
{
    private const MAX_ATTEMPTS = 30;
    private const WINDOW_SECONDS = 60;

    public function before(RequestInterface $request, $arguments = null)
    {
        $ip      = $request->getIPAddress();
        $cache   = \Config\Services::cache();
        $key     = 'api_throttle_' . md5($ip);
        $attempt = (int) $cache->get($key);

        if ($attempt >= self::MAX_ATTEMPTS) {
            return service('response')
                ->setStatusCode(429)
                ->setJSON(['error' => 'Too many requests. Please try again later.']);
        }

        $cache->save($key, $attempt + 1, self::WINDOW_SECONDS);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
