<!-- User Actions -->
<div class="order-3 flex items-center gap-x-2">
    <!-- Wishlist -->
    <button type="button" class="relative inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-gray-800 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-200 dark:hover:text-emerald-500 dark:focus:text-emerald-500">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
        </svg>
        <span class="sr-only">Wishlist</span>
        @auth
            @if(auth()->user()->wishlistItems()->count() > 0)
                <span class="absolute -top-2 -end-2 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-emerald-500 text-white">
                    {{ auth()->user()->wishlistItems()->count() }}
                </span>
            @endif
        @endauth
    </button>

    <!-- Shopping Cart -->
    <button type="button" class="relative inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-gray-800 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-200 dark:hover:text-emerald-500 dark:focus:text-emerald-500" data-hs-overlay="#hs-pro-shmncrt">
        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="8" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
        </svg>
        <span class="sr-only">Cart</span>
        @auth
            @php
                $cartCount = auth()->user()->carts()->where('status', 'active')->sum('quantity');
            @endphp
            @if($cartCount > 0)
                <span class="absolute -top-2 -end-2 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-emerald-500 text-white">
                    {{ $cartCount }}
                </span>
            @endif
        @endauth
    </button>

    <!-- User Account -->
    @auth
        <div class="hs-dropdown relative inline-flex">
            <button id="hs-dropdown-account" type="button" class="hs-dropdown-toggle inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-gray-800 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-200 dark:hover:text-emerald-500 dark:focus:text-emerald-500" aria-haspopup="menu" aria-expanded="false" aria-label="Account">
                @if(auth()->user()->avatar_url)
                    <img class="shrink-0 size-6 rounded-full" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                @else
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                @endif
                <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                <svg class="hs-dropdown-open:rotate-180 shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"></path>
                </svg>
            </button>

            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-account">
                <div class="py-3 px-5 bg-gray-100 rounded-t-lg dark:bg-neutral-700">
                    <p class="text-sm text-gray-500 dark:text-neutral-400">Signed in as</p>
                    <p class="text-sm font-medium text-gray-800 dark:text-neutral-300">{{ auth()->user()->email }}</p>
                </div>
                <div class="p-2 space-y-0.5">
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="{{ route('profile.show') }}">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profile
                    </a>
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Orders
                    </a>
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700" href="#">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.29 1.51 4.04 3 5.5l7 7Z"></path>
                        </svg>
                        Wishlist
                    </a>
                </div>
                <div class="py-2 first:pt-0 last:pb-0">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16,17 21,12 16,7"></polyline>
                                <line x1="21" x2="9" y1="12" y2="12"></line>
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="flex items-center gap-x-2">
            <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-gray-800 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-200 dark:hover:text-emerald-500 dark:focus:text-emerald-500">
                Sign in
            </a>
            <a href="{{ route('register') }}" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                Sign up
            </a>
        </div>
    @endauth
</div>
<!-- End User Actions -->