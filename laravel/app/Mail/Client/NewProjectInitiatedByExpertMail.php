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

class NewProjectInitiatedByExpertMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $client;
    public User $expert;
    public Project $project;

    /**
     * Create a new message instance.
     */
    public function __construct(User $client, User $expert, Project $project)
    {
        $this->client = $client;
        $this->expert = $expert;
        $this->project = $project;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->expert->first_name} has initiated a new project for you on Shopexperts!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Dear {$this->client->first_name} {$this->client->last_name},</p>
<p>{$this->expert->first_name} {$this->expert->last_name} has initiated a new project for you on Shopexperts. We wanted to provide you with a bit of context around this.</p>
<p>Here's what you need to do next:</p>
<ol>
    <li><strong>Log in to the dashboard:</strong> Simply log in to your Shopexperts account to view the details of your new project.</li>
    <li><strong>Review the project:</strong> Once logged in, you'll be able to see the project details and start collaborating with {$this->expert->first_name} right away.</li>
    <li><strong>Manage your project:</strong> Use our dashboard to track the progress of your project, communicate with your expert, and manage all the details.</li>
</ol>
<p>If you have any questions or need assistance, feel free to reply to this email.</p>
<p>We are excited to continue our collaboration and help you achieve your goals!</p>
<p>Best regards,<br/>The Shopexperts Team</p>",
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
