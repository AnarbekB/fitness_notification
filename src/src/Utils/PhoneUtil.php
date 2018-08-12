<?php

namespace App\Utils;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;

class PhoneUtil
{
    protected static $instance;

    protected static $cache;

    public static function format(string $phone, int $format): string
    {
        return self::getUtil()->format(self::getPhone($phone), $format);
    }

    public static function validate(string $phone): bool
    {
        return self::getUtil()->isValidNumber(self::getPhone($phone));
    }

    protected static function getUtil(): PhoneNumberUtil
    {
        if (null === self::$instance) {
            self::$instance = PhoneNumberUtil::getInstance();
        }
        return self::$instance;
    }

    protected static function getPhone(string $phone): PhoneNumber
    {
        $raw = preg_replace('/\D/', '', $phone);
        if (!isset(self::$cache[$raw])) {
            try {
                self::$cache[$raw] = self::getUtil()->parse($phone, 'RU');
            } catch (NumberParseException $e) {
                throw new \Exception(
                    'Invalid phone'
                );
            }
        }
        return self::$cache[$raw];
    }
}