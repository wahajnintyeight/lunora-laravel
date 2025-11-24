<!-- ========== HEADER ========== -->
<header class="flex flex-col lg:flex-nowrap z-50 bg-white dark:bg-neutral-900">
    <!-- Topbar -->
    @include('partials.shop.topbar')
    
    <!-- Main Header -->
    <div class="max-w-[85rem] basis-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="w-full flex md:flex-nowrap md:items-center gap-2 lg:gap-6">
            <!-- Logo -->
            @include('partials.shop.logo')
            
            <!-- Search and Navigation -->
            <div class="md:w-full order-2 md:grow md:w-auto">
                <div class="relative flex basis-full items-center gap-x-1 md:gap-x-3">
                    <!-- Catalog Dropdown -->
                    @include('partials.shop.catalog-dropdown')
                    
                    <!-- Search Bar -->
                    @include('partials.shop.search-bar')
                    
                    <!-- User Actions -->
                    @include('partials.shop.user-actions')
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->