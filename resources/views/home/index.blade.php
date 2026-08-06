@extends('layouts.app.app')

@section('title', 'ZenStyle Salon & Spa - Trang chủ')

@section('content')

{{-- Thông báo đặt lịch thành công --}}
@if (session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
@endif

<!-- ============ HOME / HERO ============ -->
<div id="home" class="hero-wrap js-fullheight" style="background-image: url('{{ asset('frontend/images/spa1.jpg') }}');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center" data-animate-effect="fadeInUp">
                <h1 class="mb-3">ZenStyle Salon &amp; Spa</h1>
                <p class="mb-4">
                    Không gian thư giãn - làm đẹp trọn vẹn. Nơi vẻ đẹp của bạn được chăm chút
                    bởi đội ngũ chuyên gia giàu kinh nghiệm cùng dịch vụ tận tâm.
                </p>
                <p>
                    <a href="#booking" class="btn btn-primary px-4 py-3 mr-2">Đặt lịch ngay</a>
                    <a href="#services" class="btn btn-white btn-outline-white px-4 py-3">Xem dịch vụ</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ============ ABOUT (Giới thiệu về chúng tôi) ============ -->
<section id="about" class="ftco-section">
    <div class="container">
        <div class="row d-flex">

            <div class="col-md-6 d-flex align-items-stretch ftco-animate" data-animate-effect="fadeInLeft">
                <div class="img img-about align-self-stretch"
                    style="background-image: url('{{ asset('frontend/images/skin3.jpg') }}');
                            min-height: 420px; width: 100%; border-radius: 6px;">
                </div>
            </div>

            <div class="col-md-6 pl-md-5 py-5 ftco-animate" data-animate-effect="fadeInRight">
                <div class="heading-section">
                    <span class="subheading">Giới thiệu</span>
                    <h2 class="mb-4">Về Chúng Tôi</h2>
                </div>

                <p>
                    ZenStyle là salon tóc &amp; spa chăm sóc sắc đẹp, đồng hành cùng hàng ngàn
                    khách hàng trên hành trình làm đẹp mỗi ngày. Chúng tôi kết hợp tay nghề
                    chuyên môn với không gian thư giãn để mang lại trải nghiệm trọn vẹn nhất.
                </p>
                <p>
                    Đội ngũ chuyên gia được đào tạo bài bản, hệ thống trang thiết bị hiện đại
                    cùng các dòng mỹ phẩm cao cấp, an toàn cho mọi loại da và tóc.
                </p>

                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Đội ngũ chuyên gia giàu kinh nghiệm</li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Sản phẩm, mỹ phẩm chính hãng, an toàn</li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Không gian sang trọng, riêng tư</li>
                    <li class="mb-2"><span class="icon-check mr-2 text-primary"></span> Đặt lịch nhanh chóng, linh hoạt</li>
                </ul>

                <p class="mt-4">
                    <a href="#services" class="btn btn-primary px-4 py-3">Khám phá dịch vụ</a>
                </p>
            </div>

        </div>
    </div>
</section>

<!-- ============ COUNTER (thống kê nhanh) ============ -->
<section id="section-counter" class="ftco-counter img"
    style="background-image: url('{{ asset('frontend/images/skin2.jpg') }}');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row d-flex justify-content-center">

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="flaticon-cosmetics"></span></div>
                    <div class="text">
                        <strong class="number" data-number="8">0</strong>
                        <span>Năm kinh nghiệm</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-users"></span></div>
                    <div class="text">
                        <strong class="number" data-number="3200">0</strong>
                        <span>Khách hàng hài lòng</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-user"></span></div>
                    <div class="text">
                        <strong class="number" data-number="15">0</strong>
                        <span>Chuyên gia</span>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 text-center d-flex">
                <div class="counter-wrap ftco-animate w-100">
                    <div class="icon"><span class="icon-star"></span></div>
                    <div class="text">
                        <strong class="number" data-number="20">0</strong>
                        <span>Dịch vụ</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============ SERVICES (Dịch vụ cung cấp) ============ -->
<section id="services" class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <!-- <span class="subheading">Dịch vụ</span> -->
                <h2 class="mb-4">Dịch Vụ Cung Cấp</h2>
                <p>
                    Đa dạng dịch vụ chăm sóc tóc, da và spa thư giãn, đáp ứng mọi nhu cầu
                    làm đẹp của bạn.
                </p>
            </div>
        </div>

        <div class="row">
            @foreach ($services as $key => $service)
            <div class="col-md-4 d-flex align-self-stretch ftco-animate">
                <div class="services w-100 text-center p-4 mb-4">
                    <div class="icon d-flex justify-content-center align-items-center mb-3">
                        <span class="{{ $service['icon'] }}"></span>
                    </div>
                    <h3>{{ $service['name'] }}</h3>
                    <p class="mb-2">{{ $service['desc'] }}</p>
                    <p class="mb-3"><strong class="text-primary">{{ $service['price'] }}</strong></p>
                    <a href="#booking" class="btn btn-outline-primary btn-sm px-3"
                        onclick="var s=document.getElementById('service'); if(s){ s.value='{{ $key }}'; }">
                        Đặt lịch
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- ============ BOOKING (Đặt lịch) ============ -->
<section id="booking" class="ftco-appointment img"
    style="background-image: url('{{ asset('frontend/images/footer.jpg') }}');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section heading-section-white text-center ftco-animate">
                <!-- <span class="subheading">Đặt lịch</span> -->
                <h2 class="mb-4">Đặt Lịch Hẹn</h2>
                <p>
                    Để lại họ tên, số điện thoại và dịch vụ bạn quan tâm - chúng tôi sẽ liên hệ
                    xác nhận lịch hẹn với bạn trong thời gian sớm nhất.
                </p>
            </div>
        </div>

        <div class="row justify-content-center">

            <div class="col-md-4">
                <div class="appointment-info mb-4 mb-md-0 p-4">
                    <h3 class="mb-4">Thông tin liên hệ</h3>

                    <p class="day d-flex justify-content-between">
                        <span>Thứ 2 - Thứ 6</span><span>9:00 - 17:00</span>
                    </p>
                    <p class="day d-flex justify-content-between">
                        <span>Thứ 7 - Chủ nhật</span><span>9:00 - 18:00</span>
                    </p>

                    <p class="mt-4 mb-1"><span class="icon-phone mr-2"></span> 0909 123 456</p>
                    <p class="mb-0"><span class="icon-envelope mr-2"></span> info@zenstyle.vn</p>
                </div>
            </div>

            <div class="col-md-7 ml-md-5">
                <form class="appointment-form" method="POST" action="{{ route('booking.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="fullname" value="{{ old('fullname') }}"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    placeholder="Họ và tên *">
                                @error('fullname')
                                <small class="d-block mt-1" style="color:#ff8fae;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="Số điện thoại *">
                                @error('phone')
                                <small class="d-block mt-1" style="color:#ff8fae;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="select-wrap">
                                    <select name="service" id="service"
                                        class="form-control @error('service') is-invalid @enderror">
                                        <option value="">-- Chọn dịch vụ * --</option>
                                        @foreach ($services as $key => $service)
                                        <option value="{{ $key }}" {{ old('service') == $key ? 'selected' : '' }}>
                                            {{ $service['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('service')
                                <small class="d-block mt-1" style="color:#ff8fae;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-wrap">
                                    <input type="text" id="appointment_date" name="appointment_date"
                                        value="{{ old('appointment_date') }}" autocomplete="off"
                                        class="form-control" placeholder="Ngày hẹn">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-wrap">
                                    <input type="text" id="appointment_time" name="appointment_time"
                                        value="{{ old('appointment_time') }}" autocomplete="off"
                                        class="form-control" placeholder="Giờ hẹn">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea name="note" rows="3" class="form-control"
                            placeholder="Ghi chú thêm (nếu có)">{{ old('note') }}</textarea>
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary py-3 px-5">Gửi đặt lịch</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection