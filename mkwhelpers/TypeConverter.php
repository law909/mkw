<?php

namespace mkwhelpers;

class TypeConverter
{

    public static function toBool($value)
    {
        if (is_bool($value)) {
            return $value;
        }
        if ($value) {
            $value = strtolower((string)$value);
            if ($value === 'false' || $value === 'no' || $value === 'off' || $value === 'not') {
                return false;
            }
            return true;
        }
        return false;
    }

    public static function toNum($value)
    {
        if (is_numeric($value)) {
            return $value * 1;
        }
        return 0;
    }

    public static function toInt($value)
    {
        return (int)$value;
    }

    public static function toFloat($value)
    {
        return (float)$value;
    }

    public static function toString($value)
    {
        return (string)$value;
    }

    public static function toArray($value)
    {
        if (is_string($value)) {
            return explode(',', $value);
        }
        return (array)$value;
    }

    public static function toDate($value)
    {
        return (string)$value;
    }
}
