<!-- ========== FOOTER ========== -->
<footer class="bg-gray-900 dark:bg-neutral-950 mt-auto">
    <div class="max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 lg:pt-20 mx-auto">
        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 lg:gap-8">
            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <!-- Logo -->
                <a class="flex-none text-xl font-semibold text-white focus:outline-none focus:opacity-80" href="{{ route('home') }}" aria-label="{{ config('app.name') }}">
                    {{ config('app.name') }}
                </a>
                <p class="mt-3 text-xs sm:text-sm text-gray-400">
                    Premium jewelry collection crafted with excellence and passion. Discover timeless pieces that celebrate life's precious moments.
                </p>
                
                <!-- Contact Info -->
                <div class="mt-6 space-y-2">
                    <div class="flex items-center gap-x-2 text-sm text-gray-400">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span>support@lunora.com</span>
                    </div>
                    <div class="flex items-center gap-x-2 text-sm text-gray-400">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span>+92 300 1234567</span>
                    </div>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-gray-100 mb-4">Shop</h4>

                <div class="grid space-y-3">
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('products.index', ['category' => 'rings']) }}">Rings</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('products.index', ['category' => 'necklaces']) }}">Necklaces</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('products.index', ['category' => 'earrings']) }}">Earrings</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('products.index', ['category' => 'bracelets']) }}">Bracelets</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('categories.index') }}">All Categories</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-gray-100 mb-4">Account</h4>

                <div class="grid space-y-3">
                    @auth
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('user.profile') }}">My Profile</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('user.orders') }}">My Orders</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('user.addresses') }}">Addresses</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('user.settings') }}">Settings</a></p>
                    @else
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('login') }}">Sign in</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('register') }}">Create account</a></p>
                        <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="{{ route('user.track-order') }}">Track Order</a></p>
                    @endauth
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-gray-100 mb-4">Support</h4>

                <div class="grid space-y-3">
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="#">Size Guide</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="#">Care Instructions</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="#">Shipping Info</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="#">Returns & Exchanges</a></p>
                    <p><a class="inline-flex gap-x-2 text-gray-400 hover:text-gray-200 focus:outline-none focus:text-gray-200 transition-colors" href="#">Contact Us</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <h4 class="font-semibold text-gray-100 mb-4">Stay Connected</h4>

                <form class="mb-6">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <div class="flex-1">
                            <label for="newsletter-email" class="sr-only">Subscribe to newsletter</label>
                            <input type="email" id="newsletter-email" name="email" class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" placeholder="Enter your email" required>
                        </div>
                        <button type="submit" class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-[#f59e0b] text-white hover:bg-emerald-700 focus:outline-none focus:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none transition-colors">
                            Subscribe
                        </button>
                    </div>
                    <p class="mt-3 text-xs text-gray-400">
                        Get the latest jewelry trends, exclusive offers, and style tips delivered to your inbox.
                    </p>
                </form>

                <!-- Social Media -->
                <div>
                    <h5 class="text-sm font-medium text-gray-100 mb-3">Follow Us</h5>
                    <div class="flex gap-2">
                        <a class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none transition-colors" href="#" aria-label="Facebook">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                        <a class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none transition-colors" href="#" aria-label="Instagram">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                        </a>
                        <a class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none transition-colors" href="#" aria-label="Twitter">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z"/>
                            </svg>
                        </a>
                        <a class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 disabled:opacity-50 disabled:pointer-events-none transition-colors" href="#" aria-label="Pinterest">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->

        <!-- Bottom Section -->
        <div class="mt-8 sm:mt-12 pt-8 border-t border-gray-800">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <p class="text-sm text-gray-400">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <a href="#" class="hover:text-gray-300 transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-gray-300 transition-colors">Terms of Service</a>
                        <a href="#" class="hover:text-gray-300 transition-colors">Cookie Policy</a>
                    </div>
                </div>
                
                <!-- Payment Methods -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">We accept:</span>
                    <div class="flex gap-1">
                        <div class="w-8 h-5 bg-gray-700 rounded flex items-center justify-center">
                            <span class="text-xs text-white font-bold">V</span>
                        </div>
                        <div class="w-8 h-5 bg-gray-700 rounded flex items-center justify-center">
                            <span class="text-xs text-white font-bold">MC</span>
                        </div>
                        <div class="w-8 h-5 bg-gray-700 rounded flex items-center justify-center">
                            <span class="text-xs text-white font-bold">PP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ========== END FOOTER ========== -->