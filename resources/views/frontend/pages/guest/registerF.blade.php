@extends('frontend.layouts.app')

@section('title', 'Our Services')
@section('meta_description', 'Explore the range of professional services we offer.')

@push('styles')
    <style>
        /* Page-specific styles for services */
        .service-card {
            transition: transform 0.3s ease;
        }

        .service-card:hover {
            transform: scale(1.03);
        }
    </style>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* Light gray background */
        }

        /* Custom styles for invalid feedback to ensure it's always visible when needed */
        .form-control.is-invalid+.invalid-feedback,
        .form-select.is-invalid+.invalid-feedback,
        .form-check-input.is-invalid+.form-check-label+.invalid-feedback,
        /* Added for general invalid states for form elements */
        .form-control.is-invalid,
        .form-select.is-invalid,
        .form-check-input.is-invalid,
        .form-control.is-invalid+.invalid-feedback {
            /* Added for textarea specific error */
            border-color: #dc3545;
            /* Red border for invalid fields */
        }

        /* Hide default browser validation messages for more control */
        input:invalid:not(:focus):not(:placeholder-shown),
        select:invalid:not(:focus):not(:placeholder-shown),
        textarea:invalid:not(:focus):not(:placeholder-shown) {
            border-color: #dc3545;
            /* Red border for invalid fields */
        }

        */

        /* Style for required asterisk */
        .required {
            color: #ef4444;
            /* Red color for required indicator */
        }

        button.accordion-button.collapsed {
            color: white;
        }
    </style>
@endpush

@section('content')
    <!-- Optional: Add interactivity or analytics tracking -->
    <div class="signup-content">
        <!-- Left -->
        <div class="signup-img">
            <div class="signup-img-content">
                <h2>RNTU Hostel </h2>
                <p>Student Registration</p>
            </div>
        </div>

        <!-- Right -->
        <div class="signup-form">
            <div class="mobile-heading">
                RNTU Hostel registration
            </div>

            <!-- ALerts Start  -->
            <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                <strong>Error!</strong> <span id="errorMessageText">Something went wrong. Please try again.</span>
            </div>

            <div id="registrationSuccessContainer" class="alert alert-success d-none text-center">
                <h4 class="alert-heading">Guest registered successfully!</h4>
                <p>Thank you!</p>
            </div>

            <div id="approvalMessageContainer" class="alert alert-info d-none text-center">
                Your registration is awaiting admin approval. Keep checking for updates.
            </div>
            <!-- ALerts End -->

            <!-- Form Start-->
            {{-- <form id="registrationForm" novalidate>
                <!-- token -->
                <input type="hidden" name="_token" value="7OQGhMSuh1zvv8wSr4e6lBEfFyCk2vpvdVeLFign" autocomplete="off"> --}}
            <form id="registrationForm" class="card shadow-lg border-0" novalidate>
                @csrf
                <!-- Personal Details -->
                <div class="card-header bg-primary text-white fw-bold fs-5">
                    Personal Details
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Scholar Number -->
                        <div class="col-md-6">
                            <label for="scholar_number" class="form-label">Scholar Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="scholar_number" id="scholar_number" class="form-control"
                                pattern="[a-zA-Z0-9]+" placeholder="Enter Scholar Number" required
                                aria-describedby="scholar_numberError">
                            <div id="scholar_numberError" class="invalid-feedback"></div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Enter Full Name" required aria-describedby="nameError">
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control"
                                placeholder="example@email.com" required aria-describedby="emailError">
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label for="mobile" class="form-label">Mobile Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" class="form-control" pattern="[0-9]{10}"
                                maxlength="10" placeholder="10-digit number" required aria-describedby="mobileError">
                            <div id="mobileError" class="invalid-feedback"></div>
                        </div>

                        <!-- Faculty -->
                        <div class="col-md-6">
                            <label for="faculty" class="form-label">Select Faculty <span
                                    class="text-danger">*</span></label>
                            <select name="faculty_id" id="faculty" class="form-select" required
                                aria-describedby="facultyError">
                                <option value="">Select Faculty</option>
                            </select>
                            <div id="facultyError" class="invalid-feedback"></div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6">
                            <label for="department" class="form-label">Select Department <span
                                    class="text-danger">*</span></label>
                            <select name="department_id" id="department" class="form-select" required
                                aria-describedby="departmentError">
                                <option value="">Select Department</option>
                            </select>
                            <div id="departmentError" class="invalid-feedback"></div>
                        </div>

                        <!-- Course -->
                        <div class="col-md-6">
                            <label for="course" class="form-label">Select Course <span
                                    class="text-danger">*</span></label>
                            <select name="course_id" id="course" class="form-select" required
                                aria-describedby="courseError">
                                <option value="">Select Course</option>
                            </select>
                            <div id="courseError" class="invalid-feedback"></div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                            <select name="gender" id="gender" class="form-select" required
                                aria-describedby="genderError">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="genderError" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <!-- Preferences -->
                <div class="card-header bg-primary text-white fw-bold fs-5">
                    Preferences
                </div>
                <div class="card-body">
                    <!-- Food Preference -->
                    <div class="mb-3">
                        <label class="form-label">Food Preference <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="food_preference" id="food_veg"
                                    value="Veg" required>
                                <label class="form-check-label" for="food_veg">Veg</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="food_preference" id="food_nonveg"
                                    value="Non-Veg">
                                <label class="form-check-label" for="food_nonveg">Non-Veg</label>
                            </div>
                        </div>
                        <div id="food_preferenceError" class="invalid-feedback"></div>
                    </div>

                    <!-- Bed Preference -->
                    <div class="mb-3">
                        <label class="form-label">Bed Preference <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="room_preference" id="room_single"
                                    value="Single" required>
                                <label class="form-check-label" for="room_single">Single</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="room_preference" id="room_double"
                                    value="Double">
                                <label class="form-check-label" for="room_double">Double</label>
                            </div>
                        </div>
                        <div id="room_preferenceError" class="invalid-feedback"></div>
                    </div>

                    <!-- Stay Duration -->
                    <div class="mb-3">
                        <label for="months" class="form-label">Stay Duration <span class="text-danger">*</span></label>
                        <select name="months" id="months" class="form-select" required
                            aria-describedby="monthsError">
                            <option value="">Select Duration</option>
                            <option value="1">Temporary (1 Month)</option>
                            <option value="3">Regular (3 Months)</option>
                        </select>
                        <div id="monthsError" class="invalid-feedback"></div>
                    </div>
                </div>

                <!-- Fee Breakup -->
                <div class="card-header bg-success text-white fw-bold fs-5">
                    Fee Breakup
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3" id="fee-breakup">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Hostel Fee</span> <span id="hostelFee">₹0</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Caution Money (One-time)</span> <span id="cautionFee">₹2000</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Accessories</span> <span id="accessoryFee">₹0</span>
                        </li>
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between fw-bold">
                            <span>Total Payable</span> <span id="totalFee">₹0</span>
                        </li>
                    </ul>
                </div>

                <!-- Agreement & Submit -->
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">
                            I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a>
                            <span class="text-danger">*</span>
                        </label>
                        <div id="agreeError" class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary w-100" id="submitBtn" >
                        Register
                    </button>
                    <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Optional: Add interactivity or analytics tracking
        console.log('Services page loaded');
    </script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    {{-- <script type="text/javascript">
        $(document).ready(function() {
            let defaultAccessoryHeadIds = [];
            let allAccessories = [];

            function displayError(field, message) {
                $('#' + field + 'Error').text(message);
                $('[name="' + field + '"]').addClass('is-invalid');
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
            }

            function toggleConditionalFields() {
                if ($('#fee_waiver').is(':checked')) {
                    $('#remarksFieldGroup').show();
                    $('#remarks').prop('required', true);
                    $('#remarksRequiredAsterisk').text('*');

                    $('#waiverDocumentFieldGroup').show();
                } else {
                    $('#remarksFieldGroup').hide();
                    $('#remarks').prop('required', false).val('');
                    clearError('remarks');
                    $('#remarksRequiredAsterisk').text('');

                    $('#waiverDocumentFieldGroup').hide();
                    $('#waiver_document').val('');
                    clearError('waiver_document');
                }
            }

            function checkFormValidity() {
                let valid = true;

                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        valid = false;
                    }
                });

                // if (!$('input[name="food_preference"]:checked').length) valid = false;
                if (!$('input[name="room_preference"]:checked').length) valid = false;
                if (!$('#agree').is(':checked')) valid = false;

                $('#submitBtn').prop('disabled', !valid);
            }

            $('#fee_waiver').change(toggleConditionalFields);

            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                });

            $('#agree').change(checkFormValidity);

            checkFormValidity();
            toggleConditionalFields();

            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                $('#errorMessage').addClass('hidden');
                $('.is-invalid').removeClass('is-invalid');

                let valid = true;

                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        displayError(this.name, this.validationMessage);
                        valid = false;
                    }
                });

                // if (!$('input[name="food_preference"]:checked').length) {
                //     displayError('food_preference', 'Select food preference.');
                //     valid = false;
                // }

                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Select bed preference.');
                    valid = false;
                }

                if (!$('#agree').is(':checked')) {
                    displayError('agree', 'You must agree to the terms.');
                    valid = false;
                }

                if (!valid) return;

                let formData = new FormData(this);

                $.each(defaultAccessoryHeadIds, function(i, id) {
                    formData.append('accessories[]', id);
                });

                formData.set('fee_waiver', $('#fee_waiver').is(':checked') ? '1' : '0');
                formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' : '0');
                formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');

                formData.set('remarks', $('#remarks').val().trim());

                if ($('#fee_waiver').is(':checked') && $('#waiver_document')[0].files.length > 0) {
                    formData.append('attachment', $('#waiver_document')[0].files[0]);
                }

                $('#submitBtn').prop('disabled', true);
                $('#loading').removeClass('hidden');

                $.ajax({
                    url: '/api/guests',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading').addClass('hidden');
                        if (response.success) {
                            $('#registrationForm').hide();
                            $('#registrationSuccessContainer').removeClass('hidden');

                            // console.log("Registration response:", response);

                            // Store token & auth-id in localStorage
                            localStorage.setItem('token', response.token);
                            localStorage.setItem('auth-id', response.data.id);

                            // setTimeout(() => {
                            window.location.href = "{{ url('/guest/dashboard') }}";
                            // }, 2000);
                        } else {
                            $('#errorMessageText').text(response.message ||
                                'Registration failed.');
                            $('#errorMessage').removeClass('hidden');
                        }
                    },
                    error: function(xhr) {
                        $('#loading').addClass('hidden');
                        $('#errorMessage').removeClass('hidden');
                        let response = xhr.responseJSON;
                        if (response && response.errors) {
                            $.each(response.errors, function(field, msgs) {
                                displayError(field, msgs[0]);
                            });
                        } else {
                            $('#errorMessageText').text('An error occurred. Try again.');
                        }
                    }
                });
            });

            // Load faculties
            $.getJSON('/api/faculties/active', function(data) {
                let facultySelect = $('#faculty_id');
                facultySelect.empty().append('<option value="">Select Faculty</option>');
                $.each(data.data, function(i, faculty) {
                    facultySelect.append(`<option value="${faculty.id}">${faculty.name}</option>`);
                });
            }).fail(function() {
                displayError('faculty_id', 'Error loading faculties.');
            });
            // Load departments based on selected faculty
            $('#faculty_id').change(function() {
                let facultyId = $(this).val();
                let departmentSelect = $('#department_id');
                departmentSelect.empty().append('<option value="">Select Department</option>');

                if (facultyId) {
                    $.getJSON(`/api/faculties/${facultyId}/departments`, function(data) {
                        $.each(data.data, function(i, department) {
                            departmentSelect.append(
                                `<option value="${department.id}">${department.name}</option>`
                            );
                        });
                    }).fail(function() {
                        displayError('department_id', 'Error loading departments.');
                    });
                }


                // Load accessories    
                $.getJSON('/api/accessories/active/' + facultyId, function(data) {
                    allAccessories = data.data;
                    let defaultHTML = '',
                        additionalHTML = '';

                    if (allAccessories.length === 0) {
                        defaultHTML = '<p>No free accessories available.</p>';
                        additionalHTML = '<p>No additional accessories available.</p>';
                    } else {
                        defaultAccessoryHeadIds = [];
                        // console.log(allAccessories);
                        $.each(allAccessories, function(i, acc) {
                            if (parseFloat(acc.is_default) === 1) {
                                defaultAccessoryHeadIds.push(acc.id);
                                defaultHTML +=
                                    `<div  class="text-gray-700 py-1">${acc.accessory_head.name}</div>`;
                            } else {
                                additionalHTML += `<div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${acc.id}" name="accessories[]" id="accessory-${acc.id}">
                            <label class="form-check-label" for="accessory-${acc.id}">
                                ${acc.accessory_head.name} (${parseFloat(acc.price).toFixed(2)} INR)
                            </label>
                        </div>`;
                            }
                        });
                    }

                    $('#default-accessories').html(defaultHTML);
                    $('#additional-accessories').html(additionalHTML);
                }).fail(function() {
                    $('#errorMessageText').text('Error loading accessories.');
                    $('#errorMessage').removeClass('hidden');
                });

            });
            // Load courses based on selected department
            $('#department_id').change(function() {
                let departmentId = $(this).val();
                let courseSelect = $('#course_id');
                courseSelect.empty().append('<option value="">Select Course</option>');

                if (departmentId) {
                    $.getJSON(`/api/departments/${departmentId}/courses`, function(data) {
                        $.each(data.data, function(i, course) {
                            courseSelect.append(
                                `<option value="${course.id}">${course.name}</option>`);
                        });
                    }).fail(function() {
                        displayError('course_id', 'Error loading courses.');
                    });
                }
            });

        });
    </script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

    {{-- <script type="text/javascript">
        $(document).ready(function() {
            const defaultAccessoryHeadIds = [];
            let allAccessories = [];

            // Utility: Show error message
            function displayError(field, message) {
                const $error = $('#' + field + 'Error');
                const $input = $('[name="' + field + '"]');
                $error.text(message).show();
                $input.addClass('is-invalid');
            }

            // Utility: Clear error message
            function clearError(field) {
                const $error = $('#' + field + 'Error');
                const $input = $('[name="' + field + '"]');
                $error.text('').hide();
                $input.removeClass('is-invalid');
            }

            // Toggle conditional fields based on fee waiver
            function toggleConditionalFields() {
                const isWaiverChecked = $('#fee_waiver').is(':checked');
                $('#remarksFieldGroup, #waiverDocumentFieldGroup').toggle(isWaiverChecked);
                $('#remarks').prop('required', isWaiverChecked);
                $('#remarksRequiredAsterisk').text(isWaiverChecked ? '*' : '');
                if (!isWaiverChecked) {
                    $('#remarks').val('');
                    $('#waiver_document').val('');
                    clearError('remarks');
                    clearError('waiver_document');
                }
            }

            // Validate form inputs
            function checkFormValidity() {
                let valid = true;

                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) valid = false;
                });

                if (!$('input[name="food_preference"]:checked').length) valid = false;
                if (!$('input[name="room_preference"]:checked').length) valid = false;
                if (!$('#agree').is(':checked')) valid = false;

                $('#submitBtn').prop('disabled', !valid);
            }



            function loadAccessories() {
                $.getJSON('/api/accessories/active')
                    .done(function(data) {
                        const allAccessories = data.data || [];
                        let defaultHTML = '',
                            additionalHTML = '';


                        if (allAccessories.length === 0) {
                            defaultHTML = '<p>No free accessories available.</p>';
                            additionalHTML = '<p>No additional accessories available.</p>';
                        } else {
                            $.each(allAccessories, function(i, acc) {
                                if (parseFloat(acc.price) === 0) {
                                    defaultAccessoryHeadIds.push(acc.accessory_head.id);

                                    defaultHTML += `
                                        <div class="border rounded p-2 mb-2 bg-white shadow-sm">
                                            <strong>${acc.accessory_head.name}</strong>
                                        </div>`;
                                } else {

                                    additionalHTML += `
                                    <div class="form-check border rounded p-3 mb-1 bg-white shadow-sm flex-grow-1" style=margin-right:20px;>
                                        <input class="form-check-input" type="checkbox" value="${acc.accessory_head.id}" name="accessories[]" id="accessory-${acc.accessory_head.id}">
                                        <label class="form-check-label" for="accessory-${acc.accessory_head.id}">
                                            <strong>${acc.accessory_head.name}</strong> (${parseFloat(acc.price).toFixed(2)} INR)
                                        </label>
                                    </div>`;
                                }
                            });
                        }

                        $('#default-accessories').html(defaultHTML);
                        $('#additional-accessories').html(additionalHTML);
                    })
                    .fail(function() {
                        $('#errorMessageText').text('Unable to load accessories. Please try again later.');
                        $('#errorMessage').removeClass('hidden');
                    });
            }




            // Submit form with validation and AJAX
            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                $('#errorMessage').addClass('hidden');
                $('.is-invalid').removeClass('is-invalid');

                let valid = true;

                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        displayError(this.name, this.validationMessage);
                        valid = false;
                    }
                });

                if (!$('input[name="food_preference"]:checked').length) {
                    displayError('food_preference', 'Please select your food preference.');
                    valid = false;
                }

                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Please select your room preference.');
                    valid = false;
                }

                if (!$('#agree').is(':checked')) {
                    displayError('agree', 'You must agree to the terms and conditions.');
                    valid = false;
                }

                if (!valid) return;

                const formData = new FormData(this);

                defaultAccessoryHeadIds.forEach(id => {
                    formData.append('accessory_head_ids[]', id);
                });

                $('input[name="accessories[]"]:checked').each(function() {
                    formData.append('accessory_head_ids[]', $(this).val());
                });

                formData.set('fee_waiver', $('#fee_waiver').is(':checked') ? '1' : '0');
                formData.set('remarks', $('#remarks').val().trim());

                const waiverFile = $('#waiver_document')[0].files[0];
                if ($('#fee_waiver').is(':checked') && waiverFile) {
                    formData.append('attachment', waiverFile);
                }

                $('#submitBtn').prop('disabled', true);
                $('#loading').removeClass('hidden');

                $.ajax({
                    url: '/api/guests',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },


                    success: function(response) {
                        $('#loading').addClass('hidden');
                        console.log('Success Response:', response); // Log to console

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful',
                                text: 'You will be redirected shortly.',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            $('#registrationForm').hide();
                            $('#registrationSuccessContainer').removeClass('hidden');

                            setTimeout(() => {
                                window.location.href = '/guest/registration-status';
                            }, 3000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Registration Failed',
                                text: response.message ||
                                    'Something went wrong. Please try again.'
                            });
                        }
                    },
                    error: function(xhr) {
                        $('#loading').addClass('hidden');
                        console.log('Error Response:', xhr); // Log to console

                        const response = xhr.responseJSON;
                        let errorMessage = 'An unexpected error occurred. Please try again.';

                        if (response && response.errors) {
                            errorMessage = Object.values(response.errors).map(msgs => msgs[0])
                                .join('\n');
                            Object.entries(response.errors).forEach(([field, msgs]) => {
                                displayError(field, msgs[0]);
                            });
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Error',
                            text: errorMessage
                        });
                    }

                });
            });

            // Initial setup
            $('#fee_waiver').change(toggleConditionalFields);
            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                });
            $('#agree').change(checkFormValidity);

            checkFormValidity();
            toggleConditionalFields();
            loadAccessories();
        });
    </script> --}}


    {{-- <script>
        $('#faculty').on('change', function() {
            const facultyId = $(this).val();
            $('#department').html('<option value="">Select Department </option>');
            $('#course').html('<option value="">Select Course</option>');

            if (facultyId) {
                $.get(`/departments/${facultyId}`, function(departments) {
                    if (departments.length > 0) {
                        departments.forEach(dept => {
                            $('#department').append(
                                `<option value="${dept.id}">${dept.name}</option>`);
                        });
                    } else {
                        // No departments — load courses directly under faculty
                        $.get(`/courses/faculty/${facultyId}`, function(courses) {
                            if (courses.length > 0) {
                                courses.forEach(course => {
                                    $('#course').append(
                                        `<option value="${course.id}">${course.name}</option>`
                                    );
                                });
                            } else {
                                $('#course').append(
                                    `<option value="">No courses available</option>`);
                            }
                        });
                    }
                });
            }
        });

        $('#department').on('change', function() {
            const departmentId = $(this).val();
            $('#course').html('<option value="">Select Course</option>');

            if (departmentId) {
                $.get(`/courses/department/${departmentId}`, function(courses) {
                    if (courses.length > 0) {
                        courses.forEach(course => {
                            $('#course').append(
                                `<option value="${course.id}">${course.name}</option>`);
                        });
                    } else {
                        $('#course').append(`<option value="">No courses available</option>`);
                    }
                });
            }
        });
    </script> --}}

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            let defaultAccessoryHeadIds = [];
            let allAccessories = [];

            /** ---------------------------
             *  COMMON HELPERS
             * -------------------------- */
            function displayError(field, message) {
                let input = $('[name="' + field + '"]');
                input.addClass('is-invalid');
                $('#' + field + 'Error').text(message);
            }

            function clearError(field) {
                $('[name="' + field + '"]').removeClass('is-invalid');
                $('#' + field + 'Error').text('');
            }

            function showAlert(type, message) {
                Swal.fire({
                    icon: type,
                    title: type === "success" ? "Success" : "Error",
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            function toggleConditionalFields() {
                if ($('#fee_waiver').is(':checked')) {
                    $('#remarksFieldGroup').show();
                    $('#remarks').prop('required', true);
                    $('#remarksRequiredAsterisk').text('*');
                    $('#waiverDocumentFieldGroup').show();
                } else {
                    $('#remarksFieldGroup').hide();
                    $('#remarks').prop('required', false).val('');
                    clearError('remarks');
                    $('#remarksRequiredAsterisk').text('');
                    $('#waiverDocumentFieldGroup').hide();
                    $('#waiver_document').val('');
                    clearError('waiver_document');
                }
            }

            function checkFormValidity() {
                let valid = true;

                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) valid = false;
                });

                if (!$('input[name="room_preference"]:checked').length) valid = false;
                if (!$('#agree').is(':checked')) valid = false;

                // $('#submitBtn').prop('disabled', !valid);
            }

            /** ---------------------------
             *  EVENT HANDLERS
             * -------------------------- */
            $('#fee_waiver').change(toggleConditionalFields);

            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                }
            );

            $('#agree').change(checkFormValidity);

            checkFormValidity();
            toggleConditionalFields();

            /** ---------------------------
             *  FORM SUBMIT
             * -------------------------- */
            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                $('.is-invalid').removeClass('is-invalid');

                let valid = true;
                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        displayError(this.name, this.validationMessage);
                        valid = false;
                    }
                });

                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Select bed preference.');
                    valid = false;
                }

                if (!$('#agree').is(':checked')) {
                    displayError('agree', 'You must agree to the terms.');
                    valid = false;
                }

                if (!valid) return;

                let formData = new FormData(this);
                $.each(defaultAccessoryHeadIds, function(i, id) {
                    formData.append('accessories[]', id);
                });

                formData.set('fee_waiver', $('#fee_waiver').is(':checked') ? '1' : '0');
                formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' : '0');
                formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');
                formData.set('remarks', $('#remarks').val().trim());

                if ($('#fee_waiver').is(':checked') && $('#waiver_document')[0].files.length > 0) {
                    formData.append('attachment', $('#waiver_document')[0].files[0]);
                }

                // $('#submitBtn').prop('disabled', true);
                $('#loading').removeClass('hidden');

                $.ajax({
                    url: '/api/guests',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#loading').addClass('hidden');
                        if (response.success) {
                            localStorage.setItem('token', response.token);
                            localStorage.setItem('auth-id', response.data.id);
                            showAlert('success', 'Registration successful! Redirecting...');
                            setTimeout(() => {
                                window.location.href = "{{ url('/guest/dashboard') }}";
                            }, 1500);
                        } else {
                            showAlert('error', response.message || 'Registration failed.');
                        }
                    },
                    error: function(xhr) {
                        $('#loading').addClass('hidden');
                        // $('#submitBtn').prop('disabled', false);

                        let response = xhr.responseJSON;
                        if (response && response.errors) {
                            $.each(response.errors, function(field, msgs) {
                                displayError(field, msgs[0]);
                            });
                            showAlert('error', 'Please fix the highlighted errors.');
                        } else {
                            showAlert('error', 'An unexpected error occurred. Try again.');
                        }
                    }
                });
            });

            /** ---------------------------
             *  LOAD FACULTIES
             * -------------------------- */
            $.getJSON('/api/faculties/active', function(data) {
                let facultySelect = $('#faculty_id');
                facultySelect.empty().append('<option value="">Select Faculty</option>');
                $.each(data.data, function(i, faculty) {
                    facultySelect.append(`<option value="${faculty.id}">${faculty.name}</option>`);
                });
            }).fail(function() {
                showAlert('error', 'Error loading faculties.');
            });

            /** ---------------------------
             *  ON FACULTY CHANGE
             * -------------------------- */
            $('#faculty_id').change(function() {
                let facultyId = $(this).val();
                let departmentSelect = $('#department_id');
                departmentSelect.empty().append('<option value="">Select Department</option>');

                if (facultyId) {
                    $.getJSON(`/api/faculties/${facultyId}/departments`, function(data) {
                        $.each(data.data, function(i, department) {
                            departmentSelect.append(
                                `<option value="${department.id}">${department.name}</option>`
                            );
                        });
                    }).fail(function() {
                        showAlert('error', 'Error loading departments.');
                    });
                }

                // Load accessories
                $.getJSON('/api/accessories/active/' + facultyId, function(data) {
                    allAccessories = data.data;
                    let defaultHTML = '',
                        additionalHTML = '';

                    if (allAccessories.length === 0) {
                        defaultHTML = '<p>No free accessories available.</p>';
                        additionalHTML = '<p>No additional accessories available.</p>';
                    } else {
                        defaultAccessoryHeadIds = [];
                        $.each(allAccessories, function(i, acc) {
                            if (parseFloat(acc.is_default) === 1) {
                                defaultAccessoryHeadIds.push(acc.id);
                                defaultHTML +=
                                    `<div class="text-gray-700 py-1">${acc.accessory_head.name}</div>`;
                            } else {
                                additionalHTML += `<div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${acc.id}" name="accessories[]" id="accessory-${acc.id}">
                            <label class="form-check-label" for="accessory-${acc.id}">
                                ${acc.accessory_head.name} (${parseFloat(acc.price).toFixed(2)} INR)
                            </label>
                        </div>`;
                            }
                        });
                    }

                    $('#default-accessories').html(defaultHTML);
                    $('#additional-accessories').html(additionalHTML);
                }).fail(function() {
                    showAlert('error', 'Error loading accessories.');
                });
            });

            /** ---------------------------
             *  ON DEPARTMENT CHANGE
             * -------------------------- */
            $('#department_id').change(function() {
                let departmentId = $(this).val();
                let courseSelect = $('#course_id');
                courseSelect.empty().append('<option value="">Select Course</option>');

                if (departmentId) {
                    $.getJSON(`/api/departments/${departmentId}/courses`, function(data) {
                        $.each(data.data, function(i, course) {
                            courseSelect.append(
                                `<option value="${course.id}">${course.name}</option>`
                            );
                        });
                    }).fail(function() {
                        showAlert('error', 'Error loading courses.');
                    });
                }
            });


            const fees = {
                hostel: {
                    1: 5000,
                    3: 12000
                }, // duration → fee
                caution: 2000,
            };

            function updateFeeBreakup() {
                let duration = parseInt($('#months').val()) || 0;
                let hostelFee = fees.hostel[duration] || 0;

                // accessories sum
                let accessoryFee = 0;
                $('input[name="accessories[]"]:checked').each(function() {
                    accessoryFee += parseFloat($(this).data('price')) || 0;
                });

                let total = hostelFee + fees.caution + accessoryFee;

                $('#hostelFee').text(`₹${hostelFee}`);
                $('#cautionFee').text(`₹${fees.caution}`);
                $('#accessoryFee').text(`₹${accessoryFee}`);
                $('#totalFee').text(`₹${total}`);
            }

            // trigger on changes
            $('#months, input[name="accessories[]"]').on('change', updateFeeBreakup);

        });
    </script>
@endpush
