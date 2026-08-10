@extends('layouts.receptionist.app')

@section('title', 'Thông báo')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Thông báo</h1>
            <p class="text-muted mb-0">Nhắc việc và cập nhật hệ thống dành cho lễ tân.</p>
        </div>
        <div class="heading-actions">
            <form action="{{ route('receptionist.notifications.read-all') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-check2-all"></i> Đánh dấu tất cả đã đọc
                </button>
            </form>
        </div>
    </div>

    <div class="panel">
        @if ($notifications->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                Chưa có thông báo nào.
            </div>
        @else
            <div class="legend-list">
                @foreach ($notifications as $notification)
                    <div>
                        <span>
                            <strong class="d-block text-body">
                                {{ $notification->title ?: 'Thông báo' }}
                                @if (!$notification->is_read)
                                    <span class="badge text-bg-primary ms-1">Mới</span>
                                @endif
                            </strong>
                            <span>{{ $notification->message }}</span>
                            <span class="d-block text-muted small mt-1">{{ $notification->created_at?->format('d/m/Y H:i') }}</span>
                        </span>

                        @if (!$notification->is_read)
                            <form action="{{ route('receptionist.notifications.read', $notification) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-light" title="Đánh dấu đã đọc">
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
