@extends('layouts.app')

@section('title', 'AmbatuGrow - Overview Dashboard')

@push('styles')
<style>
:root {
    --dash-green: #15803d;
    --dash-green-light: #f0fdf4;
    --dash-border: #e5e7eb;
}

.dash-page {
    padding: 28px 0;
}

/* Hero greeting */
.dash-hero {
    background: linear-gradient(135deg, #15803d 0%, #166534 60%, #14532d 100%);
    border-radius: 8px;
    padding: 36px 40px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 28px;
}
.dash-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
.dash-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; right: 100px;
    width: 140px; height: 140px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.dash-hero-title {
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -0.3px;
    margin-bottom: 6px;
}
.dash-hero-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.75);
    font-weight: 400;
}
.dash-hero-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 20px;
}
.dash-hero-pill {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 100px;
    padding: 4px 14px;
    font-size: 11px;
    font-weight: 700;
    color: white;
    backdrop-filter: blur(6px);
}

/* Section headings */
.dash-section-title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #6b7280;
    margin-bottom: 14px;
}

/* Module Cards */
.module-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}
@media (max-width: 900px) {
    .module-grid { grid-template-columns: 1fr; }
}

.module-card {
    background: #fffefb;
    border: 1px solid #e8e0d0;
    border-radius: 8px;
    padding: 24px 26px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: all 0.2s ease;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}
.module-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 8px 8px 0 0;
    background: var(--card-accent, #15803d);
}
.module-card:hover {
    box-shadow: 0 8px 24px -4px rgba(0,0,0,0.10);
    transform: translateY(-2px);
    border-color: #d1c7b0;
}

.module-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.module-card-icon {
    width: 42px; height: 42px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px;
    background: var(--card-icon-bg, #f0fdf4);
    color: var(--card-accent, #15803d);
    flex-shrink: 0;
}
.module-card-arrow {
    color: #9ca3af;
    font-size: 11px;
    transition: transform 0.2s;
}
.module-card:hover .module-card-arrow {
    transform: translateX(3px);
    color: var(--card-accent, #15803d);
}
.module-card-name {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #9ca3af;
    margin-bottom: 2px;
}
.module-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #111827;
}
.module-card-desc {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.6;
}

/* Stats row inside each card */
.module-stats {
    display: flex;
    gap: 16px;
    padding-top: 14px;
    border-top: 1px solid #f3f0e8;
    flex-wrap: wrap;
}
.module-stat {
    display: flex;
    flex-direction: column;
}
.module-stat-value {
    font-size: 20px;
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
}
.module-stat-label {
    font-size: 10px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 2px;
}

/* Recent Deals Table */
.dash-table-card {
    background: #fffefb;
    border: 1px solid #e8e0d0;
    border-radius: 8px;
    overflow: hidden;
}
.dash-table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px 16px;
    border-bottom: 1px solid #f3f0e8;
}
.dash-table-header h3 {
    font-size: 15px;
    font-weight: 800;
    color: #111827;
}
.dash-view-all {
    font-size: 12px;
    font-weight: 700;
    color: #15803d;
    text-decoration: none;
    transition: opacity 0.15s;
}
.dash-view-all:hover { opacity: 0.75; }

.dash-table {
    width: 100%;
    text-align: left;
    border-collapse: collapse;
}
.dash-table th {
    padding: 10px 16px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #9ca3af;
    background: #fafaf8;
    border-bottom: 1px solid #f3f0e8;
}
.dash-table td {
    padding: 13px 16px;
    font-size: 12px;
    color: #374151;
    border-bottom: 1px solid #f9f7f2;
}
.dash-table tr:last-child td { border-bottom: none; }
.dash-table tr:hover td { background: #fffcf4; }

.stage-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
}
</style>
@endpush

@section('content')
<div class="dash-page">

    <!-- Hero -->
    <div class="dash-hero">
        <div class="dash-hero-title">Welcome back to AmbatuGrow 👋</div>
        <div class="dash-hero-sub">Here's a live overview of all your business modules and key metrics.</div>
        <div class="dash-hero-meta">
            <span class="dash-hero-pill"><i class="fas fa-circle-dot mr-1" style="font-size:8px;"></i> Live</span>
            <span class="dash-hero-pill">{{ now()->format('F j, Y') }}</span>
        </div>
    </div>

    <!-- Module Overview Grid -->
    <div class="dash-section-title">Module Overview</div>
    <div class="module-grid">

        <!-- ASSCM -->
        <a href="{{ route('asscm') }}" class="module-card" style="--card-accent:#15803d; --card-icon-bg:#f0fdf4;">
            <div class="module-card-header">
                <div class="module-card-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <i class="fas fa-arrow-right module-card-arrow"></i>
            </div>
            <div>
                <div class="module-card-name">ASSCM</div>
                <div class="module-card-title">After Sales Support</div>
                <div class="module-card-desc">Track service cases, manage resolution timelines, and measure customer satisfaction.</div>
            </div>
            <div class="module-stats">
                <div class="module-stat">
                    <span class="module-stat-value" style="color:#15803d;">8</span>
                    <span class="module-stat-label">Active Tickets</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">3</span>
                    <span class="module-stat-label">Overdue</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">4.7/5</span>
                    <span class="module-stat-label">Satisfaction</span>
                </div>
            </div>
        </a>

        <!-- SPRF -->
        <a href="{{ route('sprf') }}" class="module-card" style="--card-accent:#3aa0c9; --card-icon-bg:#f0f9ff;">
            <div class="module-card-header">
                <div class="module-card-icon" style="background:#f0f9ff; color:#3aa0c9;">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <i class="fas fa-arrow-right module-card-arrow"></i>
            </div>
            <div>
                <div class="module-card-name">SPRF</div>
                <div class="module-card-title">Sales Performance & Forecasting</div>
                <div class="module-card-desc">Monitor sales rep performance, regional breakdowns, and revenue forecasts.</div>
            </div>
            <div class="module-stats">
                @if($sprfKpis)
                <div class="module-stat">
                    <span class="module-stat-value" style="color:#3aa0c9;">{{ $sprfKpis->total_revenue ?? '₱0' }}</span>
                    <span class="module-stat-label">Total Revenue</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">{{ $sprfKpis->achievement_rate ?? '0%' }}</span>
                    <span class="module-stat-label">Achievement</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">{{ $sprfKpis->deals_closed ?? '0' }}</span>
                    <span class="module-stat-label">Deals Closed</span>
                </div>
                @else
                <div class="module-stat">
                    <span class="module-stat-value" style="color:#3aa0c9;">—</span>
                    <span class="module-stat-label">No data</span>
                </div>
                @endif
            </div>
        </a>

        <!-- SOM -->
        <a href="{{ route('som') }}" class="module-card" style="--card-accent:#a67c00; --card-icon-bg:#fffbeb;">
            <div class="module-card-header">
                <div class="module-card-icon" style="background:#fffbeb; color:#a67c00;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <i class="fas fa-arrow-right module-card-arrow"></i>
            </div>
            <div>
                <div class="module-card-name">SOM</div>
                <div class="module-card-title">Sales Order Management</div>
                <div class="module-card-desc">Manage and track customer orders from processing through delivery.</div>
            </div>
            <div class="module-stats">
                <div class="module-stat">
                    <span class="module-stat-value" style="color:#a67c00;">{{ $somOrders }}</span>
                    <span class="module-stat-label">Total Orders</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">2</span>
                    <span class="module-stat-label">Pending</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">1</span>
                    <span class="module-stat-label">Delivered</span>
                </div>
            </div>
        </a>

        <!-- CRM -->
        <a href="{{ route('crm.dashboard') }}" class="module-card" style="--card-accent:#b0447a; --card-icon-bg:#fdf2f8;">
            <div class="module-card-header">
                <div class="module-card-icon" style="background:#fdf2f8; color:#b0447a;">
                    <i class="fas fa-handshake"></i>
                </div>
                <i class="fas fa-arrow-right module-card-arrow"></i>
            </div>
            <div>
                <div class="module-card-name">CRM</div>
                <div class="module-card-title">Customer Relationship Management</div>
                <div class="module-card-desc">Manage customers, communication logs, follow-ups, and segmentation.</div>
            </div>
            <div class="module-stats">
                <div class="module-stat">
                    <span class="module-stat-value" style="color:#b0447a;">—</span>
                    <span class="module-stat-label">Customers</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">—</span>
                    <span class="module-stat-label">Follow-Ups</span>
                </div>
                <div class="module-stat">
                    <span class="module-stat-value">—</span>
                    <span class="module-stat-label">Open Logs</span>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Ongoing Deals -->
    <div class="dash-section-title">Recent Ongoing Deals</div>
    <div class="dash-table-card">
        <div class="dash-table-header">
            <h3>Ongoing Deals</h3>
            <a href="{{ route('sprf.deals') }}" class="dash-view-all">View all <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Deal Name</th>
                        <th>Customer</th>
                        <th>Stage</th>
                        <th>Value</th>
                        <th>Expected Close</th>
                        <th>Owner</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentDeals as $deal)
                    @php
                        $stageColors = [
                            'Proposal'      => 'background:#dbe6ff; color:#3355bb;',
                            'Negotiation'   => 'background:#fff9c4; color:#a67c00;',
                            'Qualification' => 'background:#fde2f0; color:#b0447a;',
                            'On-Hold'       => 'background:#fee2e2; color:#dc2626;',
                        ];
                        $stageStyle = $stageColors[$deal->stage] ?? 'background:#f3f4f6; color:#374151;';
                    @endphp
                    <tr>
                        <td class="font-semibold text-gray-900">{{ $deal->name }}</td>
                        <td>{{ $deal->customer }}</td>
                        <td>
                            <span class="stage-badge" style="{{ $stageStyle }}">{{ $deal->stage }}</span>
                        </td>
                        <td class="font-bold text-gray-900">{{ $deal->value }}</td>
                        <td class="text-gray-500">{{ $deal->expected_close }}</td>
                        <td class="font-semibold">{{ $deal->owner }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-400 font-semibold">No ongoing deals found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
