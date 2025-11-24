<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends AdminController
{
    public function index()
    {
        // Calculate key metrics
        $totalRevenue = Order::where('status', '!=', 'cancelled')
            ->sum('total_pkr');

        $monthlyRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_pkr');

        $previousMonthRevenue = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_pkr');

        $revenueChange = $previousMonthRevenue > 0 
            ? (($monthlyRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100 
            : 0;

        $totalOrders = Order::count();
        $monthlyOrders = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $previousMonthOrders = Order::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        $ordersChange = $previousMonthOrders > 0 
            ? (($monthlyOrders - $previousMonthOrders) / $previousMonthOrders) * 100 
            : 0;

        $lowStockCount = Product::where('is_active', true)
            ->where('stock', '<=', 5)
            ->count();

        $totalCustomers = User::where('role', 'customer')->count();

        // Recent orders
        $recentOrders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly revenue data for chart
        $monthlyRevenueData = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total_pkr) as total')
        )
        ->where('status', '!=', 'cancelled')
        ->where('created_at', '>=', now()->subMonths(11))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        // Order status distribution
        $orderStatusData = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'monthlyRevenue',
            'revenueChange',
            'totalOrders',
            'monthlyOrders',
            'ordersChange',
            'lowStockCount',
            'totalCustomers',
            'recentOrders',
            'monthlyRevenueData',
            'orderStatusData'
        ));
    }
}