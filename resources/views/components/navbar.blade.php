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
            
            @php
                // Helper functions for pages (with fallback if view composer didn't run)
                $hasPage = $hasPage ?? function($slug) {
                    return \App\Models\Page::published()->where('slug', $slug)->exists();
                };
                $getPage = $getPage ?? function($slug) {
                    return \App\Models\Page::published()->where('slug', $slug)->first();
                };
            @endphp
            
            @if(is_callable($hasPage) && $hasPage('about'))
                @php $aboutPage = is_callable($getPage) ? $getPage('about') : null; @endphp
                @if($aboutPage)
                    <li><a href="{{ route('page.show', $aboutPage->slug) }}" class="nav-link {{ (request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'about') || request()->routeIs('about') ? 'active' : '' }}">{{ $aboutPage->title }}</a></li>
                @else
                    <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                @endif
            @else
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            @endif
            
            @if(is_callable($hasPage) && $hasPage('contact'))
                @php $contactPage = is_callable($getPage) ? $getPage('contact') : null; @endphp
                @if($contactPage)
                    <li><a href="{{ route('page.show', $contactPage->slug) }}" class="nav-link {{ (request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'contact') || request()->routeIs('contact') ? 'active' : '' }}">{{ $contactPage->title }}</a></li>
                @else
                    <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                @endif
            @else
                <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            @endif
            
            @if(is_callable($hasPage) && $hasPage('faq'))
                @php $faqPage = is_callable($getPage) ? $getPage('faq') : null; @endphp
                @if($faqPage)
                    <li><a href="{{ route('page.show', $faqPage->slug) }}" class="nav-link {{ request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'faq' ? 'active' : '' }}">{{ $faqPage->title }}</a></li>
                @endif
            @endif
            
            @if(is_callable($hasPage) && $hasPage('blog'))
                @php $blogPage = is_callable($getPage) ? $getPage('blog') : null; @endphp
                @if($blogPage)
                    <li><a href="{{ route('page.show', $blogPage->slug) }}" class="nav-link {{ request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'blog' ? 'active' : '' }}">{{ $blogPage->title }}</a></li>
                @endif
            @endif
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
                    @auth
                        @if(auth()->user()->getAvatarUrl())
                            <img src="{{ auth()->user()->getAvatarUrl() }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-8 h-8 rounded-full object-cover border-2 border-gold-300">
                        @else
                            <i class="fas fa-user-circle"></i>
                        @endif
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endauth
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
                            <div class="flex items-center space-x-3">
                                @if(auth()->user()->getAvatarUrl())
                                    <img src="{{ auth()->user()->getAvatarUrl() }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="w-10 h-10 rounded-full object-cover border-2 border-gold-300">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center border-2 border-gold-300">
                                        <svg class="w-5 h-5 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm text-gray-500">Hello,</span>
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
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
            
            @php
                // Helper functions for pages (with fallback if view composer didn't run)
                if (!isset($hasPage) || !is_callable($hasPage)) {
                    $hasPage = function($slug) {
                        return \App\Models\Page::published()->where('slug', $slug)->exists();
                    };
                }
                if (!isset($getPage) || !is_callable($getPage)) {
                    $getPage = function($slug) {
                        return \App\Models\Page::published()->where('slug', $slug)->first();
                    };
                }
            @endphp
            
            @if($hasPage('about'))
                @php $aboutPage = $getPage('about'); @endphp
                @if($aboutPage)
                    <a href="{{ route('page.show', $aboutPage->slug) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ ((request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'about') || request()->routeIs('about')) ? 'bg-gray-100 font-semibold' : '' }}">
                        {{ $aboutPage->title }}
                    </a>
                @else
                    <a href="{{ route('about') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('about') ? 'bg-gray-100 font-semibold' : '' }}">
                        About
                    </a>
                @endif
            @else
                <a href="{{ route('about') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('about') ? 'bg-gray-100 font-semibold' : '' }}">
                    About
                </a>
            @endif
            
            @if($hasPage('contact'))
                @php $contactPage = $getPage('contact'); @endphp
                @if($contactPage)
                    <a href="{{ route('page.show', $contactPage->slug) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ ((request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'contact') || request()->routeIs('contact')) ? 'bg-gray-100 font-semibold' : '' }}">
                        {{ $contactPage->title }}
                    </a>
                @else
                    <a href="{{ route('contact') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('contact') ? 'bg-gray-100 font-semibold' : '' }}">
                        Contact
                    </a>
                @endif
            @else
                <a href="{{ route('contact') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('contact') ? 'bg-gray-100 font-semibold' : '' }}">
                    Contact
                </a>
            @endif
            
            @if($hasPage('faq'))
                @php $faqPage = $getPage('faq'); @endphp
                @if($faqPage)
                    <a href="{{ route('page.show', $faqPage->slug) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'faq' ? 'bg-gray-100 font-semibold' : '' }}">
                        {{ $faqPage->title }}
                    </a>
                @endif
            @endif
            
            @if($hasPage('blog'))
                @php $blogPage = $getPage('blog'); @endphp
                @if($blogPage)
                    <a href="{{ route('page.show', $blogPage->slug) }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 {{ request()->routeIs('page.show') && request()->route('page') && request()->route('page')->slug === 'blog' ? 'bg-gray-100 font-semibold' : '' }}">
                        {{ $blogPage->title }}
                    </a>
                @endif
            @endif
        </div>

        <!-- Mobile User Section -->
        <div class="border-t pt-4 pb-4">
            @auth
                <div class="px-4 py-3 flex items-center space-x-3">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-gold-300">
                    @elseif(auth()->user()->getAvatarUrl())
                        <img src="{{ auth()->user()->getAvatarUrl() }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-10 h-10 rounded-full object-cover border-2 border-gold-300">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gold-100 flex items-center justify-center border-2 border-gold-300">
                            <svg class="w-5 h-5 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <span class="text-sm text-gray-500">Hello,</span>
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                <a href="{{ route('user.profile') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100">
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