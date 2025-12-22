{{-- <style>
    /* Reset li spacing */
    .nav li {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Make <a> full width */
    .nav li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        text-decoration: none;
        width: 100%;
        color: #333;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    /* Active link */
    .nav li a.active {
        background-color: #EC3E39;
        color: #fff;
        font-weight: 600;
    }

    .nav li a.active i {
        color: #fff;
    }

    /* Hover effect */
    .nav li a:hover {
        background-color: #FDEDEE;
        color: #EC3E39;
    }

    .nav li a:hover i {
        color: #EC3E39;
    }

    /* Icon spacing */
    .nav li a i {
        min-width: 20px;
        text-align: center;
    }
</style> --}}

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
        <li>
            <a href="{{ url('/resident/notices') }}" class="{{ Request::is('resident/notices') ? 'active' : '' }}">
                <span><i class="fas fa-bell"></i></span>Notification & Alerts
            </a>
        </li>
    </ul>

    <!-- Logout Btn -->
    <ul class="nav">
        {{-- <button type="button" onClick="callLogoutAPI()" class="btn btn-danger w-100">Logout</button> --}}
        {{-- <li><button type="button" onClick="callLogoutAPI()" class="logout callLogout"> <img
                    src="{{ asset('backend/img/dashboard/side menu/logout.png') }}"> Logout </button></li> --}}
        <li>
            <button type="button" class="logout logoutBtn">
                <img src="{{ asset('backend/img/dashboard/side menu/logout.png') }}"> Logout
            </button>
        </li>

    </ul>

</aside>
