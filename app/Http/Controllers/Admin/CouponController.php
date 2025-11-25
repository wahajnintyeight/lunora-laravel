<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\CouponRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends AdminController
{
    public function index(Request $request)
    {
        $query = Coupon::withCount('redemptions');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->filled('status')) {
            $now = now();
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                          ->where('starts_at', '<=', $now)
                          ->where('expires_at', '>=', $now);
                    break;
                case 'expired':
                    $query->where('expires_at', '<', $now);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'upcoming':
                    $query->where('starts_at', '>', $now);
                    break;
            }
        }

        $coupons = $query->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons',
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount_pkr' => 'nullable|numeric|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'usage_limit_total' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        // Convert amounts to paisa
        if ($validated['type'] === 'fixed') {
            $validated['value'] = (int) ($validated['value'] * 100);
        }
        if ($validated['minimum_order_amount_pkr']) {
            $validated['minimum_order_amount_pkr'] = (int) ($validated['minimum_order_amount_pkr'] * 100);
        }

        // Uppercase the coupon code
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        $coupon->load(['redemptions.user', 'redemptions.order']);
        
        // Get usage statistics
        $totalRedemptions = $coupon->redemptions()->count();
        $totalDiscountGiven = $coupon->redemptions()->sum('discount_amount_pkr');
        $uniqueUsers = $coupon->redemptions()->distinct('user_id')->count('user_id');

        return view('admin.coupons.show', compact(
            'coupon',
            'totalRedemptions',
            'totalDiscountGiven',
            'uniqueUsers'
        ));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description' => 'nullable|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_order_amount_pkr' => 'nullable|numeric|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            'usage_limit_total' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);

        // Convert amounts to paisa
        if ($validated['type'] === 'fixed') {
            $validated['value'] = (int) ($validated['value'] * 100);
        }
        if ($validated['minimum_order_amount_pkr']) {
            $validated['minimum_order_amount_pkr'] = (int) ($validated['minimum_order_amount_pkr'] * 100);
        }

        // Uppercase the coupon code
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used
        if ($coupon->redemptions()->exists()) {
            return back()->with('error', 'Cannot delete coupon that has been used.');
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}