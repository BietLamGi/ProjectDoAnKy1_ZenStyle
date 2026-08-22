```blade
@extends('layouts.receptionist.app')

@section('title', 'Customers')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Customers</h1>
            <p class="text-muted mb-0">
                Manage customer profiles: search, add, and update information.
            </p>
        </div>

        <div class="heading-actions">
            <a href="{{ route('receptionist.customers.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i>
                Add Customer
            </a>
        </div>
    </div>

    <div class="panel">

        <div class="panel-header">
            <form class="d-flex gap-2" method="GET">
                <input
                    type="search"
                    name="q"
                    value="{{ $keyword }}"
                    class="form-control table-search"
                    placeholder="Search by name, phone, email, or appointment #..."
                >

                <button class="btn btn-light" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        @if ($customers->isEmpty())

            <div class="blank-panel blank-state text-center py-5 text-muted">
                No customers found.
            </div>

        @else

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Bookings</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($customers as $customer)

                            <tr>

                                {{-- Customer --}}
                                <td>
                                    <a
                                        href="{{ route('receptionist.customers.show', $customer) }}"
                                        class="fw-semibold text-decoration-none"
                                    >
                                        {{ $customer->FullName }}
                                    </a>
                                </td>

                                {{-- Phone --}}
                                <td>
                                    {{ $customer->Phone }}
                                </td>

                                {{-- Email --}}
                                <td>
                                    {{ $customer->Email ?: '—' }}
                                </td>

                                {{-- Number of bookings --}}
                                <td>
                                    <span
                                        class="badge bg-primary-subtle text-primary"
                                        title="Total appointments"
                                    >
                                        {{ $customer->appointments_count ?? 0 }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">

                                    {{-- Create appointment --}}
                                    <a
                                        href="{{ route('receptionist.appointments.create', [
                                            'customer_id' => $customer->CustomerID
                                        ]) }}"
                                        class="btn btn-sm btn-light"
                                        title="Create appointment"
                                    >
                                        <i class="bi bi-calendar-plus"></i>
                                    </a>

                                    {{-- Edit customer --}}
                                    <a
                                        href="{{ route('receptionist.customers.edit', $customer) }}"
                                        class="btn btn-sm btn-light"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Delete customer --}}
                                    <form
                                        action="{{ route('receptionist.customers.destroy', $customer) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Delete this customer?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-light text-danger"
                                            title="Delete"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>

                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $customers->links() }}
            </div>

        @endif

    </div>

</div>
@endsection
```
