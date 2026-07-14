<aside class="w-72 bg-white border-r border-gray-200 p-4 space-y-4 flex-shrink-0">
    <div class="rounded-2xl bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-700">Primary Navigation</div>

    <div class="space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-home w-5"></i> <span>ASSCM</span>
        </a>
        <a href="{{ route('sprf') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('sprf') || request()->routeIs('sprf.*') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-chart-bar w-5"></i> <span>SPRF</span>
        </a>
        <a href="{{ route('som') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('som') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-file-invoice w-5"></i> <span>SOM</span>
        </a>
        <a href="{{ route('crm.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.*') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-handshake w-5"></i> <span>CRM</span>
        </a>
    </div>

    <div class="rounded-2xl bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-700">CRM Navigation</div>
    <div class="space-y-2">
        <a href="{{ route('crm.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.dashboard') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-tachometer-alt w-5"></i> <span>CRM Dashboard</span>
        </a>
        <a href="{{ route('crm.customers') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.customers') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-users w-5"></i> <span>Customers</span>
        </a>
        <a href="{{ route('crm.purchaseHistory') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.purchaseHistory') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-receipt w-5"></i> <span>Purchase History</span>
        </a>
        <a href="{{ route('crm.comlog') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.comlog') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-comments w-5"></i> <span>Communication Logs</span>
        </a>
        <a href="{{ route('crm.followup') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.followup') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-calendar-check w-5"></i> <span>Follow-Ups</span>
        </a>
        <a href="{{ route('crm.segmentation') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl {{ request()->routeIs('crm.segmentation') ? 'bg-green-700 text-white' : 'text-gray-600 hover:bg-gray-50' }} transition font-medium">
            <i class="fas fa-chart-pie w-5"></i> <span>Segmentation</span>
        </a>
    </div>
</aside>
