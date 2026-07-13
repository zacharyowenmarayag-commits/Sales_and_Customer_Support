@extends('layouts.app')

@section('title', 'AMBATUGROW - SPRF')

@push('styles')
<style>
:root {
    --sprf-border: #eedcbe;
    --sprf-muted: #8a8a7a;
    --sprf-up: #15803d;
    --sprf-yellow: #a67c00;
    --sprf-pink: #b0447a;
    --sprf-blue: #3aa0c9;
    --sprf-orange: #e08a3a;
}

body {
    margin: 0;
}

.sprf-page {
    margin: 0;
    padding: 24px 0;
}

.sprf-toolbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin: 0 0 24px 0;
}

.sprf-date-select {
    border: 1px solid #eedcbe;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 12px;
    font-weight: 700;
    color: #374151;
    background: #fff;
    cursor: pointer;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.sprf-icon-btn {
    width: 40px;
    height: 40px;
    border: 1px solid #eedcbe;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    color: var(--sprf-muted);
    cursor: pointer;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    transition: all 0.15s ease-in-out;
}
.sprf-icon-btn:hover {
    background: #f9fafb;
    color: #4b5563;
}

.sprf-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.sprf-kpi-card {
    border: 1px solid var(--sprf-border);
    border-radius: 16px;
    padding: 18px 20px;
    background: #fffefb;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.sprf-kpi-card .label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--sprf-muted);
    margin-bottom: 6px;
}

.sprf-kpi-card .value {
    font-size: 24px;
    font-weight: 800;
    color: #030712;
}

.sprf-kpi-card .delta {
    font-size: 11px;
    font-weight: 700;
    color: var(--sprf-up);
    margin-top: 6px;
}

.sprf-kpi-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.05);
}

.icon-sales { background: var(--sprf-yellow); }
.icon-orders { background: var(--sprf-pink); }
.icon-deal { background: var(--sprf-blue); }
.icon-win { background: var(--sprf-orange); }

.sprf-panel {
    border: 1px solid var(--sprf-border);
    border-radius: 24px;
    background: #fffefb;
    padding: 24px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.sprf-panel h3 {
    margin: 0 0 16px 0;
    font-size: 15px;
    font-weight: 800;
    color: #030712;
}

.sprf-charts-row {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.sprf-three-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.sprf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.sprf-table th {
    text-align: left;
    color: #030712;
    font-weight: 700;
    padding: 10px 8px;
    border-bottom: 1px solid var(--sprf-border);
}

.sprf-table td {
    padding: 12px 8px;
    border-bottom: 1px solid #f5f2e9;
    color: #374151;
}

.sprf-sub,
.sprf-rep-target {
    color: var(--sprf-up);
    font-size: 11px;
    font-weight: 700;
}

.sprf-progress-wrap {
    background: #f5f2e9;
    border-radius: 6px;
    height: 6px;
    width: 100%;
    margin-top: 6px;
    overflow: hidden;
}

.sprf-progress-bar {
    height: 100%;
    background: var(--sprf-up);
    border-radius: 6px;
}

.sprf-chart-wrap {
    min-height: 240px;
}

.sprf-chart-wrap-sm {
    height: 180px;
}

.sprf-chart-bars {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 170px;
    padding: 0 6px;
}

.sprf-chart-bar {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.sprf-chart-bar-fill {
    width: 100%;
    border-radius: 12px 12px 0 0;
    background: var(--sprf-blue);
    min-height: 8px;
}

.sprf-chart-bar-label {
    font-size: 11px;
    color: var(--sprf-muted);
    text-align: center;
}

.sprf-chart-legend {
    display: flex;
    justify-content: flex-start;
    gap: 14px;
    font-size: 12px;
    color: var(--sprf-muted);
}

.sprf-chart-legend .dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
    vertical-align: middle;
}

.dot-sales { background: var(--sprf-blue); }
.dot-orders { background: var(--sprf-yellow); }
.dot-forecast { background: var(--sprf-orange); }
.dot-target { background: #555555; }

.sprf-product-legend {
    display: grid;
    gap: 10px;
}

.sprf-product-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 6px 0;
    font-size: 12px;
    color: #2b2b2b;
}

.sprf-product-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.sprf-product-meta {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sprf-forecast-grid {
    display: grid;
    gap: 10px;
}

.sprf-forecast-row {
    display: grid;
    grid-template-columns: 56px 1fr;
    align-items: center;
    gap: 10px;
}

.sprf-forecast-label {
    font-size: 12px;
    color: var(--sprf-muted);
}

.sprf-forecast-bars {
    display: flex;
    gap: 6px;
    align-items: center;
}

.sprf-forecast-bar {
    height: 14px;
    border-radius: 8px;
}

.sprf-forecast-bar.actual { background: var(--sprf-orange); }
.sprf-forecast-bar.target { background: #555555; }

.sprf-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    display: inline-block;
}

.badge-proposal { background: #dbe6ff; color: #3355bb; }
.badge-negotiation { background: #fff9c4; color: #a67c00; }
.badge-qualification { background: #fde2f0; color: #b0447a; }
.badge-onhold { background: #fee2e2; color: #dc2626; }

.sprf-view-all {
    text-align: right;
    font-size: 13px;
    color: #15803d;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.15s ease-in-out;
}
.sprf-view-all:hover {
    color: #166534;
}

/* === Calendar Popup Styles === */
.cal-grid-month {
    display: grid;
    grid-template-columns: repeat(7, minmax(0, 1fr));
    gap: 4px 0;
    text-align: center;
}
.cal-day-head {
    font-size: 9px;
    font-weight: 800;
    color: #8a8a7a;
    padding-bottom: 6px;
}
.cal-day-wrap {
    position: relative;
}
.cal-day-wrap.in-range {
    background: #dcfce7;
}
.cal-day {
    width: 28px;
    height: 28px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.12s;
    color: #374151;
    user-select: none;
}
.cal-day:hover {
    background: #f0fdf4;
    color: #166534;
}
.cal-day.is-selected {
    background: #166534;
    color: #fff;
    font-weight: 800;
    box-shadow: 0 2px 6px rgba(22,101,52,0.25);
}
.cal-day.in-range {
    background: #dcfce7;
    color: #166534;
    border-radius: 0;
}
.cal-day.hover-end {
    outline: 2px solid #166534;
    outline-offset: 1px;
}
.cal-nav-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.cal-nav-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: #6b7280;
    cursor: pointer;
    transition: background 0.12s;
}
.cal-nav-btn:hover {
    background: #f3f4f6;
}
.cal-nav-title {
    font-size: 12px;
    font-weight: 800;
    color: #030712;
}
.cal-quarter-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}
.cal-quarter-btn {
    border: 1px solid #e3ddc9;
    border-radius: 12px;
    padding: 12px 8px;
    font-size: 12px;
    font-weight: 800;
    color: #374151;
    background: #fff;
    cursor: pointer;
    transition: all 0.12s;
    text-align: center;
}
.cal-quarter-btn:hover {
    background: #f0fdf4;
    border-color: #166534;
    color: #166534;
}
.cal-quarter-btn.is-selected {
    background: #166534;
    border-color: #166534;
    color: #fff;
}
.cal-quarter-sub {
    font-size: 10px;
    font-weight: 400;
    display: block;
    margin-top: 2px;
}
.cal-year-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
}
.cal-year-btn {
    border: 1px solid #e3ddc9;
    border-radius: 12px;
    padding: 12px 8px;
    font-size: 12px;
    font-weight: 800;
    color: #374151;
    background: #fff;
    cursor: pointer;
    transition: all 0.12s;
    text-align: center;
}
.cal-year-btn:hover {
    background: #f0fdf4;
    border-color: #166534;
    color: #166534;
}
.cal-year-btn.is-selected {
    background: #166534;
    border-color: #166534;
    color: #fff;
}

@media (max-width: 1100px) {
    .sprf-kpi-row,
    .sprf-three-row {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sprf-charts-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .sprf-kpi-row,
    .sprf-three-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
@php
    $kpiCards = [
        ['label' => 'Total Sales', 'value' => $kpis->total_sales ?? '₱0', 'delta' => $kpis->sales_delta ?? '', 'icon' => 'sales', 'symbol' => '₱'],
        ['label' => 'Total Orders', 'value' => $kpis->total_orders ?? '0', 'delta' => $kpis->orders_delta ?? '', 'icon' => 'orders', 'symbol' => '🛒'],
        ['label' => 'Average Deal Size', 'value' => $kpis->avg_deal_size ?? '₱0', 'delta' => $kpis->deal_delta ?? '', 'icon' => 'deal', 'symbol' => '◆'],
        ['label' => 'Win Rate', 'value' => $kpis->win_rate ?? '0%', 'delta' => $kpis->win_delta ?? '', 'icon' => 'win', 'symbol' => '◎'],
    ];

    $stageClasses = [
        'Proposal' => 'badge-proposal',
        'Negotiation' => 'badge-negotiation',
        'Qualification' => 'badge-qualification',
        'On-Hold' => 'badge-onhold',
    ];
@endphp

<div class="sprf-page">
    <!-- Header & Toolbar Row -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <!-- Spacer or Back button if needed -->
        </div>
        <div class="sprf-toolbar mb-0">
            <!-- Calendar icon button (Functional, opens date range popup) -->
            <div id="sprf-cal-trigger" class="sprf-icon-btn" onclick="openCalendarPopup('sprf-cal-popup', '{{ $dateRange }}')"><i class="fa-regular fa-calendar text-xs"></i></div>
            
            <!-- Date select dropdown (Functional, reloads page on select) -->
            <select class="sprf-date-select" onchange="window.location.href = '?date_range=' + encodeURIComponent(this.value)">
                <option value="May 1 - May 31, 2026" {{ $dateRange == 'May 1 - May 31, 2026' ? 'selected' : '' }}>May 1 - May 31, 2026</option>
                <option value="Apr 1 - Apr 30, 2026" {{ $dateRange == 'Apr 1 - Apr 30, 2026' ? 'selected' : '' }}>Apr 1 - Apr 30, 2026</option>
            </select>
            
            <!-- Filter button (Functional, toggles info panel) -->
            <div class="sprf-icon-btn" onclick="toggleFilterAlert()"><i class="fas fa-filter text-xs"></i></div>
            
            <!-- Download button (Functional, triggers CSV download of deals) -->
            <div class="sprf-icon-btn" onclick="downloadCSV('dealsTable', 'SPRF_Recent_Deals.csv')"><i class="fas fa-download text-xs"></i></div>
        </div>
    </div>

    <!-- Filter Alert Panel (Hidden by default) -->
    <div id="filterAlert" class="hidden mb-6 p-4 bg-[#fffdf6] border border-[#eedcbe] rounded-2xl text-xs text-gray-700 transition duration-150">
        <div class="flex justify-between items-center">
            <span><strong>Active Filter:</strong> Date range is set to <strong>{{ $dateRange }}</strong>. All charts and datasets show active database records for this range.</span>
            <button onclick="toggleFilterAlert()" class="text-gray-400 hover:text-gray-600 font-bold ml-4">✕</button>
        </div>
    </div>

    <!-- KPI Row -->
    <div class="sprf-kpi-row">
        @foreach ($kpiCards as $kpi)
            <div class="sprf-kpi-card hover:translate-y-[-2px] transition duration-150">
                <div>
                    <div class="label">{{ $kpi['label'] }}</div>
                    <div class="value">{{ $kpi['value'] }}</div>
                    <div class="delta">▲ {{ $kpi['delta'] }}</div>
                </div>
                <div class="sprf-kpi-icon icon-{{ $kpi['icon'] }}">{{ $kpi['symbol'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="sprf-charts-row">
        <div class="sprf-panel">
            <div class="flex justify-between items-center mb-4">
                <h3 class="m-0">Sales Performance Over Time</h3>
                <div class="flex items-center gap-4 text-xs font-bold text-gray-500">
                    <span class="flex items-center gap-1.5"><span class="w-3 h-1.5 rounded bg-[#3aa0c9]"></span> SALES</span>
                    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm bg-[#a3d9a5]"></span> ORDERS</span>
                </div>
            </div>
            <div class="relative w-full h-[220px]">
                <canvas id="salesPerformanceChart"></canvas>
            </div>
        </div>

        <div class="sprf-panel">
            <h3>Sales by Product</h3>
            <div class="flex flex-col sm:flex-row items-center gap-4 mt-2">
                <div class="relative w-[130px] h-[130px] flex-shrink-0">
                    <canvas id="salesByProductChart"></canvas>
                </div>
                <div class="sprf-product-legend flex-1 w-full">
                    @foreach ($productSales as $sale)
                        <div class="sprf-product-item border-b border-[#f5f2e9] last:border-0 py-1">
                            <div class="sprf-product-meta">
                                <span class="sprf-product-dot" style="background:{{ $sale->color }}"></span>
                                {{ $sale->product_name }}
                            </div>
                            <div class="font-bold text-gray-900">{{ $sale->percentage }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Details Row -->
    <div class="sprf-three-row">
        <div class="sprf-panel">
            <h3>Sales by Region</h3>
            <table class="sprf-table">
                <thead>
                    <tr>
                        <th class="pl-0">Region</th>
                        <th>Sales (₱)</th>
                        <th class="text-right pr-0">vs Last Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($regionSales as $region)
                        <tr>
                            <td class="pl-0 font-semibold">{{ $region->region_name }}</td>
                            <td>{{ $region->sales_amount }}</td>
                            <td class="sprf-sub text-right pr-0">▲ {{ $region->vs_last_month }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sprf-panel">
            <h3>Top Sales Representatives</h3>
            <table class="sprf-table">
                <thead>
                    <tr>
                        <th class="pl-0">Rep</th>
                        <th>Sales (₱)</th>
                        <th class="text-right pr-0">vs Target</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // calculate progress percentage from vs_target text
                        function getProgressWidth($targetText) {
                            return min(100, floatval(str_replace('%', '', $targetText)));
                        }
                    @endphp
                    @foreach ($repSales as $rep)
                        <tr>
                            <td class="pl-0 font-semibold">{{ $rep->rep_name }}</td>
                            <td>
                                {{ $rep->sales_amount }}
                                <div class="sprf-progress-wrap">
                                    <div class="sprf-progress-bar" style="width: {{ getProgressWidth($rep->vs_target) }}%;"></div>
                                </div>
                            </td>
                            <td class="sprf-rep-target text-right pr-0">{{ $rep->vs_target }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sprf-panel">
            <div class="flex justify-between items-center mb-4">
                <h3 class="m-0">Forecast vs Target</h3>
                <div class="flex items-center gap-3 text-[10px] font-bold text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#555555]"></span> Target</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#e08a3a]"></span> Actual</span>
                </div>
            </div>
            <div class="relative w-full h-[180px]">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Deals -->
    <div class="sprf-panel">
        <div class="flex justify-between items-center mb-4">
            <h3 class="m-0">Recent Deals</h3>
            <span class="text-xs text-gray-500 font-bold select-none cursor-pointer hover:underline" onclick="downloadCSV('dealsTable', 'SPRF_Recent_Deals.csv')">Export CSV</span>
        </div>
        <table class="sprf-table w-full" id="dealsTable">
            <thead>
                <tr>
                    <th class="pl-0">Deal Name</th>
                    <th>Customer</th>
                    <th>Stage</th>
                    <th>Value</th>
                    <th>Expected Close</th>
                    <th>Owner</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentDeals as $deal)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="pl-0 font-semibold text-gray-950">{{ $deal->name }}</td>
                        <td>{{ $deal->customer }}</td>
                        <td>
                            <span class="sprf-badge {{ $stageClasses[$deal->stage] ?? 'badge-proposal' }}">
                                {{ $deal->stage }}
                            </span>
                        </td>
                        <td class="font-bold text-gray-950">{{ $deal->value }}</td>
                        <td>{{ $deal->expected_close }}</td>
                        <td>{{ $deal->owner }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="display:flex; justify-content:flex-end; margin-top:16px;">
            <a href="{{ route('sprf.deals', ['date_range' => $dateRange]) }}" class="sprf-view-all">
                View All Deals <i class="fas fa-arrow-right" style="font-size:10px;"></i>
            </a>
        </div>
    </div>
</div>

<!-- Calendar Popup Modal -->
<div id="sprf-cal-popup" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]" onclick="closeCalendarPopup('sprf-cal-popup')"></div>
    <!-- Panel -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[380px] max-w-[95vw] bg-[#fffefb] border border-[#eedcbe] rounded-[20px] shadow-2xl overflow-hidden" style="z-index:10;">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-[#eedcbe]">
            <span class="text-sm font-extrabold text-gray-900">Select Date Range</span>
            <button onclick="closeCalendarPopup('sprf-cal-popup')" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs">✕</button>
        </div>

        <!-- Text Input -->
        <div class="px-5 pt-4">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Date Range <span class="text-gray-400 font-normal normal-case">(MMM D - MMM D, YYYY)</span></label>
            <div class="relative">
                <input id="sprf-cal-text-input" type="text" placeholder="e.g. May 1 - May 31, 2026"
                    class="w-full border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-bold focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition placeholder:font-normal placeholder:text-gray-400"
                    oninput="onCalTextInput(this, 'sprf-cal-popup')" />
                <span id="sprf-cal-input-err" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-red-500 font-semibold">Invalid format</span>
            </div>
        </div>

        <!-- Quick-select Presets -->
        <div class="px-5 pt-3 flex flex-wrap gap-1.5">
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'sprf-cal-popup', 'thisMonth')">This Month</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'sprf-cal-popup', 'lastMonth')">Last Month</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'sprf-cal-popup', 'thisQuarter')">This Quarter</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'sprf-cal-popup', 'thisYear')">This Year</button>
        </div>

        <!-- View Switcher -->
        <div class="px-5 pt-3 flex items-center gap-2">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-1">View:</span>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'sprf-cal-popup', 'month')">Month</button>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'sprf-cal-popup', 'quarter')">Quarter</button>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'sprf-cal-popup', 'year')">Year</button>
        </div>

        <!-- Calendar Grid -->
        <div id="sprf-cal-grid" class="px-5 pt-3 pb-4">
            <!-- rendered by JS -->
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-5 py-4 border-t border-[#eedcbe] bg-[#fffdf8]">
            <span id="sprf-cal-selection-label" class="text-[11px] font-bold text-gray-500">No range selected</span>
            <div class="flex gap-2">
                <button onclick="closeCalendarPopup('sprf-cal-popup')" class="px-4 py-2 rounded-lg border border-[#e3ddc9] text-[11px] font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button id="sprf-cal-apply" onclick="applyCalendarSelection('sprf-cal-popup')" class="px-5 py-2 rounded-lg bg-green-800 text-[11px] font-bold text-white hover:bg-green-900 transition disabled:opacity-40 disabled:cursor-not-allowed" disabled>Apply</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // -------------------------------------------------------------
    // Utility functions for filters and downloads
    // -------------------------------------------------------------
    function toggleFilterAlert() {
        const panel = document.getElementById('filterAlert');
        panel.classList.toggle('hidden');
    }

    function downloadCSV(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) return;
        let csv = [];
        const rows = table.querySelectorAll("tr");
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 0; j < cols.length; j++) {
                let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, "").replace(/(\s\s+)/gm, ' ');
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            csv.push(row.join(","));
        }
        const csvString = csv.join("\n");
        const blob = new Blob([csvString], { type: "text/csv" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = filename;
        link.click();
    }

    /* ============================================================
       Shared Calendar Popup Engine
       ============================================================ */
    (function () {
        const MONTHS = ['January','February','March','April','May','June',
                        'July','August','September','October','November','December'];
        const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','May','Jun',
                              'Jul','Aug','Sep','Oct','Nov','Dec'];
        const DAYS = ['Su','Mo','Tu','We','Th','Fr','Sa'];

        const _state = {};

        function getState(popupId) {
            if (!_state[popupId]) {
                const now = new Date();
                _state[popupId] = {
                    view: 'month',
                    cursor: { year: now.getFullYear(), month: now.getMonth() },
                    startDate: null,
                    endDate: null,
                    hoveredDate: null,
                };
            }
            return _state[popupId];
        }

        function fmtDate(d) {
            return MONTHS_SHORT[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        }

        function fmtRange(s, e) {
            if (!s) return 'No range selected';
            if (!e) return fmtDate(s) + ' — pick end date';
            return fmtDate(s) + ' - ' + fmtDate(e);
        }

        function parseRange(str) {
            const re = /^([A-Za-z]+)\s+(\d{1,2})(?:,\s*(\d{4}))?\s*-\s*([A-Za-z]+)\s+(\d{1,2}),\s*(\d{4})$/;
            const m = str.trim().match(re);
            if (!m) return null;
            const startYear = m[3] ? parseInt(m[3]) : parseInt(m[6]);
            const sm = MONTHS_SHORT.findIndex(x => x.toLowerCase() === m[1].slice(0,3).toLowerCase());
            const em = MONTHS_SHORT.findIndex(x => x.toLowerCase() === m[4].slice(0,3).toLowerCase());
            if (sm < 0 || em < 0) return null;
            const s = new Date(startYear, sm, parseInt(m[2]));
            const e = new Date(parseInt(m[6]), em, parseInt(m[5]));
            if (isNaN(s) || isNaN(e)) return null;
            return { start: s, end: e };
        }

        function sameDay(a, b) {
            return a && b && a.getFullYear() === b.getFullYear() &&
                   a.getMonth() === b.getMonth() && a.getDate() === b.getDate();
        }

        function isBetween(d, s, e) {
            return s && e && d > s && d < e;
        }

        function updateSelectionLabel(popupId) {
            const st = getState(popupId);
            const labelEl = document.getElementById(
                popupId === 'sprf-cal-popup' ? 'sprf-cal-selection-label' : 'deals-cal-selection-label'
            );
            if (labelEl) labelEl.textContent = fmtRange(st.startDate, st.endDate);
            const applyBtn = document.querySelector('#' + popupId + ' [id$="-cal-apply"]');
            if (applyBtn) applyBtn.disabled = !(st.startDate && st.endDate);
        }

        function renderGrid(popupId) {
            const st = getState(popupId);
            const gridId = popupId === 'sprf-cal-popup' ? 'sprf-cal-grid' : 'deals-cal-grid';
            const grid = document.getElementById(gridId);
            if (!grid) return;

            document.querySelectorAll('#' + popupId + ' .cal-view-btn').forEach(btn => {
                const v = btn.getAttribute('onclick').match(/'(month|quarter|year)'/);
                if (v && v[1] === st.view) {
                    btn.className = 'cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold bg-green-800 text-white';
                } else {
                    btn.className = 'cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold border border-[#e3ddc9] text-gray-600 hover:bg-green-50 hover:text-green-800 transition';
                }
            });

            if (st.view === 'month') renderMonthGrid(popupId, grid);
            else if (st.view === 'quarter') renderQuarterGrid(popupId, grid);
            else renderYearGrid(popupId, grid);
        }

        function renderMonthGrid(popupId, grid) {
            const st = getState(popupId);
            const { year, month } = st.cursor;
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = `<div class="cal-nav-row">
                <button class="cal-nav-btn" data-nav="-1">&#8249;</button>
                <span class="cal-nav-title">${MONTHS[month]} ${year}</span>
                <button class="cal-nav-btn" data-nav="1">&#8250;</button>
            </div>
            <div class="cal-grid-month">`;

            // Day headers
            DAYS.forEach(d => { html += `<div class="cal-day-head">${d}</div>`; });

            // Empty leading cells
            for (let i = 0; i < firstDay; i++) html += `<div></div>`;

            // Day cells
            for (let d = 1; d <= daysInMonth; d++) {
                const date = new Date(year, month, d);
                const isStart = sameDay(date, st.startDate);
                const isEnd   = sameDay(date, st.endDate);
                const inRange = isBetween(date, st.startDate, st.endDate);
                const inHoverRange = !st.endDate && st.startDate && date > st.startDate && st.hoveredDate && date < st.hoveredDate;
                const isHoverEnd   = !st.endDate && st.startDate && sameDay(date, st.hoveredDate) && date > st.startDate;

                let wrapClass = 'cal-day-wrap' + ((inRange || inHoverRange) ? ' in-range' : '');
                let dayClass  = 'cal-day';
                if (isStart || isEnd) dayClass += ' is-selected';
                else if (inRange || inHoverRange) dayClass += ' in-range';
                if (isHoverEnd) dayClass += ' hover-end';

                html += `<div class="${wrapClass}" data-y="${year}" data-m="${month}" data-d="${d}">
                            <div class="${dayClass}" data-pick="day">${d}</div>
                         </div>`;
            }
            html += '</div>';
            grid.innerHTML = html;

            // Nav buttons
            grid.querySelectorAll('.cal-nav-btn').forEach(btn => {
                btn.addEventListener('click', () => calNav(popupId, parseInt(btn.dataset.nav)));
            });
            // Day clicks via delegation
            grid.addEventListener('click', function handler(e) {
                const target = e.target.closest('[data-pick="day"]');
                if (!target) return;
                const wrap = target.closest('.cal-day-wrap');
                if (wrap) calPickDay(popupId, parseInt(wrap.dataset.y), parseInt(wrap.dataset.m), parseInt(wrap.dataset.d));
            });
            // Hover for range preview
            grid.addEventListener('mouseover', function(e) {
                const wrap = e.target.closest('.cal-day-wrap');
                if (!wrap) return;
                calHover(popupId, parseInt(wrap.dataset.y), parseInt(wrap.dataset.m), parseInt(wrap.dataset.d));
            });
            grid.addEventListener('mouseleave', function() { calLeave(popupId); });
        }

        function renderQuarterGrid(popupId, grid) {
            const st = getState(popupId);
            const year = st.cursor.year;
            const quarters = [
                { label: 'Q1', months: [0,1,2] },
                { label: 'Q2', months: [3,4,5] },
                { label: 'Q3', months: [6,7,8] },
                { label: 'Q4', months: [9,10,11] },
            ];
            let html = `<div class="cal-nav-row">
                <button class="cal-nav-btn" data-nav-year="-1">&#8249;</button>
                <span class="cal-nav-title">${year}</span>
                <button class="cal-nav-btn" data-nav-year="1">&#8250;</button>
            </div>
            <div class="cal-quarter-grid">`;
            quarters.forEach(q => {
                const s = new Date(year, q.months[0], 1);
                const e = new Date(year, q.months[2] + 1, 0);
                const isActive = st.startDate && st.endDate && sameDay(s, st.startDate) && sameDay(e, st.endDate);
                html += `<button class="cal-quarter-btn${isActive ? ' is-selected' : ''}" data-qsy="${year}" data-qsm="${q.months[0]}" data-qey="${year}" data-qem="${q.months[2]+1}">
                            ${q.label}<span class="cal-quarter-sub">${MONTHS_SHORT[q.months[0]]}&ndash;${MONTHS_SHORT[q.months[2]]}</span>
                         </button>`;
            });
            html += '</div>';
            grid.innerHTML = html;

            grid.querySelectorAll('.cal-nav-btn').forEach(btn => {
                btn.addEventListener('click', () => calNavYear(popupId, parseInt(btn.dataset.navYear)));
            });
            grid.querySelectorAll('.cal-quarter-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    calPickRange(popupId, parseInt(btn.dataset.qsy), parseInt(btn.dataset.qsm), 1, parseInt(btn.dataset.qey), parseInt(btn.dataset.qem), 0);
                });
            });
        }

        function renderYearGrid(popupId, grid) {
            const st = getState(popupId);
            const currentYear = new Date().getFullYear();
            let html = '<div class="cal-year-grid">';
            for (let y = currentYear - 2; y <= currentYear + 1; y++) {
                const s = new Date(y, 0, 1);
                const e = new Date(y, 11, 31);
                const isActive = st.startDate && st.endDate && sameDay(s, st.startDate) && sameDay(e, st.endDate);
                html += `<button class="cal-year-btn${isActive ? ' is-selected' : ''}" data-y="${y}">${y}</button>`;
            }
            html += '</div>';
            grid.innerHTML = html;

            grid.querySelectorAll('.cal-year-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const y = parseInt(btn.dataset.y);
                    calPickRange(popupId, y, 0, 1, y, 11, 31);
                });
            });
        }

        window.openCalendarPopup = function(popupId, currentRange) {
            const popup = document.getElementById(popupId);
            if (!popup) return;
            const st = getState(popupId);
            if (currentRange) {
                const parsed = parseRange(currentRange);
                if (parsed) {
                    st.startDate = parsed.start;
                    st.endDate = parsed.end;
                    st.cursor = { year: parsed.start.getFullYear(), month: parsed.start.getMonth() };
                }
            }
            const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
            const input = document.getElementById(inputId);
            if (input && st.startDate && st.endDate) input.value = fmtRange(st.startDate, st.endDate);
            popup.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            renderGrid(popupId);
            updateSelectionLabel(popupId);
        };

        window.closeCalendarPopup = function(popupId) {
            document.getElementById(popupId).classList.add('hidden');
            document.body.style.overflow = '';
        };

        window.calNav = function(popupId, dir) {
            const st = getState(popupId);
            let m = st.cursor.month + dir;
            let y = st.cursor.year;
            if (m < 0) { m = 11; y--; }
            if (m > 11) { m = 0; y++; }
            st.cursor = { year: y, month: m };
            renderGrid(popupId);
        };

        window.calNavYear = function(popupId, dir) {
            getState(popupId).cursor.year += dir;
            renderGrid(popupId);
        };

        window.calHover = function(popupId, y, m, d) {
            const st = getState(popupId);
            if (st.startDate && !st.endDate) {
                st.hoveredDate = new Date(y, m, d);
                renderGrid(popupId);
            }
        };

        window.calLeave = function(popupId) {
            getState(popupId).hoveredDate = null;
            renderGrid(popupId);
        };

        window.calPickDay = function(popupId, y, m, d) {
            const st = getState(popupId);
            const picked = new Date(y, m, d);
            if (!st.startDate || (st.startDate && st.endDate)) {
                st.startDate = picked;
                st.endDate = null;
            } else {
                if (picked < st.startDate) {
                    st.endDate = st.startDate;
                    st.startDate = picked;
                } else {
                    st.endDate = picked;
                }
            }
            const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
            const input = document.getElementById(inputId);
            if (input && st.startDate && st.endDate) input.value = fmtRange(st.startDate, st.endDate);
            updateSelectionLabel(popupId);
            renderGrid(popupId);
        };

        window.calPickRange = function(popupId, sy, sm, sd, ey, em, ed) {
            const st = getState(popupId);
            st.startDate = new Date(sy, sm, sd);
            st.endDate = new Date(ey, em, ed);
            const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
            const input = document.getElementById(inputId);
            if (input) input.value = fmtRange(st.startDate, st.endDate);
            updateSelectionLabel(popupId);
            renderGrid(popupId);
        };

        window.applyCalPreset = function(btn, popupId, preset) {
            const now = new Date();
            const y = now.getFullYear();
            const m = now.getMonth();
            let s, e;
            if (preset === 'thisMonth') {
                s = new Date(y, m, 1);
                e = new Date(y, m + 1, 0);
            } else if (preset === 'lastMonth') {
                s = new Date(y, m - 1, 1);
                e = new Date(y, m, 0);
            } else if (preset === 'thisQuarter') {
                const q = Math.floor(m / 3);
                s = new Date(y, q * 3, 1);
                e = new Date(y, q * 3 + 3, 0);
            } else if (preset === 'thisYear') {
                s = new Date(y, 0, 1);
                e = new Date(y, 11, 31);
            }
            const st = getState(popupId);
            st.startDate = s;
            st.endDate = e;
            st.cursor = { year: s.getFullYear(), month: s.getMonth() };
            document.querySelectorAll('#' + popupId + ' .cal-preset-btn').forEach(b => {
                b.className = 'cal-preset-btn px-3 py-1 rounded-full border text-[10px] font-bold transition ' +
                    (b === btn ? 'bg-green-800 text-white border-green-800' : 'border-[#e3ddc9] text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800');
            });
            const inputId = popupId === 'sprf-cal-popup' ? 'sprf-cal-text-input' : 'deals-cal-text-input';
            const input = document.getElementById(inputId);
            if (input) input.value = fmtRange(s, e);
            updateSelectionLabel(popupId);
            renderGrid(popupId);
        };

        window.setCalView = function(btn, popupId, view) {
            getState(popupId).view = view;
            renderGrid(popupId);
        };

        window.onCalTextInput = function(input, popupId) {
            const errId = popupId === 'sprf-cal-popup' ? 'sprf-cal-input-err' : 'deals-cal-input-err';
            const errEl = document.getElementById(errId);
            const val = input.value.trim();
            const parsed = parseRange(val);
            if (parsed) {
                if (errEl) errEl.classList.add('hidden');
                input.classList.remove('border-red-400');
                const st = getState(popupId);
                st.startDate = parsed.start;
                st.endDate = parsed.end;
                st.cursor = { year: parsed.start.getFullYear(), month: parsed.start.getMonth() };
                updateSelectionLabel(popupId);
                renderGrid(popupId);
            } else if (val.length > 5) {
                if (errEl) errEl.classList.remove('hidden');
                input.classList.add('border-red-400');
            } else {
                if (errEl) errEl.classList.add('hidden');
                input.classList.remove('border-red-400');
            }
        };

        window.applyCalendarSelection = function(popupId) {
            const st = getState(popupId);
            if (!st.startDate || !st.endDate) return;
            const range = fmtRange(st.startDate, st.endDate);
            closeCalendarPopup(popupId);
            window.location.href = '?date_range=' + encodeURIComponent(range);
        };
    })();

    // -------------------------------------------------------------
    // Chart 1: Sales Performance Over Time
    // -------------------------------------------------------------
    const ctx1 = document.getElementById('salesPerformanceChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: {!! json_encode($salesPerformance->pluck('label')->toArray()) !!},
            datasets: [
                {
                    label: 'Sales',
                    type: 'line',
                    data: {!! json_encode($salesPerformance->pluck('sales_amount')->toArray()) !!},
                    borderColor: '#3aa0c9',
                    backgroundColor: '#3aa0c9',
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3aa0c9',
                    pointBorderWidth: 2,
                    fill: false,
                    yAxisID: 'ySales'
                },
                {
                    label: 'Orders',
                    data: {!! json_encode($salesPerformance->pluck('orders_count')->toArray()) !!},
                    backgroundColor: '#a3d9a5',
                    borderColor: '#3f8a4a',
                    borderWidth: 0,
                    borderRadius: 4,
                    yAxisID: 'yOrders',
                    barPercentage: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#8a8a7a'
                    }
                },
                ySales: {
                    type: 'linear',
                    position: 'left',
                    grid: {
                        color: '#f5f2e9'
                    },
                    ticks: {
                        callback: function(value) {
                            return (value / 1000) + 'K';
                        },
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#8a8a7a'
                    }
                },
                yOrders: {
                    type: 'linear',
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    ticks: {
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#8a8a7a'
                    }
                }
            }
        }
    });

    // -------------------------------------------------------------
    // Chart 2: Sales by Product
    // -------------------------------------------------------------
    const ctx2 = document.getElementById('salesByProductChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productSales->pluck('product_name')->toArray()) !!},
            datasets: [{
                data: {!! json_encode($productSales->pluck('percentage')->toArray()) !!},
                backgroundColor: {!! json_encode($productSales->pluck('color')->toArray()) !!},
                borderWidth: 3,
                borderColor: '#fffefb'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // -------------------------------------------------------------
    // Chart 3: Forecast vs Target
    // -------------------------------------------------------------
    const ctx3 = document.getElementById('forecastChart').getContext('2d');
    new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: {!! json_encode($forecastTargets->pluck('category')->toArray()) !!},
            datasets: [
                {
                    label: 'Target',
                    data: {!! json_encode($forecastTargets->pluck('target_amount')->toArray()) !!},
                    backgroundColor: '#555555',
                    borderRadius: 4,
                    barPercentage: 0.35,
                    categoryPercentage: 0.7
                },
                {
                    label: 'Actual',
                    data: {!! json_encode($forecastTargets->pluck('actual_amount')->toArray()) !!},
                    backgroundColor: '#e08a3a',
                    borderRadius: 4,
                    barPercentage: 0.35,
                    categoryPercentage: 0.7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#8a8a7a'
                    }
                },
                y: {
                    grid: {
                        color: '#f5f2e9'
                    },
                    ticks: {
                        font: {
                            size: 10,
                            weight: 'bold'
                        },
                        color: '#8a8a7a'
                    }
                }
            }
        }
    });
</script>
@endpush

