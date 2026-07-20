@extends('layouts.app')

@section('title', 'AMBATUGROW - SPRF')

@push('styles')
    @vite(['resources/css/pages/sprf-dashboard.css'])
@endpush

@section('content')
<div class="sprf-page">
    <!-- Header & Toolbar Row -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales Performance Reporting and Forecasting</h1>
            <p class="text-sm text-gray-500 mt-1">Provides data-driven insights into sales activities, team performance, and future sales trends.</p>
        </div>
        <div class="sprf-toolbar mb-0 flex-shrink-0">
            <!-- Calendar icon button (Functional, opens date range popup) -->
            <div id="sprf-cal-trigger" class="sprf-icon-btn" onclick="openCalendarPopup('sprf-cal-popup', '{{ $dateRange }}')"><i class="fa-regular fa-calendar text-xs"></i></div>
            
            <!-- Date select dropdown (Functional, reloads page on select) -->
            <div class="relative">
                <select id="sprf-date-range-select" class="appearance-none bg-white border border-[#e3ddc9] rounded-lg pl-4 pr-10 py-2.5 text-xs text-gray-700 font-bold hover:bg-gray-50 transition duration-150 cursor-pointer focus:outline-none focus:ring-1 focus:ring-green-700 shadow-sm">
                    @foreach ($availableDateRanges as $range)
                        <option value="{{ $range }}" {{ $dateRange === $range ? 'selected' : '' }}>{{ $range }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Filter button (opens dashboard filter panel) -->
            <div id="sprf-dash-filter-trigger" class="sprf-icon-btn relative cursor-pointer" onclick="toggleSprfDashFilter()">
                <i class="fas fa-filter text-xs"></i>
                <span id="sprf-dash-filter-badge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center rounded-full bg-green-700 text-white text-[8px] font-bold">0</span>
            </div>
            
            <!-- Download button (Functional, triggers CSV download of deals) -->
            <div class="sprf-icon-btn" onclick="downloadCSV('dealsTable', 'SPRF_Recent_Deals.csv')"><i class="fas fa-download text-xs"></i></div>
        </div>
    </div>



    <!-- KPI Row -->
    <div id="sprf-section-kpi" class="sprf-kpi-row">
        @foreach ($kpiCards as $kpi)
            <x-sprf.kpi-card
                :label="$kpi['label']"
                :value="$kpi['value']"
                :delta="$kpi['delta']"
                :icon="$kpi['icon']"
                :symbol="$kpi['symbol']"
            />
        @endforeach
    </div>

    <!-- Charts Row -->
    <div id="sprf-section-charts" class="sprf-charts-row">
        <x-sprf.panel>
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
        </x-sprf.panel>

        <x-sprf.panel>
            <h3>Sales by Product</h3>
            <div class="flex flex-col sm:flex-row items-center gap-4 mt-2">
                <div class="relative w-[130px] h-[130px] flex-shrink-0">
                    <canvas id="salesByProductChart"></canvas>
                </div>
                <div class="sprf-product-legend flex-1 w-full">
                    @foreach ($productSales as $sale)
                        <div class="sprf-product-item border-b border-[#f5f2e9] last:border-0 py-1" data-product="{{ strtolower($sale->product_name) }}">
                            <div class="sprf-product-meta">
                                <span class="sprf-product-dot" style="background:{{ $sale->color }}"></span>
                                {!! $sale->highlighted_name !!}
                            </div>
                            <div class="font-bold text-gray-900">{{ $sale->percentage }}%</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-sprf.panel>
    </div>

    <!-- Details Row -->
    <div id="sprf-section-details" class="sprf-three-row">
        <x-sprf.panel class="flex flex-col h-full">
            <h3 class="m-0 mb-4">Sales by Region</h3>
            <div class="mx-[-24px] bg-[#faf8f2] border-y border-[#eedcbe] px-6 py-2 flex-1 flex items-center">
                <table class="sprf-table" id="regionTable">
                    <thead>
                        <tr data-table-id="regionTable">
                            <th class="pl-0 pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('regionTable', 0, 'string', this)">
                                <span class="flex items-center gap-1">Region <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                            </th>
                            <th class="pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('regionTable', 1, 'currency', this)">
                                <span class="flex items-center gap-1">Sales (₱) <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                            </th>
                            <th class="text-right pr-0 pb-3 font-bold text-gray-950 select-none">vs Last Month</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($regionSales as $region)
                            <tr data-region="{{ strtolower($region->region_name) }}">
                                <td class="pl-0 font-semibold">{!! $region->highlighted_name !!}</td>
                                <td>{{ $region->sales_amount }}</td>
                                <td class="sprf-sub text-right pr-0">▲ {{ $region->vs_last_month }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 h-4"></div>
        </x-sprf.panel>

        <x-sprf.panel class="flex flex-col h-full">
            <h3 class="m-0 mb-4">Top Sales Representatives</h3>
            <div class="mx-[-24px] bg-[#faf8f2] border-y border-[#eedcbe] px-6 py-2 flex-1 flex items-center">
                <table class="sprf-table" id="repTable">
                    <thead>
                        <tr data-table-id="repTable">
                            <th class="pl-0 pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('repTable', 0, 'string', this)">
                                <span class="flex items-center gap-1">Rep <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                            </th>
                            <th class="pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('repTable', 1, 'currency', this)">
                                <span class="flex items-center gap-1">Sales (₱) <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                            </th>
                            <th class="text-right pr-0 pb-3 font-bold text-gray-950 select-none">vs Target</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($repSales as $rep)
                            <tr data-rep="{{ strtolower($rep->rep_name) }}">
                                <td class="pl-0 font-semibold">{!! $rep->highlighted_name !!}</td>
                                <td>
                                    {{ $rep->sales_amount }}
                                    <div class="sprf-progress-wrap">
                                        <div class="sprf-progress-bar" style="width: {{ $rep->progress_width }}%;"></div>
                                    </div>
                                </td>
                                <td class="sprf-rep-target text-right pr-0">{{ $rep->vs_target }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 h-4"></div>
        </x-sprf.panel>

        <x-sprf.panel class="flex flex-col h-full">
            <h3 class="m-0 mb-4">Forecast vs Target</h3>
            <div class="mx-[-24px] bg-[#faf8f2] border-y border-[#eedcbe] px-6 py-2 flex-1 flex items-center">
                <div class="relative w-full h-[180px]">
                    <canvas id="forecastChart"></canvas>
                </div>
            </div>
            <div class="flex justify-center items-center gap-4 mt-4 text-[10px] font-bold text-gray-500 h-4">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#555555]"></span> Target</span>
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#e08a3a]"></span> Actual</span>
            </div>
        </x-sprf.panel>
    </div>

    <!-- Recent Deals -->
    <div id="sprf-section-deals" class="sprf-panel">
        <div class="flex justify-between items-center py-2 mb-2">
            <h3 class="m-0">Recent Deals</h3>
            <span class="text-xs text-gray-500 font-bold select-none cursor-pointer hover:underline" onclick="downloadCSV('dealsTable', 'SPRF_Recent_Deals.csv')">Export CSV</span>
        </div>
        <div class="mx-[-24px] bg-[#faf8f2] border-y border-[#eedcbe]/45 px-6 py-2">
            <table class="sprf-table w-full" id="dealsTable">
                <thead>
                    <tr class="border-b border-[#eedcbe]" data-table-id="dealsTable">
                        <th class="pb-3 pl-0 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('dealsTable', 0, 'string', this)">
                            <span class="flex items-center gap-1">Deal Name <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('dealsTable', 1, 'string', this)">
                            <span class="flex items-center gap-1">Customer <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 font-bold text-gray-950 select-none relative">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-blue-600 transition" onclick="toggleStageFilterMenu('dealsTable', this, event)">
                                Stage <i class="fas fa-filter text-[10px] text-gray-400"></i>
                            </span>
                        </th>
                        <th class="pb-3 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('dealsTable', 3, 'currency', this)">
                            <span class="flex items-center gap-1">Value <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 pr-0 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('dealsTable', 4, 'date', this)">
                            <span class="flex items-center gap-1">Expected Close <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                    </tr>
<<<<<<< HEAD
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-400 py-6">No recent deals found for this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="flex justify-end mt-3">
            <a href="{{ route('sprf.deals', ['date_range' => $dateRange]) }}" class="sprf-view-all">
                View all deals <i class="fas fa-arrow-right text-[10px]"></i>
            </a>
        </div>
=======
                </thead>
                <tbody>
                    @forelse ($recentDeals as $deal)
                        <tr class="deal-row">
                            <td class="pl-0 font-semibold text-gray-900">{!! $deal->highlighted_name !!}</td>
                            <td>{!! $deal->highlighted_customer !!}</td>
                            <td>
                                <span class="sprf-badge {{ $deal->stage_class }}">{{ $deal->stage }}</span>
                            </td>
                            <td>{{ $deal->value }}</td>
                            <td class="pr-0">{{ $deal->expected_close }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-400 py-6">No recent deals found for this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex justify-end mt-4">
            <a href="{{ route('sprf.deals', ['date_range' => $dateRange]) }}" class="sprf-view-all text-sm font-bold text-green-700 hover:text-green-900 transition flex items-center gap-1.5">
                View All Deals <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</div>

<!-- ==================== SPRF Dashboard Filter Panel ==================== -->
<div id="sprf-dash-filter-overlay" class="fixed inset-0 z-40 hidden" onclick="closeSprfDashFilter()"></div>
<div id="sprf-dash-filter-panel" class="fixed z-50 hidden">
    <div class="w-[360px] max-w-[95vw] bg-[#fffefb] border border-[#eedcbe] rounded-[20px] shadow-2xl overflow-hidden" style="max-height:calc(100vh - 32px);overflow-y:auto;">

        <!-- Header -->
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-[#eedcbe]">
            <span class="text-sm font-extrabold text-gray-900"><i class="fas fa-sliders-h text-xs text-green-700 mr-1.5"></i>Dashboard Filters</span>
            <button onclick="closeSprfDashFilter()" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs cursor-pointer">✕</button>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-[#eedcbe] bg-[#fffdf8]">
            <button id="sprf-tab-btn-sections" onclick="switchSprfFilterTab('sections')"
                    class="flex-1 py-2.5 text-[11px] font-bold text-green-800 border-b-2 border-green-700 transition cursor-pointer">
                <i class="fas fa-layer-group text-[10px] mr-1"></i>Sections
            </button>
            <button id="sprf-tab-btn-data" onclick="switchSprfFilterTab('data')"
                    class="flex-1 py-2.5 text-[11px] font-bold text-gray-500 border-b-2 border-transparent hover:text-gray-700 transition cursor-pointer">
                <i class="fas fa-sliders-h text-[10px] mr-1"></i>Data Filters
            </button>
        </div>

        <!-- SECTIONS TAB -->
        <div id="sprf-filter-tab-sections" class="px-5 py-4 space-y-0.5">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-3">Show / Hide Dashboard Panels</p>
            @foreach ($sectionDefs as $sec)
                <label class="flex items-center justify-between py-2.5 px-3 rounded-xl hover:bg-[#f9f7f2] cursor-pointer group select-none transition-colors">
                    <span class="flex items-center gap-2.5 text-xs font-semibold text-gray-700 group-hover:text-gray-900">
                        <i class="fas {{ $sec['icon'] }} text-[10px] text-green-700 w-3.5 text-center shrink-0"></i>
                        {{ $sec['label'] }}
                    </span>
                    <div class="relative shrink-0 ml-3">
                        <input type="checkbox" checked class="sprf-section-toggle sr-only peer" data-section="{{ $sec['id'] }}">
                        <div class="w-9 h-5 rounded-full bg-gray-300 peer-checked:bg-green-700 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow-sm transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>
            @endforeach
        </div>

        <!-- DATA FILTERS TAB -->
        <div id="sprf-filter-tab-data" class="hidden px-5 py-4 space-y-4">
            <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mb-1">Filter Table Rows &amp; Highlights</p>

            <!-- Sales Rep -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sales Representative</label>
                <div class="relative">
                    <select id="sprf-data-rep" class="w-full appearance-none border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition cursor-pointer">
                        <option value="">All Representatives</option>
                        @foreach ($repSales as $rep)
                            <option value="{{ strtolower($rep->rep_name) }}">{{ $rep->rep_name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400"><i class="fas fa-chevron-down text-[9px]"></i></div>
                </div>
            </div>

            <!-- Region -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Region</label>
                <div class="relative">
                    <select id="sprf-data-region" class="w-full appearance-none border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition cursor-pointer">
                        <option value="">All Regions</option>
                        @foreach ($regionSales as $region)
                            <option value="{{ strtolower($region->region_name) }}">{{ $region->region_name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400"><i class="fas fa-chevron-down text-[9px]"></i></div>
                </div>
            </div>

            <!-- Product -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Product</label>
                <div class="relative">
                    <select id="sprf-data-product" class="w-full appearance-none border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-medium bg-white focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition cursor-pointer">
                        <option value="">All Products</option>
                        @foreach ($productSales as $sale)
                            <option value="{{ strtolower($sale->product_name) }}">{{ $sale->product_name }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400"><i class="fas fa-chevron-down text-[9px]"></i></div>
                </div>
            </div>

            <div class="p-3 bg-[#f0fdf4] border border-green-200 rounded-xl text-[10px] text-green-800 leading-relaxed">
                <i class="fas fa-info-circle mr-1"></i>
                Filters update table rows instantly. Rep and Region filters hide non-matching rows; Product filter dims the legend.
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-5 py-4 border-t border-[#eedcbe] bg-[#fffdf8]">
            <button onclick="resetSprfDashFilters()" class="px-4 py-2 rounded-lg border border-[#e3ddc9] text-[11px] font-bold text-gray-600 hover:bg-gray-50 transition cursor-pointer">Reset All</button>
            <button onclick="closeSprfDashFilter()" class="px-5 py-2 rounded-lg bg-green-800 text-[11px] font-bold text-white hover:bg-green-900 transition cursor-pointer">Done</button>
        </div>
>>>>>>> 364da8f (feat: enhance CRM segmentation, dashboard, and SPRF features; refactor SPRF architecture)
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
    window.SPRF_DASHBOARD = {
        availableMonths: @json($availableMonths),
        salesLabels: @json($salesPerformance->pluck('label')->toArray()),
        salesAmountData: @json($salesPerformance->pluck('sales_amount')->toArray()),
        ordersData: @json($salesPerformance->pluck('orders_count')->toArray()),
        productLabels: @json($productSales->pluck('product_name')->toArray()),
        productPercentages: @json($productSales->pluck('percentage')->toArray()),
        productColors: @json($productSales->pluck('color')->toArray()),
        forecastLabels: @json($forecastTargets->pluck('category')->toArray()),
        forecastTargets: @json($forecastTargets->pluck('target_amount')->toArray()),
        forecastActuals: @json($forecastTargets->pluck('actual_amount')->toArray())
    };
</script>
<script src="{{ asset('js/sprf-dashboard.js') }}?v={{ filemtime(public_path('js/sprf-dashboard.js')) }}"></script>
@endpush


