<?php

declare(strict_types=1);

namespace App\Support\ValueObjects;

use App\Support\Enums\ContractType;
use App\Support\Enums\Currency;
use InvalidArgumentException;
use Stringable;

final readonly class SalaryRange implements Stringable
{
    public function __construct(
        public ?int $min,
        public ?int $max,
        public Currency $currency,
        public ?ContractType $contractType = null,
    ) {
        if ($min !== null && $min < 0) {
            throw new InvalidArgumentException('Salary min cannot be negative.');
        }
        if ($max !== null && $max < 0) {
            throw new InvalidArgumentException('Salary max cannot be negative.');
        }
        if ($min !== null && $max !== null && $min > $max) {
            throw new InvalidArgumentException('Salary min cannot be greater than max.');
        }
    }

    public function isDisclosed(): bool
    {
        return $this->min !== null || $this->max !== null;
    }

    public function format(): string
    {
        if (! $this->isDisclosed()) {
            return 'Wynagrodzenie nieujawnione';
        }

        $symbol = $this->currency->symbol();

        if ($this->min !== null && $this->max !== null && $this->min !== $this->max) {
            return sprintf('%s - %s %s', number_format($this->min, 0, '.', ' '), number_format($this->max, 0, '.', ' '), $symbol);
        }

        $value = $this->max ?? $this->min;

        return sprintf('%s %s', number_format((int) $value, 0, '.', ' '), $symbol);
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
