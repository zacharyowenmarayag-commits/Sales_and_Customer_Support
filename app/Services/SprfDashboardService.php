<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\ForecastTarget;
use App\Models\KpiStat;
use App\Models\ProductSale;
use App\Models\RegionSale;
use App\Models\RepSale;

class SprfDashboardService
{
    public function prepareDashboardData(string $dateRange, string $searchQuery = ''): array
    {
        $availableDateRanges = KpiStat::distinct()->pluck('date_range')->toArray();

        if (!in_array($dateRange, $availableDateRanges, true)) {
            $dateRange = 'May 1 - May 31, 2026';
        }

        $kpis = KpiStat::where('date_range', $dateRange)->first();
        if (! $kpis) {
            $dateRange = 'May 1 - May 31, 2026';
            $kpis = KpiStat::where('date_range', $dateRange)->first();
        }

        $salesPerformance = $this->loadSalesPerformance($dateRange);
        $productSales = $this->loadProductSales($dateRange, $searchQuery);
        $forecastTargets = $this->loadForecastTargets($dateRange);
        $regionSales = $this->loadRegionSales($dateRange, $searchQuery);
        $repSales = $this->loadRepSales($dateRange, $searchQuery);
        $recentDeals = $this->loadRecentDeals($dateRange, $searchQuery);

        return [
            'dateRange' => $dateRange,
            'q' => $searchQuery,
            'availableDateRanges' => $availableDateRanges,
            'kpis' => $kpis,
            'kpiCards' => $this->buildKpiCards($kpis),
            'sectionDefs' => $this->buildSectionDefs(),
            'stageClasses' => $this->buildStageClasses(),
            'salesPerformance' => $salesPerformance,
            'salesPerformanceLabels' => $salesPerformance->pluck('label')->toArray(),
            'salesPerformanceSalesData' => $salesPerformance->pluck('sales_amount')->toArray(),
            'salesPerformanceOrdersData' => $salesPerformance->pluck('orders_count')->toArray(),
            'productSales' => $productSales,
            'productChartLabels' => $productSales->pluck('product_name')->toArray(),
            'productChartData' => $productSales->pluck('percentage')->toArray(),
            'productChartColors' => $productSales->pluck('color')->toArray(),
            'forecastTargets' => $forecastTargets,
            'forecastChartLabels' => $forecastTargets->pluck('category')->toArray(),
            'forecastChartTargetData' => $forecastTargets->pluck('target_amount')->toArray(),
            'forecastChartActualData' => $forecastTargets->pluck('actual_amount')->toArray(),
            'regionSales' => $regionSales,
            'repSales' => $repSales,
            'forecastTargets' => $forecastTargets,
            'recentDeals' => $recentDeals,
        ];
    }

    protected function buildKpiCards($kpis): array
    {
        return [
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
    }

    protected function buildSectionDefs(): array
    {
        return [
            ['id' => 'sprf-section-kpi', 'label' => 'KPI Summary Cards', 'icon' => 'fa-th-large'],
            ['id' => 'sprf-section-charts', 'label' => 'Sales Charts', 'icon' => 'fa-chart-line'],
            ['id' => 'sprf-section-details', 'label' => 'Region, Reps & Forecast', 'icon' => 'fa-chart-bar'],
            ['id' => 'sprf-section-deals', 'label' => 'Recent Deals', 'icon' => 'fa-handshake'],
        ];
    }

    protected function buildStageClasses(): array
    {
        return [
            'Proposal' => 'badge-proposal',
            'Negotiation' => 'badge-negotiation',
            'Qualification' => 'badge-qualification',
            'On-Hold' => 'badge-onhold',
        ];
    }

    protected function loadSalesPerformance(string $dateRange)
    {
        return (new \Illuminate\Database\Eloquent\Collection(
            \App\Models\SalesPerformance::where('date_range', $dateRange)->get()
        ));
    }

    protected function loadProductSales(string $dateRange, string $searchQuery)
    {
        return ProductSale::where('date_range', $dateRange)
            ->get()
            ->map(function ($sale) use ($searchQuery) {
                $sale->highlighted_name = $this->highlightMatch($sale->product_name, $searchQuery);
                return $sale;
            });
    }

    protected function loadForecastTargets(string $dateRange)
    {
        return ForecastTarget::where('date_range', $dateRange)->get();
    }

    protected function loadRegionSales(string $dateRange, string $searchQuery)
    {
        return RegionSale::where('date_range', $dateRange)
            ->get()
            ->map(function ($region) use ($searchQuery) {
                $region->highlighted_name = $this->highlightMatch($region->region_name, $searchQuery);
                return $region;
            });
    }

    protected function loadRepSales(string $dateRange, string $searchQuery)
    {
        return RepSale::where('date_range', $dateRange)
            ->get()
            ->map(function ($rep) use ($searchQuery) {
                $rep->highlighted_name = $this->highlightMatch($rep->rep_name, $searchQuery);
                $rep->progress_width = $this->getProgressWidth($rep->vs_target);
                return $rep;
            });
    }

    protected function loadRecentDeals(string $dateRange, string $searchQuery)
    {
        $stageClasses = $this->buildStageClasses();

        return Deal::where('date_range', $dateRange)
            ->where('is_ongoing', true)
            ->get()
            ->map(function ($deal) use ($searchQuery, $stageClasses) {
                $deal->highlighted_name = $this->highlightMatch($deal->name, $searchQuery);
                $deal->highlighted_customer = $this->highlightMatch($deal->customer, $searchQuery);
                $deal->stage_class = $stageClasses[$deal->stage] ?? '';
                return $deal;
            });
    }

    protected function highlightMatch(string $text, string $query): string
    {
        if (trim($query) === '') {
            return e($text);
        }

        $escapedQuery = preg_quote($query, '/');
        return preg_replace('/(' . $escapedQuery . ')/i', '<span class="text-blue-600">$1</span>', e($text));
    }

    protected function getProgressWidth(string $targetText): float
    {
        return min(100, max(0, floatval(str_replace('%', '', $targetText))));
    }
}
