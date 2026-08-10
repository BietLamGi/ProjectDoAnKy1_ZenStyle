@extends('layouts.receptionist.app')

@section('title', 'Chi tiết lịch hẹn')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Lịch hẹn #{{ $appointment->AppointmentID }}</h1>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.edit', $appointment) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Sửa
            </a>
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="panel">
        <div class="info-list">
            <div><span>Khách hàng</span><strong>{{ $appointment->customer->FullName ?? 'N/A' }}</strong></div>
            <div><span>Điện thoại</span><strong>{{ $appointment->customer->Phone ?? '—' }}</strong></div>
            <div><span>Ngày giờ</span><strong>{{ $appointment->AppointmentDate }} {{ \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i') }}</strong></div>
            <div><span>Trạng thái</span><strong>{{ $appointment->Status }}</strong></div>
            <div><span>Dịch vụ</span><strong>{{ $appointment->services->pluck('service.ServiceName')->filter()->join(', ') ?: '—' }}</strong></div>
            <div><span>Ghi chú</span><strong>{{ $appointment->Notes ?: '—' }}</strong></div>
        </div>
    </div>

</div>
@endsection
