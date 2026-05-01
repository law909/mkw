<?php

namespace mkw;

class validate
{
    public static function is(mixed $value, string $classBaseName, array $args = [], ?string $namespaces = null): bool
    {
        $classBaseName = trim($classBaseName, '\\');

        switch (strtolower($classBaseName)) {
            case 'emailaddress':
            case 'email_address':
            case 'email':
                return self::isEmailAddress($value);

            default:
                throw new \InvalidArgumentException(
                    "Unsupported validator: {$classBaseName}"
                );
        }
    }

    private static function isEmailAddress(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $value = trim($value);

        if ($value === '') {
            return false;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}