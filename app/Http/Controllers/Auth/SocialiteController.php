<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    /**
     * Redirect to Google OAuth provider.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this Google ID
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // User exists with Google ID, log them in
                Auth::login($user);
                $user->update(['last_login_at' => now()]);
                
                return redirect()->intended(route('home'))
                    ->with('success', 'Welcome back!');
            }
            
            // Check if user exists with same email
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar_url' => $googleUser->getAvatar(),
                    'last_login_at' => now(),
                ]);
                
                Auth::login($existingUser);
                
                return redirect()->intended(route('home'))
                    ->with('success', 'Your Google account has been linked successfully!');
            }
            
            // Create new user
            $newUser = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar_url' => $googleUser->getAvatar(),
                'email_verified_at' => now(), // Google accounts are pre-verified
                'password' => Hash::make(Str::random(24)), // Random password for security
                'role' => 'customer',
                'is_active' => true,
                'last_login_at' => now(),
            ]);
            
            Auth::login($newUser);
            
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome to Lunora! Your account has been created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again or use email/password.');
        }
    }
}
