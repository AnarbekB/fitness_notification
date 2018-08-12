<?php

if (!function_exists('format_phone')) {
    function format_phone(string $phone, int $format = \libphonenumber\PhoneNumberFormat::E164): string
    {
        return \App\Utils\PhoneUtil::format($phone, $format);
    }
}

if (!function_exists('validate_phone')) {
    function validate_phone(string $phone): bool
    {
        return \App\Utils\PhoneUtil::validate($phone);
    }
}
