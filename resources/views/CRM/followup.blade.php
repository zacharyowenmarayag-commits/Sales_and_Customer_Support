@extends('layouts.app')

@section('title', 'AmbatuGrow - Follow-Ups')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Follow-Ups</h1>
            <p class="text-sm text-gray-500 mt-1">Organize customer follow-ups and next-step reminders.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> New Follow-Up
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Task</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Due Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 text-gray-900">Check payment schedule</td>
                    <td class="px-6 py-4 text-gray-600">SM Retail Inc.</td>
                    <td class="px-6 py-4 text-gray-600">Jul 1, 2026</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">Pending</span></td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-gray-900">Confirm follow-up meeting</td>
                    <td class="px-6 py-4 text-gray-600">Robinson's Supermarket</td>
                    <td class="px-6 py-4 text-gray-600">Jul 3, 2026</td>
                    <td class="px-6 py-4"><span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Scheduled</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
