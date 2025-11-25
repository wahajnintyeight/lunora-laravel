@extends('admin.layouts.app')

@section('title', 'Create Product')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Create Product
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Add a new product to your catalog
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.products.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Back to Products
        </a>
    </div>
</div>
<!-- End Page Header -->

<!-- Form -->
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="grid lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 lg:space-y-6">
            <!-- Basic Information -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Basic Information
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Product Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter product name" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium mb-2 dark:text-white">SKU *</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter SKU" required>
                        @error('sku')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium mb-2 dark:text-white">Description</label>
                        <textarea id="description" name="description" rows="4" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Material and Brand -->
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="material" class="block text-sm font-medium mb-2 dark:text-white">Material</label>
                            <input type="text" id="material" name="material" value="{{ old('material') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="e.g., Gold, Silver">
                            @error('material')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="brand" class="block text-sm font-medium mb-2 dark:text-white">Brand</label>
                            <input type="text" id="brand" name="brand" value="{{ old('brand') }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter brand name">
                            @error('brand')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing and Inventory -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Pricing & Inventory
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Price -->
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Price (PKR) *</label>
                            <input type="number" id="price_pkr" name="price_pkr" value="{{ old('price_pkr') }}" step="0.01" min="0" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="0.00" required>
                            @error('price_pkr')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="compare_at_price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Compare at Price (PKR)</label>
                            <input type="number" id="compare_at_price_pkr" name="compare_at_price_pkr" value="{{ old('compare_at_price_pkr') }}" step="0.01" min="0" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="0.00">
                            @error('compare_at_price_pkr')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-medium mb-2 dark:text-white">Stock Quantity *</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="0" required>
                        @error('stock')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Product Images
                    </h2>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Image Input Method Toggle -->
                    <div>
                        <label class="block text-sm font-medium mb-3 dark:text-white">Image Input Method</label>
                        <div class="flex gap-4">
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
                        <label for="images" class="block text-sm font-medium mb-2 dark:text-white">Upload Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 file:bg-gray-50 file:border-0 file:me-4 file:py-2 file:px-4 dark:file:bg-neutral-700 dark:file:text-neutral-400">
                        <p class="mt-2 text-sm text-gray-500 dark:text-neutral-500">
                            Upload multiple images. Supported formats: JPEG, PNG, WebP. Max size: 2MB per image.
                        </p>
                        @error('images.*')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- URL Input Section -->
                    <div id="url-section" class="hidden">
                        <label class="block text-sm font-medium mb-2 dark:text-white">Image URLs</label>
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

        <!-- Sidebar -->
        <div class="space-y-4 lg:space-y-6">
            <!-- Category -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Category
                    </h2>
                </div>
                
                <div class="p-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium mb-2 dark:text-white">Category *</label>
                        <select name="category_id" id="category_id" class="py-2 px-3 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                        Status
                    </h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active</label>
                    </div>

                    <!-- Featured Status -->
                    <div class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                        <label for="is_featured" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Featured</label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
                <div class="p-6 space-y-4">
                    <button type="submit" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Create Product
                    </button>
                    
                    <a href="{{ route('admin.products.index') }}" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- End Form -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadRadio = document.querySelector('input[name="image_input_method"][value="upload"]');
    const urlRadio = document.querySelector('input[name="image_input_method"][value="url"]');
    const uploadSection = document.getElementById('upload-section');
    const urlSection = document.getElementById('url-section');
    const addUrlBtn = document.getElementById('add-url-btn');
    const urlContainer = document.getElementById('image-urls-container');

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

    // Initial setup
    updateRemoveButtons();
});
</script>
@endpush