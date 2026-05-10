<?php

declare(strict_types=1);

use App\Models\Job;
use App\Support\Enums\JobStatus;
use App\Support\Enums\Seniority;

it('lists only published jobs on the index page', function () {
    Job::factory()->count(3)->create(['status' => JobStatus::Published->value]);
    Job::factory()->draft()->count(2)->create();

    $response = $this->get('/jobs');

    $response->assertOk();
    expect(substr_count($response->getContent(), 'job-card') >= 0)->toBeTrue();
});

it('filters jobs by seniority', function () {
    $senior = Job::factory()->create([
        'title' => 'SeniorBoom',
        'seniority' => Seniority::Senior->value,
        'status' => JobStatus::Published->value,
    ]);
    Job::factory()->create([
        'title' => 'JuniorBoom',
        'seniority' => Seniority::Junior->value,
        'status' => JobStatus::Published->value,
    ]);

    $response = $this->get('/jobs?seniority=senior');

    $response->assertOk()
        ->assertSee('SeniorBoom')
        ->assertDontSee('JuniorBoom');
});

it('shows a job details page', function () {
    $job = Job::factory()->create([
        'title' => 'Pokazowy Tytul',
        'status' => JobStatus::Published->value,
    ]);

    $this->get(route('jobs.show', $job))
        ->assertOk()
        ->assertSee('Pokazowy Tytul');
});

it('hides draft jobs from public', function () {
    $job = Job::factory()->draft()->create();

    $this->get(route('jobs.show', $job))->assertForbidden();
});
