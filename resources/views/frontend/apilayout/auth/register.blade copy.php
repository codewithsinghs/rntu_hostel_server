@extends('frontend.apilayout.app')

@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow-lg rounded-4 p-4" style="width: 460px;">
            <h4 class="text-center mb-3 fw-bold">Create Account</h4>
            <p class="text-muted text-center mb-4">Sign up to get started</p>

            <form id="registerForm" novalidate>
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="text" name="phone" class="form-control rounded-3">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                    <div class="invalid-feedback"></div>
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
        const validationRules = {
            name: {
                required: "Name is required",
                minLength: {
                    value: 3,
                    message: "Name must be at least 3 characters"
                }
            },
            email: {
                required: "Email is required",
                email: "Enter a valid email address"
            },
            password: {
                required: "Password is required",
                minLength: {
                    value: 8,
                    message: "Password must be at least 8 characters"
                }
            }
        };


        // function validateForm($form) {
        //     let isValid = true;

        //     $form.find("input[required], select[required]").each(function() {
        //         let $input = $(this);

        //         let field = $input.attr("name");
        //         let value = $input.val().trim();

        //         if (!$input.val().trim()) {
        //             isValid = false;
        //             showFieldError($input, "This field is required");
        //         } else {
        //             clearFieldError($input);
        //         }
        //     });

        //     return isValid;
        // }

        // function showFieldError($input, message) {
        //     $input.addClass("is-invalid");
        //     $input.siblings(".invalid-feedback").text(message);
        // }

        // function clearFieldError($input) {
        //     $input.removeClass("is-invalid");
        //     $input.siblings(".invalid-feedback").text("");
        // }

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

    $form.find("input[required], select[required]").each(function () {
        let $input = $(this);
        let field = $input.attr("name");
        let value = $input.val().trim();

        // If field has rules
        if (validationRules[field]) {
            let rules = validationRules[field];

            if (!value) {
                showFieldError($input, rules.required);
                isValid = false;
            } else if (rules.email && !/^\S+@\S+\.\S+$/.test(value)) {
                showFieldError($input, rules.email);
                isValid = false;
            } else if (rules.minLength && value.length < rules.minLength.value) {
                showFieldError($input, rules.minLength.message);
                isValid = false;
            } else {
                clearFieldError($input);
            }
        }
    });

    return isValid;
}



        // Backend error handling 
        // function handleApiErrors($form, error) {
        //     if (error.response?.status === 422) {
        //         let errors = error.response.data.errors || {};
        //         Object.keys(errors).forEach(field => {
        //             let $input = $form.find(`[name="${field}"]`);
        //             if ($input.length) {
        //                 showFieldError($input, errors[field][0]);
        //             }
        //         });
        //     } else {
        //         // Generic / DB error
        //         let message = error.response?.data?.message || "Something went wrong";
        //         Swal.fire({
        //             icon: "error",
        //             title: "Error",
        //             text: message,
        //         });
        //     }
        // }

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
        let message = error.response?.data?.message || "Something went wrong!";
        Swal.fire({
            icon: "error",
            title: "Error",
            text: message,
        });
    }
}



        $(function() {
            $("#registerForm").on("submit", function(e) {
                e.preventDefault();

                let $form = $(this);

                // Clear old errors
                $form.find("input, select").removeClass("is-invalid");
                $form.find(".invalid-feedback").text("");

                if (!validateForm($form)) return; // stop if frontend invalid

                let formData = {
                    name: $form.find("[name=name]").val(),
                    email: $form.find("[name=email]").val(),
                    phone: $form.find("[name=phone]").val(),
                    password: $form.find("[name=password]").val(),
                    password_confirmation: $form.find("[name=password_confirmation]").val(),
                };



                // let formData = {
                //     name: $(this).find("input[name=name]").val(),
                //     email: $(this).find("input[name=email]").val(),
                //     phone: $(this).find("input[name=phone]").val(),
                //     password: $(this).find("input[name=password]").val(),
                //     password_confirmation: $(this).find("input[name=password_confirmation]").val(),
                // };

                axios.post("/auth/register", formData)
                    .then(res => {
                        showMessage("success", "Registration successful! Please login.");
                        window.location.href = "/login";
                    })
                    .catch(err => {
                        let errors = err.response?.data?.errors || {};
                        let message = Object.values(errors).flat().join("\n") || "Registration failed";
                        showMessage("error", message);
                    });
            });
        });
    </script>
@endpush
