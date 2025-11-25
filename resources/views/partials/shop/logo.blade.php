<!-- Logo -->
<div class="order-1 md:w-auto flex items-center gap-x-1">
    <div class="hidden sm:block">
        <!-- Desktop Logo -->
        <a class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80" href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('img/logo.webp') }}" alt="{{ config('app.name') }}" class="h-20 w-auto">
        </a>
        <!-- End Desktop Logo -->
    </div>
    <div class="sm:hidden">
        <!-- Mobile Logo -->
        <a class="flex-none rounded-md text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80" href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
            <img src="{{ asset('img/logo.webp') }}" alt="{{ config('app.name') }}" class="h-9 w-auto">
        </a>
        <!-- End Mobile Logo -->
    </div>
</div>
<!-- End Logo -->