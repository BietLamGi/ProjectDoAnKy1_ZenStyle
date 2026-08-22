@extends('layouts.admin.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h1 class="fw-bold">Promotions</h1>
        <p class="text-muted">Manage salon promotions</p>
    </div>

    <a href="{{ route('promotions.create') }}"
       class="btn btn-primary">
        + Create Promotion
    </a>

</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">

            <h5 class="fw-bold mb-0">
                Promotion List
            </h5>

            <span class="text-muted">
                Total: {{ $promotions->total() }}
            </span>

        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Service</th>
                        <th>Discount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($promotions as $promotion)

                        <tr>

                            <td>
                                {{ $promotion->PromotionID }}
                            </td>

                            <td>
                                <strong>
                                    {{ $promotion->Title }}
                                </strong>
                            </td>

                            <td>
                                {{ $promotion->service->ServiceName ?? 'All Services' }}
                            </td>

                            <td>

                                {{ $promotion->DiscountValue }}

                                @if($promotion->DiscountType === 'Percent')
                                    %
                                @endif

                            </td>

                            <td>
                                {{ $promotion->StartDate?->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ $promotion->EndDate?->format('d/m/Y') }}
                            </td>

                            <td>

                                @if($promotion->IsActive)

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td>

                                <a
                                    href="{{ route('promotions.edit', $promotion->PromotionID) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    ✏️
                                </a>

                                <form
                                    action="{{ route('promotions.destroy', $promotion->PromotionID) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this promotion?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        🗑️
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="text-center py-4"
                            >
                                No promotions found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="mt-3">
            {{ $promotions->links() }}
        </div>

    </div>

</div>

@endsection