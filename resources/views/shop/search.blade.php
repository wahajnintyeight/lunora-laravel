@extends('layouts.app')

@section('title', 'Search Results - ' . config('app.name'))
@section('meta_description', 'Search results for jewelry products')

@section('content')
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h1 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">
            Search Results
            @if(request('q'))
                for "{{ request('q') }}"
            @endif
        </h1>
        <p class="mt-1 text-gray-600 dark:text-neutral-400">
            @if(request('q'))
                Showing results for your search query
            @else
                Enter a search term to find jewelry products
            @endif
        </p>
    </div>

    @if(request('q'))
        <!-- Search Results -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Placeholder results -->
            <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
                <div class="h-52 flex flex-col justify-center items-center bg-emerald-500 rounded-t-xl">
                    <img class="h-full w-full object-cover rounded-t-xl" src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=560&q=80" alt="Search Result">
                </div>
                <div class="p-4 md:p-6">
                    <span class="block mb-1 text-xs font-semibold uppercase text-[#f59e0b] dark:text-emerald-500">
                        Rings
                    </span>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-300 dark:hover:text-white">
                        Search Result Item
                    </h3>
                    <p class="mt-3 text-gray-500 dark:text-neutral-500">
                        This is a placeholder search result item.
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-2xl font-bold text-gray-800 dark:text-neutral-200">$999</span>
                        <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- No Results -->
        <div class="text-center mt-10">
            <p class="text-gray-500 dark:text-neutral-400">
                No results found. Try adjusting your search terms.
            </p>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No search query</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Get started by entering a search term above.</p>
        </div>
    @endif
</div>
@endsection