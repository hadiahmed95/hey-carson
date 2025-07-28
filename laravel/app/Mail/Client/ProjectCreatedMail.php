<?php

namespace App\Mail\Client;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectCreatedMail extends Mailable implements ShouldQueue
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
            subject: 'Your project has been submitted! What’s next?',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>Your project has been submitted to our team. We’re reviewing it and will match you with an expert within a few hours, sometimes faster.</p>
<p>Please keep your eyes on your inbox, we will notify you as soon as we’ve matched you with  an expert in our network. </p>
<p><strong>How do we decide which expert to match you with?</strong></p>
<p>We look at the elements of your project and manually match it with an expert based on skills, experience required and availability. We also ensure timezone compatibility. Matches are made so that experts are available to assess and start work without delays. In almost all cases, clients get estimates within a couple of days, sometimes faster, and projects start as soon as a payment is made. You’re not being put in a queue 2-3 weeks out as you would with a typical agency or  freelancers. </p>
<p>Please let us know if you have any questions. </p>
<p><a href='http://app.shopexperts.com/client'>[Go to Client area]</a></p>
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
