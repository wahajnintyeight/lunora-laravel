<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Models\Page;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop routes (alias for products)
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');

// Collections routes
Route::get('/collections', [CategoryController::class, 'index'])->name('collections.index');

// Static pages - ALWAYS check for dynamic pages first, fallback to static views only if no dynamic page exists
// These routes will use dynamic pages when they exist, otherwise fall back to static blade views
Route::get('/about', function () {
    $page = Page::published()->where('slug', 'about')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    $page = Page::published()->where('slug', 'contact')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.contact');
})->name('contact');

Route::get('/faq', function () {
    $page = Page::published()->where('slug', 'faq')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.faq');
})->name('faq');

Route::get('/shipping', function () {
    $page = Page::published()->where('slug', 'shipping')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.shipping');
})->name('shipping');

Route::get('/returns', function () {
    $page = Page::published()->where('slug', 'returns')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.returns');
})->name('returns');

Route::get('/warranty', function () {
    $page = Page::published()->where('slug', 'warranty')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.warranty');
})->name('warranty');

Route::get('/blog', function () {
    $page = Page::published()->where('slug', 'blog')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.blog');
})->name('blog.index');

Route::get('/custom', function () {
    $page = Page::published()->where('slug', 'custom')->first();
    if ($page) {
        return view('pages.show', compact('page'));
    }
    return view('pages.custom');
})->name('custom.index');

Route::get('/wishlist', function () {
    return view('pages.wishlist');
})->name('wishlist.index');

Route::get('/orders', function () {
    return redirect()->route('user.orders');
})->name('orders.index')->middleware('auth');

// Newsletter subscription
Route::post('/newsletter/subscribe', function () {
    // Newsletter subscription logic here
    return back()->with('success', 'Thank you for subscribing to our newsletter!');
})->middleware(\Spatie\Honeypot\ProtectAgainstSpam::class)->name('newsletter.subscribe');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/variants', [ProductController::class, 'getVariants'])->name('products.variants');
Route::get('/search', [ProductController::class, 'search'])->name('search')->middleware('rate.limit:search');
Route::get('/api/products/suggestions', [ProductController::class, 'searchSuggestions'])->name('products.suggestions')->middleware('rate.limit:search');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/api/categories/suggestions', [CategoryController::class, 'suggestions'])->name('categories.suggestions');

// Page routes
Route::get('/pages/{page:slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

// Cart routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::middleware(['rate.limit:cart-actions'])->group(function () {
    Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/apply-coupon', [\App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/cart/remove-coupon', [\App\Http\Controllers\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
    Route::post('/cart/update-customization/{item}', [\App\Http\Controllers\CartController::class, 'updateCustomization'])->name('cart.update-customization');
    Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
});
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::get('/api/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('api.cart.count');
Route::post('/api/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('api.cart.add');

// Checkout routes
Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/confirmation/{orderNumber}', [\App\Http\Controllers\CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
Route::get('/checkout/thank-you/{orderNumber}', [\App\Http\Controllers\CheckoutController::class, 'thankYou'])->name('checkout.thank-you');
Route::get('/api/shipping-rates', [\App\Http\Controllers\CheckoutController::class, 'getShippingRates'])->name('checkout.shipping-rates');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware(\Spatie\Honeypot\ProtectAgainstSpam::class);

    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware(['rate.limit:login', \Spatie\Honeypot\ProtectAgainstSpam::class]);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email')
        ->middleware(['rate.limit:password-reset', \Spatie\Honeypot\ProtectAgainstSpam::class]);
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update')
        ->middleware(\Spatie\Honeypot\ProtectAgainstSpam::class);

    // Google OAuth
    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Email Verification
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'rate.limit:email-verification'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('rate.limit:email-verification')
        ->name('verification.send');

    // User Profile
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::get('/profile/orders', [\App\Http\Controllers\UserController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [\App\Http\Controllers\UserController::class, 'orderDetail'])->name('profile.orders.show');
});

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin', 'rate.limit:admin-actions', 'file.security'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Profile
    Route::get('profile', [\App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
    Route::put('profile', [\App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/bulk-action', [\App\Http\Controllers\Admin\CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::post('categories/reorder', [\App\Http\Controllers\Admin\CategoryController::class, 'reorder'])->name('categories.reorder');
    
    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/bulk-action', [\App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])->name('products.bulk-action');
    
    // Product Images
    Route::get('products/{product}/images', [\App\Http\Controllers\Admin\ProductImageController::class, 'index'])->name('products.images.index');
    Route::post('products/images/upload', [\App\Http\Controllers\Admin\ProductImageController::class, 'store'])->name('products.images.store');
    Route::put('products/images/{image}', [\App\Http\Controllers\Admin\ProductImageController::class, 'update'])->name('products.images.update');
    Route::delete('products/images/{image}', [\App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('products.images.destroy');
    Route::delete('products/images/bulk-delete', [\App\Http\Controllers\Admin\ProductImageController::class, 'bulkDestroy'])->name('products.images.bulk-destroy');
    Route::post('products/images/reorder', [\App\Http\Controllers\Admin\ProductImageController::class, 'reorder'])->name('products.images.reorder');
    Route::post('products/images/{image}/set-primary', [\App\Http\Controllers\Admin\ProductImageController::class, 'setPrimary'])->name('products.images.set-primary');
    Route::get('products/images/stats', [\App\Http\Controllers\Admin\ProductImageController::class, 'stats'])->name('products.images.stats');
    Route::post('products/images/cleanup', [\App\Http\Controllers\Admin\ProductImageController::class, 'cleanup'])->name('products.images.cleanup');
    
    // Product Variants
    Route::get('products/{product}/variants', [\App\Http\Controllers\Admin\ProductController::class, 'variants'])->name('products.variants');
    Route::post('products/{product}/variants', [\App\Http\Controllers\Admin\ProductController::class, 'storeVariant'])->name('products.variants.store');
    Route::put('products/{product}/variants/{variant}', [\App\Http\Controllers\Admin\ProductController::class, 'updateVariant'])->name('products.variants.update');
    Route::delete('products/{product}/variants/{variant}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    // Product Options
    Route::get('products/{product}/options', [\App\Http\Controllers\Admin\ProductController::class, 'options'])->name('products.options');
    Route::post('products/{product}/options', [\App\Http\Controllers\Admin\ProductController::class, 'storeOption'])->name('products.options.store');
    Route::delete('products/{product}/options/{option}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyOption'])->name('products.options.destroy');
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/refund', [\App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('orders.refund');
    Route::get('orders/export', [\App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    
    // Customers
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)->only(['index', 'show', 'edit', 'update']);
    
    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    
    // Pages
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
    Route::post('upload-image', [\App\Http\Controllers\Admin\PageController::class, 'uploadImage'])->name('upload-image');
    
    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
});


// User account routes
Route::middleware(['auth', 'verified'])->prefix('account')->name('user.')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('password.update');

    Route::get('/orders', [\App\Http\Controllers\UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [\App\Http\Controllers\UserController::class, 'orderDetail'])->name('order-detail');
    Route::post('/orders/{orderNumber}/cancel', [\App\Http\Controllers\UserController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/orders/{orderNumber}/reorder', [\App\Http\Controllers\UserController::class, 'reorder'])->name('order.reorder');
    Route::get('/orders/{orderNumber}/invoice', [\App\Http\Controllers\UserController::class, 'downloadInvoice'])->name('order.invoice');

    Route::get('/addresses', [\App\Http\Controllers\UserController::class, 'addresses'])->name('addresses');
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\UserController::class, 'updateSettings'])->name('settings.update');

    Route::get('/track-order', [\App\Http\Controllers\UserController::class, 'trackOrder'])->name('track-order');
});
