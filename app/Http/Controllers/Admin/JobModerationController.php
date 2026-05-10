<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Support\Enums\JobStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobModerationController extends Controller
{
    public function index(): View
    {
        $jobs = Job::query()
            ->where('status', JobStatus::PendingReview->value)
            ->with('company', 'creator')
            ->latest()
            ->paginate(20);

        return view('admin.moderation.index', ['jobs' => $jobs]);
    }

    public function approve(Job $job): RedirectResponse
    {
        $this->authorize('publish', $job);

        $job->update([
            'status' => JobStatus::Published->value,
            'published_at' => now(),
        ]);

        activity()->performedOn($job)->causedBy(request()->user())->log('job.approved');

        return back()->with('status', __('Oferta opublikowana.'));
    }

    public function reject(Request $request, Job $job): RedirectResponse
    {
        $this->authorize('publish', $job);

        $validated = $request->validate(['reason' => ['required', 'string', 'max:500']]);

        $job->update(['status' => JobStatus::Rejected->value]);

        activity()
            ->performedOn($job)
            ->causedBy($request->user())
            ->withProperties(['reason' => $validated['reason']])
            ->log('job.rejected');

        return back()->with('status', __('Oferta odrzucona.'));
    }
}
