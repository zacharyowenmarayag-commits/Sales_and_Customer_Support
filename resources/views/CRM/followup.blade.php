@extends('layouts.app')

@section('title', 'AmbatuGrow - Follow-Ups')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Follow-Ups</h1>
            <p class="text-sm text-gray-500 mt-1">Organize customer follow-ups and next-step reminders.</p>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Search</label>
        <div class="relative">
            <input
                type="text"
                name="q"
                value="{{ old('q', $q) }}"
                id="followupSearch"
                placeholder="Search customer name..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
            />
            <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
        </div>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Filter by Status</label>
        <div class="flex flex-wrap gap-2">
            @php
                $filterParams = array_filter(['q' => $q]);
            @endphp
            <a
                href="{{ route('crm.followup', $filterParams) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium border transition {{ empty($status) ? 'bg-green-700 text-white border-green-700' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}"
            >
                All
            </a>
            @foreach (\App\Models\FollowUp::STATUSES as $filterStatus)
                @php
                    $isActive = $status === $filterStatus;
                    $statusParams = array_filter(['q' => $q, 'status' => $filterStatus]);
                    $activeClasses = match ($filterStatus) {
                        'Completed' => 'bg-blue-600 text-white border-blue-600',
                        'Cancelled' => 'bg-gray-600 text-white border-gray-600',
                        default => 'bg-yellow-500 text-white border-yellow-500',
                    };
                @endphp
                <a
                    href="{{ route('crm.followup', $statusParams) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium border transition {{ $isActive ? $activeClasses : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}"
                >
                    {{ $filterStatus }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Follow-Up ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Task</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Due Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($followUps as $followUp)
                    @php
                        $statusClasses = match ($followUp->status) {
                            'Completed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'Cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                            default => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        };
                    @endphp
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-900">FU-{{ str_pad($followUp->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $followUp->task }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $followUp->customer }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $followUp->due_date ? $followUp->due_date->format('M j, Y') : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('crm.followup.update', $followUp->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select
                                    name="status"
                                    onchange="this.form.submit()"
                                    class="status-select inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-green-600 {{ $statusClasses }}"
                                >
                                    @foreach (\App\Models\FollowUp::STATUSES as $status)
                                        <option value="{{ $status }}" {{ $followUp->status === $status ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            @if ($status)
                                No {{ strtolower($status) }} follow-ups found.
                            @else
                                No follow-ups yet. Add a communication log with subject "Order Follow-up" to create one automatically.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $followUps])
    </div>
</div>
@endsection

@push('styles')
<style>
    .status-select option {
        background-color: #fff;
        color: #1f2937;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.status-select').forEach((select) => {
        const classes = {
            Pending: ['bg-yellow-100', 'text-yellow-800', 'border-yellow-200'],
            Completed: ['bg-blue-100', 'text-blue-800', 'border-blue-200'],
            Cancelled: ['bg-gray-100', 'text-gray-800', 'border-gray-200'],
        };

        const allClasses = Object.values(classes).flat();

        select.addEventListener('change', () => {
            select.classList.remove(...allClasses);
            select.classList.add(...(classes[select.value] ?? classes.Pending));
        });
    });

    // Name search
    const searchInput = document.getElementById('followupSearch');
    const currentStatus = @json($status);

    if (searchInput) {
        const applySearch = () => {
            const value = searchInput.value.trim();
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            if (currentStatus) url.searchParams.set('status', currentStatus);
            else url.searchParams.delete('status');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') applySearch();
        });
    }
</script>
@endpush

