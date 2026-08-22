@extends('layouts.receptionist.app')

@section('title', 'Services & Products')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Services &amp; Products</h1>
            <p class="text-muted mb-0">Reference pricing for customer consultation and invoicing.</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ $type == '0' ? 'active' : '' }}"
                        href="{{ route('receptionist.services.index', ['type' => 0]) }}">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == '1' ? 'active' : '' }}"
                        href="{{ route('receptionist.services.index', ['type' => 1]) }}">Products</a>
                </li>
            </ul>

            <form class="d-flex" method="GET">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search"
                    placeholder="Search by name...">
                <button class="btn btn-light ms-2" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        @if ($services->isEmpty())
        <div class="blank-panel blank-state text-center py-5 text-muted">
            No services or products found.
        </div>
        @else
        @foreach ($services as $category => $items)
        <h6 class="mt-4 mb-2 text-uppercase text-muted">{{ $category }}</h6>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        @if ($type == '0')
                        <th>Duration</th>
                        @endif
                        <th class="text-end">Price</th>
                        <th>Promotion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $service)
                    <tr>
                        <td class="fw-semibold">{{ $service->ServiceName }}</td>
                        <td class="text-muted">{{ \Illuminate\Support\Str::limit($service->Description, 80) }}</td>
                        @if ($type == '0')
                        <td>{{ $service->DurationMinutes }} min</td>
                        @endif
                        <td class="text-end">
                            @if ($service->activePromotion)
                            <span class="text-muted text-decoration-line-through small d-block">
                                {{ number_format($service->Price, 0, ',', '.') }}đ
                            </span>
                            <strong class="text-danger">
                                {{ number_format($service->discounted_price, 0, ',', '.') }}đ
                            </strong>
                            @else
                            {{ number_format($service->Price, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>
                            @if ($service->activePromotion)
                            <span class="badge bg-danger">
                                {{ $service->activePromotion->Title }}
                                ({{ $service->activePromotion->DiscountType === 'Percent'
                                                    ? $service->activePromotion->DiscountValue . '%'
                                                    : number_format($service->activePromotion->DiscountValue, 0, ',', '.') . 'đ' }})
                            </span>
                            @else
                            <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
        @endif
    </div>

</div>
@endsection