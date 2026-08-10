@extends('layouts.receptionist.app')

@section('title', 'Sửa lịch hẹn')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Lịch hẹn #{{ $appointment->AppointmentID }}</h1>
            <p class="text-muted mb-0">
                {{ $appointment->customer->FullName ?? 'N/A' }} &middot; {{ $appointment->customer->Phone ?? '' }}
            </p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel">
                <form method="POST" action="{{ route('receptionist.appointments.update', $appointment) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ngày hẹn</label>
                            <input type="date" name="AppointmentDate" class="form-control" value="{{ old('AppointmentDate', $appointment->AppointmentDate) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giờ bắt đầu</label>
                            <input type="time" name="StartTime" class="form-control" value="{{ old('StartTime', \Illuminate\Support\Carbon::parse($appointment->StartTime)->format('H:i')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="Status" class="form-control">
                                @foreach ($statuses as $s)
                                    <option value="{{ $s }}" @selected(old('Status', $appointment->Status) === $s)>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="Notes" class="form-control" rows="3">{{ old('Notes', $appointment->Notes) }}</textarea>
                        </div>
                    </div>

                    <div class="heading-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Cập nhật
                        </button>
                        <a href="{{ route('receptionist.orders.create', ['appointment_id' => $appointment->AppointmentID]) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-receipt"></i> Lập hoá đơn
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Dịch vụ đã đặt</h5></div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead><tr><th>Dịch vụ</th><th>SL</th><th>Đơn giá</th></tr></thead>
                        <tbody>
                            @foreach ($appointment->services as $line)
                                <tr>
                                    <td>{{ $line->service->ServiceName ?? '—' }}</td>
                                    <td>{{ $line->Quantity }}</td>
                                    <td>{{ number_format($line->UnitPrice, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form action="{{ route('receptionist.appointments.destroy', $appointment) }}" method="POST" class="mt-2" onsubmit="return confirm('Huỷ lịch hẹn này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> Huỷ lịch hẹn
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
