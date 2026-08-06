@extends('layouts.app.app')

@section('title', 'Đăng nhập tài khoản - ZenStyle')

@section('content')

<div class="hero-wrap"
     style="background-image: url('{{ asset('frontend/images/bg_4.jpg') }}'); height: 300px; min-height: 300px;">
    <div class="overlay"></div>

    <div class="container">
        <div class="row no-gutters slider-text align-items-end" style="height: 300px;">
            <div class="col-md-12 ftco-animate pb-5">
                <p class="breadcrumbs">
                    <span class="mr-2">
                        <a href="{{ route('home') }}">
                            Trang chủ <i class="ion-ios-arrow-forward"></i>
                        </a>
                    </span>

                    <span>Đăng nhập</span>
                </p>

                <h1 class="mb-0 bread">Đăng Nhập Tài Khoản</h1>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST"
                      action="{{ route('login.store') }}"
                      class="appointment-form">

                    @csrf

                    <div class="form-group">
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control"
                            placeholder="Email *"
                            autocomplete="email"
                            style="border:1px solid #e6e6e6 !important; color:#000 !important;"
                        >
                    </div>

                    <div class="form-group">
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Mật khẩu *"
                            autocomplete="current-password"
                            style="border:1px solid #e6e6e6 !important; color:#000 !important;"
                        >
                    </div>

                    <div class="form-group mb-3 text-center">
    <button type="submit" class="btn btn-primary py-3 px-5">
        Đăng nhập
    </button>
</div>
                    <div class="text-center">
                        <span>Chưa có tài khoản?</span>

                        <a href="{{ route('register') }}">
                            Đăng ký ngay
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

@endsection