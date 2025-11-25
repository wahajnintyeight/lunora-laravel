@props([
    'name' => 'images[]',
    'multiple' => true,
    'maxFiles' => 10,
    'maxSize' => 2048, // KB
    'accept' => 'image/jpeg,image/png,image/webp',
    'showPreview' => true,
    'allowAltText' => true
])

<div class="image-upload-dropzone" data-max-files="{{ $multiple ? $maxFiles : 1 }}" data-max-size="{{ $maxSize }}">
    <!-- Drop Zone -->
    <div class="dropzone border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-500 transition-colors cursor-pointer">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
        </svg>
        <p class="mt-2 text-sm text-gray-600">
            <span class="font-medium text-[#f59e0b] hover:text-emerald-500">
                Click to upload
            </span>
            or drag and drop
        </p>
        <p class="text-xs text-gray-500">
            {{ strtoupper(str_replace(['image/', ','], ['', ', '], $accept)) }} up to {{ $maxSize }}KB each
            @if($multiple)
                (max {{ $maxFiles }} files)
            @endif
        </p>
        
        <input type="file" 
               name="{{ $name }}" 
               class="file-input hidden"
               {{ $multiple ? 'multiple' : '' }}
               accept="{{ $accept }}"
               data-max-size="{{ $maxSize * 1024 }}">
    </div>

    @if($showPreview)
        <!-- File Preview -->
        <div class="file-preview hidden mt-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">Selected Files</h4>
            <div class="file-list space-y-3"></div>
        </div>
    @endif

    <!-- Error Messages -->
    <div class="error-messages hidden mt-4">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Upload Errors</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="error-list list-disc pl-5 space-y-1"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeImageUploadDropzones();
});

function initializeImageUploadDropzones() {
    document.querySelectorAll('.image-upload-dropzone').forEach(dropzoneContainer => {
        const dropzone = dropzoneContainer.querySelector('.dropzone');
        const fileInput = dropzoneContainer.querySelector('.file-input');
        const filePreview = dropzoneContainer.querySelector('.file-preview');
        const fileList = dropzoneContainer.querySelector('.file-list');
        const errorMessages = dropzoneContainer.querySelector('.error-messages');
        const errorList = dropzoneContainer.querySelector('.error-list');
        
        const maxFiles = parseInt(dropzoneContainer.dataset.maxFiles);
        const maxSize = parseInt(dropzoneContainer.dataset.maxSize) * 1024; // Convert to bytes
        
        let selectedFiles = [];

        // Click to upload
        dropzone.addEventListener('click', () => {
            fileInput.click();
        });

        // File input change
        fileInput.addEventListener('change', (e) => {
            handleFiles(Array.from(e.target.files));
        });

        // Drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        dropzone.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropzone.classList.add('border-emerald-500', 'bg-emerald-50');
        }

        function unhighlight() {
            dropzone.classList.remove('border-emerald-500', 'bg-emerald-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = Array.from(dt.files);
            handleFiles(files);
        }

        function handleFiles(files) {
            const errors = [];
            const validFiles = [];

            // Validate files
            files.forEach(file => {
                // Check file type
                if (!file.type.startsWith('image/')) {
                    errors.push(`${file.name}: Not a valid image file`);
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    errors.push(`${file.name}: File size exceeds ${Math.round(maxSize / 1024)}KB limit`);
                    return;
                }

                validFiles.push(file);
            });

            // Check max files limit
            if (selectedFiles.length + validFiles.length > maxFiles) {
                errors.push(`Maximum ${maxFiles} files allowed`);
                validFiles.splice(maxFiles - selectedFiles.length);
            }

            // Show errors if any
            if (errors.length > 0) {
                showErrors(errors);
            } else {
                hideErrors();
            }

            // Add valid files
            selectedFiles = selectedFiles.concat(validFiles);
            updateFileInput();
            updatePreview();
        }

        function showErrors(errors) {
            errorList.innerHTML = '';
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorList.appendChild(li);
            });
            errorMessages.classList.remove('hidden');
        }

        function hideErrors() {
            errorMessages.classList.add('hidden');
        }

        function updateFileInput() {
            // Create a new DataTransfer object to update the file input
            const dt = new DataTransfer();
            selectedFiles.forEach(file => {
                dt.items.add(file);
            });
            fileInput.files = dt.files;
        }

        function updatePreview() {
            if (!filePreview || selectedFiles.length === 0) {
                if (filePreview) filePreview.classList.add('hidden');
                return;
            }

            filePreview.classList.remove('hidden');
            fileList.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';

                const reader = new FileReader();
                reader.onload = function(e) {
                    fileItem.innerHTML = `
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                                <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${file.name}</div>
                                <div class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            ${@json($allowAltText) ? `
                                <input type="text" 
                                       placeholder="Alt text..." 
                                       class="px-2 py-1 text-xs border border-gray-300 rounded w-32"
                                       onchange="updateAltText(${index}, this.value)">
                            ` : ''}
                            <button type="button" 
                                    onclick="removeFile(${index})"
                                    class="text-red-600 hover:text-red-800 p-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);

                fileList.appendChild(fileItem);
            });
        }

        // Make functions available globally for this dropzone
        window.updateAltText = function(index, altText) {
            if (selectedFiles[index]) {
                selectedFiles[index].altText = altText;
            }
        };

        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            updatePreview();
            hideErrors();
        };
    });
}
</script>
@endpush