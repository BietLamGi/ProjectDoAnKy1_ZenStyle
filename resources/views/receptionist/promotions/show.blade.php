@extends('layouts.receptionist.app')

@section('title', 'Promotion details')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>{{ $promotion->Title }}</h1>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.promotions.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="panel">
        <div class="info-list">
            <div><span>Applies to</span><strong>{{ $promotion->service->ServiceName ?? 'All services' }}</strong></div>
            <div><span>Discount</span><strong>{{ $promotion->DiscountValue }} {{ $promotion->DiscountType === 'Percent' ? '%' : 'đ' }}</strong></div>
            <div><span>Period</span><strong>{{ $promotion->StartDate?->format('d/m/Y') }} &rarr; {{ $promotion->EndDate?->format('d/m/Y') }}</strong></div>
            <div><span>Status</span><strong>{{ $promotion->IsActive ? 'Active' : 'Paused' }}</strong></div>
            <div><span>Description</span><strong>{{ $promotion->Description ?: '—' }}</strong></div>
        </div>
    </div>

</div>
@endsection
