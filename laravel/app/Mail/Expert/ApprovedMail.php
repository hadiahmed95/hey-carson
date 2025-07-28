<?php

namespace App\Mail\Expert;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Great news! You’ve been accepted into the ShopExperts freelancer network',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>We’re excited to welcome you into our community!</p>
<p>We’re building the best network of freelancers to help serve a new generation of brands, agencies and app developers in the Shopify ecosystem.</p>
<p>We’ve selected you because you’ve made a great impression on our selection team. Our selection process is rigid because we want to put the best possible freelancers in front of our clients.</p>
<p>Joining our network isn’t only about skill - it’s about communication, integrity, community and our collective growth. It’s extremely difficult to take high quality clients from start to end of a project and do that at a high level, then do it again repeatedly for the same client.</p>
<p>Please take some time to review your new expert dashboard. You’ll also receive an invite to our internal private Slack channel. Please introduce yourself in the Shopexperts Dev Lounge on Slack, and prepare for your first project lead.</p>
<p>Our team is available to answer your questions and make your first weeks successful on the Shopexperts platform!</p>
<p>Jonathan Kennedy</p>
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
