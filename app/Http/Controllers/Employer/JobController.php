<?php

declare(strict_types=1);

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Models\Job;
use App\Support\Enums\ContractType;
use App\Support\Enums\JobStatus;
use App\Support\Enums\Seniority;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $companyIds = request()->user()->companies()->pluck('companies.id');

        $jobs = Job::query()
            ->whereIn('company_id', $companyIds)
            ->with('company')
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return view('employer.jobs.index', ['jobs' => $jobs]);
    }

    public function create(): View
    {
        $this->authorize('create', Job::class);

        return view('employer.jobs.create', $this->formData());
    }

    public function store(StoreJobRequest $request): RedirectResponse
    {
        $this->authorize('create', Job::class);
        $this->ensureOwnsCompany((int) $request->validated('company_id'));

        $job = Job::create([...$request->validated(), 'created_by' => $request->user()->id]);

        return redirect()->route('employer.jobs.index')->with('status', __('Oferta utworzona.'));
    }

    public function edit(Job $job): View
    {
        $this->authorize('update', $job);

        return view('employer.jobs.edit', [...$this->formData(), 'job' => $job]);
    }

    public function update(StoreJobRequest $request, Job $job): RedirectResponse
    {
        $this->authorize('update', $job);
        $this->ensureOwnsCompany((int) $request->validated('company_id'));

        $job->update($request->validated());

        return redirect()->route('employer.jobs.index')->with('status', __('Oferta zaktualizowana.'));
    }

    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);
        $job->delete();

        return back()->with('status', __('Oferta usunięta.'));
    }

    private function ensureOwnsCompany(int $companyId): void
    {
        abort_unless(
            request()->user()->companies()->whereKey($companyId)->exists()
                || request()->user()->hasRole('admin'),
            403
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function formData(): array
    {
        return [
            'companies' => request()->user()->companies()->orderBy('name')->get(),
            'seniorities' => Seniority::cases(),
            'contractTypes' => ContractType::cases(),
            'statuses' => [JobStatus::Draft, JobStatus::PendingReview, JobStatus::Published],
        ];
    }
}
