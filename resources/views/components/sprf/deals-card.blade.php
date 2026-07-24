@props([
    'title',
    'tableId',
    'deals',
    'stageClasses' => [],
    'emptyMessage',
    'q' => '',
    'showPagination' => false,
    'paginator' => null,
])

@php
    $rows = $deals instanceof \Illuminate\Contracts\Pagination\Paginator || $deals instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
        ? $deals->items()
        : $deals;
@endphp

<div class="bg-[#fffefb] border border-[#eedcbe] rounded-[24px] p-8 shadow-sm">
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-[19px] font-bold text-gray-900 m-0">{{ $title }}</h2>
        @if ($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>

    <div class="mx-[-32px] bg-[#faf8f2] border-y border-[#eedcbe] px-8 py-2 overflow-x-auto">
        <table class="w-full text-xs text-left text-gray-800 border-collapse">
            <thead>
                <tr class="border-b border-[#eedcbe]" data-table-id="{{ $tableId }}">
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('{{ $tableId }}', 0, 'string', this)">
                        <span class="flex items-center gap-1">Deal Name <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                    </th>
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('{{ $tableId }}', 1, 'string', this)">
                        <span class="flex items-center gap-1">Customer <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                    </th>
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none relative">
                        <span class="flex items-center gap-1 cursor-pointer hover:text-blue-600 transition" onclick="toggleStageFilterMenu('{{ $tableId }}', this, event)">
                            Stage <i class="fas fa-filter text-[10px] text-gray-400"></i>
                        </span>
                    </th>
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('{{ $tableId }}', 3, 'currency', this)">
                        <span class="flex items-center gap-1">Value <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                    </th>
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('{{ $tableId }}', 4, 'date', this)">
                        <span class="flex items-center gap-1">Expected Close <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                    </th>
                    <th class="pb-3 px-2 font-bold text-gray-950 select-none cursor-pointer hover:text-blue-600 transition" onclick="sortSPRFTable('{{ $tableId }}', 5, 'string', this)">
                        <span class="flex items-center gap-1">Owner <i class="fas fa-sort text-[10px] text-gray-400"></i></span>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f5f2e9]" id="{{ $tableId }}">
                @forelse ($rows as $deal)
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
                        <td colspan="6" class="py-8 text-center text-gray-400 font-semibold">{{ $emptyMessage }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($showPagination && $paginator)
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6 pt-5 border-t border-[#eedcbe]">
            <div class="text-xs text-gray-500 font-bold">
                Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} entries
            </div>
            <div class="flex items-center gap-3">
                @if ($paginator->lastPage() > 1)
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs text-gray-500 font-bold">Go to:</span>
                        <select onchange="window.location.href = this.value" class="bg-white border border-[#eedcbe] rounded-lg px-2 py-1.5 text-[10px] font-bold text-gray-600 focus:outline-none focus:ring-1 focus:ring-green-700 shadow-sm cursor-pointer">
                            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                                <option value="{{ $paginator->appends(request()->query())->url($i) }}" {{ $i == $paginator->currentPage() ? 'selected' : '' }}>
                                    Page {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                @endif
                <div class="flex items-center gap-1.5">
                    @if ($paginator->onFirstPage())
                        <button class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-gray-400 select-none cursor-not-allowed" disabled>
                            Previous
                        </button>
                    @else
                        <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                            Previous
                        </a>
                    @endif

                    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button class="px-3.5 py-2 rounded-lg bg-green-800 text-[10px] text-white font-bold select-none cursor-default">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $paginator->appends(request()->query())->url($page) }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="px-3 py-2 rounded-lg border border-[#eedcbe] bg-white text-[10px] font-bold text-[#a67c00] hover:bg-gray-50 transition duration-150 cursor-pointer shadow-sm">
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
    @endif
</div>
