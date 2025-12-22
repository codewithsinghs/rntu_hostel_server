@extends('backend.layouts.app')

@push('navmenu')
    @include('backend.components.residents-nav')
@endpush
@push('scripts')
    {{-- <script type="text/javascript">
        $(document).ready(function() {
            if (!localStorage.getItem('token') && !localStorage.getItem('token')) {
                callLogoutAPI();
            } else if (localStorage.getItem('token') && !localStorage.getItem('auth-id')) {
                callLogoutAPI();
            } else if (!localStorage.getItem('token') && localStorage.getItem('auth-id')) {
                callLogoutAPI();
            } else {
                $.ajax({
                    url: '/api/resident/profile', // your API endpoint
                    type: 'GET',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'Auth-ID': localStorage.getItem('auth-id')
                    },
                    success: function(response) {
                        if (!response.success) {
                            callLogoutAPI();
                        }
                    },
                });
            }


        });

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
                    window.location.href = "/login";
                }
            });
        }
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            const token = localStorage.getItem('token');
            const authId = localStorage.getItem('auth-id');

            if (!token || !authId) {
                callLogoutAPI();
                return;
            }

            $.ajax({
                url: '/api/resident/profile',
                type: 'GET',
                headers: {
                    'token': token,
                    'Auth-ID': authId
                },
                success: function(res) {
                    if (!res.success || !res.data) {
                        callLogoutAPI();
                        return;
                    }

                    const data = res.data;
                    const userName = data.name || 'User';
                    const roles = (data.roles && data.roles.length) ? data.roles : ['User'];

                    // Update initial
                    $('#userInitial').text(userName.charAt(0).toUpperCase());

                    // Update role label with first role
                    $('#userRole').text(roles[0]);

                    // Populate profile/change-password links for all roles
                    const container = $('#profileLinksContainer');
                    container.empty(); // Clear default links

                    roles.forEach(role => {
                        container.append(`
                    <li>
                        <a class="dropdown-item custom-style-li" href="/${role.toLowerCase()}/profile">
                            ${role} Profile
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item custom-style-li" href="/${role.toLowerCase()}/change-password">
                            Change Password
                        </a>
                    </li>
                `);
                    });

                    // Notifications fallback text
                    const notificationText = (data.permissions && data.permissions.includes(
                            'view-notification')) ?
                        'Notifications' : 'Notifications Unavailable';
                    $('#notificationLink').text(notificationText);

                },
                error: function() {
                    // API failed — fallback links remain visible
                    $('#userRole').text('User');
                    $('#userInitial').text('U');
                    $('#notificationLink').text('Notifications Unavailable');
                }
            });
        });
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
                    window.location.href = "/login";
                }
            });
        }
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            const token = localStorage.getItem('token');
            const authId = localStorage.getItem('auth-id');

            if (!token || !authId) {
                callLogoutAPI();
                return;
            }

            // Check if we already have profile cached
            const cachedProfile = localStorage.getItem('userProfile');
            if (cachedProfile) {
                renderProfile(JSON.parse(cachedProfile));
                return; // **stop API call**
            }

            // Otherwise fetch from API
            // $.ajax({
            //     url: '/api/profile', // common API for all users
            //     type: 'GET',
            //     headers: {
            //         'token': token,
            //         'Auth-ID': authId
            //     },
            //     success: function(res) {
            //         if (!res.success || !res.data) {
            //             callLogoutAPI();
            //             return;
            //         }
            //         localStorage.setItem('userProfile', JSON.stringify(res.data));
            //         renderProfile(res.data);
            //     },
            //     error: function() {
            //         renderFallback();
            //     }
            // });
        });

        function renderProfile(data) {
            const userName = data.name || 'User';
            const roles = (data.roles && data.roles.length) ? data.roles : ['User'];

            $('#userInitial').text(userName.charAt(0).toUpperCase());
            $('#userRole').text(roles[0]);

            const container = $('#profileLinksContainer');
            container.empty();
            roles.forEach(role => {
                const r = role.toLowerCase();
                container.append(`
            <li><a class="dropdown-item" href="/${r}/profile">${role} Profile</a></li>
            <li><a class="dropdown-item" href="/${r}/change-password">Change Password</a></li>
        `);
            });

            $('#notificationLink').text(
                (data.permissions && data.permissions.includes('view-notification')) ?
                'Notifications' : 'Notifications Unavailable'
            );
        }

        function renderFallback() {
            $('#userInitial').text('U');
            $('#userRole').text('User');
            $('#notificationLink').text('Notifications Unavailable');
        }

        function callLogoutAPI() {
            $.ajax({
                url: '/api/logout',
                type: 'POST',
                headers: {
                    'token': localStorage.getItem('token'),
                    'Auth-ID': localStorage.getItem('auth-id')
                },
                complete: function() {
                    localStorage.clear(); // clear everything
                    window.location.href = "/login";
                }
            });
        }
    </script> --}}
@endpush
