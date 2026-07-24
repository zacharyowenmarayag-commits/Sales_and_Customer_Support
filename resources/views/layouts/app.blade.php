<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmbatuGrow')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        html {
            font-size: 14px !important;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans min-h-screen flex flex-col" style="font-family: 'Inter', sans-serif;">
    <div class="sticky top-0 z-50 shadow-sm">
        <header class="bg-green-700 border-b border-green-800 px-6 py-2.5 flex justify-between items-center">
            <div class="flex items-center space-x-2.5">
                <button id="mobile-sidebar-toggle" class="md:hidden text-white/80 hover:text-white focus:outline-none p-1 mr-1">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <img src="{{ asset('images/logo.png') }}" alt="AMBATUGROW Logo" class="w-7 h-7 object-contain bg-white rounded-full p-0.5">
                <span class="text-base font-bold tracking-wider text-white">AMBATUGROW</span>
            </div>
            <div class="relative w-72">
                <input type="text" id="globalHeaderSearch" value="{{ request('q') }}" placeholder="What are you looking for?" class="w-full bg-white/10 border border-white/20 rounded-lg px-3.5 py-1.5 text-xs text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/40 pr-9">
                <i class="fas fa-search absolute right-3 top-2.5 text-white/60 text-xs"></i>
            </div>

            @auth
                <div class="flex items-center gap-2.5">

                    <!-- Notification Bell Dropdown -->
                    @php
                        $settingsFile = storage_path('app/system_settings.json');
                        $settings = file_exists($settingsFile) ? (json_decode(file_get_contents($settingsFile), true) ?? []) : [];
                        $notifPrefs = $settings['notifications'] ?? [
                            'crm_reminders' => true,
                            'sprf_updates' => true,
                            'asscm_escalations' => true,
                        ];

                        $notifications = [];
                        try {
                            if (!empty($notifPrefs['crm_reminders'])) {
                                $followUps = \App\Support\CrmStorage::getFollowUps();
                                foreach ($followUps as $f) {
                                    $fArr = (array) $f;
                                    if (($fArr['status'] ?? 'Pending') !== 'Completed') {
                                        $notifications[] = [
                                            'icon' => 'fa-calendar-check text-green-600',
                                            'title' => 'CRM Follow-Up Due',
                                            'message' => ($fArr['task'] ?? 'Task') . ' (' . ($fArr['customer'] ?? 'Customer') . ')',
                                            'time' => 'Due Today',
                                            'link' => route('crm.dashboard'),
                                        ];
                                        if (count($notifications) >= 2) break;
                                    }
                                }
                            }

                            if (!empty($notifPrefs['asscm_escalations'])) {
                                $escalated = \App\Models\SupportCase::where('status', 'Escalated')->first();
                                if ($escalated) {
                                    $notifications[] = [
                                        'icon' => 'fa-triangle-exclamation text-amber-500',
                                        'title' => 'ASSCM Case Escalated',
                                        'message' => 'Issue: ' . $escalated->issue,
                                        'time' => 'Action Required',
                                        'link' => route('asscm'),
                                    ];
                                }
                            }

                            if (!empty($notifPrefs['sprf_updates'])) {
                                $notifications[] = [
                                    'icon' => 'fa-chart-bar text-blue-600',
                                    'title' => 'SPRF Procurement Sync',
                                    'message' => '20 pending matching records awaiting verification.',
                                    'time' => 'Active View',
                                    'link' => route('sprf'),
                                ];
                            }
                        } catch (\Throwable $e) {
                            // Silence error fallback
                        }
                    @endphp

                    <div class="relative">
                        <button id="notif-dropdown-btn" onclick="toggleNotifDropdown()" class="relative p-2 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-lg transition flex items-center justify-center">
                            <i class="fas fa-bell text-xs"></i>
                            @if(count($notifications) > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-extrabold rounded-full flex items-center justify-center border-2 border-green-700">
                                    {{ count($notifications) }}
                                </span>
                            @endif
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="notif-dropdown-panel" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 py-2 hidden z-50 text-gray-800 text-xs">
                            <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                                <span class="font-bold text-gray-900 flex items-center gap-1.5">
                                    <i class="fas fa-bell text-green-700"></i>
                                    Notifications
                                </span>
                                <a href="{{ route('settings') }}" class="text-[11px] text-green-700 hover:underline">Notification Settings</a>
                            </div>
                            <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                                @forelse($notifications as $n)
                                    <a href="{{ $n['link'] }}" class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center text-xs flex-shrink-0 mt-0.5">
                                            <i class="fas {{ $n['icon'] }}"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 text-xs truncate">{{ $n['title'] }}</p>
                                            <p class="text-[11px] text-gray-600 truncate mt-0.5">{{ $n['message'] }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ $n['time'] }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-400 text-xs">
                                        <i class="fas fa-bell-slash text-base mb-1 block text-gray-300"></i>
                                        No active notifications based on your settings preferences.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- User Profile Trigger Button -->
                    <button id="user-profile-trigger" onclick="toggleUserProfilePanel()" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 px-2.5 py-1.5 rounded-lg transition duration-200 group">
                        <!-- User Initials Avatar -->
                        <div class="w-7 h-7 bg-white/20 group-hover:bg-white/30 rounded-full flex items-center justify-center transition duration-200">
                            <span class="text-white text-[11px] font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1)) }}
                            </span>
                        </div>
                        <span class="text-white/80 text-xs font-medium hidden sm:inline">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-white/60 text-[10px] ml-0.5"></i>
                    </button>
                </div>
            @endauth
        </header>
    </div>

    <script>
        function toggleNotifDropdown() {
            const panel = document.getElementById('notif-dropdown-panel');
            if (panel) {
                panel.classList.toggle('hidden');
            }
        }

        document.addEventListener('click', (e) => {
            const btn = document.getElementById('notif-dropdown-btn');
            const panel = document.getElementById('notif-dropdown-panel');
            if (btn && panel && !btn.contains(e.target) && !panel.contains(e.target)) {
                panel.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            // Restore scroll position and input focus if the path matches the last saved path
            const savedPath = localStorage.getItem('scrollPathname');
            const savedScroll = localStorage.getItem('scrollPosition');
            if (savedPath === window.location.pathname) {
                if (savedScroll) {
                    window.scrollTo(0, parseInt(savedScroll, 10));
                }
                const activeElementId = localStorage.getItem('activeElementId');
                const cursorPosition = localStorage.getItem('cursorPosition');
                if (activeElementId) {
                    const el = document.getElementById(activeElementId);
                    if (el) {
                        el.focus();
                        if (cursorPosition !== null && el.setSelectionRange) {
                            const pos = parseInt(cursorPosition, 10);
                            el.setSelectionRange(pos, pos);
                        }
                    }
                    localStorage.removeItem('activeElementId');
                    localStorage.removeItem('cursorPosition');
                }
            }

            // Save scroll position and focused element before unloading the page
            window.addEventListener('beforeunload', () => {
                localStorage.setItem('scrollPosition', window.scrollY);
                localStorage.setItem('scrollPathname', window.location.pathname);
                if (document.activeElement && document.activeElement.id) {
                    localStorage.setItem('activeElementId', document.activeElement.id);
                    localStorage.setItem('cursorPosition', document.activeElement.selectionStart || 0);
                } else {
                    localStorage.removeItem('activeElementId');
                    localStorage.removeItem('cursorPosition');
                }
            });

            // Also save scroll position on scroll (debounced) to ensure correctness
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    localStorage.setItem('scrollPosition', window.scrollY);
                    localStorage.setItem('scrollPathname', window.location.pathname);
                }, 100);
            });

            const globalSearch = document.getElementById('globalHeaderSearch');
            if (globalSearch) {
                const triggerSearch = () => {
                    const query = globalSearch.value.trim();
                    const path = window.location.pathname.toLowerCase();
                    const url = new URL(window.location.href);

                    // Sync the global header search input to page-specific inputs if they exist,
                    // or redirect to correct search endpoints depending on section path.
                    if (query) {
                        url.searchParams.set('q', query);
                    } else {
                        url.searchParams.delete('q');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                };

                globalSearch.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        triggerSearch();
                    }
                });
            }

            // Scroll to the first matching element on page load
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('q');
            if (searchQuery) {
                setTimeout(() => {
                    // 1. First priority: highlighted data match
                    const dataMatch = document.querySelector('.text-blue-600');
                    if (dataMatch) {
                        dataMatch.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }

                    // 2. Second priority: search headings, labels, and section titles
                    const q = searchQuery.toLowerCase();
                    const candidates = document.querySelectorAll('h1, h2, h3, th, .label, .sprf-kpi-card .label, .sprf-panel h3');
                    for (const el of candidates) {
                        if (el.textContent.toLowerCase().includes(q)) {
                            // Scroll to the closest panel/section container, or the element itself
                            const section = el.closest('.sprf-panel, .sprf-kpi-card, .sprf-charts-row, .sprf-three-row, section, [class*="card"], [class*="panel"]') || el;
                            section.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            return;
                        }
                    }
                }, 300);
            }
        });
    </script>

    <div class="flex flex-1">
        @include('partials.sidebar')

        <main class="flex-1 min-w-0 md:ml-[4.75rem] p-5">
            @yield('content')
        </main>
    </div>

    @auth
        @include('partials.user-profile-panel')
    @endauth

    @stack('scripts')
</body>
</html>
