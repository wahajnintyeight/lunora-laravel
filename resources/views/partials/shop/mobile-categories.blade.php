<!-- Mobile Categories -->
<div class="px-2">
    <h3 class="text-base font-semibold text-gold-100 dark:text-gold-200 mb-4">Shop by Category</h3>
    <div class="space-y-1">
        <a 
            href="{{ route('products.index') }}" 
            class="mobile-menu-item flex items-center gap-x-3 py-3 px-3 rounded-lg text-base text-gold-200 hover:bg-[#f59e0b]-900/20 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 focus:bg-[#f59e0b]-900/20 focus:text-gold-100 dark:text-gold-300 dark:hover:bg-[#f59e0b]-800/30 dark:hover:text-gold-100 dark:focus:bg-[#f59e0b]-800/30 dark:focus:text-gold-100 transition-all duration-200"
        >
            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/>
                <path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/>
                <path d="M12 3v6"/>
            </svg>
            <span class="font-medium">All Products</span>
        </a>
        
        @php
            // Get categories for mobile menu - in a real app this would come from a service or controller
            $mobileCategories = [
                ['name' => 'Rings', 'slug' => 'rings', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'],
                ['name' => 'Necklaces', 'slug' => 'necklaces', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'],
                ['name' => 'Earrings', 'slug' => 'earrings', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'],
                ['name' => 'Bracelets', 'slug' => 'bracelets', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'],
                ['name' => 'Watches', 'slug' => 'watches', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'],
            ];
        @endphp
        
        @foreach($mobileCategories as $category)
            <a 
                href="{{ route('products.index', ['category' => $category['slug']]) }}" 
                class="mobile-menu-item flex items-center gap-x-3 py-3 px-3 rounded-lg text-base text-gold-200 hover:bg-[#f59e0b]-900/20 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 focus:bg-[#f59e0b]-900/20 focus:text-gold-100 dark:text-gold-300 dark:hover:bg-[#f59e0b]-800/30 dark:hover:text-gold-100 dark:focus:bg-[#f59e0b]-800/30 dark:focus:text-gold-100 transition-all duration-200"
            >
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M12 1v6m0 6v6"/>
                </svg>
                <span class="font-medium">{{ $category['name'] }}</span>
            </a>
        @endforeach
        
        <a 
            href="{{ route('categories.index') }}" 
            class="mobile-menu-item flex items-center gap-x-3 py-3 px-3 rounded-lg text-base text-gold-100 hover:bg-[#f59e0b]-900/20 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 focus:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30 dark:focus:bg-[#f59e0b]-800/30 transition-all duration-200"
        >
            <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3h18v18H3zM9 9h6v6H9z"/>
            </svg>
            <span class="font-medium">View All Categories</span>
        </a>
    </div>
</div>
<!-- End Mobile Categories -->