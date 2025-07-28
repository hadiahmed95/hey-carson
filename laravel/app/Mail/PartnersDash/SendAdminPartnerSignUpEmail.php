<?php

namespace App\Mail\PartnersDash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAdminPartnerSignUpEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Mail data.
     *
     * @var array
     */
    public array $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->data = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("A new company is interested in joining the Partner Program.")
            ->with($this->data)
            ->view('emails.partners.on-partner-signup-admin');
    }
}
