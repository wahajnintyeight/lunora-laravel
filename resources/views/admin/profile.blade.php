@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<!-- Page Header -->
<div class="sm:flex sm:justify-between sm:items-center mb-5">
    <div class="mb-2 sm:mb-0">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-neutral-200">
            My Profile
        </h1>
        <p class="text-sm text-gray-600 dark:text-neutral-400">
            Manage your account information and profile settings
        </p>
    </div>
</div>
<!-- End Page Header -->

<!-- Flash Messages -->
@if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-5 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-5 dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Profile Card -->
<div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
    <div class="p-4 md:p-7">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-3">
                    Profile Picture
                </label>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 dark:border-neutral-600"
                                id="avatar-preview">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#881337] to-[#78350f] flex items-center justify-center border-2 border-gray-200 dark:border-neutral-600 text-white font-bold text-2xl"
                                id="avatar-preview">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden"
                            onchange="previewAvatar(this)">
                        <div class="flex gap-2">
                            <label for="avatar"
                                class="inline-flex items-center gap-x-2 py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700 cursor-pointer">
                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 16v1a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Choose Photo
                            </label>

                            @if ($user->avatar)
                                <x-button type="button" onclick="removeAvatar()" variant="outline" size="sm" class="border-red-200 text-red-800 hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20">
                                    Remove
                                </x-button>
                                <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 dark:text-neutral-400 mt-2">JPG, PNG, GIF or WEBP. Max 2MB.</p>
                        @error('avatar')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-neutral-700"></div>

            <!-- Basic Information -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $user->name) }}" required
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#f59e0b] focus:ring-[#f59e0b] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:placeholder:text-neutral-500 dark:focus:ring-neutral-600 @error('name') border-red-500 dark:border-red-500 @enderror">
                    @error('name')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $user->email) }}" required
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#f59e0b] focus:ring-[#f59e0b] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:placeholder:text-neutral-500 dark:focus:ring-neutral-600 @error('email') border-red-500 dark:border-red-500 @enderror">
                    @error('email')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-[#f59e0b] focus:ring-[#f59e0b] disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:placeholder:text-neutral-500 dark:focus:ring-neutral-600 @error('phone') border-red-500 dark:border-red-500 @enderror"
                        placeholder="+92 300 1234567">
                    @error('phone')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-x-3 pt-4 border-t border-gray-200 dark:border-neutral-700">
                <x-button type="submit" variant="primary" size="md">
                    Save Changes
                </x-button>
                <x-button href="{{ route('admin.dashboard') }}" variant="secondary" size="md">
                    Cancel
                </x-button>
            </div>
        </form>
    </div>
</div>
<!-- End Profile Card -->

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 rounded-full object-cover border-2 border-gray-200 dark:border-neutral-600';
                    preview.parentNode.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeAvatar() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            document.getElementById('remove_avatar').value = '1';
            const preview = document.getElementById('avatar-preview');
            if (preview.tagName === 'IMG') {
                // Replace img with div
                const div = document.createElement('div');
                div.id = 'avatar-preview';
                div.className = 'w-24 h-24 rounded-full bg-gradient-to-br from-[#881337] to-[#78350f] flex items-center justify-center border-2 border-gray-200 dark:border-neutral-600 text-white font-bold text-2xl';
                div.textContent = '{{ strtoupper(substr($user->name, 0, 1)) }}';
                preview.parentNode.replaceChild(div, preview);
            }
            document.getElementById('avatar').value = '';
        }
    }
</script>
@endpush
@endsection

