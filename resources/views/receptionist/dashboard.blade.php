@extends('layouts.receptionist.app')

@section('title', 'Dashboard - Reception')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception Desk</span>
            <h1>Dashboard</h1>
            <p class="text-muted mb-0">Chào mừng trở lại! Đây là tổng quan hoạt động hôm nay.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Đặt lịch mới
            </a>
            <a href="{{ route('receptionist.orders.create') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt"></i> Lập hoá đơn
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Lịch hẹn hôm nay</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-check"></i>
                    </span>
                </div>
                <div class="metric-value">{{ $todayAppointments }}</div>
                <div class="metric-meta">Tổng số lịch trong ngày</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Chờ xử lý</span>
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
                    <span class="metric-label">Khách hàng</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-people"></i>
                    </span>
                </div>
                <div class="metric-value">{{ $totalCustomers }}</div>
                <div class="metric-meta">Tổng số khách trong hệ thống</div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Doanh thu hôm nay</span>
                    <span class="metric-icon d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-cash-coin"></i>
                    </span>
                </div>
                <div class="metric-value">{{ number_format($todayRevenue, 0, ',', '.') }}đ</div>
                <div class="metric-meta">Hoá đơn đã thanh toán</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-8">
            <div class="panel h-100">
                <div class="panel-header">
                    <div>
                        <h5 class="mb-0">Lịch hẹn sắp tới hôm nay</h5>
                        <p class="text-muted mb-0">Xác nhận, check-in hoặc hoàn tất trực tiếp từ danh sách.</p>
                    </div>
                    <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>

                @if ($upcomingAppointments->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">
                        Không có lịch hẹn nào sắp tới trong hôm nay.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Giờ</th>
                                    <th>Khách hàng</th>
                                    <th>Dịch vụ</th>
                                    <th>Trạng thái</th>
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
                                            {{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }}
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
                        <h5 class="mb-0">Thông báo</h5>
                        <p class="text-muted mb-0">{{ $unreadNotifications }} thông báo chưa đọc</p>
                    </div>
                    <a href="{{ route('receptionist.notifications.index') }}" class="btn btn-sm btn-light">Xem tất cả</a>
                </div>

                @if ($latestNotifications->isEmpty())
                    <div class="blank-panel blank-state text-center py-4 text-muted">
                        Chưa có thông báo nào.
                    </div>
                @else
                    <div class="info-list">
                        @foreach ($latestNotifications as $notification)
                            <div>
                                <span>
                                    <strong class="d-block text-body">{{ $notification->title ?? 'Thông báo' }}</strong>
                                    {{ \Illuminate\Support\Str::limit($notification->message, 60) }}
                                </span>
                                @if (!$notification->is_read)
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
