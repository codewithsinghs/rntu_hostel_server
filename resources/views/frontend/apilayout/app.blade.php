<!DOCTYPE html>
<html>

<head>
    <title>Auth System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        @yield('content')
    </div>

    {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
        axios.defaults.baseURL = "{{ url('/api') }}";
    </script> --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Global Axios defaults
        axios.defaults.baseURL = '/api'; // Laravel API prefix
        axios.defaults.headers.common['Accept'] = 'application/json';

        // Utility function for SweetAlert feedback
        function showMessage(type, message) {
            Swal.fire({
                icon: type,
                text: message,
                confirmButtonColor: type === 'success' ? '#3085d6' : '#d33'
            });
        }

        // Store token in localStorage (for API calls)
        function saveToken(token) {
            localStorage.setItem('auth_token', token);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        }

        function logoutUser() {
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
        }

        // On page load, auto-attach token if available
        if (localStorage.getItem('auth_token')) {
            axios.defaults.headers.common['Authorization'] =
                'Bearer ' + localStorage.getItem('auth_token');
        }
    </script>



    @stack('scripts')
</body>

</html>
