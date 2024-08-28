<?php

namespace Modules\User\Enums;

enum UserMemberType: string
{
    case Retail = 'Retail';
    case Wholesale = 'Wholesale';
    
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
