@extends('layouts.staff.staff')

@section('title', 'Request Leave')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="mb-1">
            Request Leave
        </h2>

        <p class="text-muted">
            Submit a leave request for approval.
        </p>

    </div>


    <div class="card shadow-sm border-0">

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form method="POST"
                  action="{{ route('staff.leave-requests.store') }}">

                @csrf


                <div class="row">

    <div class="col-md-6 mb-3">
        <label class="form-label">
            Start Date
        </label>

        <input type="date"
               name="LeaveStartDate"
               class="form-control"
               value="{{ old('LeaveStartDate') }}"
               min="{{ now()->format('Y-m-d') }}"
               required>
    </div>


    <div class="col-md-6 mb-3">
        <label class="form-label">
            End Date
        </label>

        <input type="date"
               name="LeaveEndDate"
               class="form-control"
               value="{{ old('LeaveEndDate') }}"
               min="{{ now()->format('Y-m-d') }}"
               required>
    </div>

</div>


                <div class="mb-4">

                    <label class="form-label">
                        Reason
                    </label>

                    <textarea name="Reason"
                              rows="4"
                              class="form-control"
                              maxlength="500"
                              placeholder="Enter the reason for your leave...">{{ old('Reason') }}</textarea>

                </div>


                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="bi bi-send me-1"></i>

                        Submit Request

                    </button>


                    <a href="{{ route('staff.leave-requests.index') }}"
                       class="btn btn-light">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection