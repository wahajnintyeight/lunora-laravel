<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class OrderController extends AdminController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->withCount('items');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Get status counts for filter tabs
        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.variant', 'addresses']);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->updateStatus($order, $request->status);
            
            if ($request->filled('notes')) {
                $order->update(['notes' => $request->notes]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'restore_stock' => 'boolean',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->cancelOrder($order, $request->boolean('restore_stock', true));
            
            if ($request->filled('reason')) {
                $order->update(['notes' => $request->reason]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'restore_stock' => 'boolean',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->processRefund($order, $request->boolean('restore_stock', true));
            
            if ($request->filled('reason')) {
                $order->update(['notes' => $request->reason]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order refunded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $csvData = [];
        $csvData[] = [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Status',
            'Items Count',
            'Subtotal (PKR)',
            'Discount (PKR)',
            'Shipping (PKR)',
            'Total (PKR)',
            'Order Date',
            'Coupon Code',
        ];

        foreach ($orders as $order) {
            $csvData[] = [
                $order->order_number,
                $order->user ? $order->user->name : 'Guest',
                $order->email,
                ucfirst($order->status),
                $order->items->count(),
                number_format($order->subtotal_pkr / 100, 2),
                number_format($order->discount_pkr / 100, 2),
                number_format($order->shipping_pkr / 100, 2),
                number_format($order->total_pkr / 100, 2),
                $order->created_at->format('Y-m-d H:i:s'),
                $order->coupon_code ?? '',
            ];
        }

        $filename = 'orders-export-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}