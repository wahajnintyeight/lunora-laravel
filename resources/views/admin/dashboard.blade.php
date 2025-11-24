@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Total Users</h3>
                <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
            </div>
            
            <div class="bg-green-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Active Users</h3>
                <p class="text-3xl font-bold text-green-600">{{ \App\Models\User::where('is_active', true)->count() }}</p>
            </div>
            
            <div class="bg-purple-50 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-purple-900 mb-2">Admin Users</h3>
                <p class="text-3xl font-bold text-purple-600">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
            </div>
        </div>
        
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
            <div class="space-x-4">
                <a href="/admin" class="bg-gray-900 text-white px-4 py-2 rounded-md hover:bg-gray-800">
                    Filament Admin Panel
                </a>
                <a href="{{ route('home') }}" class="bg-gray-200 text-gray-900 px-4 py-2 rounded-md hover:bg-gray-300">
                    Back to Site
                </a>
            </div>
        </div>
    </div>
</div>
@endsection