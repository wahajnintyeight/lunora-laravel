<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return view('shop.index');
})->name('home');

// Search route
Route::get('/search', function () {
    return view('shop.search');
})->name('search');

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
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Verified email required routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});
