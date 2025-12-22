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
                <h2>RNTU Hostel </h2>
                <p>Student Registrations</p>
            </div>
        </div>

        <!-- Right -->
        <div class="signup-form">
            <div class="mobile-heading">
                RNTU Hostel registrations
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
            <form id="registrationForm" novalidate>
                <h5 class="card-header bg-primary text-white">Personal Details</h5>
                <div class="card-body">
                    <div class="row mb-3">
                        <!-- Scholar No -->
                        <div class="col-md-6 mb-3">
                            <label for="scholar_no" class="form-label">Scholar Number *</label>
                            <input type="text" name="scholar_no" id="scholar_no" class="form-control" required>
                            <div class="invalid-feedback">Scholar number is required.</div>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <div class="invalid-feedback">Please enter your full name.</div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            <div class="invalid-feedback">Please enter a valid email address.</div>
                        </div>

                        <!-- Faculty -->
                        <div class="col-md-6 mb-3">
                            <label for="faculty_id" class="form-label">Select Faculty *</label>
                            <select name="faculty_id" id="faculty_id" class="form-select" required>
                                <option value="">Select Faculty</option>
                            </select>
                            <div class="invalid-feedback">Please select a faculty.</div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6 mb-3">
                            <label for="department_id" class="form-label">Select Department *</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                            </select>
                            <div class="invalid-feedback">Please select a department.</div>
                        </div>

                        <!-- Course -->
                        <div class="col-md-6 mb-3">
                            <label for="course_id" class="form-label">Select Course *</label>
                            <select name="course_id" id="course_id" class="form-select" required>
                                <option value="">Select Course</option>
                            </select>
                            <div class="invalid-feedback">Please select a course.</div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select name="gender" id="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <div class="invalid-feedback">Please select your gender.</div>
                        </div>
                    </div>

                    <!-- Family Details -->
                    <h5 class="bg-primary text-white p-2">Family Details</h5>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="fathers_name" class="form-label">Father's Name *</label>
                            <input type="text" name="fathers_name" id="fathers_name" class="form-control" required>
                            <div class="invalid-feedback">Father's name is required.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="mothers_name" class="form-label">Mother's Name *</label>
                            <input type="text" name="mothers_name" id="mothers_name" class="form-control" required>
                            <div class="invalid-feedback">Mother's name is required.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="emergency_no" class="form-label">Emergency Contact Number *</label>
                            <input type="text" name="emergency_no" id="emergency_no" class="form-control"
                                pattern="[0-9]{10}" required>
                            <div class="invalid-feedback">Enter a valid 10-digit emergency number.</div>
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
                        <div class="invalid-feedback">Please select your bed preference.</div>
                    </div>

                    <!-- Stay Duration -->
                    <div class="mb-3">
                        <label for="months" class="form-label">Stay Duration *</label>
                        <select name="months" id="months" class="form-select" required>
                            <option value="">Select Duration</option>
                            <option value="3">Regular (3 Months)</option>
                            <option value="6">Regular (6 Months)</option>
                            <option value="9">Regular (9 Months)</option>
                            <option value="12">Regular (12 Months)</option>
                        </select>
                        <div class="invalid-feedback">Please select stay duration.</div>
                    </div>

                    <!-- Com on Acc------><!--Add on Acc------><!--Add on Fee------>
                    <div class="row">
                        <div class="col-md-6 mb-3">

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
                        </div>
                  


                        {{-- <div id="feeBreakupContainer" class="mt-4 d-none col-md-6 shadow p-4 rounded bg-light">
                            <h4 class="mb-4">💰 Fee Summary for <span id="durationLabel"></span></h4>

                            <!-- Part A: Hostel Fee + Caution Money -->
                            <h5 class="text-primary">Part A: Hostel Charges</h5>
                            <table class="table table-bordered mb-4">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hostel Fee</td>
                                        <td id="hostelFee">₹ 0</td>
                                    </tr>
                                    <tr>
                                        <td>Caution Money</td>
                                        <td id="cautionFee">₹ 10000</td>
                                    </tr>
                                    <tr class="table-info fw-bold">
                                        <td>Subtotal (Part A)</td>
                                        <td id="partAFee">₹ 10000</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Part B: Accessories -->
                            <div id="accessorySection" class="d-none mt-4">
                                <h5 class="text-success">Part B: Additional Accessories</h5>
                                <table class="table table-bordered mb-4">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th>Accessory</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="accessoryBreakupBody">
                                        <!-- Accessory rows will be injected here -->
                                        <tr id="accessoryTotalRow" class="d-none">
                                            <td class="fw-bold">Accessories Total</td>
                                            <td id="accessoryFee">₹ 0</td>
                                        </tr>
                                        <tr class="table-info fw-bold d-">
                                            <td>Subtotal (Part B)</td>
                                            <td id="partBFee">₹ 0</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Final Total -->
                            <h5 class="text-dark">🧾 Final Total Payable</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="table-warning fw-bold">
                                        <td>Total Payable (A + B)</td>
                                        <td id="totalFee">₹ 10000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                        <!-- Fee Breakdown Section -->
                        <div id="fee-breakdown" class="col-md-6 card shadow-sm mt-4 d-none">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Fee Breakdown Preview</h6>
                            </div>
                            <div class="card-body">
                                <!-- Part A: Hostel & Other Fees -->
                                <h6 class="text-secondary mb-2">Part A: Fees</h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Fee Name</th>
                                                <th>Type</th>
                                                <th>Base Amount</th>
                                                <th>Calculated Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fee-breakdown-body"></tbody>
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <td colspan="3" class="text-end"><strong>Total (Part A)</strong></td>
                                                <td id="fee-total" class="fw-bold text-primary"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Part B: Accessories -->
                                <div id="accessorySection">

                                <h6 class="text-secondary mb-2">Part B: Accessories</h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Accessory</th>
                                                <th>Price (per month)</th>
                                                <th>Months</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="accessory-breakdown-body"></tbody>
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <td colspan="3" class="text-end"><strong>Total (Part B)</strong></td>
                                                <td id="accessory-total" class="fw-bold text-primary"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                                <!-- Grand Total -->
                                <div class="alert alert-success text-center fw-bold">
                                    Grand Total: <span id="grand-total" class="fs-5"></span>
                                </div>
                            </div>
                        </div>
                    </div>



                        <!-- Terms -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agree" required>
                            <label class="form-check-label" for="agree">
                                I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a> *
                            </label>
                            <div class="invalid-feedback">You must agree before submitting.</div>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            let defaultAccessoryHeadIds = [];
            let allAccessories = [];

            /* -------------------------------
             * Error Handling
             * ------------------------------- */
            function displayError(field, message) {
                const $field = $('[name="' + field + '"]');
                $('#' + field + 'Error').text(message);
                $field.addClass('is-invalid');

                // Scroll to first error if needed
                if ($('.is-invalid').first().length) {
                    // $('html, body').animate({
                    //     scrollTop: $('.is-invalid').first().offset().top - 100
                    // }, 500);
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
            }

            /* -------------------------------
             * Validation
             * ------------------------------- */
            function checkFormValidity(showAlert = false) {
                let valid = true;

                // Clear previous
                $('#registrationForm [required]').each(function() {
                    clearError(this.name);
                });

                // HTML5 required check
                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        displayError(this.name, this.validationMessage || 'This field is required.');
                        valid = false;
                    }
                });

                // Custom validation: room preference
                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Please select a room preference.');
                    valid = false;
                } else {
                    clearError('room_preference');
                }

                // Custom validation: agree
                if (!$('#agree').is(':checked')) {
                    displayError('agree', 'You must agree to the terms.');
                    valid = false;
                } else {
                    clearError('agree');
                }

                if (!valid && showAlert) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: 'Some fields need your attention before we proceed.',
                        confirmButtonText: 'Let me fix it'
                    });
                }

                return valid;
            }

            // $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
            //     'input blur change',
            //     function () {
            //         clearError(this.name);
            //         // checkFormValidity();
            //     }
            // );

            // Validate only the field being changed
            // $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
            //     'input blur change',
            //     function() {
            //         const fieldName = this.name;

            //         // If field is empty or invalid → show error
            //         if (!this.checkValidity()) {
            //             displayError(fieldName, this.validationMessage || 'This field is required.');
            //         } else {
            //             clearError(fieldName);
            //         }

            //         // Special cases
            //         if (fieldName === 'room_preference') {
            //             if (!$('input[name="room_preference"]:checked').length) {
            //                 displayError('room_preference', 'Please select a room preference.');
            //             } else {
            //                 clearError('room_preference');
            //             }
            //         }

            //         if (fieldName === 'agree') {
            //             if (!$('#agree').is(':checked')) {
            //                 displayError('agree', 'You must agree to the terms.');
            //             } else {
            //                 clearError('agree');
            //             }
            //         }
            //     }
            // );

            // Field-level validation
            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);

                    // If field is required and empty → show error
                    if (this.hasAttribute('required') && !this.checkValidity()) {
                        displayError(this.name, this.validationMessage || 'This field is required.');
                    }

                    // Special cases
                    if (this.name === 'room_preference') {
                        if (!$('input[name="room_preference"]:checked').length) {
                            displayError('room_preference', 'Please select a room preference.');
                        } else {
                            clearError('room_preference');
                        }
                    }
                    if (this.name === 'agree') {
                        if (!$('#agree').is(':checked')) {
                            displayError('agree', 'You must agree to the terms.');
                        } else {
                            clearError('agree');
                        }
                    }
                }
            );





            $('#agree').change(checkFormValidity);

            /* -------------------------------
             * Submit
             * ------------------------------- */
            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                $('#errorMessage').addClass('hidden');
                $('.is-invalid').removeClass('is-invalid');

                const isValid = checkFormValidity(true);
                if (!isValid) return;

                showConfirmationModal().then((result) => {
                    if (!result.isConfirmed) return;

                    const formData = new FormData(this);

                    // Add default accessories
                    $.each(defaultAccessoryHeadIds, function(i, id) {
                        formData.append('accessories[]', id);
                    });

                    // Checkboxes
                    formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' :
                        '0');
                    formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');

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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Welcome!',
                                    text: 'Your registration was successful. Redirecting...',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                $('#registrationForm').hide();
                                $('#registrationSuccessContainer').removeClass(
                                    'hidden');

                                localStorage.setItem('token', response.token);
                                localStorage.setItem('auth-id', response.data.id);

                                setTimeout(() => {
                                    window.location.href =
                                        "{{ url('/guest/dashboard') }}";
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Failed',
                                    text: response.message ||
                                        'Something went wrong. Please try again.'
                                });
                            }
                        },
                        error: function(xhr) {
                            $('#loading').addClass('hidden');
                            const response = xhr.responseJSON;

                            if (response && response.errors) {
                                $.each(response.errors, function(field, msgs) {
                                    displayError(field, msgs[0]);
                                });
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'We couldn’t process your request. Please try again later.'
                            });
                        }
                    });
                });
            });

            /* -------------------------------
             * Load Faculty, Department, Course, Accessories
             * ------------------------------- */
            function loadFaculties() {
                $.getJSON('/api/faculties/active', function(data) {
                    let facultySelect = $('#faculty_id');
                    facultySelect.empty().append('<option value="">Select Faculty</option>');
                    $.each(data.data, function(i, faculty) {
                        facultySelect.append(
                            `<option value="${faculty.id}">${faculty.name}</option>`);
                    });
                }).fail(function() {
                    displayError('faculty_id', 'Error loading faculties.');
                });
            }

            function loadDepartments(facultyId) {
                let departmentSelect = $('#department_id');
                departmentSelect.empty().append('<option value="">Select Department</option>');

                if (facultyId) {
                    $.getJSON(`/api/faculties/${facultyId}/departments`, function(data) {
                        $.each(data.data, function(i, department) {
                            departmentSelect.append(
                                `<option value="${department.id}">${department.name}</option>`);
                        });
                    }).fail(function() {
                        displayError('department_id', 'Error loading departments.');
                    });
                }
            }

            function loadCourses(departmentId) {
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
            }

            function loadAccessories(facultyId) {
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
                    $('#errorMessageText').text('Error loading accessories.');
                    $('#errorMessage').removeClass('hidden');
                });
            }

            /* -------------------------------
             * Event Bindings
             * ------------------------------- */
            $('#faculty_id').change(function() {
                let facultyId = $(this).val();
                loadDepartments(facultyId);
                loadAccessories(facultyId);
            });

            $('#department_id').change(function() {
                let departmentId = $(this).val();
                loadCourses(departmentId);
            });

            // When months change
            $('#months').on('change', triggerFeePreview);

            // When accessories change
            $('#additional-accessories').on('change', 'input[type="checkbox"]', triggerFeePreview);

            // Initial load (optional)
            triggerFeePreview();

            // Init
            loadFaculties();
        });
    </script>

    <script>
        // Trigger Fee Preview
        function triggerFeePreview() {
            const facultyId = $('#faculty_id').val();
            if (!facultyId) return; // wait until faculty is selected

            const months = parseInt($('#months').val()) || 1;
            const accessoryIds = Array.from(document.querySelectorAll('input[name="accessories[]"]:checked'))
                .map(el => parseInt(el.value));

            $.ajax({
                url: `/api/guests/invoice-preview`,
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify({
                    months,
                    accessory_ids: accessoryIds,
                    faculty_id: facultyId
                }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#fee-breakdown').removeClass('d-none');
                    renderFeeBreakdown(response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $('#fee-breakdown').html(
                        '<div class="alert alert-danger">❌ Failed to load fee info.</div>');
                }
            });
        }



        // Render Breakdown
        function renderFeeBreakdown(data) {
            const fees = data.fees || [];
            const accessories = data.accessories || [];
            const months = parseInt($('#months').val()) || 1;

            const feeBody = $('#fee-breakdown-body');
            const feeTotalCell = $('#fee-total');
            const accessoryBody = $('#accessory-breakdown-body');
            const accessoryTotalCell = $('#accessory-total');
            const grandTotalCell = $('#grand-total');

            feeBody.empty();
            accessoryBody.empty();

            let feeTotal = 0;
            let accessoryTotal = 0;

            // Render fees
            fees.forEach(fee => {
                const row = `
            <tr>
                <td>${fee.name}</td>
                <td>${fee.is_one_time ? 'One-Time' : 'Recurring'}</td>
                <td>₹${parseFloat(fee.amount).toFixed(2)}</td>
                <td>₹${parseFloat(fee.calculated_amount).toFixed(2)}</td>
            </tr>
                `;
                feeBody.append(row);
                feeTotal += parseFloat(fee.calculated_amount);
            });

            // Render accessories
            accessories.forEach(acc => {
                const row = `
            <tr>
                <td>${acc.name}</td>
                <td>₹${parseFloat(acc.price).toFixed(2)}</td>
                <td>${months}</td>
                <td>₹${parseFloat(acc.calculated_amount).toFixed(2)}</td>
            </tr>
                `;
                accessoryBody.append(row);
                accessoryTotal += parseFloat(acc.calculated_amount);
            });

            // Update totals
            feeTotalCell.html(`<strong>₹${feeTotal.toLocaleString()}</strong>`);
            accessoryTotalCell.html(`<strong>₹${accessoryTotal.toLocaleString()}</strong>`);
            grandTotalCell.html(`₹${(feeTotal + accessoryTotal).toLocaleString()}`);

            // Hide Part B if accessory total is 0
            $('#accessorySection').toggle(accessoryTotal > 0);
        }
    </script>
@endpush
