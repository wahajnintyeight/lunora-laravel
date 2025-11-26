{{-- Mobile-Responsive User Account Navigation --}}
<nav class="bg-white rounded-lg shadow-md">
    <!-- Mobile Navigation Toggle -->
    <div class="lg:hidden">
        <button type="button" 
                class="w-full flex items-center justify-between px-4 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-inset"
                onclick="toggleMobileNav()">
            <span>Account Menu</span>
            <svg class="h-5 w-5 transform transition-transform" id="mobile-nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="hidden lg:block" id="mobile-nav-menu">
        <ul class="space-y-2 p-4">
            <li>
                <a href="{{ route('user.profile') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-md min-h-[44px] touch-target transition-colors {{ request()->routeIs('user.profile') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:text-gold-600 hover:bg-gold-50' }}"
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Profile
                </a>
            </li>
            <li>
                <a href="{{ route('user.orders') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-md min-h-[44px] touch-target transition-colors {{ request()->routeIs('user.orders*') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:text-gold-600 hover:bg-gold-50' }}"
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    Order History
                </a>
            </li>
            <li>
                <a href="{{ route('user.addresses') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-md min-h-[44px] touch-target transition-colors {{ request()->routeIs('user.addresses*') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:text-gold-600 hover:bg-gold-50' }}"
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    Addresses
                </a>
            </li>
            <li>
                <a href="{{ route('user.settings') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-md min-h-[44px] touch-target transition-colors {{ request()->routeIs('user.settings*') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:text-gold-600 hover:bg-gold-50' }}"
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    Settings
                </a>
            </li>
            @if(auth()->user() && auth()->user()->role === 'admin')
            <li class="border-t pt-2 mt-2">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center px-3 py-3 text-sm font-medium rounded-md min-h-[44px] touch-target transition-colors {{ request()->routeIs('admin.*') ? 'text-gold-600 bg-gold-50' : 'text-gray-700 hover:text-gold-600 hover:bg-gold-50' }}"
                    <svg class="mr-3 h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10.5 1.5H5a3.5 3.5 0 00-3.5 3.5v10A3.5 3.5 0 005 18.5h10a3.5 3.5 0 003.5-3.5V9.5m-15-4h14m-7 7v-4m0 4v3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Admin Dashboard
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>

<script>
function toggleMobileNav() {
    const menu = document.getElementById('mobile-nav-menu');
    const icon = document.getElementById('mobile-nav-icon');
    
    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        menu.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Show navigation on larger screens
window.addEventListener('resize', function() {
    const menu = document.getElementById('mobile-nav-menu');
    if (window.innerWidth >= 1024) {
        menu.classList.remove('hidden');
    } else {
        menu.classList.add('hidden');
        document.getElementById('mobile-nav-icon').classList.remove('rotate-180');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const menu = document.getElementById('mobile-nav-menu');
    if (window.innerWidth >= 1024) {
        menu.classList.remove('hidden');
    }
});
</script>