<?php

namespace App\Domain\ValueObjects;

enum Agency: string
{
    case CIA = 'CIA';
    case MI6 = 'MI6';
    case KGB = 'KGB';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
