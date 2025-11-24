<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService
    ) {}

    /**
     * Display the checkout page.
     */
    public function index(): View
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        
        // Redirect if cart is empty
        if ($this->cartService->isEmpty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Validate cart items
        $validationErrors = $this->cartService->validateCartItems($cart);
        
        if (!empty($validationErrors)) {
            return redirect()->route('cart.index')->with('errors', $validationErrors);
        }

        // Load cart with relationships
        $cart->load(['items.product.images', 'items.variant', 'coupon']);
        
        // Calculate shipping (basic flat rate for now)
        $shippingCost = $this->calculateShipping($cart);
        
        // Calculate totals
        $totals = $this->cartService->calculateTotals($cart, $shippingCost);

        // Get user's saved addresses if authenticated
        $savedAddresses = auth()->check() ? auth()->user()->addresses ?? [] : [];

        return view('checkout.index', compact('cart', 'totals', 'savedAddresses'));
    }

    /**
     * Process the checkout and create order.
     */
    public function store(Request $request): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        
        // Validate cart is not empty
        if ($this->cartService->isEmpty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Validate cart items one more time
        $validationErrors = $this->cartService->validateCartItems($cart);
        if (!empty($validationErrors)) {
            return redirect()->route('cart.index')->with('errors', $validationErrors);
        }

        // Validate checkout form
        $validated = $request->validate([
            // Customer Information
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            
            // Shipping Address
            'shipping_first_name' => 'required|string|max:100',
            'shipping_last_name' => 'required|string|max:100',
            'shipping_address_line_1' => 'required|string|max:255',
            'shipping_address_line_2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            
            // Billing Address
            'billing_same_as_shipping' => 'boolean',
            'billing_first_name' => 'required_if:billing_same_as_shipping,false|nullable|string|max:100',
            'billing_last_name' => 'required_if:billing_same_as_shipping,false|nullable|string|max:100',
            'billing_address_line_1' => 'required_if:billing_same_as_shipping,false|nullable|string|max:255',
            'billing_address_line_2' => 'nullable|string|max:255',
            'billing_city' => 'required_if:billing_same_as_shipping,false|nullable|string|max:100',
            'billing_state' => 'required_if:billing_same_as_shipping,false|nullable|string|max:100',
            'billing_postal_code' => 'required_if:billing_same_as_shipping,false|nullable|string|max:20',
            'billing_country' => 'required_if:billing_same_as_shipping,false|nullable|string|max:100',
            
            // Order Notes
            'notes' => 'nullable|string|max:1000',
            
            // Terms and Conditions
            'terms_accepted' => 'required|accepted',
        ]);

        try {
            // Prepare shipping address
            $shippingAddress = [
                'type' => 'shipping',
                'first_name' => $validated['shipping_first_name'],
                'last_name' => $validated['shipping_last_name'],
                'address_line_1' => $validated['shipping_address_line_1'],
                'address_line_2' => $validated['shipping_address_line_2'] ?? null,
                'city' => $validated['shipping_city'],
                'state' => $validated['shipping_state'],
                'postal_code' => $validated['shipping_postal_code'],
                'country' => $validated['shipping_country'],
            ];

            // Prepare billing address
            $billingAddress = null;
            if (!$request->boolean('billing_same_as_shipping')) {
                $billingAddress = [
                    'type' => 'billing',
                    'first_name' => $validated['billing_first_name'],
                    'last_name' => $validated['billing_last_name'],
                    'address_line_1' => $validated['billing_address_line_1'],
                    'address_line_2' => $validated['billing_address_line_2'] ?? null,
                    'city' => $validated['billing_city'],
                    'state' => $validated['billing_state'],
                    'postal_code' => $validated['billing_postal_code'],
                    'country' => $validated['billing_country'],
                ];
            }

            // Calculate shipping cost
            $shippingCost = $this->calculateShipping($cart);

            // Add email and phone to shipping address for order creation
            $shippingAddress['email'] = $validated['email'];
            $shippingAddress['phone'] = $validated['phone'];
            
            // Create order
            $order = $this->orderService->createFromCart(
                $cart,
                $shippingAddress,
                $billingAddress,
                $shippingCost
            );
            
            // Add notes to order if provided
            if (!empty($validated['notes'])) {
                $order->update(['notes' => $validated['notes']]);
            }

            // Clear the cart
            $this->cartService->clearCart($cart);

            // Redirect to order confirmation
            return redirect()->route('checkout.confirmation', $order->order_number)
                ->with('success', 'Order placed successfully!');

        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'cart_id' => $cart->id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors(['error' => 'An error occurred while processing your order. Please try again.']);
        }
    }

    /**
     * Display order confirmation page.
     */
    public function confirmation(string $orderNumber): View
    {
        $order = auth()->check() 
            ? auth()->user()->orders()->where('order_number', $orderNumber)->firstOrFail()
            : \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();

        $order->load(['items.product.images', 'items.variant', 'addresses']);

        return view('checkout.confirmation', compact('order'));
    }

    /**
     * Display thank you page (for guest users).
     */
    public function thankYou(string $orderNumber): View
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        $order->load(['items.product.images', 'items.variant', 'addresses']);

        return view('checkout.thank-you', compact('order'));
    }

    /**
     * Calculate shipping cost based on cart contents and destination.
     */
    private function calculateShipping($cart): int
    {
        // Basic flat rate shipping for now
        // In a real application, this would be more sophisticated
        $subtotal = $cart->subtotal;
        
        // Free shipping over PKR 5,000
        if ($subtotal >= 500000) { // PKR 5,000 in paisa
            return 0;
        }
        
        // Standard shipping rate
        return 30000; // PKR 300 in paisa
    }

    /**
     * Get shipping rates for AJAX requests.
     */
    public function getShippingRates(Request $request)
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        $shippingCost = $this->calculateShipping($cart);
        
        return response()->json([
            'shipping_cost' => $shippingCost,
            'formatted_shipping_cost' => 'PKR ' . number_format($shippingCost / 100, 2),
            'free_shipping_threshold' => 500000,
            'formatted_free_shipping_threshold' => 'PKR 5,000',
        ]);
    }
}