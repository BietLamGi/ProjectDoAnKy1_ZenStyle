@php
    // Real logged-in receptionist + real notifications, instead of the
    // hard-coded "Admin Hasan" / fake notification list left over from the
    // purchased theme.
    $navUser = auth()->user();
    $navNotifications = \App\Models\Notification::where(function ($q) use ($navUser) {
            $q->whereNull('UserID');
            if ($navUser) {
                $q->orWhere('UserID', $navUser->UserID);
            }
        })
        ->orderByDesc('CreatedAt')
        ->take(5)
        ->get();
    $navUnreadCount = $navNotifications->where('IsRead', false)->count();
@endphp
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
              <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                @if ($navUnreadCount > 0)
                  <span class="notification-dot"></span>
                @endif
                <i class="bi bi-bell" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end notification-menu">
                <div class="dropdown-header fw-bold text-body">Notifications</div>
                @forelse ($navNotifications as $notification)
                  <a class="dropdown-item" href="{{ route('receptionist.notifications.index') }}">
                    <span class="notification-title">{{ $notification->Title ?: 'Notification' }}</span>
                    <span class="notification-time">{{ $notification->CreatedAt ? \Illuminate\Support\Carbon::parse($notification->CreatedAt)->diffForHumans() : '' }}</span>
                  </a>
                @empty
                  <span class="dropdown-item text-muted">No notifications</span>
                @endforelse
                <a class="dropdown-item text-center small" href="{{ route('receptionist.notifications.index') }}">View all</a>
              </div>
            </div>

            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="avatar-img avatar-sm" src="{{ asset('assets/images/avatar/avatar.jpg') }}" alt="{{ $navUser->Username ?? 'User' }}">
                <span class="profile-name d-none d-sm-inline">{{ $navUser->Username ?? 'Guest' }}</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text text-muted small">{{ $navUser->Position ?? ($navUser->role->RoleName ?? '') }}</span></li>
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