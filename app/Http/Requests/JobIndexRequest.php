<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Enums\ContractType;
use App\Support\Enums\Seniority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'seniority' => ['nullable', Rule::in(array_column(Seniority::cases(), 'value'))],
            'contract' => ['nullable', Rule::in(array_column(ContractType::cases(), 'value'))],
            'remote' => ['nullable', 'boolean'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'tag' => ['nullable', 'string', 'max:60'],
        ];
    }
}
