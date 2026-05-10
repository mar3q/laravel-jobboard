<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\Enums\UserRole;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        $this->seed(RolesSeeder::class);
    })
    ->in('Feature');

function actingAsCandidate(): User
{
    $user = User::factory()->candidate()->create();
    test()->actingAs($user);

    return $user;
}

function actingAsEmployer(): User
{
    $user = User::factory()->employer()->create();
    test()->actingAs($user);

    return $user;
}

function actingAsAdmin(): User
{
    $user = User::factory()->admin()->create();
    test()->actingAs($user);

    return $user;
}
