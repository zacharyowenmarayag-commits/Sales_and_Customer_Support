@extends('layouts.app')

@section('title', 'AmbatuGrow - Sales Order Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center mb-1">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales orders</h1>
            <p class="text-sm text-gray-500 mt-1">Sales Order Management</p>
        </div>
        <div class="flex space-x-3 items-center">
            <div class="relative w-64">
                <input type="text" id="tableSearch" placeholder="Search by ID or customer" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-green-600 pl-8">
                <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
            </div>
            <a href="/som/create" class="bg-green-700 hover:bg-green-800 text-white px-4 py-1.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
                <i class="fas fa-plus mr-2 text-xs"></i> New order
            </a>
        </div>
    </div>

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

    <div class="flex space-x-2">
        <a href="/som" class="px-4 py-1 text-xs border border-gray-300 bg-gray-50 rounded-md font-semibold text-gray-600 hover:bg-gray-100 transition">All</a>
        <a href="/som?status=Pending" class="px-4 py-1 text-xs border border-amber-200 bg-amber-50 rounded-md font-semibold text-amber-600 hover:bg-amber-100 transition">Pending</a>
        <a href="/som?status=Processed" class="px-4 py-1 text-xs border border-blue-200 bg-blue-50 rounded-md font-semibold text-blue-600 hover:bg-blue-100 transition">Processed</a>
        <a href="/som?status=Shipped" class="px-4 py-1 text-xs border border-purple-200 bg-purple-50 rounded-md font-semibold text-purple-600 hover:bg-purple-100 transition">Shipped</a>
        <a href="/som?status=Delivered" class="px-4 py-1 text-xs border border-emerald-200 bg-emerald-50 rounded-md font-semibold text-emerald-600 hover:bg-emerald-100 transition">Delivered</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="py-4 px-6">Order ID</th>
                    <th class="py-4 px-6">Customer</th>
                    <th class="py-4 px-6">Date</th>
                    <th class="py-4 px-6 text-center">Items</th>
                    <th class="py-4 px-6 text-right">Total</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="orderTableBody" class="divide-y divide-gray-100 text-sm">
                @foreach ($orders as $order)
                    <tr class="order-row hover:bg-gray-50/70 transition">
                        <td class="order-id-col py-4 px-6 font-medium text-emerald-600">{{ $order['id'] }}</td>
                        <td class="customer-col py-4 px-6 font-semibold text-gray-700">{{ $order['customer'] }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $order['date'] }}</td>
                        <td class="py-4 px-6 text-center text-gray-500">{{ $order['items'] }}</td>
                        <td class="py-4 px-6 text-right font-bold text-gray-900">₱{{ number_format($order['total'], 2) }}</td>
                        <td class="py-4 px-6 text-center font-bold">
                            @switch($order['status'])
                                @case('Pending')
                                    <span class="text-amber-600">Pending</span>
                                    @break
                                @case('Processed')
                                    <span class="text-blue-600">Processed</span>
                                    @break
                                @case('Shipped')
                                    <span class="text-purple-600">Shipped</span>
                                    @break
                                @case('Delivered')
                                    <span class="text-emerald-600">Delivered</span>
                                    @break
                                @default
                                    <span class="text-gray-600">{{ $order['status'] }}</span>
                            @endswitch
                        </td>
                        <td class="py-4 px-6 text-center text-gray-400">
                            <button type="button" 
                                    data-id="{{ $order['id'] }}" 
                                    data-customer="{{ $order['customer'] }}" 
                                    data-date="{{ $order['date'] }}" 
                                    data-total="{{ $order['total'] }}" 
                                    data-status="{{ $order['status'] }}" 
                                    class="view-details-btn hover:text-gray-600 transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-xs font-semibold text-gray-500">
            <div>
                Showing <span class="font-bold text-gray-700">6</span> of <span class="font-bold text-gray-700">6</span> orders
            </div>
            
            <div class="inline-flex space-x-1">
                <button type="button" class="px-3 py-1.5 border border-gray-300 bg-white text-gray-400 rounded-lg cursor-not-allowed font-medium" disabled>
                    Previous
                </button>
                <button type="button" class="px-3 py-1.5 bg-green-700 text-white rounded-lg font-bold">
                    1
                </button>
                <button type="button" class="px-3 py-1.5 border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 rounded-lg font-medium transition">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<div id="orderModal" class="fixed inset-0 z-50 overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div id="modalBackdrop" class="fixed inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-gray-200">
            
            <div class="px-6 py-4 bg-gray-50/70 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <h3 id="modalOrderId" class="text-lg font-bold text-gray-900 tracking-tight">ORD-XXX</h3>
                    <span id="modalOrderStatus" class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border tracking-wide">
                        Pending
                    </span>
                </div>
                <button type="button" id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 rounded-xl p-4 border border-gray-100 text-xs font-semibold text-gray-500">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Customer Name</span>
                        <span id="modalCustomerName" class="text-gray-900 font-bold text-sm">--</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Sales Representative</span>
                        <span class="text-gray-800 font-medium">Ash Ketchum</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Order Date</span>
                        <span id="modalOrderDate" class="text-gray-800 font-medium">--</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Line Items Ordered</label>
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 font-bold text-gray-400 uppercase tracking-wider text-[10px]">
                                    <th class="py-2.5 px-4">Product Specification</th>
                                    <th class="py-2.5 px-4 text-center w-20">Quantity</th>
                                    <th class="py-2.5 px-4 text-right w-24">Unit Price</th>
                                    <th class="py-2.5 px-4 text-right w-24">Line Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-gray-800">Lucky Me! Pancit Canton — Chilimansi</div>
                                        <div class="text-[10px] text-gray-400 font-mono mt-0.5">LM-PC-001</div>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-gray-600">100</td>
                                    <td class="py-3 px-4 text-right text-gray-400">₱15.00</td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-900">₱1,500.00</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4">
                                        <div class="font-bold text-gray-800">Rebisco Crackers (10s pack)</div>
                                        <div class="text-[10px] text-gray-400 font-mono mt-0.5">RB-CR-010</div>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold text-gray-600">50</td>
                                    <td class="py-3 px-4 text-right text-gray-400">₱42.50</td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-900">₱2,125.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <div class="w-64 space-y-2 text-xs font-semibold text-gray-500">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="text-gray-900 font-bold">₱3,625.00</span>
                        </div>
                        <div class="flex justify-between pb-2 border-b border-gray-100">
                            <span>Tax Amount (12% VAT)</span>
                            <span class="text-gray-900 font-bold">₱435.00</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-1">
                            <span class="text-sm font-bold text-gray-800">Total Amount</span>
                            <span id="modalOrderTotal" class="text-lg font-black text-gray-950 tracking-tight">₱0.00</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="px-6 py-3.5 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="button" id="closeModalFooterBtn" class="border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold text-xs px-4 py-2 rounded-lg shadow-sm transition">
                    Close Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- SEARCH BAR CONTROLLER LOGIC ---
    const searchInput = document.getElementById('tableSearch');
    const tableRows = document.querySelectorAll('.order-row');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();

        tableRows.forEach(row => {
            const orderId = row.querySelector('.order-id-col').innerText.toLowerCase();
            const customerName = row.querySelector('.customer-col').innerText.toLowerCase();

            if (orderId.includes(searchTerm) || customerName.includes(searchTerm)) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        });
    });

    // --- MODAL DISPLAY CONTROLLER LOGIC ---
    const modal = document.getElementById('orderModal');
    const backdrop = document.getElementById('modalBackdrop');
    const closeBtn = document.getElementById('closeModalBtn');
    const closeFooterBtn = document.getElementById('closeModalFooterBtn');

    function openModal(button) {
        const orderId = button.getAttribute('data-id');
        const customer = button.getAttribute('data-customer');
        const date = button.getAttribute('data-date');
        const total = parseFloat(button.getAttribute('data-total')) || 0;
        const status = button.getAttribute('data-status');

        document.getElementById('modalOrderId').innerText = orderId;
        document.getElementById('modalCustomerName').innerText = customer;
        document.getElementById('modalOrderDate').innerText = date;
        document.getElementById('modalOrderTotal').innerText = '₱' + total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        const statusBadge = document.getElementById('modalOrderStatus');
        statusBadge.innerText = status;
        
        if (status === 'Pending') {
            statusBadge.className = "px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-amber-200 bg-amber-50 text-amber-600 tracking-wide";
        } else if (status === 'Processed') {
            statusBadge.className = "px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-blue-200 bg-blue-50 text-blue-600 tracking-wide";
        } else if (status === 'Shipped') {
            statusBadge.className = "px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-purple-200 bg-purple-50 text-purple-600 tracking-wide";
        } else {
            statusBadge.className = "px-2.5 py-0.5 text-[10px] font-bold uppercase rounded border border-emerald-200 bg-emerald-50 text-emerald-600 tracking-wide";
        }

        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-details-btn');
        if (btn) {
            openModal(btn);
        }
    });

    backdrop.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);
    closeFooterBtn.addEventListener('click', closeModal);
});
</script>
@endsection