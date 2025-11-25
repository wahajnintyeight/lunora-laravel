@extends('admin.layouts.app')

@section('title', 'Page Details')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.pages.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Pages
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">{{ $page->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Page Details
        </h1>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View on Site
        </a>
        <a href="{{ route('admin.pages.edit', $page) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
            </svg>
            Edit Page
        </a>
    </div>
</div>
<!-- End Page Header -->

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Page Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Page Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        {{ $page->title }}
                    </h2>
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500' }}">
                        {{ $page->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-neutral-400">
                        <span class="font-medium">URL:</span> 
                        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">
                            {{ url('/') }}/{{ $page->slug }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="p-6">
                <div class="prose max-w-none dark:prose-invert">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Page Details Sidebar -->
    <div class="space-y-6">
        <!-- Page Information -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Page Information
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Title</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $page->title }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Slug</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $page->slug }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $page->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $page->updated_at->format('M j, Y g:i A') }}</dd>
                </div>
                @if($page->meta_description)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Meta Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $page->meta_description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Quick Actions
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.pages.edit', $page) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-11"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Edit Page
                </a>
                
                <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    View on Site
                </a>

                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('Are you sure you want to delete this page?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500 dark:hover:bg-red-900/20 dark:focus:bg-red-900/20">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                        Delete Page
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection