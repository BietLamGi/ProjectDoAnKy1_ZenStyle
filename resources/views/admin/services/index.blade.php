@extends('layouts.admin.admin')

@section('content')

<div class="page-header">
    <div>
        <h1>Services</h1>
        <p>Manage salon services</p>
    </div>

    <a href="{{ route('services.create') }}" class="btn btn-primary">
        + Add Service
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">

    <div class="card-header">
        <strong>Service List</strong>

        <span>
            Total: {{ $services->total() }}
        </span>
    </div>

    <div class="table-responsive">

        <table class="table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Service Name</th>
                    <th>Description</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($services as $service)

                    <tr>

                        <td>
                            {{ $service->ServiceID }}
                        </td>

                        <td>
                            {{ $service->ServiceType ? 'Service' : 'Product' }}
                        </td>

                        <td>
                            {{ $service->ServiceName }}
                        </td>

                        <td>
                            {{ $service->Description ?? '-' }}
                        </td>

                        <td>
                            {{ $service->DurationMinutes }} min
                        </td>
<td>
    @if($service->activePromotion)

        <div>
            <span class="text-muted text-decoration-line-through">
                {{ number_format($service->Price, 0, ',', '.') }}đ
            </span>
        </div>

        <div>
            <strong class="text-danger">
                {{ number_format($service->discounted_price, 0, ',', '.') }}đ
            </strong>
        </div>

        @if($service->activePromotion->DiscountType === 'Percent')

            <span class="badge bg-danger">
                -{{ $service->activePromotion->DiscountValue }}%
            </span>

        @elseif($service->activePromotion->DiscountType === 'Fixed')

            <span class="badge bg-danger">
                -{{ number_format($service->activePromotion->DiscountValue, 0, ',', '.') }}đ
            </span>

        @endif

    @else

        <strong>
            {{ number_format($service->Price, 0, ',', '.') }}đ
        </strong>

    @endif
</td>

                        <td>

                            @if($service->IsActive)
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

                            <a href="{{ route('services.edit', $service->ServiceID) }}"
                               class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>

                            <form action="{{ route('services.destroy', $service->ServiceID) }}"
                                  method="POST"
                                  style="display:inline">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this service?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No services found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
    <div class="pagination">
        {{ $services->links() }}
    </div>

</div>
@endsection