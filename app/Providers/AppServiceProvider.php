<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Job;
use App\Observers\JobObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Job::observe(JobObserver::class);
        Paginator::useTailwind();
    }
}
