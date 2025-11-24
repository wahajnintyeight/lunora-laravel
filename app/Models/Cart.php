<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_code',
        'discount_pkr',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_pkr' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the cart.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the coupon applied to the cart.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    /**
     * Get the subtotal of all items in the cart.
     */
    public function getSubtotalAttribute(): int
    {
        return $this->items->sum(function ($item) {
            return $item->total_price_pkr;
        });
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'PKR ' . number_format($this->subtotal / 100, 2);
    }

    /**
     * Get the total after discount.
     */
    public function getTotalAttribute(): int
    {
        return max(0, $this->subtotal - $this->discount_pkr);
    }

    /**
     * Get the formatted total.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'PKR ' . number_format($this->total / 100, 2);
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
     * Get the total number of items in the cart.
     */
    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Check if the cart is empty.
     */
    public function isEmpty(): bool
    {
        return $this->items->count() === 0;
    }

    /**
     * Check if the cart has a coupon applied.
     */
    public function hasCoupon(): bool
    {
        return !empty($this->coupon_code);
    }

    /**
     * Clear all items from the cart.
     */
    public function clearItems(): void
    {
        $this->items()->delete();
    }

    /**
     * Remove the applied coupon.
     */
    public function removeCoupon(): void
    {
        $this->update([
            'coupon_code' => null,
            'discount_pkr' => 0,
        ]);
    }

    /**
     * Scope a query to only include active carts (not expired).
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope a query to only include guest carts.
     */
    public function scopeGuest($query)
    {
        return $query->whereNull('user_id')->whereNotNull('session_id');
    }

    /**
     * Scope a query to only include user carts.
     */
    public function scopeUser($query)
    {
        return $query->whereNotNull('user_id');
    }
}