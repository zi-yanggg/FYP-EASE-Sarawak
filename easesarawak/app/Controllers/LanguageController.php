<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class LanguageController extends Controller
{
    public function change(string $locale = 'en')
    {
        helper('translation');

        $locale = normalize_site_locale($locale);

        session()->set('site_lang', $locale);

        $redirect = previous_url() ?: base_url('/');

        return redirect()->to($redirect)
            ->setCookie('site_lang', $locale, 60 * 60 * 24 * 30);
    }
}