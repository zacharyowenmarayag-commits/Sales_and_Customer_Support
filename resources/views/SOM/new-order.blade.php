@extends('layouts.app')

@section('title', 'AmbatuGrow - New Sales Order')

@push('styles')
<style>
.new-order-page { padding: 24px 0; }

/* Back link */
.back-link {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: #6b7280;
    text-decoration: none; transition: color 0.15s;
    margin-bottom: 14px;
}
.back-link:hover { color: #15803d; }

/* Breadcrumb */
.breadcrumb { font-size: 11px; color: #9ca3af; font-weight: 500; margin-bottom: 20px; }
.breadcrumb span { color: #374151; font-weight: 700; }

/* Page title */
.page-title { font-size: 22px; font-weight: 800; color: #111827; margin-bottom: 24px; }

/* Layout */
.order-layout { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
@media (max-width: 900px) { .order-layout { grid-template-columns: 1fr; } }

/* Cards */
.order-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
}
.order-card-section {
    padding: 20px 24px;
    border-bottom: 1px solid #f3f4f6;
}
.order-card-section:last-child { border-bottom: none; }
.section-label {
    font-size: 10px; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.07em; color: #9ca3af; margin-bottom: 12px;
}

/* Customer select */
.customer-select-wrap { position: relative; }
.customer-search-input {
    width: 100%; border: 1px solid #e5e7eb; border-radius: 10px;
    padding: 9px 36px 9px 14px; font-size: 13px; color: #374151;
    background: #fafafa; outline: none; transition: border 0.15s;
    appearance: none; cursor: pointer;
}
.customer-search-input:focus { border-color: #15803d; background: #fff; }
.customer-select-icon {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #9ca3af; font-size: 11px; pointer-events: none;
}

/* Line items table */
.line-items-header {
    display: flex; justify-content: space-between; align-items: center;
    margin-bottom: 10px;
}
.line-items-count { font-size: 11px; color: #9ca3af; font-weight: 600; }
.items-table { width: 100%; border-collapse: collapse; }
.items-table th {
    font-size: 10px; font-weight: 800; text-transform: uppercase;
    letter-spacing: 0.06em; color: #9ca3af;
    padding: 8px 10px; text-align: left; border-bottom: 1px solid #f3f4f6;
}
.items-table th:nth-child(3),
.items-table th:nth-child(4),
.items-table th:nth-child(5) { text-align: right; }
.items-table td { padding: 12px 10px; border-bottom: 1px solid #fafafa; vertical-align: top; }
.items-table tr:last-child td { border-bottom: none; }

.product-name { font-size: 12px; font-weight: 700; color: #111827; }
.product-id   { font-size: 10px; color: #9ca3af; margin-top: 2px; }
.stock-badge  { font-size: 10px; color: #6b7280; margin-top: 2px; }
.stock-warn   { font-size: 10px; color: #dc2626; margin-top: 4px; display: flex; align-items: center; gap: 4px; }

.qty-input {
    width: 72px; border: 1px solid #e5e7eb; border-radius: 8px;
    padding: 6px 8px; font-size: 12px; font-weight: 700; text-align: right;
    color: #374151; outline: none; transition: border 0.15s;
}
.qty-input:focus { border-color: #15803d; }
.qty-input.over-stock { border-color: #fca5a5; background: #fff5f5; }

.unit-price  { font-size: 12px; font-weight: 600; color: #374151; text-align: right; }
.line-total  { font-size: 12px; font-weight: 800; color: #111827; text-align: right; }

/* Add item button */
.add-item-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: #15803d;
    border: 1.5px dashed #bbf7d0; border-radius: 10px;
    padding: 9px 18px; background: #f0fdf4;
    cursor: pointer; transition: all 0.15s; margin-top: 12px;
}
.add-item-btn:hover { background: #dcfce7; border-color: #86efac; }

/* Discount */
.discount-input {
    width: 80px; border: 1px solid #e5e7eb; border-radius: 8px;
    padding: 7px 10px; font-size: 13px; font-weight: 700;
    color: #374151; outline: none; transition: border 0.15s;
}
.discount-input:focus { border-color: #15803d; }
.discount-hint { font-size: 11px; color: #9ca3af; margin-top: 4px; }

/* Summary card */
.summary-card {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 16px; padding: 24px;
    position: sticky; top: 110px;
}
.summary-title { font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #9ca3af; margin-bottom: 16px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.summary-row-label { font-size: 13px; color: #6b7280; font-weight: 500; }
.summary-row-val   { font-size: 13px; color: #111827; font-weight: 700; }
.summary-divider   { border: none; border-top: 1px solid #f3f4f6; margin: 14px 0; }
.summary-total-label { font-size: 14px; font-weight: 800; color: #111827; }
.summary-total-val   { font-size: 20px; font-weight: 900; color: #111827; }
.summary-vat-note    { font-size: 10px; color: #9ca3af; text-align: right; margin-top: 2px; }

.btn-confirm {
    display: block; width: 100%; padding: 12px;
    background: #15803d; color: #fff; border: none; border-radius: 10px;
    font-size: 13px; font-weight: 800; cursor: pointer;
    transition: background 0.15s; margin-top: 18px; text-align: center;
}
.btn-confirm:hover { background: #166534; }
.btn-draft {
    display: block; width: 100%; padding: 10px;
    background: none; color: #6b7280; border: none;
    font-size: 12px; font-weight: 600; cursor: pointer;
    transition: color 0.15s; margin-top: 8px; text-align: center;
}
.btn-draft:hover { color: #374151; }

/* Meta info */
.meta-box { margin-top: 18px; padding-top: 18px; border-top: 1px solid #f3f4f6; display: flex; flex-direction: column; gap: 6px; }
.meta-row { display: flex; justify-content: space-between; }
.meta-label { font-size: 11px; color: #9ca3af; }
.meta-val   { font-size: 11px; color: #374151; font-weight: 600; }

.stock-error-msg {
    background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;
    padding: 10px 14px; font-size: 11px; color: #dc2626; font-weight: 600;
    display: none; margin-top: 14px; align-items: center; gap: 8px;
}
.stock-error-msg.visible { display: flex; }
</style>
@endpush

@section('content')
<div class="new-order-page">

    <!-- Back + Breadcrumb -->
    <a href="{{ route('som') }}" class="back-link">
        <i class="fas fa-arrow-left text-[10px]"></i> Back to sales orders
    </a>
    <div class="breadcrumb">Draft / <span>Pending</span></div>

    <div class="page-title">New sales order</div>

    <div class="order-layout">

        <!-- LEFT: Form -->
        <div class="space-y-4">

            <!-- Customer -->
            <div class="order-card">
                <div class="order-card-section">
                    <div class="section-label">Customer</div>
                    <div class="customer-select-wrap">
                        <select class="customer-search-input" id="customer-select">
                            <option value="" disabled selected>Search customer name</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer }}">{{ $customer }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down customer-select-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Line Items -->
            <div class="order-card">
                <div class="order-card-section">
                    <div class="line-items-header">
                        <div class="section-label" style="margin-bottom:0;">Line Items</div>
                        <div class="line-items-count" id="items-count">{{ count($products) }} Items</div>
                    </div>

                    <div class="overflow-x-auto" style="margin-top:12px;">
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th style="text-align:right;">Available Stock</th>
                                    <th style="text-align:right;">Quantity</th>
                                    <th style="text-align:right;">Unit Price</th>
                                    <th style="text-align:right;">Line Total</th>
                                </tr>
                            </thead>
                            <tbody id="line-items-body">
                                @foreach($products as $product)
                                <tr class="line-item-row" data-stock="{{ $product['stock'] }}" data-price="{{ $product['price'] }}">
                                    <td>
                                        <div class="product-name">{{ $product['name'] }}</div>
                                        <div class="product-id">{{ $product['id'] }}</div>
                                        <div class="stock-warn hidden" id="warn-{{ $loop->index }}">
                                            <i class="fas fa-circle-exclamation text-[10px]"></i>
                                            Only {{ $product['stock'] }} units available — please reduce quantity
                                        </div>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="stock-badge">{{ $product['stock'] }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <input
                                            type="number"
                                            class="qty-input"
                                            value="0"
                                            min="0"
                                            max="{{ $product['stock'] }}"
                                            data-index="{{ $loop->index }}"
                                            data-price="{{ $product['price'] }}"
                                            data-stock="{{ $product['stock'] }}"
                                            oninput="updateRow(this)"
                                        >
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="unit-price">₱{{ number_format($product['price'], 2) }}</span>
                                    </td>
                                    <td style="text-align:right;">
                                        <span class="line-total" id="total-{{ $loop->index }}">₱0.00</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="stock-error" class="stock-error-msg">
                        <i class="fas fa-triangle-exclamation"></i>
                        <span id="stock-error-text">1 items exceed available stock. Adjust quantities to continue.</span>
                    </div>
                </div>
            </div>

            <!-- Discount -->
            <div class="order-card">
                <div class="order-card-section">
                    <div class="section-label">Discount (%)</div>
                    <input type="number" class="discount-input" id="discount-input" value="0" min="0" max="100" oninput="recalcSummary()">
                    <div class="discount-hint">Applied to subtotal before 12% VAT</div>
                </div>
            </div>

        </div>

        <!-- RIGHT: Summary -->
        <div class="summary-card">
            <div class="summary-title">Order Summary</div>

            <div class="summary-row">
                <span class="summary-row-label">Subtotal</span>
                <span class="summary-row-val" id="summary-subtotal">₱0.00</span>
            </div>
            <div class="summary-row">
                <span class="summary-row-label">VAT (12%)</span>
                <span class="summary-row-val" id="summary-vat">₱0.00</span>
            </div>
            <hr class="summary-divider">
            <div class="summary-row">
                <span class="summary-total-label">Total</span>
                <span class="summary-total-val" id="summary-total">₱0.00</span>
            </div>
            <div class="summary-vat-note">VAT Inclusive</div>

            <button class="btn-confirm" onclick="confirmOrder()">Confirm Order</button>
            <button class="btn-draft">Save as draft</button>

            <div class="meta-box">
                <div class="meta-row">
                    <span class="meta-label">Order date</span>
                    <span class="meta-val">{{ now()->format('M j, Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Created by</span>
                    <span class="meta-val">Ann Granlund</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Source</span>
                    <span class="meta-val">Online request</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">Payment terms</span>
                    <span class="meta-val">Net 30 days</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let overStockItems = new Set();

    function updateRow(input) {
        const qty      = parseInt(input.value) || 0;
        const price    = parseFloat(input.dataset.price);
        const stock    = parseInt(input.dataset.stock);
        const idx      = input.dataset.index;
        const warnEl   = document.getElementById('warn-' + idx);
        const totalEl  = document.getElementById('total-' + idx);

        // Stock validation
        if (qty > stock) {
            input.classList.add('over-stock');
            warnEl.classList.remove('hidden');
            overStockItems.add(idx);
        } else {
            input.classList.remove('over-stock');
            warnEl.classList.add('hidden');
            overStockItems.delete(idx);
        }

        // Show/hide global stock error
        const errBox = document.getElementById('stock-error');
        if (overStockItems.size > 0) {
            errBox.classList.add('visible');
            document.getElementById('stock-error-text').textContent =
                overStockItems.size + ' item(s) exceed available stock. Adjust quantities to continue.';
        } else {
            errBox.classList.remove('visible');
        }

        // Line total
        const lineTotal = Math.min(qty, stock) * price;
        totalEl.textContent = '₱' + lineTotal.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        recalcSummary();
    }

    function recalcSummary() {
        let subtotal = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
            const qty   = Math.max(0, Math.min(parseInt(input.value) || 0, parseInt(input.dataset.stock)));
            const price = parseFloat(input.dataset.price);
            subtotal += qty * price;
        });

        const discount   = Math.max(0, Math.min(parseFloat(document.getElementById('discount-input').value) || 0, 100));
        const discounted = subtotal * (1 - discount / 100);
        const vat        = discounted * 0.12;
        const total      = discounted + vat;

        document.getElementById('summary-subtotal').textContent = '₱' + discounted.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        document.getElementById('summary-vat').textContent      = '₱' + vat.toLocaleString('en-PH', { minimumFractionDigits: 2 });
        document.getElementById('summary-total').textContent    = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    }

    function confirmOrder() {
        if (overStockItems.size > 0) {
            alert('Please fix stock quantity errors before confirming the order.');
            return;
        }
        const customer = document.getElementById('customer-select').value;
        if (!customer) {
            alert('Please select a customer before confirming.');
            return;
        }
        alert('Order confirmed! (This is a prototype — backend submission coming soon.)');
    }
</script>
@endpush
