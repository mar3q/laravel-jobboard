<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use App\Models\Job;
use App\Support\Enums\ContractType;
use App\Support\Enums\JobStatus;
use App\Support\Enums\Seniority;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Job>
 */
class JobFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Senior PHP Developer (Laravel)',
            'Mid PHP Developer (Symfony)',
            'Backend Engineer',
            'Full-stack Developer',
            'Tech Lead',
            'DevOps Engineer',
            'Junior PHP Developer',
        ]);
        $min = fake()->numberBetween(8000, 18000);
        $max = $min + fake()->numberBetween(2000, 8000);

        return [
            'company_id' => Company::factory(),
            'job_category_id' => null,
            'created_by' => null,
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(6),
            'description' => fake()->paragraphs(4, true),
            'requirements' => fake()->paragraphs(2, true),
            'benefits' => fake()->paragraphs(1, true),
            'seniority' => fake()->randomElement(Seniority::cases())->value,
            'contract_type' => fake()->randomElement(ContractType::cases())->value,
            'status' => JobStatus::Published->value,
            'salary_min' => $min,
            'salary_max' => $max,
            'salary_currency' => 'PLN',
            'location_city' => fake()->randomElement(['Warszawa', 'Kraków', 'Wrocław', 'Poznań', 'Gdańsk', 'Remote']),
            'location_country' => 'PL',
            'remote' => fake()->boolean(60),
            'hybrid' => fake()->boolean(40),
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'expires_at' => now()->addDays(fake()->numberBetween(7, 60)),
        ];
    }

    public function draft(): static
    {
        return $this->state(['status' => JobStatus::Draft->value, 'published_at' => null]);
    }

    public function expired(): static
    {
        return $this->state([
            'status' => JobStatus::Expired->value,
            'expires_at' => now()->subDays(1),
        ]);
    }

    public function remote(): static
    {
        return $this->state(['remote' => true, 'location_city' => 'Remote']);
    }
}
