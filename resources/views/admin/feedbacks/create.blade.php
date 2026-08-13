@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Create Feedback</h1>
        <p class="text-muted">Create customer feedback</p>
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

        <form action="{{ route('feedbacks.store') }}" method="POST">
            @csrf

            <div class="row">

                {{-- Appointment --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Appointment
                    </label>

                    <select
                        name="AppointmentID"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Select Appointment --
                        </option>

                        @foreach($appointments as $appointment)

                            <option
                                value="{{ $appointment->AppointmentID }}"
                                {{ old('AppointmentID') == $appointment->AppointmentID ? 'selected' : '' }}
                            >
                                Appointment #{{ $appointment->AppointmentID }}
                                -
                                {{ $appointment->AppointmentDate }}
                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- Rating --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        Rating
                    </label>

                    <select
                        name="Rating"
                        class="form-select"
                        required
                    >
                        <option value="">
                            -- Select Rating --
                        </option>

                        <option value="5" {{ old('Rating') == 5 ? 'selected' : '' }}>
                            ★★★★★ - Excellent
                        </option>

                        <option value="4" {{ old('Rating') == 4 ? 'selected' : '' }}>
                            ★★★★☆ - Very Good
                        </option>

                        <option value="3" {{ old('Rating') == 3 ? 'selected' : '' }}>
                            ★★★☆☆ - Good
                        </option>

                        <option value="2" {{ old('Rating') == 2 ? 'selected' : '' }}>
                            ★★☆☆☆ - Fair
                        </option>

                        <option value="1" {{ old('Rating') == 1 ? 'selected' : '' }}>
                            ★☆☆☆☆ - Poor
                        </option>
                    </select>
                </div>

                {{-- Comments --}}
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">
                        Comments
                    </label>

                    <textarea
                        name="Comments"
                        class="form-control"
                        rows="5"
                        maxlength="500"
                        placeholder="Enter customer feedback..."
                    >{{ old('Comments') }}</textarea>
                </div>

            </div>

            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Create Feedback
                </button>

                <a
                    href="{{ route('feedbacks.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection