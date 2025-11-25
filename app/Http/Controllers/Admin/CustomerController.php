<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends AdminController
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount(['orders', 'carts']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Registration date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->latest()->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->load([
            'orders' => function ($query) {
                $query->latest()->take(10);
            },
            'orders.items.product'
        ]);

        // Calculate customer statistics
        $totalSpent = $customer->orders()
            ->where('status', '!=', 'cancelled')
            ->sum('total_pkr');

        $averageOrderValue = $customer->orders()
            ->where('status', '!=', 'cancelled')
            ->avg('total_pkr');

        $lastOrderDate = $customer->orders()->latest()->first()?->created_at;

        return view('admin.customers.show', compact(
            'customer',
            'totalSpent',
            'averageOrderValue',
            'lastOrderDate'
        ));
    }

    public function edit(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }
}