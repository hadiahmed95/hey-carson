<?php

namespace App\Mail\Client;

use App\Models\Offer;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectOfferReminderMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public User $expert, public Project $project, public Offer $offer, public int $remainingDays)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: Your Project Quote Awaits. What’s next?',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>A project quote for {$this->project->name} is waiting for your action to get started.</p>
<p>The quote will expire after {$this->remainingDays} more days. Please be sure to accept and pay, or decline the quote if you don’t plan on moving forward with the project at this time. </p>
<p>You may also continue to clarify or negotiate quote details directly with the expert in the project workroom. </p>
<p>Please reach out to our internal admin team [here] for general support or questions. </p>
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
