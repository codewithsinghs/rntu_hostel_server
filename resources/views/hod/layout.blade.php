<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOD Dashboard</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding: 10px;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        
        <h4 class="text-center text-light">Head Of Depart.</h4>



        <a href="{{ route('hod.dashboard') }}" class="{{ request()->routeIs('hod.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <!-- Request Management -->
        <a href="{{ route('hod.leave_requests') }}" id="leaveRequestsTab">
            <i class="bi bi-calendar-x"></i> Leave Requests
        </a>

                <!-- Logout Button -->
        <button type="button" onClick="callLogoutAPI()" class="btn btn-danger w-100">Logout</button>

    </div>

    <div class="content">
        @yield('content')
    </div>

        <script type="text/javascript">
        $(document).ready(function() {
           if(!localStorage.getItem('token') && !localStorage.getItem('token'))
           {
            callLogoutAPI();
           }
           else if(localStorage.getItem('token') && !localStorage.getItem('auth-id'))
           {
            callLogoutAPI();
           }
           else if(!localStorage.getItem('token') && localStorage.getItem('auth-id'))
           {
            callLogoutAPI();
           }
           else
           {
                $.ajax({
                    url: '/api/admin/profile', // your API endpoint
                    type: 'GET',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    success: function (response) {
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
            complete: function () {
                localStorage.removeItem('token');
                localStorage.removeItem('auth-id');
                window.location.href = "/login";
            }
        });
        }

    </script>
</body>

</html>