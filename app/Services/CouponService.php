<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CouponService
{
    /**
     * Validate a coupon with date and usage checks.
     */
    public function validateCoupon(string $code, Cart $cart, ?User $user = null): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Invalid coupon code',
                'coupon' => null,
            ];
        }

        // Check if coupon is active
        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'This coupon is no longer active',
                'coupon' => $coupon,
            ];
        }

        // Check date validity
        $now = now();
        
        if ($coupon->starts_at && $now->isBefore($coupon->starts_at)) {
            return [
                'valid' => false,
                'message' => 'This coupon is not yet valid',
                'coupon' => $coupon,
            ];
        }

        if ($coupon->expires_at && $now->isAfter($coupon->expires_at)) {
            return [
                'valid' => false,
                'message' => 'This coupon has expired',
                'coupon' => $coupon,
            ];
        }

        // Check total usage limit
        if ($coupon->usage_limit_total && $coupon->used_count >= $coupon->usage_limit_total) {
            return [
                'valid' => false,
                'message' => 'This coupon has reached its usage limit',
                'coupon' => $coupon,
            ];
        }

        // Check per-user usage limit
        if ($coupon->usage_limit_per_user && $user) {
            $userUsageCount = $coupon->redemptions()
                ->where('user_id', $user->id)
                ->count();

            if ($userUsageCount >= $coupon->usage_limit_per_user) {
                return [
                    'valid' => false,
                    'message' => 'You have already used this coupon the maximum number of times',
                    'coupon' => $coupon,
                ];
            }
        }

        // Check minimum order amount
        $subtotal = $cart->subtotal;
        if ($coupon->minimum_order_pkr > 0 && $subtotal < $coupon->minimum_order_pkr) {
            $minAmount = number_format($coupon->minimum_order_pkr / 100, 2);
            return [
                'valid' => false,
                'message' => "Minimum order amount of PKR {$minAmount} required",
                'coupon' => $coupon,
            ];
        }

        // Check category restrictions
        if ($coupon->applicable_categories) {
            $cartCategoryIds = $cart->items->pluck('product.category_id')->unique()->toArray();
            $applicableCategories = $coupon->applicable_categories;
            
            if (!array_intersect($cartCategoryIds, $applicableCategories)) {
                return [
                    'valid' => false,
                    'message' => 'This coupon is not applicable to items in your cart',
                    'coupon' => $coupon,
                ];
            }
        }

        // Check product restrictions
        if ($coupon->applicable_products) {
            $cartProductIds = $cart->items->pluck('product_id')->unique()->toArray();
            $applicableProducts = $coupon->applicable_products;
            
            if (!array_intersect($cartProductIds, $applicableProducts)) {
                return [
                    'valid' => false,
                    'message' => 'This coupon is not applicable to items in your cart',
                    'coupon' => $coupon,
                ];
            }
        }

        return [
            'valid' => true,
            'message' => 'Coupon is valid',
            'coupon' => $coupon,
        ];
    }

    /**
     * Apply a coupon with fixed and percentage calculations.
     */
    public function applyCoupon(Coupon $coupon, Cart $cart): int
    {
        $subtotal = $this->getApplicableSubtotal($coupon, $cart);
        
        return $this->calculateDiscount($coupon, $subtotal);
    }

    /**
     * Redeem a coupon for order completion.
     */
    public function redeemCoupon(Coupon $coupon, Order $order, ?User $user = null): CouponRedemption
    {
        return DB::transaction(function () use ($coupon, $order, $user) {
            // Increment coupon usage count
            $coupon->incrementUsage();

            // Create redemption record
            return CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user?->id,
                'order_id' => $order->id,
                'email' => $order->email,
                'discount_amount_pkr' => $order->discount_pkr,
                'redeemed_at' => now(),
            ]);
        });
    }

    /**
     * Calculate discount for accurate discount computation.
     */
    public function calculateDiscount(Coupon $coupon, int $subtotal): int
    {
        if ($subtotal <= 0) {
            return 0;
        }

        $discount = 0;

        if ($coupon->type === Coupon::TYPE_FIXED) {
            // Fixed amount discount
            $discount = min($coupon->value_pkr, $subtotal);
        } elseif ($coupon->type === Coupon::TYPE_PERCENTAGE) {
            // Percentage discount
            $discount = (int) round(($subtotal * $coupon->percentage_value) / 100);
        }

        // Apply maximum discount limit if set
        if ($coupon->maximum_discount_pkr && $discount > $coupon->maximum_discount_pkr) {
            $discount = $coupon->maximum_discount_pkr;
        }

        return $discount;
    }

    /**
     * Check usage limits for per-user and total limits.
     */
    public function checkUsageLimits(Coupon $coupon, ?User $user = null): array
    {
        $result = [
            'can_use' => true,
            'total_usage' => $coupon->used_count,
            'total_limit' => $coupon->usage_limit_total,
            'user_usage' => 0,
            'user_limit' => $coupon->usage_limit_per_user,
            'remaining_uses' => null,
        ];

        // Check total usage limit
        if ($coupon->usage_limit_total) {
            $remaining = $coupon->usage_limit_total - $coupon->used_count;
            $result['remaining_uses'] = max(0, $remaining);
            
            if ($remaining <= 0) {
                $result['can_use'] = false;
            }
        }

        // Check per-user usage limit
        if ($coupon->usage_limit_per_user && $user) {
            $userUsage = $coupon->redemptions()
                ->where('user_id', $user->id)
                ->count();
            
            $result['user_usage'] = $userUsage;
            
            if ($userUsage >= $coupon->usage_limit_per_user) {
                $result['can_use'] = false;
            }
            
            // Update remaining uses based on user limit
            if ($result['remaining_uses'] === null || $result['remaining_uses'] > ($coupon->usage_limit_per_user - $userUsage)) {
                $result['remaining_uses'] = max(0, $coupon->usage_limit_per_user - $userUsage);
            }
        }

        return $result;
    }

    /**
     * Get available coupons for a user and cart.
     */
    public function getAvailableCoupons(Cart $cart, ?User $user = null): array
    {
        $availableCoupons = [];
        
        // Get all active coupons that are currently valid
        $coupons = Coupon::valid()->get();
        
        foreach ($coupons as $coupon) {
            $validation = $this->validateCoupon($coupon->code, $cart, $user);
            
            if ($validation['valid']) {
                $discount = $this->applyCoupon($coupon, $cart);
                $usageLimits = $this->checkUsageLimits($coupon, $user);
                
                $availableCoupons[] = [
                    'coupon' => $coupon,
                    'discount_amount' => $discount,
                    'formatted_discount' => 'PKR ' . number_format($discount / 100, 2),
                    'usage_info' => $usageLimits,
                ];
            }
        }

        // Sort by discount amount (highest first)
        usort($availableCoupons, function ($a, $b) {
            return $b['discount_amount'] <=> $a['discount_amount'];
        });

        return $availableCoupons;
    }

    /**
     * Get coupon usage statistics.
     */
    public function getCouponStatistics(Coupon $coupon): array
    {
        $redemptions = $coupon->redemptions()->with(['user', 'order'])->get();
        
        return [
            'total_redemptions' => $redemptions->count(),
            'unique_users' => $redemptions->pluck('user_id')->filter()->unique()->count(),
            'total_discount_given' => $redemptions->sum('discount_amount_pkr'),
            'average_discount' => $redemptions->avg('discount_amount_pkr') ?? 0,
            'orders_with_coupon' => $redemptions->pluck('order_id')->unique()->count(),
            'redemption_rate' => $coupon->usage_limit_total 
                ? ($redemptions->count() / $coupon->usage_limit_total) * 100 
                : null,
            'recent_redemptions' => $redemptions->sortByDesc('redeemed_at')->take(10)->values(),
        ];
    }

    /**
     * Create a new coupon with validation.
     */
    public function createCoupon(array $data): Coupon
    {
        // Validate coupon code uniqueness
        if (Coupon::where('code', $data['code'])->exists()) {
            throw new \InvalidArgumentException('Coupon code already exists');
        }

        // Validate dates
        if (isset($data['starts_at']) && isset($data['expires_at'])) {
            if ($data['starts_at'] >= $data['expires_at']) {
                throw new \InvalidArgumentException('Start date must be before expiry date');
            }
        }

        // Validate discount values
        if ($data['type'] === Coupon::TYPE_FIXED) {
            if (!isset($data['value_pkr']) || $data['value_pkr'] <= 0) {
                throw new \InvalidArgumentException('Fixed amount must be greater than 0');
            }
            $data['percentage_value'] = null;
        } elseif ($data['type'] === Coupon::TYPE_PERCENTAGE) {
            if (!isset($data['percentage_value']) || $data['percentage_value'] <= 0 || $data['percentage_value'] > 100) {
                throw new \InvalidArgumentException('Percentage must be between 0 and 100');
            }
            $data['value_pkr'] = null;
        }

        return Coupon::create($data);
    }

    /**
     * Get the applicable subtotal for coupon calculation.
     */
    private function getApplicableSubtotal(Coupon $coupon, Cart $cart): int
    {
        // If no restrictions, apply to full cart
        if (!$coupon->applicable_categories && !$coupon->applicable_products) {
            return $cart->subtotal;
        }

        $applicableTotal = 0;

        foreach ($cart->items as $item) {
            $isApplicable = true;

            // Check category restrictions
            if ($coupon->applicable_categories) {
                $isApplicable = in_array($item->product->category_id, $coupon->applicable_categories);
            }

            // Check product restrictions
            if ($isApplicable && $coupon->applicable_products) {
                $isApplicable = in_array($item->product_id, $coupon->applicable_products);
            }

            if ($isApplicable) {
                $applicableTotal += $item->total_price_pkr;
            }
        }

        return $applicableTotal;
    }

    /**
     * Bulk expire coupons.
     */
    public function bulkExpireCoupons(array $couponIds): int
    {
        return Coupon::whereIn('id', $couponIds)
            ->update([
                'expires_at' => now(),
                'is_active' => false,
            ]);
    }

    /**
     * Get expired coupons that need cleanup.
     */
    public function getExpiredCoupons(): \Illuminate\Database\Eloquent\Collection
    {
        return Coupon::where('expires_at', '<', now())
            ->where('is_active', true)
            ->get();
    }
}