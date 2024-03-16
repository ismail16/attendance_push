<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <div class="d-flex align-items-end">
                    <span class="fw-bold" style="padding-bottom: 10px; color:rgb(13, 27, 96)">PICode Push</span>
                </div>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Home -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Home</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('devices.index') ? 'active' : '' }}">
            <a href="{{ route('devices.index') }}" class="menu-link">
                <div data-i18n="Device List"><i class='menu-icon tf-icons bx bxs-book-content'></i> Device List</div>
            </a>
        </li>


        <!-- Extended components -->
        <!--   <li class="menu-item {{ request()->routeIs('users.*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class=' menu-icon tf-icons bx bx-user'></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">


            </ul>
        </li> -->
        {{-- <li class="menu-item {{ request()->routeIs('users.import-user-index')? 'active' : '' }}">
                    <a href="{{ route('users.import-user-index') }}" class="menu-link">
                        <div data-i18n="Add users From Server"><i class=' menu-icon tf-icons bx bx-user'></i> Add users From Server</div>
                    </a>
                </li> --}}
        <li class="menu-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
                <div data-i18n="User List"> <i class=' menu-icon tf-icons bx bx-user'></i> User List</div>
            </a>
        </li>
        {{-- <li class="menu-item {{ request()->routeIs('users.create') ? 'active' : '' }}">
            <a href="{{ route('users.create') }}" class="menu-link">
                <div data-i18n="Add user to device"> <i class=' menu-icon tf-icons bx bx-user-plus'></i>Add user to
                    device</div>
            </a>
        </li> --}}

        <!--  <li class="menu-item {{ request()->routeIs('device.attendance') ? 'active' : '' }}">
            <a href="{{ route('device.attendance') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-notepad'></i>
                <div data-i18n="Attendance Log">Attendance Log</div>
            </a>
        </li>
        <li class="menu-item {{ request()->routeIs('attendance.index') ? 'active' : '' }}">
            <a href="{{ route('attendance.index') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-notepad'></i>
                <div data-i18n="All Attendance">Today's Attendance</div>
            </a>
        </li> -->

        <li
            class="menu-item {{ request()->routeIs('export.export_attendance') ? 'active' : '' }} {{ request()->routeIs('search.attendance') ? 'active' : '' }}">
            <a href="{{ route('export.export_attendance') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-notepad'></i>
                <div data-i18n="Export Attendance">Attandance Exports</div>
            </a>
        </li>

        <!-- <li class="menu-item {{ request()->routeIs('organization.edit') ? 'active' : '' }}">
            <a href="{{ route('organization.edit') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-building-house'></i>
                <div data-i18n="Organization">Organization</div>
            </a>
        </li> -->

    </ul>
</aside>
