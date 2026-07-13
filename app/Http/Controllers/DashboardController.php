<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ASSCM.index');
    }

    public function sprf()
    {
        return view('SPRF.index');
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

    public function sprfDeals()
    {
        return view('SPRF.deals');
    }
}