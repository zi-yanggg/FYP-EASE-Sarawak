<?php

declare(strict_types=1);

if (! function_exists('public_asset')) {
    /**
     * URL to a file under public/assets/ (works with any app base URL or subfolder).
     *
     * @param string $relativePath Path inside assets, e.g. "images/logo.png" or "css/site.css".
     */
    function public_asset(string $relativePath): string
    {
        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
        if ($relativePath === '') {
            return base_url('assets/');
        }

        $segments = explode('/', $relativePath);

        return base_url('assets/' . implode('/', array_map('rawurlencode', $segments)));
    }
}

if (! function_exists('public_assets_dir')) {
    /**
     * Base URL for a folder under public/assets/, with trailing slash (for string concatenation in JS/CSS).
     */
    function public_assets_dir(string $relativeDirectory): string
    {
        $relativeDirectory = trim(str_replace('\\', '/', $relativeDirectory), '/');
        if ($relativeDirectory === '') {
            return rtrim(base_url('assets/'), '/') . '/';
        }

        $segments = explode('/', $relativeDirectory);

        return rtrim(base_url('assets/' . implode('/', array_map('rawurlencode', $segments))), '/') . '/';
    }
}
