<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum ContractType: string
{
    case UoP = 'uop';
    case B2B = 'b2b';
    case UZ = 'uz';
    case UoD = 'uod';
    case Internship = 'internship';

    public function label(): string
    {
        return match ($this) {
            self::UoP => 'Umowa o pracę',
            self::B2B => 'B2B',
            self::UZ => 'Umowa zlecenie',
            self::UoD => 'Umowa o dzieło',
            self::Internship => 'Staż',
        };
    }
}
