<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWithdrawalRequestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'partner_name' => '',
        'amount' => '',
        'commission' => '',
        'date' => '',
        'paypal_email' => ''
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
        return $this->subject("New withdrawal request from partner [{$this->data['partner_name']}]")
            ->with($this->data)
            ->view('emails.partners.on-withdrawal-request');
    }
}
