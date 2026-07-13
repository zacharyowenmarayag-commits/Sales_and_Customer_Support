@extends('layouts.app')

@section('title', 'AmbatuGrow - Deals')

@push('styles')
<style>
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
</style>
@endpush

@section('content')
<div class="space-y-6 py-6">
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
                    <option value="May 1 - May 31, 2026" {{ $dateRange == 'May 1 - May 31, 2026' ? 'selected' : '' }}>May 1 - May 31, 2026</option>
                    <option value="Apr 1 - Apr 30, 2026" {{ $dateRange == 'Apr 1 - Apr 30, 2026' ? 'selected' : '' }}>Apr 1 - Apr 30, 2026</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fas fa-chevron-down text-[10px]"></i>
                </div>
            </div>
            
            <!-- Filter button -->
            <button class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                <i class="fas fa-filter text-sm"></i>
            </button>
            
            <!-- Download button (Functional, exports ongoing deals) -->
            <button onclick="downloadCSV('ongoingDealsTable', 'SPRF_Ongoing_Deals.csv')" class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                <i class="fas fa-download text-sm"></i>
            </button>
        </div>
    </div>

    <!-- On-Going Deals Card -->
    <div class="bg-[#fffefb] border border-[#eedcbe] rounded-[24px] p-8 shadow-sm">
        <h2 class="text-[19px] font-bold text-gray-900 mb-5">On-Going Deals</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-800 border-collapse">
                <thead>
                    <tr class="border-b border-[#eedcbe]">
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Deal Name <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Customer <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Stage <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-[#3aa0c9] select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-[#2d83a5] transition duration-150">
                                Value <i class="fas fa-chevron-down text-[10px] text-[#3aa0c9]/75"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Expected Close <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Owner <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
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
                        <tr class="hover:bg-[#fffcf4]/50 transition duration-150">
                            <td class="py-4 px-2 font-medium text-gray-950">{{ $deal->name }}</td>
                            <td class="py-4 px-2 text-gray-800">{{ $deal->customer }}</td>
                            <td class="py-4 px-2">
                                <span class="inline-block px-3 py-1 rounded-[6px] {{ $stageClasses[$deal->stage] ?? 'bg-gray-100 text-gray-800' }} text-[10px] font-bold">
                                    {{ $deal->stage }}
                                </span>
                            </td>
                            <td class="py-4 px-2 font-bold text-gray-950">{{ $deal->value }}</td>
                            <td class="py-4 px-2">{{ $deal->expected_close }}</td>
                            <td class="py-4 px-2 font-semibold text-gray-900">{{ $deal->owner }}</td>
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
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left text-gray-800 border-collapse">
                <thead>
                    <tr class="border-b border-[#eedcbe]">
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Deal Name <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Customer <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Stage <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-[#3aa0c9] select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-[#2d83a5] transition duration-150">
                                Value <i class="fas fa-chevron-down text-[10px] text-[#3aa0c9]/75"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Expected Close <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
                        </th>
                        <th class="pb-3 px-2 font-bold text-gray-950 select-none">
                            <span class="flex items-center gap-1 cursor-pointer hover:text-gray-600 transition duration-150">
                                Owner <i class="fas fa-chevron-down text-[10px] text-gray-500"></i>
                            </span>
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
                        <tr class="hover:bg-[#fffcf4]/50 transition duration-150">
                            <td class="py-4 px-2 font-medium text-gray-950">{{ $deal->name }}</td>
                            <td class="py-4 px-2 text-gray-800">{{ $deal->customer }}</td>
                            <td class="py-4 px-2">
                                <span class="inline-block px-3 py-1 rounded-[6px] {{ $pastStageClasses[$deal->stage] ?? 'bg-gray-100 text-gray-800' }} text-[10px] font-bold">
                                    {{ $deal->stage }}
                                </span>
                            </td>
                            <td class="py-4 px-2 font-bold text-gray-950">{{ $deal->value }}</td>
                            <td class="py-4 px-2">{{ $deal->expected_close }}</td>
                            <td class="py-4 px-2 font-semibold text-gray-900">{{ $deal->owner }}</td>
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
                Showing 1 to {{ $pastDeals->count() }} of {{ $pastDeals->count() }} entries
            </div>
            <div class="flex items-center gap-1.5">
                <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-gray-400 select-none cursor-not-allowed" disabled>
                    Previous
                </button>
                <button class="px-3.5 py-2 rounded-lg bg-green-800 text-[10px] text-white font-bold select-none cursor-default">
                    1
                </button>
                @if ($pastDeals->count() > 4)
                    <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                        2
                    </button>
                    <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                        3
                    </button>
                    <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                        Next
                    </button>
                @else
                    <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-gray-400 select-none cursor-not-allowed" disabled>
                        Next
                    </button>
                @endif
            </div>
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

        let html = `<div class="cal-nav-row">
            <button class="cal-nav-btn" data-nav="-1">&#8249;</button>
            <span class="cal-nav-title">${MONTHS[month]} ${year}</span>
            <button class="cal-nav-btn" data-nav="1">&#8250;</button>
        </div>
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
