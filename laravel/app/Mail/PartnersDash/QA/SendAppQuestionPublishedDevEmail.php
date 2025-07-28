<?php

namespace App\Mail\PartnersDash\QA;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAppQuestionPublishedDevEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'name' => '',
        'email' => '',
        'url' => '',
        'app_name' => '',
        'partner_dash_url' => ''
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
            ->subject("Someone has a question about {$this->data['app_name']}")
            ->with($this->data)
            ->view('emails.qa.on-app_question-published-dev');
    }
}
