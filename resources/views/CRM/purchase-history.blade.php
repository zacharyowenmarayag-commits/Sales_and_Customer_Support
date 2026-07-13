@extends('layouts.app')

@section('title', 'AmbatuGrow - Purchase History')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Purchase History</h1>
            <p class="text-sm text-gray-500 mt-1">View customer purchase records and order history.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-download mr-2"></i> Export History
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Order ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Amount</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 text-gray-600">Jun 25, 2026</td>
                    <td class="px-6 py-4 text-gray-900">Juan Dela Cruz</td>
                    <td class="px-6 py-4 text-gray-600">ORD-1001</td>
                    <td class="px-6 py-4 text-gray-600">₱12,450</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Completed</span></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-gray-600">Jun 22, 2026</td>
                    <td class="px-6 py-4 text-gray-900">Sara Santos</td>
                    <td class="px-6 py-4 text-gray-600">ORD-1002</td>
                    <td class="px-6 py-4 text-gray-600">₱8,790</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">Pending</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
