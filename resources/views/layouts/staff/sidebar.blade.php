<aside class="admin-sidebar"
       id="adminSidebar"
       aria-label="Main navigation">

    <div class="sidebar-header">

        <a class="brand-mark"
           href="{{ route('staff.work-schedule.index') }}"
           aria-label="ZenStyle Staff">

            <span class="brand-icon">

                <i class="bi bi-grid-1x2-fill"
                   aria-hidden="true">
                </i>

            </span>


            <span class="brand-copy">

                <span class="brand-title">
                    ZenStyle
                </span>

                <span class="brand-subtitle">
                    Staff Portal
                </span>

            </span>

        </a>

    </div>


    <nav class="sidebar-nav">

        {{-- WORK SCHEDULE --}}
        <a class="nav-link
            {{ request()->routeIs('staff.work-schedule.*') ? 'active' : '' }}"
           href="{{ route('staff.work-schedule.index') }}">

            <span class="nav-icon">

                <i class="bi bi-calendar-check"></i>

            </span>

            <span class="nav-text">
                Work Schedule
            </span>

        </a>


        {{-- LEAVE REQUEST --}}
        <a class="nav-link
            {{ request()->routeIs('staff.leave-requests.*') ? 'active' : '' }}"
           href="{{ route('staff.leave-requests.index') }}">

            <span class="nav-icon">

                <i class="bi bi-calendar-x"></i>

            </span>

            <span class="nav-text">
                Leave Request
            </span>

        </a>

    </nav>

</aside>