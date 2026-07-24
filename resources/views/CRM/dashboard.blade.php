@extends('layouts.app')

@section('title', 'AmbatuGrow - CRM Dashboard')

@push('styles')
<style>
    * { font-family: 'Inter', sans-serif; }

    .crm-dash-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .crm-dash-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.015em;
    }

    .crm-dash-header p {
        color: #6b7280;
        margin-top: 2px;
        font-size: 0.75rem;
    }

    .cards {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .card {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 18px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: transform 0.15s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .card.card-customers { border-top: 4px solid #16a34a !important; }
    .card.card-deals { border-top: 4px solid #0284c7 !important; }
    .card.card-revenue { border-top: 4px solid #8b5cf6 !important; }
    .card.card-churn { border-top: 4px solid #f59e0b !important; }

    .card p {
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .card h2 {
        margin: 4px 0 6px 0;
        font-size: 22px;
        font-weight: 800;
        color: #111827;
    }

    .green-text {
        color: #16a34a;
        font-weight: 700;
        font-size: 11px;
    }

    .red-text {
        color: #ef4444;
        font-weight: 700;
        font-size: 11px;
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
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-btn {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 6px;
        border: 1px solid;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, color .15s;
        /* default = All (gray) inactive */
        background: #fff;
        color: #4b5563;
        border-color: #e5e7eb;
    }
    .filter-btn:hover { background: #f9fafb; }

    /* All — gray */
    .filter-btn[data-filter="all"]               { border-color: #e5e7eb; background: #fff;       color: #4b5563; }
    .filter-btn[data-filter="all"]:hover         { background: #f9fafb; }
    .filter-btn[data-filter="all"].active        { background: #374151; color: #fff; border-color: #374151; }

    /* Today — blue */
    .filter-btn[data-filter="today"]             { border-color: #bfdbfe; background: #eff6ff; color: #2563eb; }
    .filter-btn[data-filter="today"]:hover       { background: #dbeafe; }
    .filter-btn[data-filter="today"].active      { background: #2563eb; color: #fff; border-color: #2563eb; }

    /* Upcoming — amber */
    .filter-btn[data-filter="upcoming"]          { border-color: #fde68a; background: #fffbeb; color: #d97706; }
    .filter-btn[data-filter="upcoming"]:hover    { background: #fef3c7; }
    .filter-btn[data-filter="upcoming"].active   { background: #d97706; color: #fff; border-color: #d97706; }

    /* Completed — emerald */
    .filter-btn[data-filter="completed"]         { border-color: #a7f3d0; background: #ecfdf5; color: #059669; }
    .filter-btn[data-filter="completed"]:hover   { background: #d1fae5; }
    .filter-btn[data-filter="completed"].active  { background: #059669; color: #fff; border-color: #059669; }

    .task-list {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 4px 20px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .task-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .task-row:last-child {
        border-bottom: none;
    }

    .task-row .task-info {
        flex: 1;
    }

    .task-row .task-info h4 {
        font-size: 0.8rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 2px;
    }

    .task-row .task-info p {
        color: #6b7280;
        font-size: 0.7rem;
    }

    .task-time {
        color: #374151;
        font-size: 0.7rem;
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

    .task-card-wrapper {
        border-bottom: 1px solid #f3f4f6;
    }
    .task-card-wrapper:last-child {
        border-bottom: none;
    }
    .task-row-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 0;
        cursor: pointer;
        user-select: none;
    }
    .task-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 1;
    }
    .task-header-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .toggle-icon {
        color: #9ca3af;
        transition: transform 0.2s;
        font-size: 14px;
    }
    .task-card-wrapper.expanded .toggle-icon {
        transform: rotate(90deg);
    }
    
    /* Collapsible Wrapper */
    .task-details-wrapper {
        background-color: #f2f8f0; /* Soft green backdrop */
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        border: 1px solid #e1eedb;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .details-left h5, .edit-header h5 {
        font-size: 14px;
        font-weight: 700;
        color: #3f8d2f;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .description-text {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
    }

    .details-right {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .meta-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .meta-item i {
        color: #3f8d2f;
        font-size: 16px;
        margin-top: 2px;
        width: 18px;
        text-align: center;
    }

    .meta-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        margin-bottom: 2px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .priority-badge {
        display: inline-block;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 4px;
    }
    .priority-badge.high {
        background-color: #fee2e2;
        color: #dc2626;
    }
    .priority-badge.medium {
        background-color: #fef3c7;
        color: #d97706;
    }
    .priority-badge.low {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .status-text {
        font-size: 13px;
        font-weight: 700;
    }
    .status-text.completed {
        color: #16a34a;
    }
    .status-text.in-progress {
        color: #16a34a;
    }
    .status-text.pending {
        color: #ea580c;
    }
    .status-text.cancelled {
        color: #dc2626;
    }

    .details-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #e1eedb;
        padding-top: 15px;
    }

    .btn {
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 6px;
        cursor: pointer;
        transition: 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-secondary {
        background-color: #ffffff;
        color: #374151;
        border: 1px solid #d1d5db;
    }
    .btn-secondary:hover {
        background-color: #f9fafb;
    }

    .btn-primary {
        background-color: #3f8d2f;
        color: #ffffff;
    }
    .btn-primary:hover {
        background-color: #337326;
    }

    /* Completed Task Styles */
    .task-card-wrapper.is-completed .task-info h4 {
        text-decoration: line-through;
        color: #9ca3af;
    }

    .task-card-wrapper.is-completed .task-info p {
        text-decoration: line-through;
        color: #9ca3af;
    }

    .done-badge {
        background-color: #e6f4ea;
        color: #16a34a;
        font-size: 12px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-right: 8px;
    }

    .btn-completed {
        background-color: #8e8e8e;
        color: #ffffff;
        cursor: not-allowed;
        border: none;
    }

    /* Edit Form Styles */
    .edit-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        border-bottom: 1px solid #e1eedb;
        padding-bottom: 15px;
    }
    .edit-header i {
        font-size: 20px;
        color: #3f8d2f;
    }
    .edit-header p {
        font-size: 12px;
        color: #6b7280;
        margin: 2px 0 0 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    @media (max-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .form-group.full-width {
        grid-column: span 2;
    }
    @media (max-width: 640px) {
        .form-group.full-width {
            grid-column: span 1;
        }
    }

    .form-group label {
        font-size: 11px;
        font-weight: 700;
        color: #3f8d2f;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input, .form-group select, .form-group textarea {
        padding: 10px 12px;
        font-size: 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        background-color: #ffffff;
        color: #374151;
        outline: none;
        transition: border-color 0.2s;
    }

    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #3f8d2f;
    }

    .edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
        border-top: 1px solid #e1eedb;
        padding-top: 15px;
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
        <div class="card card-customers">
            <p>Total Customers</p>
            <h2>{{ number_format($customersCount) }}</h2>
            <span class="green-text">+12.5%</span>
        </div>
        <div class="card card-deals">
            <p>Active Deals</p>
            <h2>{{ number_format($activeDeals) }}</h2>
            <span class="green-text">+3.2%</span>
        </div>
        <div class="card card-revenue">
            <p>Revenue (YTD)</p>
            <h2>₱{{ number_format($revenueYTD, 0) }}</h2>
            <span class="green-text">+18%</span>
        </div>
        <div class="card card-churn">
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
        <button class="filter-btn active" data-filter="all" onclick="filterTasks('all', this)">All</button>
        <button class="filter-btn" data-filter="today" onclick="filterTasks('today', this)">Today</button>
        <button class="filter-btn" data-filter="upcoming" onclick="filterTasks('upcoming', this)">Upcoming</button>
        <button class="filter-btn" data-filter="completed" onclick="filterTasks('completed', this)">Completed</button>
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

                $priority = $task->priority ?? 'Medium';
                $description = $task->description ?? '';
                $createdBy = $task->created_by ?? 'Admin';
                $createdAtStr = !empty($task->created_at) ? \Carbon\Carbon::parse($task->created_at)->format('M j, Y, g:i A') : '—';
                $dueDateRawStr = $task->due_date ? $task->due_date->format('M j, Y, g:i A') : '';
            @endphp
            <div class="task-card-wrapper {{ ($task->status ?? '') === 'Completed' ? 'is-completed' : '' }}" data-status="{{ $statusVal }}" id="task-card-{{ $task->id }}">
                <!-- Task Header -->
                <div class="task-row-header" onclick="toggleTaskDetails({{ $task->id }})">
                    <div class="task-header-left">
                        <span class="dot {{ $dot }}"></span>
                        <div class="task-info">
                            <h4>{{ $task->task }}</h4>
                            <p>Customer: {{ $task->customer }}</p>
                        </div>
                    </div>
                    <div class="task-header-right">
                        @if(($task->status ?? '') === 'Completed')
                            <span class="done-badge">Done <i class="fa-solid fa-check"></i></span>
                        @endif
                        <span class="task-time">{{ $dueStr }}</span>
                        <i class="fa-solid fa-chevron-right toggle-icon" id="toggle-icon-{{ $task->id }}"></i>
                    </div>
                </div>

                <!-- Collapsible Wrapper -->
                <div class="task-details-wrapper" id="task-details-{{ $task->id }}" style="display: none;">
                    
                    <!-- View Details -->
                    <div class="task-view-mode" id="task-view-{{ $task->id }}">
                        <div class="details-grid">
                            <div class="details-left">
                                <h5>Description</h5>
                                <p class="description-text">{{ $description ?: 'No description provided.' }}</p>
                            </div>
                            <div class="details-right">
                                <div class="meta-item">
                                    <i class="fa-regular fa-calendar"></i>
                                    <div>
                                        <span class="meta-label">Due Date</span>
                                        <span class="meta-value">{{ $dueDateRawStr ?: '—' }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-regular fa-user"></i>
                                    <div>
                                        <span class="meta-label">Customer</span>
                                        <span class="meta-value">{{ $task->customer }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-regular fa-flag"></i>
                                    <div>
                                        <span class="meta-label">Priority</span>
                                        <span class="priority-badge {{ strtolower($priority) }}">{{ $priority }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-regular fa-circle-check"></i>
                                    <div>
                                        <span class="meta-label">Status</span>
                                        <span class="status-text {{ strtolower(str_replace(' ', '-', $task->status ?? 'Pending')) }}">{{ $task->status ?? 'Pending' }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-solid fa-circle-user"></i>
                                    <div>
                                        <span class="meta-label">Created By</span>
                                        <span class="meta-value">{{ $createdBy }}</span>
                                    </div>
                                </div>
                                <div class="meta-item">
                                    <i class="fa-regular fa-clock"></i>
                                    <div>
                                        <span class="meta-label">Created At</span>
                                        <span class="meta-value">{{ $createdAtStr }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="details-actions">
                            <button class="btn btn-secondary" onclick="showEditForm({{ $task->id }})">Edit</button>
                            @if(($task->status ?? 'Pending') !== 'Completed')
                                <form action="{{ route('crm.followup.update', $task->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Completed">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Mark as Done</button>
                                </form>
                            @else
                                <button class="btn btn-completed" disabled><i class="fa-solid fa-check"></i> Done</button>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <div class="task-edit-mode" id="task-edit-{{ $task->id }}" style="display: none;">
                        <div class="edit-header">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <div>
                                <h5>Edit Task</h5>
                                <p>Update the details, then save your changes.</p>
                            </div>
                        </div>
                        <form action="{{ route('crm.followup.update', $task->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="task_name_{{ $task->id }}">Task Name</label>
                                    <input type="text" name="task" id="task_name_{{ $task->id }}" value="{{ $task->task }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="customer_{{ $task->id }}">Customer</label>
                                    <input type="text" name="customer" id="customer_{{ $task->id }}" value="{{ $task->customer }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="due_date_{{ $task->id }}">Due Date</label>
                                    <input type="text" name="due_date" id="due_date_{{ $task->id }}" value="{{ $dueDateRawStr }}" placeholder="e.g. Jul 22, 2025, 1:30 PM" required>
                                </div>
                                <div class="form-group">
                                    <label for="priority_{{ $task->id }}">Priority</label>
                                    <select name="priority" id="priority_{{ $task->id }}">
                                        <option value="Low" {{ $priority === 'Low' ? 'selected' : '' }}>Low</option>
                                        <option value="Medium" {{ $priority === 'Medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="High" {{ $priority === 'High' ? 'selected' : '' }}>High</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status_{{ $task->id }}">Status</label>
                                    <select name="status" id="status_{{ $task->id }}">
                                        @foreach(\App\Models\FollowUp::STATUSES as $stat)
                                            <option value="{{ $stat }}" {{ ($task->status ?? 'Pending') === $stat ? 'selected' : '' }}>{{ $stat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group full-width" style="margin-top: 15px;">
                                <label for="description_{{ $task->id }}">Description</label>
                                <textarea name="description" id="description_{{ $task->id }}" rows="3">{{ $description }}</textarea>
                            </div>
                            <div class="edit-actions">
                                <button type="button" class="btn btn-secondary" onclick="hideEditForm({{ $task->id }})">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>

                </div>
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
        localStorage.setItem('crm_active_view', 'tasks');
    }

    function showDashboardView() {
        document.getElementById('dashboard-view').style.display = 'block';
        document.getElementById('tasks-view').style.display = 'none';
        localStorage.setItem('crm_active_view', 'dashboard');
    }

    function filterTasks(status, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const rows = document.querySelectorAll('.task-card-wrapper');
        rows.forEach(row => {
            if (status === 'all') {
                row.style.display = 'block';
            } else {
                if (row.getAttribute('data-status') === status) {
                    row.style.display = 'block';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    function toggleTaskDetails(id) {
        const wrapper = document.getElementById('task-card-' + id);
        const details = document.getElementById('task-details-' + id);
        
        if (details.style.display === 'none') {
            details.style.display = 'block';
            wrapper.classList.add('expanded');
        } else {
            details.style.display = 'none';
            wrapper.classList.remove('expanded');
        }
    }

    function showEditForm(id) {
        document.getElementById('task-view-' + id).style.display = 'none';
        document.getElementById('task-edit-' + id).style.display = 'block';
    }

    function hideEditForm(id) {
        document.getElementById('task-view-' + id).style.display = 'block';
        document.getElementById('task-edit-' + id).style.display = 'none';
    }

    window.addEventListener('DOMContentLoaded', () => {
        const activeView = localStorage.getItem('crm_active_view');
        if (activeView === 'tasks') {
            showTasksView();
        } else {
            showDashboardView();
        }
    });
</script>
@endpush