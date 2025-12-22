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
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("registrationForm");
            const submitBtn = document.getElementById("submitBtn");
            const loading = document.getElementById("loading");

            form.addEventListener("submit", function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!form.checkValidity()) {
                    form.classList.add("was-validated");
                    Swal.fire("Validation Error",
                        "Please correct the highlighted errors before submitting.", "error");
                    return;
                }

                // Disable button and show loading
                submitBtn.disabled = true;
                loading.classList.remove("d-none");

                let formData = new FormData(form);

                fetch("/api/register", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.errors) {
                            // Laravel validation errors
                            Object.keys(data.errors).forEach(field => {
                                const input = document.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add("is-invalid");
                                    let errorDiv = input.nextElementSibling;
                                    if (errorDiv && errorDiv.classList.contains(
                                            "invalid-feedback")) {
                                        errorDiv.innerText = data.errors[field][0];
                                    }
                                }
                            });
                            Swal.fire("Error", "Please fix the highlighted errors.", "error");
                        } else if (data.success) {
                            Swal.fire("Success", data.message || "Registration successful!", "success");
                            form.reset();
                            form.classList.remove("was-validated");
                        } else {
                            Swal.fire("Error", data.message ||
                                "Something went wrong. Please try again.", "error");
                        }
                    })
                    .catch(() => {
                        Swal.fire("Server Error", "Unable to submit form at the moment.", "error");
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        loading.classList.add("d-none");
                    });
            });
        });
    </script>

    <script>
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
    </script>
    <!-- //Final working  -->
    {{-- <script type="text/javascript">
        $(document).ready(function() {
            let defaultAccessoryHeadIds = [];
            let allAccessories = [];

            function displayError(field, message) {
                console.log('Displaying error for:', field, message); // Debug
                const $field = $('[name="' + field + '"]');
                $('#' + field + 'Error').text(message);
                $field.addClass('is-invalid');



                // Optional: scroll to first error
                if (!$('.is-invalid').first().is(':visible')) {
                    $('html, body').animate({
                        scrollTop: $field.offset().top - 100
                    }, 500);
                }
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
            }



            function checkFormValidity(showAlert = false) {
                let valid = true;

                // Clear previous errors
                $('#registrationForm [required]').each(function() {
                    clearError(this.name);
                });

                // Validate required fields
                $('#registrationForm [required]').each(function() {
                    if (!this.checkValidity()) {
                        displayError(this.name, this.validationMessage || 'This field is required.');
                        valid = false;
                    }
                });

                // Custom validations
                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Please select a room preference.');
                    valid = false;
                } else {
                    clearError('room_preference');
                }

                if (!$('#agree').is(':checked')) {
                    displayError('agree', 'You must agree to the terms.');
                    valid = false;
                } else {
                    clearError('agree');
                }

                // Optional alert only during submit
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



            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                });

            $('#agree').change(checkFormValidity);



            // checkFormValidity();
            // toggleConditionalFields();




            $('#registrationForm').submit(function(e) {
                e.preventDefault();
                $('#errorMessage').addClass('hidden');
                $('.is-invalid').removeClass('is-invalid');

                const isValid = checkFormValidity({
                    showAlert: true,
                    showErrors: true
                });
                if (!isValid) return;

                showConfirmationModal().then((result) => {
                    if (!result.isConfirmed) return;

                    const formData = new FormData(this);

                    $.each(defaultAccessoryHeadIds, function(i, id) {
                        formData.append('accessories[]', id);
                    });

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





    <script>
        const monthlyFee = 6000;
        const cautionMoney = 10000;

        function calculateTotalFee() {
            const duration = parseInt(document.getElementById('months').value) || 0;
            const feeContainer = document.getElementById('feeBreakupContainer');

            if (duration > 0) {
                feeContainer.classList.remove('d-none');
                document.getElementById('durationLabel').innerText = `${duration} month${duration > 1 ? 's' : ''}`;
            } else {
                feeContainer.classList.add('d-none');
                return;
            }

            // Part A: Hostel Fee + Caution Money
            const hostelFee = duration * monthlyFee;
            const partA = hostelFee + cautionMoney;

            document.getElementById('hostelFee').innerText = `₹ ${hostelFee.toFixed(2)}`;
            document.getElementById('cautionFee').innerText = `₹ ${cautionMoney.toFixed(2)}`;
            document.getElementById('partAFee').innerText = `₹ ${partA.toFixed(2)}`;

            // Part B: Accessories
            const accessorySection = document.getElementById('accessorySection');

            let accessoryTotal = 0;
            document.querySelectorAll('.accessory-row').forEach(row => row.remove());

            const checkedAccessories = document.querySelectorAll('#additional-accessories input[type="checkbox"]:checked');
            checkedAccessories.forEach(checkbox => {
                const label = checkbox.closest('.form-check')?.querySelector('label')?.innerText || '';
                const match = label.match(/(.+?)\s*\((\d+(\.\d+)?)\s*INR\)/i);

                if (match) {
                    const name = match[1].trim();
                    const pricePerMonth = parseFloat(match[2]);
                    const totalForDuration = pricePerMonth * duration;
                    accessoryTotal += totalForDuration;

                    const row = document.createElement('tr');
                    row.classList.add('accessory-row');
                    row.innerHTML = `
                    <td>${name} × ${duration} month${duration > 1 ? 's' : ''}</td>
                    <td>₹ ${totalForDuration.toFixed(2)}</td>
                `;
                    document.getElementById('accessoryTotalRow').before(row);
                }
            });

            if (accessoryTotal > 0) {
                accessorySection.classList.remove('d-none');
            } else {
                accessorySection.classList.add('d-none');
            }

            document.getElementById('accessoryFee').innerText = `₹ ${accessoryTotal.toFixed(2)}`;
            document.getElementById('partBFee').innerText = `₹ ${accessoryTotal.toFixed(2)}`;

            // Final Total
            const totalPayable = partA + accessoryTotal;
            document.getElementById('totalFee').innerText = `₹ ${totalPayable.toFixed(2)}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('months')?.addEventListener('change', calculateTotalFee);
            document.getElementById('additional-accessories').addEventListener('change', function(e) {
                if (e.target && e.target.matches('input[type="checkbox"]')) {
                    calculateTotalFee();
                }
            });
            calculateTotalFee(); // Initial run
        });
    </script>
@endpush
