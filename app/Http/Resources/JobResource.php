<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Job */
class JobResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'seniority' => $this->seniority->value,
            'contract_type' => $this->contract_type->value,
            'status' => $this->status->value,
            'remote' => $this->remote,
            'hybrid' => $this->hybrid,
            'location' => [
                'city' => $this->location_city,
                'country' => $this->location_country,
            ],
            'salary' => $this->salary === null ? null : [
                'min' => $this->salary->min,
                'max' => $this->salary->max,
                'currency' => $this->salary->currency->value,
                'formatted' => $this->salary->format(),
            ],
            'company' => $this->whenLoaded('company', fn () => [
                'id' => $this->company->id,
                'name' => $this->company->name,
                'slug' => $this->company->slug,
            ]),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => ['name' => $t->name, 'slug' => $t->slug])),
            'published_at' => optional($this->published_at)->toIso8601String(),
            'expires_at' => optional($this->expires_at)->toIso8601String(),
            'links' => [
                'self' => route('jobs.show', $this->slug),
            ],
        ];
    }
}
