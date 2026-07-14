@extends('layouts.app')

@section('title', 'AmbatuGrow - Purchase History')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Purchase History</h1>
            <p class="text-sm text-gray-500 mt-1">View customer purchase records and order history.</p>
        </div>
        <a
            href="{{ route('crm.purchaseHistory.export', array_filter(['q' => $q])) }}"
            class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition"
        >
            <i class="fas fa-download mr-2"></i> Export History
        </a>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-700">Search</label>
        <div class="relative">
            <input
                type="text"
                name="q"
                value="{{ old('q', $q) }}"
                id="purchaseSearch"
                placeholder="Search customer name..."
                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-green-600"
            />
            <i class="fas fa-search absolute right-4 top-3.5 text-gray-400 text-sm"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Order ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Amount</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="purchaseHistoryBody">
                @forelse ($rows as $row)
                    <tr>
                        <td class="px-6 py-4 text-gray-600">{{ $row['date'] }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $row['customer'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['order_id'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['amount'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No purchase history yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $rows])
    </div>
</div>
@endsection

@push('scripts')
<script>
    const searchInput = document.getElementById('purchaseSearch');
    if (searchInput) {
        const applySearch = () => {
            const value = searchInput.value.trim();
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') applySearch();
        });
    }
</script>
@endpush

