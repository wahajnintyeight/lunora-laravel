<!-- Topbar -->
<div class="bg-[#450a0a] dark:bg-[#450a0a] border-b border-gold-600 dark:border-gold-700">
    <div class="max-w-full flex justify-between items-center w-full py-2.5 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-x-5">
            <!-- improved button styling with better hover and focus states -->
            <button type="button" 
                class="group inline-flex items-center gap-x-1.5 text-xs font-medium text-gold-200 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 dark:text-gold-300 dark:hover:text-gold-100 dark:focus:ring-offset-maroon-950 transition-colors duration-150"
                data-hs-overlay="#hs-pro-shmnlcm"
                aria-label="Specify delivery address">
                <svg class="shrink-0 size-3.5 group-hover:scale-110 transition-transform duration-150" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                <span class="hidden sm:inline">Delivery Address</span>
                <span class="sm:hidden">Location</span>
            </button>
        </div>

        <!-- improved topbar links with better spacing and visual hierarchy -->
        <ul class="flex flex-wrap items-center gap-2 sm:gap-4">
            <li class="inline-flex items-center relative text-xs text-gold-300 ps-2.5 sm:ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-[#f59e0b]-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-gold-400 dark:after:bg-[#f59e0b]">
                <button type="button" 
                    class="group inline-flex items-center gap-x-1 text-xs text-gold-200 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 rounded focus:ring-offset-2 dark:text-gold-300 dark:hover:text-gold-100 dark:focus:ring-offset-maroon-950 transition-colors duration-150 py-1 px-1.5"
                    data-hs-overlay="#hs-pro-shmnrsm"
                    aria-label="Language and currency settings">
                    <img class="shrink-0 size-3.5 rounded-full" src="{{ asset('images/flags/us.png') }}" alt="English">
                    <span class="hidden sm:inline">English · USD</span>
                    <span class="sm:hidden">USD</span>
                </button>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-gold-300 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-[#f59e0b]-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-gold-400 dark:after:bg-[#f59e0b]">
                <a class="text-xs text-gold-200 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 rounded focus:ring-offset-2 dark:text-gold-300 dark:hover:text-gold-100 dark:focus:ring-offset-maroon-950 transition-colors duration-150 py-1 px-1.5" 
                    href="#">Help</a>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-gold-300 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-[#f59e0b]-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-gold-400 dark:after:bg-[#f59e0b]">
                <a class="text-xs text-gold-200 hover:text-gold-100 focus:outline-none focus:ring-2 focus:ring-gold-500 rounded focus:ring-offset-2 dark:text-gold-300 dark:hover:text-gold-100 dark:focus:ring-offset-maroon-950 transition-colors duration-150 py-1 px-1.5" 
                    href="#">Mobile app</a>
            </li>
            <li class="hidden sm:inline-flex items-center ps-3.5 first:ps-0">
                @include('partials.shop.dark-mode-toggle')
            </li>
        </ul>

        <div class="hidden sm:flex items-center gap-x-3">
            @auth
                <div class="flex items-center gap-x-2">
                    @if (auth()->user()->getAvatarUrl())
                        <img class="shrink-0 size-8 rounded-full ring-2 ring-gold-500/70"
                            src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div
                            class="shrink-0 size-8 rounded-full bg-maroon-700/80 flex items-center justify-center text-gold-200 text-xs font-semibold ring-2 ring-gold-500/70">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden md:block min-w-0">
                        <p class="text-[10px] uppercase tracking-wide text-gold-300/80">Signed in</p>
                        <p class="text-xs font-medium text-gold-100 truncate max-w-[8rem]">
                            {{ auth()->user()->name }}
                        </p>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-x-2">
                    <a href="{{ route('login') }}"
                        class="text-xs font-medium text-gold-100 hover:text-gold-50">
                        Sign in
                    </a>
                    <span class="text-gold-400/60 text-[10px]">·</span>
                    <a href="{{ route('register') }}"
                        class="text-xs font-semibold text-[#f59e0b] hover:text-[#fbbf24]">
                        Join
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
<!-- End Topbar -->
