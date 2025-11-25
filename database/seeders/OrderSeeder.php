<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddress;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::with('variants')->get();
        $coupons = Coupon::where('is_active', true)->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please run CustomerSeeder and ProductSeeder first');
            return;
        }

        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $orderCount = 0;

        foreach ($customers->take(8) as $customer) {
            // Create 1-3 orders per customer
            $numOrders = rand(1, 3);
            
            for ($i = 0; $i < $numOrders; $i++) {
                $orderCount++;
                $status = $orderStatuses[array_rand($orderStatuses)];
                
                // Create order
                $order = $this->createOrder($customer, $status, $orderCount);
                
                // Add order items
                $this->addOrderItems($order, $products);
                
                // Add addresses
                $this->addOrderAddresses($order);
                
                // Randomly apply coupon to some orders
                if (rand(1, 3) === 1 && $coupons->isNotEmpty()) {
                    $this->applyCouponToOrder($order, $coupons->random(), $customer);
                }
                
                // Recalculate totals
                $this->recalculateOrderTotals($order);
            }
        }

        $this->command->info("Created {$orderCount} sample orders with various statuses!");
    }

    private function createOrder(User $customer, string $status, int $orderNumber): Order
    {
        $placedAt = now()->subDays(rand(1, 30));
        
        return Order::create([
            'order_number' => 'LUN-' . str_pad($orderNumber, 6, '0', STR_PAD_LEFT),
            'user_id' => $customer->id,
            'email' => $customer->email,
            'phone' => '+92 300 ' . rand(1000000, 9999999),
            'status' => $status,
            'subtotal_pkr' => 0, // Will be calculated later
            'discount_pkr' => 0,
            'shipping_pkr' => 150000, // PKR 1,500
            'total_pkr' => 0, // Will be calculated later
            'notes' => $this->getRandomOrderNote(),
            'placed_at' => $placedAt,
            'created_at' => $placedAt,
            'updated_at' => $placedAt,
        ]);
    }

    private function addOrderItems(Order $order, $products): void
    {
        $numItems = rand(1, 4);
        $selectedProducts = $products->random($numItems);
        
        foreach ($selectedProducts as $product) {
            $quantity = rand(1, 2);
            $variant = $product->variants->isNotEmpty() ? $product->variants->random() : null;
            
            // Determine price
            $price = $variant && $variant->price_pkr 
                ? $variant->price_pkr 
                : $product->price_pkr;
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variant?->id,
                'name' => $product->name,
                'sku' => $variant?->sku ?? $product->sku,
                'price_pkr' => $price,
                'quantity' => $quantity,
                'total_pkr' => $price * $quantity,
                'customizations' => $this->getRandomCustomizations($product),
            ]);
        }
    }

    private function addOrderAddresses(Order $order): void
    {
        $cities = ['Lahore', 'Karachi', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan'];
        $areas = ['Gulberg', 'DHA', 'Johar Town', 'Model Town', 'Cantt', 'Garden Town'];
        
        $city = $cities[array_rand($cities)];
        $area = $areas[array_rand($areas)];
        
        // Shipping address
        OrderAddress::create([
            'order_id' => $order->id,
            'type' => 'shipping',
            'first_name' => explode(' ', $order->user->name)[0],
            'last_name' => explode(' ', $order->user->name)[1] ?? '',
            'company' => rand(1, 3) === 1 ? 'ABC Company' : null,
            'address_line_1' => 'House # ' . rand(1, 999) . ', Street # ' . rand(1, 50),
            'address_line_2' => $area,
            'city' => $city,
            'state' => 'Punjab',
            'postal_code' => rand(10000, 99999),
            'country' => 'Pakistan',
            'phone' => $order->phone,
        ]);
        
        // Billing address (same as shipping for simplicity)
        OrderAddress::create([
            'order_id' => $order->id,
            'type' => 'billing',
            'first_name' => explode(' ', $order->user->name)[0],
            'last_name' => explode(' ', $order->user->name)[1] ?? '',
            'company' => rand(1, 3) === 1 ? 'ABC Company' : null,
            'address_line_1' => 'House # ' . rand(1, 999) . ', Street # ' . rand(1, 50),
            'address_line_2' => $area,
            'city' => $city,
            'state' => 'Punjab',
            'postal_code' => rand(10000, 99999),
            'country' => 'Pakistan',
            'phone' => $order->phone,
        ]);
    }

    private function applyCouponToOrder(Order $order, Coupon $coupon, User $customer): void
    {
        $order->update(['coupon_code' => $coupon->code]);
        
        // Create coupon redemption
        CouponRedemption::create([
            'coupon_id' => $coupon->id,
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'redeemed_at' => $order->placed_at,
        ]);
        
        // Increment coupon usage
        $coupon->increment('used_count');
    }

    private function recalculateOrderTotals(Order $order): void
    {
        $subtotal = $order->items->sum('total_pkr');
        $discount = 0;
        
        if ($order->coupon_code) {
            $coupon = Coupon::where('code', $order->coupon_code)->first();
            if ($coupon) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $total = $subtotal - $discount + $order->shipping_pkr;
        
        $order->update([
            'subtotal_pkr' => $subtotal,
            'discount_pkr' => $discount,
            'total_pkr' => $total,
        ]);
    }

    private function getRandomOrderNote(): ?string
    {
        $notes = [
            null,
            'Please handle with care',
            'Gift wrapping requested',
            'Urgent delivery needed',
            'Call before delivery',
            'Leave at reception',
            'Special occasion gift',
            'Anniversary present',
            'Birthday gift for wife',
            'Engagement ring - handle carefully',
        ];
        
        return $notes[array_rand($notes)];
    }

    private function getRandomCustomizations(Product $product): ?array
    {
        // Only add customizations for certain product types
        if (str_contains(strtolower($product->name), 'ring')) {
            return rand(1, 3) === 1 ? [
                'engraving' => ['text' => 'Forever Yours', 'font' => 'Script'],
                'size' => rand(5, 9),
            ] : null;
        }
        
        if (str_contains(strtolower($product->name), 'pendant') || 
            str_contains(strtolower($product->name), 'necklace')) {
            return rand(1, 4) === 1 ? [
                'engraving' => ['text' => 'S.A.', 'font' => 'Block'],
                'chain_length' => rand(16, 20) . ' inches',
            ] : null;
        }
        
        if (str_contains(strtolower($product->name), 'bracelet')) {
            return rand(1, 4) === 1 ? [
                'size' => rand(6, 8) . ' inches',
                'clasp_type' => 'Lobster',
            ] : null;
        }
        
        return null;
    }
}