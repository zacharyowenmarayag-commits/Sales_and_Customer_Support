<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Regions
        Schema::create('regions', function (Blueprint $table) {
            $table->string('region_id')->primary();
            $table->string('region_name');
            $table->timestamps();
        });

        // 2. Customer Segments
        Schema::create('customer_segments', function (Blueprint $table) {
            $table->string('segment_id')->primary();
            $table->string('segment_name');
            $table->text('description')->nullable();
            $table->integer('estimated_count')->default(0);
            $table->decimal('projected_sales', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('region_id')->nullable();
            $table->foreign('region_id')->references('region_id')->on('regions')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Sales Representatives
        Schema::create('sales_representatives', function (Blueprint $table) {
            $table->string('rep_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('region')->nullable();
            $table->decimal('sales_target', 15, 2)->default(0);
            $table->string('status')->default('Active');
            $table->timestamps();
        });

        // 5. Sales Orders
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->string('order_id')->primary();
            $table->string('customer_id');
            $table->string('rep_id')->nullable();
            $table->date('order_date');
            $table->string('status')->default('Pending');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('branch')->nullable();
            $table->string('payment_terms')->nullable();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
            $table->foreign('rep_id')->references('rep_id')->on('sales_representatives')->nullOnDelete();
            $table->timestamps();
        });

        // 6. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->string('order_item_id')->primary();
            $table->string('order_id');
            $table->string('product_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('line_total', 15, 2);
            $table->foreign('order_id')->references('order_id')->on('sales_orders')->cascadeOnDelete();
            $table->timestamps();
        });

        // 7. Opportunities (normalized Deal/Opportunity table)
        Schema::create('opportunities', function (Blueprint $table) {
            $table->string('deal_id')->primary();
            $table->string('customer_id');
            $table->string('rep_id')->nullable();
            $table->string('stage')->default('Proposal');
            $table->date('expected_close_date')->nullable();
            $table->decimal('deal_value', 15, 2)->default(0);
            $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
            $table->foreign('rep_id')->references('rep_id')->on('sales_representatives')->nullOnDelete();
            $table->timestamps();
        });

        // 8. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->string('payment_id')->primary();
            $table->string('order_id');
            $table->string('payment_gateway')->nullable();
            $table->string('status')->default('Pending');
            $table->foreign('order_id')->references('order_id')->on('sales_orders')->cascadeOnDelete();
            $table->timestamps();
        });

        // 9. Sales Targets
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->string('target_id')->primary();
            $table->string('rep_id');
            $table->string('month');
            $table->decimal('target_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->foreign('rep_id')->references('rep_id')->on('sales_representatives')->cascadeOnDelete();
            $table->timestamps();
        });

        // 10. Sales Forecasts
        Schema::create('sales_forecasts', function (Blueprint $table) {
            $table->string('forecast_id')->primary();
            $table->string('forecast_period');
            $table->decimal('forecast_amount', 15, 2)->default(0);
            $table->date('generated_date')->nullable();
            $table->timestamps();
        });

        // 11. Tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->string('ticket_id')->primary();
            $table->string('customer_id');
            $table->string('order_id')->nullable();
            $table->string('rep_id')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('priority')->default('Low');
            $table->string('status')->default('Open');
            $table->timestamp('created_at_ts')->nullable();
            $table->timestamp('updated_at_ts')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
            $table->foreign('order_id')->references('order_id')->on('sales_orders')->nullOnDelete();
            $table->foreign('rep_id')->references('rep_id')->on('sales_representatives')->nullOnDelete();
            $table->timestamps();
        });

        // 12. Ticket Conversations
        Schema::create('ticket_conversations', function (Blueprint $table) {
            $table->string('conversation_id')->primary();
            $table->string('ticket_id');
            $table->string('sender_type');
            $table->string('sender_id');
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->cascadeOnDelete();
            $table->timestamps();
        });

        // 13. SLA Rules
        Schema::create('sla_rules', function (Blueprint $table) {
            $table->string('sla_rule_id')->primary();
            $table->string('priority');
            $table->string('response_time_goal')->nullable();
            $table->string('resolution_time_goal')->nullable();
            $table->string('escalation_time')->nullable();
            $table->string('status')->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 14. SLA Monitoring
        Schema::create('sla_monitoring', function (Blueprint $table) {
            $table->string('monitoring_id')->primary();
            $table->string('ticket_id');
            $table->string('sla_rule_id');
            $table->timestamp('response_due')->nullable();
            $table->timestamp('resolution_due')->nullable();
            $table->string('first_response_time')->nullable();
            $table->string('resolution_time')->nullable();
            $table->string('sla_status')->default('On Track');
            $table->integer('compliance_percentage')->default(100);
            $table->timestamp('last_checked')->nullable();
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->cascadeOnDelete();
            $table->foreign('sla_rule_id')->references('sla_rule_id')->on('sla_rules')->cascadeOnDelete();
            $table->timestamps();
        });

        // 15. Escalations
        Schema::create('escalations', function (Blueprint $table) {
            $table->string('escalation_id')->primary();
            $table->string('ticket_id');
            $table->text('reason')->nullable();
            $table->string('priority')->default('High');
            $table->string('assigned_manager')->nullable();
            $table->string('status')->default('Active');
            $table->string('overdue_time')->nullable();
            $table->timestamp('escalated_at')->nullable();
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->cascadeOnDelete();
            $table->timestamps();
        });

        // 16. Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->string('notification_id')->primary();
            $table->string('rep_id')->nullable();
            $table->string('ticket_id')->nullable();
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('type')->default('Info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->foreign('rep_id')->references('rep_id')->on('sales_representatives')->nullOnDelete();
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->nullOnDelete();
            $table->timestamps();
        });

        // 17. Report Logs
        Schema::create('report_logs', function (Blueprint $table) {
            $table->string('report_id')->primary();
            $table->string('generated_by')->nullable();
            $table->string('report_type');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('file_format')->default('PDF');
            $table->foreign('generated_by')->references('rep_id')->on('sales_representatives')->nullOnDelete();
            $table->timestamps();
        });

        // 18. Customer Feedback
        Schema::create('customer_feedback', function (Blueprint $table) {
            $table->string('feedback_id')->primary();
            $table->string('ticket_id');
            $table->string('customer_id');
            $table->tinyInteger('rating')->default(5);
            $table->text('comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->cascadeOnDelete();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_feedback');
        Schema::dropIfExists('report_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('escalations');
        Schema::dropIfExists('sla_monitoring');
        Schema::dropIfExists('sla_rules');
        Schema::dropIfExists('ticket_conversations');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('sales_forecasts');
        Schema::dropIfExists('sales_targets');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('sales_representatives');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customer_segments');
        Schema::dropIfExists('regions');
    }
};
