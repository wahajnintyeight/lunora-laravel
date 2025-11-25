<!-- Modals and Overlays -->

<!-- Location Modal -->
<div id="hs-pro-shmnlcm" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmnlcm-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-[#450a0a] border border-gold-600 shadow-sm rounded-xl pointer-events-auto dark:bg-[#450a0a] dark:border-gold-700 dark:shadow-gold-900/20">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gold-600 dark:border-gold-700">
                <h3 id="hs-pro-shmnlcm-label" class="font-bold text-gold-100 dark:text-gold-200">
                    Select Location
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 focus:outline-none focus:bg-[#f59e0b]-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-[#f59e0b]-700 dark:hover:bg-[#f59e0b] dark:text-maroon-100 dark:focus:bg-[#f59e0b]" aria-label="Close" data-hs-overlay="#hs-pro-shmnlcm">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gold-200 dark:text-gold-300">
                    Please select your location to see relevant shipping options and delivery times.
                </p>
                <!-- Location selection form would go here -->
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t border-gold-600 dark:border-gold-700">
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gold-300 bg-[#450a0a] text-gold-200 shadow-sm hover:bg-[#450a0a] disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-[#450a0a] dark:bg-[#450a0a] dark:border-gold-600 dark:text-gold-300 dark:hover:bg-[#450a0a] dark:focus:bg-[#450a0a]" data-hs-overlay="#hs-pro-shmnlcm">
                    Cancel
                </button>
                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-[#f59e0b]-500">
                    Save Location
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Language/Currency Modal -->
<div id="hs-pro-shmnrsm" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmnrsm-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="flex flex-col bg-[#450a0a] border border-gold-600 shadow-sm rounded-xl pointer-events-auto dark:bg-[#450a0a] dark:border-gold-700 dark:shadow-gold-900/20">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gold-600 dark:border-gold-700">
                <h3 id="hs-pro-shmnrsm-label" class="font-bold text-gold-100 dark:text-gold-200">
                    Language & Currency
                </h3>
                <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 focus:outline-none focus:bg-[#f59e0b]-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-[#f59e0b]-700 dark:hover:bg-[#f59e0b] dark:text-maroon-100 dark:focus:bg-[#f59e0b]" aria-label="Close" data-hs-overlay="#hs-pro-shmnrsm">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4 overflow-y-auto">
                <p class="text-gold-200 dark:text-gold-300">
                    Select your preferred language and currency.
                </p>
                <!-- Language and currency selection would go here -->
            </div>
        </div>
    </div>
</div>

<!-- Shopping Cart Sidebar -->
<div id="hs-pro-shmncrt" class="hs-overlay hs-overlay-open:translate-x-0 hidden translate-x-full fixed top-0 end-0 transition-all duration-300 transform h-full max-w-xs w-full z-[80] bg-[#450a0a] border-s border-gold-600 dark:bg-[#450a0a] dark:border-gold-700" role="dialog" tabindex="-1" aria-labelledby="hs-pro-shmncrt-label">
    <div class="flex justify-between items-center py-3 px-4 border-b border-gold-600 dark:border-gold-700">
        <h3 id="hs-pro-shmncrt-label" class="font-bold text-gold-100 dark:text-gold-200">
            Shopping Cart
        </h3>
        <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 focus:outline-none focus:bg-[#f59e0b]-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-[#f59e0b]-700 dark:hover:bg-[#f59e0b] dark:text-maroon-100 dark:focus:bg-[#f59e0b]" aria-label="Close" data-hs-overlay="#hs-pro-shmncrt">
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
                $cartService = app(\App\Services\CartService::class);
                $cart = $cartService->getOrCreateCart(auth()->user(), session()->getId());
                $cartItems = $cart->items()->with('product')->get();
            @endphp
            @if($cartItems->count() > 0)
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                        <div class="flex items-center gap-x-3">
                            <img class="size-16 rounded-lg object-cover" src="{{ $item->product->image_url ?? '/images/placeholder.jpg' }}" alt="{{ $item->product->name }}">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gold-100 dark:text-gold-200">{{ $item->product->name }}</h4>
                                <p class="text-sm text-gold-300 dark:text-gold-400">Qty: {{ $item->quantity }}</p>
                                <p class="text-sm font-medium text-gold-100 dark:text-gold-200">${{ number_format($item->product->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t border-gold-600 dark:border-gold-700">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-medium text-gold-200 dark:text-gold-300">Total:</span>
                        <span class="text-lg font-bold text-gold-100 dark:text-gold-200">
                            ${{ number_format($cartItems->sum(function($item) { return $item->product->price * $item->quantity; }), 2) }}
                        </span>
                    </div>
                    <button type="button" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-maroon-900 hover:bg-[#f59e0b]-500 focus:outline-none focus:bg-[#f59e0b]-500">
                        Checkout
                    </button>
                </div>
            @else
                <p class="text-gold-300 dark:text-gold-400">Your cart is empty</p>
            @endif
        @else
            <p class="text-gold-300 dark:text-gold-400">Please sign in to view your cart</p>
        @endauth
    </div>
</div>