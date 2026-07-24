@extends('layouts.app')

@section('title', 'AmbatuGrow - Purchase History')

@push('styles')
    @vite(['resources/css/pages/crm-pages.css'])
@endpush

@section('content')
<div class="crm-page space-y-6">
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

    <x-crm.search-field
        id="purchaseSearch"
        label="Search"
        name="q"
        :value="$q"
        placeholder="Search customer name..."
    />

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
<script src="{{ asset('js/crm-pages.js') }}?v={{ filemtime(public_path('js/crm-pages.js')) }}"></script>
<script>
    window.applyQuerySearch('purchaseSearch', window.location.href);
</script>
@endpush

