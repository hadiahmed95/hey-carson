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

class ProjectOfferMail extends Mailable implements ShouldQueue
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
            subject: 'Finalized Quote for Your Project',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $appUrl = env('APP_URL');
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name},</p>
<p>{$this->expert->first_name} {$this->expert->last_name} has reviewed your project requirements and finalized a quote to bring your project to life. Please take a moment to review the details. If everything looks good, you can:</p>
<ul>
    <li>Pay for the project immediately.</li>
    <li>Opt for pre-paid hours to save and use those hours toward this project.</li>
</ul>
<p><strong>Important Details:</strong></p>
<ul>
    <li>Your quote is valid for 5 days. After this period, it will expire.</li>
    <li>The expert may extend the deadline upon request, but their availability cannot be guaranteed.</li>
</ul>
<p>If you have any questions regarding the quote or project details, we encourage you to:</p>
<ul>
    <li>Discuss the specifics in the project workroom.</li>
    <li>Schedule a call with the expert before making a payment.</li>
</ul>
<p><strong>Pro Tip:</strong> To ensure project success, set clear expectations and maintain regular communication. It’s recommended to:</p>
<ul>
    <li>Confirm the expert’s timeline and progress update schedule.</li>
    <li>Request short written reports every 2–3 days or schedule progress meetings every couple of weeks until the project is completed.</li>
</ul>
<p><a href='{$appUrl}/client/project/{$this->project->id}'>[Go to Project]</a></p>
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
