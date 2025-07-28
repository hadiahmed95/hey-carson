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

class ProjectCompletedReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public User $expert, public Project $project, public int $remainingDays)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Action Required: Your project has been marked as completed by the expert! Please confirm",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>{$this->expert->first_name} {$this->expert->last_name} has submitted the project {$this->project->name} for  your final review. If you're happy with the outcome of the project, please confirm. This step allows the expert to receive their funds for the project.</p>
<p>If there are still loose ends, bugs, issues or you don’t feel like the project is quite completed, click the <strong>‘’Not there yet’’</strong> option in the project workroom. </p>
<p>Please refer back to the initial quote and requirements if there are issues. Prompt and clear two-way communication is key, most issues stem from honest misunderstandings. Should you need help from our internal team, please reply to this email.</p>
<p>After {$this->remainingDays} days, if there’s no action on this, the project will be marked as completed.</p>
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
