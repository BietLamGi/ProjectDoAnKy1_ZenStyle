@extends('layouts.receptionist.app')

@section('title', 'Hoá đơn')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Hoá đơn</h1>
            <p class="text-muted mb-0">Danh sách hoá đơn thanh toán dịch vụ / sản phẩm.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.orders.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Lập hoá đơn
            </a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex gap-2" method="GET">
                <select name="status" class="form-control" style="max-width: 200px;">
                    <option value="">Tất cả trạng thái</option>
                    <option value="paid" @selected($status === 'paid')>Đã thanh toán</option>
                    <option value="unpaid" @selected($status === 'unpaid')>Chưa thanh toán</option>
                </select>
                <button class="btn btn-light" type="submit"><i class="bi bi-filter"></i> Lọc</button>
            </form>
        </div>

        @if ($orders->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                Chưa có hoá đơn nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Thanh toán</th>
                            <th>Ngày lập</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->FullName ?? '—' }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                <td class="text-uppercase">{{ $order->payment_method }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $order->payment_status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('receptionist.orders.show', $order) }}" class="btn btn-sm btn-light" title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if ($order->payment_status !== 'paid')
                                        <form action="{{ route('receptionist.orders.mark-paid', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light text-success" title="Đánh dấu đã thanh toán">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
