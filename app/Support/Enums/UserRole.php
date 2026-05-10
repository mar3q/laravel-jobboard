<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum UserRole: string
{
    case Candidate = 'candidate';
    case Employer = 'employer';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Candidate => 'Kandydat',
            self::Employer => 'Pracodawca',
            self::Admin => 'Administrator',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
