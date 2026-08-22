<nav class="sidebar-nav">

    {{-- Dashboard --}}
<a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
   href="{{ route('admin.dashboard') }}">
    <span class="nav-icon">
        <i class="bi bi-speedometer2"></i>
    </span>
    <span class="nav-text">Dashboard</span>
</a>


{{-- Users --}}
<a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
   href="{{ route('users.index') }}">
    <span class="nav-icon">
        <i class="bi bi-people"></i>
    </span>
    <span class="nav-text">Users</span>
</a>


{{-- Customers --}}
<a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
   href="{{ route('customers.index') }}">
    <span class="nav-icon">
        <i class="bi bi-person-heart"></i>
    </span>
    <span class="nav-text">Customers</span>
</a>


{{-- Invoices --}}
<a class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}"
   href="{{ route('invoices.index') }}">
    <span class="nav-icon">
        <i class="bi bi-receipt"></i>
    </span>
    <span class="nav-text">Invoices</span>
</a>


{{-- Services --}}
<a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}"
   href="{{ route('services.index') }}">
    <span class="nav-icon">
        <i class="bi bi-scissors"></i>
    </span>
    <span class="nav-text">Services</span>
</a>


{{-- Suppliers --}}
<a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
   href="{{ route('suppliers.index') }}">
    <span class="nav-icon">
        <i class="bi bi-truck"></i>
    </span>
    <span class="nav-text">Suppliers</span>
</a>


{{-- Promotions --}}
<a class="nav-link {{ request()->routeIs('promotions.*') ? 'active' : '' }}"
   href="{{ route('promotions.index') }}">
    <span class="nav-icon">
        <i class="bi bi-percent"></i>
    </span>
    <span class="nav-text">Promotions</span>
</a>

{{-- Appointments --}}
<a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
   href="{{ route('appointments.index') }}">
    <span class="nav-icon">
        <i class="bi bi-calendar-check"></i>
    </span>
    <span class="nav-text">Appointments</span>
</a>


{{-- Work Schedule --}}
<a class="nav-link {{ request()->routeIs('work-schedules.*') ? 'active' : '' }}"
   href="{{ route('work-schedules.index') }}">
    <span class="nav-icon">
        <i class="bi bi-calendar-week"></i>
    </span>
    <span class="nav-text">Work Schedule</span>
</a>


{{-- Feedback --}}
<a class="nav-link {{ request()->routeIs('feedbacks.*') ? 'active' : '' }}"
   href="{{ route('feedbacks.index') }}">
    <span class="nav-icon">
        <i class="bi bi-chat-left-text"></i>
    </span>
    <span class="nav-text">Feedback</span>
</a>

<a class="nav-link {{ request()->routeIs('admin.leave-requests.*') ? 'active' : '' }}"
   href="{{ route('admin.leave-requests.index') }}">

    <span class="nav-icon">
        <i class="bi bi-calendar-x"></i>
    </span>

    <span class="nav-text">
        Leave Requests
    </span>

</a>


{{-- Notifications --}}
<a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
   href="{{ route('notifications.index') }}">
    <span class="nav-icon">
        <i class="bi bi-bell"></i>
    </span>
    <span class="nav-text">Notifications</span>
</a>

</nav>