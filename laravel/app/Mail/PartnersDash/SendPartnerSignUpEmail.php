<?php

namespace App\Mail\PartnersDash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPartnerSignUpEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Mail data.
     *
     * @var array
     */
    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->replyTo(env('PARTNERS_EMAIL'), 'HeyCarson Partnerships')
            ->subject("Thank you for registering for our partnership program.")
            ->with($this->mailData)
            ->view('emails.new-partner-signup-email');
    }
}
