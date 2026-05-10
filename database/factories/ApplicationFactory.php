<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Support\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'user_id' => User::factory(),
            'cv_id' => null,
            'status' => ApplicationStatus::Pending->value,
            'cover_letter' => fake()->paragraphs(2, true),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
        ];
    }

    public function reviewing(): static
    {
        return $this->state(['status' => ApplicationStatus::Reviewing->value]);
    }

    public function accepted(): static
    {
        return $this->state(['status' => ApplicationStatus::Accepted->value, 'reviewed_at' => now()]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => ApplicationStatus::Rejected->value, 'reviewed_at' => now()]);
    }
}
