<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Deal;
use App\Models\KpiStat;
use App\Models\SalesPerformance;
use App\Models\ProductSale;
use App\Models\RegionSale;
use App\Models\RepSale;
use App\Models\ForecastTarget;

class SprfSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate existing data to prevent duplicate seeds
        KpiStat::truncate();
        Deal::truncate();
        SalesPerformance::truncate();
        ProductSale::truncate();
        RegionSale::truncate();
        RepSale::truncate();
        ForecastTarget::truncate();

        // ==========================================
        // SEED DATA FOR MAY 1 - MAY 31, 2026
        // ==========================================
        $mayRange = "May 1 - May 31, 2026";

        KpiStat::create([
            'date_range' => $mayRange,
            'total_sales' => '₱1,234,567',
            'sales_delta' => '+25.6% vs May 1 - May 30',
            'total_orders' => '345',
            'orders_delta' => '+21.7% vs May 1 - May 30',
            'avg_deal_size' => '₱2,500',
            'deal_delta' => '+24.9% vs May 1 - May 30',
            'win_rate' => '67.1%',
            'win_delta' => '+27.6% vs May 1 - May 30'
        ]);

        // May Ongoing Deals
        Deal::create(['date_range' => $mayRange, 'name' => 'Site Renovation', 'customer' => 'ABC Corp.', 'stage' => 'Proposal', 'value' => '₱734,000', 'expected_close' => 'July 15, 2026', 'owner' => 'Ash Ketchum', 'is_ongoing' => true]);
        Deal::create(['date_range' => $mayRange, 'name' => 'IT Equipments', 'customer' => 'Tech Solutions', 'stage' => 'Negotiation', 'value' => '₱532,000', 'expected_close' => 'July 30, 2026', 'owner' => 'Misty Reyes', 'is_ongoing' => true]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Confidential F.', 'customer' => 'Sarah D First', 'stage' => 'Qualification', 'value' => '₱1,500,000', 'expected_close' => 'Aug 14, 2026', 'owner' => 'Confidential', 'is_ongoing' => true]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'On-Hold', 'value' => '₱230,000', 'expected_close' => 'Dec 21, 2026', 'owner' => 'Rafael Tanks', 'is_ongoing' => true]);

        // May Past Deals
        Deal::create(['date_range' => $mayRange, 'name' => 'Site Renovation', 'customer' => 'ABC Corp.', 'stage' => 'Won', 'value' => '₱734,000', 'expected_close' => 'May 17, 2026', 'owner' => 'Ash Ketchum', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'IT Equipments', 'customer' => 'Tech Solutions', 'stage' => 'Won', 'value' => '₱532,000', 'expected_close' => 'Apr 28, 2026', 'owner' => 'Misty Reyes', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Confidential F.', 'customer' => 'Sarah D First', 'stage' => 'Won', 'value' => '₱400,000', 'expected_close' => 'Mar 15, 2026', 'owner' => 'Confidential', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Lost', 'value' => '₱230,000', 'expected_close' => 'Jan 21, 2026', 'owner' => 'Rafael Tanks', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Won', 'value' => '₱230,000', 'expected_close' => 'Dec 19, 2025', 'owner' => 'El PJ Tanks', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Won', 'value' => '₱230,000', 'expected_close' => 'Dec 17, 2025', 'owner' => 'El PJ Tanks', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Won', 'value' => '₱230,000', 'expected_close' => 'Nov 28, 2025', 'owner' => 'Rafael Tanks', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Won', 'value' => '₱230,000', 'expected_close' => 'Nov 9, 2025', 'owner' => 'Rafael Tanks', 'is_ongoing' => false]);
        Deal::create(['date_range' => $mayRange, 'name' => 'Bulk Supplies', 'customer' => 'XYZ Comp.', 'stage' => 'Won', 'value' => '₱230,000', 'expected_close' => 'Oct 27, 2025', 'owner' => 'Rafael Tanks', 'is_ongoing' => false]);

        // May Sales Performances (Sales Performance Over Time Chart)
        SalesPerformance::create(['date_range' => $mayRange, 'label' => 'MAY 1', 'sales_amount' => 200000, 'orders_count' => 20]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => 'MAY 5', 'sales_amount' => 380000, 'orders_count' => 25]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => '9 9', 'sales_amount' => 390000, 'orders_count' => 25]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => '13 17', 'sales_amount' => 420000, 'orders_count' => 24]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => '13 17 ', 'sales_amount' => 620000, 'orders_count' => 34]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => '21 21', 'sales_amount' => 720000, 'orders_count' => 55]);
        SalesPerformance::create(['date_range' => $mayRange, 'label' => 'MAY 30', 'sales_amount' => 820000, 'orders_count' => 76]);

        // May Product Sales (Sales by Product Pie/Doughnut Chart)
        ProductSale::create(['date_range' => $mayRange, 'product_name' => 'Organic Fertilizers', 'percentage' => 30, 'color' => '#3f8a4a']);
        ProductSale::create(['date_range' => $mayRange, 'product_name' => 'Heirloom Seeds', 'percentage' => 25, 'color' => '#3aa0c9']);
        ProductSale::create(['date_range' => $mayRange, 'product_name' => 'Biopesticides', 'percentage' => 12, 'color' => '#8b5a2b']);
        ProductSale::create(['date_range' => $mayRange, 'product_name' => 'Drip Irrigation Systems', 'percentage' => 18, 'color' => '#e08a3a']);
        ProductSale::create(['date_range' => $mayRange, 'product_name' => 'Hybrid Seed', 'percentage' => 15, 'color' => '#f0b429']);

        // May Region Sales
        RegionSale::create(['date_range' => $mayRange, 'region_name' => 'Luzon', 'sales_amount' => '₱1,123,935', 'vs_last_month' => '25.6%']);
        RegionSale::create(['date_range' => $mayRange, 'region_name' => 'Visayas', 'sales_amount' => '₱1,012,453', 'vs_last_month' => '24.5%']);
        RegionSale::create(['date_range' => $mayRange, 'region_name' => 'Mindanao', 'sales_amount' => '₱917,298', 'vs_last_month' => '21.4%']);
        RegionSale::create(['date_range' => $mayRange, 'region_name' => 'NCR', 'sales_amount' => '₱722,433', 'vs_last_month' => '18.2%']);

        // May Rep Sales
        RepSale::create(['date_range' => $mayRange, 'rep_name' => 'Elsa Lgh', 'sales_amount' => '₱934,000', 'vs_target' => '112.5%']);
        RepSale::create(['date_range' => $mayRange, 'rep_name' => 'Dee Nuts', 'sales_amount' => '₱800,000', 'vs_target' => '102.2%']);
        RepSale::create(['date_range' => $mayRange, 'rep_name' => 'Lee Kah', 'sales_amount' => '₱751,000', 'vs_target' => '98.4%']);
        RepSale::create(['date_range' => $mayRange, 'rep_name' => 'Fred Rice', 'sales_amount' => '₱672,000', 'vs_target' => '90.4%']);

        // May Forecast vs Target
        ForecastTarget::create(['date_range' => $mayRange, 'category' => 'A', 'actual_amount' => 120, 'target_amount' => 350]);
        ForecastTarget::create(['date_range' => $mayRange, 'category' => 'B', 'actual_amount' => 620, 'target_amount' => 100]);
        ForecastTarget::create(['date_range' => $mayRange, 'category' => 'C', 'actual_amount' => 380, 'target_amount' => 50]);
        ForecastTarget::create(['date_range' => $mayRange, 'category' => 'D', 'actual_amount' => 120, 'target_amount' => 50]);
        ForecastTarget::create(['date_range' => $mayRange, 'category' => 'E', 'actual_amount' => 280, 'target_amount' => 380]);


        // ==========================================
        // SEED DATA FOR APR 1 - APR 30, 2026
        // ==========================================
        $aprRange = "Apr 1 - Apr 30, 2026";

        KpiStat::create([
            'date_range' => $aprRange,
            'total_sales' => '₱984,320',
            'sales_delta' => '+12.3% vs Apr 1 - Apr 30',
            'total_orders' => '280',
            'orders_delta' => '+8.4% vs Apr 1 - Apr 30',
            'avg_deal_size' => '₱3,515',
            'deal_delta' => '+5.1% vs Apr 1 - Apr 30',
            'win_rate' => '62.5%',
            'win_delta' => '+14.2% vs Apr 1 - Apr 30'
        ]);

        // April Ongoing Deals
        Deal::create(['date_range' => $aprRange, 'name' => 'Greenhouse Upgrade', 'customer' => 'Flora Farms', 'stage' => 'Proposal', 'value' => '₱620,000', 'expected_close' => 'June 20, 2026', 'owner' => 'Ash Ketchum', 'is_ongoing' => true]);
        Deal::create(['date_range' => $aprRange, 'name' => 'Solar Irrigation', 'customer' => 'Eco Fields', 'stage' => 'Negotiation', 'value' => '₱480,000', 'expected_close' => 'June 28, 2026', 'owner' => 'Misty Reyes', 'is_ongoing' => true]);
        Deal::create(['date_range' => $aprRange, 'name' => 'Warehouse Lease', 'customer' => 'Storage Inc.', 'stage' => 'Qualification', 'value' => '₱950,000', 'expected_close' => 'July 10, 2026', 'owner' => 'Confidential', 'is_ongoing' => true]);

        // April Past Deals
        Deal::create(['date_range' => $aprRange, 'name' => 'Soil Test Kit', 'customer' => 'Bio Lab', 'stage' => 'Won', 'value' => '₱120,000', 'expected_close' => 'Apr 18, 2026', 'owner' => 'Ash Ketchum', 'is_ongoing' => false]);
        Deal::create(['date_range' => $aprRange, 'name' => 'Tractor Lease', 'customer' => 'Agritech', 'stage' => 'Lost', 'value' => '₱350,000', 'expected_close' => 'Apr 5, 2026', 'owner' => 'Misty Reyes', 'is_ongoing' => false]);

        // April Sales Performances
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 1', 'sales_amount' => 150000, 'orders_count' => 15]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 5', 'sales_amount' => 220000, 'orders_count' => 18]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 9', 'sales_amount' => 310000, 'orders_count' => 22]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 13', 'sales_amount' => 290000, 'orders_count' => 20]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 17', 'sales_amount' => 450000, 'orders_count' => 28]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 21', 'sales_amount' => 510000, 'orders_count' => 35]);
        SalesPerformance::create(['date_range' => $aprRange, 'label' => 'APR 30', 'sales_amount' => 600000, 'orders_count' => 48]);

        // April Product Sales
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Organic Fertilizers', 'percentage' => 20, 'color' => '#3f8a4a']);
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Heirloom Seeds', 'percentage' => 30, 'color' => '#3aa0c9']);
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Biopesticides', 'percentage' => 15, 'color' => '#8b5a2b']);
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Drip Irrigation Systems', 'percentage' => 15, 'color' => '#e08a3a']);
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Hybrid Seed', 'percentage' => 12, 'color' => '#f0b429']);
        ProductSale::create(['date_range' => $aprRange, 'product_name' => 'Automated Systems', 'percentage' => 8, 'color' => '#7a8a8a']);

        // April Region Sales
        RegionSale::create(['date_range' => $aprRange, 'region_name' => 'Luzon', 'sales_amount' => '₱980,150', 'vs_last_month' => '18.4%']);
        RegionSale::create(['date_range' => $aprRange, 'region_name' => 'Visayas', 'sales_amount' => '₱850,220', 'vs_last_month' => '15.1%']);
        RegionSale::create(['date_range' => $aprRange, 'region_name' => 'Mindanao', 'sales_amount' => '₱720,400', 'vs_last_month' => '12.5%']);
        RegionSale::create(['date_range' => $aprRange, 'region_name' => 'NCR', 'sales_amount' => '₱610,310', 'vs_last_month' => '10.2%']);

        // April Rep Sales
        RepSale::create(['date_range' => $aprRange, 'rep_name' => 'Elsa Lgh', 'sales_amount' => '₱710,000', 'vs_target' => '95.2%']);
        RepSale::create(['date_range' => $aprRange, 'rep_name' => 'Dee Nuts', 'sales_amount' => '₱650,000', 'vs_target' => '90.1%']);
        RepSale::create(['date_range' => $aprRange, 'rep_name' => 'Lee Kah', 'sales_amount' => '₱680,000', 'vs_target' => '92.4%']);
        RepSale::create(['date_range' => $aprRange, 'rep_name' => 'Fred Rice', 'sales_amount' => '₱550,000', 'vs_target' => '82.5%']);

        // April Forecast vs Target
        ForecastTarget::create(['date_range' => $aprRange, 'category' => 'A', 'actual_amount' => 180, 'target_amount' => 300]);
        ForecastTarget::create(['date_range' => $aprRange, 'category' => 'B', 'actual_amount' => 450, 'target_amount' => 150]);
        ForecastTarget::create(['date_range' => $aprRange, 'category' => 'C', 'actual_amount' => 290, 'target_amount' => 80]);
        ForecastTarget::create(['date_range' => $aprRange, 'category' => 'D', 'actual_amount' => 160, 'target_amount' => 80]);
        ForecastTarget::create(['date_range' => $aprRange, 'category' => 'E', 'actual_amount' => 310, 'target_amount' => 350]);
    }
}
