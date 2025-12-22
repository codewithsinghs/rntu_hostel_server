<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Default site description')">
    <meta name="author" content="Your Company Name">

    <!-- Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Global Styles -->
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <!-- Bootstrap  v5.2.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    {{-- <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}"> --}}
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/register.css') }}" />

    <!-- Page-specific Styles -->
    @stack('styles')
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="wrapper d-flex flex-column min-vh-100">
    <!-- Header -->
    @include('frontend.layouts.partials.header')

    <!-- Main Container -->
    {{-- <main class="container-fluid mx-auto px-4 py-6"> --}}
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.partials.footer')
 </div>
    <!-- Global Scripts -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script> -->

    <!-- Page-specific Scripts -->
    @stack('scripts')
       
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        @yield('page-scripts')
        <script type="text/javascript">
            $(document).ready(function() {
            if(localStorage.getItem('token') && localStorage.getItem('token'))
            {
                $.ajax({
                    url: '/api/authenticate-users', // your API endpoint
                    type: 'POST',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'Auth-ID': localStorage.getItem('auth-id')
                    },
                    success: function (response) {
            if (response.success && response.data && response.data.role_name) {
                switch (response.data.role_name) {
                    case "admin":
                        window.location.href = "/admin/dashboard";
                        break;
                    case "super_admin":
                        window.location.href = "/super-admin/dashboard";
                        break;
                    case "accountant":
                        window.location.href = "/accountant/dashboard";
                        break;
                    case "warden":
                        window.location.href = "/warden/dashboard";
                        break;
                    case "security":
                        window.location.href = "/security/dashboard";
                        break;
                    case "mess_manager":
                        window.location.href = "/mess-manager/dashboard";
                        break;
                    case "gym_manager":
                        window.location.href = "/gym-manager/dashboard";
                        break;
                    case "hod":
                        window.location.href = "/hod/dashboard";
                        break;
                    case "resident":
                        window.location.href = "/resident/dashboard";
                        break;
                    default:
                        callLogoutAPI();
                }

                } else {
                    callLogoutAPI();
                }                    
            },
                    error: function (xhr, status, error) {
                        console.error("Error authenticating user:", error);
                        callLogoutAPI();
                    }
                    
                });


            }
            

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
    });
        </script>
</body>

    


</html>
