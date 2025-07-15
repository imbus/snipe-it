<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class WelcomeNotification extends Notification
{
    use Queueable;

    public string $passwordResetUrl;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public User $user)
    {
        $this->user->token = Password::getRepository()->create($user);

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {

        \Log::error(print_r($this->user->toArray(), true));

        return (new MailMessage())
            ->subject(trans('mail.welcome', ['name' => $this->user->first_name.' '.$this->user->last_name]))
            ->markdown('notifications.Welcome', $this->user->toArray());
    }
}
