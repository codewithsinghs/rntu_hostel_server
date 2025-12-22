@extends('frontend.apilayout.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg rounded-4 p-4" style="width: 420px;">
            <h4 class="text-center mb-3 fw-bold">Reset Password</h4>
            <p class="text-muted text-center mb-4">Use your registered email or mobile</p>

            {{-- Request OTP --}}
            <form id="passwordOtpForm">
                <div class="mb-3">
                    <label class="form-label">Email or Mobile</label>
                    <input type="text" name="identifier" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <button class="btn btn-warning w-100 rounded-3">Send OTP</button>
            </form>

            {{-- Verify OTP + Reset --}}
            <form id="resetPasswordForm" class="d-none mt-3">
                <div class="mb-3">
                    <label class="form-label">Enter OTP</label>
                    <input type="text" name="otp" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                </div>
                <button class="btn btn-success w-100 rounded-3">Reset Password</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="small">Back to Login</a>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            // Request OTP
            $("#passwordOtpForm").on("submit", function(e) {
                e.preventDefault();
                let identifier = $(this).find("input[name=identifier]").val();

                axios.post("/auth/forgot-password", {
                        identifier
                    })
                    .then(res => {
                        showMessage("success", "OTP sent to your email/mobile");
                        $("#passwordOtpForm").addClass("d-none");
                        $("#resetPasswordForm").removeClass("d-none");
                    })
                    .catch(err => {
                        showMessage("error", err.response?.data?.message || "Failed to send OTP");
                    });
            });

            // Reset password with OTP
            $("#resetPasswordForm").on("submit", function(e) {
                e.preventDefault();

                let formData = {
                    otp: $(this).find("input[name=otp]").val(),
                    password: $(this).find("input[name=password]").val(),
                    password_confirmation: $(this).find("input[name=password_confirmation]").val(),
                };

                axios.post("/auth/reset-password", formData)
                    .then(res => {
                        showMessage("success", "Password reset successful!");
                        window.location.href = "/login";
                    })
                    .catch(err => {
                        let errors = err.response?.data?.errors || {};
                        let message = Object.values(errors).flat().join("\n") ||
                            "Failed to reset password";
                        showMessage("error", message);
                    });
            });
        });
    </script>
@endpush
