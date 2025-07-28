<?php

namespace App\Mail\Expert;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeclinedMail extends Mailable implements ShouldQueue
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
            subject: 'About your application to Shopexperts',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi [Applicant Name],</p>
<p>Thanks for your interest in joining our network. </p>
<p>Your profile doesn’t quite fit our requirements. This could be for a number of reasons. We can't be very specific since this is an automated message. Please feel free to check our requirements on the website and apply again in 3-6 months.</p>
<p>Thank you.</p>
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
