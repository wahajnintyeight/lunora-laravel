<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Exception;

class ProcessFailedEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    protected $mailable;
    protected $to;
    protected $originalException;

    /**
     * Create a new job instance.
     */
    public function __construct(Mailable $mailable, string $to, Exception $originalException = null)
    {
        $this->mailable = $mailable;
        $this->to = $to;
        $this->originalException = $originalException;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->to)->send($this->mailable);
            
            Log::channel('mail')->info('Failed email retry successful', [
                'to' => $this->to,
                'mailable' => get_class($this->mailable),
                'attempt' => $this->attempts(),
            ]);
            
        } catch (Exception $e) {
            Log::channel('mail')->error('Failed email retry failed', [
                'to' => $this->to,
                'mailable' => get_class($this->mailable),
                'attempt' => $this->attempts(),
                'error' => $e->getMessage(),
            ]);
            
            // If this is the final attempt, log the permanent failure
            if ($this->attempts() >= $this->tries) {
                Log::channel('mail')->critical('Email permanently failed after all retries', [
                    'to' => $this->to,
                    'mailable' => get_class($this->mailable),
                    'original_error' => $this->originalException?->getMessage(),
                    'final_error' => $e->getMessage(),
                ]);
            }
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::channel('mail')->critical('Email job permanently failed', [
            'to' => $this->to,
            'mailable' => get_class($this->mailable),
            'error' => $exception->getMessage(),
            'original_error' => $this->originalException?->getMessage(),
        ]);
    }
}
