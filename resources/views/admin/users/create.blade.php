@extends('layouts.admin.admin')

@section('title', 'Create Staff Account')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Create Staff Account</h1>
        <p class="text-muted mb-0">
            Create an account for a Receptionist or Service Staff
        </p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Back
    </a>
</div>

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Full Name --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="FullName"
                        class="form-control"
                        value="{{ old('FullName') }}"
                        required
                    >
                </div>

                {{-- Username --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="Username"
                        class="form-control"
                        value="{{ old('Username') }}"
                        required
                    >
                </div>

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="Email"
                        class="form-control"
                        value="{{ old('Email') }}"
                    >
                </div>

                {{-- Phone --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="Phone"
                        class="form-control"
                        value="{{ old('Phone') }}"
                    >
                </div>

                {{-- Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="Password"
                        class="form-control"
                        required
                    >
                </div>

                {{-- Confirm Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="Password_confirmation"
                        class="form-control"
                        required
                    >
                </div>

                {{-- Staff Position --}}
                <div class="col-md-12 mb-4">

                    <label class="form-label">
                        Staff Position
                    </label>

                    <div class="row">

                        {{-- Receptionist --}}
                        <div class="col-md-6 mb-3">

                            <div class="form-check border rounded p-3 h-100">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="RoleID"
                                    id="receptionist"
                                    value="1"
                                    {{ old('RoleID') == 1 ? 'checked' : '' }}
                                    required
                                >

                                <label
                                    class="form-check-label"
                                    for="receptionist"
                                >
                                    <strong>Receptionist</strong>

                                    <div class="text-muted small mt-1">
                                        Manage customers, appointments,
                                        check-ins and reception tasks.
                                    </div>
                                </label>

                            </div>

                        </div>

                        {{-- Service Staff --}}
                        <div class="col-md-6 mb-3">

                            <div class="form-check border rounded p-3 h-100">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="RoleID"
                                    id="service"
                                    value="2"
                                    {{ old('RoleID') == 2 ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="service"
                                >
                                    <strong>Service Staff</strong>

                                    <div class="text-muted small mt-1">
                                        Perform salon services and update
                                        service status.
                                    </div>
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Buttons --}}
            <div class="text-end">

                <a
                    href="{{ route('users.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Account
                </button>

            </div>

        </form>

    </div>
</div>

@endsection