<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends AdminController
{
    public function index(Request $request)
    {
        $query = AdminActivityLog::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // User filter
        if ($request->filled('user')) {
            $query->where('user_id', $request->user);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('performed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('performed_at', '<=', $request->date_to);
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }

        $logs = $query->latest('performed_at')->paginate(50);

        // Get unique users for filter dropdown
        $users = AdminActivityLog::with('user')
            ->select('user_id')
            ->distinct()
            ->get()
            ->pluck('user')
            ->filter()
            ->sortBy('name');

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }
}