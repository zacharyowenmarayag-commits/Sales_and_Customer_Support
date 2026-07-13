@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Customers')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Customers</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your customer records and contact details.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Customer
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Phone</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-900">CUST-0001</td>
                    <td class="px-6 py-4 text-gray-600">Juan Dela Cruz</td>
                    <td class="px-6 py-4 text-gray-600">juan@email.com</td>
                    <td class="px-6 py-4 text-gray-600">0912-345-6789</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Active</span></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 font-medium text-gray-900">CUST-0002</td>
                    <td class="px-6 py-4 text-gray-600">Sara Santos</td>
                    <td class="px-6 py-4 text-gray-600">sara@email.com</td>
                    <td class="px-6 py-4 text-gray-600">0913-555-1234</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">Prospect</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
