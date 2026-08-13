@extends('layouts.admin.admin')

@section('title', 'Edit Staff Account')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Edit Staff Account</h1>
        <p class="text-muted mb-0">
            Update staff account information and position
        </p>
    </div>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        Back
    </a>
</div>

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

        <form action="{{ route('users.update', $user->UserID) }}" method="POST">
            @csrf
            @method('PUT')

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
                        value="{{ old('FullName', $user->FullName) }}"
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
                        value="{{ old('Username', $user->Username) }}"
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
                        value="{{ old('Email', $user->Email) }}"
                    >
                </div>

                {{-- Phone --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Phone
                    </label>

                    <input
                        type="text"
                        name="Phone"
                        class="form-control"
                        value="{{ old('Phone', $user->Phone) }}"
                    >
                </div>

                {{-- Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="Password"
                        class="form-control"
                        placeholder="Leave blank to keep current password"
                    >
                </div>

                {{-- Confirm Password --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Confirm New Password
                    </label>

                    <input
                        type="password"
                        name="Password_confirmation"
                        class="form-control"
                        placeholder="Confirm new password"
                    >
                </div>

                {{-- Staff Type --}}
                <div class="col-md-12 mb-4">

                    <label class="form-label">
                        Staff Position
                    </label>

                    <div class="row">

                        {{-- Receptionist --}}
                        <div class="col-md-6">

                            <div class="form-check border rounded p-3">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="StaffType"
                                    id="receptionist"
                                    value="receptionist"
                                    {{ old('StaffType', $user->StaffType) === 'receptionist' ? 'checked' : '' }}
                                    required
                                >

                                <label
                                    class="form-check-label"
                                    for="receptionist"
                                >
                                    <strong>Receptionist</strong>

                                    <div class="text-muted small mt-1">
                                        Manage customers, appointments,
                                        check-in and reception tasks.
                                    </div>
                                </label>

                            </div>

                        </div>

                        {{-- Service Staff --}}
                        <div class="col-md-6">

                            <div class="form-check border rounded p-3">

                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="StaffType"
                                    id="service"
                                    value="service"
                                    {{ old('StaffType', $user->StaffType) === 'service' ? 'checked' : '' }}
                                >

                                <label
                                    class="form-check-label"
                                    for="service"
                                >
                                    <strong>Service Staff</strong>

                                    <div class="text-muted small mt-1">
                                        Perform services and update
                                        service status.
                                    </div>
                                </label>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

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
                    Update Account
                </button>

            </div>

        </form>

    </div>
</div>

@endsection