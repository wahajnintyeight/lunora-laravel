<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display the user's profile.
     */
    public function profile(): View
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'boolean',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        } elseif ($request->boolean('remove_avatar')) {
            // Remove existing avatar
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        // Remove the remove_avatar field from validated data
        unset($validated['remove_avatar']);

        $user->update($validated);

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.profile')->with('success', 'Password updated successfully.');
    }

    /**
     * Display the user's order history.
     */
    public function orders(Request $request): View
    {
        $user = auth()->user();
        
        $query = $user->orders()->with(['items.product.images', 'items.variant']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        // Sort by date (newest first by default)
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $query->orderBy($sortBy, $sortDirection);

        $orders = $query->paginate(10)->withQueryString();

        // Get available statuses for filter
        $statuses = $user->orders()
            ->select('status')
            ->distinct()
            ->pluck('status')
            ->sort()
            ->values();

        return view('user.orders', compact('orders', 'statuses'));
    }

    /**
     * Display a specific order.
     */
    public function orderDetail(string $orderNumber): View
    {
        $user = auth()->user();
        
        $order = $user->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product.images', 'items.variant', 'addresses', 'couponRedemptions'])
            ->firstOrFail();

        return view('user.order-detail', compact('order'));
    }

    /**
     * Display account settings.
     */
    public function settings(): View
    {
        $user = auth()->user();
        return view('user.settings', compact('user'));
    }

    /**
     * Update account settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'marketing_emails' => 'boolean',
            'sms_notifications' => 'boolean',
        ]);

        // For now, we'll store these as JSON in a settings column
        // In a real application, you might have a separate settings table
        $settings = array_merge($user->settings ?? [], [
            'email_notifications' => $request->boolean('email_notifications'),
            'marketing_emails' => $request->boolean('marketing_emails'),
            'sms_notifications' => $request->boolean('sms_notifications'),
        ]);

        $user->update(['settings' => $settings]);

        return redirect()->route('user.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Display the user's address book.
     */
    public function addresses(): View
    {
        $user = auth()->user();
        
        // Get unique addresses from user's orders
        $addresses = $user->orders()
            ->with('addresses')
            ->get()
            ->pluck('addresses')
            ->flatten()
            ->unique(function ($address) {
                return $address->first_name . $address->last_name . $address->address_line_1 . $address->city;
            })
            ->values();

        return view('user.addresses', compact('addresses'));
    }

    /**
     * Cancel an order (if allowed).
     */
    public function cancelOrder(string $orderNumber): RedirectResponse
    {
        $user = auth()->user();
        
        $order = $user->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if order can be cancelled (only pending orders)
        if ($order->status !== 'pending') {
            return redirect()->route('user.order-detail', $orderNumber)
                ->with('error', 'This order cannot be cancelled.');
        }

        // Update order status
        $order->update(['status' => 'cancelled']);

        // In a real application, you would also:
        // - Restore inventory
        // - Process refund if payment was taken
        // - Send cancellation email

        return redirect()->route('user.order-detail', $orderNumber)
            ->with('success', 'Order cancelled successfully.');
    }

    /**
     * Reorder items from a previous order.
     */
    public function reorder(string $orderNumber): RedirectResponse
    {
        $user = auth()->user();
        
        $order = $user->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'items.variant'])
            ->firstOrFail();

        $cartService = app(\App\Services\CartService::class);
        $cart = $cartService->getOrCreateCart($user);

        $addedItems = 0;
        $unavailableItems = [];

        foreach ($order->items as $item) {
            try {
                // Check if product is still available
                if (!$item->product->is_active || $item->product->stock <= 0) {
                    $unavailableItems[] = $item->product->name;
                    continue;
                }

                // Check if variant is still available (if applicable)
                if ($item->variant && (!$item->variant->is_active || $item->variant->stock <= 0)) {
                    $unavailableItems[] = $item->product->name . ' (variant no longer available)';
                    continue;
                }

                $cartService->addItem(
                    $cart,
                    $item->product,
                    $item->quantity,
                    $item->variant,
                    $item->customizations ?? []
                );

                $addedItems++;

            } catch (\InvalidArgumentException $e) {
                $unavailableItems[] = $item->product->name . ' (' . $e->getMessage() . ')';
            }
        }

        $message = "Added {$addedItems} items to your cart.";
        
        if (!empty($unavailableItems)) {
            $message .= ' Some items were not available: ' . implode(', ', $unavailableItems);
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    /**
     * Download order invoice (PDF).
     */
    public function downloadInvoice(string $orderNumber)
    {
        $user = auth()->user();
        
        $order = $user->orders()
            ->where('order_number', $orderNumber)
            ->with(['items.product', 'items.variant', 'addresses'])
            ->firstOrFail();

        // In a real application, you would generate a PDF here
        // For now, we'll return a simple view that can be printed
        return view('user.invoice', compact('order'));
    }

    /**
     * Track order status.
     */
    public function trackOrder(Request $request): View
    {
        $orderNumber = $request->get('order_number');
        $order = null;

        if ($orderNumber) {
            $user = auth()->user();
            $order = $user->orders()
                ->where('order_number', $orderNumber)
                ->with(['items.product.images'])
                ->first();
        }

        return view('user.track-order', compact('order', 'orderNumber'));
    }
}