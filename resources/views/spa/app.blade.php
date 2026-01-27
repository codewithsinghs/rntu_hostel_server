<!-- resources/views/app.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Hostel Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-light">

    <div id="app">
        <div id="globalMessage"></div>
        <div id="sidebar"></div>
        <div id="content"></div>
    </div>

    <script>
        window.APP_CONTEXT = {
            role: "{{ request()->segment(1) }}",
            baseUrl: "{{ url('/') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
