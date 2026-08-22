@extends('layouts.admin.admin')

@section('title', 'Leave Requests')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="mb-1">
            Leave Requests
        </h2>

        <p class="text-muted mb-0">
            Manage staff leave requests.
        </p>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Staff</th>

                            <th>From</th>

                            <th>To</th>

                            <th>Reason</th>

                            <th>Status</th>

                            <th>Submitted</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($leaveRequests as $request)

                        <tr>

                            {{-- STAFF --}}

                            <td>

                                @if($request->user)

                                    <strong>
                                        {{ $request->user->Username }}
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        {{ $request->user->Email }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        Unknown
                                    </span>

                                @endif

                            </td>


                            {{-- FROM --}}

                            <td>

                                {{ $request->LeaveStartDate->format('d/m/Y') }}

                            </td>


                            {{-- TO --}}

                            <td>

                                {{ $request->LeaveEndDate->format('d/m/Y') }}

                            </td>


                            {{-- REASON --}}

                            <td>

                                {{ $request->Reason ?: '-' }}

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @if($request->Status === 'Pending')

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                @elseif($request->Status === 'Approved')

                                    <span class="badge bg-success">
                                        Approved
                                    </span>

                                @elseif($request->Status === 'Rejected')

                                    <span class="badge bg-danger">
                                        Rejected
                                    </span>

                                @elseif($request->Status === 'Cancelled')

                                    <span class="badge bg-secondary">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        {{ $request->Status }}
                                    </span>

                                @endif

                            </td>


                            {{-- SUBMITTED --}}

                            <td>

                                {{ $request->CreatedAt?->format('d/m/Y H:i') }}

                            </td>


                            {{-- ACTION --}}

                            <td>

                                @if($request->Status === 'Pending')

                                    <div class="d-flex gap-1">

                                        {{-- APPROVE --}}

                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.leave-requests.approve',
                                                  $request->LeaveRequestID
                                              ) }}">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-success"
                                                    onclick="return confirm('Approve this leave request?')">

                                                <i class="bi bi-check-lg"></i>

                                                Approve

                                            </button>

                                        </form>


                                        {{-- REJECT --}}

                                        <form method="POST"
                                              action="{{ route(
                                                  'admin.leave-requests.reject',
                                                  $request->LeaveRequestID
                                              ) }}">

                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Reject this leave request?')">

                                                <i class="bi bi-x-lg"></i>

                                                Reject

                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="text-center text-muted py-5">

                                No leave requests found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- PAGINATION --}}

    <div class="mt-3">

        {{ $leaveRequests->links() }}

    </div>

</div>

@endsection