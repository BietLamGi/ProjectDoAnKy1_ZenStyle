@extends('layouts.app.app')

@section('title', 'Order Successful')

@section('content')

<div class="container">

    <div class="text-center py-5">

        <div class="mb-4">
            <i class="icon-check" style="font-size: 60px;"></i>
        </div>

        <h2>Order Placed Successfully!</h2>

        <p class="mt-3">
            Thank you for your order.
        </p>

        <p>
            Your order number is
            <strong>#{{ $invoice->InvoiceID }}</strong>
        </p>

        <div class="mt-4">

            <a href="{{ route('customer.orders.index') }}" class="btn btn-primary">
                View My Orders
            </a>

        </div>

    </div>

</div>

@endsection