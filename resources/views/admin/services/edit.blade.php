@extends('layouts.admin.admin')

@section('content')

<div class="page-header">
    <div>
        <h1>Edit Service</h1>
        <p>Update service information</p>
    </div>
</div>

<div class="card">

    <form action="{{ route('services.update', $service->ServiceID) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">

            <label>Service Name</label>

            <input type="text"
                   name="ServiceName"
                   class="form-control"
                   value="{{ old('ServiceName', $service->ServiceName) }}"
                   required>

        </div>


        <div class="mb-3">

            <label>Service Type</label>

            <select name="ServiceType"
                    class="form-control"
                    required>

                <option value="1"
                    {{ $service->ServiceType == 1 ? 'selected' : '' }}>
                    Service
                </option>

                <option value="0"
                    {{ $service->ServiceType == 0 ? 'selected' : '' }}>
                    Other
                </option>

            </select>

        </div>


        <div class="mb-3">

            <label>Description</label>

            <textarea name="Description"
                      class="form-control"
                      rows="4">{{ old('Description', $service->Description) }}</textarea>

        </div>


        <div class="mb-3">

            <label>Duration (minutes)</label>

            <input type="number"
                   name="DurationMinutes"
                   class="form-control"
                   min="1"
                   value="{{ old('DurationMinutes', $service->DurationMinutes) }}"
                   required>

        </div>


        <div class="mb-3">

            <label>Price</label>

            <input type="number"
                   name="Price"
                   class="form-control"
                   min="0"
                   step="0.01"
                   value="{{ old('Price', $service->Price) }}"
                   required>

        </div>


        <div class="mb-3">

            <label>

                <input type="checkbox"
                       name="IsActive"
                       value="1"
                       {{ $service->IsActive ? 'checked' : '' }}>

                Active

            </label>

        </div>


        <button type="submit"
                class="btn btn-primary">
            Update Service
        </button>

        <a href="{{ route('services.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection