<?php

if (!function_exists('placeholders_replace')) {
    /**
     * @param string $str
     * @param array $replacements
     * @param string $initialToken
     * @param string $endToken
     * @return string
     */
    function placeholders_replace(
        string $str,
        array $replacements,
        string $initialToken = '{',
        string $endToken = '}'
    ): string {
        foreach ($replacements as $name => $value) {
            $str = str_replace("{$initialToken}{$name}{$endToken}", $value, $str);
        }
        return $str;
    }
}

if (!function_exists('guid')) {
    /**
     * @return string
     */
    function guid(): string
    {
        return Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
