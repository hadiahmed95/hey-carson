<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendEmail
{
    use Dispatchable, SerializesModels;

    public mixed $template;
    public mixed $recipient;
    public mixed $delay;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($recipient, $template, $delay = 0)
    {
        $this->recipient = $recipient;
        $this->template = $template;
        $this->delay = $delay;
    }
}
