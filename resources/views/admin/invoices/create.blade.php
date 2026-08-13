@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Create Invoice</h1>
        <p class="text-muted">Create a new salon invoice</p>
    </div>

    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
        ← Back to Invoices
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Appointment --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Appointment
                    </label>

                    <select
                        name="AppointmentID"
                        class="form-select"
                        required
                    >
                        <option value="">-- Select Appointment --</option>

                        @foreach($appointments as $appointment)
                            <option
                                value="{{ $appointment->AppointmentID }}"
                                {{ old('AppointmentID') == $appointment->AppointmentID ? 'selected' : '' }}
                            >
                                Appointment #{{ $appointment->AppointmentID }}
                                -
                                {{ $appointment->AppointmentDate }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- Total Amount --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Total Amount
                    </label>

                    <input
                        type="number"
                        name="TotalAmount"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('TotalAmount', 0) }}"
                        required
                    >
                </div>

                {{-- Discount --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Discount Amount
                    </label>

                    <input
                        type="number"
                        name="DiscountAmount"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('DiscountAmount', 0) }}"
                        required
                    >
                </div>

                {{-- Final Amount --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Final Amount
                    </label>

                    <input
                        type="number"
                        name="FinalAmount"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('FinalAmount', 0) }}"
                        required
                    >
                </div>

                {{-- Payment Method --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Payment Method
                    </label>

                    <select
                        name="PaymentMethod"
                        class="form-select"
                    >
                        <option value="">-- Select Payment Method --</option>

                        <option
                            value="Cash"
                            {{ old('PaymentMethod') == 'Cash' ? 'selected' : '' }}
                        >
                            Cash
                        </option>

                        <option
                            value="Bank Transfer"
                            {{ old('PaymentMethod') == 'Bank Transfer' ? 'selected' : '' }}
                        >
                            Bank Transfer
                        </option>

                        <option
                            value="Credit Card"
                            {{ old('PaymentMethod') == 'Credit Card' ? 'selected' : '' }}
                        >
                            Credit Card
                        </option>

                        <option
                            value="E-Wallet"
                            {{ old('PaymentMethod') == 'E-Wallet' ? 'selected' : '' }}
                        >
                            E-Wallet
                        </option>

                    </select>
                </div>

            </div>

            <div class="mt-4">
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Invoice
                </button>

                <a
                    href="{{ route('invoices.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>
            </div>

        </form>

    </div>
</div>

@endsection