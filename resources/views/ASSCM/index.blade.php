@extends('layouts.app')

@section('title', 'AmbatuGrow - ASSCM')

@section('content')
<div class="space-y-6" id="mainContent">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">After-Sales Support and Case Management</h1>
            <p class="text-sm text-gray-500 mt-1">Supports issue resolution, service requests, and warranty claims after the sale is completed.</p>
        </div>
        <button type="button" id="openCaseModalBtn" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New case
        </button>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Status summary cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border-l-4 border-amber-400 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Open</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Open'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-blue-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Pending</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Pending'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-purple-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Escalated</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Escalated'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-emerald-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Resolved</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Resolved'] }}</span>
        </div>
    </div>

    {{-- Search bar --}}
    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Search</label>
        <div class="relative">
            <input
                type="text"
                id="caseSearch"
                name="q"
                value="{{ old('q', $q) }}"
                placeholder="Search customer name or issue..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
            />
            <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
        </div>
    </div>

    {{-- Status filter pills --}}
    @php
        $caseFilters = [
            ['label' => 'All',       'value' => '',          'color' => 'gray'],
            ['label' => 'Open',      'value' => 'Open',      'color' => 'amber'],
            ['label' => 'Pending',   'value' => 'Pending',   'color' => 'blue'],
            ['label' => 'Escalated', 'value' => 'Escalated', 'color' => 'purple'],
            ['label' => 'Resolved',  'value' => 'Resolved',  'color' => 'emerald'],
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach ($caseFilters as $cf)
            @php
                $isActive = $activeStatus === $cf['value'];
                $baseUrl  = url()->current();
                $params   = array_filter(['q' => $q, 'status' => $cf['value']]);
                $href     = $baseUrl . ($params ? '?' . http_build_query($params) : '');
                $c        = $cf['color'];
                $activeClass = match($c) {
                    'amber'   => 'bg-amber-500 text-white border-amber-500',
                    'blue'    => 'bg-blue-600 text-white border-blue-600',
                    'purple'  => 'bg-purple-600 text-white border-purple-600',
                    'emerald' => 'bg-emerald-600 text-white border-emerald-600',
                    default   => 'bg-gray-700 text-white border-gray-700',
                };
                $inactiveClass = match($c) {
                    'amber'   => 'border-amber-200 bg-amber-50 text-amber-600 hover:bg-amber-100',
                    'blue'    => 'border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100',
                    'purple'  => 'border-purple-200 bg-purple-50 text-purple-600 hover:bg-purple-100',
                    'emerald' => 'border-emerald-200 bg-emerald-50 text-emerald-600 hover:bg-emerald-100',
                    default   => 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50',
                };
            @endphp
            <a href="{{ $href }}"
               class="px-4 py-1 text-xs border rounded-md font-semibold transition {{ $isActive ? $activeClass : $inactiveClass }}">
                {{ $cf['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Cases table --}}

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="py-4 px-6">Case ID</th>
                    <th class="py-4 px-6">Customer</th>
                    <th class="py-4 px-6">Issue</th>
                    <th class="py-4 px-6 text-center">Priority</th>
                    <th class="py-4 px-6 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($cases as $case)
                    @php
                        $priorityClass = match($case->priority) {
                            'High'   => 'text-red-600',
                            'Medium' => 'text-amber-600',
                            default  => 'text-emerald-600',
                        };
                        $statusClass = match($case->status) {
                            'Open'      => 'text-amber-600',
                            'Pending'   => 'text-blue-600',
                            'Escalated' => 'text-purple-600',
                            'Resolved'  => 'text-emerald-600',
                            default     => 'text-gray-600',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-4 px-6 font-medium text-emerald-600">{{ $case->case_id }}</td>
                        <td class="py-4 px-6 font-semibold text-gray-700">
                            {{ $case->customer ? $case->customer->first_name . ' ' . $case->customer->last_name : '—' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $case->issue }}</td>
                        <td class="py-4 px-6 text-center font-semibold {{ $priorityClass }}">{{ $case->priority }}</td>
                        <td class="py-4 px-6 text-center font-semibold {{ $statusClass }}">{{ $case->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-gray-500">No cases found. Click "New case" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $cases])
    </div>
</div>

{{-- New Case Modal --}}
<div id="caseModalOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200" id="caseModalCard">
        <h2 class="text-base font-bold text-gray-900 mb-5">New Support Case</h2>

        <form action="{{ route('asscm.store') }}" method="POST" id="caseForm">
            @csrf

            <div class="mb-4">
                <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Customer</label>
                <select name="customer_id" id="customer_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('customer_id') border-red-400 @enderror"
                    required>
                    <option value="" disabled selected>Select customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->customer_id }}" {{ old('customer_id') === $customer->customer_id ? 'selected' : '' }}>
                            {{ $customer->first_name }} {{ $customer->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="issue" class="block text-sm font-semibold text-gray-700 mb-1.5">Issue</label>
                <textarea name="issue" id="issue" rows="4" placeholder="Describe the issue..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-y min-h-[80px] focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('issue') border-red-400 @enderror"
                    required>{{ old('issue') }}</textarea>
                @error('issue')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1.5">Priority</label>
                <select name="priority" id="priority"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600 @error('priority') border-red-400 @enderror"
                    required>
                    <option value="" disabled selected>Select priority</option>
                    <option value="Low"    {{ old('priority') === 'Low'    ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High"   {{ old('priority') === 'High'   ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end mt-5">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg text-sm font-semibold shadow-sm transition">
                    Save Case
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const mainContent   = document.getElementById('mainContent');
    const modalOverlay  = document.getElementById('caseModalOverlay');
    const modalCard     = document.getElementById('caseModalCard');
    const openModalBtn  = document.getElementById('openCaseModalBtn');
    const caseForm      = document.getElementById('caseForm');

    function openModal() {
        if (!modalOverlay || !modalCard) return;
        modalOverlay.classList.remove('opacity-0', 'pointer-events-none');
        modalOverlay.classList.add('opacity-100', 'pointer-events-auto');
        modalCard.classList.remove('translate-y-1.5');
        modalCard.classList.add('translate-y-0');
        mainContent?.classList.add('blur-sm', 'pointer-events-none', 'select-none');
    }

    function closeModal() {
        if (!modalOverlay || !modalCard) return;
        modalOverlay.classList.add('opacity-0', 'pointer-events-none');
        modalOverlay.classList.remove('opacity-100', 'pointer-events-auto');
        modalCard.classList.add('translate-y-1.5');
        modalCard.classList.remove('translate-y-0');
        mainContent?.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
        caseForm?.reset();
    }

    openModalBtn?.addEventListener('click', openModal);

    modalOverlay?.addEventListener('click', (e) => {
        if (e.target === modalOverlay) closeModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeModal();
    });

    // Case search — fires as you type (300 ms debounce), preserves status filter
    const caseSearch = document.getElementById('caseSearch');
    const currentCaseStatus = @json($activeStatus);
    if (caseSearch) {
        const applySearch = () => {
            const value = caseSearch.value.trim();
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            if (currentCaseStatus) url.searchParams.set('status', currentCaseStatus);
            else url.searchParams.delete('status');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        let debounceTimer;
        caseSearch.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applySearch, 300);
        });

        caseSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { clearTimeout(debounceTimer); applySearch(); }
        });
    }

    @if ($errors->any())
        openModal();
    @endif
</script>
@endpush
