@extends('layouts.app')

@section('title', 'AmbatuGrow - ASSCM')

@section('content')
<div class="space-y-6" id="mainContent">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">After-Sales Support and Case Management</h1>
        <p class="text-sm text-gray-500 mt-1">Supports issue resolution, service requests, and warranty claims after the sale is completed.</p>
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
                    <th class="py-4 px-6">Assigned To</th>
                    <th class="py-4 px-6 text-center">Action</th>
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
                        $assignedDisplay = $case->assigned_to ?: '—';
                    @endphp
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-4 px-6 font-medium text-emerald-600">{{ $case->case_id }}</td>
                        <td class="py-4 px-6 font-semibold text-gray-700">
                            {{ $case->customer ? $case->customer->first_name . ' ' . $case->customer->last_name : '—' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500 max-w-[240px] truncate" title="{{ $case->issue }}">{{ $case->issue }}</td>
                        <td class="py-4 px-6 text-center font-semibold {{ $priorityClass }}">{{ $case->priority }}</td>
                        <td class="py-4 px-6 text-center font-semibold {{ $statusClass }}">{{ $case->status }}</td>
                        <td class="py-4 px-6 text-gray-700">{{ $assignedDisplay }}</td>
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- View button -->
                                <button onclick="openCaseViewModal('{{ $case->case_id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition cursor-pointer" title="View case details">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                                <!-- Edit button -->
                                <button onclick="openCaseEditModal('{{ $case->case_id }}', '{{ $case->customer ? addslashes($case->customer->first_name . ' ' . $case->customer->last_name) : '' }}', '{{ addslashes($case->issue) }}', '{{ $case->priority }}', '{{ $case->status }}', '{{ addslashes($case->assigned_to ?? '') }}')" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-amber-50 hover:text-amber-600 hover:border-amber-200 transition cursor-pointer" title="Edit case">
                                    <i class="fas fa-pencil-alt text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-500">No cases found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $cases])
    </div>
</div>


{{-- View Case Modal --}}
<div id="caseViewModalOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 max-h-[90vh] overflow-y-auto" id="caseViewModalCard">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-bold text-gray-900"><i class="fas fa-info-circle text-blue-600 mr-2"></i>Case Details</h2>
            <button onclick="closeCaseViewModal()" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs cursor-pointer">✕</button>
        </div>
        <div id="caseViewContent" class="space-y-4">
            <div class="flex items-center justify-center py-8">
                <i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i>
            </div>
        </div>
        <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
            <button onclick="closeCaseViewModal()" class="px-5 py-2 rounded-lg bg-gray-100 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition cursor-pointer">Close</button>
        </div>
    </div>
</div>

{{-- Edit Case Modal --}}
<div id="caseEditModalOverlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/60 opacity-0 pointer-events-none transition-opacity duration-200">
    <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-2xl p-7 transform translate-y-1.5 transition-transform duration-200 max-h-[90vh] overflow-y-auto" id="caseEditModalCard">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-base font-bold text-gray-900"><i class="fas fa-pencil-alt text-amber-600 mr-2"></i>Edit Case</h2>
            <button onclick="closeCaseEditModal()" class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition text-xs cursor-pointer">✕</button>
        </div>
        <form id="caseEditForm" method="POST">
            @csrf
            <div class="space-y-4">
                <!-- Customer (read-only) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Customer</label>
                    <input type="text" id="edit_case_customer" readonly
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-gray-50 text-gray-500 cursor-not-allowed" />
                </div>

                <!-- Assigned To -->
                <div>
                    <label for="edit_assigned_to" class="block text-sm font-semibold text-gray-700 mb-1.5">Assigned To</label>
                    <select name="assigned_to" id="edit_assigned_to"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600">
                        <option value="">— Unassigned —</option>
                        @foreach ($reps as $rep)
                            <option value="{{ $rep->first_name }} {{ $rep->last_name }}">{{ $rep->first_name }} {{ $rep->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="edit_status" class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
                    <select name="status" id="edit_status"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600">
                        <option value="Open">Open</option>
                        <option value="Pending">Pending</option>
                        <option value="Escalated">Escalated</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>

                <!-- Priority -->
                <div>
                    <label for="edit_priority" class="block text-sm font-semibold text-gray-700 mb-1.5">Priority</label>
                    <select name="priority" id="edit_priority"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <!-- Issue -->
                <div>
                    <label for="edit_issue" class="block text-sm font-semibold text-gray-700 mb-1.5">Issue</label>
                    <textarea name="issue" id="edit_issue" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm resize-y min-h-[60px] focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeCaseEditModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition cursor-pointer">Cancel</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-green-700 text-sm font-semibold text-white hover:bg-green-800 transition cursor-pointer">
                    <i class="fas fa-save mr-1.5"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const mainContent   = document.getElementById('mainContent');

    // ─── View Case Modal ───
    function openCaseViewModal(caseId) {
        const overlay = document.getElementById('caseViewModalOverlay');
        const card = document.getElementById('caseViewModalCard');
        const content = document.getElementById('caseViewContent');
        if (!overlay || !card || !content) return;

        // Show loading
        content.innerHTML = '<div class="flex items-center justify-center py-8"><i class="fas fa-spinner fa-spin text-gray-400 text-xl"></i></div>';

        // Show modal
        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100', 'pointer-events-auto');
        card.classList.remove('translate-y-1.5');
        card.classList.add('translate-y-0');
        mainContent?.classList.add('blur-sm', 'pointer-events-none', 'select-none');

        // Fetch case details
        fetch('/asscm/' + encodeURIComponent(caseId) + '/view')
            .then(res => {
                if (!res.ok) throw new Error('Failed to fetch');
                return res.json();
            })
            .then(data => {
                const priorityColors = { 'High': 'text-red-600', 'Medium': 'text-amber-600', 'Low': 'text-emerald-600' };
                const statusColors = { 'Open': 'text-amber-600', 'Pending': 'text-blue-600', 'Escalated': 'text-purple-600', 'Resolved': 'text-emerald-600' };
                content.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2 bg-gray-50 rounded-lg p-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Case ID</span>
                            <p class="text-lg font-bold text-emerald-700 mt-1">${escapeHtml(data.case_id)}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Customer</span>
                            <p class="text-sm font-semibold text-gray-800 mt-1">${escapeHtml(data.customer)}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Assigned To</span>
                            <p class="text-sm font-semibold text-gray-800 mt-1">${escapeHtml(data.assigned_to || '— Unassigned —')}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Priority</span>
                            <p class="text-sm font-bold mt-1 ${priorityColors[data.priority] || 'text-gray-600'}">${escapeHtml(data.priority)}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status</span>
                            <p class="text-sm font-bold mt-1 ${statusColors[data.status] || 'text-gray-600'}">${escapeHtml(data.status)}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Issue</span>
                            <p class="text-sm text-gray-700 mt-1 bg-white rounded-lg p-3 border border-gray-100">${escapeHtml(data.issue)}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Created At</span>
                            <p class="text-xs text-gray-500 mt-1">${escapeHtml(data.created_at)}</p>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Last Updated</span>
                            <p class="text-xs text-gray-500 mt-1">${escapeHtml(data.updated_at)}</p>
                        </div>
                    </div>
                `;
            })
            .catch(err => {
                content.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>Failed to load case details.</div>';
                console.error(err);
            });
    }

    function closeCaseViewModal() {
        const overlay = document.getElementById('caseViewModalOverlay');
        const card = document.getElementById('caseViewModalCard');
        if (!overlay || !card) return;
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100', 'pointer-events-auto');
        card.classList.add('translate-y-1.5');
        card.classList.remove('translate-y-0');
        mainContent?.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
    }

    // ─── Edit Case Modal ───
    function openCaseEditModal(caseId, customer, issue, priority, status, assignedTo) {
        const overlay = document.getElementById('caseEditModalOverlay');
        const card = document.getElementById('caseEditModalCard');
        if (!overlay || !card) return;

        document.getElementById('edit_case_customer').value = customer;
        document.getElementById('edit_issue').value = issue;
        document.getElementById('edit_priority').value = priority;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_assigned_to').value = assignedTo;

        // Set form action
        document.getElementById('caseEditForm').action = '/asscm/' + encodeURIComponent(caseId) + '/update';

        overlay.classList.remove('opacity-0', 'pointer-events-none');
        overlay.classList.add('opacity-100', 'pointer-events-auto');
        card.classList.remove('translate-y-1.5');
        card.classList.add('translate-y-0');
        mainContent?.classList.add('blur-sm', 'pointer-events-none', 'select-none');
    }

    function closeCaseEditModal() {
        const overlay = document.getElementById('caseEditModalOverlay');
        const card = document.getElementById('caseEditModalCard');
        if (!overlay || !card) return;
        overlay.classList.add('opacity-0', 'pointer-events-none');
        overlay.classList.remove('opacity-100', 'pointer-events-auto');
        card.classList.add('translate-y-1.5');
        card.classList.remove('translate-y-0');
        mainContent?.classList.remove('blur-sm', 'pointer-events-none', 'select-none');
    }

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    document.getElementById('caseViewModalOverlay')?.addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeCaseViewModal();
    });

    document.getElementById('caseEditModalOverlay')?.addEventListener('click', (e) => {
        if (e.target === e.currentTarget) closeCaseEditModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!document.getElementById('caseViewModalOverlay')?.classList.contains('opacity-0')) {
                closeCaseViewModal();
            } else if (!document.getElementById('caseEditModalOverlay')?.classList.contains('opacity-0')) {
                closeCaseEditModal();
            }
        }
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
            debounceTimer = setTimeout(applySearch, 800);
        });

        caseSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { clearTimeout(debounceTimer); applySearch(); }
        });
    }
</script>
@endpush
