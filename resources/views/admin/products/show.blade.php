@extends('admin.layouts.app')

@section('title', $product->name)

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.products.index') }}" class="text-gray-700 hover:text-blue-600 dark:text-neutral-300 dark:hover:text-blue-500">
                        Products
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="text-gray-500 dark:text-neutral-400">{{ $product->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            {{ $product->name }}
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Product details and management
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.products.edit', $product) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
            </svg>
            Edit Product
        </a>
        
        <div class="hs-dropdown relative inline-block">
            <button type="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                Actions
                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
            </button>
            
            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700">
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="{{ route('admin.products.variants', $product) }}">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Manage Variants
                </a>
                <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="{{ route('admin.products.options', $product) }}">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Manage Options
                </a>
                <div class="border-t border-gray-200 dark:border-neutral-700 my-2"></div>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:text-red-500 dark:hover:bg-red-800/30 dark:focus:bg-red-800/30">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Page Header -->

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Images -->
        @if($product->images->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Product Images</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($product->images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-neutral-700">
                                
                                @if($loop->first)
                                    <div class="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                        Primary
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Product Details -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Product Details</h2>
            </div>
            
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">SKU</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-100">{{ $product->sku }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-100">{{ $product->category->name }}</dd>
                    </div>
                    
                    @if($product->material)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Material</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-100">{{ $product->material }}</dd>
                        </div>
                    @endif
                    
                    @if($product->brand)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Brand</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-100">{{ $product->brand }}</dd>
                        </div>
                    @endif
                </dl>
                
                @if($product->description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Description</dt>
                        <dd class="mt-2 text-sm text-gray-900 dark:text-neutral-100">{{ $product->description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Variants -->
        @if($product->variants->count() > 0)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Product Variants</h2>
                        <a href="{{ route('admin.products.variants', $product) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            Manage Variants
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">SKU</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Options</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Price</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Stock</th>
                                <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @foreach($product->variants->take(5) as $variant)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $variant->sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        @if($variant->options_json)
                                            @foreach($variant->options_json as $option => $value)
                                                <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500 mr-1">
                                                    {{ $option }}: {{ $value }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-gray-500 dark:text-neutral-500">No options</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        @if($variant->price_pkr)
                                            PKR {{ number_format($variant->price_pkr / 100, 2) }}
                                        @else
                                            <span class="text-gray-500 dark:text-neutral-500">Uses base price</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $variant->stock <= 5 ? 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' : 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' }}">
                                            {{ $variant->stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $variant->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                                            {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($product->variants->count() > 5)
                        <div class="px-6 py-3 border-t border-gray-200 dark:border-neutral-700">
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Showing 5 of {{ $product->variants->count() }} variants. 
                                <a href="{{ route('admin.products.variants', $product) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View all</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status and Pricing -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Status & Pricing</h2>
            </div>
            
            <div class="p-6 space-y-4">
                <!-- Status -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Status</span>
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Featured -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Featured</span>
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $product->is_featured ? 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500' : 'bg-gray-100 text-gray-800 dark:bg-gray-800/30 dark:text-gray-500' }}">
                        {{ $product->is_featured ? 'Yes' : 'No' }}
                    </span>
                </div>

                <!-- Price -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Price</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-neutral-100">
                        PKR {{ number_format($product->price_pkr / 100, 2) }}
                    </span>
                </div>

                @if($product->compare_at_price_pkr)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Compare Price</span>
                        <span class="text-sm text-gray-500 dark:text-neutral-400 line-through">
                            PKR {{ number_format($product->compare_at_price_pkr / 100, 2) }}
                        </span>
                    </div>
                @endif

                <!-- Stock -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Stock</span>
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $product->stock <= 5 ? 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' : 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' }}">
                        {{ $product->stock }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Quick Stats</h2>
            </div>
            
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Variants</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-neutral-100">{{ $product->variants->count() }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Options</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-neutral-100">{{ $product->options->count() }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Images</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-neutral-100">{{ $product->images->count() }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Created</span>
                    <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $product->created_at->format('M j, Y') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-500 dark:text-neutral-400">Updated</span>
                    <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $product->updated_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Actions</h2>
            </div>
            
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Edit Product
                </a>
                
                <a href="{{ route('admin.products.variants', $product) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Manage Variants
                </a>
                
                <a href="{{ route('admin.products.options', $product) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
                    Manage Options
                </a>
            </div>
        </div>
    </div>
</div>
@endsection