<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Notifications\ApplicationConfirmation;
use App\Notifications\NewApplicationReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendApplicationNotifications implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void
    {
        $application = $event->application->loadMissing(['job.company.members', 'user']);

        $application->user->notify(new ApplicationConfirmation($application));

        $employers = $application->job->company->members;
        if ($employers->isNotEmpty()) {
            Notification::send($employers, new NewApplicationReceived($application));
        }
    }
}
