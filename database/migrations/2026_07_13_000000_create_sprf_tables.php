<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_stats', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('total_sales');
            $table->string('sales_delta');
            $table->string('total_orders');
            $table->string('orders_delta');
            $table->string('avg_deal_size');
            $table->string('deal_delta');
            $table->string('win_rate');
            $table->string('win_delta');
            $table->timestamps();
        });

        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('name');
            $table->string('customer');
            $table->string('stage');
            $table->string('value');
            $table->string('expected_close');
            $table->string('owner');
            $table->boolean('is_ongoing')->default(false);
            $table->timestamps();
        });

        Schema::create('sales_performances', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('label');
            $table->double('sales_amount');
            $table->integer('orders_count');
            $table->timestamps();
        });

        Schema::create('product_sales', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('product_name');
            $table->integer('percentage');
            $table->string('color');
            $table->timestamps();
        });

        Schema::create('region_sales', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('region_name');
            $table->string('sales_amount');
            $table->string('vs_last_month');
            $table->timestamps();
        });

        Schema::create('rep_sales', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('rep_name');
            $table->string('sales_amount');
            $table->string('vs_target');
            $table->timestamps();
        });

        Schema::create('forecast_targets', function (Blueprint $table) {
            $table->id();
            $table->string('date_range');
            $table->string('category');
            $table->integer('actual_amount');
            $table->integer('target_amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forecast_targets');
        Schema::dropIfExists('rep_sales');
        Schema::dropIfExists('region_sales');
        Schema::dropIfExists('product_sales');
        Schema::dropIfExists('sales_performances');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('kpi_stats');
    }
};
