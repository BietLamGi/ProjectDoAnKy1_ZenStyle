<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.html" aria-label="adminHMD dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">ZenStyle</span>
            <span class="brand-subtitle">Salon Management</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">

    <a class="nav-link active" href="{{ route('dashboard') }}">
        <span class="nav-icon">
            <i class="bi bi-speedometer2"></i>
        </span>
        <span class="nav-text">Dashboard</span>
    </a>

    <a class="nav-link" href="{{ route('users.index') }}">
        <span class="nav-icon">
            <i class="bi bi-people"></i>
        </span>
        <span class="nav-text">Users</span>
    </a>

    <a class="nav-link" href="{{ route('categories.index') }}">
        <span class="nav-icon">
            <i class="bi bi-tags"></i>
        </span>
        <span class="nav-text">Categories</span>
    </a>

    <a class="nav-link" href="{{ route('services.index') }}">
        <span class="nav-icon">
            <i class="bi bi-scissors"></i>
        </span>
        <span class="nav-text">Services</span>
    </a>

    <a class="nav-link" href="{{ route('suppliers.index') }}">
        <span class="nav-icon">
            <i class="bi bi-truck"></i>
        </span>
        <span class="nav-text">Suppliers</span>
    </a>

    <a class="nav-link" href="{{ route('products.index') }}">
        <span class="nav-icon">
            <i class="bi bi-box-seam"></i>
        </span>
        <span class="nav-text">Products</span>
    </a>

    <a class="nav-link" href="{{ route('appointments.index') }}">
        <span class="nav-icon">
            <i class="bi bi-calendar-check"></i>
        </span>
        <span class="nav-text">Appointments</span>
    </a>

    <a class="nav-link" href="{{ route('orders.index') }}">
        <span class="nav-icon">
            <i class="bi bi-receipt"></i>
        </span>
        <span class="nav-text">Orders</span>
    </a>

    <a class="nav-link" href="{{ route('feedbacks.index') }}">
        <span class="nav-icon">
            <i class="bi bi-chat-left-text"></i>
        </span>
        <span class="nav-text">Feedback</span>
    </a>

    <a class="nav-link" href="{{ route('notifications.index') }}">
        <span class="nav-icon">
            <i class="bi bi-bell"></i>
        </span>
        <span class="nav-text">Notifications</span>
    </a>

</nav>