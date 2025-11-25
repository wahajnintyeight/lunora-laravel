<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $previousStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $previousStatus = null)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
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
        $statusTitle = $this->getStatusTitle($this->order->status);
        
        return (new MailMessage)
            ->subject('Order Update: ' . $statusTitle . ' - #' . $this->order->order_number)
            ->view('emails.orders.status-update', [
                'user' => $notifiable,
                'order' => $this->order,
                'previousStatus' => $this->previousStatus,
                'statusTitle' => $statusTitle,
                'statusMessage' => $this->getStatusMessage($this->order->status),
                'statusColor' => $this->getStatusColor($this->order->status),
            ]);
    }

    /**
     * Get user-friendly status title
     */
    protected function getStatusTitle(string $status): string
    {
        return match($status) {
            'pending' => 'Order Received',
            'processing' => 'Order Processing',
            'shipped' => 'Order Shipped',
            'delivered' => 'Order Delivered',
            'cancelled' => 'Order Cancelled',
            'refunded' => 'Order Refunded',
            default => ucfirst(str_replace('_', ' ', $status))
        };
    }

    /**
     * Get status-specific message
     */
    protected function getStatusMessage(string $status): string
    {
        return match($status) {
            'pending' => 'We\'ve received your order and it\'s being reviewed by our team.',
            'processing' => 'Great news! Your jewelry is being carefully crafted by our artisans.',
            'shipped' => 'Your order is on its way! You should receive it within 3-5 business days.',
            'delivered' => 'Your order has been delivered! We hope you love your new jewelry.',
            'cancelled' => 'Your order has been cancelled. If you have any questions, please contact us.',
            'refunded' => 'Your refund has been processed and should appear in your account within 5-7 business days.',
            default => 'Your order status has been updated.'
        };
    }

    /**
     * Get status color for styling
     */
    protected function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'shipped' => '#8b5cf6',
            'delivered' => '#10b981',
            'cancelled' => '#ef4444',
            'refunded' => '#6b7280',
            default => '#6b7280'
        };
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'user_id' => $notifiable->id,
            'status' => $this->order->status,
            'previous_status' => $this->previousStatus,
            'sent_at' => now()->toISOString(),
        ];
    }
}