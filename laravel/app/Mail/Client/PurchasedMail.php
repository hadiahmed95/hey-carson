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

class PurchasedMail extends Mailable implements ShouldQueue
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
            subject: 'Congrats! You’ve purchased a bundle of pre-paid hours!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Hi {$this->user->first_name} {$this->user->last_name}</p>
<p>We’ve received your payment for the bundle of pre-paid hours. These pre-paid hours are now assigned to your balance. You can use these hours with any expert, on any project. Aside from the significant savings on the per hour rate, you can also expect express turnaround times when you use these hours.</p>

<p>To use your pre-paid hours, simply select <strong>‘’Use prepaid hours balance’’</strong> next time you accept a project offer (quote) and proceed to make the project payment using your balance. For this to appear as an option, you’ll need to have more pre-paid hours in your balance, than the number of hours the offer is for. </p>

<p><a href='http://app.shopexperts.com/client'>[Go to Dashboard]</a></p>
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
