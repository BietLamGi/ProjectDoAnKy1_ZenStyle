@extends('layouts.admin.admin')

@section('title', 'Staff Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="mb-1">
            Staff Details
        </h1>

        <p class="text-muted mb-0">
            Staff profile and working information
        </p>

    </div>

    <a
        href="{{ route('staff.index') }}"
        class="btn btn-secondary"
    >
        Back
    </a>

</div>


<div class="row">

    {{-- Personal Information --}}

    <div class="col-md-6 mb-4">

        <div class="card h-100">

            <div class="card-header">
                <strong>Personal Information</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="text-muted">
                        Full Name
                    </label>

                    <div class="fw-bold">
                        {{ $staff->FullName ?? $staff->Username }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="text-muted">
                        Username
                    </label>

                    <div>
                        {{ $staff->Username }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="text-muted">
                        Role
                    </label>

                    <div>

                        @if($staff->RoleID == 1)

                            <span class="badge bg-warning text-dark">
                                Receptionist
                            </span>

                        @else

                            <span class="badge bg-primary">
                                Service Staff
                            </span>

                        @endif

                    </div>

                </div>


                <div class="mb-3">

                    <label class="text-muted">
                        Position
                    </label>

                    <div>
                        {{ $staff->Position ?? '-' }}
                    </div>

                </div>


                <div>

                    <label class="text-muted">
                        Status
                    </label>

                    <div>

                        @if($staff->IsActive)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                Inactive
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Contact Information --}}

    <div class="col-md-6 mb-4">

        <div class="card h-100">

            <div class="card-header">

                <strong>
                    Contact Information
                </strong>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="text-muted">
                        Email
                    </label>

                    <div>
                        {{ $staff->Email ?? '-' }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="text-muted">
                        Phone
                    </label>

                    <div>
                        {{ $staff->Phone ?? '-' }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="text-muted">
                        Date of Birth
                    </label>

                    <div>
                        {{ $staff->DateBirth ?? '-' }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Work Schedule --}}

<div class="card">

    <div class="card-header">

        <strong>
            Staff Work Schedule
        </strong>

    </div>

    <div class="card-body">

        <p class="text-muted mb-3">
            View this staff member's working schedule,
            attendance and working hours.
        </p>

        <a
            href="{{ route('work-schedules.index') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-calendar-week"></i>
            View Work Schedule
        </a>

    </div>

</div>

@endsection