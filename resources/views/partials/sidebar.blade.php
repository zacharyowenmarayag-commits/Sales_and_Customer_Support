<aside class="sidebar-container bg-white border-r border-gray-200 py-3 flex flex-col justify-between flex-shrink-0 overflow-y-auto overflow-x-hidden">

    <div class="space-y-1">
        <!-- Dashboard Overview -->
        <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-gauge-high sidebar-icon"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- ASSCM -->
        <a href="{{ route('asscm') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('asscm') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-headset sidebar-icon"></i>
            <span class="sidebar-text">ASSCM</span>
        </a>

        <!-- SPRF Parent -->
        <a href="#" class="sidebar-parent sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('sprf') || request()->routeIs('sprf.*') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition" data-target="sprf-submenu">
            <i class="fas fa-chart-bar sidebar-icon"></i>
            <span class="sidebar-text">SPRF</span>
            <i class="fas fa-chevron-right sidebar-chevron transition-transform duration-200 text-xs ml-auto"></i>
        </a>
        <!-- SPRF Submenu -->
        <div id="sprf-submenu" class="submenu-container space-y-1 bg-gray-50/80 py-1">
            <a href="{{ route('sprf') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('sprf') && !request()->routeIs('sprf.deals') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="{{ route('sprf.deals') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('sprf.deals') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-tags sidebar-icon"></i>
                <span class="sidebar-text">Deals</span>
            </a>
        </div>

        <!-- SOM -->
        <a href="{{ route('som') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('som') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <span class="sidebar-text">SOM</span>
        </a>

        <!-- CRM Parent -->
        <a href="#" class="sidebar-parent sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('crm.*') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition" data-target="crm-submenu">
            <i class="fas fa-handshake sidebar-icon"></i>
            <span class="sidebar-text">CRM</span>
            <i class="fas fa-chevron-right sidebar-chevron transition-transform duration-200 text-xs ml-auto"></i>
        </a>
        <!-- CRM Submenu -->
        <div id="crm-submenu" class="submenu-container space-y-1 bg-gray-50/80 py-1">
            <a href="{{ route('crm.dashboard') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('crm.dashboard') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">CRM Dashboard</span>
            </a>
            <a href="{{ route('crm.purchaseHistory') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('crm.purchaseHistory') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-receipt sidebar-icon"></i>
                <span class="sidebar-text">Purchase History</span>
            </a>
            <a href="{{ route('crm.comlog') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('crm.comlog') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-comments sidebar-icon"></i>
                <span class="sidebar-text">Communication Logs</span>
            </a>
            <a href="{{ route('crm.followup') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('crm.followup') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-calendar-check sidebar-icon"></i>
                <span class="sidebar-text">Follow-Ups</span>
            </a>
            <a href="{{ route('crm.segmentation') }}" class="sidebar-link flex items-center px-4 py-2 {{ request()->routeIs('crm.segmentation') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold' : 'text-gray-600 hover:bg-gray-100' }} transition text-xs">
                <i class="fas fa-chart-pie sidebar-icon"></i>
                <span class="sidebar-text">Segmentation</span>
            </a>
        </div>

        <!-- Database Schema (ERD) -->
        <a href="{{ route('db-schema') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('db-schema') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-database sidebar-icon"></i>
            <span class="sidebar-text">Database Schema</span>
        </a>
    </div>

    <!-- Bottom Actions Section (Settings & Support) -->
    <div class="mt-auto pt-3 space-y-1 border-t border-gray-100">
        <!-- Settings -->
        <a href="{{ route('settings') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('settings') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-gear sidebar-icon"></i>
            <span class="sidebar-text">Settings</span>
        </a>
        <!-- Support -->
        <a href="{{ route('support') }}" class="sidebar-link flex items-center px-4 py-2.5 {{ request()->routeIs('support') ? 'bg-[#e8f5e9] text-[#1b5e20] font-semibold border-l-4 border-[#2e7d32]' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} transition">
            <i class="fas fa-circle-question sidebar-icon"></i>
            <span class="sidebar-text">Support</span>
        </a>
    </div>

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
            padding: 1rem 0 !important;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
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
            padding: 0.75rem 1.25rem !important;
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
            position: fixed;
            top: 52px;
            left: 0;
            height: calc(100vh - 52px);
            width: 4.75rem;
            z-index: 40;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.75rem 0 !important;
            box-shadow: none;
        }
        .sidebar-container:hover {
            width: 16rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }
        main {
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-container:hover + main {
            margin-left: 16rem !important;
        }
        .sidebar-container .sidebar-link {
            height: 2.75rem;
            padding-left: 1.375rem !important;
            padding-right: 1rem !important;
            justify-content: flex-start !important;
            font-size: 13.5px !important;
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            border-left: 4px solid transparent !important;
            box-sizing: border-box !important;
        }
        .sidebar-container .sidebar-link.bg-\[\#e8f5e9\] {
            border-left-color: #2e7d32 !important;
        }
        .sidebar-icon {
            width: 1.5rem !important;
            min-width: 1.5rem !important;
            height: 1.5rem !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 14px !important;
            flex-shrink: 0 !important;
            margin-right: 0.75rem !important;
        }
        .submenu-container .sidebar-link {
            font-size: 12px !important;
            height: 2.25rem;
            padding-left: 1.375rem !important;
        }
        .sidebar-container:not(:hover) .sidebar-text {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .sidebar-container:not(:hover) .sidebar-chevron {
            opacity: 0;
            visibility: hidden;
            display: none !important;
        }
        .sidebar-container .sidebar-text {
            transition: opacity 0.15s ease 0.05s, visibility 0.15s ease 0.05s;
        }
        .sidebar-container .sidebar-chevron {
            transition: opacity 0.15s ease 0.05s, visibility 0.15s ease 0.05s;
        }
        .sidebar-container:hover .sidebar-text,
        .sidebar-container:hover .sidebar-chevron {
            opacity: 1;
            visibility: visible;
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

        const activeSubLink = document.querySelector('.submenu-container .bg-[#e8f5e9]');
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
