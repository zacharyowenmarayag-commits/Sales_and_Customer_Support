<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SprfController extends Controller
{
    /**
     * Display the SPRF dashboard with all chart data.
     */
    public function index(Request $request)
    {
        $dateRange = $request->query('date_range', 'May 1 - May 31, 2026');

        // Retrieve KPI stats for the selected date range
        $kpis = \App\Models\KpiStat::where('date_range', $dateRange)->first();
        if (!$kpis) {
            $dateRange = 'May 1 - May 31, 2026';
            $kpis = \App\Models\KpiStat::where('date_range', $dateRange)->first();
        }

        // Chart‑related data collections
        $regionSales      = \App\Models\RegionSale::where('date_range', $dateRange)->get();
        $repSales         = \App\Models\RepSale::where('date_range', $dateRange)->get();
        $forecastTargets  = \App\Models\ForecastTarget::where('date_range', $dateRange)->get();
        $productSales     = \App\Models\ProductSale::where('date_range', $dateRange)->get();
        $salesPerformance = \App\Models\SalesPerformance::where('date_range', $dateRange)->get();
        $recentDeals      = \App\Models\Deal::where('date_range', $dateRange)
                                    ->where('is_ongoing', true)
                                    ->get();

        return view('SPRF.index', [
            'dateRange'        => $dateRange,
            'kpis'             => $kpis,
            'regionSales'      => $regionSales,
            'repSales'         => $repSales,
            'forecastTargets'  => $forecastTargets,
            'productSales'     => $productSales,
            'salesPerformance' => $salesPerformance,
            'recentDeals'      => $recentDeals,
        ]);
    }
}
