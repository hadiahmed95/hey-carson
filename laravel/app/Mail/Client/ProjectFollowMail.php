<?php

namespace App\Mail\Client;

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
    public function __construct(public User $user, public User $expert, public Project $project)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Project Follow-up: {$this->expert->first_name} {$this->expert->last_name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>This is Ana from the Shopexperts internal team!</p>
<p>This is just a friendly follow-up to make sure all is going well with {$this->expert->first_name} {$this->expert->last_name} on your project with them.</p>
<p>If all is well, no need to reply. If you’re facing difficulties or delays, please let us know so we can support both of you in this process.</p>
<p>Our goal is to make sure the experience is smooth for everyone and that projects start off strong and finish successfully.</p>
<p>We’ve sent a similar message to the expert as well.</p>
<p>If you have something great to share about this project, we’d also love to hear it.</p>
<p><a href='http://app.shopexperts.com/client/project/" . $this->project->id . "'>[Go to Project]</a></p>
<p>Shopexperts Team</p>
<p>Join our private user community on Slack at Shopify Entrepreneurs</p>",
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
