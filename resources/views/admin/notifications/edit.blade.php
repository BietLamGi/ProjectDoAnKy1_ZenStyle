@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold">Edit Notification</h1>
        <p class="text-muted">Update notification information</p>
    </div>

    <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">

        <form
            action="{{ route('notifications.update', $notification->NotificationID) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            {{-- User --}}
            <div class="mb-3">
                <label class="form-label fw-bold">
                    Recipient
                </label>

                <select name="UserID" class="form-select">
                    <option value="">All Users</option>

                    @foreach($users as $user)
                        <option
                            value="{{ $user->UserID }}"
                            {{ old('UserID', $notification->UserID) == $user->UserID ? 'selected' : '' }}
                        >
                            {{ $user->Username }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Title --}}
            <div class="mb-3">
                <label class="form-label fw-bold">
                    Title
                </label>

                <input
                    type="text"
                    name="Title"
                    class="form-control"
                    value="{{ old('Title', $notification->Title) }}"
                    maxlength="150"
                    required
                >
            </div>

            {{-- Message --}}
            <div class="mb-3">
                <label class="form-label fw-bold">
                    Message
                </label>

                <textarea
                    name="Message"
                    class="form-control"
                    rows="5"
                    maxlength="500"
                    required
                >{{ old('Message', $notification->Message) }}</textarea>
            </div>

            {{-- Type --}}
            <div class="mb-3">
                <label class="form-label fw-bold">
                    Type
                </label>

                <select name="Type" class="form-select">
                    <option value="">Select type</option>

                    <option
                        value="appointment"
                        {{ old('Type', $notification->Type) == 'appointment' ? 'selected' : '' }}
                    >
                        Appointment
                    </option>

                    <option
                        value="feedback"
                        {{ old('Type', $notification->Type) == 'feedback' ? 'selected' : '' }}
                    >
                        Feedback
                    </option>

                    <option
                        value="invoice"
                        {{ old('Type', $notification->Type) == 'invoice' ? 'selected' : '' }}
                    >
                        Invoice
                    </option>

                    <option
                        value="promotion"
                        {{ old('Type', $notification->Type) == 'promotion' ? 'selected' : '' }}
                    >
                        Promotion
                    </option>

                    <option
                        value="system"
                        {{ old('Type', $notification->Type) == 'system' ? 'selected' : '' }}
                    >
                        System
                    </option>
                </select>
            </div>

            {{-- Read status --}}
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Status
                </label>

                <select name="IsRead" class="form-select">
                    <option
                        value="0"
                        {{ old('IsRead', $notification->IsRead) == 0 ? 'selected' : '' }}
                    >
                        Unread
                    </option>

                    <option
                        value="1"
                        {{ old('IsRead', $notification->IsRead) == 1 ? 'selected' : '' }}
                    >
                        Read
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    Update Notification
                </button>

                <a
                    href="{{ route('notifications.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>
</div>

@endsection