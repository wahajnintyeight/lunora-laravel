<!-- ========== HEADER ========== -->
<header class="flex flex-col lg:flex-nowrap z-50 bg-[#450a0a] dark:bg-[#450a0a] sticky top-0 shadow-lg shadow-maroon-900/20">
    <!-- Topbar -->
    @include('partials.shop.topbar')

    <!-- Main Header -->
    <div class="max-w-[85rem] basis-full w-full mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="w-full flex items-center gap-3 lg:gap-6">
            <!-- Mobile Menu Button (visible only on mobile) -->
            <div class="lg:hidden flex items-center">
                <button type="button" id="mobile-menu-toggle"
                    class="touch-target inline-flex justify-center items-center gap-x-2 rounded-lg border border-gold-300 bg-[#f59e0b]-100 text-maroon-800 shadow-sm hover:bg-[#f59e0b]-200 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none dark:bg-[#f59e0b]-900/20 dark:border-gold-700 dark:text-gold-300 dark:hover:bg-[#f59e0b]-800/30 dark:focus:ring-gold-400 transition-all duration-200"
                    data-hs-collapse="#mobile-menu" aria-controls="mobile-menu" aria-label="Toggle navigation"
                    aria-expanded="false">
                    <svg class="mobile-menu-open:hidden shrink-0 size-4 transition-transform duration-200"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="3" x2="21" y1="6" y2="6" />
                        <line x1="3" x2="21" y1="12" y2="12" />
                        <line x1="3" x2="21" y1="18" y2="18" />
                    </svg>
                    <svg class="mobile-menu-open:block hidden shrink-0 size-4 transition-transform duration-200"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m18 6-12 12" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>

            <!-- Logo (always on the left) -->
            <div class="flex items-center flex-shrink-0">
                @include('partials.shop.logo')
            </div>

            <!-- Center Area: Catalog + Search (desktop only) -->
            <div class="hidden lg:flex lg:items-center lg:gap-x-3 lg:flex-1 justify-center">
                <!-- Catalog Dropdown -->
                @include('partials.shop.catalog-dropdown')

                <!-- Search Bar -->
                @include('partials.shop.search-bar')
            </div>

            <!-- User Actions (right aligned) -->
            <div class="ml-auto flex items-center">
                @include('partials.shop.user-actions')
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu"
            class="hidden w-full overflow-hidden transition-all duration-300 lg:hidden bg-[#450a0a] dark:bg-[#450a0a] border-t border-gold-300 dark:border-gold-700">
            <div class="py-4 space-y-4">
                <!-- Mobile Search -->
                <div class="px-2">
                    <form action="{{ route('search') }}" method="GET" class="relative" autocomplete="off">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                                <svg class="shrink-0 size-4 text-gold-300/80 dark:text-gold-400/80"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}"
                                class="form-input py-3 ps-10 pe-4 block w-full border-gold-300 rounded-lg text-base focus:border-gold-500 focus:ring-gold-500 disabled:opacity-50 disabled:pointer-events-none bg-maroon-700 text-gold-100 placeholder-gold-300 dark:bg-maroon-800 dark:border-gold-600 dark:text-gold-100 dark:placeholder-gold-300 dark:focus:ring-gold-400"
                                placeholder="Search for jewelry..." autocomplete="off" aria-label="Search products"
                                data-search-input="products">
                            <!-- Realtime search results (mobile) -->
                            <div class="absolute left-0 right-0 mt-1 hidden z-30" data-search-results="products"></div>
                        </div>
                    </form>
                </div>

                <!-- Mobile Categories -->
                @include('partials.shop.mobile-categories')

                <!-- Mobile Account Links -->
                <div class="px-2 pt-4 border-t border-gold-300/60 dark:border-gold-700/60">
                    @auth
                        <div class="mb-3 flex items-center gap-x-3 px-3">
                            @if (auth()->user()->avatar_url)
                                <img class="shrink-0 size-9 rounded-full ring-2 ring-gold-500/60"
                                    src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                            @else
                                <div
                                    class="shrink-0 size-9 rounded-full bg-maroon-700/80 flex items-center justify-center text-gold-200 text-sm font-semibold">
                                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-xs uppercase tracking-wide text-gold-300/80">Signed in as</p>
                                <p class="text-sm font-semibold text-gold-100 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <a href="{{ route('user.profile') }}"
                                class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gold-100 hover:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30"
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                My Account
                            </a>
                            <a href="{{ route('user.orders') }}"
                                class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gold-100 hover:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30"
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                </svg>
                                My Orders
                            </a>
                            <a href="{{ route('cart.index') }}"
                                class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gold-100 hover:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30"
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="8" cy="21" r="1"></circle>
                                    <circle cx="19" cy="21" r="1"></circle>
                                    <path
                                        d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12">
                                    </path>
                                </svg>
                                Shopping Cart
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gold-100 hover:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30"
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
                            <a href="{{ route('login') }}"
                                class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gold-100 hover:bg-[#f59e0b]-900/20 dark:text-gold-200 dark:hover:bg-[#f59e0b]-800/30"
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                    <polyline points="10,17 15,12 10,7"></polyline>
                                    <line x1="15" x2="3" y1="12" y2="12"></line>
                                </svg>
                                Sign In
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 font-semibold"
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
