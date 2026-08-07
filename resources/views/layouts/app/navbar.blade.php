<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            Zen<span>Style</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#ftco-nav" aria-controls="ftco-nav"
            aria-expanded="false" aria-label="Toggle navigation">

            <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">

            <ul class="navbar-nav ml-auto">

                <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ request()->routeIs('home') ? '#home' : route('home') }}" class="nav-link">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->routeIs('home') ? '#about' : route('home').'#about' }}" class="nav-link">
                        About Us
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->routeIs('home') ? '#services' : route('home').'#services' }}" class="nav-link">
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->routeIs('home') ? '#contact' : route('home').'#contact' }}" class="nav-link">
                        Contact
                    </a>
                </li>

                <li class="nav-item ml-lg-2">
                    <a href="{{ route('booking') }}"
                    class="nav-link btn btn-primary text-white px-3 rounded">
                            Book Appointment
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('register') ? 'active' : '' }}">
                    <a href="{{ route('register') }}" class="nav-link">
                        Register
                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>