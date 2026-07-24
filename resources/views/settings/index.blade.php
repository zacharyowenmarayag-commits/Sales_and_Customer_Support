@extends('layouts.app')

@section('title', 'System & Account Settings - AmbatuGrow')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-gray-200 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <i class="fas fa-gear text-green-700 text-lg"></i>
                System & Account Settings
            </h1>
            <p class="text-xs text-gray-500 mt-1">Configure your system preferences, security parameters, notifications, and database options.</p>
        </div>
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                System Status: Active
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-3.5 bg-green-50 border border-green-200 rounded-lg text-xs font-semibold text-green-800 flex items-center gap-2">
            <i class="fas fa-circle-check text-green-600 text-sm"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 bg-red-50 border border-red-200 rounded-lg text-xs font-semibold text-red-800 space-y-1">
            <div class="flex items-center gap-2">
                <i class="fas fa-triangle-exclamation text-red-600 text-sm"></i>
                <span>Please fix the following issues:</span>
            </div>
            <ul class="list-disc pl-6 text-[11px] font-normal text-red-700">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Columns: Settings Form Panels -->
        <div class="lg:col-span-2 space-y-6">

            <!-- General Preferences -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-sliders text-green-700"></i>
                        General Preferences
                    </h2>
                    <span class="text-[11px] text-gray-400 font-normal">Core System Setup</span>
                </div>
                <form action="{{ route('settings.preferences') }}" method="POST" class="p-5 space-y-4 text-xs">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Application Name</label>
                            <input type="text" name="app_name" value="{{ $preferences['app_name'] ?? 'AmbatuGrow ERP' }}" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Environment</label>
                            <input type="text" value="Production (Local Host)" readonly class="w-full bg-gray-100 border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-600 cursor-not-allowed">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Timezone</label>
                            <select name="timezone" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <option value="Asia/Manila (PST)" {{ ($preferences['timezone'] ?? '') === 'Asia/Manila (PST)' ? 'selected' : '' }}>Asia/Manila (PST, GMT+8)</option>
                                <option value="UTC / GMT" {{ ($preferences['timezone'] ?? '') === 'UTC / GMT' ? 'selected' : '' }}>UTC / GMT</option>
                                <option value="America/New_York (EST)" {{ ($preferences['timezone'] ?? '') === 'America/New_York (EST)' ? 'selected' : '' }}>America/New_York (EST)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Default Date Format</label>
                            <select name="date_format" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <option value="M j, Y, g:i A" {{ ($preferences['date_format'] ?? '') === 'M j, Y, g:i A' ? 'selected' : '' }}>MMM DD, YYYY (e.g. Jul 22, 2025)</option>
                                <option value="YYYY-MM-DD" {{ ($preferences['date_format'] ?? '') === 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                <option value="DD/MM/YYYY" {{ ($preferences['date_format'] ?? '') === 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-semibold text-xs rounded-lg transition duration-200 flex items-center gap-1.5">
                            <i class="fas fa-floppy-disk"></i>
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security & Password with Eye Buttons -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-lock text-green-700"></i>
                        Security & Authentication
                    </h2>
                    <span class="text-[11px] text-gray-400 font-normal">Account Password</span>
                </div>
                <form action="{{ route('password.update') }}" method="POST" class="p-5 space-y-4 text-xs">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" placeholder="••••••••" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 pr-10 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none" title="Toggle password visibility">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" placeholder="••••••••" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 pr-10 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none" title="Toggle password visibility">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 pr-10 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none" title="Toggle password visibility">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-semibold text-xs rounded-lg transition duration-200 flex items-center gap-1.5">
                            <i class="fas fa-shield-check"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Notifications Preferences -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-bell text-green-700"></i>
                        Notification Alerts
                    </h2>
                    <span class="text-[11px] text-gray-400 font-normal">System Alerts</span>
                </div>
                <form action="{{ route('settings.notifications') }}" method="POST" class="p-5 space-y-3.5 text-xs">
                    @csrf
                    <div class="flex items-center justify-between pt-1">
                        <div>
                            <p class="font-semibold text-gray-800">CRM Follow-Up Reminders</p>
                            <p class="text-[11px] text-gray-500">Receive alerts when customer tasks are due today or overdue.</p>
                        </div>
                        <input type="checkbox" name="crm_reminders" {{ !empty($notifications['crm_reminders']) ? 'checked' : '' }} class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div>
                            <p class="font-semibold text-gray-800">SPRF Deal Status Updates</p>
                            <p class="text-[11px] text-gray-500">Get notified when a procurement matching record status changes.</p>
                        </div>
                        <input type="checkbox" name="sprf_updates" {{ !empty($notifications['sprf_updates']) ? 'checked' : '' }} class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <div>
                            <p class="font-semibold text-gray-800">ASSCM Case Escalations</p>
                            <p class="text-[11px] text-gray-500">Immediate alerts for high-priority support case escalations.</p>
                        </div>
                        <input type="checkbox" name="asscm_escalations" {{ !empty($notifications['asscm_escalations']) ? 'checked' : '' }} class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                    </div>
                    <div class="pt-3 flex justify-end border-t border-gray-100">
                        <button type="submit" class="px-4 py-2 bg-green-700 hover:bg-green-800 text-white font-semibold text-xs rounded-lg transition duration-200 flex items-center gap-1.5">
                            <i class="fas fa-check"></i>
                            Save Notification Settings
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Right Column: System Info Card & Quick Specs -->
        <div class="space-y-6">

            <!-- System Info Summary -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-server text-green-700"></i>
                        System Environment
                    </h2>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Database Driver</span>
                        <span class="font-bold text-gray-800">MySQL (wampmysqld64)</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Database Host</span>
                        <span class="font-mono text-gray-700">127.0.0.1:3306</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">Framework</span>
                        <span class="font-semibold text-gray-800">Laravel 11.x</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-gray-100">
                        <span class="text-gray-500">CRM Storage Driver</span>
                        <span class="font-medium text-green-700 bg-green-50 px-2 py-0.5 rounded">CrmStorage (JSON + DB)</span>
                    </div>
                    <div class="flex justify-between py-1.5">
                        <span class="text-gray-500">Last System Sync</span>
                        <span class="text-gray-700">{{ now()->format('M j, Y, g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-3 text-xs">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-database text-green-700"></i>
                    System Maintenance
                </h3>
                <p class="text-gray-500 text-[11px]">Perform maintenance actions to optimize system response speeds.</p>
                <div class="pt-2 space-y-2">
                    <button class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition duration-200 text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-broom text-gray-500"></i>
                        Clear Cache & Rebuild Indices
                    </button>
                    <a href="{{ route('db-schema') }}" class="w-full py-2 bg-green-50 hover:bg-green-100 text-green-800 font-semibold rounded-lg transition duration-200 text-xs flex items-center justify-center gap-2">
                        <i class="fas fa-diagram-project text-green-600"></i>
                        View ERD Database Schema
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection
