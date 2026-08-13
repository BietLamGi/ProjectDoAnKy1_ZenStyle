@extends('layouts.admin.admin')

@section('content')

<div class="page-header">
    <div>
        <h1>Add Service</h1>
        <p>Create a new salon service</p>
    </div>
</div>

<div class="card">

    <form action="{{ route('services.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label>Service Name</label>

            <input type="text"
                   name="ServiceName"
                   class="form-control"
                   value="{{ old('ServiceName') }}"
                   required>

            @error('ServiceName')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="mb-3">

            <label>Service Type</label>

            <select name="ServiceType" class="form-control" required>

                <option value="1"
                    {{ old('ServiceType') == '1' ? 'selected' : '' }}>
                    Service
                </option>

                <option value="0"
                    {{ old('ServiceType') == '0' ? 'selected' : '' }}>
                    Other
                </option>

            </select>

        </div>


        <div class="mb-3">

            <label>Description</label>

            <textarea name="Description"
                      class="form-control"
                      rows="4">{{ old('Description') }}</textarea>

        </div>


        <div class="mb-3">

            <label>Duration (minutes)</label>

            <input type="number"
                   name="DurationMinutes"
                   class="form-control"
                   min="1"
                   value="{{ old('DurationMinutes', 30) }}"
                   required>

        </div>


        <div class="mb-3">

            <label>Price</label>

            <input type="number"
                   name="Price"
                   class="form-control"
                   min="0"
                   step="0.01"
                   value="{{ old('Price') }}"
                   required>

        </div>


        <div class="mb-3">

            <label>
                <input type="checkbox"
                       name="IsActive"
                       value="1"
                       checked>

                Active
            </label>

        </div>


        <button type="submit" class="btn btn-primary">
            Save Service
        </button>

        <a href="{{ route('services.index') }}"
           class="btn btn-secondary">
            Cancel
        </a>

    </form>

</div>

@endsection