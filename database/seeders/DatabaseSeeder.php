<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use App\Support\Enums\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $admin = User::factory()->admin()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@jobboard.test',
        ]);

        $employer = User::factory()->employer()->create([
            'name' => 'Employer Demo',
            'email' => 'employer@jobboard.test',
        ]);

        User::factory()->candidate()->create([
            'name' => 'Candidate Demo',
            'email' => 'candidate@jobboard.test',
        ]);

        $candidates = User::factory()->candidate()->count(20)->create();
        $tags = Tag::factory()->count(15)->create();

        $employerCompany = Company::factory()->create(['name' => 'Acme Software sp. z o.o.']);
        $employerCompany->members()->attach($employer->id, ['role' => 'owner']);

        $companies = Company::factory()->count(15)->create();
        $companies->push($employerCompany);

        $companies->each(function (Company $company) use ($admin, $tags): void {
            $jobs = Job::factory()
                ->count(fake()->numberBetween(3, 8))
                ->for($company)
                ->state(['created_by' => $admin->id])
                ->create();

            $jobs->each(function (Job $job) use ($tags): void {
                $job->tags()->attach($tags->random(fake()->numberBetween(2, 5))->pluck('id'));
            });
        });

        $publishedJobs = Job::query()->where('status', 'published')->inRandomOrder()->limit(40)->get();
        $publishedJobs->each(function (Job $job) use ($candidates): void {
            $applicants = $candidates->random(fake()->numberBetween(0, 4));
            foreach ($applicants as $candidate) {
                Application::factory()->for($job)->for($candidate)->create();
            }
        });
    }
}
