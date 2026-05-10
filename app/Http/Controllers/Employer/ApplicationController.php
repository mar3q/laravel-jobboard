<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employer;

use App\Actions\ChangeApplicationStatusAction;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Support\Enums\ApplicationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Job $job): View
    {
        $this->authorize('update', $job);

        $applications = $job->applications()
            ->with(['user', 'cv', 'statusChanges.changedBy'])
            ->latest()
            ->paginate(20);

        return view('employer.applications.index', [
            'job' => $job,
            'applications' => $applications,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    public function show(Application $application): View
    {
        $this->authorize('view', $application);

        $application->load(['user', 'job.company', 'cv', 'statusChanges.changedBy']);

        $cvUrl = $application->cv !== null
            ? URL::signedRoute('cvs.download', ['cv' => $application->cv->id], now()->addMinutes(15))
            : null;

        return view('employer.applications.show', [
            'application' => $application,
            'cvUrl' => $cvUrl,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    public function changeStatus(Request $request, Application $application, ChangeApplicationStatusAction $action): RedirectResponse
    {
        $this->authorize('changeStatus', $application);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_column(ApplicationStatus::cases(), 'value'))],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $action->execute(
            reviewer: $request->user(),
            application: $application,
            to: ApplicationStatus::from($validated['status']),
            note: $validated['note'] ?? null,
        );

        return back()->with('status', __('Status zaktualizowany.'));
    }
}
