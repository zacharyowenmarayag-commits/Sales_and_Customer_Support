<?php

namespace App\Http\Controllers;

use App\Models\FollowUp;
use App\Services\DashboardService;
use App\Support\ArrayPaginator;
use App\Support\CrmStorage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        return view('dashboard.index', $dashboardService->prepareOverviewData());
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
        $reps = \App\Models\SalesRepresentative::orderBy('first_name')->get();

        return view('ASSCM.index', [
            'cases'        => $cases,
            'counts'       => $counts,
            'customers'    => $customers,
            'reps'         => $reps,
            'q'            => $q,
            'activeStatus' => $status,
        ]);
    }

    public function asscmView(string $caseId)
    {
        $case = \App\Models\SupportCase::with('customer')->where('case_id', $caseId)->firstOrFail();

        return response()->json([
            'case_id'     => $case->case_id,
            'customer'    => $case->customer ? $case->customer->first_name . ' ' . $case->customer->last_name : '—',
            'issue'       => $case->issue,
            'priority'    => $case->priority,
            'status'      => $case->status,
            'assigned_to' => $case->assigned_to,
            'created_at'  => $case->created_at ? $case->created_at->format('M d, Y h:i A') : '—',
            'updated_at'  => $case->updated_at ? $case->updated_at->format('M d, Y h:i A') : '—',
        ]);
    }

    public function asscmUpdate(Request $request, string $caseId)
    {
        $case = \App\Models\SupportCase::where('case_id', $caseId)->firstOrFail();

        $validated = $request->validate([
            'assigned_to' => ['nullable', 'string', 'max:255'],
            'status'      => ['nullable', 'in:Open,Pending,Escalated,Resolved'],
            'priority'    => ['nullable', 'in:Low,Medium,High'],
            'issue'       => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = array_filter([
            'assigned_to' => $validated['assigned_to'] ?? $case->assigned_to,
            'status'      => $validated['status'] ?? $case->status,
            'priority'    => $validated['priority'] ?? $case->priority,
            'issue'       => $validated['issue'] ?? $case->issue,
        ], function ($val) { return $val !== null; });

        $case->update($updateData);

        return redirect()->route('asscm')->with('success', 'Case ' . $caseId . ' updated successfully.');
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
        $q = $request->query('q');

        // --- Query available date ranges (up to 5 most recent with KPI data) ---
        $parseKey = function ($range) {
            $months = ['January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
                       'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12];
            if (preg_match('/^(\w+)\s+\d+.*,\s*(\d{4})$/', $range, $m)) {
                return intval($m[2]) * 100 + ($months[$m[1]] ?? 0);
            }
            return 0;
        };

        $availableDateRanges = \App\Models\KpiStat::select('date_range')
            ->distinct()
            ->get()
            ->pluck('date_range')
            ->filter()
            ->sortByDesc($parseKey)
            ->values()
            ->take(5);

        // Default to most recent month with data
        $firstAvailable = $availableDateRanges->first() ?? 'May 1 - May 31, 2026';
        $dateRange = $request->query('date_range', $firstAvailable);

        // YYYY-MM list for calendar availability highlighting
        $availableMonths = $availableDateRanges->map(function ($range) {
            $months = ['January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
                       'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12];
            if (preg_match('/^(\w+)\s+\d+.*,\s*(\d{4})$/', $range, $m)) {
                $month = $months[$m[1]] ?? null;
                if ($month) return $m[2] . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            }
            return null;
        })->filter()->values();

        $kpis            = \App\Models\KpiStat::where('date_range', $dateRange)->first();
        $regionSales     = \App\Models\RegionSale::where('date_range', $dateRange)->get();
        $repSales        = \App\Models\RepSale::where('date_range', $dateRange)->get();
        $forecastTargets = \App\Models\ForecastTarget::where('date_range', $dateRange)->get();
        $productSales    = \App\Models\ProductSale::where('date_range', $dateRange)->get();
        $salesPerformance = \App\Models\SalesPerformance::where('date_range', $dateRange)->get();
        $recentDeals     = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', true)->get();

        $highlightMatch = function (string $text, ?string $query): string {
            if (trim((string) $query) === '') {
                return e($text);
            }
            $escapedQuery = preg_quote($query, '/');
            return preg_replace('/(' . $escapedQuery . ')/i', '<span class="text-blue-600">$1</span>', e($text));
        };

        $getProgressWidth = function (string $targetText): float {
            return min(100, max(0, floatval(str_replace('%', '', $targetText))));
        };

        $productSales = $productSales->map(function ($sale) use ($q, $highlightMatch) {
            $sale->highlighted_name = $highlightMatch($sale->product_name, $q);
            return $sale;
        });

        $regionSales = $regionSales->map(function ($region) use ($q, $highlightMatch) {
            $region->highlighted_name = $highlightMatch($region->region_name, $q);
            return $region;
        });

        $repSales = $repSales->map(function ($rep) use ($q, $highlightMatch, $getProgressWidth) {
            $rep->highlighted_name = $highlightMatch($rep->rep_name, $q);
            $rep->progress_width = $getProgressWidth($rep->vs_target);
            return $rep;
        });

        $stageClasses = [
            'Proposal' => 'badge-proposal',
            'Negotiation' => 'badge-negotiation',
            'Qualification' => 'badge-qualification',
            'On-Hold' => 'badge-onhold',
        ];

        $recentDeals = $recentDeals->map(function ($deal) use ($q, $highlightMatch, $stageClasses) {
            $deal->highlighted_name = $highlightMatch($deal->name, $q);
            $deal->highlighted_customer = $highlightMatch($deal->customer, $q);
            $deal->stage_class = $stageClasses[$deal->stage] ?? '';
            return $deal;
        });

        $kpiCards = [
            [
                'label' => 'Total Sales',
                'value' => $kpis->total_sales ?? '₱0',
                'delta' => $kpis->sales_delta ?? '',
                'icon' => 'sales',
                'symbol' => '₱',
            ],
            [
                'label' => 'Total Orders',
                'value' => $kpis->total_orders ?? '0',
                'delta' => $kpis->orders_delta ?? '',
                'icon' => 'orders',
                'symbol' => '🛒',
            ],
            [
                'label' => 'Average Deal Size',
                'value' => $kpis->avg_deal_size ?? '₱0',
                'delta' => $kpis->deal_delta ?? '',
                'icon' => 'deal',
                'symbol' => '◆',
            ],
            [
                'label' => 'Win Rate',
                'value' => $kpis->win_rate ?? '0%',
                'delta' => $kpis->win_delta ?? '',
                'icon' => 'win',
                'symbol' => '◎',
            ],
        ];

        return view('SPRF.index', [
            'dateRange'           => $dateRange,
            'kpis'                => $kpis,
            'kpiCards'            => $kpiCards,
            'sectionDefs'         => [
                ['id' => 'sprf-section-kpi', 'label' => 'KPI Summary Cards', 'icon' => 'fa-th-large'],
                ['id' => 'sprf-section-charts', 'label' => 'Sales Charts', 'icon' => 'fa-chart-line'],
                ['id' => 'sprf-section-details', 'label' => 'Region, Reps & Forecast', 'icon' => 'fa-chart-bar'],
                ['id' => 'sprf-section-deals', 'label' => 'Recent Deals', 'icon' => 'fa-handshake'],
            ],
            'regionSales'         => $regionSales,
            'repSales'            => $repSales,
            'forecastTargets'     => $forecastTargets,
            'productSales'        => $productSales,
            'salesPerformance'    => $salesPerformance,
            'recentDeals'         => $recentDeals,
            'q'                   => $q,
            'availableDateRanges' => $availableDateRanges,
            'availableMonths'     => $availableMonths,
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

        $statusFilter = $request->query('status'); // Reads the ?status= from the URL
        if ($statusFilter && in_array($statusFilter, ['Pending', 'Processed', 'Shipped', 'Delivered'])) {
            $orders = array_filter($orders, function($order) use ($statusFilter) {
                return $order['status'] === $statusFilter;
            });
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

    public function crmDashboard(Request $request)
    {
        $q = $request->query('q');

        // Total Customers
        $customersList = CrmStorage::listCustomers($q);
        $customersCount = count($customersList);

        // Active Deals
        $activeDeals = \App\Models\Deal::where('is_ongoing', true)->count();

        // Revenue (YTD)
        $revenueYTD = \App\Models\SalesOrder::sum('total_amount');

        // Churn Rate (use aggregate query instead of loading all customers)
        $totalCount = \App\Models\Customer::count();
        $cutoffDate = now()->subDays(30);

        $customerStats = \App\Models\Customer::selectRaw('
            customers.customer_id,
            customers.created_at,
            COALESCE(SUM(sales_orders.total_amount), 0) as total_spent,
            COUNT(sales_orders.order_id) as order_count
        ')
            ->leftJoin('sales_orders', 'customers.customer_id', '=', 'sales_orders.customer_id')
            ->groupBy('customers.customer_id', 'customers.created_at')
            ->get();

        $inactiveCount = 0;
        foreach ($customerStats as $stat) {
            $totalSpent = (float) $stat->total_spent;
            $orderCount = (int) $stat->order_count;
            $isNew = $stat->created_at && \Carbon\Carbon::parse($stat->created_at)->greaterThanOrEqualTo($cutoffDate);
            if ($totalSpent < 30000 && $orderCount == 0 && !$isNew) {
                $inactiveCount++;
            }
        }
        $churnRate = $totalCount > 0 ? number_format(($inactiveCount / $totalCount) * 100, 1) : 2.4;

        // Upcoming Tasks (Limit to 4)
        $allTasks = CrmStorage::listFollowUps($q);
        $upcomingTasks = array_filter($allTasks, function ($t) {
            return $t->status === 'Pending';
        });
        usort($upcomingTasks, function ($a, $b) {
            if (!$a->due_date) return 1;
            if (!$b->due_date) return -1;
            return $a->due_date->timestamp <=> $b->due_date->timestamp;
        });
        $upcomingTasks = array_slice($upcomingTasks, 0, 4);

        return view('CRM.dashboard', [
            'customersCount' => $customersCount,
            'activeDeals' => $activeDeals,
            'revenueYTD' => $revenueYTD,
            'churnRate' => $churnRate,
            'upcomingTasks' => $upcomingTasks,
            'allTasks' => $allTasks,
            'q' => $q,
        ]);
    }

    public function crmCustomers(Request $request)
    {
        $q = $request->query('q');

        $customersList = CrmStorage::listCustomers($q);
        $customers = ArrayPaginator::make($customersList, $request);

        return view('CRM.customer', [
            'customers' => $customers,
            'q' => $q,
        ]);
    }

    public function crmCommunicationLogs(Request $request)
    {
        $q = $request->query('q');

        $logsList = CrmStorage::listCommunicationLogs($q);
        $logs = ArrayPaginator::make($logsList, $request);

        $customers = array_map(function ($c) {
            return $c->first_name . ' ' . $c->last_name;
        }, CrmStorage::listCustomers());

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

        $followUpsList = CrmStorage::listFollowUps($q, $status);
        $followUps = ArrayPaginator::make($followUpsList, $request);

        return view('CRM.followup', [
            'followUps' => $followUps,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function crmSegmentation()
    {
        $segments = \App\Models\CustomerSegment::all();

        // Use aggregate queries instead of loading all customers into memory
        $totalCount = \App\Models\Customer::count();

        // Get customer-level aggregates from DB
        $customerStats = \App\Models\Customer::selectRaw('
            customers.customer_id,
            customers.created_at,
            COALESCE(SUM(sales_orders.total_amount), 0) as total_spent,
            COUNT(sales_orders.order_id) as order_count
        ')
            ->leftJoin('sales_orders', 'customers.customer_id', '=', 'sales_orders.customer_id')
            ->groupBy('customers.customer_id', 'customers.created_at')
            ->get();

        $newCount = 0;
        $regularCount = 0;
        $vipCount = 0;
        $inactiveCount = 0;

        $newRevenue = 0;
        $regularRevenue = 0;
        $vipRevenue = 0;
        $inactiveRevenue = 0;

        $cutoffDate = now()->subDays(30);

        foreach ($customerStats as $stat) {
            $totalSpent = (float) $stat->total_spent;
            $orderCount = (int) $stat->order_count;
            $isNew = $stat->created_at && \Carbon\Carbon::parse($stat->created_at)->greaterThanOrEqualTo($cutoffDate);

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

        foreach ($segments as $seg) {
            if ($seg->segment_id === 'SEG-NEW') {
                $seg->estimated_count = $newCount;
                $seg->projected_sales = $newRevenue;
            } elseif ($seg->segment_id === 'SEG-REG') {
                $seg->estimated_count = $regularCount;
                $seg->projected_sales = $regularRevenue;
            } elseif ($seg->segment_id === 'SEG-VIP') {
                $seg->estimated_count = $vipCount;
                $seg->projected_sales = $vipRevenue;
            } elseif ($seg->segment_id === 'SEG-INA') {
                $seg->estimated_count = $inactiveCount;
                $seg->projected_sales = $inactiveRevenue;
            }
        }

        return view('CRM.segmentation', [
            'segments' => $segments,
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

    public function crmSegmentationStore(Request $request)
    {
        $validated = $request->validate([
            'segment_name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'estimated_count' => 'nullable|integer',
            'projected_sales' => 'nullable|numeric',
        ]);

        $nextId = 'SEG-' . (\App\Models\CustomerSegment::count() + 1) . '-' . mt_rand(100, 999);

        \App\Models\CustomerSegment::create([
            'segment_id' => $nextId,
            'segment_name' => $validated['segment_name'],
            'description' => $validated['description'],
            'estimated_count' => $validated['estimated_count'] ?? 0,
            'projected_sales' => $validated['projected_sales'] ?? 0.00,
        ]);

        return redirect()->back()->with('success', 'Segment created successfully.');
    }

    public function crmSegmentationUpdate(Request $request)
    {
        $validated = $request->validate([
            'segment_id' => 'required|string|exists:customer_segments,segment_id',
            'segment_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'estimated_count' => 'nullable|integer',
            'projected_sales' => 'nullable|numeric',
        ]);

        $segment = \App\Models\CustomerSegment::findOrFail($validated['segment_id']);
        
        $segment->update([
            'segment_name'    => $request->filled('segment_name') ? $validated['segment_name'] : $segment->segment_name,
            'description'     => $request->filled('description') ? $validated['description'] : $segment->description,
            'estimated_count' => $request->filled('estimated_count') ? (int)$validated['estimated_count'] : $segment->estimated_count,
            'projected_sales' => $request->filled('projected_sales') ? (float)$validated['projected_sales'] : $segment->projected_sales,
        ]);

        return redirect()->back()->with('success', 'Segment updated successfully.');
    }

    public function sprfDeals(Request $request)
    {
        $q = $request->query('q');

        // --- Query available date ranges (up to 5 most recent with deal data) ---
        $parseKey = function ($range) {
            $months = ['January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
                       'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12];
            if (preg_match('/^(\w+)\s+\d+.*,\s*(\d{4})$/', $range, $m)) {
                return intval($m[2]) * 100 + ($months[$m[1]] ?? 0);
            }
            return 0;
        };

        $availableDateRanges = \App\Models\Deal::select('date_range')
            ->distinct()
            ->get()
            ->pluck('date_range')
            ->filter()
            ->sortByDesc($parseKey)
            ->values()
            ->take(5);

        $firstAvailable = $availableDateRanges->first() ?? 'May 1 - May 31, 2026';
        $dateRange = $request->query('date_range', $firstAvailable);

        // YYYY-MM list for calendar availability highlighting
        $availableMonths = $availableDateRanges->map(function ($range) {
            $months = ['January'=>1,'February'=>2,'March'=>3,'April'=>4,'May'=>5,'June'=>6,
                       'July'=>7,'August'=>8,'September'=>9,'October'=>10,'November'=>11,'December'=>12];
            if (preg_match('/^(\w+)\s+\d+.*,\s*(\d{4})$/', $range, $m)) {
                $month = $months[$m[1]] ?? null;
                if ($month) return $m[2] . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
            }
            return null;
        })->filter()->values();

        // --- Filter parameters ---
        $stages   = $request->query('stage', []);
        $owner    = $request->query('owner');
        $minValue = $request->query('min_value');
        $maxValue = $request->query('max_value');

        $parseValue = function ($val) {
            if (is_numeric($val)) return (float)$val;
            $cleaned = preg_replace('/[^\d.]/', '', $val);
            return $cleaned !== '' ? (float)$cleaned : null;
        };

        $ongoingQuery = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', true);
        $pastQuery    = \App\Models\Deal::where('date_range', $dateRange)->where('is_ongoing', false);

        if (!empty($stages) && is_array($stages)) {
            $ongoingQuery->whereIn('stage', $stages);
            $pastQuery->whereIn('stage', $stages);
        }

        if ($owner) {
            $ongoingQuery->where('owner', 'like', "%{$owner}%");
            $pastQuery->where('owner', 'like', "%{$owner}%");
        }

        $ongoingDeals = $ongoingQuery->get();
        $pastDeals    = $pastQuery->paginate(5, ['*'], 'past_page');

        if ($minValue !== null || $maxValue !== null) {
            $filterByValue = function ($collection) use ($parseValue, $minValue, $maxValue) {
                return $collection->filter(function ($deal) use ($parseValue, $minValue, $maxValue) {
                    $numericValue = $parseValue($deal->value);
                    if ($numericValue === null) return true;
                    if ($minValue !== null && $numericValue < (float)$minValue) return false;
                    if ($maxValue !== null && $numericValue > (float)$maxValue) return false;
                    return true;
                });
            };
            $ongoingDeals = $filterByValue($ongoingDeals);
        }

        $ongoingStageClasses = [
            'Proposal' => 'bg-[#dbe6ff] text-[#3355bb]',
            'Negotiation' => 'bg-[#fff9c4] text-[#a67c00]',
            'Qualification' => 'bg-[#fde2f0] text-[#b0447a]',
            'On-Hold' => 'bg-[#fee2e2] text-[#dc2626]',
        ];
        $pastStageClasses = [
            'Won' => 'bg-[#dcfce7] text-[#15803d]',
            'Lost' => 'bg-[#e0f2fe] text-[#0369a1]',
        ];

        return view('SPRF.deals', [
            'dateRange'           => $dateRange,
            'ongoingDeals'        => $ongoingDeals,
            'pastDeals'           => $pastDeals,
            'q'                   => $q,
            'availableDateRanges' => $availableDateRanges,
            'availableMonths'     => $availableMonths,
            'ongoingStageClasses' => $ongoingStageClasses,
            'pastStageClasses'    => $pastStageClasses,
        ]);
    }
}

