<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class LowStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lowStockProducts;
    protected $threshold;

    /**
     * Create a new notification instance.
     */
    public function __construct(Collection $lowStockProducts, int $threshold = 5)
    {
        $this->lowStockProducts = $lowStockProducts;
        $this->threshold = $threshold;
        $this->onQueue(config('mail.queue.queue', 'emails'));
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $productCount = $this->lowStockProducts->count();
        
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $productCount . ' Product(s) Need Attention - ' . config('app.name'))
            ->view('emails.admin.low-stock-alert', [
                'admin' => $notifiable,
                'lowStockProducts' => $this->lowStockProducts,
                'threshold' => $this->threshold,
                'productCount' => $productCount,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'admin_id' => $notifiable->id,
            'product_count' => $this->lowStockProducts->count(),
            'threshold' => $this->threshold,
            'products' => $this->lowStockProducts->pluck('id')->toArray(),
            'sent_at' => now()->toISOString(),
        ];
    }
}