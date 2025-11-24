<!-- Search Bar -->
<div class="flex-1 max-w-2xl">
    <form action="{{ route('search') }}" method="GET" class="relative">
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
                class="py-2.5 ps-10 pe-16 block w-full border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" 
                placeholder="Search for jewelry, rings, necklaces..."
                autocomplete="off"
            >
            <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-20 pe-1">
                <button type="submit" class="inline-flex shrink-0 justify-center items-center size-6 rounded-md text-gray-500 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 dark:text-neutral-500 dark:hover:text-emerald-500 dark:focus:text-emerald-500">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12l5 5L20 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>
<!-- End Search Bar -->