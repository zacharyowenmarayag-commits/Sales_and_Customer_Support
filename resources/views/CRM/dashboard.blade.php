@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Dashboard')

@push('styles')
<style>
    * { font-family: 'Inter', sans-serif; }

    .crm-dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .crm-dash-header h1 {
        font-size: 30px;
        font-weight: 700;
        color: #111827;
    }

    .crm-dash-header p {
        color: #6b7280;
        margin-top: 4px;
        font-size: 14px;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .card {
        background: #white;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .card p {
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
    }

    .card h2 {
        margin: 12px 0;
        font-size: 32px;
        font-weight: 700;
        color: #111827;
    }

    .green-text {
        color: #3f8d2f;
        font-weight: 600;
        font-size: 14px;
    }

    .red-text {
        color: #ef6b6b;
        font-weight: 600;
        font-size: 14px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }

    .graph-card, .task-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .graph-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .graph-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    .graph-header p {
        font-size: 14px;
        color: #6b7280;
    }

    .status {
        color: #3f8d2f;
        font-weight: 600;
    }

    .small {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .graph {
        height: 260px;
        display: flex;
        justify-content: space-around;
        align-items: flex-end;
        gap: 15px;
        padding-top: 20px;
        border-bottom: 2px solid #f3f4f6;
    }

    .graph-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
        height: 100%;
        flex: 1;
    }

    .graph-item .bar {
        width: 44px;
        background: #3f8d2f;
        border-radius: 6px 6px 0 0;
        transition: .3s;
    }

    .graph-item .bar:hover {
        background: #337326;
        transform: translateY(-5px);
    }

    .graph-item span {
        margin-top: 10px;
        font-size: 13px;
        color: #6b7280;
    }

    .task-card h2 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
    }

    .task {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot.green { background: #3f8d2f; }
    .dot.orange { background: #f59e0b; }
    .dot.lightorange { background: #fbbf24; }
    .dot.gray { background: #9ca3af; }

    .task h4 {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }

    .task p {
        font-size: 12px;
        color: #6b7280;
    }

    .task-btn {
        width: 100%;
        border: none;
        background: #3f8d2f;
        color: #fff;
        padding: 12px;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
        font-weight: 500;
        font-size: 14px;
        transition: .2s;
    }

    .task-btn:hover {
        background: #337326;
    }

    /* All Tasks View */
    .filters {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-btn {
        border: 1px solid #e5e7eb;
        background: #ffffff;
        color: #374151;
        padding: 8px 18px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: .2s;
    }

    .filter-btn.active {
        background: #3f8d2f;
        color: #fff;
        border-color: #3f8d2f;
    }

    .task-list {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 25px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .task-row {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .task-row:last-child {
        border-bottom: none;
    }

    .task-row .task-info {
        flex: 1;
    }

    .task-row .task-info h4 {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .task-row .task-info p {
        color: #6b7280;
        font-size: 13px;
    }

    .task-time {
        color: #374151;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
    }

    .back-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        color: #4b5563;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: .2s;
    }

    .back-btn:hover {
        background: #3f8d2f;
        color: #fff;
        border-color: #3f8d2f;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    @media (max-width: 1024px) {
        .cards { grid-template-columns: repeat(2, 1fr); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .cards { grid-template-columns: 1fr; }
        .graph { height: 220px; }
        .graph-item .bar { width: 34px; }
    }
</style>
@endpush

@section('content')
<div id="dashboard-view">
    <div class="crm-dash-header">
        <div>
            <h1>Customer Relationship Management (CRM)</h1>
            <p>Maintains comprehensive customer data and supports relationship-building activities to improve satisfaction and retention.</p>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <p>Total Customers</p>
            <h2>{{ number_format($customersCount) }}</h2>
            <span class="green-text">+12.5%</span>
        </div>
        <div class="card">
            <p>Active Deals</p>
            <h2>{{ number_format($activeDeals) }}</h2>
            <span class="green-text">+3.2%</span>
        </div>
        <div class="card">
            <p>Revenue (YTD)</p>
            <h2>₱{{ number_format($revenueYTD, 0) }}</h2>
            <span class="green-text">+18%</span>
        </div>
        <div class="card">
            <p>Churn Rate</p>
            <h2>{{ $churnRate }}%</h2>
            <span class="red-text">-0.5%</span>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="graph-card">
            <div class="graph-header">
                <div>
                    <h2>Revenue Growth</h2>
                    <p>Status: <span class="status">Growing (+16.3%)</span></p>
                    <p class="small">Revenue trend over the last 6 months</p>
                </div>
            </div>
            <div class="graph">
                <div class="graph-item"><div class="bar" style="height:110px;"></div><span>Jan</span></div>
                <div class="graph-item"><div class="bar" style="height:125px;"></div><span>Feb</span></div>
                <div class="graph-item"><div class="bar" style="height:105px;"></div><span>Mar</span></div>
                <div class="graph-item"><div class="bar" style="height:145px;"></div><span>Apr</span></div>
                <div class="graph-item"><div class="bar" style="height:190px;"></div><span>May</span></div>
                <div class="graph-item"><div class="bar" style="height:220px;"></div><span>Jun</span></div>
            </div>
        </section>

        <section class="task-card">
            <h2>Upcoming Tasks</h2>
            @forelse($upcomingTasks as $index => $task)
                @php
                    $dots = ['green', 'orange', 'lightorange', 'gray'];
                    $dot = $dots[$index % 4];
                    $dueStr = '';
                    if ($task->due_date) {
                        if ($task->due_date->isToday()) {
                            $dueStr = $task->due_date->format('g:i A');
                        } elseif ($task->due_date->isTomorrow()) {
                            $dueStr = 'Tomorrow';
                        } else {
                            $dueStr = $task->due_date->format('M j, g:i A');
                        }
                    }
                @endphp
                <div class="task">
                    <span class="dot {{ $dot }}"></span>
                    <div>
                        <h4>{{ $task->task }}</h4>
                        <p>{{ $dueStr }} ({{ $task->customer }})</p>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500 py-4">No upcoming tasks.</p>
            @endforelse
            <button class="task-btn" onclick="showTasksView()">View All Tasks</button>
        </section>
    </div>
</div>

<div id="tasks-view" style="display: none;">
    <div class="crm-dash-header">
        <div class="header-left">
            <button class="back-btn" onclick="showDashboardView()"><i class="fa-solid fa-arrow-left"></i></button>
            <div>
                <h1>All Tasks</h1>
                <p>Everything on the schedule, in one place.</p>
            </div>
        </div>
    </div>

    <div class="filters">
        <button class="filter-btn active" onclick="filterTasks('all', this)">All</button>
        <button class="filter-btn" onclick="filterTasks('today', this)">Today</button>
        <button class="filter-btn" onclick="filterTasks('upcoming', this)">Upcoming</button>
        <button class="filter-btn" onclick="filterTasks('completed', this)">Completed</button>
    </div>

    <div class="task-list">
        @forelse($allTasks as $task)
            @php
                $statusVal = 'upcoming';
                $dueStr = '—';
                if ($task->due_date) {
                    if ($task->due_date->isToday()) {
                        $statusVal = 'today';
                        $dueStr = $task->due_date->format('g:i A');
                    } elseif ($task->due_date->isTomorrow()) {
                        $statusVal = 'upcoming';
                        $dueStr = 'Tomorrow';
                    } else {
                        $statusVal = $task->due_date->isPast() ? 'completed' : 'upcoming';
                        $dueStr = $task->due_date->format('M j, g:i A');
                    }
                }
                if ($task->status === 'Completed') {
                    $statusVal = 'completed';
                    $dueStr = 'Done';
                }
                
                $dot = 'gray';
                if ($statusVal === 'completed') {
                    $dot = 'green';
                } elseif ($statusVal === 'today') {
                    $dot = 'orange';
                }
            @endphp
            <div class="task-row" data-status="{{ $statusVal }}">
                <span class="dot {{ $dot }}"></span>
                <div class="task-info">
                    <h4>{{ $task->task }}</h4>
                    <p>Customer: {{ $task->customer }}</p>
                </div>
                <div class="task-time">{{ $dueStr }}</div>
            </div>
        @empty
            <p class="text-sm text-gray-500 py-8 text-center">No tasks found.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showTasksView() {
        document.getElementById('dashboard-view').style.display = 'none';
        document.getElementById('tasks-view').style.display = 'block';
    }

    function showDashboardView() {
        document.getElementById('dashboard-view').style.display = 'block';
        document.getElementById('tasks-view').style.display = 'none';
    }

    function filterTasks(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const rows = document.querySelectorAll('.task-row');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = 'flex';
            } else {
                if (row.getAttribute('data-status') === status) {
                    row.style.display = 'flex';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }
</script>
@endpush