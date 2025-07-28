<?php

namespace App\Mail\PartnersDash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * User's reset password token.
     *
     * @var string
     */
    public $token;

    /**
     * User's email.
     *
     * @var string
     */
    public $email;

    public string $url = '';

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $email
     *
     * @return void
     */
    public function __construct(string $token, string $email, string $url = '')
    {
        $this->token = $token;
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->url) {
            $url = $this->url;
        } else {
            $url = url(
                config('app.url') .
                route('password.reset', [$this->token, 'email' => encrypt($this->email)], false)
            );
        }

        return $this->to($this->email)
            ->subject('Reset Password')
            ->with([
                'email' => $this->email,
                'token' => $this->token,
                'resetPasswordLink' => $url,
            ])
            ->view('emails.reset_password');
    }
}
