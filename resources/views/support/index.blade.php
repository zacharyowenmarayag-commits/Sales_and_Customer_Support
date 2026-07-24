@extends('layouts.app')

@section('title', 'Help & Technical Support - AmbatuGrow')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-gray-200 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight flex items-center gap-2.5">
                <i class="fas fa-circle-question text-green-700 text-lg"></i>
                Help & Technical Support
            </h1>
            <p class="text-xs text-gray-500 mt-1">Access system documentation, submit support tickets, or view frequently asked questions.</p>
        </div>
        <div>
            <a href="#submit-ticket" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-green-700 hover:bg-green-800 text-white transition">
                <i class="fas fa-plus"></i>
                Submit Ticket
            </a>
        </div>
    </div>

    <!-- Quick Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-lg bg-green-50 text-green-700 flex items-center justify-center text-base flex-shrink-0">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Knowledge Base</p>
                <p class="text-sm font-bold text-gray-900">24 System Guides</p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-base flex-shrink-0">
                <i class="fas fa-server"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">System Status</p>
                <p class="text-sm font-bold text-emerald-700 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Operational
                </p>
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-base flex-shrink-0">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Support Desk</p>
                <p class="text-sm font-bold text-gray-900">Avg. Response: 15 Min</p>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Columns: Ticket Form & FAQs -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Submit Ticket Form -->
            <div id="submit-ticket" class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-green-700"></i>
                        Submit a Support Request
                    </h2>
                    <span class="text-[11px] text-gray-400 font-normal">Technical Helpdesk</span>
                </div>
                <form action="{{ route('asscm.store') }}" method="POST" class="p-5 space-y-4 text-xs">
                    @csrf
                    <input type="hidden" name="customer_id" value="1">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Issue Category</label>
                            <select name="category" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none" required>
                                <option value="ASSCM">ASSCM Support & Case Tracking</option>
                                <option value="SPRF">SPRF Matching & Deals</option>
                                <option value="CRM">CRM & Customer Accounts</option>
                                <option value="SOM">SOM & Purchase Orders</option>
                                <option value="System">System & Access Control</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700 mb-1">Urgency Level</label>
                            <select name="urgency" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-green-600 focus:outline-none">
                                <option value="Low">Low - Normal Inquiry</option>
                                <option value="Medium" selected>Medium - Functional Issue</option>
                                <option value="High">High - Critical System Issue</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Subject / Short Description</label>
                        <input type="text" name="issue" placeholder="Brief summary of the issue..." required class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold text-gray-700 mb-1">Detailed Explanation</label>
                        <textarea name="description" rows="4" placeholder="Provide full details, steps to reproduce, or error messages..." class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-green-600 focus:outline-none"></textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-green-700 hover:bg-green-800 text-white font-semibold text-xs rounded-lg transition duration-200 flex items-center gap-2">
                            <i class="fas fa-paper-plane"></i>
                            Submit Ticket to Support Desk
                        </button>
                    </div>
                </form>
            </div>

            <!-- FAQs Accordion Section -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-circle-info text-green-700"></i>
                        Frequently Asked Questions
                    </h2>
                </div>
                <div class="p-5 space-y-3.5 text-xs">
                    <div class="p-3.5 bg-gray-50 rounded-lg border border-gray-200 space-y-1">
                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-question-circle text-green-600"></i>
                            How do I mark follow-ups as completed in CRM?
                        </h4>
                        <p class="text-gray-600 leading-relaxed pl-6">Navigate to <strong>CRM &gt; CRM Dashboard</strong>, expand any task row, and click the green <strong>✓ Mark as Done</strong> button to mark the follow-up complete.</p>
                    </div>

                    <div class="p-3.5 bg-gray-50 rounded-lg border border-gray-200 space-y-1">
                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-question-circle text-green-600"></i>
                            How does the SPRF matching table filter work?
                        </h4>
                        <p class="text-gray-600 leading-relaxed pl-6">You can filter SPRF matching records by supplier or status tabs. The table layout dynamically anchors to the top to ensure clean alignment.</p>
                    </div>

                    <div class="p-3.5 bg-gray-50 rounded-lg border border-gray-200 space-y-1">
                        <h4 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-question-circle text-green-600"></i>
                            Where are database schema relationships defined?
                        </h4>
                        <p class="text-gray-600 leading-relaxed pl-6">Click on <strong>Database Schema</strong> in the sidebar to view the interactive ERD diagram covering ASSCM, SPRF, SOM, and CRM tables.</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Support Contacts & Helpful Links -->
        <div class="space-y-6">

            <!-- Support Channels Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3.5 bg-gray-50/80 border-b border-gray-200">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center gap-2">
                        <i class="fas fa-address-book text-green-700"></i>
                        Support Contacts
                    </h2>
                </div>
                <div class="p-5 space-y-4 text-xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Email Support</p>
                            <p class="text-[11px] text-gray-500">support@ambatugrow.com</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Internal Helpline</p>
                            <p class="text-[11px] text-gray-500">Ext. 4022 / +63 (02) 888-GROW</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-700 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-building text-xs"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">IT Administrator</p>
                            <p class="text-[11px] text-gray-500">Agricultural Procurement Desk</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentation Links -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 space-y-3 text-xs">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-file-pdf text-green-700"></i>
                    System Manuals
                </h3>
                <div class="space-y-2 pt-1">
                    <a href="{{ route('asscm') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium transition">
                        <span>ASSCM User Guide</span>
                        <i class="fas fa-arrow-right text-[10px] text-gray-400"></i>
                    </a>
                    <a href="{{ route('sprf') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium transition">
                        <span>SPRF Matching Guide</span>
                        <i class="fas fa-arrow-right text-[10px] text-gray-400"></i>
                    </a>
                    <a href="{{ route('crm.dashboard') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium transition">
                        <span>CRM & Follow-Up Manual</span>
                        <i class="fas fa-arrow-right text-[10px] text-gray-400"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
