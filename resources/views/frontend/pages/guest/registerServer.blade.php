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
            <form id="registrationForm" class="card p-4 shadow-sm">
                @csrf
                <h5 class="card-header bg-primary text-white">Personal Details</h5>
                <div class="card-body">
                    <div class="row mb-3">
                        <!-- <div class="col-md-6 mb-3">
                            <label for="scholar_no" class="form-label">Scholar Number *</label>
                            <input type="text" name="scholar_no" id="scholar_no" class="form-control"
                                pattern="[a-zA-Z0-9]+" title="Scholar number must contain only letters and digits."
                                required>
                            <div id="scholarNoError" class="invalid-feedback"></div>
                            <div id="scholar_noError" class="invalid-feedback"></div>
                        </div> -->
                        <div class="col-md-6 mb-3">
                            <label for="scholar_no" class="form-label">Scholar Number *</label>
                            <input type="text" name="scholar_no" id="scholar_no" class="form-control" required>
                            <div class="invalid-feedback">Scholar number is required.</div>
                        </div>



                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <div id="nameError" class="invalid-feedback"></div>
                            <div id="nameError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            <div id="emailError" class="invalid-feedback"></div>
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="faculty_id" class="block text-gray-700 text-sm font-medium mb-1">Select Faculty
                                <span class="required">*</span></label>
                            <select name="faculty_id" id="faculty_id"
                                class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                                required>
                                <option value="">Select Faculty</option>
                            </select>
                            <div id="facultyError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="department_id" class="block text-gray-700 text-sm font-medium mb-1">Select
                                Department <span class="required">*</span></label>
                            <select name="department_id" id="department_id"
                                class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                                required>
                                <option value="">Select Department</option>
                            </select>
                            <div id="departmentError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="course_id" class="block text-gray-700 text-sm font-medium mb-1">Select Course <span
                                    class="required">*</span></label>
                            <select name="course_id" id="course_id"
                                class="form-select w-full border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                                required>
                                <option value="">Select Course</option>
                            </select>
                            <div id="courseError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                        </div>


                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label for="fathers_name" class="form-label">Father's Name *</label>
                            <input type="text" name="fathers_name" id="fathers_name" class="form-control" required>
                            <div id="fathersNameError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
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
                        <div class="col-md-6 mb-3">
                            <label for="emergency_no" class="form-label">Emergency Contact Number *</label>
                            <input type="text" name="emergency_no" id="emergency_no" class="form-control"
                                pattern="[0-9]{10}" required>
                            <div id="emergencyNoError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="number" class="form-label">Your Contact Number</label>
                            <input type="text" name="number" id="number" class="form-control"
                                pattern="[0-9]{10}">
                            <div id="numberError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="parent_no" class="form-label">Parent's Contact Number</label>
                            <input type="text" name="parent_no" id="parent_no" class="form-control"
                                pattern="[0-9]{10}">
                            <div id="parentNoError" class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
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
                        <select name="months" id="months" class="form-select" required onchange="calculateTotalFee()">
                            <option value=""> Select Duartion of Stay </option>
                            {{-- <option value="1">Temporary (1 Month)</option> --}}
                            <option value="3">Regular (3 Months)</option>
                            <option value="6">Regular (6 Months)</option>
                            <option value="9">Regular (9 Months)</option>
                            <option value="12">Regular (12 Months)</option>
                        </select>
                        <div id="stayDurationError" class="invalid-feedback"></div>
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

                    <!--Add on Acc------>
                     <!-- <div class="col-md-6 mt-3 shadow p-4 rounded bg-light">
                            <h5 class="mb-3">🏠 Hostel Fee Breakup</h5>


                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Hostel Fee</td>
                                        <td id="hostelFee">₹ 6000 /- Monthly</td>
                                    </tr>
                                    <tr>
                                        <td>Caution Money</td>
                                        <td>₹ 10000 /- One Time</td>
                                    </tr>
                                    <tr class="table-info fw-bold">
                                        <td>Total Payable</td>
                                        <td id="totalFee">₹ 16000 /-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> -->

                        <!-- <div class="col-md-6 mt-3 shadow p-4 rounded bg-light"> -->
                             <!-- <h5 class="mb-3">🏠 Hostel Fee Breakup</h5>  -->
                                <!-- Fee Table -->
                                <!-- <table class="table table-bordered table-striped">
                                    <thead class="table-dark">
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
                                    <td>₹ 10000</td>
                                    </tr>

                                    <tr id="accessoryTotalRow">
                                    <td>Accessories Total</td>
                                    <td id="accessoryFee">₹ 0</td>
                                    </tr>
                                    <tr class="table-info fw-bold">
                                    <td>Total Payable</td>
                                    <td id="totalFee">₹ 10000</td>
                                    </tr>
                                        </tbody>
                                </table> 
                        </div>  -->
                
                    <!-- Fee Table (initially hidden) -->
                    {{-- <div id="feeBreakupContainer" class="mt-4 d-none col-md-6 mt-3 shadow p-4 rounded bg-light">
                        <h5 class="mb-3">💰 Fee Summary for <span id="durationLabel"></span></h5>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>Fee Type</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody id="feeBreakupBody">
                            <tr>
                                <td>Hostel Fee</td>
                                <td id="hostelFee">₹ 0</td>
                            </tr>
                            <tr>
                                <td>Caution Money</td>
                                <td>₹ 10000</td>
                            </tr>
                            <!-- Accessory rows will be injected here -->
                            <tr id="accessoryTotalRow" class="d-none">
                                <td>Accessories Total</td>
                                <td id="accessoryFee">₹ 0</td>
                            </tr>
                            <tr class="table-info fw-bold">
                                <td>Total Payable</td>
                                <td id="totalFee">₹ 10000</td>
                            </tr>
                            </tbody>
                        </table>
                    </div> --}}

                    <div id="feeBreakupContainer" class="mt-4 d-none col-md-6 shadow p-4 rounded bg-light">
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
                    </div>

            

                    <!-- fee Waver  -->
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <!-- <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="fee_waiver" id="fee_waiver"
                                    value="1">
                                <label class="form-check-label" for="fee_waiver">Apply Fee Waiver</label>
                            </div> -->

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
                        <!-- <div class="col-md-6 mb-3">
                            <label for="waiver_document" class="form-label">Fee Waiver Document</label>
                            <input type="file" name="attachment" id="waiver_document" class="form-control">
                            <div id="waiverDocumentError" class="invalid-feedback"></div>
                        </div> 
                        <div class="col-md-12">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                            <div id="remarksError" class="invalid-feedback"></div>
                        </div> -->
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

    <!-- //Final working  -->
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

                $('#submitBtn').prop('disabled', !valid);
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


  



        <!-- Dynamic Fee Calculation -->
    <!-- <script>
    const monthlyFee = 6000;
    const cautionMoney = 10000;

    function calculateTotalFee() {
        const duration = parseInt(document.getElementById('months')?.value) || 0;
        const hostelFee = duration * monthlyFee;

        let accessoryTotal = 0;
        const accessoryRows = [];

        // Remove previous accessory rows
        document.querySelectorAll('.accessory-row').forEach(row => row.remove());

        // Loop through checked accessories
        document.querySelectorAll('#additional-accessories input[type="checkbox"]:checked').forEach(checkbox => {
        const label = checkbox.closest('.form-check')?.querySelector('label')?.innerText || '';
        const match = label.match(/(.+?)\s*\((\d+(\.\d+)?)\s*INR\)/i);

        if (match) {
            const name = match[1].trim();
            const pricePerMonth = parseFloat(match[2]);
            const totalForDuration = pricePerMonth * duration;
            accessoryTotal += totalForDuration;

            // Create accessory row
            const row = document.createElement('tr');
            row.classList.add('accessory-row');
            row.innerHTML = `
            <td>${name} × ${duration} month${duration > 1 ? 's' : ''}</td>
            <td>₹ ${totalForDuration.toFixed(2)}</td>
            `;
            document.getElementById('accessoryTotalRow').before(row);
        }
        });

        const total = hostelFee + cautionMoney + accessoryTotal;

        // Update static rows
        document.getElementById('hostelFee').innerText = `₹ ${hostelFee}`;
        document.getElementById('accessoryFee').innerText = `₹ ${accessoryTotal.toFixed(2)}`;
        document.getElementById('totalFee').innerText = `₹ ${total.toFixed(2)}`;
    }

    // Attach listeners
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('months')?.addEventListener('change', calculateTotalFee);

        document.getElementById('additional-accessories').addEventListener('change', function (e) {
        if (e.target && e.target.matches('input[type="checkbox"]')) {
            calculateTotalFee();
        }
        });

        calculateTotalFee(); // Initial run
    });
    </script> -->


    <!-- <script>
        const monthlyFee = 6000;
        const cautionMoney = 10000;

        function calculateTotalFee() {
            const durationSelect = document.getElementById('months');
            const duration = parseInt(durationSelect.value) || 0;

            // Show/hide fee table
            const feeContainer = document.getElementById('feeBreakupContainer');
            if (duration > 0) {
            feeContainer.classList.remove('d-none');
            document.getElementById('durationLabel').innerText = `${duration} month${duration > 1 ? 's' : ''}`;
            } else {
            feeContainer.classList.add('d-none');
            return;
            }

            const hostelFee = duration * monthlyFee;
            let accessoryTotal = 0;

            // Remove previous accessory rows
            document.querySelectorAll('.accessory-row').forEach(row => row.remove());

            // Loop through checked accessories
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

            // Show/hide accessory total row
            const accessoryRow = document.getElementById('accessoryTotalRow');
            if (accessoryTotal > 0) {
            accessoryRow.classList.remove('d-none');
            document.getElementById('accessoryFee').innerText = `₹ ${accessoryTotal.toFixed(2)}`;
            } else {
            accessoryRow.classList.add('d-none');
            document.getElementById('accessoryFee').innerText = `₹ 0`;
            }

            const total = hostelFee + cautionMoney + accessoryTotal;
            document.getElementById('hostelFee').innerText = `₹ ${hostelFee}`;
            document.getElementById('totalFee').innerText = `₹ ${total.toFixed(2)}`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('months')?.addEventListener('change', calculateTotalFee);
            document.getElementById('additional-accessories').addEventListener('change', function (e) {
            if (e.target && e.target.matches('input[type="checkbox"]')) {
                calculateTotalFee();
            }
            });
            calculateTotalFee(); // Initial run
        });
    </script> -->


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
        document.getElementById('additional-accessories').addEventListener('change', function (e) {
            if (e.target && e.target.matches('input[type="checkbox"]')) {
                calculateTotalFee();
            }
        });
        calculateTotalFee(); // Initial run
    });
</script>


@endpush
