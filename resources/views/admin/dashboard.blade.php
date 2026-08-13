@extends('layouts.admin.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Welcome to ZenStyle Salon Management System
        </p>
    </div>

    {{-- Statistics --}}
    <div class="row g-4">

        {{-- Users --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Users</p>
                        <h3 class="fw-bold mb-0">
                            {{ $totalUsers }}
                        </h3>
                    </div>

                    <div class="fs-1 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customers --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Customers</p>
                        <h3 class="fw-bold mb-0">
                            {{ $totalCustomers }}
                        </h3>
                    </div>

                    <div class="fs-1 text-success">
                        <i class="bi bi-person-heart"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Services --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Active Services</p>
                        <h3 class="fw-bold mb-0">
                            {{ $activeServices }}
                        </h3>
                    </div>

                    <div class="fs-1 text-warning">
                        <i class="bi bi-scissors"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointments --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Appointments</p>
                        <h3 class="fw-bold mb-0">
                            {{ $totalAppointments }}
                        </h3>
                    </div>

                    <div class="fs-1 text-danger">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- Second row --}}
    <div class="row g-4 mt-1">

        {{-- Today Appointments --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Today's Appointments
                    </p>

                    <h3 class="fw-bold">
                        {{ $todayAppointments }}
                    </h3>

                    <small class="text-muted">
                        Appointments scheduled for today
                    </small>
                </div>
            </div>
        </div>


        {{-- Revenue --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Total Revenue
                    </p>

                    <h3 class="fw-bold">
                        {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} ₫
                    </h3>

                    <small class="text-muted">
                        Total invoice revenue
                    </small>
                </div>
            </div>
        </div>


        {{-- Feedback --}}
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-1">
                        Feedback
                    </p>

                    <h3 class="fw-bold">
                        {{ $totalFeedback }}
                    </h3>

                    <small class="text-muted">
                        Average rating:
                        {{ number_format($averageRating ?? 0, 1) }}/5
                    </small>
                </div>
            </div>
        </div>

    </div>


    {{-- Today's appointments --}}
    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white border-0 py-3">
            <h5 class="fw-bold mb-0">
                Today's Appointments
            </h5>
        </div>

        <div class="card-body">

            @if($todayList->count())

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Staff</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                        @foreach($todayList as $appointment)

                            <tr>

                                <td>
                                    #{{ $appointment->AppointmentID }}
                                </td>

                                <td>
                                    {{ $appointment->customer->FullName ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $appointment->staff->Username ?? 'Not assigned' }}
                                </td>

                                <td>
                                    {{ $appointment->StartTime }}
                                    -
                                    {{ $appointment->EndTime }}
                                </td>

                                <td>

                                    @if($appointment->Status === 'Booked')

                                        <span class="badge bg-primary">
                                            Booked
                                        </span>

                                    @elseif($appointment->Status === 'Completed')

                                        <span class="badge bg-success">
                                            Completed
                                        </span>

                                    @elseif($appointment->Status === 'Cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ $appointment->Status }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x fs-1"></i>

                    <p class="mt-2 mb-0">
                        No appointments today.
                    </p>
                </div>

            @endif

        </div>

    </div>

</div>

@endsection