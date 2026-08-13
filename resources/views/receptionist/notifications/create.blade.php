@extends('layouts.receptionist.app')

@section('title', 'Create notification')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create notification</h1>
            <p class="text-muted mb-0">Send a notification to one staff member or the entire system.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.notifications.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.notifications.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Recipient</label>
                    <select name="UserID" class="form-control">
                        <option value="">-- All users --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->UserID }}" @selected(old('UserID') == $user->UserID)>
                                {{ $user->Username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Notification type</label>
                    <input type="text" name="Type" class="form-control" maxlength="30" placeholder="E.g. info, reminder, alert..." value="{{ old('Type') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="Title" class="form-control" maxlength="150" value="{{ old('Title') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Message <span class="text-danger">*</span></label>
                    <textarea name="Message" class="form-control" rows="4" maxlength="500" required>{{ old('Message') }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i> Send notification
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
