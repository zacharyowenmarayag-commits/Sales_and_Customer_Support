@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Customers')

@section('push_styles')
    @vite(['resources/css/pages/crm-pages.css'])
@endpush

@section('content')
<div class="crm-page space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Customer Directory</h1>
            <p class="text-xs text-gray-500 mt-1">Customer profiles are automatically ingested from inter-departmental transactions (ASSCM, SOM, SPRF).</p>
        </div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
            Auto-Sync Ingestion: Active
        </span>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-xs font-semibold text-green-800 flex items-center gap-2">
            <i class="fas fa-circle-check text-green-600 text-sm"></i>
            {{ session('success') }}
        </div>
    @endif

    <x-crm.search-field
        id="customerSearch"
        label="Search"
        name="q"
        :value="$q"
        placeholder="Search customer name, email, or phone..."
    />

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-xs">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-700">Phone</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="customersTableBody">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="px-5 py-3.5 font-semibold text-gray-900">#{{ $customer->customer_id }}</td>
                        <td class="px-5 py-3.5 text-gray-800 font-medium">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $customer->email ?: '—' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $customer->phone ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">No customers found matching your search query.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @include('partials.pagination', ['paginator' => $customers])
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/crm-pages.js') }}?v={{ filemtime(public_path('js/crm-pages.js')) }}"></script>
<script>
    window.applyQuerySearch('customerSearch', window.location.href);
</script>
@endpush
