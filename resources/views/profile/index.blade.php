@extends('layouts.app.app')

@section('title', 'My Profile')

@section('content')

<div class="container">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- ERROR MESSAGE --}}
    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    {{-- HEADER --}}
    <div class="profile-header">

        <h2>My Profile</h2>

        <p>
            Manage your personal information and account.
        </p>

    </div>


    {{-- PROFILE CARD --}}
    <div class="profile-card">


        {{-- ===================================================== --}}
        {{-- LEFT SIDEBAR --}}
        {{-- ===================================================== --}}

        <div class="profile-sidebar">


            {{-- AVATAR --}}
            <div class="profile-avatar">

                <i class="icon-user"></i>

            </div>


            {{-- USERNAME --}}
            <h3>
                {{ $user->Username }}
            </h3>


            {{-- EMAIL --}}
            <p>
                {{ $user->Email }}
            </p>


            {{-- MEMBERSHIP --}}
            <div class="member-badge">

                <i class="icon-star"></i>

                <span>
                    {{ $customer->MembershipTier ?? 'Normal' }} Member
                </span>

            </div>


            {{-- EDIT PROFILE --}}
            <a
                href="{{ route('profile.edit') }}"
                class="edit-profile-btn"
            >

                <i class="icon-edit"></i>

                Edit Profile

            </a>


        </div>



        {{-- ===================================================== --}}
        {{-- RIGHT CONTENT --}}
        {{-- ===================================================== --}}

        <div class="profile-content">


            {{-- ================================================= --}}
            {{-- PERSONAL INFORMATION --}}
            {{-- ================================================= --}}

            <div class="profile-section">


                <div class="section-title">

                    <h4>
                        Personal Information
                    </h4>

                </div>


                <div class="info-grid">


                    {{-- FULL NAME --}}
                    <div class="info-item">

                        <span>
                            Full Name
                        </span>

                        <strong>
                            {{ $customer->FullName ?? 'Not updated' }}
                        </strong>

                    </div>


                    {{-- USERNAME --}}
                    <div class="info-item">

                        <span>
                            Username
                        </span>

                        <strong>
                            {{ $user->Username }}
                        </strong>

                    </div>


                    {{-- EMAIL --}}
                    <div class="info-item">

                        <span>
                            Email
                        </span>

                        <strong>
                            {{ $user->Email }}
                        </strong>

                    </div>


                    {{-- PHONE --}}
                    <div class="info-item">

                        <span>
                            Phone
                        </span>

                        <strong>
                            {{ $customer->Phone ?? 'Not updated' }}
                        </strong>

                    </div>


                </div>

            </div>



            {{-- ================================================= --}}
            {{-- QUICK ACCESS --}}
            {{-- ================================================= --}}

            <div class="profile-section account-menu-section">


                <div class="section-title">

                    <h4>
                        Quick Access
                    </h4>

                    <span>
                        Manage your ZenStyle account
                    </span>

                </div>



                {{-- ============================================= --}}
                {{-- MY APPOINTMENTS --}}
                {{-- ============================================= --}}

                <a
                    href="{{ route('appointments.my') }}"
                    class="account-menu"
                >

                    <div class="menu-icon">

                        <i class="icon-calendar"></i>

                    </div>


                    <div class="menu-content">

                        <strong>
                            My Appointments
                        </strong>

                        <span>
                            View and manage your appointments
                        </span>

                    </div>


                    <i class="icon-arrow-right menu-arrow"></i>

                </a>



                {{-- ============================================= --}}
                {{-- MY ORDERS --}}
                {{-- ============================================= --}}

                <a
                    href="#"
                    class="account-menu"
                >

                    <div class="menu-icon">

                        <i class="icon-shopping-bag"></i>

                    </div>


                    <div class="menu-content">

                        <strong>
                            My Orders
                        </strong>

                        <span>
                            View your order history
                        </span>

                    </div>


                    <i class="icon-arrow-right menu-arrow"></i>

                </a>



                {{-- ============================================= --}}
                {{-- MY FEEDBACK --}}
                {{-- ============================================= --}}

                <a
                    href="#"
                    class="account-menu"
                >

                    <div class="menu-icon">

                        <i class="icon-chat"></i>

                    </div>


                    <div class="menu-content">

                        <strong>
                            My Feedback
                        </strong>

                        <span>
                            Share your experience with us
                        </span>

                    </div>


                    <i class="icon-arrow-right menu-arrow"></i>

                </a>



                {{-- ============================================= --}}
                {{-- MEMBERSHIP --}}
                {{-- ============================================= --}}

                <a
                    href="#"
                    class="account-menu"
                >

                    <div class="menu-icon">

                        <i class="icon-star"></i>

                    </div>


                    <div class="menu-content">

                        <strong>
                            Membership
                        </strong>

                        <span>
                            View your membership benefits
                        </span>

                    </div>


                    <i class="icon-arrow-right menu-arrow"></i>

                </a>

{{-- ============================================= --}}
{{-- CHANGE PASSWORD --}}
{{-- ============================================= --}}

<div class="profile-section">

    <div class="section-title">
        <h4>Change Password</h4>

        <span>
            Update your account password
        </span>
    </div>

    @if(session('password_success'))
        <div class="alert alert-success">
            {{ session('password_success') }}
        </div>
    @endif

    @if(session('password_error'))
        <div class="alert alert-danger">
            {{ session('password_error') }}
        </div>
    @endif

    @if($errors->has('current_password') || $errors->has('new_password'))
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('profile.password.update') }}">

        @csrf
        @method('PUT')

        <div class="form-group">
            <input
                type="password"
                name="current_password"
                class="form-control"
                placeholder="Current Password"
            >
        </div>

        <div class="form-group">
            <input
                type="password"
                name="new_password"
                class="form-control"
                placeholder="New Password"
            >
        </div>

        <div class="form-group">
            <input
                type="password"
                name="new_password_confirmation"
                class="form-control"
                placeholder="Confirm New Password"
            >
        </div>

        <button
            type="submit"
            class="edit-profile-btn"
        >
            Change Password
        </button>

    </form>

</div>

                {{-- ============================================= --}}
                {{-- LOGOUT --}}
                {{-- ============================================= --}}

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf


                    <button
                        type="submit"
                        class="account-menu logout-menu"
                    >

                        <div class="menu-icon">

                            <i class="bi bi-box-arrow-right"></i>

                        </div>


                        <div class="menu-content">

                            <strong>
                                Logout
                            </strong>

                            <span>
                                Sign out of your account
                            </span>

                        </div>


                        <i class="icon-arrow-right menu-arrow"></i>

                    </button>

                </form>


            </div>


        </div>


    </div>

</div>

@endsection