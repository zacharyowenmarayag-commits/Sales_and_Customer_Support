@extends('layouts.app')

@section('title', 'AmbatuGrow - Sales Order Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center mb-1">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Sales Order Management</h1>
            <p class="text-sm text-gray-500 mt-1">Handles the entire sales order lifecycle–from quotation to order fulfillment–ensuring smooth and accurate transaction processing.</p>
        </div>
        <div class="flex space-x-3 items-center">
            <div class="relative w-64">
                <input type="text" id="somSearch" name="q" value="{{ request('q') }}" placeholder="Search" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-green-600 pl-8">
                <i class="fas fa-search absolute left-2.5 top-2.5 text-gray-400 text-xs"></i>
            </div>
            <a href="{{ route('som.new-order') }}" class="bg-green-700 hover:bg-green-800 text-white px-4 py-1.5 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
                <i class="fas fa-plus mr-2 text-xs"></i> New order
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border-l-4 border-amber-400 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Pending</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Pending'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-blue-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Processed</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Processed'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-purple-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Shipped</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Shipped'] }}</span>
        </div>
        <div class="bg-white border-l-4 border-emerald-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Delivered</span>
            <span class="text-2xl font-bold text-gray-800">{{ $counts['Delivered'] }}</span>
        </div>
    </div>

    @php
        $activeStatus = request('status');
        $statusFilters = [
            ['label' => 'All',       'value' => '',          'color' => 'gray'],
            ['label' => 'Pending',   'value' => 'Pending',   'color' => 'amber'],
            ['label' => 'Processed', 'value' => 'Processed', 'color' => 'blue'],
            ['label' => 'Shipped',   'value' => 'Shipped',   'color' => 'purple'],
            ['label' => 'Delivered', 'value' => 'Delivered', 'color' => 'emerald'],
        ];
    @endphp
    <div class="flex flex-wrap gap-2">
        @foreach ($statusFilters as $sf)
            @php
                $isActive = $activeStatus === $sf['value'];
                $baseUrl  = url()->current();
                $params   = array_filter(['q' => request('q'), 'status' => $sf['value']]);
                $href     = $baseUrl . ($params ? '?' . http_build_query($params) : '');
                $c        = $sf['color'];
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
                {{ $sf['label'] }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <th class="py-4 px-6">Order ID</th>
                    <th class="py-4 px-6">Customer</th>
                    <th class="py-4 px-6">Date</th>
                    <th class="py-4 px-6 text-center">Items</th>
                    <th class="py-4 px-6 text-right">Total</th>
                    <th class="py-4 px-6 text-center">Status</th>
                    <th class="py-4 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach ($orders as $order)
                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="py-4 px-6 font-medium text-emerald-600">{{ $order->order_id }}</td>
                        <td class="py-4 px-6 font-semibold text-gray-700">
                            {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Unknown Customer' }}
                        </td>
                        <td class="py-4 px-6 text-gray-500">
                            {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M j, Y') : '—' }}
                        </td>
                        <td class="py-4 px-6 text-center text-gray-500">{{ $order->items->count() }}</td>
                        <td class="py-4 px-6 text-right font-bold text-gray-900">₱{{ number_format($order->total_amount, 2) }}</td>
                        <td class="py-4 px-6 text-center font-bold text-{{ strtolower($order->status) }}-600">
                            {{ $order->status }}
                        </td>
                        <td class="py-4 px-6 text-center text-gray-400">
                            <button class="hover:text-gray-600 transition"><i class="fas fa-chevron-right"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // SOM search — fires as you type (300 ms debounce), preserves status filter
    const somSearch = document.getElementById('somSearch');
    if (somSearch) {
        const applySearch = () => {
            const value = somSearch.value.trim();
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('q', value);
            else url.searchParams.delete('q');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        };

        let debounceTimer;
        somSearch.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applySearch, 800);
        });

        somSearch.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') { clearTimeout(debounceTimer); applySearch(); }
        });
    }
</script>
@endpush