<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class EmailSentListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        if (!config('mail.logging.enabled') || !config('mail.logging.log_successful')) {
            return;
        }

        $message = $event->message;
        $to = collect($message->getTo())->keys()->first();
        $subject = $message->getSubject();

        Log::channel(config('mail.logging.channel', 'mail'))->info('Email sent successfully', [
            'to' => $to,
            'subject' => $subject,
            'message_id' => $message->getId(),
            'timestamp' => now()->toISOString()
        ]);
    }
}
