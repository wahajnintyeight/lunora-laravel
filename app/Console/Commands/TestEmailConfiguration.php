<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class TestEmailConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email : The email address to send the test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle(EmailService $emailService)
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->info("Sending test email to: {$email}");
        
        // Display current configuration
        $this->table(
            ['Setting', 'Value'],
            [
                ['Mail Driver', config('mail.default')],
                ['SMTP Host', config('mail.mailers.smtp.host')],
                ['SMTP Port', config('mail.mailers.smtp.port')],
                ['SMTP Encryption', config('mail.mailers.smtp.encryption')],
                ['From Address', config('mail.from.address')],
                ['From Name', config('mail.from.name')],
                ['Queue Connection', config('mail.queue.connection')],
                ['Rate Limiting', config('mail.rate_limiting.enabled') ? 'Enabled' : 'Disabled'],
            ]
        );
        
        // Test email sending
        $success = $emailService->testConfiguration($email);
        
        if ($success) {
            $this->info('✅ Test email sent successfully!');
            $this->info('Please check your inbox and spam folder.');
            
            // Display email stats
            $stats = $emailService->getStats();
            $this->info('Email Statistics:');
            $this->line("- Emails this minute: {$stats['emails_this_minute']}/{$stats['max_per_minute']}");
            $this->line("- Emails this hour: {$stats['emails_this_hour']}/{$stats['max_per_hour']}");
        } else {
            $this->error('❌ Failed to send test email.');
            $this->error('Please check your email configuration and logs for more details.');
            $this->line('Check the mail log: storage/logs/mail.log');
        }
        
        return $success ? 0 : 1;
    }
}
