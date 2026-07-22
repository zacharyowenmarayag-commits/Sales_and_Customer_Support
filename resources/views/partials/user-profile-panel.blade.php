<!-- User Profile Panel/Modal -->
<div id="user-profile-panel" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" onclick="toggleUserProfilePanel()"></div>
    
    <!-- Panel -->
    <div class="absolute right-0 top-0 h-full w-96 bg-gradient-to-b from-[#1a2332] to-[#0f1419] shadow-2xl overflow-y-auto">
        <!-- Close Button -->
        <button onclick="toggleUserProfilePanel()" class="absolute top-4 right-4 text-white/60 hover:text-white transition z-10">
            <i class="fas fa-times text-xl"></i>
        </button>

        <!-- Profile Header -->
        <div class="bg-gradient-to-b from-[#1e2a3a] to-[#1a2332] px-6 py-8 text-center border-b border-white/10">
            <!-- User Avatar/Initials -->
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center border-4 border-white/20">
                <span class="text-white text-3xl font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}</span>
            </div>
            
            <!-- User Name -->
            <h2 class="text-white text-xl font-bold mb-1">{{ Auth::user()->name }}</h2>
            
            <!-- User Role/Title -->
            <p class="text-green-400 text-sm font-semibold uppercase tracking-wide">
                {{ Auth::user()->title ?? Auth::user()->role ?? 'User' }}
            </p>
        </div>

        <!-- Profile Details -->
        <div class="px-6 py-6 space-y-5">
            <!-- Username Field -->
            <div class="bg-[#0f1419]/50 rounded-lg p-4 border border-white/10">
                <label class="text-white/60 text-xs font-semibold uppercase tracking-wider block mb-2">Username</label>
                <p class="text-white text-sm font-medium">{{ Auth::user()->username ?? 'N/A' }}</p>
            </div>

            <!-- Email Address Field -->
            <div class="bg-[#0f1419]/50 rounded-lg p-4 border border-white/10">
                <label class="text-white/60 text-xs font-semibold uppercase tracking-wider block mb-2">Email Address</label>
                <p class="text-white text-sm font-medium break-all">{{ Auth::user()->email }}</p>
            </div>

            <!-- Department Field -->
            <div class="bg-[#0f1419]/50 rounded-lg p-4 border border-white/10">
                <label class="text-white/60 text-xs font-semibold uppercase tracking-wider block mb-2">Department</label>
                <p class="text-white text-sm font-medium">{{ Auth::user()->department ?? 'N/A' }}</p>
            </div>

            <!-- Role Field -->
            <div class="bg-[#0f1419]/50 rounded-lg p-4 border border-white/10">
                <label class="text-white/60 text-xs font-semibold uppercase tracking-wider block mb-2">Role</label>
                <p class="text-white text-sm font-medium capitalize">{{ Auth::user()->role ?? 'User' }}</p>
            </div>

            <!-- Member Since -->
            <div class="bg-[#0f1419]/50 rounded-lg p-4 border border-white/10">
                <label class="text-white/60 text-xs font-semibold uppercase tracking-wider block mb-2">Member Since</label>
                <p class="text-white text-sm font-medium">{{ Auth::user()->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-6 space-y-3 border-t border-white/10">
            <button onclick="window.location.href='{{ route('profile.edit') ?? '#' }}'" class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold transition duration-200 flex items-center justify-center gap-2">
                <i class="fas fa-edit text-sm"></i>
                Edit Profile
            </button>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg font-semibold transition duration-200 flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                    Log Out Account
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleUserProfilePanel() {
        const panel = document.getElementById('user-profile-panel');
        panel.classList.toggle('hidden');
        
        // Add transition class for smooth appearance
        if (!panel.classList.contains('hidden')) {
            panel.querySelector('.absolute.right-0').classList.add('animate-slide-in-right');
        }
    }

    // Close panel when clicking outside
    document.addEventListener('click', function(event) {
        const panel = document.getElementById('user-profile-panel');
        const trigger = document.getElementById('user-profile-trigger');
        
        if (panel && trigger && !panel.contains(event.target) && !trigger.contains(event.target)) {
            if (!panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
            }
        }
    });

    // Close panel on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const panel = document.getElementById('user-profile-panel');
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
            }
        }
    });
</script>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
        }
        to {
            transform: translateX(0);
        }
    }

    .animate-slide-in-right {
        animation: slideInRight 0.3s ease-out;
    }
</style>
