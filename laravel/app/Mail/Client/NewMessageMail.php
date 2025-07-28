<?php

namespace App\Mail\Client;

use App\Models\Message;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, public Project $project, public Message $message)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: 'reply@shopexperts.com',
            cc: ['hello@shopexperts.com'],
            subject: 'You’ve received a new message'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.client.new-message',
            with: [
                'clientName'        => $this->user->first_name . ' ' . $this->user->last_name,
                'projectName'       => $this->project->name,
                'messageContent'    => $this->message->content,
                'projectId'         => $this->project->id,
                'messageId'         => $this->message->id
            ]
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

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        $messageId = sprintf(
            'project-%d-message-%d@%s',
            $this->project->id,
            $this->message->id,
            parse_url(config('app.url'), PHP_URL_HOST)
        );

        return new Headers(
            $messageId,
        );
    }
}
