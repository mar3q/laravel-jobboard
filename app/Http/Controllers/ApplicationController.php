<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SubmitApplicationAction;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Job;
use App\Support\Enums\ApplicationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    public function create(Job $job): View
    {
        $this->authorize('apply', $job);

        return view('applications.create', ['job' => $job]);
    }

    public function store(StoreApplicationRequest $request, Job $job, SubmitApplicationAction $action): RedirectResponse
    {
        $this->authorize('apply', $job);

        $action->execute(
            candidate: $request->user(),
            job: $job,
            data: $request->validated(),
            cvFile: $request->file('cv'),
        );

        return redirect()
            ->route('applications.index')
            ->with('status', __('Aplikacja wysłana!'));
    }

    public function index(): View
    {
        $applications = Application::query()
            ->where('user_id', request()->user()->id)
            ->with('job.company')
            ->latest()
            ->paginate(15);

        return view('applications.index', ['applications' => $applications]);
    }

    public function withdraw(Application $application): RedirectResponse
    {
        $this->authorize('withdraw', $application);

        $application->update(['status' => ApplicationStatus::Withdrawn->value]);

        return back()->with('status', __('Aplikacja wycofana.'));
    }

    public function downloadCv(Cv $cv): StreamedResponse|Response
    {
        if ($cv->user_id !== request()->user()->id) {
            $applications = Application::query()
                ->where('cv_id', $cv->id)
                ->with('job.company.members')
                ->get();

            $allowed = $applications->some(
                fn (Application $a) => $a->job->company->members->contains('id', request()->user()->id)
            );

            abort_unless($allowed, 403);
        }

        return Storage::disk('local')->download($cv->path, $cv->original_filename);
    }
}
