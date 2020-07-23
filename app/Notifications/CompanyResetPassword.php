<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class CompanyResetPassword extends Notification
{

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                        ->subject(Lang::getFromJson('Company Password Reset'))
                        ->greeting(Lang::getFromJson('Hello') . ' ' . $notifiable->name)
                        ->from([config('mail.from.address') => config('mail.from.name')])
                        ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
                        ->action(Lang::getFromJson('Reset Password'), url('company/password/reset', $this->token))
                        ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'))
                        ->salutation(Lang::getFromJson('Regards,'));
    }

}
