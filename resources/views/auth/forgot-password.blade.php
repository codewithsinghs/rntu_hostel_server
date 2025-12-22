@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h4 class="mb-4 text-center">🔐 Forgot Password</h4>

          <!-- Step 1: Email Input -->
          <div id="step-email">
            <input type="email" id="email" class="form-control mb-3" placeholder="Enter your email">
            <button class="btn btn-primary w-100" onclick="sendOtp()">Send OTP</button>
          </div>

          <!-- Step 2: OTP Verification -->
          <div id="step-otp" class="d-none">
            <input type="text" id="otp" class="form-control mb-3" placeholder="Enter OTP">
            <button class="btn btn-success w-100" onclick="verifyOtp()">Verify OTP</button>
          </div>

          <!-- Step 3: Reset Password -->
          <div id="step-reset" class="d-none">
            <input type="password" id="newPassword" class="form-control mb-2" placeholder="New Password">
            <input type="password" id="confirmPassword" class="form-control mb-3" placeholder="Confirm Password">
            <button class="btn btn-dark w-100" onclick="resetPassword()">Reset Password</button>
          </div>

          <!-- Status Message -->
          <div id="statusMessage" class="mt-3 text-center"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  let userEmail = '';

  function sendOtp() {
    userEmail = document.getElementById('email').value;
    if (!userEmail) return alert('Please enter your email.');

    fetch('/api/auth/forgot-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: userEmail })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('step-email').classList.add('d-none');
        document.getElementById('step-otp').classList.remove('d-none');
        showMessage('OTP sent to your email.', 'success');
      } else {
        showMessage(data.message || 'Failed to send OTP.', 'danger');
      }
    });
  }

  function verifyOtp() {
    const otp = document.getElementById('otp').value;
    if (!otp) return alert('Enter the OTP.');

    fetch('/api/auth/verify-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: userEmail, otp })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('step-otp').classList.add('d-none');
        document.getElementById('step-reset').classList.remove('d-none');
        showMessage('OTP verified. You can now reset your password.', 'success');
      } else {
        showMessage(data.message || 'Invalid OTP.', 'danger');
      }
    });
  }

  function resetPassword() {
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    if (!newPassword || !confirmPassword) return alert('Fill in both password fields.');
    if (newPassword !== confirmPassword) return alert('Passwords do not match.');

    fetch('/api/auth/reset-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: userEmail, newPassword })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        showMessage('Password reset successful. Redirecting to login...', 'success');
        setTimeout(() => window.location.href = '/login', 3000);
      } else {
        showMessage(data.message || 'Failed to reset password.', 'danger');
      }
    });
  }

  function showMessage(message, type) {
    document.getElementById('statusMessage').innerHTML = `
      <div class="alert alert-${type}">${message}</div>
    `;
  }
</script>
@endsection
