<!-- Topbar -->
<div class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
    <div class="max-w-[85rem] flex justify-between w-full mx-auto py-2.5 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-x-5">
            <!-- improved button styling with better hover and focus states -->
            <button type="button" 
                class="group inline-flex items-center gap-x-1.5 text-xs font-medium text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:text-slate-400 dark:hover:text-slate-200 dark:focus:ring-offset-slate-900 transition-colors duration-150"
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
            <li class="inline-flex items-center relative text-xs text-slate-500 ps-2.5 sm:ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-slate-300 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-slate-400 dark:after:bg-slate-700">
                <button type="button" 
                    class="group inline-flex items-center gap-x-1 text-xs text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded focus:ring-offset-2 dark:text-slate-400 dark:hover:text-slate-200 dark:focus:ring-offset-slate-900 transition-colors duration-150 py-1 px-1.5"
                    data-hs-overlay="#hs-pro-shmnrsm"
                    aria-label="Language and currency settings">
                    <img class="shrink-0 size-3.5 rounded-full" src="{{ asset('images/flags/us.png') }}" alt="English">
                    <span class="hidden sm:inline">English · USD</span>
                    <span class="sm:hidden">USD</span>
                </button>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-slate-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-slate-300 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-slate-400 dark:after:bg-slate-700">
                <a class="text-xs text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded focus:ring-offset-2 dark:text-slate-400 dark:hover:text-slate-200 dark:focus:ring-offset-slate-900 transition-colors duration-150 py-1 px-1.5" 
                    href="#">Help</a>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-slate-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2 after:bg-slate-300 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-slate-400 dark:after:bg-slate-700">
                <a class="text-xs text-slate-600 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 rounded focus:ring-offset-2 dark:text-slate-400 dark:hover:text-slate-200 dark:focus:ring-offset-slate-900 transition-colors duration-150 py-1 px-1.5" 
                    href="#">Mobile app</a>
            </li>
            <li class="hidden sm:inline-flex items-center ps-3.5 first:ps-0">
                @include('partials.shop.dark-mode-toggle')
            </li>
        </ul>
    </div>
</div>
<!-- End Topbar -->
