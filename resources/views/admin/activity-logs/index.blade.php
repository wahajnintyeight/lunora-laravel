@extends('admin.layouts.app')

@section('title', 'Activity Logs')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Activity Logs
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Monitor admin user activities and system changes
        </p>
    </div>
</div>
<!-- End Page Header -->

<!-- Filters -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 mb-5">
    <div class="px-6 py-4">
        <form method="GET" class="grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium mb-2 dark:text-white">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Action, IP, user...">
            </div>

            <!-- User -->
            <div>
                <label for="user" class="block text-sm font-medium mb-2 dark:text-white">User</label>
                <select name="user" id="user" class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action -->
            <div>
                <label for="action" class="block text-sm font-medium mb-2 dark:text-white">Action</label>
                <input type="text" id="action" name="action" value="{{ request('action') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="GET, POST, PUT...">
            </div>

            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium mb-2 dark:text-white">Date From</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600">
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-x-2">
                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    Clear
                </a>
            </div>
        </form>
    </div>
</div>
<!-- End Filters -->

<!-- Activity Logs Table -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
            Activity Logs ({{ $logs->total() }})
        </h2>
    </div>

    @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">User</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Action</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">IP Address</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Date & Time</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-x-3">
                                    @if($log->user)
                                        @if($log->user->avatar_url)
                                            <img class="inline-block size-8 rounded-full" src="{{ $log->user->avatar_url }}" alt="{{ $log->user->name }}">
                                        @else
                                            <div class="inline-block size-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                <span class="text-xs font-medium text-gray-800 leading-none">
                                                    {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="grow">
                                            <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $log->user->name }}</span>
                                            <span class="block text-sm text-gray-500 dark:text-neutral-500">{{ $log->user->email }}</span>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500 dark:text-neutral-500">
                                            Unknown User
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <div class="text-sm font-medium text-gray-900 dark:text-neutral-200">
                                        {{ $log->action }}
                                    </div>
                                    @if($log->user_agent)
                                        <div class="text-xs text-gray-500 dark:text-neutral-400 truncate">
                                            {{ $log->user_agent }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-neutral-200">
                                {{ $log->ip_address }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-neutral-400">
                                {{ $log->performed_at->format('M j, Y') }}
                                <div class="text-xs">
                                    {{ $log->performed_at->format('g:i:s A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                @if($log->changes && count($log->changes) > 0)
                                    <button type="button" class="hs-collapse-toggle inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:text-blue-500" id="hs-basic-collapse-{{ $log->id }}" data-hs-collapse="#hs-basic-collapse-heading-{{ $log->id }}">
                                        View Changes
                                        <svg class="hs-collapse-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </button>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-neutral-500">No changes</span>
                                @endif
                            </td>
                        </tr>
                        @if($log->changes && count($log->changes) > 0)
                            <tr id="hs-basic-collapse-heading-{{ $log->id }}" class="hs-collapse hidden w-full overflow-hidden transition-[height] duration-300">
                                <td colspan="5" class="px-6 py-4 bg-gray-50 dark:bg-neutral-700">
                                    <div class="text-sm">
                                        <h4 class="font-medium text-gray-900 dark:text-neutral-200 mb-2">Request Data:</h4>
                                        <div class="bg-white dark:bg-neutral-800 rounded-lg p-3 border border-gray-200 dark:border-neutral-600">
                                            <pre class="text-xs text-gray-600 dark:text-neutral-400 whitespace-pre-wrap">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700">
            {{ $logs->links() }}
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No activity logs found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">
                @if(request('search') || request('user') || request('action') || request('date_from'))
                    Try adjusting your filters to see more results.
                @else
                    Activity logs will appear here as admin users perform actions.
                @endif
            </p>
        </div>
    @endif
</div>
<!-- End Activity Logs Table -->
@endsection