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

class ProjectQuoteEditMail extends Mailable implements ShouldQueue
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
            subject: 'Updated Project Quote for Your Review',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>{$this->expert->first_name} {$this->expert->last_name} has updated your project quote to better align with your requirements.</p>
<p>Please review the revised quote details and, if everything looks good, you can proceed with the payment.</p>
<p><em>Your quote will be valid for 5 days from the date of this email.</em></p>
<p>If you have any questions about the changes, feel free to discuss them in the project workroom or schedule a call with your expert.</p>
<p>Clear communication is key to a successful project, so we encourage you to keep the dialogue open.</p>
<p><a href='http://app.shopexperts.com/client/project/" . $this->project->id . "'>[Review Updated Quote]</a></p>
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
