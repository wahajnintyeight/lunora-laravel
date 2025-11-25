<nav class="navbar" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
    <div class="navbar-container">
        <!-- Logo -->
        <div class="navbar-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="logo-img w-15">
            </a>
        </div>

        <!-- Navigation Links -->
        <ul class="nav-links">
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('shop.index') }}" class="nav-link {{ request()->routeIs('shop.*') || request()->routeIs('products.*') ? 'active' : '' }}">Shop</a></li>
            <li><a href="{{ route('collections.index') }}" class="nav-link {{ request()->routeIs('collections.*') || request()->routeIs('categories.*') ? 'active' : '' }}">Collections</a></li>
            <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
        </ul>

        <!-- Right Section: Search, Cart, User Menu -->
        <div class="navbar-right">
            <!-- Search Icon -->
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-search-modal'))"
                class="group inline-flex items-center justify-center rounded-lg p-2 text-[#f59e0b] transition-colors duration-200 hover:bg-[#f59e0b]/10 focus:outline-none focus:ring-2 focus:ring-[#f59e0b] focus:ring-offset-2 focus:ring-offset-[#450a0a]"
                aria-label="Open search" title="Search">
                <svg class="size-5 transition-transform duration-200 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>

            <!-- Cart Icon -->
            <a href="{{ route('cart.index') }}" class="icon-btn" title="Shopping Cart">
                <i class="fas fa-shopping-bag"></i>
                @php
                    $cartCount = 0;
                    if (session('cart')) {
                        $cartCount = collect(session('cart'))->sum('quantity');
                    }
                @endphp
                <span class="cart-badge" id="cart-count" style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
            </a>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="user-menu-toggle icon-btn" title="Account">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 py-1"
                     style="display: none;">
                    @auth
                        <div class="px-4 py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Hello,</span>
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        </div>
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i> My Account
                        </a>
                        <a href="{{ route('user.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-box mr-2"></i> My Orders
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-heart mr-2"></i> Wishlist
                        </a>
                        @if(auth()->user()->isAdmin())
                            <div class="border-t border-gray-100"></div>
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-shield-alt mr-2"></i> Admin Dashboard
                            </a>
                        @endif
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                        </a>
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-[#f59e0b] font-semibold hover:bg-gray-100">
                            <i class="fas fa-user-plus mr-2"></i> Create Account
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="mobile-menu-toggle" :class="{ 'active': mobileMenuOpen }">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden"
         style="display: none;"></div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 bottom-0 w-64 bg-white shadow-xl z-50 overflow-y-auto lg:hidden"
         style="display: none;">
        
        <!-- Mobile Menu Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <span class="text-lg font-bold text-[#450a0a]">✦ Lunora</span>
            <button @click="mobileMenuOpen = false" class="p-2 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Links -->
        <div class="py-4">
            <a href="{{ route('home') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('home') ? 'bg-gray-100 font-semibold' : '' }}">
                Home
            </a>
            <a href="{{ route('shop.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('shop.*') || request()->routeIs('products.*') ? 'bg-gray-100 font-semibold' : '' }}">
                Shop
            </a>
            <a href="{{ route('collections.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('collections.*') || request()->routeIs('categories.*') ? 'bg-gray-100 font-semibold' : '' }}">
                Collections
            </a>
            <a href="{{ route('about') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('about') ? 'bg-gray-100 font-semibold' : '' }}">
                About
            </a>
            <a href="{{ route('contact') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('contact') ? 'bg-gray-100 font-semibold' : '' }}">
                Contact
            </a>
        </div>

        <!-- Mobile User Section -->
        <div class="border-t pt-4 pb-4">
            @auth
                <div class="px-4 py-2 text-sm text-gray-500">
                    Hello, {{ auth()->user()->name }}
                </div>
                <a href="{{ route('profile.show') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog mr-2"></i> My Account
                </a>
                <a href="{{ route('user.orders') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-box mr-2"></i> My Orders
                </a>
                <a href="{{ route('wishlist.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-heart mr-2"></i> Wishlist
                </a>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-shield-alt mr-2"></i> Admin Dashboard
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-in-alt mr-2"></i> Sign In
                </a>
                <a href="{{ route('register') }}" class="block px-4 py-3 text-[#f59e0b] font-semibold hover:bg-gray-100">
                    <i class="fas fa-user-plus mr-2"></i> Create Account
                </a>
            @endauth
        </div>
    </div>
</nav>