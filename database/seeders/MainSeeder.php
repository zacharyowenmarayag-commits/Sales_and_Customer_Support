<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Region;
use App\Models\CustomerSegment;
use App\Models\Customer;
use App\Models\SalesRepresentative;
use App\Models\SalesOrder;
use App\Models\OrderItem;
use App\Models\Opportunity;
use App\Models\Payment;
use App\Models\SalesTarget;
use App\Models\SalesForecast;
use App\Models\Ticket;
use App\Models\TicketConversation;
use App\Models\SlaRule;
use App\Models\SlaMonitoring;
use App\Models\Escalation;
use App\Models\Notification;
use App\Models\ReportLog;
use App\Models\CustomerFeedback;
use App\Models\SupportCase;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // Disable FK checks so TRUNCATE works on MySQL despite foreign key constraints
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate in reverse FK dependency order
        CustomerFeedback::truncate();
        ReportLog::truncate();
        Notification::truncate();
        Escalation::truncate();
        SlaMonitoring::truncate();
        SlaRule::truncate();
        TicketConversation::truncate();
        Ticket::truncate();
        SalesForecast::truncate();
        SalesTarget::truncate();
        Payment::truncate();
        Opportunity::truncate();
        OrderItem::truncate();
        SalesOrder::truncate();
        SalesRepresentative::truncate();
        Customer::truncate();
        CustomerSegment::truncate();
        Region::truncate();

        if (\Illuminate\Support\Facades\Schema::hasTable('cases')) {
            \Illuminate\Support\Facades\DB::table('cases')->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ──────────────────────────────────────────
        // 1. REGIONS
        // ──────────────────────────────────────────
        Region::create(['region_id' => 'REG-001', 'region_name' => 'CALABARZON']);
        Region::create(['region_id' => 'REG-002', 'region_name' => 'NCR']);
        Region::create(['region_id' => 'REG-003', 'region_name' => 'Visayas']);
        Region::create(['region_id' => 'REG-004', 'region_name' => 'Mindanao']);

        // ──────────────────────────────────────────
        // 2. CUSTOMER SEGMENTS
        // ──────────────────────────────────────────
        CustomerSegment::create([
            'segment_id'   => 'SEG-001',
            'segment_name' => 'VIP Customer',
            'description'  => 'High value customers with frequent purchases.',
        ]);
        CustomerSegment::create([
            'segment_id'   => 'SEG-002',
            'segment_name' => 'Active Customer',
            'description'  => 'Customers with recent follow-ups or purchases.',
        ]);
        CustomerSegment::create([
            'segment_id'   => 'SEG-003',
            'segment_name' => 'Prospect',
            'description'  => 'Potential customers without purchases yet.',
        ]);

        // ──────────────────────────────────────────
        // 3. CUSTOMERS
        // ──────────────────────────────────────────
        Customer::create([
            'customer_id' => 'CUST-124',
            'first_name'  => 'Emmanuel',
            'last_name'   => 'Creo',
            'email'       => 'emanpogi@hotmail.com',
            'phone'       => '+639876543210',
            'address'     => 'Blk 12, Brgy. San Jose, Indang, Cavite',
            'region_id'   => 'REG-001',
        ]);
        Customer::create([
            'customer_id' => 'CUST-125',
            'first_name'  => 'Maria',
            'last_name'   => 'Santos',
            'email'       => 'maria.santos@gmail.com',
            'phone'       => '+639181234567',
            'address'     => 'Unit 3A, Taguig City, Metro Manila',
            'region_id'   => 'REG-002',
        ]);
        Customer::create([
            'customer_id' => 'CUST-126',
            'first_name'  => 'Jose',
            'last_name'   => 'Reyes',
            'email'       => 'jose.reyes@yahoo.com',
            'phone'       => '+639221234567',
            'address'     => 'Brgy. Lahug, Cebu City',
            'region_id'   => 'REG-003',
        ]);
        Customer::create([
            'customer_id' => 'CUST-127',
            'first_name'  => 'Elsa',
            'last_name'   => 'Lgh',
            'email'       => 'elsa.lgh@gmail.com',
            'phone'       => '+639171112222',
            'address'     => 'Calamba, Laguna',
            'region_id'   => 'REG-001',
            'created_at'  => now(),
        ]);
        Customer::create([
            'customer_id' => 'CUST-128',
            'first_name'  => 'Dee',
            'last_name'   => 'Nuts',
            'email'       => 'deenuts@gmail.com',
            'phone'       => '+639172223333',
            'address'     => 'Quezon City',
            'region_id'   => 'REG-002',
            'created_at'  => now()->subMonths(7),
        ]);
        Customer::create([
            'customer_id' => 'CUST-129',
            'first_name'  => 'Lee',
            'last_name'   => 'Kah',
            'email'       => 'leekah@gmail.com',
            'phone'       => '+639173334444',
            'address'     => 'Cebu City',
            'region_id'   => 'REG-003',
            'created_at'  => now()->subDays(2),
        ]);

        // ──────────────────────────────────────────
        // 4. SALES REPRESENTATIVES
        // ──────────────────────────────────────────
        SalesRepresentative::create([
            'rep_id'        => 'REP-101',
            'first_name'    => 'Ash',
            'last_name'     => 'Ketchum',
            'email'         => 'ashley.ketchum@ambatugrow.com',
            'phone'         => '+639171234567',
            'region'        => 'CALABARZON',
            'sales_target'  => 250000.00,
            'status'        => 'Active',
        ]);
        SalesRepresentative::create([
            'rep_id'        => 'REP-102',
            'first_name'    => 'Misty',
            'last_name'     => 'Reyes',
            'email'         => 'misty.reyes@ambatugrow.com',
            'phone'         => '+639182345678',
            'region'        => 'NCR',
            'sales_target'  => 200000.00,
            'status'        => 'Active',
        ]);
        SalesRepresentative::create([
            'rep_id'        => 'REP-103',
            'first_name'    => 'Brock',
            'last_name'     => 'Dela Cruz',
            'email'         => 'brock.delacruz@ambatugrow.com',
            'phone'         => '+639193456789',
            'region'        => 'Visayas',
            'sales_target'  => 180000.00,
            'status'        => 'Active',
        ]);

        // ──────────────────────────────────────────
        // 5. SALES ORDERS
        // ──────────────────────────────────────────
        SalesOrder::create([
            'order_id'        => 'ORD-001',
            'customer_id'     => 'CUST-124',
            'rep_id'          => 'REP-101',
            'order_date'      => '2026-06-25',
            'status'          => 'Delivered',
            'subtotal'        => 13450.00,
            'tax_amount'      => 1333.93,
            'discount_amount' => 0.00,
            'total_amount'    => 12450.00,
            'branch'          => 'Cavite',
            'payment_terms'   => 'Net 30 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-002',
            'customer_id'     => 'CUST-125',
            'rep_id'          => 'REP-102',
            'order_date'      => '2026-06-20',
            'status'          => 'Processing',
            'subtotal'        => 8500.00,
            'tax_amount'      => 850.00,
            'discount_amount' => 200.00,
            'total_amount'    => 9150.00,
            'branch'          => 'Makati',
            'payment_terms'   => 'Net 15 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-003',
            'customer_id'     => 'CUST-126',
            'rep_id'          => 'REP-103',
            'order_date'      => '2026-06-18',
            'status'          => 'Shipped',
            'subtotal'        => 22000.00,
            'tax_amount'      => 2640.00,
            'discount_amount' => 500.00,
            'total_amount'    => 24140.00,
            'branch'          => 'Cebu',
            'payment_terms'   => 'Net 30 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-004',
            'customer_id'     => 'CUST-124',
            'rep_id'          => 'REP-101',
            'order_date'      => '2026-06-18',
            'status'          => 'Pending',
            'subtotal'        => 56780.00,
            'tax_amount'      => 6813.60,
            'discount_amount' => 0.00,
            'total_amount'    => 63593.60,
            'branch'          => 'Cavite',
            'payment_terms'   => 'Net 30 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-005',
            'customer_id'     => 'CUST-125',
            'rep_id'          => 'REP-102',
            'order_date'      => '2026-06-16',
            'status'          => 'Pending',
            'subtotal'        => 2150.00,
            'tax_amount'      => 258.00,
            'discount_amount' => 0.00,
            'total_amount'    => 2408.00,
            'branch'          => 'Makati',
            'payment_terms'   => 'COD',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-006',
            'customer_id'     => 'CUST-126',
            'rep_id'          => 'REP-103',
            'order_date'      => '2026-06-14',
            'status'          => 'Processed',
            'subtotal'        => 18300.00,
            'tax_amount'      => 2196.00,
            'discount_amount' => 300.00,
            'total_amount'    => 20196.00,
            'branch'          => 'Cebu',
            'payment_terms'   => 'Net 15 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-007',
            'customer_id'     => 'CUST-124',
            'rep_id'          => 'REP-101',
            'order_date'      => '2026-06-12',
            'status'          => 'Delivered',
            'subtotal'        => 14500.00,
            'tax_amount'      => 1740.00,
            'discount_amount' => 500.00,
            'total_amount'    => 15740.00,
            'branch'          => 'Cavite',
            'payment_terms'   => 'Net 30 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-008',
            'customer_id'     => 'CUST-125',
            'rep_id'          => 'REP-102',
            'order_date'      => '2026-06-10',
            'status'          => 'Shipped',
            'subtotal'        => 35000.00,
            'tax_amount'      => 4200.00,
            'discount_amount' => 1000.00,
            'total_amount'    => 38200.00,
            'branch'          => 'Makati',
            'payment_terms'   => 'Net 45 days',
        ]);
        SalesOrder::create([
            'order_id'        => 'ORD-009',
            'customer_id'     => 'CUST-127',
            'rep_id'          => 'REP-101',
            'order_date'      => '2026-06-08',
            'status'          => 'Delivered',
            'subtotal'        => 12450.00,
            'tax_amount'      => 1494.00,
            'discount_amount' => 0.00,
            'total_amount'    => 13944.00,
            'branch'          => 'Cavite',
            'payment_terms'   => 'COD',
        ]);

        // ──────────────────────────────────────────
        // 6. ORDER ITEMS
        // ──────────────────────────────────────────
        OrderItem::create([
            'order_item_id' => 'ITEM-701',
            'order_id'      => 'ORD-001',
            'product_id'    => 'PROD-LM-PC-001',
            'quantity'      => 120,
            'unit_price'    => 15.00,
            'line_total'    => 1800.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-702',
            'order_id'      => 'ORD-001',
            'product_id'    => 'PROD-HB-SD-002',
            'quantity'      => 50,
            'unit_price'    => 210.00,
            'line_total'    => 10500.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-703',
            'order_id'      => 'ORD-002',
            'product_id'    => 'PROD-DR-IR-003',
            'quantity'      => 5,
            'unit_price'    => 1700.00,
            'line_total'    => 8500.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-704',
            'order_id'      => 'ORD-003',
            'product_id'    => 'PROD-OF-GR-004',
            'quantity'      => 200,
            'unit_price'    => 110.00,
            'line_total'    => 22000.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-705',
            'order_id'      => 'ORD-004',
            'product_id'    => 'PROD-SK-01',
            'quantity'      => 1494,
            'unit_price'    => 38.00,
            'line_total'    => 56780.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-706',
            'order_id'      => 'ORD-005',
            'product_id'    => 'PROD-IM-BC-01',
            'quantity'      => 119,
            'unit_price'    => 18.00,
            'line_total'    => 2150.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-707',
            'order_id'      => 'ORD-006',
            'product_id'    => 'PROD-IM-CP-01',
            'quantity'      => 430,
            'unit_price'    => 42.50,
            'line_total'    => 18300.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-708',
            'order_id'      => 'ORD-007',
            'product_id'    => 'PROD-IM-JC-01',
            'quantity'      => 146,
            'unit_price'    => 99.00,
            'line_total'    => 14500.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-709',
            'order_id'      => 'ORD-008',
            'product_id'    => 'PROD-IM-CF-01',
            'quantity'      => 636,
            'unit_price'    => 55.00,
            'line_total'    => 35000.00,
        ]);
        OrderItem::create([
            'order_item_id' => 'ITEM-710',
            'order_id'      => 'ORD-009',
            'product_id'    => 'PROD-LM-PC-001',
            'quantity'      => 830,
            'unit_price'    => 15.00,
            'line_total'    => 12450.00,
        ]);

        // ──────────────────────────────────────────
        // 7. OPPORTUNITIES (Deal/Opportunity)
        // ──────────────────────────────────────────
        Opportunity::create([
            'deal_id'             => 'DEAL-001',
            'customer_id'         => 'CUST-124',
            'rep_id'              => 'REP-101',
            'stage'               => 'Proposal',
            'expected_close_date' => '2026-07-15',
            'deal_value'          => 734000.00,
        ]);
        Opportunity::create([
            'deal_id'             => 'DEAL-002',
            'customer_id'         => 'CUST-125',
            'rep_id'              => 'REP-102',
            'stage'               => 'Negotiation',
            'expected_close_date' => '2026-07-30',
            'deal_value'          => 532000.00,
        ]);
        Opportunity::create([
            'deal_id'             => 'DEAL-003',
            'customer_id'         => 'CUST-126',
            'rep_id'              => 'REP-103',
            'stage'               => 'Won',
            'expected_close_date' => '2026-06-30',
            'deal_value'          => 220000.00,
        ]);

        // ──────────────────────────────────────────
        // 8. PAYMENTS
        // ──────────────────────────────────────────
        Payment::create([
            'payment_id'      => 'PAY-322',
            'order_id'        => 'ORD-001',
            'payment_gateway' => 'GCash',
            'status'          => 'Completed',
        ]);
        Payment::create([
            'payment_id'      => 'PAY-323',
            'order_id'        => 'ORD-002',
            'payment_gateway' => 'Bank Transfer',
            'status'          => 'Pending',
        ]);
        Payment::create([
            'payment_id'      => 'PAY-324',
            'order_id'        => 'ORD-003',
            'payment_gateway' => 'Maya',
            'status'          => 'Completed',
        ]);

        // ──────────────────────────────────────────
        // 9. SALES TARGETS
        // ──────────────────────────────────────────
        SalesTarget::create([
            'target_id'     => 'TGT-001',
            'rep_id'        => 'REP-101',
            'month'         => 'May 2026',
            'target_amount' => 250000.00,
            'actual_amount' => 234567.00,
        ]);
        SalesTarget::create([
            'target_id'     => 'TGT-002',
            'rep_id'        => 'REP-102',
            'month'         => 'May 2026',
            'target_amount' => 200000.00,
            'actual_amount' => 187000.00,
        ]);
        SalesTarget::create([
            'target_id'     => 'TGT-003',
            'rep_id'        => 'REP-103',
            'month'         => 'May 2026',
            'target_amount' => 180000.00,
            'actual_amount' => 195000.00,
        ]);

        // ──────────────────────────────────────────
        // 10. SALES FORECASTS
        // ──────────────────────────────────────────
        SalesForecast::create([
            'forecast_id'     => 'FCST-001',
            'forecast_period' => 'June 2026',
            'forecast_amount' => 1350000.00,
            'generated_date'  => '2026-05-31',
        ]);
        SalesForecast::create([
            'forecast_id'     => 'FCST-002',
            'forecast_period' => 'July 2026',
            'forecast_amount' => 1480000.00,
            'generated_date'  => '2026-06-30',
        ]);

        // ──────────────────────────────────────────
        // 11. TICKETS
        // ──────────────────────────────────────────
        Ticket::create([
            'ticket_id'     => 'TCK-1024',
            'customer_id'   => 'CUST-124',
            'order_id'      => 'ORD-001',
            'rep_id'        => 'REP-101',
            'subject'       => 'Breached Response Time',
            'description'   => 'Customer has not received any response regarding the order.',
            'category'      => 'Technical Support',
            'priority'      => 'High',
            'status'        => 'Open',
            'created_at_ts' => '2026-06-28 09:15:00',
            'updated_at_ts' => '2026-06-28 10:30:00',
            'closed_at'     => null,
        ]);
        Ticket::create([
            'ticket_id'     => 'TCK-1025',
            'customer_id'   => 'CUST-125',
            'order_id'      => 'ORD-002',
            'rep_id'        => 'REP-102',
            'subject'       => 'Incorrect Item Delivered',
            'description'   => 'Customer received wrong product variant.',
            'category'      => 'Billing & Orders',
            'priority'      => 'Medium',
            'status'        => 'In Progress',
            'created_at_ts' => '2026-06-27 14:00:00',
            'updated_at_ts' => '2026-06-27 15:00:00',
            'closed_at'     => null,
        ]);
        Ticket::create([
            'ticket_id'     => 'TCK-1026',
            'customer_id'   => 'CUST-126',
            'order_id'      => 'ORD-003',
            'rep_id'        => 'REP-103',
            'subject'       => 'Payment Not Reflected',
            'description'   => 'Customer payment via Maya not showing in system.',
            'category'      => 'Billing & Orders',
            'priority'      => 'High',
            'status'        => 'Resolved',
            'created_at_ts' => '2026-06-26 10:00:00',
            'updated_at_ts' => '2026-06-26 16:00:00',
            'closed_at'     => '2026-06-26 16:00:00',
        ]);

        // ──────────────────────────────────────────
        // 12. TICKET CONVERSATIONS
        // ──────────────────────────────────────────
        TicketConversation::create([
            'conversation_id' => 'CONV-001',
            'ticket_id'       => 'TCK-1024',
            'sender_type'     => 'Customer',
            'sender_id'       => 'CUST-124',
            'message'         => 'I haven\'t received any update regarding my order.',
            'attachment'      => null,
            'sent_at'         => '2026-06-28 09:20:00',
        ]);
        TicketConversation::create([
            'conversation_id' => 'CONV-002',
            'ticket_id'       => 'TCK-1024',
            'sender_type'     => 'Representative',
            'sender_id'       => 'REP-101',
            'message'         => 'We apologize for the delay. We are currently investigating the issue.',
            'attachment'      => null,
            'sent_at'         => '2026-06-28 10:35:00',
        ]);
        TicketConversation::create([
            'conversation_id' => 'CONV-003',
            'ticket_id'       => 'TCK-1025',
            'sender_type'     => 'Customer',
            'sender_id'       => 'CUST-125',
            'message'         => 'I received a different product than what I ordered.',
            'attachment'      => null,
            'sent_at'         => '2026-06-27 14:05:00',
        ]);

        // ──────────────────────────────────────────
        // 13. SLA RULES
        // ──────────────────────────────────────────
        SlaRule::create([
            'sla_rule_id'          => 'SLA-001',
            'priority'             => 'High',
            'response_time_goal'   => '2 Hours',
            'resolution_time_goal' => '24 Hours',
            'escalation_time'      => '4 Hours',
            'status'               => 'Active',
            'description'          => 'High Priority Customer Support',
        ]);
        SlaRule::create([
            'sla_rule_id'          => 'SLA-002',
            'priority'             => 'Medium',
            'response_time_goal'   => '4 Hours',
            'resolution_time_goal' => '48 Hours',
            'escalation_time'      => '8 Hours',
            'status'               => 'Active',
            'description'          => 'Medium Priority Customer Support',
        ]);
        SlaRule::create([
            'sla_rule_id'          => 'SLA-003',
            'priority'             => 'Low',
            'response_time_goal'   => '8 Hours',
            'resolution_time_goal' => '72 Hours',
            'escalation_time'      => '24 Hours',
            'status'               => 'Active',
            'description'          => 'Low Priority Customer Support',
        ]);

        // ──────────────────────────────────────────
        // 14. SLA MONITORING
        // ──────────────────────────────────────────
        SlaMonitoring::create([
            'monitoring_id'        => 'MON-001',
            'ticket_id'            => 'TCK-1024',
            'sla_rule_id'          => 'SLA-001',
            'response_due'         => '2026-06-28 11:15:00',
            'resolution_due'       => '2026-06-29 09:15:00',
            'first_response_time'  => '1 Hour 25 Minutes',
            'resolution_time'      => '16 Hours 40 Minutes',
            'sla_status'           => 'On Track',
            'compliance_percentage' => 93,
            'last_checked'         => '2026-06-28 10:45:00',
        ]);
        SlaMonitoring::create([
            'monitoring_id'        => 'MON-002',
            'ticket_id'            => 'TCK-1025',
            'sla_rule_id'          => 'SLA-002',
            'response_due'         => '2026-06-27 18:00:00',
            'resolution_due'       => '2026-06-29 14:00:00',
            'first_response_time'  => '3 Hours 10 Minutes',
            'resolution_time'      => null,
            'sla_status'           => 'In Progress',
            'compliance_percentage' => 78,
            'last_checked'         => '2026-06-27 16:00:00',
        ]);

        // ──────────────────────────────────────────
        // 15. ESCALATIONS
        // ──────────────────────────────────────────
        Escalation::create([
            'escalation_id'    => 'ESC-001',
            'ticket_id'        => 'TCK-1024',
            'reason'           => 'Response Time Breached',
            'priority'         => 'High',
            'assigned_manager' => 'MGR-001',
            'status'           => 'Active',
            'overdue_time'     => '1 Hour',
            'escalated_at'     => '2026-06-28 11:20:00',
        ]);
        Escalation::create([
            'escalation_id'    => 'ESC-002',
            'ticket_id'        => 'TCK-1025',
            'reason'           => 'Resolution Time Approaching',
            'priority'         => 'Medium',
            'assigned_manager' => 'MGR-002',
            'status'           => 'Resolved',
            'overdue_time'     => null,
            'escalated_at'     => '2026-06-27 22:00:00',
        ]);

        // ──────────────────────────────────────────
        // 16. NOTIFICATIONS
        // ──────────────────────────────────────────
        Notification::create([
            'notification_id' => 'NOTIF-001',
            'rep_id'          => 'REP-101',
            'ticket_id'       => 'TCK-1024',
            'title'           => 'SLA Warning',
            'message'         => 'Ticket TCK-1024 is approaching the SLA response deadline.',
            'type'            => 'Warning',
            'is_read'         => false,
            'notified_at'     => '2026-06-28 10:45:00',
        ]);
        Notification::create([
            'notification_id' => 'NOTIF-002',
            'rep_id'          => 'REP-102',
            'ticket_id'       => 'TCK-1025',
            'title'           => 'New Ticket Assigned',
            'message'         => 'Ticket TCK-1025 has been assigned to you.',
            'type'            => 'Info',
            'is_read'         => true,
            'notified_at'     => '2026-06-27 14:00:00',
        ]);
        Notification::create([
            'notification_id' => 'NOTIF-003',
            'rep_id'          => 'REP-101',
            'ticket_id'       => 'TCK-1024',
            'title'           => 'Escalation Created',
            'message'         => 'Ticket TCK-1024 has been escalated to management.',
            'type'            => 'Alert',
            'is_read'         => false,
            'notified_at'     => '2026-06-28 11:25:00',
        ]);

        // ──────────────────────────────────────────
        // 17. REPORT LOGS
        // ──────────────────────────────────────────
        ReportLog::create([
            'report_id'    => 'RPT-001',
            'generated_by' => 'REP-101',
            'report_type'  => 'SLA Report',
            'date_from'    => '2026-05-14',
            'date_to'      => '2026-05-20',
            'generated_at' => '2026-05-20 17:30:00',
            'file_format'  => 'PDF',
        ]);
        ReportLog::create([
            'report_id'    => 'RPT-002',
            'generated_by' => 'REP-102',
            'report_type'  => 'Sales Summary',
            'date_from'    => '2026-05-01',
            'date_to'      => '2026-05-31',
            'generated_at' => '2026-06-01 08:00:00',
            'file_format'  => 'Excel',
        ]);

        // ──────────────────────────────────────────
        // 18. CUSTOMER FEEDBACK
        // ──────────────────────────────────────────
        CustomerFeedback::create([
            'feedback_id'  => 'FB-001',
            'ticket_id'    => 'TCK-1024',
            'customer_id'  => 'CUST-124',
            'rating'       => 5,
            'comment'      => 'Excellent customer support.',
            'submitted_at' => '2026-06-30 00:00:00',
        ]);
        CustomerFeedback::create([
            'feedback_id'  => 'FB-002',
            'ticket_id'    => 'TCK-1026',
            'customer_id'  => 'CUST-126',
            'rating'       => 4,
            'comment'      => 'Issue was resolved quickly, thank you.',
            'submitted_at' => '2026-06-27 09:00:00',
        ]);
        // ──────────────────────────────────────────
        // SUPPORT CASES (ASSCM)
        // ──────────────────────────────────────────
        SupportCase::create(['case_id' => 'CAS-1001', 'customer_id' => 'CUST-124', 'issue' => 'Replacement request for damaged shipment',      'priority' => 'High',   'status' => 'Pending']);
        SupportCase::create(['case_id' => 'CAS-1002', 'customer_id' => 'CUST-126', 'issue' => 'Product quality follow-up after delivery',       'priority' => 'Medium', 'status' => 'Resolved']);
        SupportCase::create(['case_id' => 'CAS-1003', 'customer_id' => 'CUST-124', 'issue' => 'Invoice discrepancy — overcharged by ₱500',      'priority' => 'High',   'status' => 'Open']);
        SupportCase::create(['case_id' => 'CAS-1004', 'customer_id' => 'CUST-125', 'issue' => 'Delivery delayed by 3 business days',            'priority' => 'Medium', 'status' => 'Open']);
        SupportCase::create(['case_id' => 'CAS-1005', 'customer_id' => 'CUST-127', 'issue' => 'Wrong items delivered — requested re-shipment',  'priority' => 'High',   'status' => 'Escalated']);
        SupportCase::create(['case_id' => 'CAS-1006', 'customer_id' => 'CUST-128', 'issue' => 'Warranty claim for defective product',           'priority' => 'Low',    'status' => 'Pending']);
    }
}
