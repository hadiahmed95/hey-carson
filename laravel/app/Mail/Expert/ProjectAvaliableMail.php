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

class ProjectAvaliableMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public Project $project)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Request: {$this->project->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $source = $this->project->client->source;
        if ($source === "Website Direct") {
            $importantLine = "<p><strong>Source:</strong> {$source}.</p>";
        } else {
            $importantLine = "<p><strong>Referred by:</strong> {$source}.</p>";
        }

        return new Content(
            htmlString: "
            <p>Hi {$this->user->first_name} {$this->user->last_name},</p>
            <p>A new request titled <strong>{$this->project->name}</strong> is now available.</p>
            <p><strong>Shopify Plan:</strong> {$this->project->client->shopify_plan}.</p>
            {$importantLine}
            <p><a href='https://app.shopexperts.com/expert/login'>Log in here</a> to check the projects list and view the project details.</p>
            <p><strong>Keep in mind that once you open a project, you have 5 minutes to read and assess it.</strong></p>
            <p>If no action is taken within the first 5 minutes, the request will be released to the list of available projects, as only one expert may read or claim a project at a time.</p>
            <p>If you decide to claim the project, the client will be notified, and you should introduce yourself to start the conversation immediately.</p>
            <p>Thank you,</p>
            <p>Shopexperts Admin</p>
        ",
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
