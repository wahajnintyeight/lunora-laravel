@extends('admin.layouts.app')

@section('title', 'Categories - Tree View')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            Categories - Tree View
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Hierarchical view of your product categories with drag-and-drop reordering.
        </p>
    </div>

    <div class="flex justify-end items-center gap-x-2">
        <a href="{{ route('admin.categories.index', ['view' => 'list']) }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12h18"/>
                <path d="M3 6h18"/>
                <path d="M3 18h18"/>
            </svg>
            List View
        </a>
        
        <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" href="{{ route('admin.categories.create') }}">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="M12 5v14"/>
            </svg>
            Add Category
        </a>
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

<!-- Category Tree -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-800 dark:border-neutral-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                Category Hierarchy
            </h2>
            <div class="text-sm text-gray-500 dark:text-neutral-500">
                Drag and drop to reorder categories
            </div>
        </div>
    </div>

    <div class="p-6">
        @if($categories->count() > 0)
            <div id="category-tree" class="space-y-2">
                @foreach($categories as $category)
                    @include('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0])
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-neutral-100">No categories found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-neutral-500">Get started by creating your first category.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add Category
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- End Category Tree -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryTree = document.getElementById('category-tree');
    
    if (categoryTree) {
        new Sortable(categoryTree, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                updateCategoryOrder();
            }
        });

        // Make nested lists sortable too
        const nestedLists = document.querySelectorAll('.category-children');
        nestedLists.forEach(list => {
            new Sortable(list, {
                group: 'categories',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    updateCategoryOrder();
                }
            });
        });
    }

    function updateCategoryOrder() {
        const categories = [];
        
        function processLevel(container, parentId = null) {
            const items = container.children;
            for (let i = 0; i < items.length; i++) {
                const item = items[i];
                const categoryId = item.dataset.categoryId;
                
                if (categoryId) {
                    categories.push({
                        id: parseInt(categoryId),
                        parent_id: parentId,
                        sort_order: i
                    });

                    // Process children
                    const childrenContainer = item.querySelector('.category-children');
                    if (childrenContainer) {
                        processLevel(childrenContainer, parseInt(categoryId));
                    }
                }
            }
        }

        processLevel(categoryTree);

        // Send update to server
        fetch('{{ route("admin.categories.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                categories: categories
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update category order:', data.message);
                // Optionally show user notification
            }
        })
        .catch(error => {
            console.error('Error updating category order:', error);
        });
    }

    // Toggle category children
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('toggle-children')) {
            e.preventDefault();
            const childrenContainer = e.target.closest('.category-item').querySelector('.category-children');
            const icon = e.target.querySelector('svg');
            
            if (childrenContainer) {
                childrenContainer.classList.toggle('hidden');
                icon.classList.toggle('rotate-90');
            }
        }
    });
});
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
}

.sortable-chosen {
    cursor: grabbing;
}

.sortable-drag {
    transform: rotate(5deg);
}

.category-item {
    transition: all 0.2s ease;
}

.category-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>
@endpush