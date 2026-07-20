<?php

namespace App\Services;

use App\Models\Deal;
use App\Models\KpiStat;
use App\Models\SalesOrder;
use Illuminate\Support\Collection;

class DashboardService
{
    public function prepareOverviewData(): array
    {
        $sprfKpis = $this->loadSprfKpis();
        $recentDeals = $this->loadRecentDeals();
        $somOrders = $this->loadSomOrders();
        $currentDate = now()->format('F j, Y');

        return [
            'sprfKpis' => $sprfKpis,
            'recentDeals' => $recentDeals,
            'somOrders' => $somOrders,
            'currentDate' => $currentDate,
        ];
    }

    protected function loadSprfKpis()
    {
        try {
            return KpiStat::where('date_range', 'May 1 - May 31, 2026')->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function loadRecentDeals(): Collection
    {
        try {
            return Deal::where('is_ongoing', true)
                ->take(5)
                ->get()
                ->map(function ($deal) {
                    $deal->stage_style = $this->getStageStyle($deal->stage);
                    return $deal;
                });
        } catch (\Exception $e) {
            return collect();
        }
    }

    protected function loadSomOrders(): int
    {
        try {
            return SalesOrder::count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    protected function getStageStyle(string $stage): string
    {
        $styles = [
            'Proposal' => 'background:#dbe6ff; color:#3355bb;',
            'Negotiation' => 'background:#fff9c4; color:#a67c00;',
            'Qualification' => 'background:#fde2f0; color:#b0447a;',
            'On-Hold' => 'background:#fee2e2; color:#dc2626;',
        ];

        return $styles[$stage] ?? 'background:#f3f4f6; color:#374151;';
    }
}
