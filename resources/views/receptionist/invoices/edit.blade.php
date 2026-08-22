@extends('layouts.receptionist.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Invoice #{{ $invoice->InvoiceID }}</h1>
            <p class="text-muted mb-0">{{ $invoice->appointment->customer->FullName ?? '' }}</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.invoices.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.invoices.update', $invoice) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Appointment</label>
                    <input type="text" class="form-control" disabled
                        value="#{{ $invoice->AppointmentID }} - {{ $invoice->appointment?->customer?->FullName ?? 'N/A' }} ({{ $invoice->appointment?->AppointmentDate }})">
                    <div class="form-text">This invoice stays tied to its original appointment - it can't be moved.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Invoice date</label>
                    <input type="text" class="form-control" disabled
                        value="{{ $invoice->InvoiceDate?->format('d/m/Y H:i') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Payment method</label>
                    <select name="PaymentMethod" class="form-control">
                        <option value="">-- Select payment method --</option>
                        <option value="Cash" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Cash')>Cash
                        </option>
                        <option value="Bank Transfer" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Bank
                            Transfer')>Bank Transfer</option>
                        <option value="Credit Card" @selected(old('PaymentMethod', $invoice->PaymentMethod) == 'Credit
                            Card')>Credit Card</option>
                        <option value="E-Wallet" @selected(old('PaymentMethod', $invoice->PaymentMethod) ==
                            'E-Wallet')>E-Wallet</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control" value="{{ number_format($totalAmount, 0, ',', '.') }} đ"
                        readonly style="background-color: #f1f3f5; cursor: not-allowed;">
                    <div class="form-text">
                        Automatically calculated from booked services.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount Amount</label>

                    <input type="text" class="form-control" value="{{ number_format($discountAmount, 0, ',', '.') }} đ"
                        readonly style="background-color: #f1f3f5; cursor: not-allowed;">
                    <div class="form-text">
                        Sum of each line's own active promotion.
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Final amount</label>

                    <input type="text" class="form-control" value="{{ number_format($finalAmount, 0, ',', '.') }} đ"
                        readonly style="background-color: #f1f3f5; cursor: not-allowed; font-weight: bold;">
                </div>
            </div>

            @if ($lines->isNotEmpty())
            <div class="blank-panel mt-3">
                <div class="table-responsive">
                    {{-- Mỗi dòng tự áp dụng khuyến mãi đang active của
                             chính nó - không giới hạn chỉ 1 khuyến mãi cho cả
                             hoá đơn. --}}
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
                            @foreach ($lines as $line)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update
                </button>
            </div>
        </form>

        <form action="{{ route('receptionist.invoices.destroy', $invoice) }}" method="POST"
            onsubmit="return confirm('Delete this invoice?');" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete invoice
            </button>
        </form>
    </div>

</div>
@endsection