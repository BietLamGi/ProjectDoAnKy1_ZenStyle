<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.app.head')
</head>

<body>

    @include('layouts.app.navbar')

    @yield('content')

    @include('layouts.app.footer')

    @include('layouts.app.scripts')

</body>

</html>