@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold">Suppliers</h1>
            <p class="text-muted">Manage salon suppliers</p>
        </div>

        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            + Add Supplier
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
                    Suppliers
                </h5>

                <span class="text-muted">
                    Total: {{ $suppliers->total() }}
                </span>
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Supplier Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($suppliers as $supplier)

                            <tr>

                                <td>
                                    {{ $supplier->SupplierID }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $supplier->SupplierName }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $supplier->Phone ?? '-' }}
                                </td>

                                <td>
                                    {{ $supplier->Email ?? '-' }}
                                </td>

                                <td>
                                    {{ $supplier->Address ?? '-' }}
                                </td>

                                <td>

                                    <a
                                        href="{{ route('suppliers.edit', $supplier->SupplierID) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        ✏️
                                    </a>

                                    <form
                                        action="{{ route('suppliers.destroy', $supplier->SupplierID) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this supplier?')"
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
                                <td colspan="6" class="text-center py-4">
                                    No suppliers found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">
                {{ $suppliers->links() }}
            </div>

        </div>
    </div>

</div>

@endsection