<?php

namespace App\Mail\Client;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterClientAndProjectMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Project Submission Confirmation - Welcome to Shopexperts!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hey there!</p>
                <p>Big welcome to Shopexperts—your go-to for top-notch Shopify pros! It's like Upwork or Fiverr, only way cooler and with the best of the best talent.</p>
                <p>We're here to zap away the hassle of finding the perfect fit for your Shopify projects. No more freelancer roulette!</p>
                <p>Check your client area—your account's all set, and we're about to pair you with a stellar Shopexpert, stat!</p>
                <p>Dive into our features while you're here—get techy questions answered or spark your next big idea in our 'Project ideas' feed.</p>
                <p>Can't wait to connect you with your first expert!</p>
                <p>Cheers,</p>
                <p>The Shopexperts Crew</p>
                <p>P.S. Join the buzz in our Facebook community, <a href='https://www.facebook.com/groups/shopifyentrepreneurs/'>Shopify Entrepreneurs</a>!</p>",
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
