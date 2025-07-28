<?php

namespace App\Listeners;

use App\Events\SendEmail;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Support\Facades\Mail;


class SendEmailHandler
{
    /**
     * Handle the event.
     *
     * @param SendEmail $event
     * @return void
     */
    public function handle(SendEmail $event): void
    {
        try {
            $template = $event->template;
            $recipient = $event->recipient;

            if ($event->delay) {
                Mail::to($recipient)->later(
                    now()->addMinutes($event->delay),
                    $template
                );
            } else {
                Mail::to($recipient)->queue($template);
            }
        } catch (\Exception $exception) {
            Bugsnag::notifyException($exception);
        }
    }
}
