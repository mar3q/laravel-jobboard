<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Application $application)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $job = $this->application->job;

        return (new MailMessage)
            ->subject('Potwierdzenie aplikacji — '.$job->title)
            ->greeting('Cześć '.($notifiable->name ?? '').'!')
            ->line('Dziękujemy za aplikację na stanowisko **'.$job->title.'** w firmie **'.$job->company->name.'**.')
            ->line('Damy znać, gdy pracodawca zapozna się z Twoim zgłoszeniem.')
            ->action('Zobacz ofertę', route('jobs.show', $job));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title,
        ];
    }
}
