<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReviewInviteEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'developer_name' => '',
        'developer_email' => '',
        'theme_name' => '',
        'theme_url' => '',
        'email' => '',
        'description' => '',
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
        return $this->withSymfonyMessage(function ($message) {
                $message->getHeaders()->addTextHeader('X-PM-Message-Stream', env('MAIL_BULK_STREAM'));
            })
            ->subject("{$this->data['developer_name']} is inviting you to review {$this->data['theme_name']} theme.")
            ->with($this->data)
            ->view('emails.partners.on-review-invite');
    }
}
