<?php

namespace App\Mail\PartnersDash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendClaimAppProfileEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = [
            'name' => $data['name'],
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Thanks for claiming your developer profile')
            ->view('emails.partners.on-claim-profile', [
                'name' => $this->data['name']
            ]);
    }
}
