@extends('layouts.receptionist.app')

@section('title', 'Sửa khách hàng')

@section('content')
<div class="container-fluid">

    <div class="page-heading">
        <div>
            <span class="eyebrow">Reception</span>
            <h1>Cập nhật khách hàng</h1>
        </div>
        <div class="heading-actions">
            <a href="{{ route('receptionist.customers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="panel">
        <form method="POST" action="{{ route('receptionist.customers.update', $customer) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="FullName" class="form-control" value="{{ old('FullName', $customer->FullName) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" name="Phone" class="form-control" value="{{ old('Phone', $customer->Phone) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="Email" class="form-control" value="{{ old('Email', $customer->Email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="DOB" class="form-control" value="{{ old('DOB', $customer->DOB) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Hạng thành viên</label>
                    <select name="MembershipTier" class="form-control">
                        @foreach (['Normal', 'Silver', 'Gold', 'VIP'] as $tier)
                            <option value="{{ $tier }}" @selected(old('MembershipTier', $customer->MembershipTier) === $tier)>{{ $tier }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Dị ứng (nếu có)</label>
                    <input type="text" name="Allergies" class="form-control" value="{{ old('Allergies', $customer->Allergies) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="Notes" class="form-control" rows="3">{{ old('Notes', $customer->Notes) }}</textarea>
                </div>
            </div>

            <div class="heading-actions mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
