<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Default site description')">
    <meta name="author" content="shodh Shikhar 2026">

    <!-- Title -->
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables Core + Buttons + Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- ✅ CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('backend/css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/css/mainStyle.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/fonts/stylesheet.css') }}" />

    <!-- Page-specific Styles -->
    @stack('styles')

</head>

<body>

    <div class="main-container">

        <!-- sidebar -->
        @include('backend.components.sidebar')

        <!-- Header -->
        @include('backend.layouts.partials.header')

        <!-- Main Content -->
        <main class="main-content">

            @yield('content')

        </main>

    </div>

    <!-- Footer -->
    @include('backend.layouts.partials.footer')

    @stack('scripts')

</body>

</html>