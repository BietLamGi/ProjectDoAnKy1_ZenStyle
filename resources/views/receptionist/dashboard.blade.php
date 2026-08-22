@extends('layouts.receptionist.app')

@section('title', 'Dashboard - Reception')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception Desk</span>
            <h1>Dashboard</h1>
            <p class="text-muted mb-0">Welcome back! Here is today's activity overview.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New appointment
            </a>
            <a href="{{ route('receptionist.invoices.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt"></i> Create invoice
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Today's appointments</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                </div>
                <div class="metric-value">{{ $todayAppointments }}</div>
                <div class="metric-meta">Total appointments today</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Pending</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
                <div class="metric-value">{{ $pendingAppointments }}</div>
                <div class="metric-meta">Pending / Confirmed</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Customers</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                    </span>
                </div>
                <div class="metric-value">{{ $totalCustomers }}</div>
                <div class="metric-meta">Total customers in system</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Today's Revenue</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                    </span>
                </div>
                <div class="metric-value">{{ number_format($todayRevenue, 0, ',', '.') }}đ</div>
                <div class="metric-meta">Paid invoices</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        @if ($unconfirmedInvoices > 0)
            <div class="col-12">
                <div class="alert alert-warning d-flex justify-content-between align-items-center mb-0">
                    <span>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ $unconfirmedInvoices }} invoice(s) have no payment method recorded - please confirm.
                    </span>
                    <a href="{{ route('receptionist.invoices.index', ['unconfirmed' => 1]) }}" class="btn btn-sm btn-outline-dark">
                        Review
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="mb-0">Upcoming appointments today</h5>
                        <p class="text-muted mb-0">Confirm, check in, or complete directly from the list.</p>
                    </div>
                    <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-sm btn-light">View all</a>
                </div>

                @if ($upcomingAppointments->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">
                        No upcoming appointments for today.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i') }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $appointment->customer->FullName ?? 'N/A' }}</div>
                                            <div class="text-muted small">{{ $appointment->customer->Phone ?? '' }}</div>
                                        </td>
                                        <td>
                                            <!-- {{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }} -->
                                        </td>
                                        <td>
                                            <span class="badge text-bg-{{ $appointment->Status === 'Completed' ? 'success' : ($appointment->Status === 'Cancelled' ? 'danger' : ($appointment->Status === 'CheckedIn' ? 'info' : 'warning')) }}">
                                                {{ $appointment->Status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="mb-0">Notifications</h5>
                        <p class="text-muted mb-0">{{ $unreadNotifications }} unread notifications</p>
                    </div>
                    <a href="{{ route('receptionist.notifications.index') }}" class="btn btn-sm btn-light">View all</a>
                </div>

                @if ($latestNotifications->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">
                        No notifications yet.
                    </div>
                @else
                    <div class="info-list">
                        @foreach ($latestNotifications as $notification)
                            <div>
                                <span>
                                    <strong class="d-block text-body">{{ $notification->Title ?? 'Notification' }}</strong>
                                    {{ \Illuminate\Support\Str::limit($notification->Message, 60) }}
                                </span>
                                @if (!$notification->IsRead)
                                    <span class="status-dot"></span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
