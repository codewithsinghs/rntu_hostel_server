@extends('frontend.layouts.app')

@section('title', 'Our Services')
@section('meta_description', 'Explore the range of professional services we offer.')

@push('styles')
@endpush
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h3>Registration Status</h3>
                    </div>
                    <div class="card-body">
                        <div id="loginMessage"></div>
                        <form id="guest-login">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="mobile" class="form-label">Mobile Number :</label>
                                <input type="mobile" class="form-control" name="mobile" required>
                            </div>

                            <div class="d-grid w-50">
                                <button type="submit" class="btn btn-primary">Check Status</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-scripts')
    {{-- <script type="text/javascript">
        $(document).ready(function() {
            $('#guest-login').on('submit', function(event) {
                event.preventDefault();

                const formData = $(this).serialize();

                $('#loginMessage').html(
                    '<span style="color: blue;">Checking registration status...</span>');

                $.ajax({
                    url: '/api/guest/login',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // only if route is in web.php
                    },
                    success: function(response) {
                        $('#loginMessage').html('<span style="color: green;">' + response.data
                            .message + '</span>');

                        // Store token & auth-id in localStorage
                        localStorage.setItem('token', response.data.token);
                        localStorage.setItem('auth-id', response.data.user.id);

                        window.location.href = "/guest/dashboard";
                        // Now check guest authentication
                        // $.ajax({
                        //     url: '/api/guest-authentication',
                        //     type: 'POST',
                        //     headers: {
                        //         'token': localStorage.getItem('token'),
                        //         'auth-id': localStorage.getItem('auth-id')
                        //     },
                        //     success: function(response) {
                        //         if (response.success && response.data) {
                        //             window.location.href = "/guest/dashboard";
                        //         } else {
                        //             $('#loginMessage').append(
                        //                 '<div class="alert alert-danger">Role not found or not authorized!</div>'
                        //                 );
                        //         }
                        //     },
                        //     error: function(xhr, status, error) {
                        //         $('#loginMessage').append(
                        //             '<div class="alert alert-danger">Error during guest authentication.</div>'
                        //             );
                        //         console.error(xhr.responseText);
                        //     }
                        // });
                    },
                    error: function(xhr, status, error) {
                        $('#loginMessage').html(
                            '<div class="alert alert-danger">Login failed. Please check your credentials.</div>'
                        );
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script> --}}

    {{-- <script type="text/javascript">
        $(function() {

            const GuestLogin = {

                selectors: {
                    form: '#guest-login',
                    message: '#loginMessage',
                    submitBtn: '#guest-login button[type="submit"]'
                },

                init() {
                    $(this.selectors.form).on('submit', this.handleSubmit.bind(this));
                },

                handleSubmit(e) {
                    e.preventDefault();

                    const $form = $(this.selectors.form);
                    const $btn = $(this.selectors.submitBtn);

                    $btn.prop('disabled', true);
                    this.showMessage('Checking registration status...', 'info');

                    $.ajax({
                            url: '/api/guest/login',
                            type: 'POST',
                            data: $form.serialize(),
                            headers: this.getHeaders(),
                        })
                        .done(response => this.handleSuccess(response))
                        .fail(xhr => this.handleError(xhr))
                        .always(() => $btn.prop('disabled', false));
                },

                handleSuccess(response) {
                    if (!response.success || !response.data) {
                        this.showMessage('Unexpected response from server.', 'danger');
                        return;
                    }

                    const {
                        token,
                        user,
                        redirect_url
                    } = response.data;

                    if (!token || !user?.id) {
                        this.showMessage('Authentication failed. Invalid session data.', 'danger');
                        return;
                    }

                    // Store auth data
                    localStorage.setItem('token', token);
                    localStorage.setItem('auth-id', user.id);

                    this.showMessage('Login successful. Redirecting...', 'success');

                    setTimeout(() => {
                        window.location.href = redirect_url || '/guest/dashboard';
                    }, 800);
                },

                handleError(xhr) {
                    let message = 'Login failed. Please check your credentials.';

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }

                    if (xhr.status === 401) {
                        message = 'Invalid email or mobile number.';
                    }

                    this.showMessage(message, 'danger');
                    console.error(xhr.responseText);
                },

                getHeaders() {
                    const headers = {
                        'Accept': 'application/json'
                    };

                    // Only needed if route is in web.php
                    const csrf = $('meta[name="csrf-token"]').attr('content');
                    if (csrf) {
                        headers['X-CSRF-TOKEN'] = csrf;
                    }

                    return headers;
                },

                showMessage(message, type = 'info') {
                    $(this.selectors.message).html(`
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
                }
            };

            GuestLogin.init();
        });
    </script> --}}

    <script type="text/javascript">
        $(function() {

            const GuestLogin = {

                selectors: {
                    form: '#guest-login',
                    message: '#loginMessage',
                    submitBtn: '#guest-login button[type="submit"]'
                },

                init() {
                    $(this.selectors.form).on('submit', this.submit.bind(this));
                },

                submit(e) {
                    e.preventDefault();

                    const $btn = $(this.selectors.submitBtn);
                    $btn.prop('disabled', true);

                    this.showMessage('Checking registration status...', 'info');

                    $.ajax({
                            url: '/api/guest/login',
                            method: 'POST',
                            data: $(this.selectors.form).serialize(),
                            headers: this.headers()
                        })
                        .done(res => this.onSuccess(res))
                        .fail(xhr => this.onError(xhr))
                        .always(() => $btn.prop('disabled', false));
                },

                onSuccess(res) {
                    console.log(res);
                    // if (!res.success || !res.access_token || !res.profile) {
                    if (!res.success || !res.data.token || !res.data.user) {
                        this.showMessage('Invalid authentication response.', 'danger');
                        return;
                    }

                    console.log('holding here');
                    // ✅ Store auth data (single source of truth)
                    localStorage.setItem('token', res.data.token);
                    localStorage.setItem('auth-id', res.data.user.id);
                    // localStorage.setItem('auth-type', res.data.user.auth_type);
                    localStorage.setItem('role', res.data.user.role);

                    this.showMessage('Login successful. Redirecting...', 'success');

                    setTimeout(() => {
                        window.location.href = res.redirect || '/guest/dashboard';
                    }, 700);
                },

                onError(xhr) {
                    let message = 'Login failed. Please try again.';

                    if (xhr.status === 401) {
                        message = 'Invalid email or mobile number.';
                    }

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        message = Object.values(xhr.responseJSON.errors)
                            .flat()
                            .join('<br>');
                    }

                    this.showMessage(message, 'danger');
                    console.error(xhr.responseText);
                },

                headers() {
                    const headers = {
                        'Accept': 'application/json'
                    };

                    const csrf = $('meta[name="csrf-token"]').attr('content');
                    if (csrf) headers['X-CSRF-TOKEN'] = csrf;

                    return headers;
                },

                showMessage(message, type = 'info') {
                    $(this.selectors.message).html(`
                <div class="alert alert-${type} alert-dismissible fade show">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
                }
            };

            GuestLogin.init();
        });
    </script>


@endsection
