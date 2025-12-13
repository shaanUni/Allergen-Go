<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DishShareNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    //Email of admin sharing the dishes
    public string $email;

    //Unique identifier to dishShare record in DB
    public string $uuid;


    /**
     * Create a new notification instance.
     */
    public function __construct($email, $uuid)
    {
        //
        $this->email = $email;
        $this->uuid = $uuid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
      
        return (new MailMessage)
            ->subject('Dish share request')
            ->view('emails.dish-share', [
                'email' => $this->email,
                'uuid' => $this->uuid,
            ]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
