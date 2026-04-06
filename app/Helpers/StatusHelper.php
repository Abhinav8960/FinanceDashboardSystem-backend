<?php

namespace App\Helpers;

class StatusHelper
{
    public static function check($status)
    {
        return match ($status) {
            1 => 'active',
            0 => 'inactive',
            default => 'unknown',
        };
    }

    public static function isActive($status)
    {
        return match ($status) {
            1 => true,
            0 => false,
            default => false,
        };
    }
}
