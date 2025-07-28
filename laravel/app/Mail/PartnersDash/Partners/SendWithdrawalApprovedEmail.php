<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWithdrawalApprovedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [

    ];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Your request to withdraw from your Partner balance has been processed.")
            ->with($this->data)
            ->view('emails.partners.on-withdrawal-approved');
    }
}
