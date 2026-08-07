<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="@yield('description', 'ZenStyle Salon & Spa - Đặt lịch cắt tóc, chăm sóc da, spa thư giãn cùng đội ngũ chuyên gia hàng đầu.')">
<link rel="icon" href="{{ asset('assets/images/favicon/favicon.ico') }}">

<title>@yield('title', 'ZenStyle Salon & Spa')</title>

<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('frontend/css/open-iconic-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">

<link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">

<link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">

<link rel="stylesheet" href="{{ asset('frontend/css/ionicons.min.css') }}">

<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-datepicker.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery.timepicker.css') }}">

<link rel="stylesheet" href="{{ asset('frontend/css/flaticon.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/icomoon.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

@yield('styles')

@stack('styles')