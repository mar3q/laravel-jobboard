<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Job;
use Illuminate\Support\Facades\Cache;

class JobObserver
{
    public function saved(Job $job): void
    {
        Cache::forget('jobs.featured');
    }

    public function deleted(Job $job): void
    {
        Cache::forget('jobs.featured');
    }
}
