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
    <!-- ✅ CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('backend/css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/mainStyle.css') }}" />
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
        <main class="main-content" style="margin-top:80px">

            <!-- Header -->
            {{-- @include('backend.layouts.partials.header') --}}

            <!-- Dashboard content -->

            @yield('content')

        </main>
    </div>

    @include('backend.layouts.partials.footer')

    <!-- Page-specific Scripts -->
    {{-- @stack('lscript') --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- <script>
        $(function() {
            const publicPages = ['/login', '/register']; // add all public URLs

            const currentPath = window.location.pathname;
            if (publicPages.includes(currentPath)) return; // skip check for login/register

            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            $.ajax({
                url: '/api/profile', // API that returns {success:true, data:{role:'admin'}}
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(res) {
                    if (!res.success || !res.data.role) {
                        localStorage.clear();
                        window.location.href = '/login';
                        return;
                    }

                    const role = res.data.role;

                    // Prevent access to wrong role dashboards
                    if (currentPath.startsWith('/admin') && role !== 'admin') {
                        window.location.href = '/login';
                    } else if (currentPath.startsWith('/resident') && role !== 'resident') {
                        window.location.href = '/login';
                    } else if (currentPath.startsWith('/hod') && role !== 'hod') {
                        window.location.href = '/login';
                    }

                    // show username
                    $('#userName').text(res.data.name + ' (' + role + ')');
                },
                error: function() {
                    localStorage.clear();
                    window.location.href = '/login';
                }
            });

            $('#logoutBtn').click(function() {
                $.ajax({
                    url: '/api/logout',
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    complete: function() {
                        localStorage.clear();
                        window.location.href = '/login';
                    }
                });
            });
        });
    </script> --}}
    <script type="text/javascript">
        // $(document).ready(function() {
        //     if (!localStorage.getItem('token') && !localStorage.getItem('token')) {
        //         callLogoutAPI();
        //     } else if (localStorage.getItem('token') && !localStorage.getItem('auth-id')) {
        //         callLogoutAPI();
        //     } else if (!localStorage.getItem('token') && localStorage.getItem('auth-id')) {
        //         callLogoutAPI();
        //     } else {
        //         $.ajax({
        //             url: '/api/resident/profile', // your API endpoint
        //             type: 'GET',
        //             headers: {
        //                 'token': localStorage.getItem('token'),
        //                 'Auth-ID': localStorage.getItem('auth-id')
        //             },
        //             success: function(response) {
        //                 if (!response.success) {
        //                     callLogoutAPI();
        //                 }
        //             },
        //         });
        //     }


        // });



        // let profileData = null; // global variable

        // $(document).ready(function() {
        //     const token = localStorage.getItem('token');
        //     const authId = localStorage.getItem('auth-id');

        //     if (!token || !authId) return callLogoutAPI();

        //     $.ajax({
        //         url: '/api/profile', // Single reusable API for all roles
        //         type: 'GET',
        //         headers: {
        //             'token': token,
        //             'Auth-ID': authId
        //         },
        //         success: function(res) {
        //             if (!res.success || !res.data) return callLogoutAPI();

        //             profileData = res.data; // store globally

        //             // Trigger event so profile page knows data is ready
        //             $(document).trigger('profileDataLoaded');


        //             const user = profileData;
        //             const roles = user.roles && user.roles.length ? user.roles : ['User'];
        //             const primaryRole = roles[0]; // use first role as default
        //             const userName = user.name || 'User';

        //             // Update user initial and role in header
        //             $('#userInitial').text(userName.charAt(0).toUpperCase());
        //             $('#userRole').text(primaryRole);

        //             // Update profile/change-password links
        //             $('#profileLink').attr('href', `/${primaryRole.toLowerCase()}/profile`).text(
        //                 `${primaryRole} Profile`
        //             );
        //             $('#changePasswordLink').attr('href',
        //                 `/${primaryRole.toLowerCase()}/change-password`).text(
        //                 'Change Password'
        //             );

        //             // Notifications fallback
        //             const canViewNotification = user.permissions?.includes('view-notification');
        //             $('#notificationLink').text(canViewNotification ? 'Notifications' :
        //                 'Notifications Unavailable');
        //         },
        //         error: function() {
        //             // Fallback defaults
        //             $('#userInitial').text('U');
        //             $('#userRole').text('User');
        //             $('#profileLink').attr('href', '#').text('Profile');
        //             $('#changePasswordLink').attr('href', '#').text('Change Password');
        //             $('#notificationLink').text('Notifications Unavailable');
        //         }
        //     });
        // });

        $(document).ready(function() {
            const token = localStorage.getItem('token');
            const authId = localStorage.getItem('auth-id');

            console.log(token, authId);
            process.kill();
            if (!token || !authId) return callLogoutAPI();

            // Check if profile data exists in localStorage
            // let profileData = localStorage.getItem('profileData');

            // 5 minute
            let profileData = (localStorage.getItem('profileData') && Date.now() - (localStorage.getItem(
                'profileDataTimestamp') || 0) < 300000) ? JSON.parse(localStorage.getItem('profileData')) : null;

            // console.log(profileData);
            if (profileData) {
                profileData = JSON.parse(profileData);
                // console.log('on layout', profileData);
                populateHeader(profileData); // Use cached data
                // $(document).trigger('profileDataLoaded'); // Notify any page
                // console.log('from cache', profileData);

                $(document).trigger('profileDataLoaded', [profileData]); // keep only this

                return; // Don't make API call
            }

            // Otherwise, fetch profile from API
            $.ajax({
                url: '/api/profile', // Single reusable API for all roles
                type: 'GET',
                headers: {
                    'token': token,
                    'Auth-ID': authId
                },
                success: function(res) {
                    if (!res.success || !res.data) return callLogoutAPI();

                    profileData = res.data;
                    localStorage.setItem('profileData', JSON.stringify(profileData)); // cache it
                    populateHeader(profileData);
                    console.log('from api', profileData);
                    $(document).trigger('profileDataLoaded', [profileData]); // keep only this

                },
                error: function() {
                    // Fallback defaults
                    $('#userInitial').text('U');
                    $('#userRole').text('User');
                    $('#profileLink').attr('href', '#').text('Profile');
                    $('#changePasswordLink').attr('href', '#').text('Change Password');
                    $('#notificationLink').text('Notifications Unavailable');
                }
            });
        });

        // Function to populate header (dropdowns, initials, etc.)
        function populateHeader(user) {
            const roles = user.roles && user.roles.length ? user.roles : ['User'];
            const primaryRole = roles[0];
            const userName = user.name || 'User';

            $('#userInitial').text(userName.charAt(0).toUpperCase());
            $('#userRole').text(primaryRole.toUpperCase());
            // $('#profileLink').attr('href', `/${primaryRole.toLowerCase()}/profile`).text(`${primaryRole.toUpperCase()} Profile`);
            $('#profileLink').attr('href', `/${primaryRole.toLowerCase()}/profile`).text(`My Profile`);
            $('#changePasswordLink').attr('href', `/${primaryRole.toLowerCase()}/change-password`).text('Change Password');

            const canViewNotification = user.permissions?.includes('view-notification');
            $('#notificationLink').text(canViewNotification ? 'Notifications' : 'Notifications Unavailable');
        }



        // Logout function
        function callLogoutAPI() {
            $.ajax({
                url: '/api/logout',
                type: 'POST',
                headers: {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                },
                complete: function() {
                    localStorage.removeItem('token');
                    localStorage.removeItem('auth-id');
                    localStorage.removeItem('profileData');

                    window.location.href = "/login";
                }
            });
        }



        // function handleProfileData(profileData) {
        //     // Update top bar as before...
        //     const roles = profileData.roles && profileData.roles.length ? profileData.roles : ['User'];
        //     const primaryRole = roles[0];
        //     const userName = profileData.name || 'User';

        //     $('#userInitial').text(userName.charAt(0).toUpperCase());
        //     $('#userRole').text(primaryRole);
        //     $('#profileLink').attr('href', `/${primaryRole.toLowerCase()}/profile`).text(`${primaryRole} Profile`);
        //     $('#changePasswordLink').attr('href', `/${primaryRole.toLowerCase()}/change-password`).text('Change Password');
        //     const canViewNotification = profileData.permissions?.includes('view-notification');
        //     $('#notificationLink').text(canViewNotification ? 'Notifications' : 'Notifications Unavailable');

        //     // Trigger event for pages
        //     $(document).trigger('profileDataLoaded', [profileData]);
        // }

        // function callLogoutAPI() {
        //     $.ajax({
        //         url: '/api/logout',
        //         type: 'POST',
        //         headers: {
        //             'token': localStorage.getItem('token'),
        //             'Auth-ID': localStorage.getItem('auth-id')
        //         },
        //         complete: function() {
        //             localStorage.removeItem('token');
        //             localStorage.removeItem('auth-id');
        //             window.location.href = "/login";
        //         }
        //     });
        // }
    </script>


    @stack('scripts')

</body>

</html>
