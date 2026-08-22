@extends('layouts.staff.staff')

@section('title', 'Leave Request')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">
                Leave Request
            </h2>

            <p class="text-muted mb-0">
                Manage your leave requests.
            </p>

        </div>


        <a href="{{ route('staff.leave-requests.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-lg me-1"></i>

            Request Leave

        </a>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

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

                            <td>
                                {{ \Carbon\Carbon::parse($request->LeaveStartDate)->format('d/m/Y') }}
                            </td>


                            <td>
                                {{ \Carbon\Carbon::parse($request->LeaveEndDate)->format('d/m/Y') }}
                            </td>


                            <td>
                                {{ $request->Reason ?: '-' }}
                            </td>


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


                            <td>

                                @if($request->CreatedAt)

                                    {{ \Carbon\Carbon::parse($request->CreatedAt)->format('d/m/Y H:i') }}

                                @else

                                    -

                                @endif

                            </td>


                            <td>

                                @if($request->Status === 'Pending')

                                    <form method="POST"
                                          action="{{ route(
                                              'staff.leave-requests.destroy',
                                              $request->LeaveRequestID
                                          ) }}">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Cancel this leave request?')">

                                            <i class="bi bi-x-circle me-1"></i>

                                            Cancel

                                        </button>

                                    </form>

                                @else

                                    <span class="text-muted">
                                        -
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="text-center text-muted py-5">

                                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>

                                No leave requests found.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection