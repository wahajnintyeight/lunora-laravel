<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Jobs\ProcessFailedEmail;
use Exception;

class EmailService
{
    /**
     * Send an email with rate limiting and error handling
     */
    public function send(Mailable $mailable, string $to, bool $queue = true): bool
    {
        try {
            // Check rate limiting
            if (!$this->checkRateLimit()) {
                Log::warning('Email rate limit exceeded', [
                    'to' => $to,
                    'mailable' => get_class($mailable)
                ]);
                return false;
            }

            // Send email (queued or immediate)
            if ($queue && config('mail.queue.connection')) {
                Mail::to($to)->queue($mailable);
                $this->logEmail($to, get_class($mailable), 'queued');
            } else {
                Mail::to($to)->send($mailable);
                $this->logEmail($to, get_class($mailable), 'sent');
            }

            // Update rate limiting counters
            $this->updateRateLimitCounters();

            return true;

        } catch (Exception $e) {
            Log::error('Failed to send email', [
                'to' => $to,
                'mailable' => get_class($mailable),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Queue failed email for retry if queuing is enabled
            if ($queue && config('mail.queue.connection')) {
                ProcessFailedEmail::dispatch($mailable, $to, $e)
                    ->onQueue(config('mail.queue.queue', 'emails'))
                    ->delay(now()->addMinutes(1));
                
                Log::channel('mail')->info('Failed email queued for retry', [
                    'to' => $to,
                    'mailable' => get_class($mailable),
                ]);
            }

            return false;
        }
    }

    /**
     * Send multiple emails with batch processing
     */
    public function sendBatch(array $emails): array
    {
        $results = [];

        foreach ($emails as $email) {
            $to = $email['to'];
            $mailable = $email['mailable'];
            $queue = $email['queue'] ?? true;

            $results[$to] = $this->send($mailable, $to, $queue);

            // Small delay between emails to prevent overwhelming SMTP server
            if (!$queue) {
                usleep(100000); // 0.1 second delay
            }
        }

        return $results;
    }

    /**
     * Check if we're within rate limits
     */
    protected function checkRateLimit(): bool
    {
        if (!config('mail.rate_limiting.enabled')) {
            return true;
        }

        $throttleKey = config('mail.rate_limiting.throttle_key');
        $maxPerMinute = config('mail.rate_limiting.max_emails_per_minute');
        $maxPerHour = config('mail.rate_limiting.max_emails_per_hour');

        // Check per-minute limit
        $minuteKey = $throttleKey . ':minute:' . now()->format('Y-m-d-H-i');
        $minuteCount = Cache::get($minuteKey, 0);
        if ($minuteCount >= $maxPerMinute) {
            return false;
        }

        // Check per-hour limit
        $hourKey = $throttleKey . ':hour:' . now()->format('Y-m-d-H');
        $hourCount = Cache::get($hourKey, 0);
        if ($hourCount >= $maxPerHour) {
            return false;
        }

        return true;
    }

    /**
     * Update rate limiting counters
     */
    protected function updateRateLimitCounters(): void
    {
        if (!config('mail.rate_limiting.enabled')) {
            return;
        }

        $throttleKey = config('mail.rate_limiting.throttle_key');

        // Update per-minute counter
        $minuteKey = $throttleKey . ':minute:' . now()->format('Y-m-d-H-i');
        Cache::increment($minuteKey);
        Cache::expire($minuteKey, 60); // Expire after 1 minute

        // Update per-hour counter
        $hourKey = $throttleKey . ':hour:' . now()->format('Y-m-d-H');
        Cache::increment($hourKey);
        Cache::expire($hourKey, 3600); // Expire after 1 hour
    }

    /**
     * Log email sending activity
     */
    protected function logEmail(string $to, string $mailable, string $status): void
    {
        if (!config('mail.logging.enabled')) {
            return;
        }

        $shouldLog = false;
        if ($status === 'sent' && config('mail.logging.log_successful')) {
            $shouldLog = true;
        } elseif ($status === 'failed' && config('mail.logging.log_failed')) {
            $shouldLog = true;
        } elseif ($status === 'queued') {
            $shouldLog = true;
        }

        if ($shouldLog) {
            Log::channel(config('mail.logging.channel', 'mail'))->info('Email activity', [
                'to' => $to,
                'mailable' => $mailable,
                'status' => $status,
                'timestamp' => now()->toISOString()
            ]);
        }
    }

    /**
     * Get email sending statistics
     */
    public function getStats(): array
    {
        $throttleKey = config('mail.rate_limiting.throttle_key');
        
        $minuteKey = $throttleKey . ':minute:' . now()->format('Y-m-d-H-i');
        $hourKey = $throttleKey . ':hour:' . now()->format('Y-m-d-H');

        return [
            'emails_this_minute' => Cache::get($minuteKey, 0),
            'emails_this_hour' => Cache::get($hourKey, 0),
            'max_per_minute' => config('mail.rate_limiting.max_emails_per_minute'),
            'max_per_hour' => config('mail.rate_limiting.max_emails_per_hour'),
            'rate_limiting_enabled' => config('mail.rate_limiting.enabled'),
        ];
    }

    /**
     * Test email configuration
     */
    public function testConfiguration(string $testEmail): bool
    {
        try {
            $testMailable = new \App\Mail\TestEmail();
            return $this->send($testMailable, $testEmail, false);
        } catch (Exception $e) {
            Log::error('Email configuration test failed', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}