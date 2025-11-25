<!-- Search Bar -->
<div class="flex-1 max-w-2xl">
    <form action="{{ route('search') }}" method="GET" class="relative" autocomplete="off">
        <div class="relative">

            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                <svg class="shrink-0 size-4 text-gray-400 dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </div>
            <input 
                type="text" 
                name="q" 
                value="{{ request('q') }}"
                class="form-input py-3 ps-10 pe-16 block w-full border-gray-200 rounded-lg text-base sm:text-sm focus:border-emerald-500 focus:ring-emerald-500 focus:ring-2 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 transition-all duration-200" 
                placeholder="Search for jewelry, rings, necklaces..."
                autocomplete="off"
                aria-label="Search products"
                data-search-input="products"
            >

            <div class="absolute inset-y-0 end-0 flex items-center z-20 pe-1">

                <button 
                    type="submit" 
                    class="touch-target inline-flex shrink-0 justify-center items-center size-8 rounded-md text-gray-500 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:text-neutral-500 dark:hover:text-emerald-500 dark:focus:text-emerald-500 transition-colors duration-200"
                    aria-label="Search"
                >
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </div>
            <!-- Realtime search results -->
            <div 
                class="absolute left-0 right-0 mt-1 hidden z-30" 
                data-search-results="products"
            ></div>
        </div>
    </form>
</div>

<!-- End Search Bar -->