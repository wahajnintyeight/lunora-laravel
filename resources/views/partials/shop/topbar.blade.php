<!-- Topbar -->
<div class="bg-gray-100 dark:bg-neutral-800">
    <div class="max-w-[85rem] flex justify-between w-full mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-x-5">
            <button type="button" class="flex items-center gap-x-1 text-start font-medium text-xs text-gray-800 hover:text-gray-600 focus:outline-hidden focus:text-gray-600 dark:text-neutral-200 dark:hover:text-neutral-300 dark:focus:text-neutral-300" data-hs-overlay="#hs-pro-shmnlcm">
                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                    <circle cx="12" cy="10" r="3" />
                </svg>
                Specify the address
            </button>
        </div>

        <ul class="flex flex-wrap items-center gap-3">
            <li class="inline-flex items-center relative text-xs text-gray-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2.5 after:bg-gray-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-neutral-500 dark:after:bg-neutral-600">
                <button type="button" class="flex items-center gap-x-1.5 text-start text-xs text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" data-hs-overlay="#hs-pro-shmnrsm">
                    <img class="shrink-0 size-3.5 rounded-full" src="{{ asset('images/flags/us.png') }}" alt="English">
                    English · USD
                </button>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-gray-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2.5 after:bg-gray-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-neutral-500 dark:after:bg-neutral-600">
                <a class="text-xs text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" href="#">
                    Help
                </a>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-gray-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2.5 after:bg-gray-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-neutral-500 dark:after:bg-neutral-600">
                <a class="text-xs text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:text-neutral-400" href="#">
                    Mobile app
                </a>
            </li>
            <li class="hidden sm:inline-flex items-center relative text-xs text-gray-500 ps-3.5 first:ps-0 first:after:hidden after:absolute after:top-1/2 after:start-0 after:inline-block after:w-px after:h-2.5 after:bg-gray-400 after:rounded-full after:-translate-y-1/2 after:rotate-12 dark:text-neutral-500 dark:after:bg-neutral-600">
                <!-- Dark Mode Toggle -->
                @include('partials.shop.dark-mode-toggle')
            </li>
        </ul>
    </div>
</div>
<!-- End Topbar -->