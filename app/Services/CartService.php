<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Get or create a cart for the given user or session.
     */
    public function getOrCreateCart(?User $user = null, ?string $sessionId = null): Cart
    {
        if ($user) {
            // For authenticated users, find or create user cart
            $cart = Cart::withoutGlobalScopes()
                ->where('user_id', $user->id)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->first();
            
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'expires_at' => now()->addDays(30), // User carts expire in 30 days
                ]);
            }
        } else {
            // For guests, use session ID
            $sessionId = $sessionId ?: Session::getId();
            
            $cart = Cart::where('session_id', $sessionId)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })
                ->first();
            
            if (!$cart) {
                $cart = Cart::create([
                    'session_id' => $sessionId,
                    'expires_at' => now()->addDays(7), // Guest carts expire in 7 days
                ]);
            }
        }
        
        return $cart;
    }

    /**
     * Add an item to the cart with stock validation and customization support.
     */
    public function addItem(
        Cart $cart, 
        Product $product, 
        int $quantity, 
        ?ProductVariant $variant = null,
        array $customizations = []
    ): CartItem {
        // Validate product is active
        if (!$product->is_active) {
            throw new \InvalidArgumentException('Product is not available');
        }

        // Validate variant if provided
        if ($variant && (!$variant->is_active || $variant->product_id !== $product->id)) {
            throw new \InvalidArgumentException('Product variant is not available');
        }

        // Check stock availability
        $availableStock = $variant ? $variant->stock : $product->stock;
        
        // Check if item already exists in cart
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->where('customizations', json_encode($customizations))
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $availableStock) {
                throw new \InvalidArgumentException("Insufficient stock. Only {$availableStock} items available");
            }
            
            return $this->updateItemQuantity($existingItem, $newQuantity);
        }

        // Validate stock for new item
        if ($quantity > $availableStock) {
            throw new \InvalidArgumentException("Insufficient stock. Only {$availableStock} items available");
        }

        // Determine unit price
        $unitPrice = $variant ? ($variant->price_pkr ?? $product->price_pkr) : $product->price_pkr;

        // Create new cart item
        $cartItem = $cart->items()->create([
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity' => $quantity,
            'unit_price_pkr' => $unitPrice,
            'customizations' => $customizations,
        ]);

        // Recalculate cart totals if coupon is applied
        if ($cart->hasCoupon()) {
            $this->recalculateCouponDiscount($cart);
        }

        return $cartItem;
    }

    /**
     * Update the quantity of a cart item.
     */
    public function updateItemQuantity(CartItem $item, int $quantity): CartItem|bool
    {
        if ($quantity <= 0) {
            return $this->removeItem($item);
        }

        // Check stock availability
        $availableStock = $item->variant ? $item->variant->stock : $item->product->stock;
        
        if ($quantity > $availableStock) {
            throw new \InvalidArgumentException("Insufficient stock. Only {$availableStock} items available");
        }

        $item->update(['quantity' => $quantity]);

        // Recalculate cart totals if coupon is applied
        if ($item->cart->hasCoupon()) {
            $this->recalculateCouponDiscount($item->cart);
        }

        return $item->fresh();
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(CartItem $item): bool
    {
        $cart = $item->cart;
        $result = $item->delete();

        // Recalculate cart totals if coupon is applied
        if ($cart->hasCoupon()) {
            $this->recalculateCouponDiscount($cart);
        }

        return $result;
    }

    /**
     * Apply a coupon to the cart with validation.
     */
    public function applyCoupon(Cart $cart, string $couponCode): Cart
    {
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            throw new \InvalidArgumentException('Invalid coupon code');
        }

        if (!$coupon->isValid()) {
            throw new \InvalidArgumentException('Coupon is not valid or has expired');
        }

        $user = $cart->user;
        if (!$coupon->isValidForUser($user)) {
            throw new \InvalidArgumentException('Coupon usage limit exceeded for this user');
        }

        $subtotal = $cart->subtotal;
        if (!$coupon->meetsMinimumOrder($subtotal)) {
            $minOrder = number_format($coupon->minimum_order_pkr / 100, 2);
            throw new \InvalidArgumentException("Minimum order amount of PKR {$minOrder} required");
        }

        $discount = $coupon->calculateDiscount($subtotal);

        $cart->update([
            'coupon_code' => $couponCode,
            'discount_pkr' => $discount,
        ]);

        return $cart->fresh();
    }

    /**
     * Remove the coupon from the cart.
     */
    public function removeCoupon(Cart $cart): Cart
    {
        $cart->update([
            'coupon_code' => null,
            'discount_pkr' => 0,
        ]);

        return $cart->fresh();
    }

    /**
     * Calculate cart totals including subtotal, discount, and shipping.
     */
    public function calculateTotals(Cart $cart, int $shippingCost = 0): array
    {
        $subtotal = $cart->subtotal;
        $discount = $cart->discount_pkr;
        $total = max(0, $subtotal - $discount + $shippingCost);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shippingCost,
            'total' => $total,
            'formatted' => [
                'subtotal' => 'PKR ' . number_format($subtotal / 100, 2),
                'discount' => 'PKR ' . number_format($discount / 100, 2),
                'shipping' => 'PKR ' . number_format($shippingCost / 100, 2),
                'total' => 'PKR ' . number_format($total / 100, 2),
            ]
        ];
    }

    /**
     * Merge guest cart with user cart when user logs in.
     */
    public function mergeGuestCart(Cart $guestCart, Cart $userCart): Cart
    {
        return DB::transaction(function () use ($guestCart, $userCart) {
            foreach ($guestCart->items as $guestItem) {
                // Check if user cart already has this item
                $existingItem = $userCart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->where('customizations', json_encode($guestItem->customizations))
                    ->first();

                if ($existingItem) {
                    // Merge quantities
                    $newQuantity = $existingItem->quantity + $guestItem->quantity;
                    $availableStock = $guestItem->variant ? $guestItem->variant->stock : $guestItem->product->stock;
                    
                    // Use available stock if requested quantity exceeds it
                    $finalQuantity = min($newQuantity, $availableStock);
                    
                    $this->updateItemQuantity($existingItem, $finalQuantity);
                } else {
                    // Move item to user cart
                    $guestItem->update(['cart_id' => $userCart->id]);
                }
            }

            // Apply guest cart coupon to user cart if user cart doesn't have one
            if ($guestCart->hasCoupon() && !$userCart->hasCoupon()) {
                try {
                    $this->applyCoupon($userCart, $guestCart->coupon_code);
                } catch (\InvalidArgumentException $e) {
                    // Ignore if coupon can't be applied to user cart
                }
            }

            // Delete the guest cart
            $guestCart->delete();

            return $userCart->fresh(['items']);
        });
    }

    /**
     * Validate all items in the cart for availability and stock.
     */
    public function validateCartItems(Cart $cart): array
    {
        $errors = [];
        $itemsToRemove = [];

        foreach ($cart->items as $item) {
            // Check if product is still active
            if (!$item->product->is_active) {
                $errors[] = "'{$item->name}' is no longer available and has been removed from your cart";
                $itemsToRemove[] = $item;
                continue;
            }

            // Check if variant is still active (if applicable)
            if ($item->variant && !$item->variant->is_active) {
                $errors[] = "'{$item->name}' variant is no longer available and has been removed from your cart";
                $itemsToRemove[] = $item;
                continue;
            }

            // Check stock availability
            $availableStock = $item->variant ? $item->variant->stock : $item->product->stock;
            
            if ($item->quantity > $availableStock) {
                if ($availableStock > 0) {
                    $this->updateItemQuantity($item, $availableStock);
                    $errors[] = "'{$item->name}' quantity has been reduced to {$availableStock} due to limited stock";
                } else {
                    $errors[] = "'{$item->name}' is out of stock and has been removed from your cart";
                    $itemsToRemove[] = $item;
                }
            }
        }

        // Remove unavailable items
        foreach ($itemsToRemove as $item) {
            $this->removeItem($item);
        }

        return $errors;
    }

    /**
     * Clear all items from the cart.
     */
    public function clearCart(Cart $cart): void
    {
        $cart->items()->delete();
        $cart->update([
            'coupon_code' => null,
            'discount_pkr' => 0,
        ]);
    }

    /**
     * Get cart item count.
     */
    public function getItemCount(Cart $cart): int
    {
        return $cart->items->sum('quantity');
    }

    /**
     * Check if cart is empty.
     */
    public function isEmpty(Cart $cart): bool
    {
        return $cart->items->count() === 0;
    }

    /**
     * Recalculate coupon discount when cart contents change.
     */
    private function recalculateCouponDiscount(Cart $cart): void
    {
        if (!$cart->hasCoupon()) {
            return;
        }

        $coupon = $cart->coupon;
        if (!$coupon || !$coupon->isValid()) {
            $this->removeCoupon($cart);
            return;
        }

        $subtotal = $cart->fresh()->subtotal;
        if (!$coupon->meetsMinimumOrder($subtotal)) {
            $this->removeCoupon($cart);
            return;
        }

        $discount = $coupon->calculateDiscount($subtotal);
        $cart->update(['discount_pkr' => $discount]);
    }
}