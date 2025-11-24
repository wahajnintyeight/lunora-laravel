<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\CouponService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Create an order from a cart with address handling.
     */
    public function createFromCart(
        Cart $cart, 
        array $shippingAddress, 
        ?array $billingAddress = null,
        int $shippingCost = 0
    ): Order {
        if ($cart->isEmpty()) {
            throw new \InvalidArgumentException('Cannot create order from empty cart');
        }

        return DB::transaction(function () use ($cart, $shippingAddress, $billingAddress, $shippingCost) {
            // Calculate totals
            $subtotal = $cart->subtotal;
            $discount = $cart->discount_pkr ?? 0;
            $total = $subtotal - $discount + $shippingCost;

            // Create the order
            $order = Order::create([
                'user_id' => $cart->user_id,
                'email' => $cart->user?->email ?? $shippingAddress['email'],
                'phone' => $shippingAddress['phone'] ?? null,
                'status' => Order::STATUS_PENDING,
                'subtotal_pkr' => $subtotal,
                'discount_pkr' => $discount,
                'shipping_pkr' => $shippingCost,
                'total_pkr' => $total,
                'coupon_code' => $cart->coupon_code,
            ]);

            // Create shipping address
            $this->createOrderAddress($order, OrderAddress::TYPE_SHIPPING, $shippingAddress);

            // Create billing address (use shipping if not provided)
            $billingData = $billingAddress ?? $shippingAddress;
            $this->createOrderAddress($order, OrderAddress::TYPE_BILLING, $billingData);

            // Create order items and reserve stock
            foreach ($cart->items as $cartItem) {
                // Reserve stock
                $this->inventoryService->reserveStock(
                    $cartItem->product,
                    $cartItem->quantity,
                    $cartItem->variant
                );

                // Create order item with snapshot data
                $productSnapshot = [
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'variant_options' => $cartItem->variant?->options_json,
                    'product_details' => [
                        'description' => $cartItem->product->description,
                        'material' => $cartItem->product->material,
                        'brand' => $cartItem->product->brand,
                    ]
                ];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->variant?->sku ?? $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'unit_price_pkr' => $cartItem->unit_price_pkr,
                    'total_price_pkr' => $cartItem->total_price_pkr,
                    'customizations' => $cartItem->customizations,
                    'product_snapshot' => $productSnapshot,
                ]);
            }

            // Redeem coupon if applied
            if ($cart->hasCoupon() && $cart->coupon) {
                app(CouponService::class)->redeemCoupon(
                    $cart->coupon, 
                    $order, 
                    $cart->user
                );
            }

            // Clear the cart
            $cart->clearItems();
            $cart->removeCoupon();

            return $order->fresh(['items', 'addresses']);
        });
    }

    /**
     * Update order status with email notifications.
     */
    public function updateStatus(Order $order, string $status): Order
    {
        $oldStatus = $order->status;
        
        if ($oldStatus === $status) {
            return $order; // No change needed
        }

        // Validate status transition
        if (!$this->isValidStatusTransition($oldStatus, $status)) {
            throw new \InvalidArgumentException("Invalid status transition from {$oldStatus} to {$status}");
        }

        $order->updateStatus($status);

        // Send status update notification
        $this->sendStatusUpdateNotification($order, $oldStatus, $status);

        return $order->fresh();
    }

    /**
     * Cancel an order with optional stock restoration.
     */
    public function cancelOrder(Order $order, bool $restoreStock = true): Order
    {
        if (!$order->canBeCancelled()) {
            throw new \InvalidArgumentException('Order cannot be cancelled in current status: ' . $order->status);
        }

        return DB::transaction(function () use ($order, $restoreStock) {
            // Restore stock if requested
            if ($restoreStock) {
                foreach ($order->items as $item) {
                    $this->inventoryService->releaseStock(
                        $item->product,
                        $item->quantity,
                        $item->variant
                    );
                }
            }

            // Restore coupon usage if applicable
            if ($order->coupon_code && $order->coupon) {
                $order->coupon->decrementUsage();
            }

            // Update order status
            $this->updateStatus($order, Order::STATUS_CANCELLED);

            return $order->fresh();
        });
    }

    /**
     * Process a refund with stock management.
     */
    public function processRefund(Order $order, bool $restoreStock = true): Order
    {
        if (!$order->canBeRefunded()) {
            throw new \InvalidArgumentException('Order cannot be refunded in current status: ' . $order->status);
        }

        return DB::transaction(function () use ($order, $restoreStock) {
            // Restore stock if requested
            if ($restoreStock) {
                foreach ($order->items as $item) {
                    $this->inventoryService->releaseStock(
                        $item->product,
                        $item->quantity,
                        $item->variant
                    );
                }
            }

            // Restore coupon usage if applicable
            if ($order->coupon_code && $order->coupon) {
                $order->coupon->decrementUsage();
            }

            // Update order status
            $this->updateStatus($order, Order::STATUS_REFUNDED);

            return $order->fresh();
        });
    }

    /**
     * Generate a unique order number.
     */
    public function generateOrderNumber(): string
    {
        return Order::generateOrderNumber();
    }

    /**
     * Calculate shipping cost for delivery.
     */
    public function calculateShipping(Cart $cart, array $address): int
    {
        // Basic shipping calculation - can be enhanced with more complex logic
        $baseShipping = 50000; // PKR 500 base shipping
        $freeShippingThreshold = 500000; // PKR 5000 for free shipping
        
        // Free shipping for orders above threshold
        if ($cart->subtotal >= $freeShippingThreshold) {
            return 0;
        }

        // Additional charges based on location (example logic)
        $city = strtolower($address['city'] ?? '');
        $additionalCharge = 0;

        // Major cities have standard shipping
        $majorCities = ['karachi', 'lahore', 'islamabad', 'rawalpindi', 'faisalabad'];
        
        if (!in_array($city, $majorCities)) {
            $additionalCharge = 25000; // PKR 250 additional for other cities
        }

        return $baseShipping + $additionalCharge;
    }

    /**
     * Get order statistics for dashboard.
     */
    public function getOrderStatistics(): array
    {
        return [
            'total_orders' => Order::count(),
            'pending_orders' => Order::byStatus(Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::byStatus(Order::STATUS_PROCESSING)->count(),
            'shipped_orders' => Order::byStatus(Order::STATUS_SHIPPED)->count(),
            'delivered_orders' => Order::byStatus(Order::STATUS_DELIVERED)->count(),
            'cancelled_orders' => Order::byStatus(Order::STATUS_CANCELLED)->count(),
            'total_revenue' => Order::active()->sum('total_pkr'),
            'average_order_value' => Order::active()->avg('total_pkr') ?? 0,
        ];
    }

    /**
     * Create an order address.
     */
    private function createOrderAddress(Order $order, string $type, array $addressData): OrderAddress
    {
        return $order->addresses()->create([
            'type' => $type,
            'first_name' => $addressData['first_name'],
            'last_name' => $addressData['last_name'],
            'company' => $addressData['company'] ?? null,
            'address_line_1' => $addressData['address_line_1'],
            'address_line_2' => $addressData['address_line_2'] ?? null,
            'city' => $addressData['city'],
            'state_province' => $addressData['state_province'] ?? $addressData['state'] ?? null,
            'postal_code' => $addressData['postal_code'] ?? null,
            'country' => $addressData['country'] ?? 'Pakistan',
            'phone' => $addressData['phone'] ?? null,
        ]);
    }

    /**
     * Check if status transition is valid.
     */
    private function isValidStatusTransition(string $fromStatus, string $toStatus): bool
    {
        $validTransitions = [
            Order::STATUS_PENDING => [
                Order::STATUS_CONFIRMED,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_CONFIRMED => [
                Order::STATUS_PROCESSING,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_PROCESSING => [
                Order::STATUS_SHIPPED,
                Order::STATUS_CANCELLED,
            ],
            Order::STATUS_SHIPPED => [
                Order::STATUS_DELIVERED,
                Order::STATUS_REFUNDED,
            ],
            Order::STATUS_DELIVERED => [
                Order::STATUS_REFUNDED,
            ],
            Order::STATUS_CANCELLED => [], // Terminal state
            Order::STATUS_REFUNDED => [], // Terminal state
        ];

        return in_array($toStatus, $validTransitions[$fromStatus] ?? []);
    }

    /**
     * Send status update notification.
     */
    private function sendStatusUpdateNotification(Order $order, string $oldStatus, string $newStatus): void
    {
        // This would typically send an email notification
        // For now, we'll just log it or implement a simple notification system
        
        // Example implementation:
        // Mail::to($order->email)->send(new OrderStatusUpdated($order, $oldStatus, $newStatus));
        
        // For testing purposes, we'll just ensure the method exists
        // The actual email implementation would be done in the email notification task
    }
}