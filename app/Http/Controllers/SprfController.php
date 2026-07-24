<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SprfDashboardService;

class SprfController extends Controller
{
    /**
     * Display the SPRF dashboard with all chart data.
     */
    public function index(Request $request, SprfDashboardService $dashboardService)
    {
        $dateRange = $request->query('date_range', 'May 1 - May 31, 2026');
        $searchQuery = $request->query('q', '');

        $dashboardData = $dashboardService->prepareDashboardData($dateRange, $searchQuery);

        return view('SPRF.index', $dashboardData);
    }
}
