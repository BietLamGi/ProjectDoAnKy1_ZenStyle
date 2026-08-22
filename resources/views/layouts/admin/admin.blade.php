<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Dashboard')
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
    * {
        box-sizing: border-box;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
    }

    body {
        background: #0b1326;
        color: #f5f7fa;
    }

    /* =========================
           ADMIN LAYOUT
        ========================= */

    .admin-layout {
        display: flex;
        min-height: 100vh;
        width: 100%;
    }

    /* =========================
           SIDEBAR
        ========================= */

    .admin-sidebar {
        width: 260px;
        min-width: 260px;
        min-height: 100vh;

        background: #0d172b;

        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;

        overflow-y: auto;

        z-index: 1000;
    }

    /* =========================
           MAIN
        ========================= */

    .admin-main {
        margin-left: 260px;

        width: calc(100% - 260px);

        min-height: 100vh;

        background: #0b1326;
    }

    /* =========================
           TOPBAR
        ========================= */

    .admin-topbar {
        height: 86px;

        display: flex;
        align-items: center;

        padding: 0 28px;

        background: #172238;

        border-bottom: 1px solid #26344d;
    }

    /* =========================
           CONTENT
        ========================= */

    .admin-content {
        padding: 40px 52px;
    }

    /* =========================
           SIDEBAR LINK
        ========================= */

    .admin-sidebar .nav-link {
        display: flex;
        align-items: center;

        gap: 14px;

        padding: 14px 20px;

        margin: 4px 12px;

        border-radius: 10px;

        color: #dce5f5;

        text-decoration: none;
    }

    .admin-sidebar .nav-link:hover,
    .admin-sidebar .nav-link.active {
        background: #1d2a43;
        color: #ffffff;
    }

    .nav-icon {
        width: 36px;
        height: 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #202c43;

        font-size: 18px;
    }

    .nav-text {
        font-weight: 600;
    }

    /* =========================
           RESPONSIVE
        ========================= */

    @media (max-width: 992px) {

        .admin-sidebar {
            width: 220px;
            min-width: 220px;
        }

        .admin-main {
            margin-left: 220px;
            width: calc(100% - 220px);
        }

    }
    </style>

    @stack('styles')

</head>

<body>

    <div class="admin-layout">

        
        {{-- SIDEBAR --}}
        <aside class="admin-sidebar">
            
            @include('layouts.admin.sidebar')

        </aside>


        {{-- MAIN --}}
        <main class="admin-main">

            {{-- TOPBAR --}}
            <header class="admin-topbar">

                <button type="button" class="btn btn-outline-light me-3">
                    <i class="bi bi-list fs-4"></i>
                </button>

                <div class="flex-grow-1">

                    <input type="text" class="form-control" placeholder="Search users, orders, reports"
                        style="max-width: 620px;">

                </div>

                <div class="ms-3">

                    <button class="btn btn-outline-light">
                        <i class="bi bi-bell"></i>
                    </button>

                </div>

                <div class="ms-3 dropdown">

                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->user()->Username ?? 'Admin' }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            @if (Route::has('logout'))
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Sign out</button>
                                </form>
                            @else
                                <a class="dropdown-item" href="{{ url('/') }}">Sign out</a>
                            @endif
                        </li>
                    </ul>

                </div>

            </header>


            {{-- PAGE CONTENT --}}
            <section class="admin-content">

                @yield('content')

            </section>

        </main>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>

</html>