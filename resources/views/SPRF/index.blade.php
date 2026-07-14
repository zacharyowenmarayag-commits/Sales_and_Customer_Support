@extends('layouts.app')

@section('title', 'AMBATUGROW - SPRF')

@push('styles')
<style>
:root {
    --sprf-border: #e3ddc9;
    --sprf-muted: #8a8a7a;
    --sprf-up: #3f8a4a;
    --sprf-yellow: #f0b429;
    --sprf-pink: #e05a8a;
    --sprf-blue: #3aa0c9;
    --sprf-orange: #e08a3a;
}

body {
    margin: 0;
}

.sprf-page {
    margin: 0;
    padding: 0;
}

.sprf-toolbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
    margin: 0 0 16px 0;
}

.sprf-date-select {
    border: 1px solid var(--sprf-border);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    color: #2b2b2b;
    background: #fff;
}

.sprf-icon-btn {
    width: 34px;
    height: 34px;
    border: 1px solid var(--sprf-border);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    color: var(--sprf-muted);
}

.sprf-kpi-row {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 16px;
}

.sprf-kpi-card {
    border: 1px solid var(--sprf-border);
    border-radius: 12px;
    padding: 14px 16px;
    background: #fff;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.sprf-kpi-card .label {
    font-size: 12px;
    color: var(--sprf-muted);
    margin-bottom: 4px;
}

.sprf-kpi-card .value {
    font-size: 22px;
    font-weight: 700;
    color: #2b2b2b;
}

.sprf-kpi-card .delta {
    font-size: 11px;
    color: var(--sprf-up);
    margin-top: 6px;
}

.sprf-kpi-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 13px;
    flex-shrink: 0;
}

.icon-sales { background: var(--sprf-yellow); }
.icon-orders { background: var(--sprf-pink); }
.icon-deal { background: var(--sprf-blue); }
.icon-win { background: var(--sprf-orange); }

.sprf-panel {
    border: 1px solid var(--sprf-border);
    border-radius: 12px;
    background: #fff;
    padding: 16px;
}

.sprf-panel h3 {
    margin: 0 0 12px 0;
    font-size: 14px;
    font-weight: 600;
    color: #2b2b2b;
}

.sprf-charts-row {
    display: grid;
    grid-template-columns: 1.6fr 1fr;
    gap: 14px;
    margin-bottom: 14px;
}

.sprf-three-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 14px;
}

.sprf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.sprf-table th {
    text-align: left;
    color: var(--sprf-muted);
    font-weight: 600;
    padding: 8px 4px;
    border-bottom: 1px solid var(--sprf-border);
}

.sprf-table td {
    padding: 8px 4px;
    border-bottom: 1px solid #f2f0e7;
}

.sprf-sub,
.sprf-rep-target {
    color: var(--sprf-up);
    font-size: 11px;
}

.sprf-progress-wrap {
    background: #f0f0e8;
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
    border-radius: 999px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-proposal { background: #dbe6ff; color: #3355bb; }
.badge-negotiation { background: #fdf0c4; color: #a67c00; }
.badge-qualification { background: #fde2f0; color: #b0447a; }
.badge-onhold { background: #f8d3d3; color: #c23b3b; }

.sprf-view-all {
    text-align: right;
    font-size: 13px;
    color: #2f6b3a;
    font-weight: 600;
    text-decoration: none;
    display: block;
    margin-top: 12px;
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
    $kpis = [
        ['label' => 'Total Sales', 'value' => '₱1,234,567', 'delta' => '+25.6% vs May 1 - May 30', 'icon' => 'sales', 'symbol' => '₱'],
        ['label' => 'Total Orders', 'value' => '345', 'delta' => '+25.6% vs May 1 - May 30', 'icon' => 'orders', 'symbol' => '🛒'],
        ['label' => 'Average Deal Size', 'value' => '₱2,500', 'delta' => '+25.6% vs May 1 - May 30', 'icon' => 'deal', 'symbol' => '◆'],
        ['label' => 'Win Rate', 'value' => '67.1%', 'delta' => '+25.6% vs May 1 - May 30', 'icon' => 'win', 'symbol' => '◎'],
    ];

    $regions = [
        ['name' => 'Luzon', 'sales' => '₱123,635', 'vs' => '+25.6%'],
        ['name' => 'Visayas', 'sales' => '₱103,453', 'vs' => '+25.6%'],
        ['name' => 'Mindanao', 'sales' => '₱121,298', 'vs' => '+25.6%'],
        ['name' => 'NCR', 'sales' => '₱122,433', 'vs' => '+25.6%'],
    ];

    $reps = [
        ['name' => 'Elsa Lgh', 'sales' => '₱934,000', 'target' => 92.4],
        ['name' => 'Dee Nuts', 'sales' => '₱800,000', 'target' => 66.0],
        ['name' => 'Lee Kah', 'sales' => '₱751,000', 'target' => 66.0],
        ['name' => 'Fred Rice', 'sales' => '₱672,000', 'target' => 66.0],
    ];

    $deals = [
        ['name' => 'Site Renovation', 'customer' => 'ABC Corp.', 'stage' => 'Proposal', 'value' => '₱734,000', 'close' => 'July 15, 2024', 'owner' => 'Ash Ketchum'],
        ['name' => 'IT Equipments', 'customer' => 'Tech Solutions', 'stage' => 'Negotiation', 'value' => '₱532,000', 'close' => 'July 30, 2024', 'owner' => 'Misty Reyes'],
        ['name' => 'Confidential F.', 'customer' => 'Sarah D First', 'stage' => 'Qualification', 'value' => '₱1,500,000', 'close' => 'Aug 14, 2024', 'owner' => 'Confidential'],
        ['name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'On-Hold', 'value' => '₱230,000', 'close' => 'Dec 21, 2024', 'owner' => 'Rafael Tanks'],
    ];

    $stageClasses = [
        'Proposal' => 'badge-proposal',
        'Negotiation' => 'badge-negotiation',
        'Qualification' => 'badge-qualification',
        'On-Hold' => 'badge-onhold',
    ];
@endphp

<div class="sprf-page">
    <div class="sprf-toolbar">
        <select class="sprf-date-select">
            <option>May 1 - May 31, 2026</option>
            <option>Apr 1 - Apr 30, 2026</option>
        </select>
        <div class="sprf-icon-btn"><i class="fas fa-filter"></i></div>
        <div class="sprf-icon-btn"><i class="fas fa-download"></i></div>
    </div>

    <div class="sprf-kpi-row">
        @foreach ($kpis as $kpi)
            <div class="sprf-kpi-card">
                <div>
                    <div class="label">{{ $kpi['label'] }}</div>
                    <div class="value">{{ $kpi['value'] }}</div>
                    <div class="delta">▲ {{ $kpi['delta'] }}</div>
                </div>
                <div class="sprf-kpi-icon icon-{{ $kpi['icon'] }}">{{ $kpi['symbol'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="sprf-charts-row">
        <div class="sprf-panel">
            <h3>Sales Performance Over Time</h3>
            <div class="sprf-chart-wrap">
                <div class="sprf-chart-bars">
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 23%;"></div>
                        <span class="sprf-chart-bar-label">May 1</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 28%; background: var(--sprf-yellow);"></div>
                        <span class="sprf-chart-bar-label">May 5</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 33%;"></div>
                        <span class="sprf-chart-bar-label">May 9</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 39%; background: var(--sprf-yellow);"></div>
                        <span class="sprf-chart-bar-label">May 13</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 45%;"></div>
                        <span class="sprf-chart-bar-label">May 17</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 52%; background: var(--sprf-yellow);"></div>
                        <span class="sprf-chart-bar-label">May 21</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 81%;"></div>
                        <span class="sprf-chart-bar-label">May 25</span>
                    </div>
                    <div class="sprf-chart-bar">
                        <div class="sprf-chart-bar-fill" style="height: 97%; background: var(--sprf-yellow);"></div>
                        <span class="sprf-chart-bar-label">May 29</span>
                    </div>
                </div>
                <div class="sprf-chart-legend">
                    <span><span class="dot dot-sales"></span> Sales</span>
                    <span><span class="dot dot-orders"></span> Orders</span>
                </div>
            </div>
        </div>

        <div class="sprf-panel">
            <h3>Sales by Product</h3>
            <div class="sprf-chart-wrap">
                <div class="sprf-product-legend">
                    <div class="sprf-product-item"><div class="sprf-product-meta"><span class="sprf-product-dot" style="background:#3f8a4a"></span> Organic Fertilizers</div><div>28%</div></div>
                    <div class="sprf-product-item"><div class="sprf-product-meta"><span class="sprf-product-dot" style="background:#f0b429"></span> Hybrid Seeds</div><div>23%</div></div>
                    <div class="sprf-product-item"><div class="sprf-product-meta"><span class="sprf-product-dot" style="background:#a67c52"></span> Biopesticides</div><div>18%</div></div>
                    <div class="sprf-product-item"><div class="sprf-product-meta"><span class="sprf-product-dot" style="background:#3aa0c9"></span> Drip Irrigation</div><div>13%</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="sprf-three-row">
        <div class="sprf-panel">
            <h3>Sales by Region</h3>
            <table class="sprf-table">
                <thead>
                    <tr>
                        <th>Region</th>
                        <th>Sales (₱)</th>
                        <th>vs Last Month</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($regions as $region)
                        <tr>
                            <td>{{ $region['name'] }}</td>
                            <td>{{ $region['sales'] }}</td>
                            <td class="sprf-sub">▲ {{ $region['vs'] }}</td>
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
                        <th>Rep</th>
                        <th>Sales (₱)</th>
                        <th>vs Target</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reps as $rep)
                        <tr>
                            <td>{{ $rep['name'] }}</td>
                            <td>
                                {{ $rep['sales'] }}
                                <div class="sprf-progress-wrap">
                                    <div class="sprf-progress-bar" style="width: {{ $rep['target'] }}%;"></div>
                                </div>
                            </td>
                            <td class="sprf-rep-target">{{ $rep['target'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="sprf-panel">
            <h3>Forecast vs Target</h3>
            <div class="sprf-chart-wrap-sm">
                <div class="sprf-forecast-grid">
                    <div class="sprf-forecast-row"><span class="sprf-forecast-label">Q1</span><div class="sprf-forecast-bars"><div class="sprf-forecast-bar actual" style="width: 82%;"></div><div class="sprf-forecast-bar target" style="width: 100%;"></div></div></div>
                    <div class="sprf-forecast-row"><span class="sprf-forecast-label">Q2</span><div class="sprf-forecast-bars"><div class="sprf-forecast-bar actual" style="width: 60%;"></div><div class="sprf-forecast-bar target" style="width: 85%;"></div></div></div>
                    <div class="sprf-forecast-row"><span class="sprf-forecast-label">Q3</span><div class="sprf-forecast-bars"><div class="sprf-forecast-bar actual" style="width: 28%;"></div><div class="sprf-forecast-bar target" style="width: 74%;"></div></div></div>
                    <div class="sprf-forecast-row"><span class="sprf-forecast-label">Q4</span><div class="sprf-forecast-bars"><div class="sprf-forecast-bar actual" style="width: 57%;"></div><div class="sprf-forecast-bar target" style="width: 62%;"></div></div></div>
                </div>
                <div class="sprf-chart-legend">
                    <span><span class="dot dot-forecast"></span> Forecast</span>
                    <span><span class="dot dot-target"></span> Target</span>
                </div>
            </div>
        </div>
    </div>

    <div class="sprf-panel">
        <h3>Recent Deals</h3>
        <table class="sprf-table">
            <thead>
                <tr>
                    <th>Deal Name</th>
                    <th>Customer</th>
                    <th>Stage</th>
                    <th>Value</th>
                    <th>Expected Close</th>
                    <th>Owner</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($deals as $deal)
                    <tr>
                        <td>{{ $deal['name'] }}</td>
                        <td>{{ $deal['customer'] }}</td>
                        <td>
                            <span class="sprf-badge {{ $stageClasses[$deal['stage']] ?? 'badge-proposal' }}">
                                {{ $deal['stage'] }}
                            </span>
                        </td>
                        <td>{{ $deal['value'] }}</td>
                        <td>{{ $deal['close'] }}</td>
                        <td>{{ $deal['owner'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('sprf.deals') }}" class="sprf-view-all">View All Deals →</a>
    </div>
</div>
@endsection
