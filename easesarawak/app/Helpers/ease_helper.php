<?php

/**
 * Project-relative URL path (no hostname). Derived from app.baseURL in .env / Config.
 */
function ease_path(string $uri = ''): string
{
    $basePath = parse_url(config('App')->baseURL, PHP_URL_PATH) ?? '';
    $basePath = rtrim($basePath, '/');
    $uri      = ltrim($uri, '/');

    return $uri === '' ? $basePath : $basePath . '/' . $uri;
}

/**
 * Named route path segments for dashboard / admin UI.
 */
function ease_route(string $name, ...$params): string
{
    $routes = [
        'order'         => 'order',
        'order_details' => 'admin/order_details',
        'change_status' => 'change_status',
        'contact'       => 'admin/contact',
        'refund'        => 'admin/refund_request',
    ];

    if (! isset($routes[$name])) {
        return ease_path($name);
    }

    $path = $routes[$name];
    foreach ($params as $p) {
        $path .= '/' . rawurlencode((string) $p);
    }

    return ease_path($path);
}
