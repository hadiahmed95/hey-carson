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

class ProjectFinishedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public User $client, public Project $project)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Yes! {$this->client->first_name} {$this->client->last_name} has confirmed the project completed",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>{$this->client->first_name} {$this->client->last_name} has confirmed the project as completed or it’s been more than 72 hours and the system automatically closed the project. </p>
<p>{$this->project->name}</p>
<p>This means the funds from this project have been released to your balance. You will be able to withdraw these funds from your balance in as little as 10 days, sometimes faster.</p>
<p>This is also a great time to ask for a review and/or offer a follow-up project from this client if you haven’t already. </p>
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
