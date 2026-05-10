<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\ApplicationSubmitted;
use App\Models\Application;
use App\Models\Cv;
use App\Models\Job;
use App\Models\User;
use App\Support\Enums\ApplicationStatus;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

final class SubmitApplicationAction
{
    /**
     * @param  array{cover_letter?: ?string, contact_email: string, contact_phone?: ?string}  $data
     */
    public function execute(User $candidate, Job $job, array $data, ?UploadedFile $cvFile = null): Application
    {
        return DB::transaction(function () use ($candidate, $job, $data, $cvFile): Application {
            $cv = $cvFile !== null ? $this->storeCv($candidate, $cvFile) : null;

            try {
                $application = Application::create([
                    'job_id' => $job->id,
                    'user_id' => $candidate->id,
                    'cv_id' => $cv?->id,
                    'status' => ApplicationStatus::Pending->value,
                    'cover_letter' => $data['cover_letter'] ?? null,
                    'contact_email' => $data['contact_email'],
                    'contact_phone' => $data['contact_phone'] ?? null,
                ]);
            } catch (UniqueConstraintViolationException) {
                throw ValidationException::withMessages([
                    'job' => __('Aplikowałeś już na tę ofertę.'),
                ]);
            }

            $job->increment('applications_count');

            ApplicationSubmitted::dispatch($application);

            return $application;
        });
    }

    private function storeCv(User $user, UploadedFile $file): Cv
    {
        $path = $file->store("cvs/{$user->id}", ['disk' => 'local']);

        return Cv::create([
            'user_id' => $user->id,
            'label' => 'CV — '.now()->format('Y-m-d'),
            'original_filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'size_bytes' => $file->getSize() ?: 0,
            'is_default' => false,
        ]);
    }
}
