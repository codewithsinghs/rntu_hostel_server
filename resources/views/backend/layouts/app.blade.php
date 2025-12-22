<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Default site description')">
    <meta name="author" content="Your Company Name">

    <!-- Title -->
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Global Styles Bootstrap 5.2 -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Bootstrap CSS Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- 19/12/2025 -->
    <!-- DataTables Core + Buttons + Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <!-- 19122025  -->

    <!-- ✅ CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('backend/css/sidebar.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('backend/css/dashboard.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('backend/css/mainStyle.css') }}" />
    <!-- 19122025  -->
    <link rel="stylesheet" href="{{ asset('backend/css/admin.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/table.css') }}" />
    <!-- 19122025  -->
    <link rel="stylesheet" href="{{ asset('backend/fonts/stylesheet.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/popup&tabs.css') }}">

    <!-- Page-specific Styles -->
    @stack('styles')

</head>

<body>
    <div class="main-container">

        <!-- aside -->
        @include('backend.components.aside')

        <!-- Header -->
        @include('backend.layouts.partials.header')
        <!-- Main Content -->
        {{-- <main class="main-content" style="margin-top:80px"> --}}
        <main class="main-content">

            <!-- Header -->
            {{-- @include('backend.layouts.partials.header') --}}

            <!-- Dashboard content -->

            @yield('content')

        </main>
    </div>

    @include('backend.layouts.partials.footer')

    <!-- Page-specific Scripts -->
    {{-- @stack('lscript') --}}

    <!-- Include these in your main layout (e.g., layouts/app.blade.php) -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    {{-- <script>
        $(function() {
            const token = localStorage.getItem("token");
            const currentPath = window.location.pathname;

            // Predefined dashboards per role
            const dashboards = {
                admin: '/admin/dashboard',
                admission: '/admission/dashboard',
                warden: '/warden/dashboard',
                resident: '/resident/dashboard',
                hod: '/hod/dashboard',
                guest: '/guest/dashboard'
            };

            // Auto fallback route if something goes wrong
            const fallbackRoute = '/login';

            // Helper: get cached profile
            function getCachedProfile() {
                const cached = localStorage.getItem("userProfile");
                if (!cached) return null;
                const data = JSON.parse(cached);
                const now = new Date().getTime();
                // Cache valid for 10 minutes
                if (now - data.timestamp > 10 * 60 * 1000) {
                    localStorage.removeItem("userProfile");
                    return null;
                }
                return data.profile;
            }

            // Helper: save cache
            function cacheProfile(profile) {
                localStorage.setItem("userProfile", JSON.stringify({
                    profile: profile,
                    timestamp: new Date().getTime()
                }));
            }

            // Redirect if no token
            if (!token) {
                // window.location.href = "/login";
                redirectTo(fallbackRoute);
                return;
            }

            let profile = getCachedProfile();

            if (profile) {
                processProfile(profile);
            } else {
                $.ajax({
                    url: "/api/profile",
                    method: "GET",
                    headers: {
                        Authorization: "Bearer " + token
                    },
                    success: function(res) {
                        if (!res.success || !res.data) {
                            handleLogout(true);
                            return;
                        }
                        cacheProfile(res.data);
                        processProfile(res.data);
                    },
                    error: function() {
                        handleLogout(true);
                    }
                });
            }

            // Core logic after getting profile
            function processProfile(data) {
                const role = data.role;
                const allowedPrefix = "/" + role;

                if (!currentPath.startsWith(allowedPrefix)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Unauthorized Access",
                        text: "You don’t have permission to view this page.",
                        confirmButtonColor: "#3085d6"
                    }).then(() => {
                        window.location.href = allowedPrefix + "/dashboard" || fallbackRoute;
                    });
                    return;
                }

                //         // Update UI (user menu)
                //         $("#userDropdown").html(`
            //     <div class="dropdown">
            //         <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            //             ${data.name} (${role})
            //         </button>
            //         <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
            //             <li><a class="dropdown-item" href="/${role}/profile">View Profile</a></li>
            //             <li><a class="dropdown-item" href="/${role}/change-password">Change Password</a></li>
            //             <li><hr class="dropdown-divider"></li>
            //             <li><a class="dropdown-item text-danger" href="#" id="logoutBtn">Logout</a></li>
            //         </ul>
            //     </div>
            // `);

                // Update UI (user menu)
                //                 $("#userDropdown").html(`
            //     <div class="dropdown d-flex align-items-center">
            //         <button class="btn btn-outline-secondary d-flex align-items-center dropdown-toggle px-3 py-2 rounded-pill shadow-sm"
            //             type="button"
            //             id="dropdownMenuButton"
            //             data-bs-toggle="dropdown"
            //             aria-expanded="false"
            //             style="transition: all 0.2s ease;">

            //             <!-- Avatar or Initial -->
            //             <div class="rounded-circle bg-primary text-white fw-bold me-2 d-flex align-items-center justify-content-center"
            //                 style="width: 36px; height: 36px;">
            //                 ${data.name ? data.name.charAt(0).toUpperCase() : 'U'}
            //             </div>

            //             <!-- User Info -->
            //             <div class="text-start">
            //                 <div class="fw-semibold text-dark">${data.name || 'User'}</div>
            //                 <small class="text-muted">${role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Guest'}</small>
            //             </div>
            //         </button>

            //         <ul class="dropdown-menu dropdown-menu-end mt-2 shadow border-0 rounded-3"
            //             aria-labelledby="dropdownMenuButton"
            //             style="min-width: 220px;">

            //             <li><h6 class="dropdown-header text-muted">Account</h6></li>
            //             <li><a class="dropdown-item" href="/${role}/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            //             <li><a class="dropdown-item" href="/${role}/profile"><i class="bi bi-person-circle me-2"></i>View Profile</a></li>
            //             <li><a class="dropdown-item" href="/${role}/change-password"><i class="bi bi-lock me-2"></i>Change Password</a></li>

            //             <li><hr class="dropdown-divider"></li>
            //             <li><a class="dropdown-item" href="/${role}/settings"><i class="bi bi-gear me-2"></i>Settings</a></li>
            //             <li><a class="dropdown-item" href="/${role}/help"><i class="bi bi-question-circle me-2"></i>Help Center</a></li>

            //             <li><hr class="dropdown-divider"></li>
            //             <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            //         </ul>
            //     </div>
            // `);

                // Example API or cached response
                 const userData = {
                 name: "Amresh Singh",
                    role: "admin",
                    profileUrl: "/admin/profile",
                    dashboardUrl: "/admin/dashboard",
                    changePasswordUrl: "/admin/change-password",
                   settingsUrl: "/admin/settings",
                     helpUrl: "/admin/help"
                 };

                // Safely inject data if available
                if (userData && userData.name) {
                    $("#userName").text(userData.name);
                    $("#userRole").text(userData.role.charAt(0).toUpperCase() + userData.role.slice(1));
                    $("#userAvatar").text(userData.name.charAt(0).toUpperCase());

                    // Dynamic links with fallback handling
                    $("#dashboardLink").attr("href", userData.dashboardUrl || "#");
                    $("#profileLink").attr("href", userData.profileUrl || "#");
                    $("#changePasswordLink").attr("href", userData.changePasswordUrl || "#");
                    $("#settingsLink").attr("href", userData.settingsUrl || "#");
                    $("#helpLink").attr("href", userData.helpUrl || "#");
                }


                // Bind logout
                $(document).on("click", "#logoutBtn", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Logout?",
                        text: "You’ll be logged out from this session.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, logout",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) handleLogout();
                    });
                });
            }

            // Logout function
            function handleLogout(force = false) {
                if (!force && !token) {
                    localStorage.clear();
                    window.location.href = "/login";
                    return;
                }

                $.ajax({
                    url: "/api/logout",
                    type: "POST",
                    headers: {
                        Authorization: "Bearer " + token
                    },
                    complete: function() {
                        localStorage.clear();
                        Swal.fire({
                            icon: "success",
                            title: "Logged out successfully",
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => window.location.href = "/login");
                    }
                });
            }

            // Helper: Redirect and prevent back navigation
            function redirectTo(path) {
                window.location.replace(path); // prevent back navigation
            }

            // Helper: Clear session and redirect to login
            function forceLogout() {
                localStorage.clear();
                redirectTo(fallbackRoute);
            }

            // Prevent navigating back to protected pages after logout
            window.onpageshow = function(event) {
                if (event.persisted || window.performance.getEntriesByType("navigation")[0].type ===
                    "back_forward") {
                    if (!localStorage.getItem('token')) {
                        redirectTo(fallbackRoute);
                    }
                }
            };

        });
    </script> --}}

    <script>
        $(function() {
            const token = localStorage.getItem("token");
            const currentPath = window.location.pathname;

            const dashboards = {
                admin: '/admin/dashboard',
                admission: '/admission/dashboard',
                warden: '/warden/dashboard',
                resident: '/resident/dashboard',
                hod: '/hod/dashboard',
                guest: '/guest/dashboard'
            };

            const fallbackRoute = '/login';

            // ===== Helper: Cached profile =====
            function getCachedProfile() {
                const cached = localStorage.getItem("userProfile");

                if (!cached) return null;
                const data = JSON.parse(cached);
                const now = Date.now();
                if (now - data.timestamp > 10 * 60 * 1000) { // 10 min cache
                    localStorage.removeItem("userProfile");
                    return null;
                }
                return data.profile;
            }


            function cacheProfile(profile) {
                localStorage.setItem("userProfile", JSON.stringify({
                    profile: profile,
                    timestamp: Date.now()
                }));
            }

            // ===== Redirect if no token =====
            if (!token) {
                // console.log('no');
                redirectTo(fallbackRoute);
                return;
            }


            // ===== Try from cache first =====
            let profile = getCachedProfile();


            if (profile) {
                processProfile(profile);
            } else {
                $.ajax({
                    url: "/api/profile",
                    method: "GET",
                    headers: {
                        Authorization: "Bearer " + token
                    },
                    success: function(res) {
                        console.log(res);
                        //  process.kill();

                        if (!res.success || !res.data) {
                            handleLogout(true);
                            return;
                        }
                        cacheProfile(res.data);
                        processProfile(res.data);
                    },
                    error: function() {
                        // handleLogout(true);
                    }
                });
            }

            // ===== Core Function =====
            function processProfile(data) {
                // console.log('data', data);
                // const role = data.role;
                // Try to get role directly
                let role = data.role;

                // If not found, fallback to roles array
                if (!role && Array.isArray(data.roles) && data.roles.length > 0) {
                    // Example: pick the first role's name
                    role = data.roles[0].name;

                    // Or if you prefer fullname:
                    // role = data.roles[0].fullname;
                }

                // Now you can use `role`
                // console.log("User role:", role);


                const allowedPrefix = "/" + role;

                // console.log('allowedPrefix', allowedPrefix);
                // process.kill();
                // Restrict cross-role access
                if (!currentPath.startsWith(allowedPrefix)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Unauthorized Access",
                        text: "You don’t have permission to view this page.",
                        confirmButtonColor: "#3085d6"
                    }).then(() => {
                        redirectTo(dashboards[role] || fallbackRoute);
                    });
                    return;
                }

                // ==== Dynamically inject user info ====
                $("#userName").text(data.name || "User");
                $("#userRole").text(role ? role.charAt(0).toUpperCase() + role.slice(1) : "Guest");
                $("#userAvatar").text(data.name ? data.name.charAt(0).toUpperCase() : "U");

                // ==== Dynamic links ====
                $("#dashboardLink").attr("href", dashboards[role] || "#");
                $("#profileLink").attr("href", `/${role}/profile`);
                $("#changePasswordLink").attr("href", `/${role}/change-password`);
                $("#settingsLink").attr("href", `/${role}/settings`);
                $("#helpLink").attr("href", `/${role}/help`);

                // ==== Bind Logout (always working) ====
                // $(document).off("click", "#logoutBtn").on("click", "#logoutBtn", function(e) {
                // Bind once, works everywhere
                $(document).off("click", ".logoutBtn").on("click", ".logoutBtn", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Logout?",
                        text: "You’ll be logged out from this session.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, logout",
                        cancelButtonText: "Cancel"
                    }).then((result) => {
                        if (result.isConfirmed) handleLogout();
                    });
                });
            }

            // ===== Logout =====
            function handleLogout(force = false) {
                if (!force && !token) {
                    forceLogout();
                    return;
                }

                $.ajax({
                    url: "/api/logout",
                    type: "POST",
                    headers: {
                        Authorization: "Bearer " + token
                    },
                    complete: function() {
                        localStorage.clear();
                        Swal.fire({
                            icon: "success",
                            title: "Logged out successfully",
                            timer: 1000,
                            showConfirmButton: false
                        }).then(() => redirectTo(fallbackRoute));
                    }
                });
            }

            // ===== Utility Helpers =====
            function redirectTo(path) {
                window.location.replace(path); // Prevent back navigation
            }

            function forceLogout() {
                localStorage.clear();
                redirectTo(fallbackRoute);
            }

            // ===== Prevent back after logout =====
            window.onpageshow = function(event) {
                if (event.persisted ||
                    window.performance.getEntriesByType("navigation")[0].type === "back_forward") {
                    if (!localStorage.getItem("token")) redirectTo(fallbackRoute);
                }
            };
        });
    </script>

    @include('layouts.swal')
    <script>
        $(document).ajaxError(function(event, xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let html = Object.values(errors).map(e => e[0]).join('<br>');

                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    html: html
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON?.message ?? 'Something went wrong'
                });
            }
        });
    </script>

    @stack('scripts')

</body>

</html>
