<?php

declare(strict_types=1);

namespace App\Support\Casts;

use App\Support\Enums\Currency;
use App\Support\ValueObjects\SalaryRange;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<SalaryRange, SalaryRange>
 */
class SalaryRangeCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): SalaryRange
    {
        return new SalaryRange(
            min: isset($attributes['salary_min']) ? (int) $attributes['salary_min'] : null,
            max: isset($attributes['salary_max']) ? (int) $attributes['salary_max'] : null,
            currency: Currency::from($attributes['salary_currency'] ?? Currency::PLN->value),
        );
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (! $value instanceof SalaryRange) {
            return [];
        }

        return [
            'salary_min' => $value->min,
            'salary_max' => $value->max,
            'salary_currency' => $value->currency->value,
        ];
    }
}
