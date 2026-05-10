<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::random(6),
            'website' => fake()->url(),
            'nip' => fake()->numerify('##########'),
            'size' => fake()->randomElement(['1-10', '11-50', '51-200', '201-500', '500+']),
            'industry' => fake()->randomElement(['IT', 'Fintech', 'E-commerce', 'Gaming', 'Healthcare', 'Consulting']),
            'description' => fake()->paragraphs(2, true),
            'city' => fake()->randomElement(['Warszawa', 'Kraków', 'Wrocław', 'Poznań', 'Gdańsk', 'Łódź', 'Katowice']),
            'country' => 'PL',
            'is_verified' => fake()->boolean(70),
        ];
    }

    public function verified(): static
    {
        return $this->state(['is_verified' => true]);
    }
}
