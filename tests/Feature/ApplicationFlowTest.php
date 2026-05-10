<?php

declare(strict_types=1);

use App\Events\ApplicationSubmitted;
use App\Models\Application;
use App\Models\Job;
use App\Support\Enums\ApplicationStatus;
use App\Support\Enums\JobStatus;
use Illuminate\Support\Facades\Event;

it('lets a candidate submit an application', function () {
    Event::fake([ApplicationSubmitted::class]);

    $candidate = actingAsCandidate();
    $job = Job::factory()->create(['status' => JobStatus::Published->value]);

    $response = $this->post(route('applications.store', $job), [
        'contact_email' => 'me@example.com',
        'cover_letter' => 'Bardzo chętnie dołączę do zespołu.',
    ]);

    $response->assertRedirect(route('applications.index'));

    expect(Application::where('user_id', $candidate->id)->where('job_id', $job->id)->exists())->toBeTrue();
    expect($job->fresh()->applications_count)->toBe(1);

    Event::assertDispatched(ApplicationSubmitted::class);
});

it('prevents employers from applying', function () {
    actingAsEmployer();
    $job = Job::factory()->create(['status' => JobStatus::Published->value]);

    $this->get(route('applications.create', $job))->assertForbidden();
});

it('prevents duplicate applications from the same candidate', function () {
    $candidate = actingAsCandidate();
    $job = Job::factory()->create(['status' => JobStatus::Published->value]);

    $this->post(route('applications.store', $job), ['contact_email' => 'a@b.test']);
    $response = $this->post(route('applications.store', $job), ['contact_email' => 'a@b.test']);

    $response->assertSessionHasErrors('job');
    expect(Application::where('user_id', $candidate->id)->where('job_id', $job->id)->count())->toBe(1);
});

it('allows candidate to withdraw their own application', function () {
    $candidate = actingAsCandidate();
    $application = Application::factory()
        ->for($candidate)
        ->for(Job::factory()->create(['status' => JobStatus::Published->value]))
        ->create();

    $this->patch(route('applications.withdraw', $application))->assertRedirect();

    expect($application->fresh()->status)->toBe(ApplicationStatus::Withdrawn);
});
