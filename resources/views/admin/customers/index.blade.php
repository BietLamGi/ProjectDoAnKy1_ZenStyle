@extends('layouts.admin.admin')

@section('title', 'Customers')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="mb-1">Customers</h1>
        <p class="text-muted mb-0">
            Manage salon customers
        </p>
    </div>

    <a href="{{ route('customers.create') }}"
       class="btn btn-primary">
        + Add Customer
    </a>

</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Membership</th>
                        <th>Loyalty Points</th>
                        <th width="180">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($customers as $customer)

                    <tr>

                        <td>
                            #{{ $customer->CustomerID }}
                        </td>

                        <td>
                            <strong>
                                {{ $customer->FullName }}
                            </strong>
                        </td>

                        <td>
                            {{ $customer->Phone ?? '-' }}
                        </td>

                        <td>
                            {{ $customer->Email ?? '-' }}
                        </td>

                        <td>
                            <span class="badge bg-info">
                                {{ $customer->MembershipTier ?? 'Basic' }}
                            </span>
                        </td>

                        <td>
                            {{ $customer->LoyaltyPoints ?? 0 }}
                        </td>

                        <td>

                            <a href="{{ route('customers.edit', $customer->CustomerID) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('customers.destroy', $customer->CustomerID) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Are you sure you want to delete this customer?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-danger">
                                    Delete
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="text-center text-muted py-4">
                            No customers found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $customers->links() }}
        </div>

    </div>

</div>

@endsection