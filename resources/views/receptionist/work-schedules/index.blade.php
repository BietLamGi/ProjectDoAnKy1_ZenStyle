@extends('layouts.receptionist.app')

@section('title', 'Lịch làm việc')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Lịch làm việc nhân viên</h1>
            <p class="text-muted mb-0">Theo dõi ca làm, giờ vào/ra thực tế của nhân viên.</p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.work-schedules.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Thêm lịch làm việc
            </a>
        </div>
    </div>

    <div class="panel">
        @if ($schedules->isEmpty())
            <div class="blank-panel blank-state text-center py-5 text-muted">
                Chưa có lịch làm việc nào.
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            <th>Ngày làm</th>
                            <th>Ca làm</th>
                            <th>Vào / Ra thực tế</th>
                            <th>Số giờ làm</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $schedule)
                            <tr>
                                <td class="fw-semibold">{{ $schedule->user->Username ?? 'N/A' }}</td>
                                <td>{{ $schedule->WorkDate?->format('d/m/Y') }}</td>
                                <td>{{ $schedule->ShiftStart }} - {{ $schedule->ShiftEnd }}</td>
                                <td>
                                    {{ $schedule->ActualCheckIn?->format('H:i d/m') ?? '—' }}
                                    /
                                    {{ $schedule->ActualCheckOut?->format('H:i d/m') ?? '—' }}
                                </td>
                                <td>{{ $schedule->WorkedHours !== null ? number_format($schedule->WorkedHours, 2) . ' h' : '—' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $schedule->Status === 'Completed' ? 'success' : ($schedule->Status === 'OnLeave' ? 'warning' : 'primary') }}">
                                        {{ $schedule->Status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('receptionist.work-schedules.edit', $schedule) }}" class="btn btn-sm btn-light" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('receptionist.work-schedules.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá lịch làm việc này?');">
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
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
