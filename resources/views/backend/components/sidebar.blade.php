<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        {{-- <button id="toggle-btn-small-screen">&#9776;</button> --}}
        <h2>Welcome! </h2>
        <!-- <button id="toggle-btn">&#9776;</button> -->
    </div>

    {{-- <input type="text" placeholder="Search" class="search-box" /> --}}

    <!-- Overview -->
    {{-- <p>Overview</p> --}}
    {{-- <ul class="nav">
        <li class="active"><a href="index.html"><span><img src="{{ asset('backend/img/dashboard/side menu/dashboard.png') }}"></span>
                Dashboard</a></li>
    </ul> --}}

    {{-- @include('backend.components.residents-nav') --}}
    @stack('navmenu')

    <!-- Communication & Alerts -->
    <p>Communication & Alerts</p>
    <ul class="nav">
        <li><a href="pages/Notifications/Notification.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Notification & Alerts.png') }}"></span>Notification
                &
                Alerts</a></li>
    </ul>

    <!-- Logout Btn -->
    <ul class="nav">
                <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
    </ul>

</aside>

<!-- End Sidebar -->