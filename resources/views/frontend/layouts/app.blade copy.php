<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'RNTU Hostel')">
    <meta name="author" content="RNTU Hostel">

    <!-- Title -->
    <title>@yield('title', config('app.name'))</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Global Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/swiper-bundle.min.css') }}" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <!-- Bootstrap  v5.2.3 -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.css') }}">
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('frontend/css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/register.css') }}" />
    <!-- Font -->
    {{-- <link rel="stylesheet" href="{{ asset('/frontend/fonts/stylesheet.css') }}" /> --}}

    <!-- Page-specific Styles -->
    @stack('styles')

    <!-- External Libraries (Modern Hierarchy) -->
    <!-- Example: Tailwind, Alpine.js, FontAwesome -->
    {{-- <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"> --}}
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    @include('frontend.layouts.partials.header')

    <!-- Main Container -->
    <main class="container-fluid mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.partials.footer')

    <!-- Global Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    <!-- Page-specific Scripts -->
    @stack('scripts')

    <!-- Optional: Livewire or Vue support -->
    {{-- @livewireScripts --}}
</body>

</html>
