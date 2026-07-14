@extends('layouts.app')

@section('title', 'AmbatuGrow - Deals')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">All Deals</h1>
            <p class="text-sm text-gray-500 mt-1">View all ongoing and past deals across your CRM pipeline.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New Deal
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">On-Going Deals</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="rounded-2xl bg-yellow-50 p-4 border border-yellow-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Site Renovation</h3>
                                <p class="text-sm text-gray-500">ABC Corp. · Negotiation</p>
                            </div>
                            <span class="text-sm font-semibold text-yellow-800 bg-yellow-100 px-2 py-1 rounded-full">₱714,000</span>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-blue-50 p-4 border border-blue-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">IT Equipments</h3>
                                <p class="text-sm text-gray-500">Tech Solutions · Proposal</p>
                            </div>
                            <span class="text-sm font-semibold text-blue-800 bg-blue-100 px-2 py-1 rounded-full">₱532,000</span>
                        </div>
                    </div>
                    <div class="rounded-2xl bg-pink-50 p-4 border border-pink-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Confidential F.</h3>
                                <p class="text-sm text-gray-500">Bambi S First · Negotiation</p>
                            </div>
                            <span class="text-sm font-semibold text-pink-800 bg-pink-100 px-2 py-1 rounded-full">₱1,500,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Past Deals</h2>
            </div>
            <table class="w-full text-sm divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Deal Name</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Stage</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">Bulk Supplies</td>
                        <td class="px-4 py-3 text-gray-600">XYZ Comp.</td>
                        <td class="px-4 py-3 text-gray-600">Closed Won</td>
                        <td class="px-4 py-3 text-gray-900">₱293,000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">Equipment Order</td>
                        <td class="px-4 py-3 text-gray-600">Agri Farm</td>
                        <td class="px-4 py-3 text-gray-600">Closed Won</td>
                        <td class="px-4 py-3 text-gray-900">₱412,000</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">Supply Renewal</td>
                        <td class="px-4 py-3 text-gray-600">Harvest Co.</td>
                        <td class="px-4 py-3 text-gray-600">Closed Lost</td>
                        <td class="px-4 py-3 text-gray-900">₱185,000</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
