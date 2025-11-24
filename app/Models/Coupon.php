<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Coupon type constants.
     */
    const TYPE_FIXED = 'fixed';
    const TYPE_PERCENTAGE = 'percentage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_order_pkr',
        'maximum_discount_pkr',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'is_active',
        'starts_at',
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
            'value' => 'integer',
            'minimum_order_pkr' => 'integer',
            'maximum_discount_pkr' => 'integer',
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the redemptions for the coupon.
     */
    public function redemptions(): HasMany
    {
        return $this->hasMany(CouponRedemption::class);
    }

    /**
     * Get the orders that used this coupon.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    /**
     * Check if the coupon is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->isBefore($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->isAfter($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if the coupon is valid for a specific user.
     */
    public function isValidForUser(?User $user = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->usage_limit_per_user && $user) {
            $userUsageCount = $this->redemptions()
                ->where('user_id', $user->id)
                ->count();

            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the coupon meets minimum order requirements.
     */
    public function meetsMinimumOrder(int $orderTotal): bool
    {
        if (!$this->minimum_order_pkr) {
            return true;
        }

        return $orderTotal >= $this->minimum_order_pkr;
    }

    /**
     * Calculate the discount amount for a given order total.
     */
    public function calculateDiscount(int $orderTotal): int
    {
        if (!$this->meetsMinimumOrder($orderTotal)) {
            return 0;
        }

        $discount = 0;

        if ($this->type === self::TYPE_FIXED) {
            $discount = min($this->value, $orderTotal);
        } elseif ($this->type === self::TYPE_PERCENTAGE) {
            $discount = (int) round(($orderTotal * $this->value) / 10000); // value is stored as basis points
        }

        // Apply maximum discount limit if set
        if ($this->maximum_discount_pkr && $discount > $this->maximum_discount_pkr) {
            $discount = $this->maximum_discount_pkr;
        }

        return $discount;
    }

    /**
     * Get the formatted value for display.
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === self::TYPE_FIXED) {
            return 'PKR ' . number_format($this->value / 100, 2);
        } elseif ($this->type === self::TYPE_PERCENTAGE) {
            return ($this->value / 100) . '%';
        }

        return (string) $this->value;
    }

    /**
     * Get the formatted minimum order amount.
     */
    public function getFormattedMinimumOrderAttribute(): string
    {
        if (!$this->minimum_order_pkr) {
            return '';
        }
        return 'PKR ' . number_format($this->minimum_order_pkr / 100, 2);
    }

    /**
     * Get the formatted maximum discount amount.
     */
    public function getFormattedMaximumDiscountAttribute(): string
    {
        if (!$this->maximum_discount_pkr) {
            return '';
        }
        return 'PKR ' . number_format($this->maximum_discount_pkr / 100, 2);
    }

    /**
     * Get the type display name.
     */
    public function getTypeDisplayAttribute(): string
    {
        return match($this->type) {
            self::TYPE_FIXED => 'Fixed Amount',
            self::TYPE_PERCENTAGE => 'Percentage',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get the remaining usage count.
     */
    public function getRemainingUsageAttribute(): ?int
    {
        if (!$this->usage_limit) {
            return null;
        }
        return max(0, $this->usage_limit - $this->used_count);
    }

    /**
     * Increment the used count.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Decrement the used count.
     */
    public function decrementUsage(): void
    {
        $this->decrement('used_count');
    }

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid coupons (active and within date range).
     */
    public function scopeValid($query)
    {
        $now = now();
        
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')
                  ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                  ->orWhereRaw('used_count < usage_limit');
            });
    }

    /**
     * Scope a query by coupon type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get all possible coupon types.
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_FIXED => 'Fixed Amount',
            self::TYPE_PERCENTAGE => 'Percentage',
        ];
    }
}