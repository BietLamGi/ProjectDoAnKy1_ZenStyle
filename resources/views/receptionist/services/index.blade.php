@extends('layouts.receptionist.app')

@section('title', 'Dịch vụ & Sản phẩm')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Dịch vụ &amp; Sản phẩm</h1>
            <p class="text-muted mb-0">Bảng giá tham khảo khi tư vấn và lập hoá đơn cho khách.</p>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ $type == '0' ? 'active' : '' }}" href="{{ route('receptionist.services.index', ['type' => 0]) }}">Dịch vụ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $type == '1' ? 'active' : '' }}" href="{{ route('receptionist.services.index', ['type' => 1]) }}">Sản phẩm</a>
                </li>
            </ul>

            <form class="d-flex" method="GET">
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="search" name="q" value="{{ $keyword }}" class="form-control table-search" placeholder="Tìm theo tên...">
                <button class="btn btn-light ms-2" type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>

        @if ($services->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                Không tìm thấy dịch vụ / sản phẩm nào.
            </div>
        @else
            @foreach ($services as $category => $items)
                <h6 class="mt-4 mb-2 text-uppercase text-muted">{{ $category }}</h6>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                @if ($type == '0')
                                    <th>Thời lượng</th>
                                @endif
                                <th class="text-end">Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $service)
                                <tr>
                                    <td class="fw-semibold">{{ $service->ServiceName }}</td>
                                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($service->Description, 80) }}</td>
                                    @if ($type == '0')
                                        <td>{{ $service->DurationMinutes }} phút</td>
                                    @endif
                                    <td class="text-end">{{ number_format($service->Price, 0, ',', '.') }}đ</td>
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
