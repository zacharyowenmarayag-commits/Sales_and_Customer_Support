@extends('layouts.app')

@section('title', 'AmbatuGrow - ASSCM')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">After-Sales Support and Case Management</h1>
            <p class="text-sm text-gray-500 mt-1">Supports issue resolution, service requests, and warranty claims after the sale is completed.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New case
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white border-l-4 border-amber-400 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Open</span>
            <span class="text-2xl font-bold text-gray-800">24</span>
        </div>
        <div class="bg-white border-l-4 border-blue-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Pending</span>
            <span class="text-2xl font-bold text-gray-800">9</span>
        </div>
        <div class="bg-white border-l-4 border-purple-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Escalated</span>
            <span class="text-2xl font-bold text-gray-800">4</span>
        </div>
        <div class="bg-white border-l-4 border-emerald-500 rounded-xl p-4 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 block mb-1">Resolved</span>
            <span class="text-2xl font-bold text-gray-800">18</span>
        </div>
    </div>

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
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="py-4 px-6 font-medium text-emerald-600">CAS-1001</td>
                    <td class="py-4 px-6 font-semibold text-gray-700">Jollipop Foods Corp.</td>
                    <td class="py-4 px-6 text-gray-500">Replacement request for damaged shipment</td>
                    <td class="py-4 px-6 text-center text-amber-600 font-semibold">High</td>
                    <td class="py-4 px-6 text-center text-blue-600 font-semibold">Pending</td>
                </tr>
                <tr class="hover:bg-gray-50/70 transition">
                    <td class="py-4 px-6 font-medium text-emerald-600">CAS-1002</td>
                    <td class="py-4 px-6 font-semibold text-gray-700">SM Retail Inc.</td>
                    <td class="py-4 px-6 text-gray-500">Product quality follow-up</td>
                    <td class="py-4 px-6 text-center text-emerald-600 font-semibold">Medium</td>
                    <td class="py-4 px-6 text-center text-emerald-600 font-semibold">Resolved</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

