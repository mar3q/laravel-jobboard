<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use App\Support\Enums\JobStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = Cache::remember('admin.stats', now()->addMinutes(5), fn () => [
            'users' => User::count(),
            'companies' => Company::count(),
            'jobs_total' => Job::count(),
            'jobs_published' => Job::query()->where('status', JobStatus::Published->value)->count(),
            'jobs_pending' => Job::query()->where('status', JobStatus::PendingReview->value)->count(),
            'applications' => Application::count(),
            'applications_today' => Application::query()->whereDate('created_at', today())->count(),
        ]);

        $recentActivity = Activity::query()->latest()->limit(50)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
        ]);
    }
}
