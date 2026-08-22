@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Invoices</h1>
        <p class="text-muted">Manage salon invoices</p>
    </div>

    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        + Create Invoice
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                Invoice List
            </h5>

            <span class="text-muted">
                Total: {{ $invoices->total() }}
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Appointment ID</th>
                        <th>Invoice Date</th>
                        <th>Total Amount</th>
                        <th>Discount</th>
                        <th>Final Amount</th>
                        <th>Payment Method</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($invoices as $invoice)

                        <tr>

                            <td>
                                {{ $invoice->InvoiceID }}
                            </td>

                            <td>
                                {{ $invoice->AppointmentID }}
                            </td>

                            <td>
                                {{ $invoice->InvoiceDate }}
                            </td>

                            <td>
                                {{ number_format($invoice->TotalAmount, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ number_format($invoice->DiscountAmount, 0, ',', '.') }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($invoice->FinalAmount, 0, ',', '.') }}
                                </strong>
                            </td>

                            <td>
                                {{ $invoice->PaymentMethod ?? '-' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('invoices.edit', $invoice->InvoiceID) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    ✏️
                                </a>

                                <form
                                    action="{{ route('invoices.destroy', $invoice->InvoiceID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this invoice?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        🗑️
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No invoices found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $invoices->links() }}
        </div>

    </div>
</div>

@endsection