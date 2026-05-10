<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Application;
use App\Models\ApplicationStatusChange;
use App\Models\User;
use App\Support\Enums\ApplicationStatus;
use Illuminate\Support\Facades\DB;

final class ChangeApplicationStatusAction
{
    public function execute(User $reviewer, Application $application, ApplicationStatus $to, ?string $note = null): Application
    {
        return DB::transaction(function () use ($reviewer, $application, $to, $note): Application {
            $from = $application->status;

            if ($from === $to) {
                return $application;
            }

            ApplicationStatusChange::create([
                'application_id' => $application->id,
                'from_status' => $from->value,
                'to_status' => $to->value,
                'changed_by' => $reviewer->id,
                'note' => $note,
            ]);

            $application->update([
                'status' => $to->value,
                'reviewed_at' => now(),
                'reviewed_by' => $reviewer->id,
            ]);

            return $application->fresh();
        });
    }
}
