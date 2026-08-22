@extends('layouts.receptionist.app')

@section('title', 'Edit promotion')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Edit promotion</h1>
            <p class="text-muted mb-0">{{ $promotion->Title }}</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.promotions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.promotions.update', $promotion) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Program Name <span class="text-danger">*</span></label>
                    <input type="text" name="Title" class="form-control" maxlength="100" value="{{ old('Title', $promotion->Title) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Applies to service</label>
                    <select name="ServiceID" class="form-control">
                        <option value="">-- All services --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->ServiceID }}" @selected(old('ServiceID', $promotion->ServiceID) == $service->ServiceID)>
                                {{ $service->ServiceName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount type <span class="text-danger">*</span></label>
                    <select name="DiscountType" class="form-control" required>
                        <option value="Percent" @selected(old('DiscountType', $promotion->DiscountType) == 'Percent')>Percent (%)</option>
                        <option value="Fixed" @selected(old('DiscountType', $promotion->DiscountType) == 'Fixed')>Fixed amount</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" name="DiscountValue" class="form-control" step="0.01" min="0" value="{{ old('DiscountValue', $promotion->DiscountValue) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label d-block">&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="IsActive" {{ old('IsActive', $promotion->IsActive) ? 'checked' : '' }}>
                        <label class="form-check-label" for="IsActive">Apply immediately</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="StartDate" class="form-control" value="{{ old('StartDate', $promotion->StartDate?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" name="EndDate" class="form-control" value="{{ old('EndDate', $promotion->EndDate?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="Description" class="form-control" rows="3" maxlength="500">{{ old('Description', $promotion->Description) }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Update
                </button>
            </div>
        </form>

        <form action="{{ route('receptionist.promotions.destroy', $promotion) }}" method="POST" onsubmit="return confirm('Delete this promotion?');" class="mt-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete promotion
            </button>
        </form>
    </div>

</div>
@endsection
