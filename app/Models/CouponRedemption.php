<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponRedemption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'email',
        'discount_amount_pkr',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_amount_pkr' => 'integer',
            'redeemed_at' => 'datetime',
        ];
    }

    /**
     * Get the coupon that was redeemed.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the user who redeemed the coupon.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order where the coupon was redeemed.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the formatted discount amount.
     */
    public function getFormattedDiscountAmountAttribute(): string
    {
        return 'PKR ' . number_format($this->discount_amount_pkr / 100, 2);
    }

    /**
     * Scope a query by user.
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query by coupon.
     */
    public function scopeByCoupon($query, int $couponId)
    {
        return $query->where('coupon_id', $couponId);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($redemption) {
            if (empty($redemption->redeemed_at)) {
                $redemption->redeemed_at = now();
            }
        });
    }
}