<header class="topbar">
    <button id="toggle-btn">&#9776;</button>
    <div class="topbar-right">
        <input type="text" class="search-bar" placeholder="Search" />

        <div>
            <button class=" nav-notification" data-bs-toggle="modal" data-bs-target="#ConfirmationPopup">
                <img src="{{ asset('backend/img/dashboard/notification.png') }}">
            </button>
        </div>


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
                <div class="text-start mobile_none">
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
                <li><a class="dropdown-item" href="#" id="profileLink"><i class="bi bi-person-circle me-2"></i>View
                        Profile</a></li>
                <li><a class="dropdown-item" href="#" id="changePasswordLink"><i class="bi bi-lock me-2"></i>Change
                        Password</a></li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="#" id="settingsLink"><i class="bi bi-gear me-2"></i>Settings</a>
                </li>
                <li><a class="dropdown-item" href="#" id="helpLink"><i class="bi bi-question-circle me-2"></i>Help
                        Center</a></li>

                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i
                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
        </div>

    </div>
</header>