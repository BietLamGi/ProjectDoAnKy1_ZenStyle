<nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search" placeholder="Search users, orders, reports" aria-label="Search">
          </form>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
           

            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                
                <span class="profile-name d-none d-sm-inline">{{ auth()->user()->Username ?? 'Guest' }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->Position ?? '' }}</span></li>
                <li><hr class="dropdown-divider"></li>
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
          </div>
        </div>
      </nav>