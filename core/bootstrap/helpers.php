<?php

use Illuminate\Support\Str;

if (!function_exists('phone_format_convert')) {
    function phone_format_convert($inputPhone)
    {
        $output = preg_replace('/[^0-9]/', '', $inputPhone);
        if (str_starts_with($output, "8")) $output = '7' . substr($output, 1);
        if (strlen($output) == 10) $output = '7' . $output;
        return $output;
    }
}

if (!function_exists('email_or_phone')) {
    function email_or_phone($input)
    {
        return Str::contains($input, '@') ? 'email' : 'phone';
    }
}
