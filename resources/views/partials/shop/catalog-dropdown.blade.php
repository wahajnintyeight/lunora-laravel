<!-- Catalog Dropdown -->
<div class="hs-dropdown [--adaptive:none] [--auto-close:inside] md:inline-block">
    <!-- Dropdown Button -->
    <button id="hs-pro-shmnctdm" type="button" class="hs-dropdown-toggle relative py-[7px] sm:py-2 sm:py-2.5 px-3 flex items-center gap-x-1.5 text-sm text-start bg-emerald-600 border border-transparent text-white rounded-full hover:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden focus:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 dark:focus:bg-emerald-600" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
        <svg class="hs-dropdown-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="4" x2="20" y1="12" y2="12" />
            <line x1="4" x2="20" y1="6" y2="6" />
            <line x1="4" x2="20" y1="18" y2="18" />
        </svg>
        <svg class="hs-dropdown-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </svg>
        Catalog
    </button>
    <!-- End Dropdown Button -->

    <!-- Dropdown Menu -->
    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 opacity-0 w-full hidden z-20 top-full start-0 min-w-60 bg-white shadow-xl before:absolute before:-top-4 before:start-0 before:w-full before:h-5 dark:bg-neutral-900 dark:border-neutral-700" role="menu" aria-orientation="vertical" aria-labelledby="hs-pro-shmnctdm">
        <!-- Container -->
        <div class="max-w-[85rem] w-full mx-auto py-2 md:py-4 px-4 sm:px-6 lg:px-8">
            <!-- Mobile Category Select -->
            @include('partials.shop.mobile-category-select')
            
            <!-- Desktop Category Grid -->
            <div class="flex">
                <!-- Sidebar -->
                @include('partials.shop.category-sidebar')
                
                <!-- Content -->
                @include('partials.shop.category-content')
            </div>
        </div>
    </div>
</div>
<!-- End Catalog Dropdown -->