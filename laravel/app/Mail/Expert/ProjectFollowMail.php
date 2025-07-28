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

class ProjectFollowMail extends Mailable implements ShouldQueue
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
            subject: "Project Follow-up: {$this->client->first_name} {$this->client->last_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>How is the project with {$this->client->first_name} {$this->client->last_name} going? If all is well, no need to reply. If you’re facing difficulties or delays, it’s best to manage the expectations and conversations with the client before it’s too late.</p>
<p>Projects can get overwhelming but it’s 100x better to manage expectations before clients start chasing you for updates and complaining to our team about delays.</p>
<p>So we’re trying to make sure we can work all together to catch and manage every potential issue before it escalates. So reach out to the client or to our internal staff if you need help.</p>
<p>We’ve sent a similar message to the client as well. </p>
<p>If you have something great to share about this project, we’d also love to hear it.</p>
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
