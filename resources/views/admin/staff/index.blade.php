@extends('layouts.admin.admin')

@section('title', 'Staff Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="mb-1">Staff Management</h1>

        <p class="text-muted mb-0">
            Manage staff profiles, roles and contact information
        </p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i>
        Add Staff
    </a>

</div>


{{-- Search --}}

<div class="card mb-4">

    <div class="card-body">

        <form
            action="{{ route('staff.index') }}"
            method="GET"
            class="row g-2"
        >

            <div class="col-md-10">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search staff..."
                    value="{{ request('search') }}"
                >

            </div>

            <div class="col-md-2">

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >
                    <i class="bi bi-search"></i>
                    Search
                </button>

            </div>

        </form>

    </div>

</div>


{{-- Staff List --}}

<div class="card">

    <div class="card-header d-flex justify-content-between">

        <strong>Staff List</strong>

        <span class="text-muted">
            Total: {{ $staff->total() }}
        </span>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Full Name</th>

                        <th>Username</th>

                        <th>Role</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                @forelse($staff as $member)

                    <tr>

                        <td>
                            {{ $member->UserID }}
                        </td>


                        <td>

                            <strong>
                                {{ $member->FullName ?? $member->Username }}
                            </strong>

                        </td>


                        <td>
                            {{ $member->Username }}
                        </td>


                        <td>

                            @if($member->RoleID == 1)

                                <span class="badge bg-warning text-dark">
                                    Receptionist
                                </span>

                            @else

                                <span class="badge bg-primary">
                                    Service Staff
                                </span>

                            @endif

                        </td>


                        <td>
                            {{ $member->Email ?? '-' }}
                        </td>


                        <td>
                            {{ $member->Phone ?? '-' }}
                        </td>


                        <td>

                            @if($member->IsActive)

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    Inactive
                                </span>

                            @endif

                        </td>


                        <td>

                            <a
                                href="{{ route('staff.show', $member->UserID) }}"
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="bi bi-eye"></i>
                                View
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5"
                        >

                            <i
                                class="bi bi-person-x"
                                style="font-size: 40px;"
                            ></i>

                            <p class="mt-2 mb-0">
                                No staff found.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    @if($staff->hasPages())

        <div class="card-footer">

            {{ $staff->links() }}

        </div>

    @endif

</div>

@endsection