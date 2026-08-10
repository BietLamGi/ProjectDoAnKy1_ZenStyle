@extends('layouts.receptionist.app')

@section('title', 'Chi tiết hoá đơn')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Hoá đơn #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Lập lúc {{ $order->created_at?->format('d/m/Y H:i') }}
                @if ($order->receptionist) bởi {{ $order->receptionist->Username ?? $order->receptionist->Email }} @endif
            </p>
        </div>
        <div class="heading-actions">
            @if ($order->payment_status !== 'paid')
                <form action="{{ route('receptionist.orders.mark-paid', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Đánh dấu đã thanh toán
                    </button>
                </form>
            @endif
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer"></i> In hoá đơn
            </button>
            <a href="{{ route('receptionist.orders.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Thông tin</h5></div>
                <div class="info-list">
                    <div><span>Khách hàng</span><strong>{{ $order->customer->FullName ?? '—' }}</strong></div>
                    <div><span>Điện thoại</span><strong>{{ $order->customer->Phone ?? '—' }}</strong></div>
                    <div><span>Lịch hẹn</span><strong>{{ $order->appointment ? '#' . $order->appointment->AppointmentID : '—' }}</strong></div>
                    <div><span>Phương thức</span><strong class="text-uppercase">{{ $order->payment_method }}</strong></div>
                    <div>
                        <span>Trạng thái</span>
                        <strong>
                            <span class="badge text-bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ $order->payment_status }}
                            </span>
                        </strong>
                    </div>
                </div>
                @if ($order->note)
                    <p class="text-muted mt-3 mb-0">{{ $order->note }}</p>
                @endif
            </div>
        </div>

        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-header"><h5 class="mb-0">Chi tiết hoá đơn</h5></div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Dịch vụ / sản phẩm</th>
                                <th>SL</th>
                                <th>Đơn giá</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $line)
                                <tr>
                                    <td>{{ $line->service_name }}</td>
                                    <td>{{ $line->quantity }}</td>
                                    <td>{{ number_format($line->unit_price, 0, ',', '.') }}đ</td>
                                    <td class="text-end">{{ number_format($line->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Tổng cộng</th>
                                <th class="text-end">{{ number_format($order->total_amount, 0, ',', '.') }}đ</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
