@extends('frontend.apilayout.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">Login</div>
        <div class="card-body">
            <form id="loginForm">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="{{ route('forgot') }}" class="btn btn-link">Forgot Password?</a>
            </form>
            <div id="loginMessage" class="mt-3"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                let res = await axios.post('/auth/login', {
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                });
                document.getElementById('loginMessage').innerHTML =
                    `<div class="alert alert-success">${res.data.message}</div>`;
                localStorage.setItem('token', res.data.token);
            } catch (err) {
                document.getElementById('loginMessage').innerHTML =
                    `<div class="alert alert-danger">${err.response.data.message}</div>`;
            }
        });
    </script>
@endpush
