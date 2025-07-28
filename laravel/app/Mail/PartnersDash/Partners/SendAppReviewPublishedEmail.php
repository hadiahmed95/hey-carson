<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAppReviewPublishedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'app_name' => '',
        'developer_name' => '',
        'login_url' => '',
        'email' => '',
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
        return $this->subject("A new customer review for {$this->data['app_name']} has been published at HeyCarson.")
            ->with($this->data)
            ->view('emails.partners.on-app_review-published');
    }
}
