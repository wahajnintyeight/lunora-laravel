@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Categories
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage your product categories and organize your catalog.
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="{{ route('admin.categories.create') }}">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Add Category
        </a>
    </div>
</div>
<!-- End Page Header -->

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-5 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-5 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500">
        {{ session('error') }}
    </div>
@endif

<!-- Categories Table -->
<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
                <!-- Header -->
                <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-neutral-700">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                            Categories
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-neutral-400">
                            {{ $categories->total() }} categories total
                        </p>
                    </div>

                    <div>
                        <div class="inline-flex gap-x-2">
                            <!-- Search -->
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                                    <svg class="shrink-0 size-4 text-gray-400 dark:text-white/60" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"/>
                                        <path d="m21 21-4.3-4.3"/>
                                    </svg>
                                </div>
                                <input type="text" class="py-2 ps-10 pe-16 block w-full bg-white border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder:text-neutral-400 dark:focus:ring-neutral-600" placeholder="Search categories">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Header -->

                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="ps-6 py-3 text-start">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Name
                                    </span>
                                </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Parent
                                    </span>
                                </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Products
                                    </span>
                                </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-start">
                                <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                        Status
                                    </span>
                                </div>
                            </th>

                            <th scope="col" class="px-6 py-3 text-end">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-neutral-200">
                                    Actions
                                </span>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                                <td class="size-px whitespace-nowrap">
                                    <div class="ps-6 py-3">
                                        <div class="flex items-center gap-x-3">
                                            <div class="grow">
                                                <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">
                                                    {{ $category->name }}
                                                </span>
                                                <span class="block text-sm text-gray-500 dark:text-neutral-500">
                                                    {{ $category->slug }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        @if($category->parent)
                                            <span class="text-sm text-gray-800 dark:text-neutral-200">
                                                {{ $category->parent->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500 dark:text-neutral-500">
                                                Root Category
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <span class="text-sm text-gray-800 dark:text-neutral-200">
                                            {{ $category->products_count }}
                                        </span>
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        @if($category->is_active)
                                            <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-green-100 text-green-800 rounded-full dark:bg-green-500/10 dark:text-green-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.061L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                                </svg>
                                                Active
                                            </span>
                                        @else
                                            <span class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-red-100 text-red-800 rounded-full dark:bg-red-500/10 dark:text-red-500">
                                                <svg class="size-2.5" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                                </svg>
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-1.5">
                                        <div class="hs-dropdown [--placement:bottom-right] relative inline-block">
                                            <button id="hs-table-dropdown-{{ $category->id }}" type="button" class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-lg text-gray-700 align-middle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <circle cx="12" cy="12" r="1"/>
                                                    <circle cx="19" cy="12" r="1"/>
                                                    <circle cx="5" cy="12" r="1"/>
                                                </svg>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-table-dropdown-{{ $category->id }}">
                                                <div class="py-2 first:pt-0 last:pb-0">
                                                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('admin.categories.show', $category) }}">
                                                        View
                                                    </a>
                                                    <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('admin.categories.edit', $category) }}">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div class="py-2 first:pt-0 last:pb-0">
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:ring-2 focus:ring-red-500 dark:text-red-500 dark:hover:bg-red-800/30">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-neutral-100 mb-2">No categories found</h3>
                                        <p class="text-gray-500 dark:text-neutral-500 mb-4">Get started by creating your first category.</p>
                                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Add Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- End Table -->

                <!-- Footer -->
                @if($categories->hasPages())
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-neutral-700">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-neutral-400">
                                Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} results
                            </p>
                        </div>

                        <div>
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
                <!-- End Footer -->
            </div>
        </div>
    </div>
</div>
<!-- End Categories Table -->
@endsection