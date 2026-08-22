
<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('receptionist.dashboard') }}" aria-label="ZenStyle dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
              <span class="brand-title">ZenStyle </span>
              <span class="brand-subtitle">Reception Desk</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link {{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}" href="{{ route('receptionist.dashboard') }}">
            <span class="nav-icon">
                <i class="bi bi-speedometer2"></i>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.appointments.*') ? 'active' : '' }}" href="{{ route('receptionist.appointments.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-check"></i>
            </span>
            <span class="nav-text">Appointments</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.customers.*') ? 'active' : '' }}" href="{{ route('receptionist.customers.index') }}">
            <span class="nav-icon">
                <i class="bi bi-people"></i>
            </span>
            <span class="nav-text">Customers</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.services.*') ? 'active' : '' }}" href="{{ route('receptionist.services.index') }}">
            <span class="nav-icon">
                <i class="bi bi-truck"></i>
            </span>
            <span class="nav-text">Services &amp; Products</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.invoices.*') ? 'active' : '' }}" href="{{ route('receptionist.invoices.index') }}">
            <span class="nav-icon">
                <i class="bi bi-receipt"></i>
            </span>
            <span class="nav-text">Invoices</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.promotions.*') ? 'active' : '' }}" href="{{ route('receptionist.promotions.index') }}">
            <span class="nav-icon">
                <i class="bi bi-tag"></i>
            </span>
            <span class="nav-text">Promotions</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.work-schedules.*') ? 'active' : '' }}" href="{{ route('receptionist.work-schedules.index') }}">
            <span class="nav-icon">
                <i class="bi bi-calendar-week"></i>
            </span>
            <span class="nav-text">Work Schedule</span>
        </a>


        <a class="nav-link {{ request()->routeIs('receptionist.feedbacks.*') ? 'active' : '' }}" href="{{ route('receptionist.feedbacks.index') }}">
            <span class="nav-icon">
                <i class="bi bi-chat-left-text"></i>
            </span>
            <span class="nav-text">Feedback</span>
        </a>

        <a class="nav-link {{ request()->routeIs('receptionist.notifications.*') ? 'active' : '' }}" href="{{ route('receptionist.notifications.index') }}">
            <span class="nav-icon">
                <i class="bi bi-bell"></i>
            </span>
            <span class="nav-text">Notifications</span>
        </a>

    </nav>
</aside>
