@extends('layouts.receptionist.app')

@section('title', 'Customer feedback')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Customer feedback</h1>
            <p class="text-muted mb-0">Review ratings and comments left by customers after their appointment.</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <form class="d-flex flex-wrap gap-2" method="GET">
                <select name="rating" class="form-control" style="max-width: 170px;">
                    <option value="">All ratings</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" @selected((string) $rating === (string) $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search" placeholder="Customer name or phone...">
                <button class="btn btn-light" type="submit"><i class="bi bi-filter"></i> Filter</button>
                <a href="{{ route('receptionist.feedbacks.index') }}" class="btn btn-outline-secondary">Clear filter</a>
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
                            <th>Appointment</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $feedback)
                            <tr class="{{ $feedback->Rating <= 2 ? 'table-danger' : '' }}">
                                <td>
                                    <div class="fw-semibold">{{ $feedback->customer->FullName ?? 'Anonymous' }}</div>
                                    <div class="text-muted small">{{ $feedback->customer->Phone ?? '' }}</div>
                                </td>
                                <td>
                                    @if ($feedback->appointment)
                                        <a href="{{ route('receptionist.appointments.show', $feedback->appointment) }}">#{{ $feedback->AppointmentID }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="bi {{ $i < $feedback->Rating ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                    @endfor
                                    @if ($feedback->Rating <= 2)
                                        <span class="badge text-bg-danger ms-1">Needs follow-up</span>
                                    @endif
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($feedback->Comments, 80) ?: '—' }}</td>
                                <td>{{ $feedback->FeedbackDate?->format('d/m/Y H:i') ?? '—' }}</td>
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
