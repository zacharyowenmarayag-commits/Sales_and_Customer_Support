<aside class="sidebar-container bg-white border-r border-gray-200 p-4 space-y-4 flex-shrink-0 overflow-y-auto overflow-x-hidden">


    <div class="space-y-2">
        <!-- Dashboard Overview -->
        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-gauge-high w-5 flex-shrink-0 sidebar-icon mr-3"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- ASSCM -->
        <a href="{{ route('asscm') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('asscm') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-headset w-5 flex-shrink-0 sidebar-icon mr-3"></i>
            <span class="sidebar-text">ASSCM</span>
        </a>

        <!-- SPRF Parent -->
        <a href="#" class="sidebar-parent sidebar-link flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('sprf') || request()->routeIs('sprf.*') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium" data-target="sprf-submenu">
            <div class="flex items-center">
                <i class="fas fa-chart-bar w-5 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">SPRF</span>
            </div>
            <i class="fas fa-chevron-right sidebar-chevron transition-transform duration-200 text-xs ml-auto"></i>
        </a>
        <!-- SPRF Submenu -->
        <div id="sprf-submenu" class="submenu-container space-y-1 pl-4">
            <a href="{{ route('sprf') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('sprf') && !request()->routeIs('sprf.deals') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-chart-line w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('sprf.deals') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('sprf.deals') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-tags w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Deals</span>
            </a>
        </div>

        <!-- SOM -->
        <a href="{{ route('som') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('som') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-file-invoice w-5 flex-shrink-0 sidebar-icon mr-3"></i>
            <span class="sidebar-text">SOM</span>
        </a>

        <!-- CRM Parent -->
        <a href="#" class="sidebar-parent sidebar-link flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('crm.*') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium" data-target="crm-submenu">
            <div class="flex items-center">
                <i class="fas fa-handshake w-5 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">CRM</span>
            </div>
            <i class="fas fa-chevron-right sidebar-chevron transition-transform duration-200 text-xs ml-auto"></i>
        </a>
        <!-- CRM Submenu -->
        <div id="crm-submenu" class="submenu-container space-y-1 pl-4">
            <a href="{{ route('crm.dashboard') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.dashboard') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-tachometer-alt w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">CRM Dashboard</span>
            </a>
            <a href="{{ route('crm.customers') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.customers') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-users w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Customers</span>
            </a>
            <a href="{{ route('crm.purchaseHistory') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.purchaseHistory') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-receipt w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Purchase History</span>
            </a>
            <a href="{{ route('crm.comlog') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.comlog') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-comments w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Communication Logs</span>
            </a>
            <a href="{{ route('crm.followup') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.followup') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-calendar-check w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Follow-Ups</span>
            </a>
            <a href="{{ route('crm.segmentation') }}" class="sidebar-link flex items-center px-4 py-2 rounded-lg {{ request()->routeIs('crm.segmentation') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium text-sm">
                <i class="fas fa-chart-pie w-4 flex-shrink-0 sidebar-icon mr-3"></i>
                <span class="sidebar-text">Segmentation</span>
            </a>
        </div>

        <!-- Database Schema (ERD) -->
        <a href="{{ route('db-schema') }}" class="sidebar-link flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('db-schema') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-database w-5 flex-shrink-0 sidebar-icon mr-3"></i>
            <span class="sidebar-text">Database Schema</span>
        </a>
    </div>

    @auth
    @php
        $avatarColor = substr(md5(Auth::user()->email), 0, 6);
        $nameParts = explode(' ', Auth::user()->name);
        if (count($nameParts) >= 2) {
            $initials = strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1));
        } else {
            $initials = strtoupper(substr(Auth::user()->name, 0, 2));
        }
    @endphp
    <div class="mt-auto pt-3 border-t border-gray-200">
        <div class="flex items-center px-4 py-2.5 rounded-lg cursor-default transition hover:bg-gray-50" title="{{ Auth::user()->name }}">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0" style="border: 2px solid #{{ $avatarColor }}; color: #{{ $avatarColor }};">
                {{ $initials }}
            </div>
            <span class="sidebar-text ml-3 text-sm font-semibold text-gray-700 truncate">{{ Auth::user()->name }}</span>
        </div>
    @endauth
</aside>

<div id="sidebar-backdrop" class="fixed inset-0 bg-black/40 z-40 hidden opacity-0 transition-opacity duration-300 md:hidden"></div>

<style>
    @media (max-width: 767px) {
        .sidebar-container {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            width: 18rem !important;
            z-index: 50 !important;
            padding: 1rem !important;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
        }
        .sidebar-container.mobile-open {
            transform: translateX(0);
        }
        .sidebar-container .sidebar-text,
        .sidebar-container .sidebar-header,
        .sidebar-container .sidebar-chevron {
            opacity: 1 !important;
            visibility: visible !important;
        }
        .sidebar-container .sidebar-link {
            width: 100% !important;
            padding: 0.75rem 1rem !important;
            justify-content: flex-start !important;
        }
        .sidebar-container .sidebar-icon {
            margin-right: 0.75rem !important;
        }
        .sidebar-container .sidebar-chevron {
            display: inline-block !important;
        }
    }

    @media (min-width: 768px) {
        .sidebar-container {
            position: sticky;
            top: 65px;
            height: calc(100vh - 65px);
            width: 5rem;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1rem !important;
            box-shadow: none;
        }
        .sidebar-container:hover {
            width: 18rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .sidebar-container .sidebar-link {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            justify-content: flex-start !important;
        }
        .sidebar-container:not(:hover) .sidebar-icon {
            margin-right: 0 !important;
        }
        .sidebar-container:not(:hover) .sidebar-header {
            display: none;
        }
        .sidebar-container:not(:hover) .sidebar-chevron {
            display: none !important;
        }
        .sidebar-container .sidebar-text,
        .sidebar-container .sidebar-header,
        .sidebar-container .sidebar-chevron {
            opacity: 0;
            visibility: hidden;
            white-space: nowrap;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }
        .sidebar-container:hover .sidebar-text,
        .sidebar-container:hover .sidebar-header,
        .sidebar-container:hover .sidebar-chevron {
            opacity: 1;
            visibility: visible;
            transition-delay: 0.1s;
        }
    }

    .submenu-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
        opacity: 0;
    }
    .submenu-container.open {
        opacity: 1;
    }
    @media (min-width: 768px) {
        .sidebar-container:not(:hover) .submenu-container {
            max-height: 0 !important;
            opacity: 0 !important;
            padding-left: 0 !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const parentItems = document.querySelectorAll('.sidebar-parent');
        parentItems.forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetSubmenu = document.getElementById(targetId);
                const chevron = this.querySelector('.sidebar-chevron');

                document.querySelectorAll('.submenu-container').forEach(sub => {
                    if (sub.id !== targetId) {
                        sub.classList.remove('open');
                        sub.style.maxHeight = null;
                        const otherParent = document.querySelector(`[data-target="${sub.id}"]`);
                        if (otherParent) {
                            const otherChevron = otherParent.querySelector('.sidebar-chevron');
                            if (otherChevron) otherChevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });

                if (targetSubmenu.classList.contains('open')) {
                    targetSubmenu.classList.remove('open');
                    targetSubmenu.style.maxHeight = null;
                    if (chevron) chevron.style.transform = 'rotate(0deg)';
                } else {
                    targetSubmenu.classList.add('open');
                    targetSubmenu.style.maxHeight = targetSubmenu.scrollHeight + "px";
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                }
            });
        });

        const activeSubLink = document.querySelector('.submenu-container .bg-green-700');
        if (activeSubLink) {
            const parentSubmenu = activeSubLink.closest('.submenu-container');
            if (parentSubmenu) {
                parentSubmenu.classList.add('open');
                parentSubmenu.style.maxHeight = parentSubmenu.scrollHeight + "px";
                const parentButton = document.querySelector(`[data-target="${parentSubmenu.id}"]`);
                if (parentButton) {
                    const chevron = parentButton.querySelector('.sidebar-chevron');
                    if (chevron) chevron.style.transform = 'rotate(90deg)';
                }
            }
        }

        const toggleBtn = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.querySelector('.sidebar-container');
        const backdrop = document.getElementById('sidebar-backdrop');

        if (toggleBtn && sidebar && backdrop) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.add('mobile-open');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.add('opacity-100'), 10);
            });

            backdrop.addEventListener('click', function () {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            });
        }
    });
</script>
