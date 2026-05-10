<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Job;
use App\Support\Enums\JobStatus;

it('lets company members edit their own jobs', function () {
    $employer = actingAsEmployer();
    $company = Company::factory()->create();
    $company->members()->attach($employer->id, ['role' => 'owner']);
    $job = Job::factory()->for($company)->create();

    expect($employer->can('update', $job))->toBeTrue()
        ->and($employer->can('delete', $job))->toBeTrue();
});

it('forbids editing jobs of a foreign company', function () {
    $employer = actingAsEmployer();
    $job = Job::factory()->for(Company::factory())->create();

    expect($employer->can('update', $job))->toBeFalse();
});

it('admin can moderate any job', function () {
    $admin = actingAsAdmin();
    $job = Job::factory()->create(['status' => JobStatus::PendingReview->value]);

    expect($admin->can('publish', $job))->toBeTrue();
});

it('candidate cannot create jobs', function () {
    $candidate = actingAsCandidate();

    expect($candidate->can('create', Job::class))->toBeFalse();
});
