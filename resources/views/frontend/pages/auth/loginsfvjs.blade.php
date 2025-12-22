<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hostel Management Auth</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body { background-color: #f0f2f5; }
    .auth-card { max-width: 500px; margin: auto; }
    .nav-tabs .nav-link.active { background-color: #0d6efd; color: #fff; }
  </style>
</head>
<body>

<div class="container py-5">
  <div class="card shadow auth-card">
    <div class="card-header text-center">
      <h4>Hostel Management System</h4>
    </div>
    <div class="card-body">
      <!-- Tabs -->
      <ul class="nav nav-tabs mb-3" id="authTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#loginTab">Login</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#otpTab">OTP Login</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#forgotTab">Forgot Password</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#registerTab">Register</button></li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">
        <!-- Login -->
        <div class="tab-pane fade show active" id="loginTab">
          <form id="loginForm">
            <div id="loginMessage"></div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required />
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" class="form-control" name="password" required />
            </div>
            <button type="submit" class="btn btn-primary w-100" data-login-url="/api/admin/login">Login</button>
          </form>
        </div>

        <!-- OTP Login -->
        <div class="tab-pane fade" id="otpTab">
          <form id="otpRequestForm">
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required />
            </div>
            <button type="submit" class="btn btn-warning w-100">Send OTP</button>
          </form>
          <form id="otpVerifyForm" class="mt-3" style="display:none;">
            <div class="mb-3">
              <label>Enter OTP</label>
              <input type="text" class="form-control" name="otp" required />
            </div>
            <button type="submit" class="btn btn-success w-100">Verify OTP</button>
          </form>
        </div>

        <!-- Forgot Password -->
        <div class="tab-pane fade" id="forgotTab">
          <form id="forgotPasswordForm">
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required />
            </div>
            <button type="submit" class="btn btn-secondary w-100">Send Reset Link</button>
          </form>
        </div>

        <!-- Register -->
        <div class="tab-pane fade" id="registerTab">
          <form id="registrationForm">
            <div class="mb-3">
              <label>Scholar Number</label>
              <input type="text" class="form-control" name="scholar_number" required />
            </div>
            <div class="mb-3">
              <label>Full Name</label>
              <input type="text" class="form-control" name="name" required />
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" name="email" required />
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" class="form-control" name="password" required />
            </div>
            <button type="submit" class="btn btn-dark w-100" data-url="/api/register">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AJAX Logic -->
<script>
  // Login
  document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const url = form.querySelector('button').dataset.loginUrl;
    const data = {
      email: form.email.value,
      password: form.password.value
    };
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    document.getElementById('loginMessage').innerHTML = res.ok
      ? '<div class="alert alert-success">Login successful</div>'
      : `<div class="alert alert-danger">${result.message}</div>`;
    if (res.ok) window.location.href = '/dashboard';
  });

  // OTP Request
  document.getElementById('otpRequestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = e.target.email.value;
    const res = await fetch('/api/send-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    if (res.ok) document.getElementById('otpVerifyForm').style.display = 'block';
  });

  // OTP Verify
  document.getElementById('otpVerifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const otp = e.target.otp.value;
    const res = await fetch('/api/verify-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ otp })
    });
    if (res.ok) window.location.href = '/dashboard';
  });

  // Forgot Password
  document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = e.target.email.value;
    const res = await fetch('/api/forgot-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    if (res.ok) alert('Reset link sent to your email');
  });

  // Registration
  document.getElementById('registrationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const url = form.querySelector('button').dataset.url;
    const data = {
      scholar_number: form.scholar_number.value,
      name: form.name.value,
      email: form.email.value,
      password: form.password.value
    };
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    alert(res.ok ? 'Registration successful' : result.message);
  });
</script>

</body>
</html>
 <!-- Scripts -->
<script>
  // Login
  document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const url = form.querySelector('button').dataset.loginUrl;
    const data = {
      email: form.email.value,
      password: form.password.value
    };
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    document.getElementById('loginMessage').innerHTML = res.ok
      ? '<div class="alert alert-success">Login successful</div>'
      : `<div class="alert alert-danger">${result.message}</div>`;
    if (res.ok) window.location.href = '/dashboard';
  });

  // OTP Request
  document.getElementById('otpRequestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = e.target.email.value;
    const res = await fetch('/api/send-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    if (res.ok) document.getElementById('otpVerifyForm').style.display = 'block';
  });

  // OTP Verify
  document.getElementById('otpVerifyForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const otp = e.target.otp.value;
    const res = await fetch('/api/verify-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ otp })
    });
    if (res.ok) window.location.href = '/dashboard';
  });

  // Forgot Password
  document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = e.target.email.value;
    const res = await fetch('/api/forgot-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    if (res.ok) alert('Reset link sent to your email');
  });

  // Registration
  document.getElementById('registrationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = e.target;
    const url = form.querySelector('button').dataset.url;
    const data = {
      scholar_number: form.scholar_number.value,
      name: form.name.value,
      email: form.email.value,
      password: form.password.value
    };
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    const result = await res.json();
    alert(res.ok ? 'Registration successful' : result.message);
  });
</script>