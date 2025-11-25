@extends('admin.layouts.app')

@section('title', 'Product Options - ' . $product->name)

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
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('admin.products.show', $product) }}" class="text-gray-700 hover:text-blue-600 dark:text-neutral-300 dark:hover:text-blue-500">
                            {{ $product->name }}
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="text-gray-500 dark:text-neutral-400">Options</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Product Options
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage options for {{ $product->name }}
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.products.variants', $product) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Manage Variants ({{ $product->variants->count() }})
        </a>
        
        <button type="button" id="add-option-btn" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Add Option
        </button>
    </div>
</div>
<!-- End Page Header -->

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-sm text-green-800 rounded-lg p-4 mb-5 dark:bg-green-800/10 dark:border-green-900 dark:text-green-500">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border border-red-200 text-sm text-red-800 rounded-lg p-4 mb-5 dark:bg-red-800/10 dark:border-red-900 dark:text-red-500">
        {{ session('error') }}
    </div>
@endif

<!-- Product Info -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700 mb-5">
    <div class="px-6 py-4">
        <div class="flex items-center gap-x-4">
            @if($product->images->first())
                <img class="size-16 rounded-lg" src="{{ Storage::url($product->images->first()->image_path) }}" alt="{{ $product->name }}">
            @else
                <div class="size-16 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="size-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-neutral-500">{{ $product->sku }}</p>
                <p class="text-sm text-gray-600 dark:text-neutral-400">Base Price: PKR {{ number_format($product->price_pkr / 100, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Options List -->
<div class="space-y-4">
    @forelse($product->options as $option)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">{{ $option->name }}</h3>
                        <div class="flex items-center gap-x-4 mt-1">
                            <span class="text-sm text-gray-500 dark:text-neutral-500">Type: {{ $option->type_display }}</span>
                            @if($option->is_required)
                                <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500">
                                    Required
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-x-2">
                        <form method="POST" action="{{ route('admin.products.options.destroy', [$product, $option]) }}" onsubmit="return confirm('Are you sure you want to delete this option? This will also delete all its values.')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500 dark:hover:bg-red-800/30 dark:focus:bg-red-800/30">
                                Delete Option
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if(in_array($option->type, ['select', 'radio', 'checkbox']) && $option->values->count() > 0)
                <div class="px-6 py-4">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-neutral-200 mb-3">Values</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($option->values as $value)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg dark:border-neutral-700">
                                <div>
                                    <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $value->value }}</span>
                                    @if($value->price_adjustment_pkr != 0)
                                        <span class="block text-xs text-gray-500 dark:text-neutral-500">
                                            {{ $value->formatted_price_adjustment }}
                                        </span>
                                    @endif
                                    @if($value->is_default)
                                        <span class="inline-flex items-center gap-x-1.5 py-0.5 px-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500 mt-1">
                                            Default
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No options found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Get started by creating your first option.</p>
                <div class="mt-6">
                    <button type="button" id="add-option-btn-empty" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add Option
                    </button>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Option Modal -->
<div id="option-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-2xl sm:w-full m-3 sm:mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h3 class="block text-2xl font-bold text-gray-800 dark:text-neutral-200">Add Option</h3>
                </div>

                <div class="mt-5">
                    <form id="option-form" method="POST" action="{{ route('admin.products.options.store', $product) }}">
                        @csrf
                        
                        <div class="grid gap-y-4">
                            <!-- Option Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Option Name</label>
                                <input type="text" id="name" name="name" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="e.g., Size, Color, Material" required>
                            </div>

                            <!-- Option Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium mb-2 dark:text-white">Option Type</label>
                                <select id="type" name="type" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:ring-neutral-600" required>
                                    <option value="">Select type</option>
                                    <option value="select">Dropdown</option>
                                    <option value="radio">Radio Buttons</option>
                                    <option value="checkbox">Checkboxes</option>
                                    <option value="text">Text Input</option>
                                    <option value="textarea">Text Area</option>
                                </select>
                            </div>

                            <!-- Required -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_required" name="is_required" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                                <label for="is_required" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Required option</label>
                            </div>

                            <!-- Option Values -->
                            <div id="values-container" style="display: none;">
                                <label class="block text-sm font-medium mb-2 dark:text-white">Option Values</label>
                                <div id="values-list" class="space-y-3">
                                    <!-- Values will be added dynamically -->
                                </div>
                                <button type="button" id="add-value-btn" class="mt-2 py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"/>
                        <path d="M12 5v14"/>
                    </svg>
                    Add Value
                </button>
                            </div>

                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#option-modal">
                                    Cancel
                                </button>
                                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                    Save Option
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addOptionBtns = document.querySelectorAll('#add-option-btn, #add-option-btn-empty');
    const optionModal = document.getElementById('option-modal');
    const typeSelect = document.getElementById('type');
    const valuesContainer = document.getElementById('values-container');
    const valuesList = document.getElementById('values-list');
    const addValueBtn = document.getElementById('add-value-btn');
    let valueIndex = 0;

    // Add option
    addOptionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('option-form').reset();
            valuesList.innerHTML = '';
            valueIndex = 0;
            valuesContainer.style.display = 'none';
            HSOverlay.open(optionModal);
        });
    });

    // Show/hide values container based on type
    typeSelect.addEventListener('change', function() {
        const needsValues = ['select', 'radio', 'checkbox'].includes(this.value);
        valuesContainer.style.display = needsValues ? 'block' : 'none';
        
        if (needsValues && valuesList.children.length === 0) {
            addValue();
        }
    });

    // Add value
    addValueBtn.addEventListener('click', addValue);

    function addValue() {
        const valueDiv = document.createElement('div');
        valueDiv.className = 'flex items-center gap-x-3 p-3 border border-gray-200 rounded-lg dark:border-neutral-700';
        valueDiv.innerHTML = `
            <div class="flex-1">
                <input type="text" name="values[${valueIndex}][value]" placeholder="Value name" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
            </div>
            <div class="w-32">
                <input type="number" name="values[${valueIndex}][price_adjustment_pkr]" step="0.01" placeholder="Price adj." class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="values[${valueIndex}][is_default]" value="1" class="shrink-0 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700">
                <label class="text-xs text-gray-500 ms-2 dark:text-neutral-400">Default</label>
            </div>
            <button type="button" class="remove-value-btn py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500">
                Remove
            </button>
        `;

        valuesList.appendChild(valueDiv);
        valueIndex++;

        // Add remove functionality
        valueDiv.querySelector('.remove-value-btn').addEventListener('click', function() {
            valueDiv.remove();
        });
    }
});
</script>
@endpush