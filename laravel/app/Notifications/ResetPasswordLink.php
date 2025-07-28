<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordLink extends ResetPassword
{
    use Queueable;
    public $isNewDashboard;

    public function __construct($token, $isNewDashboard = true)
    {
        parent::__construct($token);
        $this->isNewDashboard = $isNewDashboard;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $frontendUrl = $this->isNewDashboard
            ? env('NEW_DASH') . '/reset-password'
            : env('OLD_DASH') . '/reset-password';

        $type = $notifiable->role_id === 2
            ? 'client'
            : 'expert';

        $url = $frontendUrl . '?token=' . $this->token . '&email=' . urlencode($notifiable->email) . '&type=' . $type ;

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('Click the button below to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.');
    }
}
