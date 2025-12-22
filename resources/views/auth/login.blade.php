@extends('layout')

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
@endsection

{{-- page scripts --}}
@section('page-scripts')
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
                            console.log(response.data);
                            process.kill();
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

    {{-- <script src="{{asset('js/scripts/pages/login.js')}}"></script> --}}
@endsection
