<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum ApplicationStatus: string
{
    case Pending = 'pending';
    case Reviewing = 'reviewing';
    case Interview = 'interview';
    case Rejected = 'rejected';
    case Accepted = 'accepted';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Oczekuje',
            self::Reviewing => 'W weryfikacji',
            self::Interview => 'Rozmowa',
            self::Rejected => 'Odrzucona',
            self::Accepted => 'Zaakceptowana',
            self::Withdrawn => 'Wycofana',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::Rejected, self::Accepted, self::Withdrawn], true);
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Reviewing => 'blue',
            self::Interview => 'amber',
            self::Rejected => 'red',
            self::Accepted => 'green',
            self::Withdrawn => 'slate',
        };
    }
}
