@extends('admin.layouts.app')

@section('title', 'Edit Page')

@section('content')
    <!-- Page Header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-5">
        <div class="mb-2 sm:mb-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.pages.index') }}"
                            class="text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                            Pages
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('admin.pages.show', $page) }}"
                                class="ml-1 text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200">{{ $page->title }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-1 text-gray-500 dark:text-neutral-400">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
                Edit Page
            </h1>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="max-w-4xl">
        <!-- Form -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Page Details
                </h2>
                <p class="text-sm text-gray-600 dark:text-neutral-400">
                    Update page content and settings
                </p>
            </div>

            <form method="POST" action="{{ route('admin.pages.update', $page) }}">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium mb-2 dark:text-white">Page Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $page->title) }}"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            required>
                        @error('title')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium mb-2 dark:text-white">URL Slug</label>
                        <div class="flex">
                            <span
                                class="inline-flex items-center px-3 rounded-s-md border border-e-0 border-gray-200 bg-gray-50 text-gray-500 text-sm dark:bg-neutral-700 dark:border-neutral-700 dark:text-neutral-400">
                                {{ url('/') }}/
                            </span>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $page->slug) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-e-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                required>
                        </div>
                        @error('slug')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium mb-2 dark:text-white">Content</label>
                        <textarea id="content" name="content" rows="15"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            required>{!! old('content', $page->content) !!}</textarea>
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            Rich text editor with formatting options
                        </p>
                        @error('content')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Meta Description -->
                    <div>
                        <label for="meta_description" class="block text-sm font-medium mb-2 dark:text-white">Meta
                            Description</label>
                        <textarea id="meta_description" name="meta_description" rows="3" maxlength="160"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                            placeholder="Brief description for search engines (max 160 characters)">{{ old('meta_description', $page->meta_description) }}</textarea>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500 dark:text-neutral-400">
                                Used for SEO and social media previews
                            </p>
                            <span id="meta-counter"
                                class="text-xs text-gray-500 dark:text-neutral-400">{{ strlen($page->meta_description ?? '') }}/160</span>
                        </div>
                        @error('meta_description')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published Status -->
                    <div>
                        <div class="flex items-center">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" id="is_published" name="is_published" value="1"
                                {{ old('is_published', $page->is_published) ? 'checked' : '' }}
                                class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            <label for="is_published" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Publish
                                page</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 dark:text-neutral-400">
                            Unpublished pages are not visible to visitors
                        </p>
                        @error('is_published')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Page Information (Read-only) -->
                    <div class="border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-neutral-200 mb-4">Page Information</h3>

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                    {{ $page->created_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                    {{ $page->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Current URL</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-neutral-200">
                                    <a href="{{ route('page.show', $page->slug) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                        {{ url('/') }}/{{ $page->slug }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-neutral-400">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium {{ $page->is_published ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500' }}">
                                        {{ $page->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-neutral-700 flex justify-end gap-x-2">
                    <a href="{{ route('admin.pages.show', $page) }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                        Cancel
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17,21 17,13 7,13 7,21" />
                            <polyline points="7,3 7,8 15,8" />
                        </svg>
                        Update Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tinymce@7/skins/ui/oxide/skin.min.css">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
        <script>
            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: true,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount', 'emoticons'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic forecolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | link image media | code fullscreen | help',
                content_style: 'body { font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; }',
                branding: false,
                promotion: false,
                skin: 'oxide',
                content_css: 'default',
                // Dark mode support
                skin_url: window.matchMedia('(prefers-color-scheme: dark)').matches ||
                    document.documentElement.classList.contains('dark') ?
                    'https://cdn.jsdelivr.net/npm/tinymce@7/skins/ui/oxide-dark' :
                    'https://cdn.jsdelivr.net/npm/tinymce@7/skins/ui/oxide',
                // Image upload configuration
                images_upload_handler: async function(blobInfo, progress) {
                    return new Promise((resolve, reject) => {
                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .content);

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', '/admin/upload-image', true);

                        xhr.upload.onprogress = function(e) {
                            progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = function() {
                            if (xhr.status === 403 || xhr.status === 404) {
                                reject({
                                    message: 'HTTP Error: ' + xhr.status,
                                    remove: true
                                });
                                return;
                            }

                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject('HTTP Error: ' + xhr.status);
                                return;
                            }

                            const json = JSON.parse(xhr.responseText);
                            if (!json || typeof json.location !== 'string') {
                                reject('Invalid JSON: ' + xhr.responseText);
                                return;
                            }

                            resolve(json.location);
                        };

                        xhr.onerror = function() {
                            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr
                                .status);
                        };

                        xhr.send(formData);
                    });
                },
                // Link configuration
                link_assume_external_targets: true,
                link_default_target: '_blank',
                // Table configuration
                table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
                // Responsive
                resize: true,
                // Auto-focus
                auto_focus: false,
                // Setup callback for theme changes
                setup: function(editor) {
                    // Watch for theme changes
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'class') {
                                const isDark = document.documentElement.classList.contains('dark');
                                editor.getBody().style.backgroundColor = isDark ? '#1e293b' :
                                    '#ffffff';
                                editor.getBody().style.color = isDark ? '#e2e8f0' : '#1f2937';
                            }
                        });
                    });
                    observer.observe(document.documentElement, {
                        attributes: true
                    });
                }
            });

            // Meta description character counter
            document.getElementById('meta_description').addEventListener('input', function() {
                const counter = document.getElementById('meta-counter');
                const length = this.value.length;
                counter.textContent = length + '/160';

                if (length > 160) {
                    counter.classList.add('text-red-500');
                    counter.classList.remove('text-gray-500');
                } else {
                    counter.classList.remove('text-red-500');
                    counter.classList.add('text-gray-500');
                }
            });
        </script>
    @endpush
@endsection
