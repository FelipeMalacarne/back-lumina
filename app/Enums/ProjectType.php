<?php

namespace App\Enums;

enum ProjectType: string
{
    case Personal = 'personal';
    case Professional = 'professional';

    public static function values(): array
    {
        return array_values(self::cases());
    }
}
