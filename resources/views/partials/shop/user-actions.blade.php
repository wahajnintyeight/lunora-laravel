<!-- User Actions -->
<div class="flex items-center gap-x-1 sm:gap-x-3">
    <!-- Wishlist Button -->
    <button type="button"
        class="group hidden sm:inline-flex touch-target relative justify-center items-center text-sm font-medium rounded-lg border border-transparent text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:text-emerald-600 focus:bg-emerald-50 disabled:opacity-50 disabled:pointer-events-none dark:text-slate-400 dark:hover:text-emerald-400 dark:hover:bg-emerald-950/50 dark:focus:text-emerald-400 dark:focus:ring-emerald-600 dark:focus:ring-offset-slate-900 dark:focus:bg-emerald-950/50 transition-all duration-200 py-2 px-2.5"
        aria-label="Wishlist">
        <svg class="shrink-0 size-5 group-hover:scale-110 transition-transform duration-200"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path
                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z">
            </path>
        </svg>
        @auth
            @if (auth()->user()->wishlistItems()->count() > 0)
                <!-- improved badge styling -->
                <span
                    class="absolute -top-1.5 -end-1.5 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-semibold bg-emerald-500 text-white min-w-[1.25rem] h-5 justify-center shadow-md">
                    {{ auth()->user()->wishlistItems()->count() }}
                </span>
            @endif
        @endauth
    </button>

    <!-- Shopping Cart Button -->
    <a href="{{ route('cart.index') }}"
        class="group touch-target relative inline-flex justify-center items-center text-sm font-medium rounded-lg border border-transparent text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:text-emerald-600 focus:bg-emerald-50 disabled:opacity-50 disabled:pointer-events-none dark:text-slate-400 dark:hover:text-emerald-400 dark:hover:bg-emerald-950/50 dark:focus:text-emerald-400 dark:focus:ring-emerald-600 dark:focus:ring-offset-slate-900 dark:focus:bg-emerald-950/50 transition-all duration-200 py-2 px-2.5"
        aria-label="Shopping cart">
        <svg class="shrink-0 size-5 sm:size-5 group-hover:scale-110 transition-transform duration-200"
            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
        </svg>
        @php
            $cartService = app(\App\Services\CartService::class);
            $cart = $cartService->getOrCreateCart(auth()->user(), session()->getId());
            $cartCount = $cartService->getItemCount($cart);
        @endphp
        @if ($cartCount > 0)
            <!-- improved cart badge styling -->
            <span
                class="absolute -top-1.5 -end-1.5 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-semibold bg-emerald-500 text-white min-w-[1.25rem] h-5 justify-center shadow-md cart-count">
                {{ $cartCount }}
            </span>
        @endif
    </a>

    <!-- User Account Dropdown -->
    @auth
        <div class="hs-dropdown relative inline-flex [&_.hs-dropdown-menu]:absolute">
            <!-- improved button with better focus and hover states -->
            <button id="hs-dropdown-account" type="button"
                class="hs-dropdown-toggle group touch-target inline-flex justify-center items-center text-sm font-medium rounded-lg border border-transparent text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:text-slate-900 focus:bg-slate-100 disabled:opacity-50 disabled:pointer-events-none dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-800 dark:focus:text-slate-200 dark:focus:ring-emerald-600 dark:focus:ring-offset-slate-900 dark:focus:bg-slate-800 transition-all duration-200 py-2 px-2.5"
                aria-haspopup="menu" aria-expanded="false" aria-label="Account menu">
                @if (auth()->user()->avatar_url)
                    <img class="shrink-0 size-6 rounded-full ring-2 ring-transparent group-hover:ring-emerald-500 transition-all duration-200"
                        src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                @else
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                @endif
                <span class="hidden lg:block font-medium">{{ auth()->user()->name }}</span>
                <svg class="hs-dropdown-open:rotate-180 shrink-0 size-3.5 hidden sm:block group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-all duration-200"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </button>

            <!-- improved dropdown menu styling with better shadows and animations -->
            <div id="hs-dropdown-account-menu"
                class="hs-dropdown-menu transition-[opacity,margin] duration-300 hs-dropdown-open:opacity-100 opacity-0 hidden min-w-64 bg-white shadow-lg rounded-xl p-0 dark:bg-slate-800 dark:border dark:border-slate-700 z-50 divide-y divide-slate-200 dark:divide-slate-700 absolute right-0 top-full mt-2"
                role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-account">
                <div class="py-3 px-5 bg-slate-50 rounded-t-xl dark:bg-slate-700/50">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium uppercase tracking-wide">Signed in as
                    </p>
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-200 mt-0.5 truncate">
                        {{ auth()->user()->email }}</p>
                </div>
                <div class="p-3 space-y-1">
                    <a class="group flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 dark:focus:bg-slate-700 transition-all duration-150"
                        href="{{ route('user.profile') }}">
                        <svg class="shrink-0 size-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-600 dark:group-hover:text-emerald-400 transition-colors duration-150"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Profile</span>
                    </a>
                    <a class="group flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 dark:focus:bg-slate-700 transition-all duration-150"
                        href="{{ route('user.orders') }}">
                        <svg class="shrink-0 size-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-600 dark:group-hover:text-emerald-400 transition-colors duration-150"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                        </svg>
                        <span>Orders</span>
                    </a>
                    <a class="group flex items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-slate-700 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:bg-slate-700 dark:focus:bg-slate-700 transition-all duration-150 sm:hidden"
                        href="#">
                        <svg class="shrink-0 size-4 text-slate-500 group-hover:text-emerald-600 dark:text-slate-600 dark:group-hover:text-emerald-400 transition-colors duration-150"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z">
                            </path>
                        </svg>
                        <span>Wishlist</span>
                    </a>
                </div>
                <div class="p-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="group flex w-full items-center gap-x-3 py-2.5 px-3 rounded-lg text-sm text-slate-700 hover:text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 dark:text-slate-400 dark:hover:text-red-400 dark:hover:bg-red-950/30 dark:focus:bg-red-950/30 transition-all duration-150">
                            <svg class="shrink-0 size-4 text-slate-500 group-hover:text-red-600 dark:text-slate-600 dark:group-hover:text-red-400 transition-colors duration-150"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" x2="9" y1="12" y2="12"></line>
                            </svg>
                            <span>Sign out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center gap-x-1 sm:gap-x-2">
            <!-- improved sign-in button styling -->
            <a href="{{ route('login') }}"
                class="group hidden sm:inline-flex touch-target justify-center items-center text-sm font-medium rounded-lg border border-slate-300 text-slate-700 hover:text-slate-900 hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:text-slate-900 focus:bg-slate-50 dark:border-slate-600 dark:text-slate-400 dark:hover:text-slate-200 dark:hover:border-slate-500 dark:hover:bg-slate-800 dark:focus:ring-emerald-600 dark:focus:ring-offset-slate-900 dark:focus:text-slate-200 dark:focus:bg-slate-800 transition-all duration-200 py-2 px-3.5">
                Sign in
            </a>
            <!-- improved sign-up button with gradient and better hover -->
            <a href="{{ route('register') }}"
                class="group touch-target py-2.5 px-3 sm:px-4 inline-flex items-center justify-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:ring-emerald-600 dark:focus:ring-offset-slate-900 dark:hover:bg-emerald-500 transition-all duration-200 shadow-md hover:shadow-lg">
                <span class="hidden sm:block">Create Account</span>
                <span class="sm:hidden">Sign up</span>
            </a>
        </div>
    @endauth
</div>
<!-- End User Actions -->