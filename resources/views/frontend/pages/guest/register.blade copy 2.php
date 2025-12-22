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
        <form id="registrationForm" class="card p-4 shadow-sm">
            @csrf
            <h5 class="card-header bg-primary text-white">Personal Details</h5>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="scholar_no" class="form-label">Scholar Number *</label>
                        <input type="text" name="scholar_no" id="scholar_no" class="form-control"
                            pattern="[a-zA-Z0-9]+" title="Scholar number must contain only letters and digits."
                            required>
                        <div id="scholarNoError" class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                        <div id="nameError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <div id="emailError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="faculty_id" class="block text-gray-700 text-sm font-medium mb-1">Select Faculty
                            <span class="required">*</span></label>
                        <select name="faculty_id" id="faculty_id"
                            class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value="">Select Faculty</option>
                        </select>
                        <div id="facultyError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="block text-gray-700 text-sm font-medium mb-1">Select
                            Department <span class="required">*</span></label>
                        <select name="department_id" id="department_id"
                            class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value="">Select Department</option>
                        </select>
                        <div id="departmentError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="course_id" class="block text-gray-700 text-sm font-medium mb-1">Select Course <span
                                class="required">*</span></label>
                        <select name="course_id" id="course_id"
                            class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value="">Select Course</option>
                        </select>
                        <div id="courseError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>


                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender *</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <div id="genderError" class="invalid-feedback"></div>
                    </div>
                </div>

                <!--Family Details -->
                <h5 class="bg-primary text-white p-2">Family Details</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="fathers_name" class="form-label">Father's Name *</label>
                        <input type="text" name="fathers_name" id="fathers_name" class="form-control" required>
                        <div id="fathersNameError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="mothers_name" class="form-label">Mother's Name *</label>
                        <input type="text" name="mothers_name" id="mothers_name" class="form-control" required>
                        <div id="mothersNameError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12">
                        <label for="local_guardian_name" class="form-label">Local Guardian Name (Optional)</label>
                        <input type="text" name="local_guardian_name" id="local_guardian_name"
                            class="form-control">
                        <div id="localGuardianNameError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="emergency_no" class="form-label">Emergency Contact Number *</label>
                        <input type="text" name="emergency_no" id="emergency_no" class="form-control"
                            pattern="[0-9]{10}" required>
                        <div id="emergencyNoError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="number" class="form-label">Your Contact Number</label>
                        <input type="text" name="number" id="number" class="form-control"
                            pattern="[0-9]{10}">
                        <div id="numberError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="parent_no" class="form-label">Parent's Contact Number</label>
                        <input type="text" name="parent_no" id="parent_no" class="form-control"
                            pattern="[0-9]{10}">
                        <div id="parentNoError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_no" class="form-label">Local Guardian's Contact Number</label>
                        <input type="text" name="guardian_no" id="guardian_no" class="form-control"
                            pattern="[0-9]{10}">
                        <div id="guardianNoError" class="invalid-feedback"></div>
                    </div>
                </div>


                <!--Preferences ------>
                <h5 class="bg-primary text-white p-2">Preferences</h5>

                <div class="mb-3">
                    <label class="form-label">Bed Preference *</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="room_preference" value="Single"
                                id="room_single" required>
                            <label class="form-check-label" for="room_single">Single</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="room_preference" value="Double"
                                id="room_double">
                            <label class="form-check-label" for="room_double">Double</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="room_preference" value="Triple"
                                id="room_triple">
                            <label class="form-check-label" for="room_triple">Triple</label>
                        </div>
                    </div>
                    <div id="bedPreferenceError" class="invalid-feedback"></div>
                </div>

                <!--Stay Duration ------>
                <div class="mb-3">
                    <label for="months" class="form-label">Stay Duration *</label>
                    <select name="months" id="months" class="form-select" required>
                        <option value="">-- Select Type --</option>
                        {{-- <option value="1">Temporary (1 Month)</option> --}}
                        <option value="3">Regular (3 Months)</option>
                        <option value="6">Regular (6 Months)</option>
                        <option value="9">Regular (9 Months)</option>
                        <option value="12">Regular (12 Months)</option>
                    </select>
                    <div id="stayDurationError" class="invalid-feedback"></div>
                </div>

                <!--Comp Acc------>
                <div class="mb-3">
                    <label class="form-label">Free Accessories</label>
                    <div class="border border-gray-300 p-4 rounded-md bg-gray-50" id="default-accessories">
                        <p class="text-muted">Loading free accessories...</p>
                    </div>
                </div>

                <!--Add on Acc------>
                <div class="mb-3">
                    <label class="form-label">Additional Accessories (Optional)</label>
                    <div class="border p-3" id="additional-accessories">
                        <p class="text-muted">Loading additional accessories...</p>
                    </div>
                </div>

                <!-- fee Waver  -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="fee_waiver" id="fee_waiver"
                                value="1">
                            <label class="form-check-label" for="fee_waiver">Apply Fee Waiver</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="bihar_credit_card"
                                id="bihar_credit_card" value="1">
                            <label class="form-check-label" for="bihar_credit_card">Bihar Credit Card</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="tnsd" id="tnsd"
                                value="1">
                            <label class="form-check-label" for="tnsd">TNSD Student</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="waiver_document" class="form-label">Fee Waiver Document</label>
                        <input type="file" name="attachment" id="waiver_document" class="form-control">
                        <div id="waiverDocumentError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                        <div id="remarksError" class="invalid-feedback"></div>
                    </div>
                </div>


                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="agree" required>
                    <label class="form-check-label" for="agree">
                        I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a> *
                    </label>
                    <div id="agreeError" class="invalid-feedback"></div>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitBtn" disabled>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
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
</script>
@endpush