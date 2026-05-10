<?php

declare(strict_types=1);

namespace App\Support\Enums;

enum JobStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Published = 'published';
    case Rejected = 'rejected';
    case Expired = 'expired';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Szkic',
            self::PendingReview => 'Oczekuje na moderację',
            self::Published => 'Opublikowane',
            self::Rejected => 'Odrzucone',
            self::Expired => 'Wygasło',
            self::Closed => 'Zamknięte',
        };
    }

    public function isVisibleToPublic(): bool
    {
        return $this === self::Published;
    }
}
