@extends('admin.layouts.app')

@section('title', 'Edit Product - ' . $product->name)

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
                        <span class="text-gray-500 dark:text-neutral-400">Edit Product</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Edit Product
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Update product information and settings
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.products.show', $product) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            View Product
        </a>
        
        <a href="{{ route('admin.products.variants', $product) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Variants ({{ $product->variants->count() }})
        </a>
    </div>
</div>
<!-- End Page Header -->

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-5 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-5 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Basic Information</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Product Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium mb-2 dark:text-white">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                        <textarea id="description" name="description" rows="4" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Material and Brand -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="material" class="block text-sm font-medium mb-2 dark:text-white">Material</label>
                            <input type="text" id="material" name="material" value="{{ old('material', $product->material) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                        
                        <div>
                            <label for="brand" class="block text-sm font-medium mb-2 dark:text-white">Brand</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand) }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing and Inventory -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Pricing & Inventory</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Pricing -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Price (PKR)</label>
                            <input type="number" id="price_pkr" name="price_pkr" value="{{ old('price_pkr', $product->price_pkr / 100) }}" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                        </div>
                        
                        <div>
                            <label for="compare_at_price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Compare at Price (PKR)</label>
                            <input type="number" id="compare_at_price_pkr" name="compare_at_price_pkr" value="{{ old('compare_at_price_pkr', $product->compare_at_price_pkr ? $product->compare_at_price_pkr / 100 : '') }}" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        </div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium mb-2 dark:text-white">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Product Images</h2>
                </div>
                
                <div class="p-6">
                    <!-- Existing Images -->
                    @if($product->images->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-800 dark:text-neutral-200 mb-3">Current Images</h3>
                            <div id="existing-images" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                @foreach($product->images as $image)
                                    <div class="relative group" data-image-id="{{ $image->id }}">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-32 object-cover rounded-lg border border-gray-200 dark:border-neutral-700">
                                        
                                        <!-- Image Actions -->
                                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                            <button type="button" class="delete-image-btn p-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500" data-image-id="{{ $image->id }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Drag Handle -->
                                        <div class="absolute top-2 left-2 cursor-move bg-white bg-opacity-75 rounded p-1">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add New Images -->
                    <div class="space-y-4">
                        <!-- Image Input Method Toggle -->
                        <div>
                            <label class="block text-sm font-medium mb-3 dark:text-white">Add New Images</label>
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center">
                                    <input type="radio" name="image_input_method" value="upload" checked class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                    <span class="text-sm text-gray-500 ms-2 dark:text-neutral-400">File Upload</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="image_input_method" value="url" class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                    <span class="text-sm text-gray-500 ms-2 dark:text-neutral-400">Image URLs</span>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload Section -->
                        <div id="upload-section">
                            <input type="file" id="images" name="images[]" multiple accept="image/*" class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400">
                            <p class="mt-2 text-sm text-gray-500 dark:text-neutral-500">Upload JPEG, PNG, or WebP images. Maximum 2MB per image.</p>
                        </div>

                        <!-- URL Input Section -->
                        <div id="url-section" class="hidden">
                            <div id="image-urls-container">
                                <div class="image-url-input flex gap-2 mb-2">
                                    <input type="url" name="image_urls[]" placeholder="https://example.com/image.jpg" class="flex-1 py-2 px-3 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                    <button type="button" class="remove-url-btn py-2 px-3 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:focus:bg-red-900/20" style="display: none;">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="add-url-btn" class="mt-2 py-2 px-3 text-sm font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 focus:outline-none focus:bg-blue-50 dark:border-blue-700 dark:text-blue-400 dark:hover:bg-blue-900/20 dark:focus:bg-blue-900/20">
                                Add Another URL
                            </button>
                            <p class="mt-2 text-sm text-gray-500 dark:text-neutral-500">
                                Enter direct URLs to images. Supported formats: JPEG, PNG, WebP, GIF.
                            </p>
                            @if($errors->has('image_urls.*'))
                                @foreach($errors->get('image_urls.*') as $error)
                                    <p class="text-sm text-red-600 mt-2">{{ $error[0] }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Category and Status -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Organization</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium mb-2 dark:text-white">Category</label>
                        <select id="category_id" name="category_id" class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Toggles -->
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active (visible to customers)</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            <label for="is_featured" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Featured product</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">Quick Actions</h2>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.products.variants', $product) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Manage Variants ({{ $product->variants->count() }})
                    </a>
                    
                    <a href="{{ route('admin.products.options', $product) }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
                        </svg>
                        Manage Options ({{ $product->options->count() }})
                    </a>
                </div>
            </div>

            <!-- Save Actions -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="p-6 space-y-3">
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17,21 17,13 7,13 7,21"/>
                            <polyline points="7,3 7,8 15,8"/>
                        </svg>
                        Update Product
                    </button>
                    
                    <a href="{{ route('admin.products.index') }}" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const existingImages = document.getElementById('existing-images');
    const uploadRadio = document.querySelector('input[name="image_input_method"][value="upload"]');
    const urlRadio = document.querySelector('input[name="image_input_method"][value="url"]');
    const uploadSection = document.getElementById('upload-section');
    const urlSection = document.getElementById('url-section');
    const addUrlBtn = document.getElementById('add-url-btn');
    const urlContainer = document.getElementById('image-urls-container');
    
    // Make images sortable
    if (existingImages) {
        new Sortable(existingImages, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                updateImageOrder();
            }
        });
    }

    // Toggle between upload and URL sections
    function toggleImageInputMethod() {
        if (uploadRadio.checked) {
            uploadSection.classList.remove('hidden');
            urlSection.classList.add('hidden');
            // Clear URL inputs when switching to upload
            urlContainer.querySelectorAll('input[name="image_urls[]"]').forEach(input => input.value = '');
        } else {
            uploadSection.classList.add('hidden');
            urlSection.classList.remove('hidden');
            // Clear file input when switching to URL
            document.getElementById('images').value = '';
        }
    }

    uploadRadio.addEventListener('change', toggleImageInputMethod);
    urlRadio.addEventListener('change', toggleImageInputMethod);

    // Add new URL input
    addUrlBtn.addEventListener('click', function() {
        const newInput = document.createElement('div');
        newInput.className = 'image-url-input flex gap-2 mb-2';
        newInput.innerHTML = `
            <input type="url" name="image_urls[]" placeholder="https://example.com/image.jpg" class="flex-1 py-2 px-3 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
            <button type="button" class="remove-url-btn py-2 px-3 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 dark:focus:bg-red-900/20">
                Remove
            </button>
        `;
        urlContainer.appendChild(newInput);
        updateRemoveButtons();
    });

    // Remove URL input
    urlContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-url-btn')) {
            e.target.closest('.image-url-input').remove();
            updateRemoveButtons();
        }
    });

    // Show/hide remove buttons based on number of inputs
    function updateRemoveButtons() {
        const inputs = urlContainer.querySelectorAll('.image-url-input');
        inputs.forEach((input, index) => {
            const removeBtn = input.querySelector('.remove-url-btn');
            if (inputs.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // Delete image functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-image-btn')) {
            const btn = e.target.closest('.delete-image-btn');
            const imageId = btn.dataset.imageId;
            
            if (confirm('Are you sure you want to delete this image?')) {
                deleteImage(imageId);
            }
        }
    });

    function deleteImage(imageId) {
        fetch(`{{ route('admin.products.images.delete', ['image' => '__IMAGE_ID__']) }}`.replace('__IMAGE_ID__', imageId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-image-id="${imageId}"]`).remove();
            } else {
                alert('Failed to delete image: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to delete image');
        });
    }

    function updateImageOrder() {
        const imageElements = existingImages.querySelectorAll('[data-image-id]');
        const imageIds = Array.from(imageElements).map(el => el.dataset.imageId);

        fetch(`{{ route('admin.products.images.reorder', $product) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                images: imageIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update image order:', data.message);
            }
        })
        .catch(error => {
            console.error('Error updating image order:', error);
        });
    }

    // Initial setup
    updateRemoveButtons();
});
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
}
</style>
@endpush