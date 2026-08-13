@extends('layouts.app.app')

@section('title', 'Edit Profile')

@section('styles')
    <link rel="stylesheet"
          href="{{ asset('frontend/css/profile-edit.css') }}">
@endsection

@section('content')

<div class="edit-profile-page">

    <div class="edit-profile-header">
        <span>ZENSTYLE SALON & SPA</span>
        <h2>Edit Profile</h2>
        <p>Update your personal information.</p>
    </div>


    <div class="edit-profile-card">

        {{-- HEADER --}}
        <div class="edit-card-header">

            <div class="edit-avatar">
                <i class="icon-user"></i>
            </div>

            <div>
                <h3>Edit Personal Information</h3>
                <p>Keep your account information up to date.</p>
            </div>

        </div>


        {{-- ERROR --}}
        @if ($errors->any())

            <div class="edit-error">

                <div class="edit-error-icon">
                    <i class="icon-warning"></i>
                </div>

                <div>
                    <strong>Please check the following:</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        @endif


        {{-- FORM --}}
        <form method="POST" action="{{ route('profile.update') }}">

            @csrf
            @method('PUT')


            <div class="edit-form-grid">

                {{-- USERNAME --}}
                <div class="edit-form-group">

                    <label>
                        <i class="icon-user"></i>
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        value="{{ old('username', $user->Username) }}"
                        class="edit-form-control"
                        placeholder="Enter your username"
                    >

                </div>


                {{-- FULL NAME --}}
                <div class="edit-form-group">

                    <label>
                        <i class="icon-id-card"></i>
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="fullname"
                        value="{{ old('fullname', $customer->FullName ?? '') }}"
                        class="edit-form-control"
                        placeholder="Enter your full name"
                    >

                </div>


                {{-- EMAIL --}}
                <div class="edit-form-group">

                    <label>
                        <i class="icon-envelope"></i>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->Email) }}"
                        class="edit-form-control"
                        placeholder="Enter your email"
                    >

                </div>


                {{-- PHONE --}}
                <div class="edit-form-group">

                    <label>
                        <i class="icon-phone"></i>
                        Phone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $customer->Phone ?? '') }}"
                        class="edit-form-control"
                        placeholder="Enter your phone number"
                    >

                </div>

            </div>


            {{-- ACTIONS --}}
            <div class="edit-form-footer">

                <a
                    href="{{ route('profile') }}"
                    class="edit-cancel-btn"
                >
                    <i class="icon-arrow-left"></i>
                    Cancel
                </a>

                <button
                    type="submit"
                    class="edit-save-btn"
                >
                    <i class="icon-check"></i>
                    Save Changes
                </button>

            </div>

        </form>

    </div>

</div>

@endsection