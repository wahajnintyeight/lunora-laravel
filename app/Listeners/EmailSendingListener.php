<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class EmailSendingListener
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
    public function handle(MessageSending $event): void
    {
        if (!config('mail.logging.enabled')) {
            return;
        }

        $message = $event->message;
        $to = collect($message->getTo())->keys()->first();
        $subject = $message->getSubject();

        Log::channel(config('mail.logging.channel', 'mail'))->info('Email sending', [
            'to' => $to,
            'subject' => $subject,
            'timestamp' => now()->toISOString()
        ]);
    }
}
