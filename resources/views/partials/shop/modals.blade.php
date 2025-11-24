<!-- Modals and Overlays -->

<!-- Location Modal -->
<div id="hs-pro-shmnlcm" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmnlcm-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="hs-pro-shmnlcm-label" class="font-bold text-gray-800 dark:text-white">
                    Select Location
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-pro-shmnlcm">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800 dark:text-neutral-400">
                    Please select your location to see relevant shipping options and delivery times.
                </p>
                <!-- Location selection form would go here -->
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-overlay="#hs-pro-shmnlcm">
                    Cancel
                </button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-emerald-700">
                    Save Location
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Language/Currency Modal -->
<div id="hs-pro-shmnrsm" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmnrsm-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                <h3 id="hs-pro-shmnrsm-label" class="font-bold text-gray-800 dark:text-white">
                    Language & Currency
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-pro-shmnrsm">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gray-800 dark:text-neutral-400">
                    Select your preferred language and currency.
                </p>
                <!-- Language and currency selection would go here -->
            </div>
        </div>
    </div>
</div>

<!-- Shopping Cart Sidebar -->
<div id="hs-pro-shmncrt" class="hs-overlay hs-overlay-open:translate-x-0 hidden translate-x-full fixed top-0 end-0 transition-all duration-300 transform h-full max-w-xs w-full z-[80] bg-white border-s dark:bg-neutral-800 dark:border-neutral-700" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmncrt-label">
    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
        <h3 id="hs-pro-shmncrt-label" class="font-bold text-gray-800 dark:text-white">
            Shopping Cart
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-pro-shmncrt">
            <span class="sr-only">Close</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
    </div>
    <div class="p-4 overflow-y-auto">
        @auth
            @php
                $cartItems = auth()->user()->carts()->where('status', 'active')->with('product')->get();
            @endphp
            @if($cartItems->count() > 0)
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-x-3">
                            <img class="size-16 rounded-lg object-cover" src="{{ $item->product->image_url ?? '/images/placeholder.jpg' }}" alt="{{ $item->product->name }}">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-neutral-400">Qty: {{ $item->quantity }}</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t dark:border-neutral-700">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">Total:</span>
                        <span class="text-lg font-bold text-gray-800 dark:text-neutral-200">
                            ${{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}
                        </span>
                    </div>
                    <button type="button" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700">
                        Checkout
                    </button>
                </div>
            @else
                <p class="text-gray-500 dark:text-neutral-400">Your cart is empty</p>
            @endif
        @else
            <p class="text-gray-500 dark:text-neutral-400">Please sign in to view your cart</p>
        @endauth
    </div>
</div>