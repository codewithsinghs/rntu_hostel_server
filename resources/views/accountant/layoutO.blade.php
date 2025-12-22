<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant Dashboard</title>

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
        
        <h4 class="text-center text-light">Accountant</h4>

        <!-- Logout Button -->
        <button type="button" onClick="callLogoutAPI()" class="btn btn-danger w-100">Logout</button>


        <a href="{{ route('accountant.dashboard') }}" class="{{ request()->routeIs('accountant.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <!-- Master Management (Dropdown) -->
        <a class="d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#masterMenu" role="button" aria-expanded="false" aria-controls="masterMenu">
            <span><i class="bi bi-gear"></i> Master Management</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse ps-4" id="masterMenu">
            <a href="{{ route('accountant.fee_heads') }}" id="FeeHeadTab">
                <i class="bi bi-building"></i> Fee Head
            </a>
       </div>

        <a href="{{ route('accountant.fees') }}" class="nav-link text-white">
            <i class="bi bi-currency-dollar"></i> Manage Fees {{-- Changed icon to Bootstrap Icon --}}
        </a>

        <a href="{{ route('accountant.account') }}" class="{{ request()->routeIs('accountant.account') ? 'active' : '' }}">
            <i class="bi bi-box-arrow-in-left"></i> Checkout Requests
        </a>

        <a href="{{ url('/accountant/resident-payments') }}" class="{{ request()->is('accountant/resident-payments') ? 'active' : '' }}">
            <i class="bi bi-cash-coin"></i> Resident History and Payments
        </a>

        <a href="{{ route('accountant.feemaster') }}" class="{{ request()->is('accountant/feemaster') ? 'active' : '' }}">
            <i class="bi bi-wallet-fill"></i> Fee Master
        </a>

        <a href="{{ route('accountant.guests') }}" class="{{ request()->routeIs('accountant.guests') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> Guest Management
        </a>

        <a href="{{ route('accountant.fines') }}" class="{{ request()->routeIs('accountant.guests') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> Fines Management
        </a>

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