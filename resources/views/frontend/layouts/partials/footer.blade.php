

<!-- Registration Status Check -->
{{-- <div class="login-popup-overlay" id="registerPopup">
    <div class="login-popup">
        <div class="popup-left">
            <img src="../public/reg1.png" alt="Login Visual" />
        </div>
        <div class="popup-right">
            <button class="close-btn" id="rclosePopup">&times;</button>

            <div class="xfw-form-wrapper-container center-line">
                <p class="title">Registration Status Check</p>
                <div class="xfw-form-wrapper-register">
                    <div class="xfw-form-panel xfw-form-panel-2 xfw-student-form xfw-active-form">
                        <form id="studentLoginForm">
                            <label for="loginInput">Mobile Number</label>
                            <input type="text" id="loginInput" placeholder="Enter Mobile Number" maxlength="10" required />

                            <label for="loginInput">Email Address</label>
                            <input type="text" id="loginInput" placeholder="Enter Email Address" maxlength="10" required />


                            <button class="btn-login-submit" type="submit">
                                Login
                            </button>
                            <p class="register-link">
                                Don’t have an account? <a href="#">Register Here</a>
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Login  Check -->
<!-- Registration Status Check -->
{{-- <div class="login-popup-overlay" id="loginPopup">
    <div class="login-popup">
        <div class="popup-left">
            <img src="../public/reg1.png" alt="Login Visual" />
        </div>
        <div class="popup-right">
            <button class="close-btn" id="closePopup">&times;</button>
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="student">
                    Student Login
                </button>
                <button class="tab-btn" data-tab="staff">Staff Login</button>
            </div>
            <div class="xfw-form-wrapper-container">
                <div class="xfw-form-wrapper">
                    <div class="xfw-form-panel xfw-student-form xfw-active-form">
                        <form id="studentLoginForm">
                            <label for="loginInput">Mobile Number</label>
                            <input type="text" id="loginInput" placeholder="Enter Mobile Number" maxlength="10" />

                            <div class="otp-container">
                                <label>OTP</label>
                                <div class="otp-inputs">
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                    <input type="text" maxlength="1" />
                                </div>
                                <small>Didn't receive the OTP?
                                    <a href="#">Resend OTP</a></small>
                            </div>

                            <!-- <button class="btn-login-submit" type="submit"> Login </button> -->
                            <a href="../Student Dashboard/index.html" class="btn-login-submit" type="submit">
                                Login
                            </a>
                            <p class="register-link">
                                Don’t have an account? <a href="#">Register Here</a>
                            </p>
                        </form>
                    </div>

                    <div class="xfw-form-panel xfw-staff-form">
                        <form id="staffLoginForm">
                            <label for="staffEmail">Email</label>
                            <input type="email" id="staffEmail" placeholder="Enter Email" />

                            <label for="staffPassword">Password</label>
                            <input type="password" id="staffPassword" placeholder="Enter Password" />

                            <!-- <button class="btn-login-submit" type="submit"> Login </button> -->
                            <a href="../Admin Dashboard/index.html" class="btn-login-submit" type="submit">
                                Login
                            </a>
                            <p class="register-link">
                                Forgot password? <a href="index.html">Reset Here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- footer section  -->


<section class="footer-section">
    <p>Copyright © 2025 RNTU University. All rights reserved.</p>
    <img src="{{ asset('frontend/img/footer-img.png') }}" class="footer-img" alt="">
</section>

<!-- Bootstrap v5.3.7 -->
<script src="{{ asset('frontend/js/Boostrap/bootstrap.bundle.js') }}"></script>
{{-- <script src="{{ asset('frontend/js/jquery3-6-0.min.js') }}"></script> --}}
<script src="{{ asset('frontend/js/jquery3-7-1min.js') }}"></script>
<script src="{{ asset('frontend/js/style.js') }}"></script>
<script src="{{ asset('frontend/js/sweetalert.js') }}"></script>




<!-- <script>
    // JavaScript for tab functionality (can be merged with register.js)
    document.addEventListener("DOMContentLoaded", () => {
     

        // Login Popup functionality (existing from your original HTML)
        const loginBtn = document.getElementById("btn-login");
        const loginPopup = document.getElementById("loginPopup");
        const closePopupBtn = document.getElementById("closePopup");
        const tabBtns = document.querySelectorAll(".tab-btn");
        const formPanels = document.querySelectorAll(".xfw-form-panel");

        loginBtn.addEventListener("click", () => {
            loginPopup.style.display = "flex";
        });

        closePopupBtn.addEventListener("click", () => {
            loginPopup.style.display = "none";
        });

        tabBtns.forEach((btn) => {
            btn.addEventListener("click", () => {
                tabBtns.forEach((b) => b.classList.remove("active"));
                btn.classList.add("active");

                const targetTab = btn.dataset.tab;

                formPanels.forEach((panel) => {
                    panel.classList.remove("xfw-active-form");
                });

                if (targetTab === "student") {
                    document
                        .querySelector(".xfw-student-form")
                        .classList.add("xfw-active-form");
                } else if (targetTab === "staff") {
                    document
                        .querySelector(".xfw-staff-form")
                        .classList.add("xfw-active-form");
                }
                // Logic to slide the form wrapper container
                const formWrapper = document.querySelector(".xfw-form-wrapper");
                if (targetTab === "staff") {
                    formWrapper.style.transform = "translateX(-50%)";
                } else {
                    formWrapper.style.transform = "translateX(0)";
                }
            });
        });
    });
</script> -->


<!-- login popup -->
<!-- <script>
    // --- Global DOM variables ---
    const loginPopup = document.getElementById("loginPopup");
    const closeBtn = document.getElementById("closePopup");
    const tabBtns = document.querySelectorAll(".tab-btn");
    const formPanels = document.querySelectorAll(".xfw-form-panel");
    const formWrapper = document.querySelector(".xfw-form-wrapper");

    // --- User authentication status from Blade ---
    const isAuthenticated = @json(Auth::check());

    // --- Helper Function: Error and Success Display ---
    function displayFormMessages(form, messages) {
        const allMessageDivs = form.querySelectorAll('.text-danger, .text-success, .alert-danger, .alert-success');
        allMessageDivs.forEach(div => {
            div.textContent = '';
            div.style.display = 'none';
        });
        const inputElements = form.querySelectorAll('input, select, textarea');
        inputElements.forEach(input => input.classList.remove('is-invalid'));

        if (messages.errors) {
            for (const field in messages.errors) {
                const errorDiv = form.querySelector(`#${field}-error`);
                if (errorDiv) {
                    errorDiv.textContent = messages.errors[field][0];
                    errorDiv.style.display = 'block';
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) input.classList.add('is-invalid');
                }
            }
        }

        const generalErrorDiv = form.querySelector('#general-error') || form.querySelector('#otp-general-error');
        if (messages.message && generalErrorDiv) {
            generalErrorDiv.textContent = messages.message;
            if (messages.success) {
                generalErrorDiv.classList.remove('alert-danger');
                generalErrorDiv.classList.add('alert-success');
            } else {
                generalErrorDiv.classList.remove('alert-success');
                generalErrorDiv.classList.add('alert-danger');
            }
            generalErrorDiv.style.display = 'block';
        }
    }

    // --- OTP UI and Timer Logic ---
    let timerInterval;

    function handleOtpSent(form, data) {
        const otpContainer = form.querySelector('#otpContainer');
        const submitBtn = form.querySelector('#otpSubmitBtn');
        const resendBtn = form.querySelector('#resendOtpBtn');
        const resendTimer = form.querySelector('#resendTimer');
        const phoneInput = form.querySelector('#otpMobileNumber');

        otpContainer.style.display = 'block';
        submitBtn.textContent = 'Login';
        // form.action = '{{ url('api/otp/verify ') }}';

        phoneInput.readOnly = true;
        phoneInput.style.backgroundColor = '#f3f4f6';
        phoneInput.style.cursor = 'not-allowed';

        let timer = 60;
        resendBtn.style.display = 'none';
        resendTimer.style.display = 'inline';
        resendTimer.textContent = `Resend in ${timer}s`;

        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            timer--;
            resendTimer.textContent = `Resend in ${timer}s`;
            if (timer <= 0) {
                clearInterval(timerInterval);
                resendBtn.style.display = 'inline';
                resendTimer.style.display = 'none';
            }
        }, 1000);

        const oldOtp = @json(old('otp'));
        if (oldOtp) {
            const otpInputs = form.querySelectorAll('#otpContainer .otp-input-fields input');
            oldOtp.forEach((digit, index) => {
                if (otpInputs[index]) {
                    otpInputs[index].value = digit;
                }
            });
        }
    }

    // --- The dynamic form handler ---
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            if (form.dataset.ajax === 'true') {
                e.preventDefault();
                const actionUrl = this.getAttribute('action');
                const formData = new FormData(this);

                if (this.id === 'otpLoginForm') {
                    const otp = Array.from(this.querySelectorAll('.otp-input-fields input')).map(input => input.value).join('');
                    formData.set('otp', otp);
                }

                displayFormMessages(this, {});

                try {
                    const response = await fetch(actionUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();

                    if (response.ok) {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                        if (this.id === 'otpLoginForm' && data.success) {
                            handleOtpSent(this, data);
                            displayFormMessages(this, {
                                message: data.message,
                                success: true
                            });
                        }
                    } else {
                        displayFormMessages(this, data);
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    displayFormMessages(this, {
                        message: 'An unexpected error occurred. Please try again.',
                        errors: {
                            general: ['An unexpected error occurred.']
                        }
                    });
                }
            }
        });
    });

    // --- Resend OTP Logic ---
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    if (resendOtpBtn) {
        resendOtpBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const mobileNumber = form.querySelector('input[name="phone"]').value;
            const csrfToken = form.querySelector('input[name="_token"]').value;

            try {
                const response = await fetch('{{ url('
                    api / otp / send ') }}', {
                        method: 'POST',
                        body: JSON.stringify({
                            phone: mobileNumber
                        }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    });
                const data = await response.json();
                if (response.ok) {
                    if (timerInterval) clearInterval(timerInterval);
                    handleOtpSent(form, data);
                    displayFormMessages(form, {
                        message: data.message,
                        success: true
                    });
                } else {
                    displayFormMessages(form, data);
                }
            } catch (error) {
                displayFormMessages(form, {
                    message: 'An error occurred while sending OTP.',
                    errors: {
                        general: ['An error occurred.']
                    }
                });
            }
        });
    }

    // --- Final Part: Handle Traditional Submissions and Cursor Focus ---
    function setupPopupAndTabs() {
        // Do nothing if the user is already authenticated
        if (isAuthenticated) {
            return;
        }

        function showAndSwitchToTab(tabName) {
            loginPopup.style.display = "flex";
            tabBtns.forEach(btn => btn.classList.remove("active"));
            const targetBtn = document.querySelector(`.tab-btn[data-tab="${tabName}"]`);
            if (targetBtn) targetBtn.classList.add("active");
            formPanels.forEach(panel => panel.classList.remove("xfw-active-form"));
            const targetForm = document.querySelector(`.xfw-${tabName}-form`);
            if (targetForm) targetForm.classList.add("xfw-active-form");
            formWrapper.style.transform = `translateX(${tabName === "otp-based" ? -50 : 0}%)`;
        }



        if (hasErrors || hasSessionMessage || hasOldInput) {
            loginPopup.style.display = "flex";
            if (activeForm) {
                showAndSwitchToTab(activeForm);



                if (activeForm === 'otp-based' && !hasPhoneError && !hasEmailError && (otpSent || @json(old('otp') !== null))) {
                    const otpForm = document.getElementById('otpLoginForm');
                    if (otpForm) {
                        handleOtpSent(otpForm, {});
                    }
                    const firstOtpInput = otpForm.querySelector('.otp-input-fields input');
                    if (firstOtpInput) firstOtpInput.focus();
                }
            }
        }

        if (hasErrors) {
            const form = document.querySelector('.xfw-active-form form');
            if (form) {
                displayFormMessages(form, {});
            }
        } else if (hasSessionMessage) {
            const form = document.querySelector('.xfw-active-form form');
            if (form) {
                displayFormMessages(form, {
                    message: @json(session('success') ?? session('error')),
                    success: @json(session('success') ? true : false)
                });
            }
        }

        document.querySelectorAll(".btn-login").forEach(btn => {
            btn.addEventListener("click", () => {
                if (!isAuthenticated) {
                    loginPopup.style.display = "flex";
                }
            });
        });
        closeBtn.addEventListener("click", () => loginPopup.style.display = "none");
        tabBtns.forEach(btn => {
            btn.addEventListener("click", () => showAndSwitchToTab(btn.dataset.tab));
        });

        const otpInputsContainer = document.querySelector("#otpLoginForm .otp-input-fields");
        if (otpInputsContainer) {
            otpInputsContainer.addEventListener("input", (e) => {
                const target = e.target;
                if (target.matches('input') && target.value && target.nextElementSibling) {
                    target.nextElementSibling.focus();
                }
            });
            otpInputsContainer.addEventListener("keydown", (e) => {
                const target = e.target;
                if (target.matches('input') && e.key === "Backspace" && !target.value && target.previousElementSibling) {
                    target.previousElementSibling.focus();
                }
            });
        }
    }

    document.addEventListener("DOMContentLoaded", setupPopupAndTabs);
</script> -->



<!-- REGISTRATION sTATUS -->
<!-- <script>
    // Login Popup functionality (existing from your original HTML)
    const loginBtn = document.getElementById("btn-regitser");
    const loginPopup = document.getElementById("registerPopup");
    const closePopupBtn = document.getElementById("rclosePopup");
    const tabBtns = document.querySelectorAll(".tab-btn");
    const formPanels = document.querySelectorAll(".xfw-form-panel");

    loginBtn.addEventListener("click", () => {
        loginPopup.style.display = "flex";
    });

    closePopupBtn.addEventListener("click", () => {
        loginPopup.style.display = "none";
    });

    tabBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            tabBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");

            const targetTab = btn.dataset.tab;

            formPanels.forEach((panel) => {
                panel.classList.remove("xfw-active-form");
            });

            if (targetTab === "student") {
                document
                    .querySelector(".xfw-student-form")
                    .classList.add("xfw-active-form");
            } else if (targetTab === "staff") {
                document
                    .querySelector(".xfw-staff-form")
                    .classList.add("xfw-active-form");
            }
            // Logic to slide the form wrapper container
            const formWrapper = document.querySelector(".xfw-form-wrapper");
            if (targetTab === "staff") {
                formWrapper.style.transform = "translateX(-50%)";
            } else {
                formWrapper.style.transform = "translateX(0)";
            }
        });
    });
</script> -->




<script>
    // Login Popup functionality (existing from your original HTML)
    const loginBtn = document.getElementById("btn-regitser");
    const loginPopup = document.getElementById("registerPopup");
    const closePopupBtn = document.getElementById("rclosePopup");
    const tabBtns = document.querySelectorAll(".tab-btn");
    const formPanels = document.querySelectorAll(".xfw-form-panel");

    loginBtn.addEventListener("click", () => {
        loginPopup.style.display = "flex";
    });

    closePopupBtn.addEventListener("click", () => {
        loginPopup.style.display = "none";
    });

    tabBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            tabBtns.forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");

            const targetTab = btn.dataset.tab;

            formPanels.forEach((panel) => {
                panel.classList.remove("xfw-active-form");
            });

            if (targetTab === "student") {
                document
                    .querySelector(".xfw-student-form")
                    .classList.add("xfw-active-form");
            } else if (targetTab === "staff") {
                document
                    .querySelector(".xfw-staff-form")
                    .classList.add("xfw-active-form");
            }
            // Logic to slide the form wrapper container
            const formWrapper = document.querySelector(".xfw-form-wrapper");
            if (targetTab === "staff") {
                formWrapper.style.transform = "translateX(-50%)";
            } else {
                formWrapper.style.transform = "translateX(0)";
            }
        });
    });
</script>


</body>

</html>