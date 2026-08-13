@extends('layouts.receptionist.app')

@section('title', 'Create promotion')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Create promotion</h1>
            <p class="text-muted mb-0">Set up a new promotion campaign.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.promotions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.promotions.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tên chương trình <span class="text-danger">*</span></label>
                    <input type="text" name="Title" class="form-control" maxlength="100" value="{{ old('Title') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Applies to service</label>
                    <select name="ServiceID" class="form-control">
                        <option value="">-- All services --</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->ServiceID }}" @selected(old('ServiceID') == $service->ServiceID)>
                                {{ $service->ServiceName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Discount type <span class="text-danger">*</span></label>
                    <select name="DiscountType" class="form-control" required>
                        <option value="Percent" @selected(old('DiscountType', 'Percent') == 'Percent')>Percent (%)</option>
                        <option value="Fixed" @selected(old('DiscountType') == 'Fixed')>Fixed amount</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                    <input type="number" name="DiscountValue" class="form-control" step="0.01" min="0" value="{{ old('DiscountValue', 0) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label d-block">&nbsp;</label>
                    <div class="form-check">
                        <input type="checkbox" name="IsActive" value="1" class="form-check-input" id="IsActive" {{ old('IsActive', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="IsActive">Apply immediately</label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" name="StartDate" class="form-control" value="{{ old('StartDate') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" name="EndDate" class="form-control" value="{{ old('EndDate') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="Description" class="form-control" rows="3" maxlength="500">{{ old('Description') }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create promotion
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
