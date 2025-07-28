<?php

namespace App\Mail\PartnersDash\QA;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendAppQuestionSubmittedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'name' => '',
        'email' => '',
        'app_url' => '',
        'theme_url' => ''
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
        return $this->subject("Your question has been submitted")
            ->with($this->data)
            ->view('emails.qa.on-app_question-submitted');
    }
}
