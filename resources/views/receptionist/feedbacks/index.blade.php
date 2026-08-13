@extends('layouts.receptionist.app')

@section('title', 'Customer feedback')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Customer feedback</h1>
            <p class="text-muted mb-0">Track and manage reviews from customers.</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex gap-2" method="GET">
                <select name="status" class="form-control" style="max-width: 200px;">
                    <option value="">All statuses</option>
                    <option value="new" @selected($status === 'new')>New</option>
                    <option value="reviewed" @selected($status === 'reviewed')>Reviewed</option>
                    <option value="resolved" @selected($status === 'resolved')>Resolved</option>
                </select>
                <button class="btn btn-light" type="submit"><i class="bi bi-filter"></i> Filter</button>
            </form>
        </div>

        @if ($feedbacks->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                No feedback yet.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $feedback)
                            <tr>
                                <td>{{ $feedback->customer->FullName ?? 'Ẩn danh' }}</td>
                                <td>
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="bi {{ $i < $feedback->rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                    @endfor
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($feedback->comment, 80) }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $feedback->status === 'resolved' ? 'success' : ($feedback->status === 'reviewed' ? 'info' : 'warning') }}">
                                        {{ $feedback->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if ($feedback->status !== 'reviewed')
                                        <form action="{{ route('receptionist.feedbacks.status', $feedback) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="reviewed">
                                            <button type="submit" class="btn btn-sm btn-light" title="Mark as reviewed">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($feedback->status !== 'resolved')
                                        <form action="{{ route('receptionist.feedbacks.status', $feedback) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="btn btn-sm btn-light text-success" title="Mark as resolved">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
