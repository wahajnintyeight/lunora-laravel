@extends('admin.layouts.app')

@section('title', 'Edit Category')

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
                        <span class="ml-1 text-gray-500 dark:text-neutral-400">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Edit Category: {{ $category->name }}
        </h1>
    </div>
</div>
<!-- End Page Header -->

<!-- Form -->
<div class="max-w-4xl">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
        <div class="p-4 sm:p-7">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid sm:grid-cols-12 gap-2 sm:gap-6">
                    <!-- Name -->
                    <div class="sm:col-span-3">
                        <label for="name" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                            Name
                        </label>
                        <span class="text-red-500">*</span>
                    </div>
                    <div class="sm:col-span-9">
                        <input id="name" name="name" type="text" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Category name" value="{{ old('name', $category->name) }}" required>
                        @error('name')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div class="sm:col-span-3">
                        <label for="parent_id" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                            Parent Category
                        </label>
                    </div>
                    <div class="sm:col-span-9">
                        <select id="parent_id" name="parent_id" class="py-2 px-3 pe-9 block w-full border-gray-200 shadow-sm rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                            <option value="">Select parent category (optional)</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-3">
                        <label for="description" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                            Description
                        </label>
                    </div>
                    <div class="sm:col-span-9">
                        <textarea id="description" name="description" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="4" placeholder="Category description...">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image -->
                    <div class="sm:col-span-3">
                        <label for="image" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                            Category Image
                        </label>
                    </div>
                    <div class="sm:col-span-9">
                        <div class="space-y-3">
                            <!-- Current Image -->
                            @if($category->image_url)
                                <div class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-neutral-400 mb-2">Current Image:</p>
                                    <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}" class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                                </div>
                            @endif

                            <!-- File Upload -->
                            <div>
                                <label for="image" class="block">
                                    <div class="flex items-center justify-center w-full px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 dark:border-neutral-600 dark:hover:border-blue-400 transition-colors">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <circle cx="32" cy="20" r="4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21 37l7-7 10 10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-neutral-400">
                                                <span class="font-medium text-blue-600 dark:text-blue-400">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-neutral-500">PNG, JPG, GIF up to 2MB</p>
                                        </div>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </label>
                            </div>

                            <!-- Image Preview -->
                            <div id="image-preview" class="hidden">
                                <img id="preview-img" src="" alt="Preview" class="h-24 w-24 object-cover rounded-lg border border-gray-200">
                            </div>

                            <!-- URL Input -->
                            <div>
                                <label for="image_url" class="block text-sm text-gray-600 dark:text-neutral-400 mb-1">Or enter image URL</label>
                                <input id="image_url" name="image_url" type="url" class="py-2 px-3 pe-11 block w-full border-gray-200 shadow-sm text-sm rounded-lg focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="https://example.com/image.jpg" value="{{ old('image_url', $category->image_url) }}">
                            </div>
                        </div>
                        @error('image')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                        @error('image_url')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-3">
                        <label for="is_active" class="inline-block text-sm text-gray-800 mt-2.5 dark:text-neutral-200">
                            Status
                        </label>
                    </div>
                    <div class="sm:col-span-9">
                        <div class="flex">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active</label>
                        </div>
                        @error('is_active')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-5 flex justify-end gap-x-2">
                    <a href="{{ route('admin.categories.index') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
                        Cancel
                    </a>
                    <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Form -->

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
