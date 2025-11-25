@extends('admin.layouts.app')

@section('title', 'User Details')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Users
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">{{ $user->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            User Details
        </h1>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.users.edit', $user) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
            </svg>
            Edit User
        </a>
    </div>
</div>
<!-- End Page Header -->

<div class="grid lg:grid-cols-3 gap-6">
    <!-- User Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    User Information
                </h2>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-x-4 mb-6">
                    @if($user->avatar_url)
                        <img class="inline-block size-16 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                    @else
                        <div class="inline-block size-16 bg-gray-100 rounded-full flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-800 leading-none">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $user->email }}</p>
                        <div class="mt-1 flex items-center gap-x-2">
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500' : 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Registration Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $user->created_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Login</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Email Verified</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $user->email_verified_at ? 'Yes (' . $user->email_verified_at->format('M j, Y') . ')' : 'No' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Google Account</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $user->google_id ? 'Linked' : 'Not Linked' }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        @if($user->role === 'customer')
            <!-- Recent Orders (for customers only) -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Recent Orders
                    </h2>
                </div>
                @if($user->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-50 dark:bg-neutral-800">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Order</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                                    <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Total</th>
                                    <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                @foreach($user->orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                                #{{ $order->order_number }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-neutral-400">
                                                {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                            {{ $order->created_at->format('M j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500',
                                                    'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500',
                                                    'shipped' => 'bg-purple-100 text-purple-800 dark:bg-purple-800/30 dark:text-purple-500',
                                                    'delivered' => 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500',
                                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                            PKR {{ number_format($order->total_pkr / 100, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No orders yet</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">This user hasn't placed any orders.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Statistics Sidebar -->
    <div class="space-y-6">
        @if($user->role === 'customer')
            <!-- Customer Statistics -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Statistics
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Orders</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">{{ $totalOrders }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Spent</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">
                            PKR {{ number_format($totalSpent / 100, 2) }}
                        </dd>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Quick Actions
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Edit User
                </a>
                
                @if($user->role === 'customer' && $totalOrders > 0)
                    <a href="{{ route('admin.orders.index', ['customer' => $user->id]) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        View All Orders
                    </a>
                @endif

                @if($user->id !== auth()->id() && !$user->orders()->exists())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500 dark:hover:bg-red-900/20 dark:focus:bg-red-900/20">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                            </svg>
                            Delete User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection