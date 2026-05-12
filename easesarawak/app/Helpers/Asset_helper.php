<?php

declare(strict_types=1);

if (! function_exists('public_asset')) {
    /**
     * URL for a static file under public/assets (relative to the site index).
     *
     * @param string $path Path after assets/, e.g. "css/navbar_style.css" or "images/logo.png"
     */
    function public_asset(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if ($path === '') {
            return rtrim(base_url(), '/') . '/';
        }

        if (str_starts_with($path, 'assets/')) {
            return base_url($path);
        }

        return base_url('assets/' . $path);
    }
}

if (! function_exists('public_assets_dir')) {
    /**
     * Base URL for a folder under public/assets, with trailing slash (for JS concatenation).
     */
    function public_assets_dir(string $subdir = ''): string
    {
        $subdir = trim(str_replace('\\', '/', $subdir), '/');
        $url = $subdir === '' ? base_url('assets') : base_url('assets/' . $subdir);

        return rtrim($url, '/') . '/';
    }
}
