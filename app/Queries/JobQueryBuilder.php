<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Job;
use App\Support\Enums\ContractType;
use App\Support\Enums\Seniority;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

final class JobQueryBuilder
{
    /** @var Builder<Job> */
    private Builder $query;

    public function __construct()
    {
        $this->query = Job::query()->published()->with(['company', 'tags']);
    }

    public static function make(): self
    {
        return new self;
    }

    public function search(?string $term): self
    {
        if ($term !== null && $term !== '') {
            $this->query->search($term);
        }

        return $this;
    }

    public function inCity(?string $city): self
    {
        if ($city !== null && $city !== '') {
            $this->query->forLocation($city);
        }

        return $this;
    }

    public function withSeniority(?string $seniority): self
    {
        if ($seniority !== null && $seniority !== '') {
            $this->query->withSeniority(Seniority::from($seniority));
        }

        return $this;
    }

    public function withContractType(?string $type): self
    {
        if ($type !== null && $type !== '') {
            $this->query->withContractType(ContractType::from($type));
        }

        return $this;
    }

    public function remoteOnly(?bool $remote): self
    {
        if ($remote === true) {
            $this->query->remote();
        }

        return $this;
    }

    public function salaryAtLeast(?int $min): self
    {
        if ($min !== null && $min > 0) {
            $this->query->where('salary_max', '>=', $min);
        }

        return $this;
    }

    public function withTag(?string $tagSlug): self
    {
        if ($tagSlug !== null && $tagSlug !== '') {
            $this->query->whereHas('tags', fn (Builder $q) => $q->where('slug', $tagSlug));
        }

        return $this;
    }

    /**
     * @return LengthAwarePaginator<int, Job>
     */
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query
            ->orderByDesc('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
