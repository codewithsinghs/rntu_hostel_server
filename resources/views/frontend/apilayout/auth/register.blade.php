@extends('frontend.apilayout.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg rounded-4 p-4" style="width: 460px;">
            <h4 class="text-center mb-3 fw-bold">Create Account</h4>
            <p class="text-muted text-center mb-4">Sign up to get started</p>

            <form id="registerForm" class="ajax-form" data-action="/auth/register" data-method="POST" data-success="/login"
                novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control rounded-3" data-label="Name" minlength="3"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control rounded-3" data-label="Email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="phone" class="form-control  rounded-3" data-label="Phone">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" data-label="Password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3"
                        data-label="Confirm Password" required>
                </div>
                <button class="btn btn-primary w-100 rounded-3">Register</button>
            </form>

            <div class="text-center mt-3">
                <p class="small mb-0">Already have an account?
                    <a href="{{ route('login') }}" class="fw-bold">Login</a>
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const defaultValidationRules = {
            required: (value, fieldLabel) =>
                value.trim() ? null : `${fieldLabel} is required`,

            email: (value, fieldLabel) =>
                /^\S+@\S+\.\S+$/.test(value) ? null : `Enter a valid ${fieldLabel}`,

            minLength: (value, fieldLabel, min) =>
                value.length >= min ? null : `${fieldLabel} must be at least ${min} characters`,

            maxLength: (value, fieldLabel, max) =>
                value.length <= max ? null : `${fieldLabel} must not exceed ${max} characters`,

            number: (value, fieldLabel) =>
                !isNaN(value) ? null : `${fieldLabel} must be a number`,

            phone: (value, fieldLabel) =>
                /^[0-9]{10}$/.test(value) ? null : `Enter a valid 10-digit ${fieldLabel}`,
        };

        function showFieldError($input, message) {
            $input.addClass("is-invalid");
            $input.next(".invalid-feedback").remove();
            $input.after(`<div class="invalid-feedback">${message}</div>`);
        }

        function clearFieldError($input) {
            $input.removeClass("is-invalid");
            $input.next(".invalid-feedback").remove();
        }


        function validateForm($form) {
            let isValid = true;

            $form.find("input, select, textarea").each(function() {
                let $input = $(this);
                let value = $input.val() || "";
                let fieldLabel = $input.data("label") || $input.attr("name");

                // clear previous errors
                clearFieldError($input);

                // Required check
                if ($input.prop("required")) {
                    let error = defaultValidationRules.required(value, fieldLabel);
                    if (error) {
                        showFieldError($input, error);
                        isValid = false;
                        return; // skip further checks for this field
                    }
                }

                // Email check
                if ($input.attr("type") === "email") {
                    let error = defaultValidationRules.email(value, fieldLabel);
                    if (error) {
                        showFieldError($input, error);
                        isValid = false;
                        return;
                    }
                }

                // Min length
                if ($input.attr("minlength")) {
                    let min = parseInt($input.attr("minlength"));
                    let error = defaultValidationRules.minLength(value, fieldLabel, min);
                    if (error) {
                        showFieldError($input, error);
                        isValid = false;
                        return;
                    }
                }

                // Max length
                if ($input.attr("maxlength")) {
                    let max = parseInt($input.attr("maxlength"));
                    let error = defaultValidationRules.maxLength(value, fieldLabel, max);
                    if (error) {
                        showFieldError($input, error);
                        isValid = false;
                        return;
                    }
                }

                // Custom rule (like phone)
                if ($input.data("rule")) {
                    let rule = $input.data("rule");
                    if (defaultValidationRules[rule]) {
                        let error = defaultValidationRules[rule](value, fieldLabel);
                        if (error) {
                            showFieldError($input, error);
                            isValid = false;
                        }
                    }
                }
            });

            return isValid;
        }


        function handleApiErrors($form, error) {
            if (error.response?.status === 422) {
                let errors = error.response.data.errors || {};
                Object.keys(errors).forEach(field => {
                    let $input = $form.find(`[name="${field}"]`);
                    if ($input.length) {
                        showFieldError($input, errors[field][0]);
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: error.response?.data?.message || "Something went wrong!",
                });
            }
        }


        $(function() {
            $(document).on("submit", ".ajax-form", function(e) {
                e.preventDefault();

                let $form = $(this);

                // Clear old errors
                $form.find("input, select, textarea").each(function() {
                    clearFieldError($(this));
                });

                // Frontend validation
                if (!validateForm($form)) return;

                // Collect form data dynamically
                let formData = {};
                $form.serializeArray().forEach(item => {
                    formData[item.name] = item.value;
                });

                let action = $form.data("action");
                let method = ($form.data("method") || "POST").toLowerCase();

                axios({
                        url: action,
                        method: method,
                        data: formData
                    })
                    .then(res => {
                        let successRedirect = $form.data("success");

                        if (successRedirect) {
                            // If it looks like a URL, redirect; else show as message
                            if (successRedirect.startsWith("/")) {
                                showMessage("success", "Success! Redirecting...");
                                window.location.href = successRedirect;
                            } else {
                                showMessage("success", successRedirect);
                            }
                        } else {
                            showMessage("success", res.data.message || "Action successful");
                        }
                    })
                    .catch(err => {
                        handleApiErrors($form, err);
                    });
            });
        });

        function showMessage(type, text) {
            Swal.fire({
                icon: type,
                title: type === "success" ? "Success" : "Error",
                text: text,
            });
        }
    </script>
@endpush
