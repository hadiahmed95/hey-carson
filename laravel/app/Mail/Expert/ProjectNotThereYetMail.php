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

class ProjectNotThereYetMail extends Mailable implements ShouldQueue
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
            subject: "Project marked as not completed yet! Next steps..",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>{$this->client->first_name} {$this->client->last_name} has indicated that project: {$this->project->name}s not quite done yet. Please get in touch with them within the project workroom to understand what the issue could be.</p>
<p>Resolving any issue or misunderstanding quickly and without delay is the best way to avoid escalations.</p>
<p>If there’s something our support team can help with, please let us know.</p>
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
