@extends('frontend.layouts.app')

@section('title', 'Our Services')
@section('meta_description', 'Explore the range of professional services we offer.')

@push('styles')
@endpush

@section('content')

    <div class="main">
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
                <form id="registrationForm" novalidate>

                    <!-- Profile Information -->
                    <section class="form-box">

                        <!-- Heading -->
                        <div class="form-header">
                            <h3>Profile Information</h3>
                            <!-- <button class="edit-btn">Edit</button> -->
                        </div>

                        <!-- Form-grid -->
                        <div class="form-grid">


                            <!-- Scholar No -->
                            <div class="form-field">
                                <label for="scholar_no" class="form-label">Scholar Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="scholar_no" id="scholar_no" class="form-control"
                                    placeholder="Enter Scholar number" required>
                                <div id="scholar_noError" class="invalid-feedback">Please enter scholar number (OR temporary scholar number if
                                    unavailable).</div>
                            </div>

                            <!-- Faculty -->
                            <div class="form-field">
                                <label for="faculty_id" class="form-label">Select Faculty <span
                                        class="text-danger">*</span></label>
                                <select name="faculty_id" id="faculty_id" class="form-select" placeholder="Select Faculty"
                                    required>
                                    <option value="">Select Faculty</option>
                                </select>
                                <div class="invalid-feedback">Please select a faculty.</div>
                            </div>

                            <!-- Department -->
                            <div class="form-field">
                                <label for="department_id" class="form-label">Select Department <span
                                        class="text-danger">*</span></label>
                                <select name="department_id" id="department_id" class="form-select"
                                    placeholder="Select Department" required>
                                    <option value="">Select Department</option>
                                </select>
                                <div class="invalid-feedback">Please select a department.</div>
                            </div>

                            <!-- Course -->
                            <div class="form-field">
                                <label for="course_id" class="form-label">Select Course <span
                                        class="text-danger">*</span></label>
                                <select name="course_id" id="course_id" class="form-select" placeholder="Select Course"
                                    required>
                                    <option value="">Select Course</option>
                                </select>
                                <div class="invalid-feedback">Please select a course.</div>
                            </div>

                            <!-- Full Name -->
                            <div class="form-field">
                                <label for="name" class="form-label">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter Full Name" required>
                                <div class="invalid-feedback">Please enter your full name.</div>
                            </div>

                            <!-- Email -->
                            <div class="form-field">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter valid email address" required>
                                <div id="emailError" class="invalid-feedback">Please enter valid email address.</div>
                            </div>

                            <!-- Contact Number -->
                            <div class="form-field">
                                <label for="number" class="form-label">Contact Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="number" id="number" class="form-control"
                                    placeholder="Enter Mobile Number" pattern="[0-9]{10}" required>
                                <div class="invalid-feedback">Enter a valid 10-digit contact number.</div>
                            </div>


                            <!-- Gender -->
                            <div class="form-field">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select" placeholder="Select Gender"
                                    required>
                                    <option value="">Select Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                                <div class="invalid-feedback">Please select your gender.</div>
                            </div>
                        </div>

                    </section>

                    <!-- Profile Information End -->

                    <!-- Fee Waiver Start -->
                    {{-- <section class="form-box">

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


                    </section> --}}
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
                                <label for="fathers_name" class="form-label">Father's Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="fathers_name" id="fathers_name" class="form-control"
                                    placeholder="Enter father's name" required>
                                <div class="invalid-feedback">Please enter father's name.</div>
                            </div>

                            <!-- Mother Name -->
                            <div class="form-field">
                                <label for="mothers_name" class="form-label">Mother's Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="mothers_name" id="mothers_name" class="form-control"
                                    placeholder="Enter mother's name" required>
                                <div class="invalid-feedback">Please enter mother's name.</div>
                            </div>

                            <!-- Parent's Contact Number -->
                            <div class="form-field">
                                <label for="parent_no" class="form-label">Parent Contact Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="parent_no" id="parent_no" class="form-control"
                                    pattern="[0-9]{10}" placeholder="Enter parent's contact number" required>
                                <div class="invalid-feedback">Enter a valid 10-digit parent's contact number.</div>
                            </div>

                            <!-- Emaergency's Contact Number -->
                            <div class="form-field">
                                <label for="emergency_no" class="form-label">Emergency Contact Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="emergency_no" id="emergency_no" class="form-control"
                                    pattern="[0-9]{10}" placeholder="Enter emergency contact number" required>
                                <div class="invalid-feedback">Enter a valid 10-digit emergency contact number.</div>
                            </div>

                            <!-- guardiien's name -->
                            <div class="form-field">
                                <label for="local_guardian_name" class="form-label">Local Guardien Name</label>
                                <input type="text" name="local_guardian_name" id="local_guardian_name"
                                    class="form-control" placeholder="Enter local guardien's name" required>
                                <div class="invalid-feedback">Enter local guardien's name.</div>
                                <!-- <div class="invalid-feedback"></div> -->
                            </div>

                            <!-- guardiien's Contact Number -->
                            <div class="form-field">
                                <label for="guardian_no" class="form-label">Local Guardien's Contact Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="guardian_no" id="guardian_no" class="form-control"
                                    pattern="[0-9]{10}" placeholder="Enter guardien's contact number" required>
                                <div class="invalid-feedback">Enter a valid 10-digit guardien's contact number.</div>
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
                            <!-- Bed Preference -->
                            <div class="form-field">
                                <label for="room_preference">Select Room Preference <span
                                        class="text-danger">*</span></label>
                                <select name="room_preference" id="room_preference" required
                                    aria-describedby="roomPreferenceError">
                                    <option selected value="">Select Room Preference</option>
                                    <option value="Single">Single</option>
                                    <option value="Double">Double</option>
                                    <option value="Triple">Triple</option>
                                </select>
                                <div class="invalid-feedback">Please select your room preference.</div>
                            </div>

                            <!-- Stay Duration -->
                            <div class="form-field ">
                                <label for="months" class="form-label">Stay Duration (Payment in Advance)<span
                                        class="text-danger">*</span></label>
                                <select name="months" id="months" class="form-select" required>
                                    <option value="">Select Duration</option>
                                    <option value="3">Regular (3 Months)</option>
                                    <option value="6">Regular (6 Months)</option>
                                    <option value="9">Regular (9 Months)</option>
                                    <option value="12">Regular (12 Months)</option>
                                </select>
                                <div class="invalid-feedback">Please select stay duration.</div>
                            </div>

                            {{-- <!-- Food Preference -->
                        <div class="form-field">
                            <label for="faculty">Select Food Preference <span class="text-danger">*</span></label>
                            <select name="Food_id" id="Food" required aria-describedby="foodPreferenceError">
                                <option selected value="">Select Food Preference</option>
                                <option value="1">Veg</option>
                                <option value="2">Non-Veg</option>
                                <option value="3">Both</option>
                            </select>
                            <div class="invalid-feedback">Enter a valid 10-digit emergency number.</div>

                        </div> --}}

                            <!-- bed preference -->
                            {{-- <div class="form-check form-check-inline">
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
                        <div class="invalid-feedback">Please select your bed preference.</div> --}}

                        </div>
                    </section>
                    <!-- Preferences Ends -->


                    <!-- Com on Acc------><!--Add on Acc------><!--Add on Fee------>
                    <!-- Accessories Start -->
                    <section class="form-box">

                        <!-- Form-grid -->
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3 d-none" id="accessory">

                                <!-- Heading -->
                                <div class="form-header ">
                                    <h3>Accessories</h3>
                                    <!-- <button class="edit-btn">Edit</button> -->
                                </div>

                                <!--Comp Acc------>
                                <!-- Free Accessories -->

                                <div class="form-field mb-5 ">
                                    <label class="form-label fw-semibold">Complimentary Accessories</label>
                                    <div class="border rounded p-3 bg-light d-flex" id="default-accessories">
                                        <p class="text-muted mb-0">Fetching complimentary accessories...</p>
                                    </div>
                                </div>

                                <!-- Additional Accessories (Optional) -->
                                <div class="form-field mb-2 ">
                                    <label class="form-label fw-semibold">Optional Add-on Accessories</label>
                                    <div class="border rounded p-3 bg-light d-flex flex-wrap gap-3"
                                        id="additional-accessories">
                                        <p class="text-muted mb-0">Fetching add-on accessories...</p>
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
                            <div id="fee-breakdown" class="col-md-6 card shadow-sm  d-none fs-5">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">Fee Breakdown (Payable in Advance)</h6>
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
                                                    <td colspan="3" class="text-end"><strong>Total (Part
                                                            A)</strong></td>
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
                                                        <td colspan="3" class="text-end"><strong>Total (Part
                                                                B)</strong>
                                                        </td>
                                                        <td id="accessory-total" class="fw-bold text-primary"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- Grand Total -->
                                    <div class="alert alert-success text-center fw-bold">
                                        Grand Total : <span id="grand-total" class="fs-5"> (Payable in advance)</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement -->
                        <div class="form-field">
                            <div class="check-flex form-check">
                                <input class="form-check-input" type="checkbox" name="agree" id="agree" required>
                                <label class="form-check-label " for="agree">
                                    I agree to the <a href="/terms-and-conditions" target="_blank">terms and
                                        conditions</a>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="invalid-feedback">You must agree before submitting.</div>
                            </div>
                        </div>


                    </section>
                    <!-- Accessories End -->


                    {{-- <!-- Terms -->
                <div class="form-checkform-field">
                    <input class="form-check-input" type="checkbox" id="agree" required>
                    <label class="form-check-label" for="agree">
                        I agree to the <a href="/terms-and-conditions" target="_blank">terms and conditions</a> *
                    </label>
                    <div class="invalid-feedback">You must agree before submitting.</div>
                </div> --}}
                    <!-- Submit Btn -->
                    <section class="submit-btn-registration">
                        <button type="submit" class="edit-btn" id="submitBtn">
                            Register
                        </button>
                        <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div>
                    </section>
                    {{-- <button type="submit" class="btn btn-primary w-100" id="submitBtn">Register</button>
                <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div> --}}

                </form>
            </div>
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
            // just inputError id class missing in Html
            function displayError(field, message) {
                console.log(field);
                const $field = $('[name="' + field + '"]');
                $('#' + field + 'Error').text(message);
                $field.addClass('is-invalid');

                // Scroll to first error if needed
                if ($('.is-invalid').first().length) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 200);
                    // scrollTop: $('.is-invalid').first().offset().top - 100
                }
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
            }

            // function displayError(field, message) {
            //     console.log(field);
            //     const $field = $('[name="' + field + '"]');

            //     // Add error class
            //     $field.addClass('is-invalid');

            //     // Find or create invalid-feedback container
            //     let $feedback = $field.next('.invalid-feedback');
            //     if (!$feedback.length) {
            //         $feedback = $('<div class="invalid-feedback"></div>').insertAfter($field);
            //     }

            //     // Inject message
            //     $feedback.text(message).show();

            //     // Scroll to first error
            //     if ($('.is-invalid').first().length) {
            //         $('html, body').animate({
            //             scrollTop: $('.is-invalid').first().offset().top - 100
            //         }, 200);
            //     }
            // }

            // function clearError(field) {
            //     const $field = $('[name="' + field + '"]');
            //     $field.removeClass('is-invalid');
            //     $field.next('.invalid-feedback').text('').hide();
            // }


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
                // if (!$('input[name="room_preference"]:checked').length) {
                //     displayError('room_preference', 'Please select a room preference.');
                //     valid = false;
                // } else {
                //     clearError('room_preference');
                // }

                // Custom validation: agree
                // Agree checkbox validation
                // if (!$('#agree').is(':checked')) {
                //     displayError('agree', 'I agree to the terms and conditions *');
                //     valid = false;
                // } else {
                //     clearError('agree');
                // }

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
                    // if (this.name === 'room_preference') {
                    //     if (!$('input[name="room_preference"]:checked').length) {
                    //         displayError('room_preference', 'Please select a room preference.');
                    //     } else {
                    //         clearError('room_preference');
                    //     }
                    // }
                    if (this.name === 'agree') {
                        if (!$('#agree').is(':checked')) {
                            displayError('agree', 'You must agree to the terms.');
                        } else {
                            clearError('agree');
                        }
                    }

                }
            );

            function showConfirmationModal(message = "Do you want to submit this form?") {
                return Swal.fire({
                    title: "Confirm Submission",
                    text: message,
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "Cancel"
                });
            }




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

                            console.log(response);
                            if (response && response.errors) {
                                $.each(response.errors, function(field, msgs) {
                                    console.log(field, msgs[0]);
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
                        defaultHTML = '<p>No complementry accessories available.</p>';
                        additionalHTML = '<p>No add-on accessories available.</p>';
                    } else {
                        $('#accessory').removeClass('d-none');
                        defaultAccessoryHeadIds = [];
                        $.each(allAccessories, function(i, acc) {
                            if (parseFloat(acc.is_default) === 1) {
                                defaultAccessoryHeadIds.push(acc.id);


                                defaultHTML += `
                                        <div class="border rounded p-3 mb-2 bg-white shadow-sm mx-2">
                                            <strong>${acc.accessory_head.name}</strong>
                                        </div>`;
                            } else {
                                additionalHTML += `
                                    <div class="form-check border rounded p-3 mb-1 bg-white shadow-sm flex-grow-1 mx-2">
                                        <input class="form-check-input" type="checkbox" value="${acc.id}" name="accessories[]" id="accessory-${acc.accessory_head.id}">
                                        <label class="form-check-label px-3" for="accessory-${acc.accessory_head.id}">
                                            <strong>${acc.accessory_head.name}</strong> (${parseFloat(acc.price).toFixed(2)} INR)
                                        </label>
                                    </div>`;
                            }
                        });
                    }
                    // $('#accessory').removeClass('d-none');
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

            // When faculty change
            $('#faculty_id').on('change', triggerFeePreview);

            // When months change
            $('#months').on('change', triggerFeePreview);

            // When accessories change
            $('#additional-accessories').on('change', 'input[type="checkbox"]', triggerFeePreview);

            // Initial load (optional)
            triggerFeePreview();

            // Init
            loadFaculties();

            // Hide  accessory
            // $('#accessory').toggle(facultyId != '');
        });
    </script>

    <script>
        // Trigger Fee Preview
        function triggerFeePreview() {
            const facultyId = $('#faculty_id').val();
            if (!facultyId) return; // wait until faculty is selected

            const months = parseInt($('#months').val()) || 0;
            const accessoryIds = Array.from(document.querySelectorAll('input[name="accessories[]"]:checked'))
                .map(el => parseInt(el.value));
            // console.log(accessoryIds);
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
            const months = parseInt($('#months').val()) || 0;

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
