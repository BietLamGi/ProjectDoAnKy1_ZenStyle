@extends('layouts.receptionist.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Notifications</h1>
            <p class="text-muted mb-0">Reminders and updates for the receptionist.</p>
        </div>
        <div class="heading-actions">
            <form action="{{ route('receptionist.notifications.read-all') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-check2-all"></i> Mark all as read
                </button>
            </form>
        </div>
    </div>

    <div class="panel">
        @if ($notifications->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                No notifications yet.
            </div>
        @else
            <div class="legend-list">
                @foreach ($notifications as $notification)
                    <div>
                        <span>
                            <strong class="d-block text-body">
                                {{ $notification->Title ?: 'Notification' }}
                                @if (!$notification->IsRead)
                                    <span class="badge text-bg-primary ms-1">New</span>
                                @endif
                                @if ($notification->Type)
                                    <span class="badge text-bg-secondary ms-1">{{ $notification->Type }}</span>
                                @endif
                            </strong>
                            <span>{{ $notification->Message }}</span>
                            <span class="d-block text-muted small mt-1">
                                {{ $notification->user->Username ?? 'System-wide' }}
                                &middot;
                                {{ $notification->CreatedAt ? \Illuminate\Support\Carbon::parse($notification->CreatedAt)->format('d/m/Y H:i') : '' }}
                            </span>
                        </span>

                        @if (!$notification->IsRead)
                            <form action="{{ route('receptionist.notifications.read', $notification) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-light" title="Mark as read">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-3">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
