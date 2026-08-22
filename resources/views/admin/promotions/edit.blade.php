@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">Edit Promotion</h1>
        <p class="text-muted">Update promotion information</p>
    </div>

    <a
        href="{{ route('promotions.index') }}"
        class="btn btn-secondary"
    >
        ← Back to Promotions
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

        <form
            action="{{ route('promotions.update', $promotion->PromotionID) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Title
                    </label>

                    <input
                        type="text"
                        name="Title"
                        class="form-control"
                        maxlength="100"
                        value="{{ old('Title', $promotion->Title) }}"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Service
                    </label>

                    <select
                        name="ServiceID"
                        class="form-select"
                    >

                        <option value="">
                            All Services
                        </option>

                        @foreach($services as $service)

                            <option
                                value="{{ $service->ServiceID }}"
                                {{ old('ServiceID', $promotion->ServiceID) == $service->ServiceID ? 'selected' : '' }}
                            >
                                {{ $service->ServiceName }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Discount Type
                    </label>

                    <select
                        name="DiscountType"
                        class="form-select"
                        required
                    >

                        <option
                            value="Percent"
                            {{ old('DiscountType', $promotion->DiscountType) == 'Percent' ? 'selected' : '' }}
                        >
                            Percentage
                        </option>

                        <option
                            value="Fixed"
                            {{ old('DiscountType', $promotion->DiscountType) == 'Fixed' ? 'selected' : '' }}
                        >
                            Fixed Amount
                        </option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Discount Value
                    </label>

                    <input
                        type="number"
                        name="DiscountValue"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('DiscountValue', $promotion->DiscountValue) }}"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        Start Date
                    </label>

                    <input
                        type="date"
                        name="StartDate"
                        class="form-control"
                        value="{{ old('StartDate', $promotion->StartDate?->format('Y-m-d')) }}"
                        required
                    >

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label fw-bold">
                        End Date
                    </label>

                    <input
                        type="date"
                        name="EndDate"
                        class="form-control"
                        value="{{ old('EndDate', $promotion->EndDate?->format('Y-m-d')) }}"
                        required
                    >

                </div>

                <div class="col-12 mb-3">

                    <label class="form-label fw-bold">
                        Description
                    </label>

                    <textarea
                        name="Description"
                        class="form-control"
                        rows="4"
                        maxlength="500"
                    >{{ old('Description', $promotion->Description) }}</textarea>

                </div>

                <div class="col-12 mb-3">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="IsActive"
                            value="1"
                            class="form-check-input"
                            id="IsActive"
                            {{ old('IsActive', $promotion->IsActive) ? 'checked' : '' }}
                        >

                        <label
                            class="form-check-label"
                            for="IsActive"
                        >
                            Active Promotion
                        </label>

                    </div>

                </div>

            </div>

            <div class="mt-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Promotion
                </button>

                <a
                    href="{{ route('promotions.index') }}"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection