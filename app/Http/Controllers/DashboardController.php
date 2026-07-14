<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Support\ArrayPaginator;
use App\Support\CrmStorage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
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

    public function crmDashboard()
    {
        CrmStorage::ensureSeeded();

        $customersCount = count(CrmStorage::distinctCustomers());
        $logsCount = CrmStorage::communicationLogsCount();

        $pendingFollowUps = CrmStorage::followUpsCountByStatus('Pending');
        $completedFollowUps = CrmStorage::followUpsCountByStatus('Completed');

        return view('CRM.dashboard', [
            'customersCount' => $customersCount,
            'logsCount' => $logsCount,
            'pendingFollowUps' => $pendingFollowUps,
            'completedFollowUps' => $completedFollowUps,
        ]);
    }

    public function crmCustomers(Request $request)
    {
        CrmStorage::ensureSeeded();

        $q = $request->query('q');
        $customers = ArrayPaginator::make(CrmStorage::listCustomers($q), $request);

        return view('CRM.customer', [
            'customers' => $customers,
            'q' => $q,
        ]);
    }

    public function crmPurchaseHistory()
    {
        return view('CRM.purchase-history');
    }

    public function crmCommunicationLogs(Request $request)
    {
        CrmStorage::ensureSeeded();

        $q = $request->query('q');

        $logs = ArrayPaginator::make(CrmStorage::listCommunicationLogs($q, null), $request);
        $customers = CrmStorage::distinctCustomers();

        return view('CRM.comlog', [
            'logs' => $logs,
            'customers' => $customers,
            'subjects' => \App\Models\CommunicationLog::SUBJECTS,
            'q' => $q,
        ]);
    }

    public function crmFollowup(Request $request)
    {
        CrmStorage::ensureSeeded();

        $q = $request->query('q');
        $status = $request->query('status');

        if ($status && !in_array($status, FollowUp::STATUSES, true)) {
            $status = null;
        }

        $followUps = ArrayPaginator::make(CrmStorage::listFollowUps($q, $status), $request);

        return view('CRM.followup', [
            'followUps' => $followUps,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function crmSegmentation()
    {
        CrmStorage::ensureSeeded();

        $allCustomers = CrmStorage::distinctCustomers();
        $customersWithFollowUps = CrmStorage::customersWithFollowUps();

        $prospectCount = count(array_values(array_diff($allCustomers, $customersWithFollowUps)));
        $activeCount = count($customersWithFollowUps);

        $vipCustomers = CrmStorage::customersWithPurchases();

        $vipCount = count($vipCustomers);

        return view('CRM.segmentation', [
            'prospectCount' => $prospectCount,
            'activeCount' => $activeCount,
            'vipCount' => $vipCount,
        ]);
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
