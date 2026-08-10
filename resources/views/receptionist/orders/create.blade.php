@extends('layouts.receptionist.app')

@section('title', 'Lập hoá đơn')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Lập hoá đơn</h1>
            <p class="text-muted mb-0">Thanh toán dịch vụ / sản phẩm cho khách hàng.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.orders.store') }}" id="orderForm">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Khách hàng <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">-- Chọn khách hàng --</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->CustomerID }}"
                                @selected(old('customer_id', $appointment->CustomerID ?? null) == $customer->CustomerID)>
                                {{ $customer->FullName }} - {{ $customer->Phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lịch hẹn liên quan</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $appointment ? '#' . $appointment->AppointmentID . ' - ' . $appointment->AppointmentDate : 'Không gắn lịch hẹn' }}">
                    <input type="hidden" name="appointment_id" value="{{ $appointment->AppointmentID ?? '' }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Phương thức thanh toán</label>
                    <select name="payment_method" class="form-control">
                        <option value="cash" @selected(old('payment_method') === 'cash')>Tiền mặt</option>
                        <option value="card" @selected(old('payment_method') === 'card')>Thẻ</option>
                        <option value="transfer" @selected(old('payment_method') === 'transfer')>Chuyển khoản</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái thanh toán</label>
                    <select name="payment_status" class="form-control">
                        <option value="paid" @selected(old('payment_status') === 'paid')>Đã thanh toán</option>
                        <option value="unpaid" @selected(old('payment_status', 'unpaid') === 'unpaid')>Chưa thanh toán</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Ghi chú</label>
                    <input type="text" name="note" class="form-control" value="{{ old('note') }}">
                </div>
            </div>

            <hr class="my-4">

            <div class="panel-header">
                <h5 class="mb-0">Dịch vụ / sản phẩm</h5>
                <button type="button" class="btn btn-sm btn-light" id="addLineBtn">
                    <i class="bi bi-plus-lg"></i> Thêm dòng
                </button>
            </div>

            <div class="table-responsive">
                <table class="table align-middle" id="itemsTable">
                    <thead>
                        <tr>
                            <th style="min-width: 260px;">Dịch vụ / sản phẩm</th>
                            <th style="width: 120px;">Số lượng</th>
                            <th style="width: 150px;">Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        {{-- các dòng sẽ được thêm bởi JS, mặc định 1 dòng --}}
                    </tbody>
                </table>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Lưu hoá đơn
                </button>
            </div>
        </form>
    </div>

</div>

{{-- Template dữ liệu dịch vụ dùng cho JS (JSON, không phải HTML) --}}
<script id="servicesData" type="application/json">
    @php
        echo $services->map(function ($s) {
            return [
                'id' => $s->ServiceID,
                'label' => $s->Category . ' - ' . $s->ServiceName . ($s->ServiceType == 1 ? ' (SP)' : ' (DV)'),
                'price' => (float) $s->Price,
            ];
        })->values()->toJson();
    @endphp
</script>

@php
    $prefillAppointmentServices = $appointment ? $appointment->services->map(function ($line) {
        return [
            'service_id' => $line->ServiceID,
            'quantity' => $line->Quantity,
        ];
    })->values() : collect();
@endphp

<script id="prefillData" type="application/json">{!! $prefillAppointmentServices->toJson() !!}</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const services = JSON.parse(document.getElementById('servicesData').textContent);
    const prefill = JSON.parse(document.getElementById('prefillData').textContent);
    const body = document.getElementById('itemsBody');
    let rowIndex = 0;

    function addRow(serviceId, quantity) {
        const idx = rowIndex++;
        const tr = document.createElement('tr');

        const options = services.map(function (s) {
            const selected = (serviceId && String(serviceId) === String(s.id)) ? 'selected' : '';
            return '<option value="' + s.id + '" data-price="' + s.price + '" ' + selected + '>' + s.label + ' - ' + new Intl.NumberFormat('vi-VN').format(s.price) + 'đ</option>';
        }).join('');

        tr.innerHTML =
            '<td>' +
                '<select class="form-control service-select" name="items[' + idx + '][service_id]" required>' +
                    '<option value="">-- Chọn --</option>' + options +
                '</select>' +
            '</td>' +
            '<td><input type="number" min="1" class="form-control" name="items[' + idx + '][quantity]" value="' + (quantity || 1) + '" required></td>' +
            '<td class="line-price text-muted">—</td>';

        body.appendChild(tr);

        const select = tr.querySelector('.service-select');
        const priceCell = tr.querySelector('.line-price');

        function updatePrice() {
            const opt = select.options[select.selectedIndex];
            const price = opt ? parseFloat(opt.getAttribute('data-price') || 0) : 0;
            priceCell.textContent = price ? new Intl.NumberFormat('vi-VN').format(price) + 'đ' : '—';
        }

        select.addEventListener('change', updatePrice);
        updatePrice();
    }

    if (prefill.length) {
        prefill.forEach(function (item) {
            addRow(item.service_id, item.quantity);
        });
    } else {
        addRow();
    }

    document.getElementById('addLineBtn').addEventListener('click', function () {
        addRow();
    });
});
</script>
@endsection
