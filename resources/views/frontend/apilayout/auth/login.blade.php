@extends('frontend.apilayout.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg rounded-4 p-4" style="width: 420px;">
            <h4 class="text-center mb-3 fw-bold">Welcome Back</h4>
            <p class="text-muted text-center mb-4">Login to your account</p>

            {{-- Password Login --}}
            <form id="passwordLoginForm" class="mb-3">
                <div class="mb-3">
                    <label class="form-label">Email or Mobile</label>
                    <input type="text" class="form-control rounded-3" name="identifier" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control rounded-3" name="password" required>
                </div>
                <button class="btn btn-primary w-100 rounded-3">Login</button>
            </form>

            {{-- OTP Login --}}
            <form id="otpRequestForm" class="mb-3 d-none">
                <div class="mb-3">
                    <label class="form-label">Email or Mobile</label>
                    <input type="text" class="form-control rounded-3" name="identifier" required>
                </div>
                <button class="btn btn-warning w-100 rounded-3">Send OTP</button>
            </form>

            <form id="otpVerifyForm" class="mb-3 d-none">
                <div class="mb-3">
                    <label class="form-label">Enter OTP</label>
                    <input type="text" class="form-control rounded-3" name="otp" required>
                </div>
                <button class="btn btn-success w-100 rounded-3">Verify OTP</button>
            </form>

            <div class="text-center mt-3">
                <a href="javascript:void(0)" id="switchToOtp" class="small">Login with OTP</a> |
                <a href="javascript:void(0)" id="switchToPassword" class="small d-none">Login with Password</a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('forgot.password') }}" class="small">Forgot Password?</a>
            </div>

            <div class="text-center mt-2">
                <p class="small mb-0">Don’t have an account?
                    <a href="{{ route('register') }}" class="fw-bold">Register</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('switchToOtp').addEventListener('click', function() {
            document.getElementById('passwordLoginForm').classList.add('d-none');
            document.getElementById('otpRequestForm').classList.remove('d-none');
            this.classList.add('d-none');
            document.getElementById('switchToPassword').classList.remove('d-none');
        });
        document.getElementById('switchToPassword').addEventListener('click', function() {
            document.getElementById('passwordLoginForm').classList.remove('d-none');
            document.getElementById('otpRequestForm').classList.add('d-none');
            document.getElementById('otpVerifyForm').classList.add('d-none');
            this.classList.add('d-none');
            document.getElementById('switchToOtp').classList.remove('d-none');
        });
    </script>
@endsection
    @push('scripts')
        <script>
            $(function() {
                // Password login
                $("#passwordLoginForm").on("submit", function(e) {
                    e.preventDefault();

                    let formData = {
                        identifier: $(this).find("input[name=identifier]").val(),
                        password: $(this).find("input[name=password]").val()
                    };

                    axios.post("/auth/login", formData)
                        .then(res => {
                            saveToken(res.data.token);
                            showMessage("success", "Login successful!");
                            window.location.href = "/dashboard";
                        })
                        .catch(err => {
                            showMessage("error", err.response?.data?.message || "Login failed");
                        });
                });

                // OTP request
                $("#otpRequestForm").on("submit", function(e) {
                    e.preventDefault();
                    let identifier = $(this).find("input[name=identifier]").val();

                    axios.post("/auth/send-otp", {
                            identifier
                        })
                        .then(res => {
                            showMessage("success", "OTP sent successfully!");
                            $("#otpRequestForm").addClass("d-none");
                            $("#otpVerifyForm").removeClass("d-none");
                        })
                        .catch(err => {
                            showMessage("error", err.response?.data?.message || "Error sending OTP");
                        });
                });

                // OTP verify
                $("#otpVerifyForm").on("submit", function(e) {
                    e.preventDefault();
                    let otp = $(this).find("input[name=otp]").val();

                    axios.post("/auth/verify-otp", {
                            otp
                        })
                        .then(res => {
                            saveToken(res.data.token);
                            showMessage("success", "OTP verified! Logged in.");
                            window.location.href = "/dashboard";
                        })
                        .catch(err => {
                            showMessage("error", err.response?.data?.message || "OTP verification failed");
                        });
                });
            });
        </script>
    @endpush

