<?php

namespace App\Enums;

enum AccountColor: string
{
    case Lavender = 'lavender';
    case Orange = 'orange';
    case Yellow = 'yellow';
    case Green = 'green';
    case Emerald = 'emerald';

    public static function values(): array
    {
        return array_values(self::cases());
    }
}
