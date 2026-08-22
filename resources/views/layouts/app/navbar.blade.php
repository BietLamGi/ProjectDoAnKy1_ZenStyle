<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            Zen<span>Style</span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
            aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">

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
                    <a href="{{ route('services') }}" class="nav-link">
                        Services
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ request()->routeIs('home') ? '#contact' : route('home').'#contact' }}" class="nav-link">
                        Contact
                    </a>
                </li>

                {{-- BOOK APPOINTMENT --}}
                <li class="nav-item">
                    <a href="{{ route('booking') }}" class="nav-link navbar-book-btn">
                        Book Appointment
                    </a>
                </li>

                {{-- TRACK ORDER --}}
                <li class="nav-item">
                    <a href="{{ route('track-order.index') }}" class="nav-link navbar-icon-link" title="Track Order">
                        <i class="icon-search"></i>
                    </a>
                </li>

                {{-- CART --}}
                @php
                use App\Models\CartDetail;

                $cartCount = 0;

                if (Auth::check()) {
                $cartCount = CartDetail::whereHas('cart', function ($query) {
                $query->where('UserID', Auth::user()->UserID);
                })->sum('Quantity');
                } else {
                $cart = session('cart', []);
                $cartCount = collect($cart)->sum('quantity');
                }
                @endphp

                <li class="nav-item cart-nav-item">

                    <a href="{{ route('cart.index') }}" class="nav-link cart-link">

                        <i class="icon-shopping-cart"></i>

                        @if($cartCount > 0)
                        <span class="cart-count">
                            {{ $cartCount }}
                        </span>
                        @endif

                    </a>

                </li>

                @guest

                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link">
                        Login
                    </a>
                </li>

                @endguest

                @auth
                @if((int) Auth::user()->RoleID === 4)

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle navbar-user-link" href="#" id="navbarDropdown" role="button"
                        data-toggle="dropdown">

                        <span class="icon-user"></span>

                        {{ Auth::user()->Username }}

                    </a>

                    <div class="dropdown-menu dropdown-menu-right">

                        <a class="dropdown-item" href="{{ route('profile') }}">
                            My Profile
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="dropdown-item">
                                Logout
                            </button>
                        </form>

                    </div>

                </li>
                @endif
                @endauth

            </ul>

        </div>

    </div>
</nav>