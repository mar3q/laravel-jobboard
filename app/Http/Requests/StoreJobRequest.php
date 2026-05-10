<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\Enums\ContractType;
use App\Support\Enums\Currency;
use App\Support\Enums\JobStatus;
use App\Support\Enums\Seniority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'min:50'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'seniority' => ['required', Rule::in(array_column(Seniority::cases(), 'value'))],
            'contract_type' => ['required', Rule::in(array_column(ContractType::cases(), 'value'))],
            'status' => ['required', Rule::in([JobStatus::Draft->value, JobStatus::PendingReview->value, JobStatus::Published->value])],
            'salary_min' => ['nullable', 'integer', 'min:0', 'lte:salary_max'],
            'salary_max' => ['nullable', 'integer', 'min:0'],
            'salary_currency' => ['nullable', Rule::in(array_column(Currency::cases(), 'value'))],
            'location_city' => ['nullable', 'string', 'max:120'],
            'location_country' => ['required', 'string', 'size:2'],
            'remote' => ['boolean'],
            'hybrid' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:today'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareForValidation(): array
    {
        $this->merge([
            'remote' => $this->boolean('remote'),
            'hybrid' => $this->boolean('hybrid'),
            'location_country' => $this->input('location_country', 'PL'),
        ]);

        return [];
    }
}
