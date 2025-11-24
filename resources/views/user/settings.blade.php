@extends('layouts.shop')

@section('title', 'Account Settings - Lunora Jewelry')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
            <p class="text-gray-600 mt-2">Manage your preferences and notifications</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <nav class="bg-white rounded-lg shadow-md p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.profile') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.orders') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                Order History
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.addresses') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                Addresses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.settings') }}" 
                               class="flex items-center px-3 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-md">
                                <svg class="mr-3 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                                </svg>
                                Settings
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Notification Preferences -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notification Preferences</h2>
                    
                    <form method="POST" action="{{ route('user.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Email Notifications -->
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-3">Email Notifications</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="email_notifications" 
                                               value="1"
                                               {{ ($user->settings['email_notifications'] ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-3 text-sm text-gray-700">
                                            Order updates and shipping notifications
                                        </span>
                                    </label>
                                    
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="marketing_emails" 
                                               value="1"
                                               {{ ($user->settings['marketing_emails'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-3 text-sm text-gray-700">
                                            Promotional emails and special offers
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- SMS Notifications -->
                            <div>
                                <h3 class="text-base font-medium text-gray-900 mb-3">SMS Notifications</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="sms_notifications" 
                                               value="1"
                                               {{ ($user->settings['sms_notifications'] ?? false) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                        <span class="ml-3 text-sm text-gray-700">
                                            Order status updates via SMS
                                        </span>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    SMS notifications require a valid phone number in your profile
                                </p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" 
                                    class="bg-emerald-600 text-white px-4 py-2 rounded-md hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Privacy Settings -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Privacy & Security</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Email Verification</h3>
                                <p class="text-sm text-gray-600">Your email address verification status</p>
                            </div>
                            <div>
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Two-Factor Authentication</h3>
                                <p class="text-sm text-gray-600">Add an extra layer of security to your account</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Coming Soon
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Account Activity</h3>
                                <p class="text-sm text-gray-600">View your recent account activity and login history</p>
                            </div>
                            <div>
                                <button class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                                    View Activity
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Actions</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Download Your Data</h3>
                                <p class="text-sm text-gray-600">Get a copy of your account data and order history</p>
                            </div>
                            <div>
                                <button class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">
                                    Request Data
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200">
                            <div>
                                <h3 class="text-sm font-medium text-gray-900">Delete Account</h3>
                                <p class="text-sm text-gray-600">Permanently delete your account and all associated data</p>
                            </div>
                            <div>
                                <button class="text-red-600 hover:text-red-700 text-sm font-medium"
                                        onclick="alert('Account deletion is not available at this time. Please contact support for assistance.')">
                                    Delete Account
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support Information -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h2>
                    <div class="space-y-3 text-gray-600">
                        <p>If you have any questions about your account or need assistance, we're here to help:</p>
                        <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-2 sm:space-y-0">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <span>support@lunora.com</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <span>+92 300 1234567</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection