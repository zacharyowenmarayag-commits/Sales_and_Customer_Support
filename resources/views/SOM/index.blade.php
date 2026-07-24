@extends('layouts.app')

@section('title', 'AmbatuGrow - Sales Order Management')

@section('content')
<div class="space-y-5">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-1">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Sales Order Management</h1>
            <p class="text-xs text-gray-500 mt-1">Handles the entire sales order lifecycle–from quotation to order fulfillment–ensuring smooth and accurate transaction processing.</p>
        </div>
        <div class="flex space-x-2.5 items-center">
            <div class="relative w-60">
                <input type="text" id="somSearch" name="q" value="{{ request('q') }}" placeholder="Search orders..." class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-green-600 pl-8">
                <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
            </div>
            <a href="{{ route('som.new-order') }}" class="bg-green-700 hover:bg-green-800 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold flex items-center shadow-sm transition gap-1.5">
                <i class="fas fa-plus text-[10px]"></i> New Order
            </a>
        </div>
    </div>

    <!-- Status summary cards (100% identical to ASSCM) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border-l-4 border-amber-400 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Pending</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Pending'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-blue-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Processed</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Processed'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-purple-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Shipped</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Shipped'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-emerald-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Delivered</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Delivered'] }}</span>
        </div>
    </div>

    <!-- Status Filters (JS client-side, no page reload) -->
    <div class="flex flex-wrap gap-2" id="somFilterRow">
        <button onclick="filterOrders('', this)" data-filter=""
            data-inactive="border-gray-200 bg-white text-gray-600"
            data-active="bg-gray-700 text-white border-gray-700"
            class="som-filter-btn active px-3 py-1 text-xs border rounded-lg font-semibold transition bg-gray-700 text-white border-gray-700">All</button>
        <button onclick="filterOrders('Pending', this)" data-filter="Pending"
            data-inactive="border-amber-200 bg-amber-50 text-amber-600"
            data-active="bg-amber-500 text-white border-amber-500"
            class="som-filter-btn px-3 py-1 text-xs border rounded-lg font-semibold transition border-amber-200 bg-amber-50 text-amber-600">Pending</button>
        <button onclick="filterOrders('Processed', this)" data-filter="Processed"
            data-inactive="border-blue-200 bg-blue-50 text-blue-600"
            data-active="bg-blue-600 text-white border-blue-600"
            class="som-filter-btn px-3 py-1 text-xs border rounded-lg font-semibold transition border-blue-200 bg-blue-50 text-blue-600">Processed</button>
        <button onclick="filterOrders('Shipped', this)" data-filter="Shipped"
            data-inactive="border-purple-200 bg-purple-50 text-purple-600"
            data-active="bg-purple-600 text-white border-purple-600"
            class="som-filter-btn px-3 py-1 text-xs border rounded-lg font-semibold transition border-purple-200 bg-purple-50 text-purple-600">Shipped</button>
        <button onclick="filterOrders('Delivered', this)" data-filter="Delivered"
            data-inactive="border-emerald-200 bg-emerald-50 text-emerald-600"
            data-active="bg-emerald-600 text-white border-emerald-600"
            class="som-filter-btn px-3 py-1 text-xs border rounded-lg font-semibold transition border-emerald-200 bg-emerald-50 text-emerald-600">Delivered</button>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 font-bold text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-4">Order ID</th>
                    <th class="py-3 px-4">Customer</th>
                    <th class="py-3 px-4">Date</th>
                    <th class="py-3 px-4 text-center">Items</th>
                    <th class="py-3 px-4 text-right">Total</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="orderTableBody" class="divide-y divide-gray-100 text-xs">
                @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50/70 transition som-order-row" data-status="{{ $order->status }}">
                        <td class="py-3 px-4 font-semibold text-emerald-700">{{ $order->order_id }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800">
                            {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Unknown Customer' }}
                        </td>
                        <td class="py-3 px-4 text-gray-500">
                            {{ $order->created_at ? $order->created_at->format('M j, Y') : '—' }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600">
                            {{ count($order->items ?? []) }}
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-gray-900">
                            ₱{{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $badgeClass = match($order->status) {
                                    'Pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Processed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Shipped'   => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'Delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    default     => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold border rounded-full {{ $badgeClass }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button onclick="openOrderModal('{{ $order->order_id }}')" class="text-green-700 hover:text-green-900 font-semibold text-xs hover:underline">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-3 border-t border-gray-100">
            @include('partials.pagination', ['paginator' => $orders])
        </div>
    </div>
</div>

<!-- Order Detail Modal -->
<div id="orderModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-200">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-5 border border-gray-200 text-xs">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200 mb-4">
            <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
                <i class="fas fa-receipt text-green-700"></i>
                Order Details — <span id="modalOrderId" class="text-green-700"></span>
            </h3>
            <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600 text-sm focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="space-y-3">
            <p class="text-gray-500">Loading order items...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.applyQuerySearch('somSearch', window.location.href);

    // Smooth client-side filter (no page reload)
    const somFilterColors = {
        '':          { inactive: 'border-gray-200 bg-white text-gray-600',         active: 'bg-gray-700 text-white border-gray-700' },
        'Pending':   { inactive: 'border-amber-200 bg-amber-50 text-amber-600',    active: 'bg-amber-500 text-white border-amber-500' },
        'Processed': { inactive: 'border-blue-200 bg-blue-50 text-blue-600',       active: 'bg-blue-600 text-white border-blue-600' },
        'Shipped':   { inactive: 'border-purple-200 bg-purple-50 text-purple-600', active: 'bg-purple-600 text-white border-purple-600' },
        'Delivered': { inactive: 'border-emerald-200 bg-emerald-50 text-emerald-600', active: 'bg-emerald-600 text-white border-emerald-600' },
    };

    function filterOrders(status, btn) {
        // Reset all pills to their inactive color classes
        document.querySelectorAll('.som-filter-btn').forEach(b => {
            b.dataset.inactive.split(' ').forEach(c => { if(c) b.classList.add(c); });
            b.dataset.active.split(' ').forEach(c => { if(c) b.classList.remove(c); });
        });
        // Apply active color classes to clicked pill
        btn.dataset.active.split(' ').forEach(c => { if(c) btn.classList.add(c); });
        btn.dataset.inactive.split(' ').forEach(c => { if(c) btn.classList.remove(c); });

        // Show/hide rows
        document.querySelectorAll('.som-order-row').forEach(row => {
            row.style.display = (!status || row.dataset.status === status) ? '' : 'none';
        });
    }

    function openOrderModal(orderId) {
        const modal = document.getElementById('orderModal');
        const modalId = document.getElementById('modalOrderId');
        const modalContent = document.getElementById('modalContent');
        if (!modal) return;
        modalId.innerText = orderId;
        modalContent.innerHTML = '<p class="text-gray-500 py-4 text-center">Loading details...</p>';
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }

    function closeOrderModal() {
        const modal = document.getElementById('orderModal');
        if (!modal) return;
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
</script>
@endpush
