@extends('layouts.app')

@section('title', 'AmbatuGrow - Database Schema')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Database Schema (ERD)</h1>
        <p class="text-sm text-gray-500 mt-1">Entity-Relationship Diagram representing the application database tables and their linkages.</p>
    </div>

    <!-- ERD Visualization Container using a premium glassmorphic style -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6 overflow-x-auto">
        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-project-diagram text-green-700"></i> Entity Relationship Diagram
        </h2>

        <!-- Interactive Diagram Layout -->
        <div class="min-w-[1000px] p-6 bg-gray-50 rounded-lg border border-gray-100 space-y-8">
            
            <!-- Module 1: core CRM & Regions -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 border-b pb-1">1. CRM Core & Segmentation</h3>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Regions Table -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>regions</span>
                            <span class="text-gray-400">Region</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 region_id <span class="text-gray-400">(PK, string)</span></li>
                            <li>region_name <span class="text-gray-400">(string)</span></li>
                        </ul>
                    </div>

                    <!-- Customer Segments -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>customer_segments</span>
                            <span class="text-gray-400">CustomerSegment</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 segment_id <span class="text-gray-400">(PK, string)</span></li>
                            <li>segment_name <span class="text-gray-400">(string)</span></li>
                            <li>description <span class="text-gray-400">(text, nullable)</span></li>
                        </ul>
                    </div>

                    <!-- Customers -->
                    <div class="bg-white rounded-lg border-2 border-emerald-500 shadow-md p-4 text-xs relative">
                        <div class="absolute -top-2 right-2 bg-emerald-500 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">CORE</div>
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>customers</span>
                            <span class="text-gray-400">Customer</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 customer_id <span class="text-gray-400">(PK, string)</span></li>
                            <li>first_name <span class="text-gray-400">(string)</span></li>
                            <li>last_name <span class="text-gray-400">(string)</span></li>
                            <li class="text-blue-600">✉ email <span class="text-gray-400">(unique, string)</span></li>
                            <li>phone <span class="text-gray-400">(string, nullable)</span></li>
                            <li>address <span class="text-gray-400">(text, nullable)</span></li>
                            <li class="text-amber-600 font-medium">🔗 region_id <span class="text-gray-400">(FK → regions, nullable)</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Connective lines / indicators -->
            <div class="flex justify-center text-gray-400 text-xs font-bold select-none">
                <span>🔻 RELATIONSHIPS & TRANSACTIONS 🔻</span>
            </div>

            <!-- Module 2: SOM (Sales Order Management) -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 border-b pb-1">2. Sales Order Management (SOM)</h3>
                <div class="grid grid-cols-4 gap-6">
                    <!-- Sales Representatives -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>sales_representatives</span>
                            <span class="text-gray-400">SalesRepresentative</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 rep_id <span class="text-gray-400">(PK, string)</span></li>
                            <li>first_name <span class="text-gray-400">(string)</span></li>
                            <li>last_name <span class="text-gray-400">(string)</span></li>
                            <li class="text-blue-600">✉ email <span class="text-gray-400">(unique, string)</span></li>
                            <li>phone <span class="text-gray-400">(string, nullable)</span></li>
                            <li>region <span class="text-gray-400">(string, nullable)</span></li>
                            <li>sales_target <span class="text-gray-400">(decimal)</span></li>
                            <li>status <span class="text-gray-400">(string: Active)</span></li>
                        </ul>
                    </div>

                    <!-- Sales Orders -->
                    <div class="bg-white rounded-lg border-2 border-emerald-500 shadow-md p-4 text-xs relative">
                        <div class="absolute -top-2 right-2 bg-emerald-500 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">SOM CORE</div>
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>sales_orders</span>
                            <span class="text-gray-400">SalesOrder</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 order_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 customer_id <span class="text-gray-400">(FK → customers)</span></li>
                            <li class="text-amber-600 font-medium">🔗 rep_id <span class="text-gray-400">(FK → sales_reps, nullable)</span></li>
                            <li>order_date <span class="text-gray-400">(date)</span></li>
                            <li class="font-bold text-blue-600">status <span class="text-gray-400">(Pending/Processed/Shipped/Delivered)</span></li>
                            <li>subtotal <span class="text-gray-400">(decimal)</span></li>
                            <li>tax_amount <span class="text-gray-400">(decimal)</span></li>
                            <li>discount_amount <span class="text-gray-400">(decimal)</span></li>
                            <li class="font-semibold text-gray-900">total_amount <span class="text-gray-400">(decimal)</span></li>
                            <li>branch <span class="text-gray-400">(string, nullable)</span></li>
                            <li>payment_terms <span class="text-gray-400">(string, nullable)</span></li>
                        </ul>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>order_items</span>
                            <span class="text-gray-400">OrderItem</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 order_item_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 order_id <span class="text-gray-400">(FK → sales_orders)</span></li>
                            <li>product_id <span class="text-gray-400">(string)</span></li>
                            <li>quantity <span class="text-gray-400">(integer)</span></li>
                            <li>unit_price <span class="text-gray-400">(decimal)</span></li>
                            <li class="font-semibold text-gray-900">line_total <span class="text-gray-400">(decimal)</span></li>
                        </ul>
                    </div>

                    <!-- Payments -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>payments</span>
                            <span class="text-gray-400">Payment</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 payment_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 order_id <span class="text-gray-400">(FK → sales_orders)</span></li>
                            <li>payment_gateway <span class="text-gray-400">(string, nullable)</span></li>
                            <li>status <span class="text-gray-400">(string: Pending)</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Connective lines / indicators -->
            <div class="flex justify-center text-gray-400 text-xs font-bold select-none">
                <span>🔻 SUPPORT, CASES, & ENGAGEMENT 🔻</span>
            </div>

            <!-- Module 3: ASSCM & Customer Service -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3 border-b pb-1">3. After-Sales Support & Cases (ASSCM)</h3>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Cases (ASSCM Case Management) -->
                    <div class="bg-white rounded-lg border-2 border-green-600 shadow-md p-4 text-xs relative">
                        <div class="absolute -top-2 right-2 bg-green-600 text-white text-[9px] px-1.5 py-0.5 rounded font-bold">ASSCM CORE</div>
                        <div class="font-bold text-green-800 border-b pb-2 mb-2 flex justify-between">
                            <span>cases</span>
                            <span class="text-gray-400">SupportCase</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 case_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 customer_id <span class="text-gray-400">(FK → customers)</span></li>
                            <li>issue <span class="text-gray-400">(text)</span></li>
                            <li class="font-semibold text-amber-600">priority <span class="text-gray-400">(Low/Medium/High)</span></li>
                            <li class="font-semibold text-blue-600">status <span class="text-gray-400">(Open/Pending/Escalated/Resolved)</span></li>
                        </ul>
                    </div>

                    <!-- Tickets -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>tickets</span>
                            <span class="text-gray-400">Ticket</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 ticket_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 customer_id <span class="text-gray-400">(FK → customers)</span></li>
                            <li class="text-amber-600 font-medium">🔗 order_id <span class="text-gray-400">(FK → sales_orders, nullable)</span></li>
                            <li class="text-amber-600 font-medium">🔗 rep_id <span class="text-gray-400">(FK → sales_reps, nullable)</span></li>
                            <li>subject <span class="text-gray-400">(string)</span></li>
                            <li>description <span class="text-gray-400">(text, nullable)</span></li>
                            <li>priority <span class="text-gray-400">(string: Low)</span></li>
                            <li>status <span class="text-gray-400">(string: Open)</span></li>
                        </ul>
                    </div>

                    <!-- Escalations -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 text-xs">
                        <div class="font-bold text-emerald-800 border-b pb-2 mb-2 flex justify-between">
                            <span>escalations</span>
                            <span class="text-gray-400">Escalation</span>
                        </div>
                        <ul class="space-y-1">
                            <li class="font-semibold text-gray-800">🔑 escalation_id <span class="text-gray-400">(PK, string)</span></li>
                            <li class="text-amber-600 font-medium">🔗 ticket_id <span class="text-gray-400">(FK → tickets)</span></li>
                            <li>reason <span class="text-gray-400">(text, nullable)</span></li>
                            <li>priority <span class="text-gray-400">(string: High)</span></li>
                            <li>assigned_manager <span class="text-gray-400">(string, nullable)</span></li>
                            <li>status <span class="text-gray-400">(string: Active)</span></li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
