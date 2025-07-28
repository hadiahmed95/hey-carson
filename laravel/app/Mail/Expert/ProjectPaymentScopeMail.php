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

class ProjectPaymentScopeMail extends Mailable implements ShouldQueue
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
            subject: "Sweet! An add-on payment has been made!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>Great news! {$this->client->first_name} {$this->client->last_name} has made an add-on payment for the project: {$this->project->name}.</p>
<p>Follow-up payments on projects are a great sign! It usually means a good level of trust has been established! Be sure to keep delivering at the highest possible level for this client.</p>
<p><a href='http://app.shopexperts.com/expert/project/" . $this->project->id . "'>[Go to Project]</a></p>
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
