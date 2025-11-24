<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    /**
     * Display the shopping cart.
     */
    public function index(): View
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        
        // Validate cart items for availability and stock
        $validationErrors = $this->cartService->validateCartItems($cart);
        
        // Load cart with relationships
        $cart->load(['items.product.images', 'items.variant', 'coupon']);
        
        // Calculate totals
        $totals = $this->cartService->calculateTotals($cart);
        
        return view('cart.index', compact('cart', 'totals', 'validationErrors'));
    }

    /**
     * Add item to cart (AJAX).
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1|max:100',
            'customizations' => 'nullable|array',
            'customizations.engraving' => 'nullable|string|max:20',
            'customizations.instructions' => 'nullable|string|max:500',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $variant = $request->variant_id ? ProductVariant::findOrFail($request->variant_id) : null;
            $customizations = $request->customizations ?? [];

            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            
            $cartItem = $this->cartService->addItem(
                $cart,
                $product,
                $request->quantity,
                $variant,
                $customizations
            );

            // Get updated cart count
            $itemCount = $this->cartService->getItemCount($cart);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart_count' => $itemCount,
                'item' => [
                    'id' => $cartItem->id,
                    'name' => $cartItem->name,
                    'quantity' => $cartItem->quantity,
                    'formatted_price' => 'PKR ' . number_format($cartItem->unit_price_pkr / 100, 2),
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding item to cart'
            ], 500);
        }
    }

    /**
     * Update cart item quantity (AJAX).
     */
    public function update(Request $request, int $itemId): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:100',
        ]);

        try {
            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            $cartItem = $cart->items()->findOrFail($itemId);

            if ($request->quantity == 0) {
                $this->cartService->removeItem($cartItem);
                $message = 'Item removed from cart';
            } else {
                $cartItem = $this->cartService->updateItemQuantity($cartItem, $request->quantity);
                $message = 'Cart updated successfully';
            }

            // Get updated totals
            $cart = $cart->fresh(['items']);
            $totals = $this->cartService->calculateTotals($cart);
            $itemCount = $this->cartService->getItemCount($cart);

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $itemCount,
                'totals' => $totals['formatted'],
                'item_removed' => $request->quantity == 0,
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating cart'
            ], 500);
        }
    }

    /**
     * Remove item from cart (AJAX).
     */
    public function remove(int $itemId): JsonResponse
    {
        try {
            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            $cartItem = $cart->items()->findOrFail($itemId);

            $this->cartService->removeItem($cartItem);

            // Get updated totals
            $cart = $cart->fresh(['items']);
            $totals = $this->cartService->calculateTotals($cart);
            $itemCount = $this->cartService->getItemCount($cart);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_count' => $itemCount,
                'totals' => $totals['formatted'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing item'
            ], 500);
        }
    }

    /**
     * Apply coupon to cart (AJAX).
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);

        try {
            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            
            $this->cartService->applyCoupon($cart, $request->coupon_code);

            // Get updated totals
            $cart = $cart->fresh(['coupon']);
            $totals = $this->cartService->calculateTotals($cart);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'code' => $cart->coupon_code,
                    'discount' => $totals['formatted']['discount'],
                ],
                'totals' => $totals['formatted'],
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while applying coupon'
            ], 500);
        }
    }

    /**
     * Remove coupon from cart (AJAX).
     */
    public function removeCoupon(): JsonResponse
    {
        try {
            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            
            $this->cartService->removeCoupon($cart);

            // Get updated totals
            $cart = $cart->fresh();
            $totals = $this->cartService->calculateTotals($cart);

            return response()->json([
                'success' => true,
                'message' => 'Coupon removed',
                'totals' => $totals['formatted'],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while removing coupon'
            ], 500);
        }
    }

    /**
     * Get cart count for header display (AJAX).
     */
    public function count(): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        $itemCount = $this->cartService->getItemCount($cart);

        return response()->json([
            'count' => $itemCount
        ]);
    }

    /**
     * Clear entire cart.
     */
    public function clear(): RedirectResponse
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
        $this->cartService->clearCart($cart);

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully');
    }

    /**
     * Update item customization (AJAX).
     */
    public function updateCustomization(Request $request, $itemId): JsonResponse
    {
        $request->validate([
            'customizations' => 'nullable|array',
            'customizations.engraving' => 'nullable|string|max:50',
            'customizations.instructions' => 'nullable|string|max:200',
            'customizations.ring_size' => 'nullable|string|max:10',
            'customizations.chain_length' => 'nullable|string|max:20',
        ]);

        try {
            $cart = $this->cartService->getOrCreateCart(auth()->user(), session()->getId());
            $cartItem = $cart->items()->findOrFail($itemId);

            // Update customizations
            $customizations = $request->input('customizations', []);
            
            // Remove empty values
            $customizations = array_filter($customizations, function($value) {
                return !empty(trim($value));
            });

            $cartItem->update([
                'customizations' => !empty($customizations) ? $customizations : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customization updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating customization'
            ], 500);
        }
    }
}