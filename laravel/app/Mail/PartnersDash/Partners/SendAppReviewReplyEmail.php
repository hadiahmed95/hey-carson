<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAppReviewReplyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'app_name' => '',
        'developer_name' => '',
        'user_name' => '',
        'app_url' => '',
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
        return $this->subject("New Reply from {$this->data['developer_name']} to your {$this->data['app_name']} app review.")
            ->with($this->data)
            ->view('emails.partners.on-app_review-reply');
    }
}
