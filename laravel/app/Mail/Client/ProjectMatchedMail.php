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

class ProjectMatchedMail extends Mailable implements ShouldQueue
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
            subject: 'Great news! You’ve been matched with an expert.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>Great news! We found the ideal expert for your project - {$this->expert->first_name} {$this->expert->last_name}</p>
<p>{$this->expert->first_name} {$this->expert->last_name} will initiate a chat with you within the project workroom shortly. Feel free to start the chat immediately.</p>
<p>They may ask for clarification or more detail about your project and goals, to make sure the best possible solutions are being recommended.</p>
<p>Expect proactive and consistent communication, fast response times to your questions, and a clear and accurate quote for your project. Should you receive a quote, you will have 5 days to accept and pay, this can be extended on request. Once the payment is made, the expert will begin the work and share regular updates with you.</p>
<p>For added peace of mind and security, your project payments are held with us in escrow until you confirm and mark the project as completed.</p>
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
