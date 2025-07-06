<?php

namespace App\Helpers;

class Str
{
    public static function studly(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return str_replace(' ', '', $value);
    }

    public static function camel(string $value): string
    {
        return ucfirst(static::studly($value));
    }
}