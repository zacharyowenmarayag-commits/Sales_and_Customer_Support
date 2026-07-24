@extends('layouts.app')

@section('title', 'AmbatuGrow - New Sales Order')

@section('content')
<div class="space-y-6">
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="/som" class="flex items-center space-x-1 hover:text-gray-700 transition">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Back to sales orders</span>
        </a>
    </div>

    <div class="flex items-center space-x-3">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">New sales order</h1>
        <span class="px-2 py-0.5 text-xs bg-gray-100 border border-gray-300 rounded text-gray-500 font-medium uppercase tracking-wider scale-90 origin-left">Draft / Pending</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Customer</label>
                <div class="relative">
                    <select class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm appearance-none focus:outline-none focus:ring-1 focus:ring-green-600 text-gray-600 pr-10">
                        <option value="" disabled>Search customer name</option>
                        <option value="1">Jollipop Foods Corporation</option>
                        <option value="2" selected>SM Retail Inc.</option>
                        <option value="3">Puregold Price Club</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-4 text-gray-400 text-sm pointer-events-none"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Line Items</span>
                    <span class="text-xs font-semibold text-gray-500">3 items</span>
                </div>
                
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-[11px] font-bold text-gray-400 uppercase tracking-wider bg-gray-50/30">
                            <th class="py-3 px-5">Product</th>
                            <th class="py-3 px-5 text-center w-32">Available Stock</th>
                            <th class="py-3 px-5 text-center w-24">Quantity</th>
                            <th class="py-3 px-5 text-right w-28">Unit Price</th>
                            <th class="py-3 px-5 text-right w-28">Line Total</th>
                            <th class="py-3 px-5 w-12"></th> </tr>
                        </tr>
                    </thead>
                    <tbody id="itemsContainer" class="divide-y divide-gray-100 text-sm">
                        <tr class="product-row" data-stock="500" data-price="15.00">
                            <td class="py-4 px-5">
                                <div class="font-semibold text-gray-800">Lucky Me! Pancit Canton — Chilimansi</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">LM-PC-001</div>
                            </td>
                            <td class="py-4 px-5 text-center text-gray-400 font-medium">500</td>
                            <td class="py-4 px-5 text-center">
                                <input type="number" value="120" class="qty-input w-16 border border-gray-300 rounded px-2 py-1 text-center font-medium text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
                            </td>
                            <td class="py-4 px-5 text-right text-gray-400 font-medium">₱15.00</td>
                            <td class="py-4 px-5 text-right font-bold text-gray-800 line-total">₱1,800.00</td>
                        </tr>

                        <tr class="product-row" data-stock="150" data-price="42.50">
                            <td class="py-4 px-5">
                                <div class="font-semibold text-gray-800">Rebisco Crackers (10s pack)</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">RB-CR-010</div>
                                <div class="stock-alert text-red-500 text-xs font-medium hidden items-center mt-1.5">
                                    <i class="fas fa-exclamation-circle mr-1 text-[10px]"></i> Only 150 units available — please reduce quantity
                                </div>
                            </td>
                            <td class="py-4 px-5 text-center stock-display font-medium text-gray-400">150</td>
                            <td class="py-4 px-5 text-center">
                                <input type="number" value="50" class="qty-input w-16 border border-gray-300 rounded px-2 py-1 text-center font-medium text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
                            </td>
                            <td class="py-4 px-5 text-right text-gray-400 font-medium">₱42.50</td>
                            <td class="py-4 px-5 text-right font-bold text-gray-800 line-total">₱2,125.00</td>
                        </tr>

                        <tr class="product-row" data-stock="80" data-price="35.00">
                            <td class="py-4 px-5">
                                <div class="font-semibold text-gray-800">Chippy BBQ Flavored Corn Chips (110 g)</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">CH-BB-001</div>
                                <div class="stock-alert text-red-500 text-xs font-medium hidden items-center mt-1.5">
                                    <i class="fas fa-exclamation-circle mr-1 text-[10px]"></i> Only 80 units available — please reduce quantity
                                </div>
                            </td>
                            <td class="py-4 px-5 text-center stock-display font-medium text-gray-400">80</td>
                            <td class="py-4 px-5 text-center">
                                <input type="number" value="20" class="qty-input w-16 border border-gray-300 rounded px-2 py-1 text-center font-medium text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
                            </td>
                            <td class="py-4 px-5 text-right text-gray-400 font-medium">₱35.00</td>
                            <td class="py-4 px-5 text-right font-bold text-gray-800 line-total">₱700.00</td>
                        </tr>
                    </tbody>
                </table>

                <div class="p-4 bg-white border-t border-gray-100">
                    <button type="button" id="addItemBtn" class="border border-green-600 text-green-700 hover:bg-green-50 px-4 py-1.5 rounded-lg text-sm font-semibold flex items-center shadow-sm transition">
                        <i class="fas fa-plus mr-2 text-xs"></i> Add Item
                    </button>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-3">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Discount (%)</label>
                <div class="flex items-center space-x-3">
                    <input type="number" id="discountInput" value="0" min="0" max="100" class="w-24 border border-gray-300 rounded-lg px-3 py-1.5 text-center font-bold text-gray-800 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
                    <span class="text-xs text-gray-400 font-medium">Applied to subtotal before 12% VAT</span>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5 space-y-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide">Order Summary</h3>
                
                <div class="space-y-3 border-b border-gray-100 pb-4 text-sm">
                    <div class="flex justify-between font-semibold text-gray-600">
                        <span>Subtotal</span>
                        <span id="summarySubtotal" class="text-gray-900">₱0.00</span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-600">
                        <span>VAT (12%)</span>
                        <span id="summaryVat" class="text-gray-900">₱0.00</span>
                    </div>
                </div>

                <div class="flex justify-between items-baseline">
                    <span class="text-sm font-bold text-gray-700">Total</span>
                    <div class="text-right">
                        <div id="summaryTotal" class="text-2xl font-black text-gray-900 tracking-tight">₱0.00</div>
                        <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider block mt-0.5">VAT-Inclusive</span>
                    </div>
                </div>

                <div class="space-y-2.5 pt-2">
                    <button id="confirmBtn" class="w-full text-white font-bold text-sm py-2.5 rounded-lg shadow-sm transition">
                        Confirm Order
                    </button>
                    <button class="w-full border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 font-semibold text-sm py-2.5 rounded-lg shadow-sm transition">
                        Save as draft
                    </button>
                </div>

                <div id="globalAlert" class="bg-red-50 border border-red-200 rounded-lg p-3 text-red-600 flex items-start space-x-2 text-xs font-semibold leading-normal">
                    <i class="fas fa-info-circle text-xs mt-0.5 flex-shrink-0"></i>
                    <span>2 items exceed available stock. Adjust quantities to confirm.</span>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-xs space-y-3 font-semibold text-gray-500">
                <div class="flex justify-between">
                    <span class="text-gray-400">Order date</span>
                    <span class="text-gray-800">July 1, 2026</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Created by</span>
                    <span class="text-gray-800">Ash Ketchum</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Branch</span>
                    <span class="text-gray-800">Cavite branch</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Payment terms</span>
                    <span class="text-gray-800">Net 30 days</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const discountInput = document.getElementById('discountInput');
    const globalAlert = document.getElementById('globalAlert');
    const confirmBtn = document.getElementById('confirmBtn');

    const productCatalog = [
        { name: "SkyFlakes Crackers (M-Pack)", code: "SF-CR-002", stock: 300, price: 45.00 },
        { name: "Nissin Cup Noodles — Seafood", code: "NS-CN-005", stock: 250, price: 22.00 },
        { name: "Piattos Cheese Flavored Chips (85g)", code: "PT-CH-003", stock: 180, price: 38.50 },
        { name: "C2 Cool & Clean Green Tea (500ml)", code: "C2-GT-001", stock: 400, price: 20.00 }
    ];
    let catalogIndex = 0;

    function calculateOrder() {
        const rows = document.querySelectorAll('.product-row');
        let subtotal = 0;
        let totalOverstockItems = 0;
        let activeItemCount = rows.length;

        rows.forEach(row => {
            const stock = parseInt(row.getAttribute('data-stock'));
            const price = parseFloat(row.getAttribute('data-price'));
            const qtyInput = row.querySelector('.qty-input');
            const alertBox = row.querySelector('.stock-alert');
            const stockDisplay = row.querySelector('.stock-display');
            
            let qty = parseInt(qtyInput.value) || 0;
            if (qty < 0) { qty = 0; qtyInput.value = 0; }

            const lineTotal = qty * price;
            subtotal += lineTotal;
            row.querySelector('.line-total').innerText = '₱' + lineTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            if (qty > stock) {
                totalOverstockItems++;
                if (alertBox) alertBox.classList.remove('hidden');
                if (alertBox) alertBox.classList.add('flex');
                if (stockDisplay) stockDisplay.className = "py-4 px-5 text-center stock-display font-bold text-red-500";
                qtyInput.className = "qty-input w-16 border border-red-400 bg-red-50 rounded px-2 py-1 text-center font-bold text-red-600 text-sm focus:outline-none";
                row.classList.add('bg-red-50/10');
            } else {
                if (alertBox) alertBox.classList.add('hidden');
                if (alertBox) alertBox.classList.remove('flex');
                if (stockDisplay) stockDisplay.className = "py-4 px-5 text-center stock-display font-medium text-gray-400";
                qtyInput.className = "qty-input w-16 border border-gray-300 rounded px-2 py-1 text-center font-medium text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-green-600";
                row.classList.remove('bg-red-50/10');
            }
        });

        const discountPercent = parseFloat(discountInput.value) || 0;
        const discountAmount = subtotal * (discountPercent / 100);
        const subtotalAfterDiscount = subtotal - discountAmount;

        const vat = subtotalAfterDiscount * 0.12;
        const finalTotal = subtotalAfterDiscount + vat;

        document.getElementById('summarySubtotal').innerText = '₱' + subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('summaryVat').innerText = '₱' + vat.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('summaryTotal').innerText = '₱' + finalTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.querySelector('.text-xs.font-semibold.text-gray-500').innerText = `${activeItemCount} item(s)`;

        if (totalOverstockItems > 0) {
            globalAlert.classList.remove('hidden');
            globalAlert.querySelector('span').innerText = `${totalOverstockItems} item(s) exceed available stock. Adjust quantities to confirm.`;
            confirmBtn.className = "w-full bg-green-700/50 text-white font-bold text-sm py-2.5 rounded-lg shadow-sm cursor-not-allowed";
            confirmBtn.disabled = true;
        } else {
            globalAlert.classList.add('hidden');
            confirmBtn.className = "w-full bg-green-700 hover:bg-green-800 text-white font-bold text-sm py-2.5 rounded-lg shadow-sm transition";
            confirmBtn.disabled = false;
        }
    }

    // Add Item Click Event
    addItemBtn.addEventListener('click', function() {
        const product = productCatalog[catalogIndex % productCatalog.length];
        catalogIndex++;

        const newRowHTML = `
            <tr class="product-row animate-fade-in" data-stock="${product.stock}" data-price="${product.price.toFixed(2)}">
                <td class="py-4 px-5">
                    <div class="font-semibold text-gray-800">${product.name}</div>
                    <div class="text-xs text-gray-400 font-mono mt-0.5">${product.code}</div>
                    <div class="stock-alert text-red-500 text-xs font-medium hidden items-center mt-1.5">
                        <i class="fas fa-exclamation-circle mr-1 text-[10px]"></i> Only ${product.stock} units available — please reduce quantity
                    </div>
                </td>
                <td class="py-4 px-5 text-center stock-display font-medium text-gray-400">${product.stock}</td>
                <td class="py-4 px-5 text-center">
                    <input type="number" value="1" class="qty-input w-16 border border-gray-300 rounded px-2 py-1 text-center font-medium text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-green-600">
                </td>
                <td class="py-4 px-5 text-right text-gray-400 font-medium">₱${product.price.toFixed(2)}</td>
                <td class="py-4 px-5 text-right font-bold text-gray-800 line-total">₱${product.price.toFixed(2)}</td>
                <td class="py-4 px-5 text-center">
                    <button type="button" class="remove-item-btn text-gray-400 hover:text-red-500 transition focus:outline-none">
                        <i class="fas fa-trash-alt text-xs"></i>
                    </button>
                </td>
            </tr>
        `;

        itemsContainer.insertAdjacentHTML('beforeend', newRowHTML);
        calculateOrder();
    });

    // Handle inputs inside the container
    itemsContainer.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input')) {
            calculateOrder();
        }
    });

    // Handle clicks for the remove button (using Event Delegation)
    itemsContainer.addEventListener('click', function(e) {
        // Fallback checks to match either the button or the icon nested inside it
        const removeBtn = e.target.closest('.remove-item-btn');
        if (removeBtn) {
            const targetRow = removeBtn.closest('.product-row');
            if (targetRow) {
                targetRow.remove();
                calculateOrder(); // Instantly update subtotals and header counts
            }
        }
    });

    discountInput.addEventListener('input', calculateOrder);
    calculateOrder();
});
</script>
@endsection