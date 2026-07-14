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
        try {
            $sprfKpis    = \App\Models\KpiStat::where('date_range', 'May 1 - May 31, 2026')->first();
            $recentDeals = \App\Models\Deal::where('is_ongoing', true)->take(5)->get();
        } catch (\Exception $e) {
            $sprfKpis    = null;
            $recentDeals = collect();
        }

        $somOrders = \App\Models\SalesOrder::count();

        return view('dashboard.index', [
            'sprfKpis'    => $sprfKpis,
            'somOrders'   => $somOrders,
            'recentDeals' => $recentDeals,
        ]);
    }

    public function asscm(Request $request)
    {
        $q      = $request->query('q');
        $status = $request->query('status');

        $validStatuses = ['Open', 'Pending', 'Escalated', 'Resolved'];
        if ($status && !in_array($status, $validStatuses, true)) {
            $status = null;
        }

        $query = \App\Models\SupportCase::with('customer');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('issue', 'like', "%{$q}%")
                    ->orWhereHas('customer', function ($c) use ($q) {
                        $c->where('first_name', 'like', "%{$q}%")
                          ->orWhere('last_name', 'like', "%{$q}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $cases = $query->paginate(15);

        // Counts always reflect ALL cases (ignoring current filter)
        $counts = [
            'Open'      => \App\Models\SupportCase::where('status', 'Open')->count(),
            'Pending'   => \App\Models\SupportCase::where('status', 'Pending')->count(),
            'Escalated' => \App\Models\SupportCase::where('status', 'Escalated')->count(),
            'Resolved'  => \App\Models\SupportCase::where('status', 'Resolved')->count(),
        ];

        $customers = \App\Models\Customer::orderBy('first_name')->get();

        return view('ASSCM.index', [
            'cases'        => $cases,
            'counts'       => $counts,
            'customers'    => $customers,
            'q'            => $q,
            'activeStatus' => $status,
        ]);
    }

    public function asscmStore(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'string', 'exists:customers,customer_id'],
            'issue'       => ['required', 'string', 'max:1000'],
            'priority'    => ['required', 'in:Low,Medium,High'],
        ]);

        // Auto-generate case_id: CAS-XXXX
        $last = \App\Models\SupportCase::orderBy('case_id', 'desc')->first();
        $nextNum = 1001;
        if ($last && preg_match('/CAS-(\d+)/', $last->case_id, $m)) {
            $nextNum = (int)$m[1] + 1;
        }

        \App\Models\SupportCase::create([
            'case_id'     => 'CAS-' . $nextNum,
            'customer_id' => $validated['customer_id'],
            'issue'       => $validated['issue'],
            'priority'    => $validated['priority'],
            'status'      => 'Open',
        ]);

        return redirect()->route('asscm')->with('success', 'Case created successfully.');
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
        $q      = $request->query('q');
        $status = $request->query('status');

        $validStatuses = ['Pending', 'Processed', 'Shipped', 'Delivered'];
        if ($status && !in_array($status, $validStatuses, true)) {
            $status = null;
        }

        $query = \App\Models\SalesOrder::with(['customer', 'items'])->orderBy('order_date', 'desc');

        if ($q) {
            $query->whereHas('customer', function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();

        // Counts always reflect ALL orders (ignoring current filter)
        $allOrders = \App\Models\SalesOrder::all();
        $counts = ['Pending' => 0, 'Processed' => 0, 'Shipped' => 0, 'Delivered' => 0];
        foreach ($allOrders as $order) {
            if (isset($counts[$order->status])) {
                $counts[$order->status]++;
            }
        }

        return view('SOM.index', [
            'view'          => $request->query('view', 'dashboard'),
            'orders'        => $orders,
            'counts'        => $counts,
            'q'             => $q,
            'activeStatus'  => $status,
        ]);
    }

    public function dbSchema()
    {
        return view('db-schema.index');
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

        $customers = \App\Models\Customer::all();

        return view('SOM.new-order', compact('products', 'customers'));
    }

    public function somSaveOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'string', 'exists:customers,customer_id'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'products' => ['required', 'array'],
        ]);

        $customerId = $validated['customer_id'];
        $discountPercent = (float)($validated['discount'] ?? 0);

        // Find product price maps
        $productPrices = [
            'IM-PC-001' => 15.00,
            'IM-CP-01'  => 42.50,
            'IM-CF-01'  => 55.00,
            'IM-BC-01'  => 18.00,
            'IM-SK-01'  => 38.00,
            'IM-JC-01'  => 99.00,
        ];

        $subtotal = 0;
        $itemsToCreate = [];

        // Generate sequential item ID
        $itemSeq = 800 + mt_rand(1, 99);

        foreach ($request->input('products') as $prodId => $pData) {
            $qty = (int)($pData['qty'] ?? 0);
            if ($qty <= 0) continue;

            $price = $productPrices[$prodId] ?? 0.00;
            $lineTotal = $qty * $price;
            $subtotal += $lineTotal;

            $itemsToCreate[] = [
                'order_item_id' => 'ITEM-' . ($itemSeq++),
                'product_id' => 'PROD-' . $prodId,
                'quantity' => $qty,
                'unit_price' => $price,
                'line_total' => $lineTotal,
            ];
        }

        if (empty($itemsToCreate)) {
            return redirect()->back()->withErrors(['products' => 'Please add at least one item.'])->withInput();
        }

        // Calculations
        $discounted = $subtotal * (1 - $discountPercent / 100);
        $tax = $discounted * 0.12;
        $total = $discounted + $tax;

        // Auto-generate order_id
        $lastOrderNum = 0;
        $lastOrder = \App\Models\SalesOrder::orderBy('order_id', 'desc')->first();
        if ($lastOrder && preg_match('/ORD-(\d+)/', $lastOrder->order_id, $matches)) {
            $lastOrderNum = (int)$matches[1];
        }
        $newOrderId = 'ORD-' . str_pad($lastOrderNum + 1, 3, '0', STR_PAD_LEFT);

        // Save order
        $order = \App\Models\SalesOrder::create([
            'order_id' => $newOrderId,
            'customer_id' => $customerId,
            'rep_id' => 'REP-101', // Default rep
            'order_date' => now()->format('Y-m-d'),
            'status' => 'Pending',
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'discount_amount' => $subtotal * ($discountPercent / 100),
            'total_amount' => $total,
            'branch' => 'Cavite',
            'payment_terms' => 'Net 30 days',
        ]);

        // Save items
        foreach ($itemsToCreate as $itemData) {
            $itemData['order_id'] = $order->order_id;
            \App\Models\OrderItem::create($itemData);
        }

        return redirect()->route('som')->with('success', 'Sales order created successfully.');
    }

    public function crmDashboard()
    {
        $customersCount = \App\Models\Customer::count();
        $logsCount = \App\Models\CommunicationLog::count();

        $pendingFollowUps = \App\Models\FollowUp::where('status', 'Pending')->count();
        $completedFollowUps = \App\Models\FollowUp::where('status', 'Completed')->count();

        return view('CRM.dashboard', [
            'customersCount' => $customersCount,
            'logsCount' => $logsCount,
            'pendingFollowUps' => $pendingFollowUps,
            'completedFollowUps' => $completedFollowUps,
        ]);
    }

    public function crmCustomers(Request $request)
    {
        $q = $request->query('q');

        $query = \App\Models\Customer::query();
        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%");
            });
        }

        $customers = $query->paginate(10);

        return view('CRM.customer', [
            'customers' => $customers,
            'q' => $q,
        ]);
    }

    public function crmPurchaseHistory(Request $request)
    {
        $q = $request->query('q');

        $query = \App\Models\SalesOrder::with('customer')
            ->whereNotNull('customer_id')
            ->orderBy('order_date', 'desc');

        if ($q) {
            $query->whereHas('customer', function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%");
            });
        }

        $paginator = $query->paginate(10);

        $rows = $paginator->getCollection()->map(function ($order) {
            $customerName = $order->customer
                ? $order->customer->first_name . ' ' . $order->customer->last_name
                : 'Unknown';
            return [
                'date'     => $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('M j, Y') : '—',
                'customer' => $customerName,
                'order_id' => $order->order_id,
                'amount'   => '₱' . number_format($order->total_amount, 2),
            ];
        });

        // Replace collection on paginator
        $rows = $paginator->setCollection($rows);

        return view('CRM.purchase-history', [
            'rows' => $rows,
            'q'    => $q,
        ]);
    }

    public function crmCommunicationLogs(Request $request)
    {
        $q = $request->query('q');

        $query = \App\Models\CommunicationLog::query();
        if ($q) {
            $query->where('customer', 'like', "%{$q}%");
        }

        $logs = $query->paginate(10);
        $customers = \App\Models\Customer::all()->map(function ($c) {
            return $c->first_name . ' ' . $c->last_name;
        })->toArray();

        return view('CRM.comlog', [
            'logs' => $logs,
            'customers' => $customers,
            'subjects' => \App\Models\CommunicationLog::SUBJECTS,
            'q' => $q,
        ]);
    }

    public function crmFollowup(Request $request)
    {
        $q = $request->query('q');
        $status = $request->query('status');

        if ($status && !in_array($status, FollowUp::STATUSES, true)) {
            $status = null;
        }

        $query = \App\Models\FollowUp::query();
        if ($q) {
            $query->where('customer', 'like', "%{$q}%");
        }
        if ($status) {
            $query->where('status', $status);
        }

        $followUps = $query->paginate(10);

        return view('CRM.followup', [
            'followUps' => $followUps,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function crmSegmentation()
    {
        $customers = \App\Models\Customer::with('salesOrders')->get();
        $totalCount = $customers->count();

        $newCount = 0;
        $regularCount = 0;
        $vipCount = 0;
        $inactiveCount = 0;

        $newRevenue = 0;
        $regularRevenue = 0;
        $vipRevenue = 0;
        $inactiveRevenue = 0;

        foreach ($customers as $customer) {
            $totalSpent = (float)$customer->salesOrders->sum('total_amount');
            $orderCount = $customer->salesOrders->count();

            $isNew = $customer->created_at && $customer->created_at->greaterThanOrEqualTo(now()->subDays(30));

            if ($totalSpent >= 30000) {
                $vipCount++;
                $vipRevenue += $totalSpent;
            } elseif ($orderCount > 0) {
                $regularCount++;
                $regularRevenue += $totalSpent;
            } elseif ($isNew) {
                $newCount++;
                $newRevenue += $totalSpent;
            } else {
                $inactiveCount++;
                $inactiveRevenue += $totalSpent;
            }
        }

        return view('CRM.segmentation', [
            'totalCount' => $totalCount,
            'newCount' => $newCount,
            'regularCount' => $regularCount,
            'vipCount' => $vipCount,
            'inactiveCount' => $inactiveCount,
            'newRevenue' => $newRevenue,
            'regularRevenue' => $regularRevenue,
            'vipRevenue' => $vipRevenue,
            'inactiveRevenue' => $inactiveRevenue,
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
        $pastDeals = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', false)->paginate(5, ['*'], 'past_page');

        return view('SPRF.deals', [
            'dateRange' => $dateRange,
            'ongoingDeals' => $ongoingDeals,
            'pastDeals' => $pastDeals,
        ]);
    }
}
