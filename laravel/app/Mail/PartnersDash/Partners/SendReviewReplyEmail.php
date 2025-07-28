<?php

namespace App\Mail\PartnersDash\Partners;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReviewReplyEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'theme_name' => '',
        'developer_name' => '',
        'user_name' => '',
        'theme_url' => '',
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
        return $this->subject("New Reply from {$this->data['developer_name']} to your {$this->data['theme_name']} theme review.")
            ->with($this->data)
            ->view('emails.partners.on-review-reply');
    }
}
