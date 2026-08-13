@extends('layouts.receptionist.app')

@section('title', 'Promotions')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Promotions</h1>
            <p class="text-muted mb-0">Manage active promotion campaigns.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.promotions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Create promotion
            </a>
        </div>
    </div>

    <div class="panel">
        @if ($promotions->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                No promotions available.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Promotion</th>
                            <th>Applies to</th>
                            <th>Discount</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($promotions as $promotion)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $promotion->Title }}</div>
                                    <div class="text-muted small">{{ \Illuminate\Support\Str::limit($promotion->Description, 60) }}</div>
                                </td>
                                <td>{{ $promotion->service->ServiceName ?? 'All services' }}</td>
                                <td>
                                    {{ $promotion->DiscountValue }}
                                    {{ $promotion->DiscountType === 'Percent' ? '%' : 'đ' }}
                                </td>
                                <td>
                                    {{ $promotion->StartDate?->format('d/m/Y') }}
                                    &rarr;
                                    {{ $promotion->EndDate?->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if ($promotion->IsActive)
                                        <span class="badge text-bg-success">Active</span>
                                    @else
                                        <span class="badge text-bg-secondary">Paused</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('receptionist.promotions.edit', $promotion) }}" class="btn btn-sm btn-light" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('receptionist.promotions.destroy', $promotion) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this promotion?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Xoá">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $promotions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
