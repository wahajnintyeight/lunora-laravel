<!-- ========== HEADER ========== -->
<header class="flex flex-col lg:flex-nowrap z-50 bg-white dark:bg-neutral-900 sticky top-0 shadow-sm">
    <!-- Topbar -->
    @include('partials.shop.topbar')
    
    <!-- Main Header -->
    <div class="max-w-[85rem] basis-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="w-full flex items-center justify-between gap-2 lg:gap-6">
            <!-- Mobile Menu Button -->
            <div class="lg:hidden">
                <button type="button" class="hs-collapse-toggle inline-flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-neutral-700 dark:text-white dark:hover:bg-white/10 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 size-9" data-hs-collapse="#mobile-menu" aria-controls="mobile-menu" aria-label="Toggle navigation">
                    <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6"/>
                        <line x1="3" x2="21" y1="12" y2="12"/>
                        <line x1="3" x2="21" y1="18" y2="18"/>
                    </svg>
                    <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 6-12 12"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
            
            <!-- Logo -->
            @include('partials.shop.logo')
            
            <!-- Desktop Navigation -->
            <div class="hidden lg:flex lg:items-center lg:gap-x-3 lg:flex-1">
                <!-- Catalog Dropdown -->
                @include('partials.shop.catalog-dropdown')
                
                <!-- Search Bar -->
                @include('partials.shop.search-bar')
            </div>
            
            <!-- User Actions -->
            @include('partials.shop.user-actions')
        </div>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hs-collapse hidden w-full overflow-hidden transition-all duration-300 lg:hidden">
            <div class="py-4 space-y-4">
                <!-- Mobile Search -->
                <div class="px-2">
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
                                class="py-3 ps-10 pe-4 block w-full border-gray-200 rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" 
                                placeholder="Search for jewelry..."
                                autocomplete="off"
                            >
                        </div>
                    </form>
                </div>
                
                <!-- Mobile Categories -->
                @include('partials.shop.mobile-categories')
                
                <!-- Mobile Account Links -->
                <div class="px-2 pt-4 border-t border-gray-200 dark:border-neutral-700">
                    @auth
                        <div class="space-y-2">
                            <a href="{{ route('user.profile') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                My Account
                            </a>
                            <a href="{{ route('user.orders') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                My Orders
                            </a>
                            <a href="{{ route('cart.index') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                                </svg>
                                Shopping Cart
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16,17 21,12 16,7"></polyline>
                                        <line x1="21" x2="9" y1="12" y2="12"></line>
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="space-y-2">
                            <a href="{{ route('login') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-200 dark:hover:bg-neutral-700">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                    <polyline points="10,17 15,12 10,7"></polyline>
                                    <line x1="15" x2="3" y1="12" y2="12"></line>
                                </svg>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm bg-emerald-600 text-white hover:bg-emerald-700">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                Create Account
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->