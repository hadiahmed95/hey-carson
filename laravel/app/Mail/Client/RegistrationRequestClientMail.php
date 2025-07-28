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

class RegistrationRequestClientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $expert, public Project $project, public String $clientName, public String $clientEmail)
    {
        $this->clientEmail = urlencode($clientEmail);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->expert->first_name} {$this->expert->last_name} created a Shopify project for you on Shopexperts! What’s next?",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $appUrl = env('APP_URL');
        return new Content(
            htmlString: "<p>Dear {$this->clientName},</p>
<p>We are excited to inform you that {$this->expert->first_name} {$this->expert->last_name} has created a project for you on Shopexperts. To get started, please complete your client profile by clicking on the link below:</p>
<p><a href='{$appUrl}/client/register/?email={$this->clientEmail}'>Click here to complete your profile</a></p>
<p>Once your profile is completed, you will have access to our dashboard where you can track the progress of your project, communicate with your expert, and manage all the details.</p>
<p>Here’s what you need to do:</p>
<ol>
    <li>Click on the link above to complete your profile.</li>
    <li>Log in to the dashboard to see your project details.</li>
    <li>Start collaborating with {$this->expert->first_name} {$this->expert->last_name}!</li>
</ol>
<p>If you have any questions or need assistance, simply reply to this email.</p>
<p>We look forward to working with you and helping you achieve your goals!</p>
<p>Best regards,</p>
<p>The Shopexperts Team</p>",
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
