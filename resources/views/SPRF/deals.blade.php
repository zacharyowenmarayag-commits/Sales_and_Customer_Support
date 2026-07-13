@extends('layouts.app')

@section('title', 'AmbatuGrow - Deals')

@section('content')
<div class="space-y-6 py-6">
    <!-- Header & Toolbar Row -->
    <div class="flex justify-between items-center">
        <a href="{{ route('sprf', ['date_range' => $dateRange]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-green-800 transition duration-150 select-none">
            <i class="fas fa-arrow-left text-[10px]"></i> Back to Dashboard
        </a>
        <div class="flex items-center gap-3">
            <!-- Calendar icon button -->
            <button class="w-10 h-10 flex items-center justify-center bg-white border border-[#e3ddc9] rounded-lg text-[#8a8a7a] hover:bg-gray-50 hover:text-gray-600 transition duration-150 cursor-pointer shadow-sm">
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
