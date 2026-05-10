<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use App\Support\Enums\JobStatus;
use App\Support\Enums\UserRole;

class JobPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Job $job): bool
    {
        if ($job->status->isVisibleToPublic()) {
            return true;
        }

        return $user !== null
            && ($user->hasRole(UserRole::Admin->value) || $this->ownsCompany($user, $job));
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRole::Employer->value, UserRole::Admin->value]);
    }

    public function update(User $user, Job $job): bool
    {
        return $user->hasRole(UserRole::Admin->value) || $this->ownsCompany($user, $job);
    }

    public function delete(User $user, Job $job): bool
    {
        return $this->update($user, $job);
    }

    public function publish(User $user, Job $job): bool
    {
        return $user->hasRole(UserRole::Admin->value)
            || ($this->ownsCompany($user, $job) && $job->status !== JobStatus::Published);
    }

    public function apply(User $user, Job $job): bool
    {
        return $user->hasRole(UserRole::Candidate->value)
            && $job->status === JobStatus::Published;
    }

    private function ownsCompany(User $user, Job $job): bool
    {
        return $user->companies()->whereKey($job->company_id)->exists();
    }
}
