<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\Job;
use App\Support\Enums\JobStatus;

it('returns published jobs as JSON via API', function () {
    Job::factory()->count(3)->create(['status' => JobStatus::Published->value]);
    Job::factory()->draft()->count(2)->create();

    $response = $this->getJson('/api/v1/jobs');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [['id', 'slug', 'title', 'salary', 'links']],
            'meta' => ['current_page', 'total'],
        ]);

    expect($response->json('meta.total'))->toBe(3);
});

it('returns 404 for non-public job via API', function () {
    $job = Job::factory()->draft()->create();

    $this->getJson("/api/v1/jobs/{$job->slug}")
        ->assertStatus(404)
        ->assertHeader('content-type', 'application/problem+json');
});

it('rejects job creation without sanctum token', function () {
    $this->postJson('/api/v1/jobs', [])->assertStatus(401);
});

it('lets an employer create a job via API with the right ability', function () {
    $employer = actingAsEmployer();
    $company = Company::factory()->create();
    $company->members()->attach($employer->id, ['role' => 'owner']);

    $token = $employer->createToken('test', ['jobs:write'])->plainTextToken;

    $payload = [
        'company_id' => $company->id,
        'title' => 'Mid PHP Developer (API test)',
        'description' => str_repeat('Lorem ipsum dolor sit amet. ', 10),
        'seniority' => 'mid',
        'contract_type' => 'b2b',
        'status' => 'published',
        'location_country' => 'PL',
    ];

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/v1/jobs', $payload);

    $response->assertStatus(201);
    expect(Job::where('title', 'Mid PHP Developer (API test)')->exists())->toBeTrue();
});
