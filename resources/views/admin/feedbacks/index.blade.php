@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Feedback</h1>
        <p class="text-muted">Manage customer feedback</p>
    </div>

    <a href="{{ route('feedbacks.create') }}" class="btn btn-primary">
        + Create Feedback
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
                Feedback List
            </h5>

            <span class="text-muted">
                Total: {{ $feedbacks->total() }}
            </span>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Appointment ID</th>
                        <th>Rating</th>
                        <th>Comments</th>
                        <th>Feedback Date</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($feedbacks as $feedback)

                        <tr>

                            <td>
                                {{ $feedback->FeedbackID }}
                            </td>

                            <td>
                                #{{ $feedback->AppointmentID }}
                            </td>

                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->Rating)
                                        <span>★</span>
                                    @else
                                        <span>☆</span>
                                    @endif
                                @endfor

                                <small class="text-muted">
                                    ({{ $feedback->Rating }}/5)
                                </small>
                            </td>

                            <td>
                                {{ $feedback->Comments ?? '-' }}
                            </td>

                            <td>
                                {{ $feedback->FeedbackDate
                                    ? \Carbon\Carbon::parse($feedback->FeedbackDate)->format('d/m/Y H:i')
                                    : '-'
                                }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('feedbacks.edit', $feedback->FeedbackID) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    ✏️
                                </a>

                                <form
                                    action="{{ route('feedbacks.destroy', $feedback->FeedbackID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this feedback?')"
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
                            <td colspan="6" class="text-center py-4">
                                No feedback found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $feedbacks->links() }}
        </div>

    </div>
</div>

@endsection