@extends('layouts.admin.admin')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h1 class="fw-bold">Edit Supplier</h1>
        <p class="text-muted">
            Update supplier information
        </p>
    </div>

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

            <form
                action="{{ route('suppliers.update', $supplier->SupplierID) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">
                        Supplier Name
                    </label>

                    <input
                        type="text"
                        name="SupplierName"
                        class="form-control"
                        value="{{ old('SupplierName', $supplier->SupplierName) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="Phone"
                        class="form-control"
                        value="{{ old('Phone', $supplier->Phone) }}"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="Email"
                        class="form-control"
                        value="{{ old('Email', $supplier->Email) }}"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Address
                    </label>

                    <textarea
                        name="Address"
                        class="form-control"
                        rows="3"
                    >{{ old('Address', $supplier->Address) }}</textarea>
                </div>

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Supplier
                    </button>

                    <a
                        href="{{ route('suppliers.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection