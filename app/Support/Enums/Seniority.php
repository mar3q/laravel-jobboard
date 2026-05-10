<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum Seniority: string
{
    case Intern = 'intern';
    case Junior = 'junior';
    case Mid = 'mid';
    case Senior = 'senior';
    case Lead = 'lead';
    case Principal = 'principal';

    public function label(): string
    {
        return match ($this) {
            self::Intern => 'Stażysta',
            self::Junior => 'Junior',
            self::Mid => 'Mid / Regular',
            self::Senior => 'Senior',
            self::Lead => 'Lead',
            self::Principal => 'Principal',
        };
    }
}
