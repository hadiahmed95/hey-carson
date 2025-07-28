<?php

namespace App\Mail\PartnersDash\QA;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendThemeAnswerPublishedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data = [
        'name' => '',
        'email' => '',
        'url' => ''
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
        return $this->subject("Great news! There’s an answer to your question")
            ->with($this->data)
            ->view('emails.qa.on-theme_answer-published');
    }
}
