@extends('layouts.admin.admin')

@section('title', 'Add Customer')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="mb-1">Add Customer</h1>
        <p class="text-muted mb-0">
            Create a new customer profile
        </p>
    </div>

    <a href="{{ route('customers.index') }}"
       class="btn btn-secondary">
        Back
    </a>

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

        <form action="{{ route('customers.store') }}"
              method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Full Name
                    </label>

                    <input type="text"
                           name="FullName"
                           class="form-control"
                           value="{{ old('FullName') }}"
                           required>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Phone
                    </label>

                    <input type="text"
                           name="Phone"
                           class="form-control"
                           value="{{ old('Phone') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input type="email"
                           name="Email"
                           class="form-control"
                           value="{{ old('Email') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Date of Birth
                    </label>

                    <input type="date"
                           name="DOB"
                           class="form-control"
                           value="{{ old('DOB') }}">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Membership Tier
                    </label>

                    <select name="MembershipTier"
                            class="form-select">

                        <option value="Basic">
                            Basic
                        </option>

                        <option value="Silver">
                            Silver
                        </option>

                        <option value="Gold">
                            Gold
                        </option>

                        <option value="VIP">
                            VIP
                        </option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Loyalty Points
                    </label>

                    <input type="number"
                           name="LoyaltyPoints"
                           class="form-control"
                           value="{{ old('LoyaltyPoints', 0) }}"
                           min="0">

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Allergies
                    </label>

                    <textarea name="Allergies"
                              class="form-control"
                              rows="4"
                              placeholder="Customer allergies...">{{ old('Allergies') }}</textarea>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">
                        Notes
                    </label>

                    <textarea name="Notes"
                              class="form-control"
                              rows="4"
                              placeholder="Additional notes...">{{ old('Notes') }}</textarea>

                </div>

            </div>

            <div class="text-end mt-3">

                <a href="{{ route('customers.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit"
                        class="btn btn-primary">
                    Create Customer
                </button>

            </div>

        </form>

    </div>

</div>

@endsection