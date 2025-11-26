@extends('layouts.app')

@section('title', 'My Profile - Lunora Jewelry')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <aside class="lg:col-span-1">
                    @include('partials.user.navigation')
                </aside>

                <main class="lg:col-span-3 space-y-8">
                    <!-- Profile Information -->
                    <section class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Profile Information</h2>

                        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            @method('PUT')

                            <!-- Avatar Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Profile Picture</label>
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        @if ($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                                class="w-20 h-20 rounded-full object-cover border-2 border-gold-200"
                                                id="avatar-preview">
                                        @else
                                            <div class="w-20 h-20 rounded-full bg-gold-100 flex items-center justify-center border-2 border-gold-200"
                                                id="avatar-preview">
                                                <svg class="w-8 h-8 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                                            onchange="previewAvatar(this)">
                                        <div class="flex gap-2">
                                            <label for="avatar"
                                                class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus-within:ring-2 focus-within:ring-gold-500 focus-within:ring-offset-2 cursor-pointer">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Choose Photo
                                            </label>

                                            @if ($user->avatar)
                                                <button type="button" onclick="removeAvatar()"
                                                    class="inline-flex items-center gap-2 px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                    Remove
                                                </button>
                                                <input type="hidden" name="remove_avatar" id="remove_avatar"
                                                    value="0">
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF. Max 2MB.</p>
                                        @error('avatar')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full
                                        Name</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                    @error('name')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                        Address</label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone
                                        Number</label>
                                    <input type="tel" id="phone" name="phone"
                                        value="{{ old('phone', $user->phone) }}" placeholder="+92 300 1234567"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                    @error('phone')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of
                                        Birth</label>
                                    <input type="date" id="date_of_birth" name="date_of_birth"
                                        value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('date_of_birth') border-red-500 @enderror">
                                    @error('date_of_birth')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account Status -->
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-600">
                                    <strong>Member since:</strong> {{ $user->created_at->format('M d, Y') }}
                                </p>
                                @if ($user->email_verified_at)
                                    <p class="text-sm text-green-600 flex items-center gap-1 mt-2">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Email verified
                                    </p>
                                @else
                                    <p class="text-sm text-orange-600 flex items-center gap-1 mt-2">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Email not verified
                                    </p>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full md:w-auto bg-maroon-600 hover:bg-maroon-700 text-white font-medium px-6 py-2.5 rounded-md focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 transition-colors">
                                Save Changes
                            </button>
                        </form>
                    </section>

                    <!-- Change Password -->
                    <section class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h2>

                        <form method="POST" action="{{ route('user.password.update') }}" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current
                                    Password</label>
                                <input type="password" id="current_password" name="current_password" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                                @error('current_password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New
                                    Password</label>
                                <input type="password" id="password" name="password" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-md text-base focus:ring-2 focus:ring-gold-500 focus:border-transparent @error('password_confirmation') border-red-500 @enderror">
                                @error('password_confirmation')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit"
                                class="w-full md:w-auto bg-maroon-600 hover:bg-maroon-700 text-white font-medium px-6 py-2.5 rounded-md focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 transition-colors">
                                Update Password
                            </button>
                        </form>
                    </section>

                    <!-- Account Statistics -->
                    <section class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6">Account Overview</h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <div class="text-2xl font-bold text-maroon-600">{{ $user->orders->count() }}</div>
                                <div class="text-sm text-gray-600 mt-1">Total Orders</div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <div class="text-2xl font-bold text-maroon-600">PKR
                                    {{ number_format($user->orders->sum('total_pkr') / 100, 2) }}</div>
                                <div class="text-sm text-gray-600 mt-1">Total Spent</div>
                            </div>

                            <div class="p-4 bg-gray-50 rounded-lg text-center">
                                <div class="text-2xl font-bold text-maroon-600">
                                    {{ $user->orders->where('status', 'pending')->count() }}</div>
                                <div class="text-sm text-gray-600 mt-1">Pending Orders</div>
                            </div>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewAvatar(input) {
                if (!input.files?.[0]) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.getElementById('avatar-preview');
                    preview.innerHTML =
                        `<img src="${e.target.result}" alt="Avatar Preview" class="w-20 h-20 rounded-full object-cover border-2 border-gold-200">`;
                };
                reader.readAsDataURL(input.files[0]);

                const removeInput = document.getElementById('remove_avatar');
                if (removeInput) removeInput.value = '0';
            }

            function removeAvatar() {
                document.getElementById('remove_avatar').value = '1';
                document.getElementById('avatar').value = '';

                const preview = document.getElementById('avatar-preview');
                preview.innerHTML = `
        <div class="w-20 h-20 rounded-full bg-gold-100 flex items-center justify-center border-2 border-gold-200">
            <svg class="w-8 h-8 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
        </div>
    `;
            }
        </script>
    @endpush
@endsection
