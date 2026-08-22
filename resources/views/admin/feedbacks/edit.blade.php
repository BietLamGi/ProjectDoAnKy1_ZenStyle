@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Edit Feedback</h1>
        <p class="text-muted">Update customer feedback</p>
    </div>

    <a href="{{ route('feedbacks.index') }}" class="btn btn-secondary">
        ← Back to Feedback
    </a>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Please fix the following errors:</strong>

    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">

        <form action="{{ route('feedbacks.update', $feedback->FeedbackID) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Appointment --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Appointment
                    </label>

                    <select name="AppointmentID" class="form-select" required>

                        @foreach($appointments as $appointment)

                        <option value="{{ $appointment->AppointmentID }}"
                            {{ old('AppointmentID', $feedback->AppointmentID) == $appointment->AppointmentID ? 'selected' : '' }}>
                            Appointment #{{ $appointment->AppointmentID }}
                            -
                            {{ $appointment->AppointmentDate?->format('d/m/Y') }}
                        </option>

                        @endforeach

                    </select>
                </div>

                {{-- Rating --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Rating
                    </label>

                    <select name="Rating" class="form-select" required>

                        <option value="5" {{ old('Rating', $feedback->Rating) == 5 ? 'selected' : '' }}>
                            ★★★★★ - Excellent
                        </option>

                        <option value="4" {{ old('Rating', $feedback->Rating) == 4 ? 'selected' : '' }}>
                            ★★★★☆ - Very Good
                        </option>

                        <option value="3" {{ old('Rating', $feedback->Rating) == 3 ? 'selected' : '' }}>
                            ★★★☆☆ - Good
                        </option>

                        <option value="2" {{ old('Rating', $feedback->Rating) == 2 ? 'selected' : '' }}>
                            ★★☆☆☆ - Fair
                        </option>

                        <option value="1" {{ old('Rating', $feedback->Rating) == 1 ? 'selected' : '' }}>
                            ★☆☆☆☆ - Poor
                        </option>

                    </select>
                </div>

                {{-- Comments --}}
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">
                        Comments
                    </label>

                    <textarea name="Comments" class="form-control" rows="5" maxlength="500"
                        placeholder="Enter customer feedback...">{{ old('Comments', $feedback->Comments) }}</textarea>
                </div>

                {{-- Feedback Date --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Feedback Date
                    </label>

                    <input type="datetime-local" name="FeedbackDate" class="form-control" value="{{ old(
                            'FeedbackDate',
                            $feedback->FeedbackDate
                                ? \Carbon\Carbon::parse($feedback->FeedbackDate)->format('Y-m-d\TH:i')
                                : ''
                        ) }}" required>
                </div>

            </div>

            <div class="mt-4">

                <button type="submit" class="btn btn-primary">
                    Update Feedback
                </button>

                <a href="{{ route('feedbacks.index') }}" class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection