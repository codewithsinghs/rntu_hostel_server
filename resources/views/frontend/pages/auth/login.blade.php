@extends('frontend.layouts.app')

@section('title', 'Login')
@section('meta_description', '.')

@push('styles')
@endpush

@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3>Login</h3>
                    </div>
                    <div class="card-body">
                        <div id="loginMessage"></div>
                        <!-- <form method="POST" action="{{ route('login') }}"> -->
                        <form id="loginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" id="email" class="form-control" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" id="loginBtn" data-login-url="{{ url('/api/admin/login') }}"
                                    class="btn btn-primary">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Options -->
    <!-- <div class="mt-4 text-center">
                    <a href="#" id="otpTrigger" class="d-block text-decoration-none mb-2">📲 Login with OTP</a>
                    <a href="#" id="forgotTrigger" class="d-block text-decoration-none mb-2">🧠 Forgot Password</a>
                    <a href="#" id="registerTrigger" class="d-block text-decoration-none">📝 Register</a>
                  </div> -->








@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="{{ asset('js/scripts/pages/login.js') }}"></script> --}}
    {{-- <script type="text/javascript">
        $(document).ready(function() {
            if (localStorage.getItem('token') && localStorage.getItem('token')) {
                $.ajax({
                    url: '/api/authenticate-users', // your API endpoint
                    type: 'POST',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'Auth-ID': localStorage.getItem('auth-id')
                    },
                    success: function(response) {
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
                    error: function(xhr, status, error) {
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
                    complete: function() {
                        localStorage.removeItem('token');
                        localStorage.removeItem('auth-id');
                        window.location.href = "/login";
                    }
                });
            }
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function(e) {
                e.preventDefault();
                const loginUrl = $("#loginBtn").data("login-url");

                $.ajax({
                    url: loginUrl,
                    type: "POST",
                    data: {
                        email: $("#email").val(),
                        password: $("#password").val(),
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(response) {
                        if (response.success) {
                            // Store token in localStorage
                            localStorage.setItem("token", response.data.token);
                            localStorage.setItem("auth-id", response.data.user.id);
                            // console.log(response, response.data.redirect_url, response.data.user.id, response.data.token);
                            // process.kill();
                            // Redirect to dashboard based on backend
                            window.location.href = response.data.redirect_url;
                        } else {
                            $("#loginMessage").html(
                                '<div class="alert alert-danger">' + response.message +
                                "</div>"
                            );
                        }
                    },
                    error: function(xhr) {
                        $("#loginMessage").html(
                            '<div class="alert alert-danger">' +
                            (xhr.responseJSON?.message || "An error occurred") +
                            "</div>"
                        );
                    }
                });
            });
        });
    </script>
@endpush
