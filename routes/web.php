<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/variants', [ProductController::class, 'getVariants'])->name('products.variants');
Route::get('/search', [ProductController::class, 'search'])->name('search');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/api/categories/suggestions', [CategoryController::class, 'suggestions'])->name('categories.suggestions');

// Cart routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{item}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/apply-coupon', [\App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/remove-coupon', [\App\Http\Controllers\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');
Route::post('/cart/update-customization/{item}', [\App\Http\Controllers\CartController::class, 'updateCustomization'])->name('cart.update-customization');
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::post('/cart/clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');

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
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:login');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email')
        ->middleware('throttle:password-reset');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');

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
        ->middleware(['signed', 'throttle:email-verification'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:email-verification')
        ->name('verification.send');
});

// Admin routes (protected by admin middleware)
Route::middleware(['auth', 'admin', 'throttle:admin-actions'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Products
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::post('products/{product}/images', [\App\Http\Controllers\Admin\ProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::delete('products/{product}/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');
    
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
    
    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Verified email required routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
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
