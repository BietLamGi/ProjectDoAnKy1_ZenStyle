@extends('layouts.receptionist.app')

@section('title', 'Đặt lịch mới')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Đặt lịch tại quầy</h1>
            <p class="text-muted mb-0">Dùng cho khách walk-in hoặc đặt lịch qua điện thoại.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.appointments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.appointments.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Khách hàng đã có (tuỳ chọn)</label>
                    <select name="customer_id" class="form-control" id="customerSelect">
                        <option value="">-- Khách hàng mới --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->CustomerID }}" @selected(old('customer_id', request('customer_id')) == $customer->CustomerID)>
                                {{ $customer->FullName }} - {{ $customer->Phone }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6"></div>

                <div class="col-md-6">
                    <label class="form-label">Họ tên khách mới</label>
                    <input type="text" name="fullname" class="form-control" value="{{ old('fullname') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Dịch vụ <span class="text-danger">*</span></label>
                    <select name="service_id" class="form-control" required>
                        <option value="">-- Chọn dịch vụ --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->ServiceID }}" @selected(old('service_id') == $service->ServiceID)>
                                {{ $service->Category }} - {{ $service->ServiceName }} ({{ number_format($service->Price, 0, ',', '.') }}đ, {{ $service->DurationMinutes }} phút)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày hẹn <span class="text-danger">*</span></label>
                    <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Giờ hẹn <span class="text-danger">*</span></label>
                    <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Tạo lịch hẹn
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
