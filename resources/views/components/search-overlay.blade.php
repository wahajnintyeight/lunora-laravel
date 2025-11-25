<!-- Search Overlay Component -->
<div x-data="{ open: false, query: '' }" x-cloak>
    <!-- Toggle button (use any icon/button you like) -->
    <button @click="open = true" type="button"
        class="touch-target inline-flex items-center justify-center gap-x-1.5 rounded-full border border-transparent text-[#f59e0b] hover:text-white hover:bg-[#f59e0b] focus:outline-none focus:ring-2 focus:ring-[#f59e0b] focus:ring-offset-2 transition-colors duration-150 p-2"
        aria-label="Open search" :aria-expanded="open.toString()">
        <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
    </button>

    <!-- Overlay -->
    <div x-show="open" x-transition.opacity x-transition.duration.200ms
        class="fixed inset-0 z-[90] bg-black/40 backdrop-blur-sm flex items-start justify-center pt-24">
        <!-- Click outside closes -->
        <div @click.away="open = false" class="w-full max-w-xl mx-auto px-4">
            <!-- Search form -->
            <form action="{{ route('products.index') }}" method="GET" class="relative" @keydown.window.escape="open=false">
                <input x-model="query" type="text" name="search"
                    placeholder="Search for jewelry, rings, necklaces..."
                    class="w-full block bg-white border-2 border-gold-500 rounded-xl py-4 pl-12 pr-16 text-base focus:ring-2 focus:ring-gold-500 focus:border-gold-500 placeholder-gray-400 shadow-lg"
                    autocomplete="off" autofocus>
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </div>
                <button type="button" @click="open=false"
                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </form>
            <!-- Suggested products -->
            <div class="bg-white rounded-b-lg shadow-lg mt-2 max-h-80 overflow-auto" x-show="open">
                @php
                    $suggestedProducts = \App\Models\Product::query()->latest()->limit(8)->get();
                @endphp
                <ul class="divide-y divide-gray-200">
                    @foreach ($suggestedProducts as $product)
                        <li>
                            <a href="{{ route('products.show', $product) }}"
                                class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50">
                                <img src="{{ $product->image_url ?? '/images/placeholder.jpg' }}"
                                    alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded">
                                <span class="text-sm font-medium text-gray-700 truncate">{{ $product->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
