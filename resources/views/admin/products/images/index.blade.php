@extends('admin.layouts.app')

@section('title', 'Product Images - ' . $product->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Images</h1>
            <p class="text-gray-600">{{ $product->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.show', $product) }}" 
               class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Back to Product
            </a>
            <button type="button" 
                    onclick="openUploadModal()"
                    class="px-4 py-2 bg-[#f59e0b] text-white rounded-lg hover:bg-emerald-700">
                Upload Images
            </button>
        </div>
    </div>

    <!-- Image Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Images ({{ $images->count() }})</h2>
                <div class="flex items-center gap-3">
                    <button type="button" 
                            onclick="toggleBulkActions()"
                            class="px-3 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Bulk Actions
                    </button>
                    <button type="button" 
                            onclick="toggleSortMode()"
                            class="px-3 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                        Reorder
                    </button>
                </div>
            </div>

            @if($images->count() > 0)
                <!-- Images Grid -->
                <div id="images-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach($images as $image)
                        <div class="image-item relative group" data-image-id="{{ $image->id }}">
                            <!-- Primary Badge -->
                            @if($image->is_primary)
                                <div class="absolute top-2 left-2 z-10 px-2 py-1 bg-[#f59e0b] text-white text-xs rounded">
                                    Primary
                                </div>
                            @endif

                            <!-- Image -->
                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                <img src="{{ $image->thumbnail_url }}" 
                                     alt="{{ $image->alt_text }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <!-- Image Info -->
                            <div class="mt-2 space-y-1">
                                <div class="text-xs text-gray-500">
                                    Sort: {{ $image->sort_order }}
                                </div>
                                @if($image->alt_text)
                                    <div class="text-xs text-gray-600 truncate" title="{{ $image->alt_text }}">
                                        {{ $image->alt_text }}
                                    </div>
                                @endif
                            </div>

                            <!-- Actions Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                <div class="flex items-center gap-2">
                                    <button type="button" 
                                            onclick="viewImage('{{ $image->url }}', '{{ $image->alt_text }}')"
                                            class="p-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100"
                                            title="View Full Size">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button type="button" 
                                            onclick="editImage({{ $image->id }}, '{{ $image->alt_text }}', {{ $image->sort_order }})"
                                            class="p-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100"
                                            title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    @if(!$image->is_primary)
                                        <button type="button" 
                                                onclick="setPrimaryImage({{ $image->id }})"
                                                class="p-2 bg-[#f59e0b] text-white rounded-lg hover:bg-emerald-700"
                                                title="Set as Primary">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    @endif
                                    <button type="button" 
                                            onclick="deleteImage({{ $image->id }})"
                                            class="p-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                            title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No images</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by uploading your first product image.</p>
                    <div class="mt-6">
                        <button type="button" 
                                onclick="openUploadModal()"
                                class="px-4 py-2 bg-[#f59e0b] text-white rounded-lg hover:bg-emerald-700">
                            Upload Images
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="upload-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeUploadModal()"></div>
        
        <div class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Upload Product Images</h3>
                <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="upload-form" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <!-- Drag and Drop Area -->
                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-500 transition-colors">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">
                        <span class="font-medium text-[#f59e0b] hover:text-emerald-500 cursor-pointer" onclick="document.getElementById('file-input').click()">
                            Click to upload
                        </span>
                        or drag and drop
                    </p>
                    <p class="text-xs text-gray-500">JPEG, PNG, WebP up to 2MB each (max 10 files)</p>
                    <input type="file" 
                           id="file-input" 
                           name="images[]" 
                           multiple 
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden"
                           onchange="handleFileSelect(this.files)">
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="closeUploadModal()"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="upload-btn"
                            class="px-4 py-2 bg-[#f59e0b] text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed">
                        Upload Images
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let selectedFiles = [];

// Initialize drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('drop-zone');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    dropZone.addEventListener('drop', handleDrop, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    document.getElementById('drop-zone').classList.add('border-emerald-500', 'bg-emerald-50');
}

function unhighlight(e) {
    document.getElementById('drop-zone').classList.remove('border-emerald-500', 'bg-emerald-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFileSelect(files);
}

function handleFileSelect(files) {
    selectedFiles = Array.from(files).slice(0, 10); // Limit to 10 files
}

// Modal functions
function openUploadModal() {
    document.getElementById('upload-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeUploadModal() {
    document.getElementById('upload-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    selectedFiles = [];
    document.getElementById('file-input').value = '';
}

// Image actions
function viewImage(url, altText) {
    window.open(url, '_blank');
}

function editImage(imageId, altText, sortOrder) {
    const newAltText = prompt('Enter alt text:', altText || '');
    if (newAltText === null) return;
    
    fetch(`/admin/products/images/${imageId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            alt_text: newAltText,
            sort_order: sortOrder
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the image.');
    });
}

function deleteImage(imageId) {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }
    
    fetch(`/admin/products/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while deleting the image.');
    });
}

function setPrimaryImage(imageId) {
    fetch(`/admin/products/images/${imageId}/set-primary`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while setting the primary image.');
    });
}

// Form submission
document.getElementById('upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fileInput = document.getElementById('file-input');
    if (fileInput.files.length === 0) {
        alert('Please select at least one image to upload.');
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('product_id', document.querySelector('input[name="product_id"]').value);
    
    Array.from(fileInput.files).forEach((file, index) => {
        formData.append('images[]', file);
    });
    
    const uploadBtn = document.getElementById('upload-btn');
    uploadBtn.disabled = true;
    uploadBtn.textContent = 'Uploading...';
    
    fetch('/admin/products/images/upload', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while uploading images.');
    })
    .finally(() => {
        uploadBtn.disabled = false;
        uploadBtn.textContent = 'Upload Images';
    });
});
</script>
@endpush