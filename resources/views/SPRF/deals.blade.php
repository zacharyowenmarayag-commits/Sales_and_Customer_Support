@extends('layouts.app')

@section('title', 'AmbatuGrow - Deals')

@push('styles')
<style>
* { font-family: 'Inter', sans-serif; }
/* === Calendar Popup Styles (shared with SPRF index) === */
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
.cal-day-wrap { position: relative; }
.cal-day-wrap.in-range { background: #dcfce7; }
.cal-day {
    width: 28px; height: 28px;
    margin: 0 auto;
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%;
    font-size: 11px; font-weight: 600;
    cursor: pointer;
    transition: all 0.12s;
    color: #374151;
    user-select: none;
}
.cal-day:hover { background: #f0fdf4; color: #166534; }
.cal-day.is-selected { background: #166534; color: #fff; font-weight: 800; box-shadow: 0 2px 6px rgba(22,101,52,0.25); }
.cal-day.in-range { background: #dcfce7; color: #166534; border-radius: 0; }
.cal-day.hover-end { outline: 2px solid #166534; outline-offset: 1px; }
.cal-nav-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.cal-nav-btn {
    width: 28px; height: 28px;
    border: none; background: none; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; font-weight: 700; color: #6b7280;
    cursor: pointer; transition: background 0.12s;
}
.cal-nav-btn:hover { background: #f3f4f6; }
.cal-nav-title { font-size: 12px; font-weight: 800; color: #030712; }
.cal-quarter-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
.cal-quarter-btn {
    border: 1px solid #e3ddc9; border-radius: 12px;
    padding: 12px 8px; font-size: 12px; font-weight: 800;
    color: #374151; background: #fff; cursor: pointer;
    transition: all 0.12s; text-align: center;
}
.cal-quarter-btn:hover { background: #f0fdf4; border-color: #166534; color: #166534; }
.cal-quarter-btn.is-selected { background: #166534; border-color: #166534; color: #fff; }
.cal-quarter-sub { font-size: 10px; font-weight: 400; display: block; margin-top: 2px; }
.cal-year-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
.cal-year-btn {
    border: 1px solid #e3ddc9; border-radius: 12px;
    padding: 12px 8px; font-size: 12px; font-weight: 800;
    color: #374151; background: #fff; cursor: pointer;
    transition: all 0.12s; text-align: center;
}
.cal-year-btn:hover { background: #f0fdf4; border-color: #166534; color: #166534; }
.cal-year-btn.is-selected { background: #166534; border-color: #166534; color: #fff; }

table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    margin-top: 4px !important;
}
table thead tr {
    background-color: #fbf9f1 !important;
}
table th {
    padding: 10px 12px !important;
    border-bottom: none !important;
}
table thead tr th:first-child {
    border-top-left-radius: 8px !important;
    border-bottom-left-radius: 8px !important;
    padding-left: 12px !important;
}
table thead tr th:last-child {
    border-top-right-radius: 8px !important;
    border-bottom-right-radius: 8px !important;
    padding-right: 12px !important;
}
table td {
    padding: 10px 12px !important;
}
#ongoingDealsTable tr td,
#pastDealsTable tr td {
    border-bottom: 1px solid #f5f2e9;
}
#ongoingDealsTable tr:last-child td,
#pastDealsTable tr:last-child td {
    border-bottom: none;
}
/* Orange line between header and first data row */
[data-table-id="ongoingDealsTable"] th,
[data-table-id="pastDealsTable"] th {
    border-bottom: 1px solid #eedcbe !important;
}
</style>
@endpush

@section('content')
@php
    if (!function_exists('highlightMatch')) {
        function highlightMatch($text, $query) {
            if (!$query || trim($query) === '') {
                return e($text);
            }
            $escapedQuery = preg_quote($query, '/');
            return preg_replace('/(' . $escapedQuery . ')/i', '<span class="text-blue-600">$1</span>', e($text));
        }
    }
@endphp
<div class="space-y-6">
    <!-- Header & Toolbar Row -->
    <div class="flex justify-between items-center">
        <a href="{{ route('sprf', ['date_range' => $dateRange]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-green-800 transition duration-150 select-none">
            <i class="fas fa-arrow-left text-[10px]"></i> Back to Dashboard
        </a>
        <div class="flex items-center gap-3">
            <!-- Calendar icon button -->
            <button id="deals-cal-trigger" onclick="openCalendarPopup('deals-cal-popup', '{{ $dateRange }}')" class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                <i class="fa-regular fa-calendar text-sm"></i>
            </button>
            
            <!-- Date select dropdown (Functional) -->
            <div class="relative">
                <select class="appearance-none bg-white border border-[#e3ddc9] rounded-lg pl-4 pr-10 py-2.5 text-xs text-gray-700 font-bold hover:bg-gray-50 transition duration-150 cursor-pointer focus:outline-none focus:ring-1 focus:ring-green-700 shadow-sm" onchange="window.location.href = '?date_range=' + encodeURIComponent(this.value)">
                    @foreach ($availableDateRanges as $range)
                        <option value="{{ $range }}" {{ $dateRange === $range ? 'selected' : '' }}>{{ $range }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Filter button -->
            <div class="relative">
                <button id="sprf-filter-trigger" onclick="toggleSprfFilter()" class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                    <i class="fas fa-filter text-sm"></i>
                </button>
                <span id="sprf-filter-badge" class="hidden absolute -top-1.5 -right-1.5 w-4 h-4 flex items-center justify-center rounded-full bg-green-700 text-white text-[8px] font-bold">0</span>
            </div>
            
            <!-- Download button (Functional, exports ongoing deals) -->
            <button onclick="downloadCSV('ongoingDealsTable', 'SPRF_Ongoing_Deals.csv')" class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                <i class="fas fa-download text-sm"></i>
            </button>
        </div>
    </div>

    <!-- On-Going Deals Card -->
    <div class="bg-[#fffefb] border border-[#eedcbe] rounded-[24px] p-8 shadow-sm">
        <h2 class="text-[19px] font-bold text-gray-900 mb-5">On-Going Deals</h2>
        <div class="mx-[-32px] bg-[#faf8f2] border-y border-[#eedcbe] px-8 py-2 overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-800 border-collapse">
                <thead>
                    <tr class="border-b border-[#eedcbe]" data-table-id="ongoingDealsTable">
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('ongoingDealsTable', 0, 'string', this)">
                            <span class="flex items-center gap-1">Deal Name <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('ongoingDealsTable', 1, 'string', this)">
                            <span class="flex items-center gap-1">Customer <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none relative">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-blue-600 transition" onclick="toggleStageFilterMenu('ongoingDealsTable', this, event)">
                                Stage <i class="fas fa-filter text-[10px] text-gray-400"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('ongoingDealsTable', 3, 'currency', this)">
                            <span class="flex items-center gap-1">Value <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('ongoingDealsTable', 4, 'date', this)">
                            <span class="flex items-center gap-1">Expected Close <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('ongoingDealsTable', 5, 'string', this)">
                            <span class="flex items-center gap-1">Owner <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                    </tr>
                </thead>
                @php
                    $stageClasses = [
                        'Proposal' => 'bg-[#dbe6ff] text-[#3355bb]',
                        'Negotiation' => 'bg-[#fff9c4] text-[#a67c00]',
                        'Qualification' => 'bg-[#fde2f0] text-[#b0447a]',
                        'On-Hold' => 'bg-[#fee2e2] text-[#dc2626]',
                    ];
                @endphp
                <tbody class="divide-y divide-[#f5f2e9]" id="ongoingDealsTable">
                    @forelse ($ongoingDeals as $deal)
                        @if (!empty($q) && !(stripos($deal->name, $q) !== false || stripos($deal->customer, $q) !== false || stripos($deal->owner, $q) !== false))
                            @continue
                        @endif
                        <tr class="hover:bg-[#fffcf4]/50 transition duration-150">
                            <td class="py-4 px-2 font-medium text-gray-950">{!! highlightMatch($deal->name, $q) !!}</td>
                            <td class="py-4 px-2 text-gray-800">{!! highlightMatch($deal->customer, $q) !!}</td>
                            <td class="py-4 px-2">
                                <span class="inline-block px-3 py-1 rounded-[6px] {{ $stageClasses[$deal->stage] ?? 'bg-gray-100 text-gray-800' }} text-[10px] font-bold">
                                    {{ $deal->stage }}
                                </span>
                            </td>
                            <td class="py-4 px-2 font-bold text-gray-950">{{ $deal->value }}</td>
                            <td class="py-4 px-2">{{ $deal->expected_close }}</td>
                            <td class="py-4 px-2 font-semibold text-gray-900">{!! highlightMatch($deal->owner, $q) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 font-semibold">No ongoing deals found for this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Past Deals Card -->
    <div class="bg-[#fffefb] border border-[#eedcbe] rounded-[24px] p-8 shadow-sm">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-[19px] font-bold text-gray-900 m-0">Past Deals</h2>
            <span class="text-xs text-gray-500 font-bold select-none cursor-pointer hover:underline" onclick="downloadCSV('pastDealsTable', 'SPRF_Past_Deals.csv')">Export CSV</span>
        </div>
        <div class="mx-[-32px] bg-[#faf8f2] border-y border-[#eedcbe] px-8 py-2 overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-800 border-collapse">
                <thead>
                    <tr class="border-b border-[#eedcbe]" data-table-id="pastDealsTable">
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('pastDealsTable', 0, 'string', this)">
                            <span class="flex items-center gap-1">Deal Name <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('pastDealsTable', 1, 'string', this)">
                            <span class="flex items-center gap-1">Customer <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none relative">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-blue-600 transition" onclick="toggleStageFilterMenu('pastDealsTable', this, event)">
                                Stage <i class="fas fa-filter text-[10px] text-gray-400"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('pastDealsTable', 3, 'currency', this)">
                            <span class="flex items-center gap-1">Value <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('pastDealsTable', 4, 'date', this)">
                            <span class="flex items-center gap-1">Expected Close <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('pastDealsTable', 5, 'string', this)">
                            <span class="flex items-center gap-1">Owner <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                        </th>
                    </tr>
                </thead>
                @php
                    $pastStageClasses = [
                        'Won' => 'bg-[#dcfce7] text-[#15803d]',
                        'Lost' => 'bg-[#e0f2fe] text-[#0369a1]',
                    ];
                @endphp
                <tbody class="divide-y divide-[#f5f2e9]" id="pastDealsTable">
                    @forelse ($pastDeals as $deal)
                        @if (!empty($q) && !(stripos($deal->name, $q) !== false || stripos($deal->customer, $q) !== false || stripos($deal->owner, $q) !== false))
                            @continue
                        @endif
                        <tr class="hover:bg-[#fffcf4]/50 transition duration-150">
                            <td class="py-4 px-2 font-medium text-gray-950">{!! highlightMatch($deal->name, $q) !!}</td>
                            <td class="py-4 px-2 text-gray-800">{!! highlightMatch($deal->customer, $q) !!}</td>
                            <td class="py-4 px-2">
                                <span class="inline-block px-3 py-1 rounded-[6px] {{ $pastStageClasses[$deal->stage] ?? 'bg-gray-100 text-gray-800' }} text-[10px] font-bold">
                                    {{ $deal->stage }}
                                </span>
                            </td>
                            <td class="py-4 px-2 font-bold text-gray-950">{{ $deal->value }}</td>
                            <td class="py-4 px-2">{{ $deal->expected_close }}</td>
                            <td class="py-4 px-2 font-semibold text-gray-900">{!! highlightMatch($deal->owner, $q) !!}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400 font-semibold">No past deals found for this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Premium Pagination Footer -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 pt-5 border-t border-[#eedcbe]">
            <div class="text-xs text-gray-500 font-bold">
                Showing {{ $pastDeals->firstItem() ?? 0 }} to {{ $pastDeals->lastItem() ?? 0 }} of {{ $pastDeals->total() }} entries
            </div>
            <div class="flex items-center gap-3">
                @if ($pastDeals->lastPage() > 1)
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-500 font-bold">Go to:</span>
                        <select onchange="window.location.href = this.value" class="bg-white border border-[#eedcbe] rounded-lg px-2 py-1.5 text-[10px] font-bold text-gray-600 focus:outline-none focus:ring-1 focus:ring-green-700 shadow-sm cursor-pointer">
                            @for ($i = 1; $i <= $pastDeals->lastPage(); $i++)
                                <option value="{{ $pastDeals->appends(request()->query())->url($i) }}" {{ $i == $pastDeals->currentPage() ? 'selected' : '' }}>
                                    Page {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                @endif
                <div class="flex items-center gap-1.5">
                    @if ($pastDeals->onFirstPage())
                        <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-gray-400 select-none cursor-not-allowed" disabled>
                            Previous
                        </button>
                    @else
                        <a href="{{ $pastDeals->appends(request()->query())->previousPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                            Previous
                        </a>
                    @endif

                    @foreach ($pastDeals->getUrlRange(1, $pastDeals->lastPage()) as $page => $url)
                        @if ($page == $pastDeals->currentPage())
                            <button class="px-3.5 py-2 rounded-lg bg-green-800 text-[10px] text-white font-bold select-none cursor-default">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $pastDeals->appends(request()->query())->url($page) }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($pastDeals->hasMorePages())
                        <a href="{{ $pastDeals->appends(request()->query())->nextPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                            Next
                        </a>
                    @else
                        <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-gray-400 select-none cursor-not-allowed" disabled>
                            Next
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== SPRF Filter Popover ==================== -->
<div id="sprf-filter-overlay" class="fixed inset-0 z-40 hidden" onclick="closeSprfFilter()"></div>
<div id="sprf-filter-panel" class="fixed z-50 hidden" style="top:0;right:0;">
    <div class="w-[340px] max-w-[95vw] bg-[#fffefb] border border-[#eedcbe] rounded-[20px] shadow-2xl overflow-hidden mt-2 mr-2" style="max-height:calc(100vh - 24px); overflow-y:auto;">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-[#eedcbe]">
            <span class="text-sm font-extrabold text-gray-900"><i class="fas fa-filter text-xs text-green-700 mr-1.5"></i>Filter Deals</span>
            <button onclick="closeSprfFilter()" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs cursor-pointer">✕</button>
        </div>

        <div class="px-5 py-4 space-y-4">
            <!-- Stage Multi-select -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Stage</label>
                <div class="flex flex-wrap gap-2" id="sprf-filter-stages">
                    @php $allStages = ['Proposal','Negotiation','Qualification','On-Hold','Won','Lost']; @endphp
                    @foreach ($allStages as $st)
                        @php
                            $colors = [
                                'Proposal'      => 'border-[#3355bb]/30 text-[#3355bb] peer-checked:bg-[#dbe6ff] peer-checked:border-[#3355bb]',
                                'Negotiation'   => 'border-[#a67c00]/30 text-[#a67c00] peer-checked:bg-[#fff9c4] peer-checked:border-[#a67c00]',
                                'Qualification' => 'border-[#b0447a]/30 text-[#b0447a] peer-checked:bg-[#fde2f0] peer-checked:border-[#b0447a]',
                                'On-Hold'       => 'border-[#dc2626]/30 text-[#dc2626] peer-checked:bg-[#fee2e2] peer-checked:border-[#dc2626]',
                                'Won'           => 'border-[#15803d]/30 text-[#15803d] peer-checked:bg-[#dcfce7] peer-checked:border-[#15803d]',
                                'Lost'          => 'border-[#0369a1]/30 text-[#0369a1] peer-checked:bg-[#e0f2fe] peer-checked:border-[#0369a1]',
                            ];
                        @endphp
                        <label class="relative cursor-pointer select-none">
                            <input type="checkbox" name="filter_stage" value="{{ $st }}" class="peer sr-only">
                            <span class="inline-block px-3 py-1.5 rounded-full border text-[10px] font-bold transition-all duration-150 {{ $colors[$st] ?? 'border-gray-300 text-gray-600' }}">{{ $st }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Owner -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Owner</label>
                <input id="sprf-filter-owner" type="text" placeholder="e.g. Juan Dela Cruz"
                       class="w-full border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition placeholder:text-gray-400" />
            </div>

            <!-- Value Range -->
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Deal Value Range</label>
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">₱</span>
                        <input id="sprf-filter-min" type="number" min="0" placeholder="Min"
                               class="w-full border border-[#e3ddc9] rounded-lg pl-7 pr-3 py-2.5 text-xs text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition placeholder:text-gray-400" />
                    </div>
                    <span class="text-gray-400 text-xs font-bold">—</span>
                    <div class="relative flex-1">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 font-bold">₱</span>
                        <input id="sprf-filter-max" type="number" min="0" placeholder="Max"
                               class="w-full border border-[#e3ddc9] rounded-lg pl-7 pr-3 py-2.5 text-xs text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition placeholder:text-gray-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-5 py-4 border-t border-[#eedcbe] bg-[#fffdf8]">
            <button onclick="clearSprfFilter()" class="px-4 py-2 rounded-lg border border-[#e3ddc9] text-[11px] font-bold text-gray-600 hover:bg-gray-50 transition cursor-pointer">Clear All</button>
            <button onclick="applySprfFilter()" class="px-5 py-2 rounded-lg bg-green-800 text-[11px] font-bold text-white hover:bg-green-900 transition cursor-pointer">Apply Filters</button>
        </div>
    </div>
</div>

<!-- Calendar Popup Modal -->
<div id="deals-cal-popup" class="fixed inset-0 z-50 hidden" aria-modal="true" role="dialog">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/30 backdrop-blur-[2px]" onclick="closeCalendarPopup('deals-cal-popup')"></div>
    <!-- Panel -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[380px] max-w-[95vw] bg-[#fffefb] border border-[#eedcbe] rounded-[20px] shadow-2xl overflow-hidden" style="z-index:10;">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-[#eedcbe]">
            <span class="text-sm font-extrabold text-gray-900">Select Date Range</span>
            <button onclick="closeCalendarPopup('deals-cal-popup')" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs">✕</button>
        </div>

        <!-- Text Input -->
        <div class="px-5 pt-4">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Date Range <span class="text-gray-400 font-normal normal-case">(MMM D - MMM D, YYYY)</span></label>
            <div class="relative">
                <input id="deals-cal-text-input" type="text" placeholder="e.g. May 1 - May 31, 2026"
                    class="w-full border border-[#e3ddc9] rounded-lg px-3 py-2.5 text-xs text-gray-800 font-bold focus:outline-none focus:ring-2 focus:ring-green-700/40 focus:border-green-700 transition placeholder:font-normal placeholder:text-gray-400"
                    oninput="onCalTextInput(this, 'deals-cal-popup')" />
                <span id="deals-cal-input-err" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-red-500 font-semibold">Invalid format</span>
            </div>
        </div>

        <!-- Quick-select Presets -->
        <div class="px-5 pt-3 flex flex-wrap gap-1.5">
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'deals-cal-popup', 'thisMonth')">This Month</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'deals-cal-popup', 'lastMonth')">Last Month</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'deals-cal-popup', 'thisQuarter')">This Quarter</button>
            <button class="cal-preset-btn px-3 py-1 rounded-full border border-[#e3ddc9] text-[10px] font-bold text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800 transition" onclick="applyCalPreset(this, 'deals-cal-popup', 'thisYear')">This Year</button>
        </div>

        <!-- View Switcher -->
        <div class="px-5 pt-3 flex items-center gap-2">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mr-1">View:</span>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'deals-cal-popup', 'month')">Month</button>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'deals-cal-popup', 'quarter')">Quarter</button>
            <button class="cal-view-btn px-3 py-1 rounded-full text-[10px] font-bold transition" onclick="setCalView(this, 'deals-cal-popup', 'year')">Year</button>
        </div>

        <!-- Calendar Grid -->
        <div id="deals-cal-grid" class="px-5 pt-3 pb-4">
            <!-- rendered by JS -->
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-5 py-4 border-t border-[#eedcbe] bg-[#fffdf8]">
            <span id="deals-cal-selection-label" class="text-[11px] font-bold text-gray-500">No range selected</span>
            <div class="flex gap-2">
                <button onclick="closeCalendarPopup('deals-cal-popup')" class="px-4 py-2 rounded-lg border border-[#e3ddc9] text-[11px] font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button id="deals-cal-apply" onclick="applyCalendarSelection('deals-cal-popup')" class="px-5 py-2 rounded-lg bg-green-800 text-[11px] font-bold text-white hover:bg-green-900 transition disabled:opacity-40 disabled:cursor-not-allowed" disabled>Apply</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
</script>
@endpush

@push('scripts')
<script>
/* ---- Available months for calendar (from DB) ---- */
window._sprfAvailMonths = @json($availableMonths);

/* ============================================================
   Shared Calendar Popup Engine
   ============================================================ */
(function () {
    const MONTHS = ['January','February','March','April','May','June',
                    'July','August','September','October','November','December'];
    const MONTHS_SHORT = ['Jan','Feb','Mar','Apr','May','Jun',
                          'Jul','Aug','Sep','Oct','Nov','Dec'];
    const DAYS = ['Su','Mo','Tu','We','Th','Fr','Sa'];

    // Per-popup state
    const _state = {};

    function getState(popupId) {
        if (!_state[popupId]) {
            const now = new Date();
            _state[popupId] = {
                view: 'month',         // 'month' | 'quarter' | 'year'
                cursor: { year: now.getFullYear(), month: now.getMonth() },
                startDate: null,
                endDate: null,
                hoveredDate: null,
            };
        }
        return _state[popupId];
    }

    /* ---- Helpers ---- */
    function fmtDate(d) {
        // "May 1, 2026"
        return MONTHS_SHORT[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
    }

    function fmtRange(s, e) {
        if (!s) return 'No range selected';
        if (!e) return fmtDate(s) + ' — pick end date';
        return fmtDate(s) + ' - ' + fmtDate(e);
    }

    function parseRange(str) {
        // Accepts: "May 1 - May 31, 2026" or "May 1, 2026 - May 31, 2026"
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
        const el = document.getElementById(popupId.replace('-popup', '-selection-label')
                        .replace('deals-cal', 'deals-cal')
                        .replace('sprf-cal', 'sprf-cal'));
        const labelId = popupId + '-selection-label';
        const lbl = document.getElementById(labelId) || document.querySelector('#' + popupId + ' #deals-cal-selection-label, #' + popupId + ' #sprf-cal-selection-label');
        if (lbl) lbl.textContent = fmtRange(st.startDate, st.endDate);
        const applyBtn = document.querySelector('#' + popupId + ' [id$="-cal-apply"]');
        if (applyBtn) applyBtn.disabled = !(st.startDate && st.endDate);
    }

    function renderGrid(popupId) {
        const st = getState(popupId);
        const grid = document.querySelector('#' + popupId + ' [id$="-cal-grid"]');
        if (!grid) return;

        // Update view button styles
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

        const _avail = window._sprfAvailMonths || [];
        const _ym = year + '-' + String(month + 1).padStart(2, '0');
        const _hasData = !_avail.length || _avail.includes(_ym);

        let html = `<div class="cal-nav-row">
            <button class="cal-nav-btn" data-nav="-1">&#8249;</button>
            <span class="cal-nav-title">${MONTHS[month]} ${year}</span>
            <button class="cal-nav-btn" data-nav="1">&#8250;</button>
        </div>
        ${_avail.length ? `<div class="text-center pb-2 -mt-1"><span class="inline-flex items-center gap-1 text-[9px] font-bold ${_hasData ? 'text-green-600' : 'text-amber-500'}">${_hasData ? '● Data available' : '◌ No data for this period'}</span></div>` : ''}
        <div class="cal-grid-month">`;

        DAYS.forEach(d => { html += `<div class="cal-day-head">${d}</div>`; });
        for (let i = 0; i < firstDay; i++) html += `<div></div>`;
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

        grid.querySelectorAll('.cal-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => calNav(popupId, parseInt(btn.dataset.nav)));
        });
        grid.addEventListener('click', function(e) {
            const target = e.target.closest('[data-pick="day"]');
            if (!target) return;
            const wrap = target.closest('.cal-day-wrap');
            if (wrap) calPickDay(popupId, parseInt(wrap.dataset.y), parseInt(wrap.dataset.m), parseInt(wrap.dataset.d));
        });
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
            const _avail = window._sprfAvailMonths || [];
            const _qHasData = !_avail.length || q.months.some(m => _avail.includes(year + '-' + String(m + 1).padStart(2, '0')));
            html += `<button class="cal-quarter-btn${isActive ? ' is-selected' : ''}${_avail.length && !_qHasData ? ' opacity-40' : ''}" data-qsy="${year}" data-qsm="${q.months[0]}" data-qey="${year}" data-qem="${q.months[2]+1}">
                        ${q.label}<span class="cal-quarter-sub">${MONTHS_SHORT[q.months[0]]}&ndash;${MONTHS_SHORT[q.months[2]]}</span>
                        ${_avail.length ? `<span style="display:block;font-size:8px;margin-top:2px;color:${_qHasData ? '#15803d' : '#f59e0b'}">${_qHasData ? '● data' : 'no data'}</span>` : ''}
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
            const _avail = window._sprfAvailMonths || [];
            const _yHasData = !_avail.length || _avail.some(m => m.startsWith(y + '-'));
            html += `<button class="cal-year-btn${isActive ? ' is-selected' : ''}${_avail.length && !_yHasData ? ' opacity-40' : ''}" data-y="${y}">${y}${_avail.length ? `<span style="display:block;font-size:8px;color:${_yHasData ? '#15803d' : '#f59e0b'}">${_yHasData ? '●' : '◌'}</span>` : ''}</button>`;
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

    /* ---- Public API ---- */
    window.openCalendarPopup = function(popupId, currentRange) {
        const popup = document.getElementById(popupId);
        if (!popup) return;
        const st = getState(popupId);
        // Pre-fill from current date range
        if (currentRange) {
            const parsed = parseRange(currentRange);
            if (parsed) {
                st.startDate = parsed.start;
                st.endDate = parsed.end;
                st.cursor = { year: parsed.start.getFullYear(), month: parsed.start.getMonth() };
            }
        }
        // Pre-fill text input
        const input = popup.querySelector('input[id$="-cal-text-input"]');
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
        const st = getState(popupId);
        st.cursor.year += dir;
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
        const st = getState(popupId);
        st.hoveredDate = null;
        renderGrid(popupId);
    };

    window.calPickDay = function(popupId, y, m, d) {
        const st = getState(popupId);
        const picked = new Date(y, m, d);
        if (!st.startDate || (st.startDate && st.endDate)) {
            // Start fresh
            st.startDate = picked;
            st.endDate = null;
        } else {
            // Pick end
            if (picked < st.startDate) {
                st.endDate = st.startDate;
                st.startDate = picked;
            } else {
                st.endDate = picked;
            }
        }
        // Sync text input
        const input = document.querySelector('#' + popupId + ' input[id$="-cal-text-input"]');
        if (input && st.startDate && st.endDate) input.value = fmtRange(st.startDate, st.endDate);
        updateSelectionLabel(popupId);
        renderGrid(popupId);
    };

    window.calPickRange = function(popupId, sy, sm, sd, ey, em, ed) {
        const st = getState(popupId);
        st.startDate = new Date(sy, sm, sd);
        st.endDate = new Date(ey, em, ed);
        const input = document.querySelector('#' + popupId + ' input[id$="-cal-text-input"]');
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
        // Highlight active preset
        document.querySelectorAll('#' + popupId + ' .cal-preset-btn').forEach(b => {
            b.className = 'cal-preset-btn px-3 py-1 rounded-full border text-[10px] font-bold transition ' +
                (b === btn ? 'bg-green-800 text-white border-green-800' : 'border-[#e3ddc9] text-gray-600 hover:bg-green-50 hover:border-green-700 hover:text-green-800');
        });
        const input = document.querySelector('#' + popupId + ' input[id$="-cal-text-input"]');
        if (input) input.value = fmtRange(s, e);
        updateSelectionLabel(popupId);
        renderGrid(popupId);
    };

    window.setCalView = function(btn, popupId, view) {
        getState(popupId).view = view;
        renderGrid(popupId);
    };

    window.onCalTextInput = function(input, popupId) {
        const errEl = document.querySelector('#' + popupId + ' [id$="-cal-input-err"]');
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
</script>
@endpush

@push('scripts')
<script>
/* ============================================================
   SPRF Filter Engine
   ============================================================ */
(function () {
    const filterPanel   = () => document.getElementById('sprf-filter-panel');
    const filterOverlay = () => document.getElementById('sprf-filter-overlay');
    const filterBadge   = () => document.getElementById('sprf-filter-badge');

    /* ---- Position the panel below the trigger button ---- */
    function positionPanel() {
        const trigger = document.getElementById('sprf-filter-trigger');
        const panel   = filterPanel();
        if (!trigger || !panel) return;
        const rect = trigger.getBoundingClientRect();
        panel.style.position = 'fixed';
        panel.style.top  = (rect.bottom + 8) + 'px';
        panel.style.right = (window.innerWidth - rect.right) + 'px';
        panel.style.left = 'auto';
    }

    /* ---- Open / Close ---- */
    window.toggleSprfFilter = function () {
        const panel = filterPanel();
        if (panel.classList.contains('hidden')) {
            positionPanel();
            panel.classList.remove('hidden');
            filterOverlay().classList.remove('hidden');
        } else {
            closeSprfFilter();
        }
    };

    window.closeSprfFilter = function () {
        filterPanel().classList.add('hidden');
        filterOverlay().classList.add('hidden');
    };

    /* ---- Apply ---- */
    window.applySprfFilter = function () {
        const params = new URLSearchParams(window.location.search);

        // Stages
        params.delete('stage[]');
        document.querySelectorAll('#sprf-filter-stages input[name="filter_stage"]:checked').forEach(cb => {
            params.append('stage[]', cb.value);
        });

        // Owner
        const owner = document.getElementById('sprf-filter-owner').value.trim();
        if (owner) params.set('owner', owner);
        else params.delete('owner');

        // Value range
        const min = document.getElementById('sprf-filter-min').value.trim();
        const max = document.getElementById('sprf-filter-max').value.trim();
        if (min) params.set('min_value', min);
        else params.delete('min_value');
        if (max) params.set('max_value', max);
        else params.delete('max_value');

        // Reset page
        params.delete('past_page');

        closeSprfFilter();
        window.location.href = '?' + params.toString();
    };

    /* ---- Clear ---- */
    window.clearSprfFilter = function () {
        document.querySelectorAll('#sprf-filter-stages input[name="filter_stage"]').forEach(cb => cb.checked = false);
        document.getElementById('sprf-filter-owner').value = '';
        document.getElementById('sprf-filter-min').value = '';
        document.getElementById('sprf-filter-max').value = '';

        // Remove filter params from URL but keep date_range
        const params = new URLSearchParams(window.location.search);
        params.delete('stage[]');
        params.delete('owner');
        params.delete('min_value');
        params.delete('max_value');
        params.delete('past_page');

        closeSprfFilter();
        window.location.href = '?' + params.toString();
    };

    /* ---- Populate from URL on load ---- */
    function populateFromUrl() {
        const params = new URLSearchParams(window.location.search);
        let activeCount = 0;

        // Stages
        const stages = params.getAll('stage[]');
        if (stages.length) {
            activeCount++;
            document.querySelectorAll('#sprf-filter-stages input[name="filter_stage"]').forEach(cb => {
                cb.checked = stages.includes(cb.value);
            });
        }

        // Owner
        const owner = params.get('owner');
        if (owner) {
            activeCount++;
            document.getElementById('sprf-filter-owner').value = owner;
        }

        // Value range
        const min = params.get('min_value');
        const max = params.get('max_value');
        if (min) { activeCount++; document.getElementById('sprf-filter-min').value = min; }
        if (max) { activeCount++; document.getElementById('sprf-filter-max').value = max; }

        // Badge
        const badge = filterBadge();
        if (activeCount > 0) {
            badge.textContent = activeCount;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', populateFromUrl);
})();

/* ============================================================
   Table Sorting and Column Filters (Client-Side)
   ============================================================ */
(function() {
    let sortDirections = {};

    window.sortSPRFTable = function(tableId, colIndex, type, headerEl) {
        const el = document.getElementById(tableId);
        if (!el) return;
        const tbody = el.tagName === 'TBODY' ? el : el.querySelector('tbody');
        if (!tbody) return;
        
        // Exclude empty rows
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.innerText.includes('No ongoing deals') && !r.innerText.includes('No past deals') && !r.innerText.includes('No recent deals'));
        if (rows.length === 0) return;
        
        // Reset header colors in this table, make selected blue
        const headerRow = headerEl.closest('tr');
        headerRow.querySelectorAll('th').forEach(th => {
            th.classList.remove('text-blue-600');
            th.classList.add('text-gray-950');
            const icon = th.querySelector('i');
            if (icon && !icon.classList.contains('fa-filter')) {
                icon.className = 'fas fa-sort text-[10px] text-gray-400';
            }
        });
        
        headerEl.classList.remove('text-gray-950');
        headerEl.classList.add('text-blue-600');
        
        // Toggle direction
        const dirKey = tableId + '_' + colIndex;
        const isAsc = !sortDirections[dirKey];
        sortDirections[dirKey] = isAsc;
        
        const arrowIcon = headerEl.querySelector('i');
        if (arrowIcon) {
            arrowIcon.className = isAsc ? 'fas fa-sort-up text-[10px] text-blue-600' : 'fas fa-sort-down text-[10px] text-blue-600';
        }

        const parseVal = (td) => {
            if (!td) return '';
            let text = td.innerText.trim();
            if (type === 'currency') {
                return parseFloat(text.replace(/[^\d.-]/g, '')) || 0;
            }
            if (type === 'date') {
                return new Date(text) || new Date(0);
            }
            return text.toLowerCase();
        };

        rows.sort((a, b) => {
            const valA = parseVal(a.cells[colIndex]);
            const valB = parseVal(b.cells[colIndex]);
            if (valA < valB) return isAsc ? -1 : 1;
            if (valA > valB) return isAsc ? 1 : -1;
            return 0;
        });

        // Re-append rows
        rows.forEach(row => tbody.appendChild(row));
    };

    window.toggleStageFilterMenu = function(tableId, headerEl, event) {
        event.stopPropagation();
        
        // Remove existing temporary stage menus
        const existingMenu = document.getElementById('sprf-stage-menu');
        if (existingMenu) {
            existingMenu.remove();
            if (existingMenu.dataset.header === headerEl.outerHTML) return;
        }

        const el = document.getElementById(tableId);
        if (!el) return;
        const tbody = el.tagName === 'TBODY' ? el : el.querySelector('tbody');
        if (!tbody) return;
        
        const rows = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.innerText.includes('No ongoing deals') && !r.innerText.includes('No past deals') && !r.innerText.includes('No recent deals'));
        if (rows.length === 0) return;
        
        // Find unique stages in current table data
        const stages = new Set();
        rows.forEach(r => {
            const stageCell = r.cells[2];
            if (stageCell) {
                const stageText = stageCell.innerText.trim();
                if (stageText) stages.add(stageText);
            }
        });

        // Create dropdown popover
        const menu = document.createElement('div');
        menu.id = 'sprf-stage-menu';
        menu.className = 'absolute bg-white border border-[#eedcbe] rounded-xl shadow-xl p-3 z-50 flex flex-col gap-2 min-w-[140px] text-xs font-semibold text-gray-700';
        menu.dataset.header = headerEl.outerHTML;

        // Position it below header
        const rect = headerEl.getBoundingClientRect();
        menu.style.top = (rect.bottom + window.scrollY + 6) + 'px';
        menu.style.left = (rect.left + window.scrollX) + 'px';

        // Add checkboxes
        stages.forEach(st => {
            const label = document.createElement('label');
            label.className = 'flex items-center gap-2 cursor-pointer select-none py-1 hover:text-gray-950';
            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.value = st;
            cb.checked = true;
            cb.className = 'rounded text-green-700 focus:ring-green-700';
            
            // Check current active filter state
            const currentFilter = headerEl.dataset.filterStage;
            if (currentFilter) {
                const activeFilters = currentFilter.split(',');
                cb.checked = activeFilters.includes(st);
            }

            cb.addEventListener('change', () => {
                const checkedStages = Array.from(menu.querySelectorAll('input:checked')).map(i => i.value);
                headerEl.dataset.filterStage = checkedStages.join(',');
                
                // Style header
                if (checkedStages.length < stages.size) {
                    headerEl.classList.add('text-blue-600');
                    headerEl.querySelector('i').className = 'fas fa-filter text-[10px] text-blue-600';
                } else {
                    headerEl.classList.remove('text-blue-600');
                    headerEl.querySelector('i').className = 'fas fa-filter text-[10px] text-gray-400';
                }

                // Filter rows
                rows.forEach(row => {
                    const rowStage = row.cells[2].innerText.trim();
                    row.style.display = checkedStages.includes(rowStage) ? '' : 'none';
                });
            });

            label.appendChild(cb);
            label.appendChild(document.createTextNode(' ' + st));
            menu.appendChild(label);
        });

        document.body.appendChild(menu);

        // Click outside closes it
        const clickOutside = (e) => {
            if (!menu.contains(e.target) && e.target !== headerEl) {
                menu.remove();
                document.removeEventListener('click', clickOutside);
            }
        };
        setTimeout(() => document.addEventListener('click', clickOutside), 10);
    };
})();
</script>
@endpush
