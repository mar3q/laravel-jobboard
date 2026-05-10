<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobIndexRequest;
use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Queries\JobQueryBuilder;
use App\Support\Enums\JobStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class JobController extends Controller
{
    public function index(JobIndexRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $cacheKey = 'api.jobs.'.md5(serialize($filters + ['page' => $request->integer('page', 1)]));

        $jobs = Cache::remember($cacheKey, now()->addMinutes(2), function () use ($filters) {
            return JobQueryBuilder::make()
                ->search($filters['q'] ?? null)
                ->inCity($filters['city'] ?? null)
                ->withSeniority($filters['seniority'] ?? null)
                ->withContractType($filters['contract'] ?? null)
                ->remoteOnly(isset($filters['remote']) ? (bool) $filters['remote'] : null)
                ->salaryAtLeast(isset($filters['salary_min']) ? (int) $filters['salary_min'] : null)
                ->withTag($filters['tag'] ?? null)
                ->paginate(20);
        });

        return JobResource::collection($jobs);
    }

    public function show(Job $job): JobResource
    {
        abort_unless($job->status->isVisibleToPublic(), 404);

        $job->load(['company', 'tags']);

        return new JobResource($job);
    }

    public function store(StoreJobRequest $request): JsonResponse
    {
        $this->authorize('create', Job::class);

        abort_unless(
            $request->user()->companies()->whereKey((int) $request->validated('company_id'))->exists()
                || $request->user()->hasRole('admin'),
            403
        );

        $job = Job::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
            'status' => JobStatus::PendingReview->value,
        ]);

        return (new JobResource($job->fresh(['company'])))
            ->response()
            ->setStatusCode(201);
    }
}
