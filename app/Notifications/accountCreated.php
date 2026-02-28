<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class accountCreated extends Notification implements ShouldQueue
{
    use Queueable;
    public $date;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        //Get the admins subscription table
        $subscription = $notifiable->subscription('default');

        //Send a welcome email, if they have a valid subscription, and include the date their trial is over
        if ($subscription && $subscription->trial_ends_at) {
            Log::info('in here all is fine');
            Log::info($subscription->trial_ends_at);
            $this->date = Carbon::parse($subscription->trial_ends_at)->format('F j, Y');
            return ['mail'];

        } else {
            Log::warning("No subscription found for Admin ID: {$notifiable->id}");
            return ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Created')
            ->view('emails.account-created', ['date' => $this->date]);
    }

}
