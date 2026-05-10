<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Laravel', 'Symfony', 'PHP 8', 'MySQL', 'PostgreSQL', 'Redis',
            'Docker', 'AWS', 'GCP', 'TDD', 'DDD', 'Microservices',
            'REST', 'GraphQL', 'Vue', 'React', 'Tailwind', 'Livewire',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
