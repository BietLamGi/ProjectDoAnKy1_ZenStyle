@extends('layouts.app.app')

@section('title', 'Create an account - ZenStyle')

@section('content')

<div class="hero-wrap" style="background-image: url('{{ asset('frontend/images/bg_4.jpg') }}'); height: 300px; min-height: 300px;">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-end" style="height: 300px;">
            <div class="col-md-12 ftco-animate pb-5">
                <p class="breadcrumbs">
                    <span class="mr-2"><a href="{{ route('home') }}">Home Page <i class="ion-ios-arrow-forward"></i></a></span>
                    <span>Sign up</span>
                </p>
                <h1 class="mb-0 bread">Create an account
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

                <form method="POST" action="{{ route('register.store') }}" class="appointment-form">
                    @csrf

                    <div class="form-group">
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control" placeholder="Your fullname *"
                               style="border:1px solid #e6e6e6 !important; color:#000 !important;">
                    </div>

                    <div class="form-group">
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control" placeholder="Email *"
                               style="border:1px solid #e6e6e6 !important; color:#000 !important;">
                    </div>

                    <div class="form-group">
                        <input type="password" name="password"
                               class="form-control" placeholder="Password *"
                               style="border:1px solid #e6e6e6 !important; color:#000 !important;">
                    </div>

                    <div class="form-group">
                        <input type="password" name="password_confirmation"
                               class="form-control" placeholder="Confirm password *"
                               style="border:1px solid #e6e6e6 !important; color:#000 !important;">
                    </div>

                    <div class="form-group mb-0 d-flex justify-content-center">
    <button type="submit" class="btn btn-primary py-3 px-5">
        Sign Up
    </button>
</div>
                </form>
                <div class="text-center mt-3">
    <p>
        Already have an account?
        <a href="{{ route('login') }}">Log in</a>
    </p>
</div>

            </div>
        </div>
    </div>
</section>

@endsection
