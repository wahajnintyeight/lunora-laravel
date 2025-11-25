@extends('layouts.app')

@section('title', 'Shop - ' . config('app.name'))
@section('meta_description', 'Discover our exquisite collection of premium jewelry including rings, necklaces, earrings, and bracelets.')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-neutral-900 dark:to-neutral-800">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-24">
        <div class="text-center">
            <h1 class="text-4xl sm:text-6xl font-bold text-gray-800 dark:text-neutral-200">
                Exquisite
                <span class="bg-clip-text bg-gradient-to-tl from-[#f59e0b] to-emerald-400 text-transparent">
                    Jewelry
                </span>
            </h1>
            <p class="mt-3 text-gray-600 dark:text-neutral-400">
                Discover our handcrafted collection of premium jewelry, designed to celebrate life's precious moments.
            </p>

            <div class="mt-7 sm:mt-12 mx-auto max-w-3xl relative">
                <!-- Form -->
                <form action="{{ route('search') }}" method="GET">
                    <div class="relative z-10 flex space-x-3 p-3 bg-white border rounded-lg shadow-lg shadow-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-gray-900/20">
                        <div class="flex-[1_0_0%]">
                            <label for="hs-search-article-1" class="block text-sm text-gray-700 font-medium dark:text-white">
                                <span class="sr-only">Search jewelry</span>
                            </label>
                            <input type="text" name="q" id="hs-search-article-1" class="py-2.5 px-4 block w-full border-transparent rounded-lg focus:border-emerald-500 focus:ring-emerald-500 dark:bg-neutral-900 dark:border-transparent dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Search for rings, necklaces, earrings...">
                        </div>
                        <div class="flex-[0_0_auto]">
                            <button type="submit" class="size-[46px] inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
                <!-- End Form -->

                <!-- SVG Element -->
                <div class="hidden md:block absolute top-2 -start-3 -z-[1] size-16 bg-gradient-to-b from-orange-500 to-red-400 rounded-full transform -rotate-12"></div>
                <div class="hidden md:block absolute bottom-2 -end-3 -z-[1] size-16 bg-gradient-to-t from-[#f59e0b] to-cyan-400 rounded-full transform rotate-12"></div>
            </div>

            <div class="mt-10 sm:mt-20 flex justify-center gap-x-6">
                <a class="group inline-flex items-center gap-x-2 py-2 px-3 bg-emerald-50 font-medium text-sm text-emerald-800 rounded-full focus:outline-none focus:bg-emerald-100 dark:bg-emerald-800/30 dark:text-emerald-400 dark:focus:bg-emerald-800/10" href="#">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 3h12l4 6-10 13L2 9Z"/>
                        <path d="M11 3 8 9l4 13 4-13-3-6"/>
                        <path d="M2 9h20"/>
                    </svg>
                    New Collection
                </a>
                <a class="group inline-flex items-center gap-x-2 py-2 px-3 bg-emerald-50 font-medium text-sm text-emerald-800 rounded-full focus:outline-none focus:bg-emerald-100 dark:bg-emerald-800/30 dark:text-emerald-400 dark:focus:bg-emerald-800/10" href="#">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    Limited Time Offers
                </a>
            </div>
        </div>
    </div>
</section>
<!-- End Hero -->

<!-- Categories Section -->
<section class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Shop by Category</h2>
        <p class="mt-1 text-gray-600 dark:text-neutral-400">Explore our curated collections of fine jewelry</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Category Card -->
        <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
            <div class="aspect-w-12 aspect-h-7 sm:aspect-w-1 sm:aspect-h-1">
                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover size-full rounded-xl" src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Rings Collection">
            </div>
            <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                <div class="text-sm font-bold text-gray-800 rounded-lg bg-white p-4 md:text-xl dark:bg-neutral-800 dark:text-neutral-200">
                    Rings
                </div>
            </div>
        </a>
        <!-- End Category Card -->

        <!-- Category Card -->
        <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
            <div class="aspect-w-12 aspect-h-7 sm:aspect-w-1 sm:aspect-h-1">
                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover size-full rounded-xl" src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Necklaces Collection">
            </div>
            <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                <div class="text-sm font-bold text-gray-800 rounded-lg bg-white p-4 md:text-xl dark:bg-neutral-800 dark:text-neutral-200">
                    Necklaces
                </div>
            </div>
        </a>
        <!-- End Category Card -->

        <!-- Category Card -->
        <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
            <div class="aspect-w-12 aspect-h-7 sm:aspect-w-1 sm:aspect-h-1">
                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover size-full rounded-xl" src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Earrings Collection">
            </div>
            <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                <div class="text-sm font-bold text-gray-800 rounded-lg bg-white p-4 md:text-xl dark:bg-neutral-800 dark:text-neutral-200">
                    Earrings
                </div>
            </div>
        </a>
        <!-- End Category Card -->

        <!-- Category Card -->
        <a class="group relative block rounded-xl overflow-hidden focus:outline-none" href="#">
            <div class="aspect-w-12 aspect-h-7 sm:aspect-w-1 sm:aspect-h-1">
                <img class="group-hover:scale-105 group-focus:scale-105 transition-transform duration-500 ease-in-out object-cover size-full rounded-xl" src="https://images.unsplash.com/photo-1611652022419-a9419f74343d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=560&q=80" alt="Bracelets Collection">
            </div>
            <div class="absolute bottom-0 start-0 end-0 p-2 sm:p-4">
                <div class="text-sm font-bold text-gray-800 rounded-lg bg-white p-4 md:text-xl dark:bg-neutral-800 dark:text-neutral-200">
                    Bracelets
                </div>
            </div>
        </a>
        <!-- End Category Card -->
    </div>
</section>
<!-- End Categories -->

<!-- Featured Products -->
<section class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white">Featured Products</h2>
        <p class="mt-1 text-gray-600 dark:text-neutral-400">Handpicked selections from our premium collection</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Product Card -->
        <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="h-52 flex flex-col justify-center items-center bg-emerald-500 rounded-t-xl">
                <img class="h-full w-full object-cover rounded-t-xl" src="https://images.unsplash.com/photo-1605100804763-247f67b3557e?ixlib=rb-4.0.3&auto=format&fit=crop&w=560&q=80" alt="Diamond Ring">
            </div>
            <div class="p-4 md:p-6">
                <span class="block mb-1 text-xs font-semibold uppercase text-[#f59e0b] dark:text-emerald-500">
                    Rings
                </span>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-300 dark:hover:text-white">
                    Classic Diamond Ring
                </h3>
                <p class="mt-3 text-gray-500 dark:text-neutral-500">
                    Elegant solitaire diamond ring crafted in 18k white gold.
                </p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800 dark:text-neutral-200">$2,499</span>
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <!-- End Product Card -->

        <!-- Product Card -->
        <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="h-52 flex flex-col justify-center items-center bg-emerald-500 rounded-t-xl">
                <img class="h-full w-full object-cover rounded-t-xl" src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?ixlib=rb-4.0.3&auto=format&fit=crop&w=560&q=80" alt="Pearl Necklace">
            </div>
            <div class="p-4 md:p-6">
                <span class="block mb-1 text-xs font-semibold uppercase text-[#f59e0b] dark:text-emerald-500">
                    Necklaces
                </span>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-300 dark:hover:text-white">
                    Pearl Strand Necklace
                </h3>
                <p class="mt-3 text-gray-500 dark:text-neutral-500">
                    Timeless freshwater pearl necklace with sterling silver clasp.
                </p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800 dark:text-neutral-200">$899</span>
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <!-- End Product Card -->

        <!-- Product Card -->
        <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="h-52 flex flex-col justify-center items-center bg-emerald-500 rounded-t-xl">
                <img class="h-full w-full object-cover rounded-t-xl" src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?ixlib=rb-4.0.3&auto=format&fit=crop&w=560&q=80" alt="Gold Earrings">
            </div>
            <div class="p-4 md:p-6">
                <span class="block mb-1 text-xs font-semibold uppercase text-[#f59e0b] dark:text-emerald-500">
                    Earrings
                </span>
                <h3 class="text-xl font-semibold text-gray-800 dark:text-neutral-300 dark:hover:text-white">
                    Gold Drop Earrings
                </h3>
                <p class="mt-3 text-gray-500 dark:text-neutral-500">
                    Sophisticated 14k gold drop earrings with diamond accents.
                </p>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-2xl font-bold text-gray-800 dark:text-neutral-200">$1,299</span>
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        <!-- End Product Card -->
    </div>

    <div class="mt-12 text-center">
        <a class="py-3 px-4 inline-flex items-center gap-x-1 text-sm font-medium rounded-full border border-gray-200 bg-white text-[#f59e0b] shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-neutral-900 dark:border-neutral-700 dark:text-emerald-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" href="#">
            View all products
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m9 18 6-6-6-6"></path>
            </svg>
        </a>
    </div>
</section>
<!-- End Featured Products -->
@endsection