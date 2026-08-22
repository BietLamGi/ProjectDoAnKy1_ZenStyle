<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Staff Dashboard')
    </title>

    <link rel="stylesheet"
          href="{{ asset('assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/css/style.css') }}">

</head>

<body>

<div class="admin-shell">

    <div class="admin-main">

        {{-- NAVBAR --}}
        @include('layouts.staff.navbar')

        {{-- SIDEBAR --}}
        @include('layouts.staff.sidebar')


        {{-- MAIN CONTENT --}}
        <main class="dashboard-content">

            <div class="container-fluid">

                {{-- SUCCESS --}}
                @if (session('success'))

                    <div class="alert alert-success alert-dismissible fade show mt-3"
                         role="alert">

                        {{ session('success') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>

                    </div>

                @endif


                {{-- ERROR --}}
                @if (session('error'))

                    <div class="alert alert-danger alert-dismissible fade show mt-3"
                         role="alert">

                        {{ session('error') }}

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>

                    </div>

                @endif


                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())

                    <div class="alert alert-danger alert-dismissible fade show mt-3"
                         role="alert">

                        <ul class="mb-0 ps-3">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close">
                        </button>

                    </div>

                @endif

            </div>


            {{-- PAGE CONTENT --}}
            @yield('content')

        </main>

    </div>

</div>


<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}">
</script>

<script src="{{ asset('assets/js/main.js') }}">
</script>

</body>

</html>