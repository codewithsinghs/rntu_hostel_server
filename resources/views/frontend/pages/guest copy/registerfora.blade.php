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
                            <div id="scholar_noError" class="invalid-feedback"></div>
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
                            <label for="faculty_id" class="form-label">Faculty *</label>
                            <select name="faculty_id" id="faculty_id" class="form-select" required>
                                <option value="">Select Faculty</option>
                            </select>
                            <div id="faculty_idError" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department *</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                            </select>
                            <div id="department_idError" class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="course_id" class="form-label">Course *</label>
                            <select name="course_id" id="course_id" class="form-select" required>
                                <option value="">Select Course</option>
                            </select>
                            <div id="course_idError" class="invalid-feedback"></div>
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

                    <!-- Preferences -->
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
                        <div id="room_preferenceError" class="invalid-feedback"></div>
                    </div>

                    <!-- Stay Duration -->
                    <div class="mb-3">
                        <label for="months" class="form-label">Stay Duration *</label>
                        <select name="months" id="months" class="form-select" required>
                            <option value="">-- Select Duration --</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="9">9 Months</option>
                            <option value="12">12 Months</option>
                        </select>
                        <div id="monthsError" class="invalid-feedback"></div>
                    </div>

                    <!-- Accessories -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Free Accessories</h6>
                            <div id="default-accessories" class="p-3 border rounded bg-light">Loading...</div>

                            <h6 class="mt-3">Additional Accessories</h6>
                            <div id="additional-accessories" class="p-3 border rounded">Loading...</div>
                        </div>

                        <!-- Fee Breakup -->
                        <div class="col-md-6">
                            <h6>Fee Breakup</h6>
                            <table class="table table-bordered" id="feeBreakupTable" style="display:none;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="feeBreakupBody"></tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th>Total</th>
                                        <th id="totalFee">₹ 0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Agreement -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="agree" required>
                        <label class="form-check-label" for="agree">
                            I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a> *
                        </label>
                        <div id="agreeError" class="invalid-feedback"></div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="submitBtn">Register</button>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    let defHtml = '',
                        addHtml = '';
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
                    $('#additional-accessories').html(addHtml ||
                    '<p>No additional accessories</p>');
                    updateFeeBreakup();
                });
            });

            $('#department_id').change(function() {
                let did = $(this).val();
                $('#course_id').empty().append('<option value="">Select Course</option>');
                if (!did) return;
                $.getJSON(`/api/departments/${did}/courses`, function(data) {
                    $.each(data.data, function(_, c) {
                        $('#course_id').append(
                        `<option value="${c.id}">${c.name}</option>`);
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
    </script>



    {{-- <script>
        $(function() {
            let defaultAccessoryHeadIds = [];
            let allAccessories = [];
            const fees = {
                hostel: 6000, // per month
                caution: 10000 // one-time
            };

            /** Helpers */
            function displayError(field, message) {
                $('#' + field + 'Error').text(message);
                $('[name="' + field + '"]').addClass('is-invalid');
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
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
                        `<tr><td>${$(this).data('name')} × ${months}m</td><td>₹ ${ (price * months).toFixed(2) }</td></tr>`;
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

            /** Enable/disable submit */
            function checkFormValidity() {
                let valid = true;
                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) valid = false;
                });
                if (!$('input[name="room_preference"]:checked').length) valid = false;
                if (!$('#agree').is(':checked')) valid = false;
                // $('#submitBtn').prop('disabled', !valid);
            }

            /** Input events */
            $('#registrationForm input, #registrationForm select, #registrationForm textarea')
                .on('input blur change', function() {
                    clearError(this.name);
                    checkFormValidity();
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
                    let defHtml = '',
                        addHtml = '';
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
                    $('#additional-accessories').html(addHtml ||
                        '<p>No additional accessories</p>');
                });
            });

            $('#department_id').change(function() {
                let did = $(this).val();
                $('#course_id').empty().append('<option value="">Select Course</option>');
                if (!did) return;
                $.getJSON(`/api/departments/${did}/courses`, function(data) {
                    $.each(data.data, function(_, c) {
                        $('#course_id').append(
                            `<option value="${c.id}">${c.name}</option>`);
                    });
                });
            });

            /** Form submit */
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

                // $('#submitBtn').prop('disabled', true);
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
@endpush
