<style>
    .user-section {
        gap: 8px;
    }

    .user-role-label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        white-space: nowrap;
    }

    .user-button {
        background: none;
        border: none;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .user-initial {
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Hide role label on small screens */
    @media (max-width: 576px) {
        .user-role-label {
            display: none;
        }

        .mob {
            display: none;
        }
    }

    /* --- Topbar Styles --- */
    .topbar {
        position: fixed;
        top: 0;
        z-index: 100;
        /* ✅ No transition initially */
        transition: none;
    }

    .topbar-ready {
        transition: transform 0.4s ease, left 0.3s ease, width 0.3s ease;
    }

    .topbar-full {
        left: 0;
        width: 100%;
    }

    .topbar-part {
        left: 300px;
        width: calc(100% - 300px);

        /* left: 190px;
        width: calc(100% - 250px);
        z-index: 1000;
        background: transparent; */
    }

    .topbar.hide {
        transform: translateY(-100%);
    }

    @media only screen and (max-width: 1024px) {
        .topbar-part {
            left: 250px;
            width: calc(100% - 250px);
        }
    }

    @media (max-width: 768px) {

        /* .topbar-part, */
        .topbar-full {
            left: 0;
            width: 100%;
        }
    }

    @media (max-width: 768px) {

        /* .topbar, */
        /* .topbar-full, */
        .topbar-part {
            right: 0;
            left: auto;
            /* width: 100%; */
            /* width: fit-content; */
            left: 190px;
        width: calc(100% - 190px);
        z-index: 1000;
        background: transparent;
        }
    }
</style>


<!-- Header -->
<header class="topbar">
    <button id="toggle-btn">&#9776;</button>
    <div class="topbar-right">
        <input type="text" class="search-bar" placeholder="Search" />

        <div>
            <button class=" nav-notification" data-bs-toggle="modal" data-bs-target="#ConfirmationPopup">
                <img src="{{ asset('backend/img/dashboard/notification.png') }}">
            </button>
        </div>

        {{-- <div class="user-section dropdown d-flex align-items-center">
            <span class="user-role-label">Admin</span>
            <button class="dropdown-toggle user-button" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-initial">A</span>
            </button>

            @php
                $role = request()->segment(1); // Gets 'admin' from /admin/change-password
            @endphp

            <ul class="dropdown-menu custom-style-ul">
                <li>
                    <a class="dropdown-item custom-style-li" href="#">
                        {{ ucfirst($role) }} Profile
                    </a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="{{ url("/{$role}/change-password") }}">
                        Change Password
                    </a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="#">Notification</a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Log Out
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>


        </div> --}}



        {{-- <div class="user-section dropdown d-flex align-items-center">
            <!-- Dynamic role label (API sets this) -->
            <span class="user-role-label" id="userRole">User</span>

            <!-- Dynamic user initial -->
            <button class="dropdown-toggle user-button" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-initial" id="userInitial">U</span>
            </button>

            <button id="userDropdown"></button>
            <!-- Dropdown menu (static, fallback-ready) -->
            <ul class="dropdown-menu custom-style-ul" id="userDropdownMenu">
                <!-- Profile links (first role used as default) -->
                <li>
                    <a class="dropdown-item custom-style-li" href="#" id="profileLink">Profile</a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="#" id="changePasswordLink">Change Password</a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="#" id="notificationLink">Notifications</a>
                </li>
                <li>
                    <a class="dropdown-item custom-style-li" href="#" onclick="callLogoutAPI()">Log Out</a>
                </li>
            </ul>

            
        </div> --}}


        <div class="user-section dropdown d-flex align-items-center">
            <button
                class="btn btn-outline-secondary d-flex align-items-center dropdown-toggle px-3 py-2 rounded-pill shadow-sm"
                type="button" id="userDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">

                <!-- Avatar or Initial -->
                <div class="rounded-circle bg-secondary text-white fw-bold me-2 d-flex align-items-center justify-content-center"
                    style="width: 36px; height: 36px;" id="userAvatar">
                    U
                </div>

                <!-- Fallback User Info -->
                <div class="text-start">
                    <div class="fw-semibold text-dark" id="userName">Guest User</div>
                    <small class="text-muted" id="userRole">Visitor</small>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end mt-2 shadow border-0 rounded-3"
                aria-labelledby="userDropdownButton" id="userDropdownMenu" style="min-width: 220px;">

                <li>
                    <h6 class="dropdown-header text-muted">Account</h6>
                </li>
                <li><a class="dropdown-item" href="#" id="dashboardLink"><i
                            class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="#" id="profileLink"><i
                            class="bi bi-person-circle me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#" id="changePasswordLink"><i
                            class="bi bi-lock me-2"></i>Change Password</a></li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                {{-- <li><a class="dropdown-item" href="#" id="settingsLink"><i
                            class="bi bi-gear me-2"></i>Settings</a></li>
                <li><a class="dropdown-item" href="#" id="helpLink"><i
                            class="bi bi-question-circle me-2"></i>Help Center</a></li> 

                <li>
                    <hr class="dropdown-divider">
                </li> --}}
                <li><a class="dropdown-item text-danger logoutBtn" href="#" id="logoutBtn"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>





    </div>
</header>

{{-- <script>
    let lastScrollY = window.scrollY;
    let timeoutId;

    const aside = document.querySelector('.sidebar');
    const header = document.querySelector('.topbar');
    const toggleBtn = document.getElementById('toggle-btn');

    function showHeader() {
        header.classList.remove('hide');
    }

    function hideHeader() {
        header.classList.add('hide');
    }

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY) {
            hideHeader(); // scrolling down
        } else {
            showHeader(); // scrolling up
        }

        lastScrollY = currentScrollY;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            showHeader(); // show when scroll stops
        }, 250);
    });

    // ✅ Initial setup without transition
    if (aside.classList.contains('collapsed')) {
        header.classList.add('topbar-full');
    } else {
        header.classList.add('topbar-part');
    }

    // ✅ Enable transitions only after first render
    window.addEventListener("load", () => {
        header.classList.add("topbar-ready");
    });

    // ✅ Toggle button handling
    toggleBtn.addEventListener('click', () => {
        if (aside.classList.contains('collapsed')) {
            header.classList.remove('topbar-full');
            header.classList.add('topbar-part');
        } else {
            header.classList.remove('topbar-part');
            header.classList.add('topbar-full');
        }
    });
</script> --}}



{{-- Rajat Changes in main.js  25/11/2025 --}}

{{-- <script>
    let lastScrollY = window.scrollY;
    let timeoutId;

    const aside = document.querySelector('.sidebar');
    const header = document.querySelector('.topbar');
    const toggleBtn = document.getElementById('toggle-btn');

    // --- Header show/hide on scroll ---
    function showHeader() {
        header.classList.remove('hide');
    }

    function hideHeader() {
        header.classList.add('hide');
    }

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY) hideHeader();
        else showHeader();

        lastScrollY = currentScrollY;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            showHeader();
        }, 250);
    });

    // --- Default sidebar collapsed on page load ---
    if (!aside.classList.contains('collapsed')) aside.classList.add('collapsed');

    // --- Initial header class based on sidebar state ---
    if (aside.classList.contains('collapsed')) header.classList.add('topbar-full');
    else header.classList.add('topbar-part');

    // --- Enable transitions after first render ---
    window.addEventListener("load", () => {
        header.classList.add("topbar-ready");
        toggleBtn.innerHTML = aside.classList.contains('collapsed') ? '☰' : '✖';
    });

    // --- Toggle sidebar & update header + icon ---
    toggleBtn.addEventListener('click', () => {
        // Toggle the sidebar visually
        aside.classList.toggle('collapsed');

        // Update header classes
        if (aside.classList.contains('collapsed')) {
            header.classList.remove('topbar-part');
            header.classList.add('topbar-full');
            toggleBtn.innerHTML = '☰';
        } else {
            header.classList.remove('topbar-full');
            header.classList.add('topbar-part');
            toggleBtn.innerHTML = '✖';
        }
    });

    // --- Close sidebar on mobile link click ---
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768 && !aside.classList.contains('collapsed')) {
                aside.classList.add('collapsed');
                header.classList.remove('topbar-part');
                header.classList.add('topbar-full');
                toggleBtn.innerHTML = '☰';
            }
        });
    });
</script> --}}
{{-- Rajat Changes in main.js  25/11/2025 --}}
