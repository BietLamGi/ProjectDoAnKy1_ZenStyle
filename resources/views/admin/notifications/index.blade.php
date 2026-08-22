@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Notifications</h1>
        <p class="text-muted">Manage system notifications</p>
    </div>

    <a href="{{ route('notifications.create') }}" class="btn btn-primary">
        + Add Notification
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                Notifications
            </h5>

            <span class="text-muted">
                Total: {{ $notifications->total() }}
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($notifications as $notification)

                        <tr>

                            <td>
                                {{ $notification->NotificationID }}
                            </td>

                            <td>
                                <strong>
                                    {{ $notification->Title }}
                                </strong>
                            </td>

                            <td>
                                {{ $notification->Message }}
                            </td>

                            <td>
                                @if($notification->Type)
                                    <span class="badge bg-info">
                                        {{ $notification->Type }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $notification->user->Username ?? 'All Users' }}
                            </td>

                            <td>
                                @if($notification->IsRead)
                                    <span class="badge bg-success">
                                        Read
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        Unread
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $notification->CreatedAt?->format('d/m/Y H:i') ?? '-' }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('notifications.edit', $notification->NotificationID) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    ✏️
                                </a>

                                <form
                                    action="{{ route('notifications.destroy', $notification->NotificationID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this notification?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        🗑️
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No notifications found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>

    </div>
</div>

@endsection