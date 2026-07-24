@extends('layouts.app')

@section('title', 'AmbatuGrow - Deals')

@push('styles')
    @vite(['resources/css/pages/sprf-deals.css'])
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
<div id="sprf-deals-page" class="sprf-deals-page space-y-6" data-available-months='@json($availableMonths)'>
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
                <span id="sprf-filter-badge" class="absolute -top-1.5 -right-1.5 hidden w-4 h-4 items-center justify-center rounded-full bg-green-700 text-white text-[8px] font-bold">0</span>
            </div>
            
            <!-- Download button (Functional, exports ongoing deals) -->
            <button onclick="downloadCSV('ongoingDealsTable', 'SPRF_Ongoing_Deals.csv')" class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
                <i class="fas fa-download text-sm"></i>
            </button>
        </div>
    </div>

    <x-sprf.deals-card
        title="On-Going Deals"
        table-id="ongoingDealsTable"
        :deals="$ongoingDeals"
        :stage-classes="$ongoingStageClasses"
        empty-message="No ongoing deals found for this date range."
        :q="$q"
    >
        <span class="text-xs text-gray-500 font-bold select-none cursor-pointer hover:underline" onclick="downloadCSV('ongoingDealsTable', 'SPRF_Ongoing_Deals.csv')">Export CSV</span>
    </x-sprf.deals-card>

    <x-sprf.deals-card
        title="Past Deals"
        table-id="pastDealsTable"
        :deals="$pastDeals"
        :stage-classes="$pastStageClasses"
        empty-message="No past deals found for this date range."
        :q="$q"
        show-pagination
        :paginator="$pastDeals"
    >
        <span class="text-xs text-gray-500 font-bold select-none cursor-pointer hover:underline" onclick="downloadCSV('pastDealsTable', 'SPRF_Past_Deals.csv')">Export CSV</span>
    </x-sprf.deals-card>

<!-- ==================== SPRF Filter Popover ==================== -->
<div id="sprf-filter-overlay" class="fixed inset-0 z-40 hidden" onclick="closeSprfFilter()"></div>
<div id="sprf-filter-panel" class="sprf-filter-panel fixed z-50 hidden">
    <div class="sprf-filter-panel-body w-85 max-w-[95vw] bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden mt-2 mr-2">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 pt-5 pb-3 border-b border-gray-200">
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
    <div class="sprf-calendar-panel absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-95 max-w-[95vw] bg-[#fffefb] border border-[#eedcbe] rounded-[20px] shadow-2xl overflow-hidden">
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

</div><!-- /sprf-deals-page -->

@endsection

@push('scripts')
<script src="{{ asset('js/sprf-deals.js') }}?v={{ filemtime(public_path('js/sprf-deals.js')) }}"></script>
@endpush
