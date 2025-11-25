<div class="category-item border border-gray-200 rounded-lg p-4 bg-white dark:bg-neutral-800 dark:border-neutral-700" 
     data-category-id="{{ $category->id }}" 
     style="margin-left: {{ $level * 20 }}px;">
    
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-x-3">
            <!-- Drag Handle -->
            <div class="cursor-move text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-400">
                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="12" r="1"/>
                    <circle cx="9" cy="5" r="1"/>
                    <circle cx="9" cy="19" r="1"/>
                    <circle cx="15" cy="12" r="1"/>
                    <circle cx="15" cy="5" r="1"/>
                    <circle cx="15" cy="19" r="1"/>
                </svg>
            </div>

            <!-- Toggle Children Button -->
            @if($category->children->count() > 0)
                <button type="button" class="toggle-children text-gray-400 hover:text-gray-600 dark:text-neutral-500 dark:hover:text-neutral-400">
                    <svg class="size-4 transition-transform" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            @else
                <div class="size-4"></div>
            @endif

            <!-- Category Info -->
            <div class="flex items-center gap-x-3">
                <div>
                    <div class="flex items-center gap-x-2">
                        <span class="text-sm font-semibold text-gray-800 dark:text-neutral-200">
                            {{ $category->name }}
                        </span>
                        
                        <!-- Status Badge -->
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500' : 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <!-- Product Count -->
                        <span class="inline-flex items-center gap-x-1.5 py-1 px-2 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                            {{ $category->products_count }} products
                        </span>
                    </div>
                    
                    <div class="text-xs text-gray-500 dark:text-neutral-500">
                        {{ $category->slug }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-x-2">
            <div class="hs-dropdown relative inline-block">
                <button type="button" class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-lg text-gray-700 align-middle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1"/>
                        <circle cx="19" cy="12" r="1"/>
                        <circle cx="5" cy="12" r="1"/>
                    </svg>
                </button>
                
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700">
                    <div class="py-2 first:pt-0 last:pb-0">
                        <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('admin.categories.show', $category) }}">
                            View
                        </a>
                        <a class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="{{ route('admin.categories.edit', $category) }}">
                            Edit
                        </a>
                    </div>
                    <div class="py-2 first:pt-0 last:pb-0">
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 focus:ring-2 focus:ring-red-500 dark:text-red-500 dark:hover:bg-red-800/30">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Children -->
    @if($category->children->count() > 0)
        <div class="category-children mt-4 space-y-2">
            @foreach($category->children as $child)
                @include('admin.categories.partials.tree-item', ['category' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>