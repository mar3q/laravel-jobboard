<?php

declare(strict_types=1);

use App\Support\Enums\Currency;
use App\Support\ValueObjects\SalaryRange;

it('formats range with thousand separator and currency symbol', function () {
    $range = new SalaryRange(8000, 14000, Currency::PLN);

    expect($range->format())->toBe('8 000 - 14 000 zł');
});

it('formats single value when min equals max', function () {
    $range = new SalaryRange(10000, 10000, Currency::EUR);

    expect($range->format())->toBe('10 000 €');
});

it('returns undisclosed label when both null', function () {
    $range = new SalaryRange(null, null, Currency::USD);

    expect($range->isDisclosed())->toBeFalse()
        ->and($range->format())->toBe('Wynagrodzenie nieujawnione');
});

it('throws when min greater than max', function () {
    new SalaryRange(20000, 10000, Currency::PLN);
})->throws(InvalidArgumentException::class);

it('throws on negative values', function () {
    new SalaryRange(-1, 100, Currency::PLN);
})->throws(InvalidArgumentException::class);
