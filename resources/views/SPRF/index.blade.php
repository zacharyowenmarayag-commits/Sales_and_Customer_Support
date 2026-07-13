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
    display: block;
    margin-top: 16px;
    transition: color 0.15s ease-in-out;
}
.sprf-view-all:hover {
    color: #166534;
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
            <!-- Calendar icon button (Functional, toggles filter view) -->
            <div class="sprf-icon-btn" onclick="toggleFilterAlert()"><i class="fa-regular fa-calendar text-xs"></i></div>
            
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
        <a href="{{ route('sprf.deals', ['date_range' => $dateRange]) }}" class="sprf-view-all inline-flex items-center gap-1 float-right">
            View All Deals <i class="fas fa-arrow-right text-[10px]"></i>
        </a>
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

