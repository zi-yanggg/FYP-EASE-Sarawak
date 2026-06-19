<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Checks that the domain part of an email address has MX or A DNS records.
     * Rejects clearly fake domains without sending anything to the mailbox.
     */
    public function valid_email_domain(string $value, ?string $params, array $data, ?string &$error = null): bool
    {
        $atPos = strrpos($value, '@');
        if ($atPos === false) {
            $error = 'The email domain could not be verified.';
            return false;
        }

        $domain = substr($value, $atPos + 1);

        if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A')) {
            return true;
        }

        $error = 'The email domain "{value}" does not appear to exist.';
        return false;
    }
}
