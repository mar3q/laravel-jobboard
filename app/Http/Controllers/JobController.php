<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\JobIndexRequest;
use App\Models\Job;
use App\Queries\JobQueryBuilder;
use App\Support\Enums\ContractType;
use App\Support\Enums\Seniority;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(JobIndexRequest $request): View
    {
        $filters = $request->validated();

        $jobs = JobQueryBuilder::make()
            ->search($filters['q'] ?? null)
            ->inCity($filters['city'] ?? null)
            ->withSeniority($filters['seniority'] ?? null)
            ->withContractType($filters['contract'] ?? null)
            ->remoteOnly(isset($filters['remote']) ? (bool) $filters['remote'] : null)
            ->salaryAtLeast(isset($filters['salary_min']) ? (int) $filters['salary_min'] : null)
            ->withTag($filters['tag'] ?? null)
            ->paginate(12);

        $featured = Cache::remember(
            'jobs.featured',
            now()->addMinutes(10),
            fn () => Job::query()->published()->with('company')->latest('published_at')->limit(5)->get()
        );

        return view('jobs.index', [
            'jobs' => $jobs,
            'featured' => $featured,
            'filters' => $filters,
            'seniorities' => Seniority::cases(),
            'contractTypes' => ContractType::cases(),
        ]);
    }

    public function show(Job $job): View|Response
    {
        $this->authorize('view', $job);

        $job->loadMissing(['company', 'tags', 'category']);
        $job->increment('views_count');

        return view('jobs.show', [
            'job' => $job,
        ]);
    }
}
