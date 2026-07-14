<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $sprfKpis    = \App\Models\KpiStat::where('date_range', 'May 1 - May 31, 2026')->first();
            $recentDeals = \App\Models\Deal::where('is_ongoing', true)->take(5)->get();
        } catch (\Exception $e) {
            $sprfKpis    = null;
            $recentDeals = collect();
        }

        $somOrders = 6;

        return view('dashboard.index', [
            'sprfKpis'    => $sprfKpis,
            'somOrders'   => $somOrders,
            'recentDeals' => $recentDeals,
        ]);
    }

    public function asscm()
    {
        return view('ASSCM.index');
    }

    public function sprf(Request $request)
    {
        $dateRange = $request->query('date_range', 'May 1 - May 31, 2026');

        $kpis = \App\Models\KpiStat::where('date_range', $dateRange)->first();
        if (!$kpis) {
            $dateRange = 'May 1 - May 31, 2026';
            $kpis = \App\Models\KpiStat::where('date_range', $dateRange)->first();
        }

        $regionSales = \App\Models\RegionSale::where('date_range', $dateRange)->get();
        $repSales = \App\Models\RepSale::where('date_range', $dateRange)->get();
        $forecastTargets = \App\Models\ForecastTarget::where('date_range', $dateRange)->get();
        $productSales = \App\Models\ProductSale::where('date_range', $dateRange)->get();
        $salesPerformance = \App\Models\SalesPerformance::where('date_range', $dateRange)->get();
        $recentDeals = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', true)->get();

        return view('SPRF.index', [
            'dateRange' => $dateRange,
            'kpis' => $kpis,
            'regionSales' => $regionSales,
            'repSales' => $repSales,
            'forecastTargets' => $forecastTargets,
            'productSales' => $productSales,
            'salesPerformance' => $salesPerformance,
            'recentDeals' => $recentDeals,
        ]);
    }

    public function som(Request $request)
    {
        $orders = [
            ['id' => 'ORD-001', 'customer' => 'Jollipop Foods Corporation', 'date' => 'Jun 25, 2026', 'items' => 3, 'total' => 12450.00, 'status' => 'Delivered'],
            ['id' => 'ORD-002', 'customer' => 'SM Retail Inc.', 'date' => 'Jun 22, 2026', 'items' => 5, 'total' => 34200.00, 'status' => 'Shipped'],
            ['id' => 'ORD-003', 'customer' => 'Puregold Price Club', 'date' => 'Jun 20, 2026', 'items' => 2, 'total' => 8900.00, 'status' => 'Processed'],
            ['id' => 'ORD-004', 'customer' => "Robinson's Supermarket", 'date' => 'Jun 18, 2026', 'items' => 7, 'total' => 56780.00, 'status' => 'Pending'],
            ['id' => 'ORD-005', 'customer' => 'Mercury Drug Corporation', 'date' => 'Jun 16, 2026', 'items' => 1, 'total' => 2150.00, 'status' => 'Pending'],
            ['id' => 'ORD-006', 'customer' => '7-Eleven Philippines', 'date' => 'Jun 14, 2026', 'items' => 4, 'total' => 18300.00, 'status' => 'Processed'],
        ];

        $counts = ['Pending' => 0, 'Processed' => 0, 'Shipped' => 0, 'Delivered' => 0];
        foreach ($orders as $order) {
            if (isset($counts[$order['status']])) {
                $counts[$order['status']]++;
            }
        }

        return view('SOM.index', [
            'view' => $request->query('view', 'dashboard'),
            'orders' => $orders,
            'counts' => $counts,
        ]);
    }

    public function somNewOrder()
    {
        $products = [
            ['id' => 'IM-PC-001', 'name' => 'Lucky Me! Pancit Canton — Chilimansi', 'stock' => 500, 'price' => 15.00],
            ['id' => 'IM-CP-01',  'name' => 'Rebisco Crackers (10s pack)',           'stock' => 150, 'price' => 42.50],
            ['id' => 'IM-CF-01',  'name' => 'Chippy BBQ Flavored Corn Chips (110g)', 'stock' => 80,  'price' => 55.00],
            ['id' => 'IM-BC-01',  'name' => 'Bear Brand Sterilized Milk (33ml)',     'stock' => 300, 'price' => 18.00],
            ['id' => 'IM-SK-01',  'name' => 'Skyflakes Crackers (10s pack)',         'stock' => 220, 'price' => 38.00],
            ['id' => 'IM-JC-01',  'name' => 'Jollibee Chicken Joy (1pc)',            'stock' => 60,  'price' => 99.00],
        ];

        $customers = [
            'Jollipop Foods Corporation',
            'SM Retail Inc.',
            'Puregold Price Club',
            "Robinson's Supermarket",
            'Mercury Drug Corporation',
            '7-Eleven Philippines',
        ];

        return view('SOM.new-order', compact('products', 'customers'));
    }

    public function crmDashboard()
    {
        return view('CRM.dashboard');
    }

    public function crmCustomers()
    {
        return view('CRM.customer');
    }

    public function crmPurchaseHistory()
    {
        return view('CRM.purchase-history');
    }

    public function crmCommunicationLogs()
    {
        return view('CRM.comlog');
    }

    public function crmFollowup()
    {
        return view('CRM.followup');
    }

    public function crmSegmentation()
    {
        return view('CRM.segmentation');
    }

    public function sprfDeals(Request $request)
    {
        $dateRange = $request->query('date_range', 'May 1 - May 31, 2026');

        $hasData = \App\Models\Deal::where('date_range', $dateRange)->exists();
        if (!$hasData) {
            $dateRange = 'May 1 - May 31, 2026';
        }

        $ongoingDeals = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', true)->get();
        $pastDeals = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', false)->get();

        return view('SPRF.deals', [
            'dateRange' => $dateRange,
            'ongoingDeals' => $ongoingDeals,
            'pastDeals' => $pastDeals,
        ]);
    }
}