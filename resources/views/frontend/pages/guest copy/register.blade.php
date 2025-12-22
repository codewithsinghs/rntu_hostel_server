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
@endpush

@section('content')
    <!-- Optional: Add interactivity or analytics tracking -->
    <div class="signup-content">
        <!-- Left -->
        <div class="signup-img">
            <div class="signup-img-content">
                <h2>RNTU Hostel t</h2>
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
            <form id="registrationForm" novalidate>
                <!-- token -->
                <!-- Profile Information -->
                <section class="form-box">

                    <!-- Heading -->
                    <div class="form-header">
                        <h3>Profile Information</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <div class="form-grid">

                        <!-- Scholar Number -->
                        <div class="form-field">
                            <label for="scholar_number">Scholar Number <span class="text-danger">*</span> </label>
                            <input type="text" name="scholar_number" id="scholar_number" pattern="[a-zA-Z0-9]+"
                                title="Only letters and digits allowed" required aria-describedby="scholarNoError"
                                placeholder="***********" />
                            <div id="scholarNoError" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="scholar_no" class="form-label">Scholar Number *</label>
                            <input type="text" name="scholar_no" id="scholar_no" class="form-control"
                                pattern="[a-zA-Z0-9]+" title="Scholar number must contain only letters and digits."
                                required>
                            <div id="scholarNoError" class="invalid-feedback"></div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-field">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" aria-describedby="nameError" required
                                placeholder="Rajat Pradhan" />
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="form-field">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" aria-describedby="emailError" required
                                placeholder="name@gmail.com" />
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- Mobile Number -->
                        <div class="form-field">
                            <label for="mobile">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" id="mobile" pattern="[0-9]{10}"
                                aria-describedby="mobileError" required placeholder="99999-99999" />
                            <div id="mobileError" class="invalid-feedback"></div>
                        </div>

                        <!-- Faculty -->
                        <div class="form-field">
                            <label for="faculty">Select Faculty <span class="text-danger">*</span></label>
                            <select name="faculty_id" id="faculty" required aria-describedby="facultyError">
                                <option selected value="">Select Faculty</option>
                                <option value="1">Faculty of Science</option>
                                <option value="2">Faculty of Arts</option>
                                <option value="3">Faculty of Engineering</option>
                            </select>
                            <div id="facultyError" class="invalid-feedback"></div>
                        </div>

                        <!-- Department -->
                        <div class="form-field">
                            <label for="department">Select department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department" required aria-describedby="departmentError">
                                <option value="">Select department</option>
                            </select>
                            <div id="departmentError" class="invalid-feedback"></div>
                        </div>

                        <!-- Course -->
                        <div class="form-field">
                            <label for="course">Select course <span class="text-danger">*</span></label>
                            <select name="course_id" id="course" required aria-describedby="courseError">
                                <option value="">Select Course</option>
                            </select>
                            <div id="courseError" class="invalid-feedback"></div>
                        </div>

                        <!-- Gender -->
                        <div class="form-field">
                            <label for="gender">Select gender <span class="text-danger">*</span></label>
                            <select name="gender_id" id="gender" required aria-describedby="genderError">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <div id="genderError" class="invalid-feedback"></div>
                        </div>

                    </div>

                </section>
                <!-- Profile Information End -->

                <!-- Fee Waiver Start -->
                <section class="form-box">

                    <!-- Heading -->
                    <div class="form-header">
                        <h3>Fee Waiver Request (Optional)</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Introductory Message -->
                    <div class="alert alert-info mb-2" role="alert">
                        If you already have documentation or eligibility for a fee waiver, you may submit
                        your
                        request here. This section is optional and intended to support students with
                        verified
                        Channel.
                    </div>

                    <!-- Apply Fee Waiver Checkbox -->
                    <div class="form-field">
                        <div class="check-flex">
                            <input type="checkbox" name="fee_waiver" id="fee_waiver" value="1" required>
                            <label class="m-0" for="fee_waiver">
                                I would like to request a fee waiver based on <a href="/terms-and-conditions"
                                    target="_blank"> existing eligibility or documentation.</a>
                                <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div id="feeWaiverError" class="invalid-feedback"></div>
                    </div>

                    <!-- Upload Supporting Document -->
                    <div class="form-field mt-5">
                        <label for="waiver_document">Supporting Document <span class="text-danger">* (Upload
                                relevant documentation (e.g., scholarship letter, income certificate)) </span>
                        </label>
                        <input type="file" name="attachment" id="waiver_document" required
                            accept=".pdf,.jpg,.jpeg,.png" />
                        <div id="waiverDocumentError" class="invalid-feedback"></div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-field mt-5">
                        <label for="remarks">Additional Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control"
                            placeholder="Feel free to share any context or notes that may support your request."
                            aria-describedby="remarksError"></textarea>
                        <div id="remarksError" class="invalid-feedback"></div>
                    </div>


                </section>
                <!-- Fee Waiver End -->

                <!-- Family Details Start -->
                <section class="form-box">

                    <!-- Heading -->
                    <div class="form-header ">
                        <h3>Family Details</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <div class="form-grid">

                        <!-- Fathers Name -->
                        <div class="form-field">
                            <label for="fathers_name">Father's Name <span class="text-danger">*</span> </label>
                            <input type="text" name="fathers_name" id="fathers_name" required
                                aria-describedby="fathersNameError" placeholder="Mr. Hariom Pradhan" />
                            <div id="fatherNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Full Name -->
                        <div class="form-field">
                            <label for="mothers_name">Mother's Name <span class="text-danger">*</span></label>
                            <input type="text" name="mothers_name" id="mothers_name"
                                aria-describedby="mothersNameError" required placeholder="Kirti Pradhan" />
                            <div id="motherNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Parent's Contact Number -->
                        <div class="form-field">
                            <label for="parent_contact">Parent's Contact Number <span class="text-danger">*</span></label>
                            <input type="text" name="parent_contact" id="parent_contact"
                                aria-describedby="parent_contactError" required placeholder="99999-99999" />
                            <div id="parent_contactError" class="invalid-feedback"></div>
                        </div>

                        <!-- Local Guardian Name -->
                        <div class="form-field">
                            <label for="local_guardian_name">Local Guardian Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="local_guardian_name" id="local_guardian_name"
                                aria-describedby="localGuardianNameError" required placeholder="Rajat Pradhan" />
                            <div id="localGuardianNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Local Guardian's Contact Number -->
                        <div class="form-field">
                            <label for="guardian_contact">Local Guardian's Contact Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="guardian_contact" id="guardian_contact"
                                aria-describedby="guardian_contactError" required placeholder="99999-99999" />
                            <div id="guardian_contactError" class="invalid-feedback"></div>
                        </div>

                        <!-- Emergency Contact Number -->
                        <div class="form-field">
                            <label for="emergency_contact">Emergency Contact Number <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="emergency_contact" id="emergency_contact"
                                aria-describedby="emergency_contactError" required placeholder="99999-99999" />
                            <div id="emergency_contactError" class="invalid-feedback"></div>
                        </div>

                    </div>

                </section>
                <!-- Family Details End -->

                <!-- Preferences Start -->
                <section class="form-box">

                    <!-- Heading -->
                    <div class="form-header ">
                        <h3>Preferences</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <div class="form-grid">

                        <!-- Food Preference -->
                        <div class="form-field">
                            <label for="faculty">Select Food Preference <span class="text-danger">*</span></label>
                            <select name="Food_id" id="Food" required aria-describedby="foodPreferenceError">
                                <option selected value="">Select Food Preference</option>
                                <option value="1">Veg</option>
                                <option value="2">Non-Veg</option>
                                <option value="3">Both</option>
                            </select>
                            <div id="foodPreferenceError" class="invalid-feedback"></div>
                        </div>

                        <!-- Bed Preference -->
                        <div class="form-field">
                            <label for="faculty">Select Bed Preference <span class="text-danger">*</span></label>
                            <select name="Bed_id" id="Bed" required aria-describedby="bedPreferenceError">
                                <option selected value="">Select Bed Preference</option>
                                <option value="1">Single</option>
                                <option value="2">Double</option>
                                <option value="3">Triple</option>
                            </select>
                            <div id="bedPreferenceError" class="invalid-feedback"></div>
                        </div>

                        <!-- Stay Duration -->
                        <div class="form-field">
                            <label for="faculty">Select Stay Duration <span class="text-danger">*</span></label>
                            <select name="months" id="months" required aria-describedby="bedPreferenceError">
                                <option selected value="">Select Bed Preference</option>
                                <option value="1">Temporary (1 Month)</option>
                                <option value="3">Regular (3 Months)</option>
                            </select>
                            <div id="bedPreferenceError" class="invalid-feedback"></div>
                        </div>

                    </div>

                </section>
                <!-- Preferences Start -->

                <!-- Accessories Start -->
                <section class="form-box">

                    <!-- Heading -->
                    <div class="form-header ">
                        <h3>Accessories</h3>
                        <!-- <button class="edit-btn">Edit</button> -->
                    </div>

                    <!-- Form-grid -->
                    <!-- Free Accessories -->
                    <div class="form-field">
                        <label for="fathers_name">Free Accessories</label>
                        <p name="" id=""> </p>
                    </div>

                    <!-- Additional Accessories (Optional) -->
                    <div class="form-field">
                        <label for="fathers_name">Additional Accessories (Optional)</label>
                        <p name="" id=""> </p>
                    </div>

                    <!-- Agreement -->
                    <div class="form-field">
                        <div class="check-flex">
                            <input type="checkbox" id="agree" required>
                            <label class="m-0" for="agree">
                                I agree to the <a href="/terms-and-conditions" target="_blank">terms and
                                    conditions</a>
                                <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div id="agreeError" class="invalid-feedback"></div>
                    </div>
                </section>
                <!-- Accessories End -->

                <!-- Submit Btn -->
                <section class="submit-btn-registration">
                    {{-- <button type="submit" class="edit-btn" id="submitBtn">
                        Register
                    </button>
                    <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div> --}}
                    <button type="submit" class="btn btn-primary w-100" id="submitBtn" >
                        Register
                    </button>
                    <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div>
                </section>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        // Optional: Add interactivity or analytics tracking
        console.log('Services page loaded');
    </script>

    <script>
$(function() {
    let defaultAccessoryHeadIds = [];
    let allAccessories = [];
    const fees = {
        hostel: 6000, // per month
        caution: 10000 // one-time
    };

    /** Helpers */
    function displayError(field, message) {
        let input = $('[name="' + field + '"]');
        input.addClass('is-invalid');
        let errorEl = $('#' + field + 'Error');
        if (errorEl.length) {
            errorEl.text(message).show();
        } else {
            // fallback if error placeholder missing
            input.after('<div class="invalid-feedback d-block">' + message + '</div>');
        }
    }

    function clearError(field) {
        $('[name="' + field + '"]').removeClass('is-invalid');
        let errorEl = $('#' + field + 'Error');
        if (errorEl.length) {
            errorEl.text('').hide();
        }
    }

    function showAlert(type, msg) {
        Swal.fire({
            icon: type,
            title: type === 'success' ? 'Success' : 'Error',
            text: msg,
            timer: 2500,
            showConfirmButton: false
        });
    }

    /** Fee calculation */
    function updateFeeBreakup() {
        let months = parseInt($('#months').val()) || 0;
        if (!months) {
            $('#feeBreakupTable').hide();
            return;
        }

        let hostelFee = fees.hostel * months;
        let cautionFee = fees.caution;

        // Accessories
        let accessoryFee = 0;
        let accessoryRows = '';
        $('input[name="accessories[]"]:checked').each(function() {
            let price = parseFloat($(this).data('price')) || 0;
            accessoryFee += price * months;
            accessoryRows +=
                `<tr><td>${$(this).data('name')} × ${months}m</td><td>₹ ${(price * months).toFixed(2)}</td></tr>`;
        });

        // Breakup
        let html = `
            <tr><td>Hostel Fee × ${months}m</td><td>₹ ${hostelFee.toFixed(2)}</td></tr>
            <tr><td>Caution Fee (one-time)</td><td>₹ ${cautionFee.toFixed(2)}</td></tr>
            ${accessoryRows}
        `;

        let total = hostelFee + cautionFee + accessoryFee;
        $('#feeBreakupBody').html(html);
        $('#totalFee').text(`₹ ${total.toFixed(2)}`);
        $('#feeBreakupTable').show();
    }

    /** Input events */
    $('#registrationForm input, #registrationForm select, #registrationForm textarea')
        .on('input blur change', function() {
            clearError(this.name);
        });

    $('#months, #additional-accessories').on('change', updateFeeBreakup);

    /** Faculty → Department → Course + Accessories */
    $.getJSON('/api/faculties/active', function(data) {
        let facultySelect = $('#faculty_id').empty().append(
            '<option value="">Select Faculty</option>');
        $.each(data.data, function(_, f) {
            facultySelect.append(`<option value="${f.id}">${f.name}</option>`);
        });
    });

    $('#faculty_id').change(function() {
        let fid = $(this).val();
        $('#department_id').empty().append('<option value="">Select Department</option>');
        $('#course_id').empty().append('<option value="">Select Course</option>');
        $('#default-accessories').html('Loading...');
        $('#additional-accessories').html('Loading...');

        if (!fid) return;

        $.getJSON(`/api/faculties/${fid}/departments`, function(data) {
            $.each(data.data, function(_, d) {
                $('#department_id').append(
                    `<option value="${d.id}">${d.name}</option>`);
            });
        });

        $.getJSON(`/api/accessories/active/${fid}`, function(data) {
            allAccessories = data.data;
            let defHtml = '', addHtml = '';
            defaultAccessoryHeadIds = [];
            $.each(allAccessories, function(_, acc) {
                if (parseInt(acc.is_default) === 1) {
                    defaultAccessoryHeadIds.push(acc.id);
                    defHtml += `<div>${acc.accessory_head.name}</div>`;
                } else {
                    addHtml += `<div class="form-check">
                        <input type="checkbox" class="form-check-input" id="acc-${acc.id}" 
                               name="accessories[]" value="${acc.id}" 
                               data-price="${acc.price}" data-name="${acc.accessory_head.name}">
                        <label for="acc-${acc.id}" class="form-check-label">
                            ${acc.accessory_head.name} (₹ ${parseFloat(acc.price).toFixed(2)}/m)
                        </label>
                    </div>`;
                }
            });
            $('#default-accessories').html(defHtml || '<p>No free accessories</p>');
            $('#additional-accessories').html(addHtml || '<p>No additional accessories</p>');
            updateFeeBreakup();
        });
    });

    $('#department_id').change(function() {
        let did = $(this).val();
        $('#course_id').empty().append('<option value="">Select Course</option>');
        if (!did) return;
        $.getJSON(`/api/departments/${did}/courses`, function(data) {
            $.each(data.data, function(_, c) {
                $('#course_id').append(`<option value="${c.id}">${c.name}</option>`);
            });
        });
    });

    /** Form submit */
    $('#registrationForm').submit(function(e) {
        e.preventDefault();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();

        let valid = true;
        $('#registrationForm [required]').each(function() {
            if (!this.checkValidity()) {
                displayError(this.name, this.validationMessage);
                valid = false;
            }
        });
        if (!$('input[name="room_preference"]:checked').length) {
            displayError('room_preference', 'Please select bed preference.');
            valid = false;
        }
        if (!$('#agree').is(':checked')) {
            displayError('agree', 'You must agree to the terms.');
            valid = false;
        }

        if (!valid) {
            showAlert('error', 'Please correct the highlighted fields.');
            return;
        }

        let formData = new FormData(this);
        $.each(defaultAccessoryHeadIds, (_, id) => formData.append('accessories[]', id));
        formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' : '0');
        formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');

        $('#loading').removeClass('d-none');

        $.ajax({
            url: '/api/guests',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                $('#loading').addClass('d-none');
                if (res.success) {
                    showAlert('success', 'Registration successful!');
                    localStorage.setItem('token', res.token);
                    localStorage.setItem('auth-id', res.data.id);
                    setTimeout(() => window.location.href =
                        "{{ url('/guest/dashboard') }}", 1500);
                } else {
                    showAlert('error', res.message || 'Registration failed.');
                }
            },
            error: function(xhr) {
                $('#loading').addClass('d-none');
                let res = xhr.responseJSON;
                if (res && res.errors) {
                    $.each(res.errors, (field, msgs) => displayError(field, msgs[0]));
                    showAlert('error', 'Please fix the highlighted errors.');
                } else {
                    showAlert('error', 'Unexpected error. Try again.');
                }
            }
        });
    });
});
</script> --}}

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

            // function toggleConditionalFields() {
            //     if ($('#fee_waiver').is(':checked')) {
            //         $('#remarksFieldGroup').show();
            //         $('#remarks').prop('required', true);
            //         $('#remarksRequiredAsterisk').text('*');

            //         $('#waiverDocumentFieldGroup').show();
            //     } else {
            //         $('#remarksFieldGroup').hide();
            //         $('#remarks').prop('required', false).val('');
            //         clearError('remarks');
            //         $('#remarksRequiredAsterisk').text('');

            //         $('#waiverDocumentFieldGroup').hide();
            //         $('#waiver_document').val('');
            //         clearError('waiver_document');
            //     }
            // }

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

               // $('#submitBtn').prop('disabled', !valid);
            }

            // $('#fee_waiver').change(toggleConditionalFields);

            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                });

            $('#agree').change(checkFormValidity);

            checkFormValidity();
            // toggleConditionalFields();

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

                // formData.set('fee_waiver', $('#fee_waiver').is(':checked') ? '1' : '0');
                formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' : '0');
                formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');

                // formData.set('remarks', $('#remarks').val().trim());

                // if ($('#fee_waiver').is(':checked') && $('#waiver_document')[0].files.length > 0) {
                //     formData.append('attachment', $('#waiver_document')[0].files[0]);
                // }

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
