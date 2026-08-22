@extends('layouts.receptionist.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create Invoice</h1>
            <p class="text-muted mb-0">Choose an appointment and generate an invoice for the customer.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        @if ($selectedAppointment)
        {{-- Đã biết chính xác appointment nào (đến từ nút "Create
                 invoice" trên trang Appointments) - hiện thẳng thông tin,
                 không cần dropdown chọn lại. --}}
        <div class="blank-panel mb-3 d-flex justify-content-between align-items-center">
            <div>
                <div class="text-muted small">Appointment</div>
                <div class="fw-semibold">
                    #{{ $selectedAppointment->AppointmentID }} - {{ $selectedAppointment->customer->FullName ?? 'N/A' }}
                    ({{ $selectedAppointment->AppointmentDate?->format('d/m/Y') }})
                </div>
            </div>
            <a href="{{ route('receptionist.invoices.create') }}" class="btn btn-sm btn-outline-secondary">
                Change appointment
            </a>
        </div>
        @else
        {{-- Chưa có appointment nào được chọn sẵn (vào thẳng từ menu
                 Invoices) - cần chọn 1 cái để tính hoá đơn. --}}
        <div class="mb-3">
            <label class="form-label">Select appointment</label>
            <select class="form-control"
                onchange="if(this.value){ window.location='{{ route('receptionist.invoices.create') }}?appointment_id='+this.value; }">
                <option value="">-- Select appointment to calculate automatically --</option>
                @foreach ($appointments as $appointment)
                <option value="{{ $appointment->AppointmentID }}">
                    #{{ $appointment->AppointmentID }} - {{ $appointment->customer->FullName ?? 'N/A' }}
                    ({{ $appointment->AppointmentDate?->format('d/m/Y') }})
                </option>
                @endforeach
            </select>
        </div>
        @endif

        @if ($selectedAppointment)
        <div class="blank-panel mb-3">
            <div class="table-responsive">
                {{-- Mỗi dòng tự áp dụng khuyến mãi đang active của chính dịch
                     vụ/sản phẩm đó (nếu có) - không cần chọn thủ công, và
                     không giới hạn chỉ 1 khuyến mãi cho cả hoá đơn. --}}
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Service / Product</th>
                            <th>Qty</th>
                            <th>Unit price</th>
                            <th>Promotion</th>
                            <th>Unit price after discount</th>
                            <th>Line total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lines as $line)
                        <tr>
                            <td>{{ $line['service_name'] }}</td>
                            <td>{{ $line['quantity'] }}</td>
                            <td>{{ number_format($line['unit_price'], 0, ',', '.') }}đ</td>
                            <td>
                                @if ($line['promotion'])
                                <span class="badge bg-danger">
                                    {{ $line['promotion']->Title }}
                                    ({{ $line['promotion']->DiscountType === 'Percent'
                                            ? $line['promotion']->DiscountValue . '%'
                                            : number_format($line['promotion']->DiscountValue, 0, ',', '.') . 'đ' }})
                                </span>
                                @else
                                <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($line['promotion'])
                                <strong
                                    class="text-danger">{{ number_format($line['unit_final'], 0, ',', '.') }}đ</strong>
                                @else
                                {{ number_format($line['unit_final'], 0, ',', '.') }}đ
                                @endif
                            </td>
                            <td>{{ number_format($line['line_final'], 0, ',', '.') }}đ</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">This appointment has no services yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <form method="POST" action="{{ route('receptionist.invoices.store') }}">
            @csrf
            <input type="hidden" name="AppointmentID" value="{{ $selectedAppointment->AppointmentID }}">

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Payment method</label>
                    <select name="PaymentMethod" class="form-control">
                        <option value="">-- Select payment method --</option>
                        <option value="Cash" @selected(old('PaymentMethod')=='Cash' )>Cash</option>
                        <option value="Bank Transfer" @selected(old('PaymentMethod')=='Bank Transfer' )>Bank Transfer
                        </option>
                        <option value="Credit Card" @selected(old('PaymentMethod')=='Credit Card' )>Credit Card</option>
                        <option value="E-Wallet" @selected(old('PaymentMethod')=='E-Wallet' )>E-Wallet</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control" readonly
                        value="{{ number_format($totalAmount, 0, ',', '.') }}đ">
                    <div class="form-text">Calculated automatically from the appointment's booked services.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount</label>
                    <input type="text" class="form-control" readonly
                        value="{{ number_format($discountAmount, 0, ',', '.') }}đ">
                    <div class="form-text">Sum of each line's own active promotion.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Final amount</label>
                    <input type="text" class="form-control fw-bold" readonly
                        value="{{ number_format($finalAmount, 0, ',', '.') }}đ">
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create invoice
                </button>
            </div>
        </form>
        @endif
    </div>


</div>
@endsection