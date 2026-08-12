@extends('layouts.receptionist.app')

@section('title', 'Edit customer')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Update customer</h1>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.customers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Full name <span class="text-danger">*</span></label>
                    <input type="text" name="FullName" class="form-control" value="{{ old('FullName', $customer->FullName) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone number <span class="text-danger">*</span></label>
                    <input type="text" name="Phone" class="form-control" value="{{ old('Phone', $customer->Phone) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="Email" class="form-control" value="{{ old('Email', $customer->Email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date of birth</label>
                    <input type="date" name="DOB" class="form-control" value="{{ old('DOB', $customer->DOB) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Membership tier</label>
                    <select name="MembershipTier" class="form-control">
                        @foreach (['Normal', 'Silver', 'Gold', 'VIP'] as $tier)
                            <option value="{{ $tier }}" @selected(old('MembershipTier', $customer->MembershipTier) === $tier)>{{ $tier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Allergies (optional)</label>
                    <input type="text" name="Allergies" class="form-control" value="{{ old('Allergies', $customer->Allergies) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Notes</label>
                    <textarea name="Notes" class="form-control" rows="3">{{ old('Notes', $customer->Notes) }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
