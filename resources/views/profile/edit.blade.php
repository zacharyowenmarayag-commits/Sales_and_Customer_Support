@extends('layouts.app')

@section('title', 'Profile - AmbatuGrow')

@section('content')
<div class="max-w-4xl">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-500 mt-1">Manage your account information and settings</p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Profile Header with Avatar -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-8 py-12 text-center text-white">
            <div class="w-24 h-24 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center border-4 border-white/40">
                <span class="text-5xl font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                </span>
            </div>
            <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
            <p class="text-green-100 mt-1">{{ Auth::user()->title ?? Auth::user()->role ?? 'User' }}</p>
        </div>

        <!-- Profile Information Form -->
        <div class="px-8 py-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-green-700 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    <p class="text-red-700 font-semibold mb-2">There were errors updating your profile:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                        <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                        <input type="text" id="department" name="department" value="{{ Auth::user()->department }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title/Role -->
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Title/Position</label>
                        <input type="text" id="title" name="title" value="{{ Auth::user()->title }}" placeholder="e.g., Sales Manager" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Account Info Section (Read-only) -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200 mt-8 pt-8 border-t">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Role -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                            <p class="text-gray-900 capitalize">{{ Auth::user()->role ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Contact administrator to change role</p>
                        </div>

                        <!-- Member Since -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Member Since</label>
                            <p class="text-gray-900">{{ Auth::user()->created_at->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ Auth::user()->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2.5 rounded-lg font-semibold transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mt-6">
        <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Change Password</h3>
            <p class="text-sm text-gray-500 mt-1">Update your password to keep your account secure</p>
        </div>

        <div class="px-8 py-8">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Update Button -->
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg font-semibold transition flex items-center gap-2">
                    <i class="fas fa-lock"></i>
                    Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
