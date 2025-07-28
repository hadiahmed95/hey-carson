<?php

namespace App\Mail;

use App\Models\Role;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterMail extends Mailable implements ShouldQueue
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
        if ($this->user->role_id === Role::CLIENT) {
            return new Envelope(
                subject: 'Account Creation Confirmation - Welcome to Shopexperts!',
            );
        } else {
            return new Envelope(
                subject: 'We’ve received your application. What’s next!',
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        if ($this->user->role_id === Role::CLIENT) {
            return new Content(
                htmlString: "<p>Hello and welcome aboard!</p>
                    <p>Thrilled to have you join Shopexperts, where the best of Shopify talent awaits!</p>
                    <p>Think of us as the cooler cousin of Upwork or Fiverr, but designed and built specifically for businesses on Shopify.</p>
                    <p>We're all about making your journey to find the perfect Shopify guru as smooth as silk. Say goodbye to endless searching!</p>
                    <p>Your client area is ready to roll, and while you haven't posted a project yet, we're here to support you every step of the way.</p>
                    <p>Feel free to explore our features, get answers to your tech queries, or ignite your imagination in our 'project ideas' feed.</p>
                    <p>Feel free to browse around to check out some of our other features - you can use our ‘’Questions’’ feature to have very technical questions answered by our expert network, or browse the ‘’Project ideas’’ feed for inspiration on what Shopify project to work on next.</p>
                    <p>Eagerly anticipating kickstarting your Shopify success story!</p>
                    <p>Warm regards,</p>
                    <p>The Shopexperts Crew</p>
                    <p>P.S. Join the buzz in our Facebook community, <a href='https://www.facebook.com/groups/shopifyentrepreneurs/'>Shopify Entrepreneurs</a>!</p>",
            );
        } else {
            return new Content(
                htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
                    <p>Thanks for submitting your application to join our freelancer network.</p>
                    <p>We review all applications carefully. Our average response time is between 10-30 days, sometimes faster. If accepted, we will contact you for a 1:1 call with our Talent Relations manager to further assess your fit with soft skill and technical questions.</p>
                    <p>Shopexperts Team</p>",
            );
        }
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
