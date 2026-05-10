<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Support\Enums\UserRole;

class CompanyPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Company $company): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRole::Employer->value, UserRole::Admin->value]);
    }

    public function update(User $user, Company $company): bool
    {
        return $user->hasRole(UserRole::Admin->value)
            || $user->companies()->whereKey($company->id)->exists();
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }

    public function verify(User $user, Company $company): bool
    {
        return $user->hasRole(UserRole::Admin->value);
    }
}
