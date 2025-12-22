@extends('frontend.apilayout.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">Reset Password</div>
        <div class="card-body">
            <form id="resetForm">
                <input type="hidden" id="token" value="{{ request()->route('token') }}">
                <input type="hidden" id="email" value="{{ request()->query('email') }}">

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="password" id="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" id="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger">Reset</button>
            </form>
            <div id="resetMessage" class="mt-3"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('resetForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            try {
                let res = await axios.post('/auth/reset-password', {
                    token: document.getElementById('token').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation').value,
                });
                document.getElementById('resetMessage').innerHTML =
                    `<div class="alert alert-success">${res.data.message}</div>`;
            } catch (err) {
                document.getElementById('resetMessage').innerHTML =
                    `<div class="alert alert-danger">${err.response.data.message}</div>`;
            }
        });
    </script>
@endpush
