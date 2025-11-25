@extends('admin.layouts.app')

@section('title', 'Product Variants - ' . $product->name)

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
                        <span class="text-gray-500 dark:text-neutral-400">Variants</span>
                    </div>
                </li>
            </ol>
        </nav>
        
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200 mt-2">
            Product Variants
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage variants for {{ $product->name }}
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.products.options', $product) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4Z"/>
            </svg>
            Manage Options
        </a>
        
        <button type="button" id="add-variant-btn" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Add Variant
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

<!-- Variants Table -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
            Variants ({{ $product->variants->count() }})
        </h2>
    </div>

    @if($product->variants->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">SKU</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Options</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Price</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Stock</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Status</th>
                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach($product->variants as $variant)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
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
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                <div class="flex justify-end items-center gap-x-2">
                                    <button type="button" class="edit-variant-btn py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" 
                            data-variant-id="{{ $variant->id }}"
                            data-variant-sku="{{ $variant->sku }}"
                            data-variant-price="{{ $variant->price_pkr ? $variant->price_pkr / 100 : '' }}"
                            data-variant-compare-price="{{ $variant->compare_at_price_pkr ? $variant->compare_at_price_pkr / 100 : '' }}"
                            data-variant-stock="{{ $variant->stock }}"
                            data-variant-weight="{{ $variant->weight }}"
                            data-variant-options="{{ json_encode($variant->options_json) }}"
                            data-variant-active="{{ $variant->is_active ? '1' : '0' }}">
                                        Edit
                                    </button>
                                    
                                    <form method="POST" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" onsubmit="return confirm('Are you sure you want to delete this variant?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="py-1.5 px-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-red-200 bg-white text-red-600 shadow-sm hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:bg-neutral-800 dark:border-red-700 dark:text-red-500 dark:hover:bg-red-800/30 dark:focus:bg-red-800/30">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No variants found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Get started by creating your first variant.</p>
            <div class="mt-6">
                <button type="button" id="add-variant-btn-empty" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add Variant
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Variant Modal -->
<div id="variant-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <h3 id="modal-title" class="block text-2xl font-bold text-gray-800 dark:text-neutral-200">Add Variant</h3>
                </div>

                <div class="mt-5">
                    <form id="variant-form" method="POST">
                        @csrf
                        <div id="method-field"></div>
                        
                        <div class="grid gap-y-4">
                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-sm font-medium mb-2 dark:text-white">SKU</label>
                                <input type="text" id="sku" name="sku" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                            </div>

                            <!-- Price -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Price (PKR)</label>
                                    <input type="number" id="price_pkr" name="price_pkr" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Leave empty to use base price">
                                </div>
                                
                                <div>
                                    <label for="compare_at_price_pkr" class="block text-sm font-medium mb-2 dark:text-white">Compare Price (PKR)</label>
                                    <input type="number" id="compare_at_price_pkr" name="compare_at_price_pkr" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>

                            <!-- Stock and Weight -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="stock" class="block text-sm font-medium mb-2 dark:text-white">Stock</label>
                                    <input type="number" id="stock" name="stock" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" required>
                                </div>
                                
                                <div>
                                    <label for="weight" class="block text-sm font-medium mb-2 dark:text-white">Weight (g)</label>
                                    <input type="number" id="weight" name="weight" step="0.01" min="0" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                </div>
                            </div>

                            <!-- Options -->
                            <div id="options-container">
                                <label class="block text-sm font-medium mb-2 dark:text-white">Options</label>
                                @foreach($product->options as $option)
                                    <div class="mb-3">
                                        <label for="option_{{ $option->id }}" class="block text-xs font-medium mb-1 text-gray-600 dark:text-neutral-400">{{ $option->name }}</label>
                                        <select name="options[{{ $option->name }}]" id="option_{{ $option->id }}" class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400" required>
                                            <option value="">Select {{ $option->name }}</option>
                                            @foreach($option->values as $value)
                                                <option value="{{ $value->value }}">{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Status -->
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" checked>
                                <label for="is_active" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Active</label>
                            </div>

                            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#variant-modal">
                                    Cancel
                                </button>
                                <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                                    Save Variant
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
    const addVariantBtns = document.querySelectorAll('#add-variant-btn, #add-variant-btn-empty');
    const editVariantBtns = document.querySelectorAll('.edit-variant-btn');
    const variantModal = document.getElementById('variant-modal');
    const variantForm = document.getElementById('variant-form');
    const modalTitle = document.getElementById('modal-title');
    const methodField = document.getElementById('method-field');

    // Add variant
    addVariantBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            modalTitle.textContent = 'Add Variant';
            variantForm.action = '{{ route("admin.products.variants.store", $product) }}';
            methodField.innerHTML = '';
            variantForm.reset();
            document.getElementById('is_active').checked = true;
            
            // Show modal
            HSOverlay.open(variantModal);
        });
    });

    // Edit variant
    editVariantBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            const sku = this.dataset.variantSku;
            const price = this.dataset.variantPrice;
            const comparePrice = this.dataset.variantComparePrice;
            const stock = this.dataset.variantStock;
            const weight = this.dataset.variantWeight;
            const options = JSON.parse(this.dataset.variantOptions || '{}');
            const isActive = this.dataset.variantActive === '1';

            modalTitle.textContent = 'Edit Variant';
            variantForm.action = `{{ route("admin.products.variants.update", [$product, "__VARIANT_ID__"]) }}`.replace('__VARIANT_ID__', variantId);
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            // Fill form
            document.getElementById('sku').value = sku;
            document.getElementById('price_pkr').value = price;
            document.getElementById('compare_at_price_pkr').value = comparePrice;
            document.getElementById('stock').value = stock;
            document.getElementById('weight').value = weight;
            document.getElementById('is_active').checked = isActive;

            // Fill options
            Object.keys(options).forEach(optionName => {
                const select = document.querySelector(`select[name="options[${optionName}]"]`);
                if (select) {
                    select.value = options[optionName];
                }
            });

            // Show modal
            HSOverlay.open(variantModal);
        });
    });
});
</script>
@endpush