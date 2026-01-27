{{-- @php
    $role = request()->segment(1); // Gets 'admin' from /admin/change-password
@endphp

@extends("$role.layout") --}}

@php
    $role = request()->segment(1); // "admin", "resident", etc.
    $layouts = [
        'admin' => 'admin.layout',
        'resident' => 'resident.layout',
        'accountant' => 'accountant.layout',
        'admission' => 'admission.layout',
        'warden' => 'warden.layout',
        'admission' => 'admission.layout',
        'guest' => 'guest.layout',
    ];
    $layout = $layouts[$role] ?? 'backend.layouts.app'; // fallback
@endphp

@extends($layout)


@section('content')
    <div class="container mt-5">
        <h3>🔐 Change Password</h3>

        <form id="changePasswordForm">
            @csrf

            <div class="mb-3">
                <label for="method">Choose Method</label>
                <select id="method" class="form-select" onchange="toggleMethod()">
                    <option value="password">Use Current Password</option>
                    <option value="otp">Use OTP</option>
                </select>
            </div>

            <div id="passwordMethod">
                <div class="mb-3">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                </div>
            </div>

            <div id="otpMethod" style="display: none;">
                <div class="mb-3">
                    <label for="email">Email (for OTP)</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-secondary" onclick="sendOtp()">Send OTP</button>
                </div>
                <div class="mb-3">
                    <label for="otp">Enter OTP</label>
                    <input type="text" class="form-control" id="otp" name="otp">
                </div>
            </div>

            <div class="mb-3">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>

            <button type="submit" class="btn btn-primary">Change Password</button>
        </form>

        <div id="responseMessage" class="mt-3"></div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        function toggleMethod() {
            const method = document.getElementById('method').value;
            document.getElementById('passwordMethod').style.display = method === 'password' ? 'block' : 'none';
            document.getElementById('otpMethod').style.display = method === 'otp' ? 'block' : 'none';
        }

        function sendOtp() {
            const email = document.getElementById('email').value;
            fetch('/api/send-otp', {
                    method: 'POST',
                    headers: {
                        "Accept": "application/json",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    },
                    body: JSON.stringify({
                        email
                    })
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                });
        }

        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const method = document.getElementById('method').value;
            const payload = {
                new_password: document.getElementById('new_password').value
            };

            if (method === 'password') {
                payload.current_password = document.getElementById('current_password').value;
            } else {
                payload.email = document.getElementById('email').value;
                payload.otp = document.getElementById('otp').value;
            }

            fetch('/api/change-password', {
                    method: 'POST',
                    headers: {
                        "Accept": "application/json",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('responseMessage').innerHTML =
                        `<div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">${data.message}</div>`;
                });
        });
    </script> --}}

    <script>
    function toggleMethod() {
        const method = document.getElementById('method').value;
        document.getElementById('passwordMethod').style.display = method === 'password' ? 'block' : 'none';
        document.getElementById('otpMethod').style.display = method === 'otp' ? 'block' : 'none';
    }

    function sendOtp() {
        const email = document.getElementById('email').value;

        fetch('/api/admin/send-otp', {
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json", // ✅ important
                "token": localStorage.getItem('token'),
                "auth-id": localStorage.getItem('auth-id')
            },
            body: JSON.stringify({ email })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('responseMessage').innerHTML =
                `<div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">${data.message}</div>`;
        })
        .catch(err => console.error("OTP Error:", err));
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const method = document.getElementById('method').value;
        const payload = { new_password: document.getElementById('new_password').value };

        if (method === 'password') {
            payload.current_password = document.getElementById('current_password').value;
        } else {
            payload.email = document.getElementById('email').value;
            payload.otp   = document.getElementById('otp').value;
        }

        fetch('/api/admin/change-password', {
            method: 'POST',
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json", // ✅ important
                "token": localStorage.getItem('token'),
                "auth-id": localStorage.getItem('auth-id')
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('responseMessage').innerHTML =
                `<div class="alert ${data.success ? 'alert-success' : 'alert-danger'}">${data.message}</div>`;
        })
        .catch(err => console.error("Password Change Error:", err));
    });
</script>

@endpush
