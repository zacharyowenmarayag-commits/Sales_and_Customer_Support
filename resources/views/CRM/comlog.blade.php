@extends('layouts.app')

@section('title', 'AmbatuGrow - Communication Logs')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Communication Logs</h1>
            <p class="text-sm text-gray-500 mt-1">Track your recent customer conversations and notes.</p>
        </div>
        <a href="#" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center shadow-sm transition">
            <i class="fas fa-plus mr-2"></i> Add Log
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Customer</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Channel</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Notes</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Owner</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4 text-gray-600">Jun 26, 2026</td>
                    <td class="px-6 py-4 text-gray-900">ABC Corp.</td>
                    <td class="px-6 py-4 text-gray-600">Email</td>
                    <td class="px-6 py-4 text-gray-600">Followed up on invoice status.</td>
                    <td class="px-6 py-4 text-gray-600">Misty Reyes</td>
                </tr>
                <tr>
                    <td class="px-6 py-4 text-gray-600">Jun 25, 2026</td>
                    <td class="px-6 py-4 text-gray-900">Puregold Price Club</td>
                    <td class="px-6 py-4 text-gray-600">Phone</td>
                    <td class="px-6 py-4 text-gray-600">Confirmed delivery schedule.</td>
                    <td class="px-6 py-4 text-gray-600">Jarius Mendoza</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
