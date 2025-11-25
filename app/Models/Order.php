<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    /**
     * Order status constants.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'email',
        'phone',
        'status',
        'subtotal_pkr',
        'discount_pkr',
        'shipping_pkr',
        'total_pkr',
        'coupon_code',
        'notes',
        'placed_at',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal_pkr' => 'integer',
            'discount_pkr' => 'integer',
            'shipping_pkr' => 'integer',
            'total_pkr' => 'integer',
            'placed_at' => 'datetime',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddress::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): HasMany
    {
        return $this->addresses()->where('type', 'shipping');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress(): HasMany
    {
        return $this->addresses()->where('type', 'billing');
    }

    /**
     * Get the coupon redemptions for the order.
     */
    public function couponRedemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    /**
     * Get the coupon applied to the order.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    /**
     * Get the formatted total.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'PKR ' . number_format($this->total_pkr / 100, 2);
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'PKR ' . number_format($this->subtotal_pkr / 100, 2);
    }

    /**
     * Get the formatted discount.
     */
    public function getFormattedDiscountAttribute(): string
    {
        if ($this->discount_pkr <= 0) {
            return '';
        }
        return 'PKR ' . number_format($this->discount_pkr / 100, 2);
    }

    /**
     * Get the formatted shipping.
     */
    public function getFormattedShippingAttribute(): string
    {
        return 'PKR ' . number_format($this->shipping_pkr / 100, 2);
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get the status color for UI display.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_CONFIRMED => 'blue',
            self::STATUS_PROCESSING => 'indigo',
            self::STATUS_SHIPPED => 'purple',
            self::STATUS_DELIVERED => 'green',
            self::STATUS_CANCELLED => 'red',
            self::STATUS_REFUNDED => 'gray',
            default => 'gray',
        };
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_PROCESSING,
        ]);
    }

    /**
     * Check if the order can be refunded.
     */
    public function canBeRefunded(): bool
    {
        return in_array($this->status, [
            self::STATUS_DELIVERED,
            self::STATUS_SHIPPED,
        ]);
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    /**
     * Check if the order is refunded.
     */
    public function isRefunded(): bool
    {
        return $this->status === self::STATUS_REFUNDED;
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Update the order status and set appropriate timestamps.
     */
    public function updateStatus(string $status): void
    {
        $this->status = $status;

        switch ($status) {
            case self::STATUS_SHIPPED:
                $this->shipped_at = now();
                break;
            case self::STATUS_DELIVERED:
                $this->delivered_at = now();
                break;
        }

        $this->save();
    }

    /**
     * Get all possible order statuses.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_REFUNDED => 'Refunded',
        ];
    }

    /**
     * Scope a query by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include active orders (not cancelled or refunded).
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_REFUNDED]);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
            if (empty($order->placed_at)) {
                $order->placed_at = now();
            }
        });
    }
}