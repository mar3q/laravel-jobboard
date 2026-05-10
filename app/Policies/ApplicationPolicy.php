<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use App\Support\Enums\ApplicationStatus;
use App\Support\Enums\UserRole;

class ApplicationPolicy
{
    public function view(User $user, Application $application): bool
    {
        if ($user->hasRole(UserRole::Admin->value)) {
            return true;
        }

        if ($application->user_id === $user->id) {
            return true;
        }

        return $user->companies()->whereKey($application->job->company_id)->exists();
    }

    public function withdraw(User $user, Application $application): bool
    {
        return $application->user_id === $user->id
            && ! $application->status->isTerminal();
    }

    public function changeStatus(User $user, Application $application): bool
    {
        if ($user->hasRole(UserRole::Admin->value)) {
            return true;
        }

        return $user->companies()->whereKey($application->job->company_id)->exists();
    }

    public function reject(User $user, Application $application): bool
    {
        return $this->changeStatus($user, $application)
            && $application->status !== ApplicationStatus::Rejected;
    }
}
