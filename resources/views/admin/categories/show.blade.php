@extends('admin.layouts.app')

@section('title', 'Category Details')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                        Categories
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">{{ $category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            {{ $category->name }}
        </h1>
    </div>
    <div class="flex gap-x-2">
        <a href="{{ route('admin.categories.edit', $category) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
            Edit Category
        </a>
    </div>
</div>
<!-- End Page Header -->

<div class="grid lg:grid-cols-3 gap-5">
    <!-- Category Details -->
    <div class="lg:col-span-2">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                    Category Information
                </h2>
                
                @if($category->image_url)
                    <div class="mb-4">
                        <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="h-40 w-40 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                <dl class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $category->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">{{ $category->slug }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Parent Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-blue-600 hover:underline">
                                    {{ $category->parent->name }}
                                </a>
                            @else
                                <span class="text-gray-500">None (Root Category)</span>
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Status</dt>
                        <dd class="mt-1">
                            @if($category->is_active)
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                                    Inactive
                                </span>
                            @endif
                        </dd>
                    </div>
                    
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $category->description ?? 'No description provided' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Subcategories -->
        @if($category->children->count() > 0)
        <div class="mt-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                    Subcategories ({{ $category->children->count() }})
                </h2>
                
                <div class="space-y-2">
                    @foreach($category->children as $child)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg dark:bg-neutral-700">
                            <div>
                                <a href="{{ route('admin.categories.show', $child) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 dark:text-neutral-200">
                                    {{ $child->name }}
                                </a>
                                <p class="text-xs text-gray-500 dark:text-neutral-400">
                                    {{ $child->products_count ?? 0 }} products
                                </p>
                            </div>
                            <a href="{{ route('admin.categories.edit', $child) }}" class="text-sm text-blue-600 hover:underline">
                                Edit
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Stats -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                    Statistics
                </h2>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Products</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">
                            {{ $category->products->count() }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Subcategories</dt>
                        <dd class="mt-1 text-2xl font-semibold text-gray-900 dark:text-neutral-200">
                            {{ $category->children->count() }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $category->created_at->format('M d, Y') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                            {{ $category->updated_at->format('M d, Y') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-5 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200 mb-4">
                    Actions
                </h2>
                
                <div class="space-y-2">
                    <a href="{{ route('category.show', $category->slug) }}" target="_blank" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        View on Site
                    </a>
                    
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700">
                            Delete Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
