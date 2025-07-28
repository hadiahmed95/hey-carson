<?php

namespace App\Mail\Expert;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectCompletedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public User $client)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You’ve marked the project as completed",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>Nice job! You’ve marked the project with {$this->client->first_name} {$this->client->last_name} as completed. What’s next?</p>
<p>They have 72 hours to confirm the completion on their end, or let you know it’s not yet done. If they take no action, the project will be automatically confirmed as completed and your funds will be released to your Shopexperts balance.</p>
<p>Please be prompt with any follow-up communication or issues they may want to address.</p>
<p>Shopexperts Team</p>",
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
