<?php

namespace App\Mail\PartnersDash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendClaimAppProfileAdminEmail extends Mailable implements ShouldQueue
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
            'company'       => $data['company'],
            'website'       => $data['website'],
            'name'          => $data['name'],
            'email'         => $data['email'],
            'developer'     => $data['developer']
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.website.claim-app-profile', [
            'company'    => $this->data['company'],
            'website'    => $this->data['website'],
            'name'       => $this->data['name'],
            'email'      => $this->data['email'],
            'developer'  => $this->data['developer']
        ]);
    }
}
