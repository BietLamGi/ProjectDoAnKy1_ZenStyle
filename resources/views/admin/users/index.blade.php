@extends('layouts.admin.admin')

@section('title', 'Users')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Users</h1>
        <p class="text-muted mb-0">
            Manage admin and staff accounts
        </p>
    </div>

    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1"></i>
        Create Staff Account
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>User Accounts</strong>

        <span class="text-muted">
            Total: {{ $users->total() }}
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Staff Type</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>
                            {{ $user->UserID }}
                        </td>

                        <td>
                            <strong>
                                {{ $user->FullName }}
                            </strong>
                        </td>

                        <td>
                            {{ $user->Username }}
                        </td>

                        <td>
                            {{ $user->Email ?? '-' }}
                        </td>

                        <td>
                            {{ $user->Phone ?? '-' }}
                        </td>

                  <td>

    @if((int) $user->RoleID === 1)

        <span class="badge bg-danger">
            Admin
        </span>

    @elseif((int) $user->RoleID === 2)

        <span class="badge bg-info text-dark">
            Receptionist
        </span>

    @elseif((int) $user->RoleID === 3)

        <span class="badge bg-success">
            Staff
        </span>

    @elseif((int) $user->RoleID === 4)

        <span class="badge bg-secondary">
            Customer
        </span>

    @else

        <span class="badge bg-dark">
            Unknown
        </span>

    @endif

</td>

                        <td class="text-center">

                            <a
                                href="{{ route('users.edit', $user->UserID) }}"
                                class="btn btn-sm btn-outline-primary"
                                title="Edit"
                            >
                                <i class="bi bi-pencil"></i>
                            </a>

                            @if((int) $user->RoleID !== 1)

                                <form
                                    action="{{ route('users.destroy', $user->UserID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this account?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        title="Delete"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                </form>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5"
                        >

                            <div class="text-muted">

                                <i
                                    class="bi bi-people"
                                    style="font-size: 40px;"
                                ></i>

                                <p class="mt-2 mb-0">
                                    No user accounts found.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @if($users->hasPages())

        <div class="card-footer">

            {{ $users->links() }}

        </div>

    @endif

</div>

@endsection