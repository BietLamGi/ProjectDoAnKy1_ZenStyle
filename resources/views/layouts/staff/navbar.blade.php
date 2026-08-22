<header class="admin-navbar">

    <div class="navbar-left">

        <button type="button"
                class="btn btn-link d-lg-none"
                id="sidebarToggle">

            <i class="bi bi-list fs-4"></i>

        </button>

        <div>

            <h5 class="mb-0">
                @yield('title', 'Staff Dashboard')
            </h5>

            <small class="text-muted">
                ZenStyle Staff Portal
            </small>

        </div>

    </div>


    <div class="navbar-right">

        <span class="me-3">

            <i class="bi bi-person-circle me-1"></i>

            {{ auth()->user()->Username ?? 'Staff' }}

        </span>


        <form method="POST"
              action="{{ route('logout') }}"
              class="d-inline">

            @csrf

            <button type="submit"
                    class="btn btn-outline-danger btn-sm">

                <i class="bi bi-box-arrow-right me-1"></i>

                Logout

            </button>

        </form>

    </div>

</header>