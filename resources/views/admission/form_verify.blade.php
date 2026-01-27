@extends('admission.layout')

@section('head')
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

        /* Style for required asterisk */
        .required {
            color: #ef4444;
            /* Red color for required indicator */
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid mt-5 mb-5">
        <h2 class="text-center bg-dark text-white p-3">Guest Form Details</h2>

        <div id="errorMessage" class="alert alert-danger d-none" role="alert">
            <strong>Error!</strong> <span id="errorMessageText">Something went wrong. Please try again.</span>
        </div>

        <div id="registrationSuccessContainer" class="alert alert-success d-none text-center">
            <h4 class="alert-heading">Guest Verified successfully!</h4>
            <p>Thank you!</p>
        </div>

        <div id="approvalMessageContainer" class="alert alert-info d-none text-center">
            Your registration is awaiting admin approval. Keep checking for updates.
        </div>

        <form id="registrationForm" class="card p-4 shadow-sm">
            @csrf
            <h5 class="card-header bg-primary text-white">Personal Details</h5>
            <div class="card-body">
                <div class="row mb-3">
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
                        <label for="faculty_id" class="block text-gray-700 text-sm font-medium mb-1">Select Faculty <span
                                class="required">*</span></label>
                        <select name="faculty_id" id="faculty_id"
                            class="form-select w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value=""> Select Faculty </option>
                        </select>
                        <div id="facultyError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="department_id" class="block text-gray-700 text-sm font-medium mb-1">Select Department
                            <span class="required">*</span></label>
                        <select name="department_id" id="department_id"
                            class="form-select w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value=""> Select Department </option>
                        </select>
                        <div id="departmentError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="course_id" class="block text-gray-700 text-sm font-medium mb-1">Select Course <span
                                class="required">*</span></label>
                        <select name="course_id" id="course_id"
                            class="form-select w-full p-2 border border-gray-300 rounded-md focus:ring focus:ring-blue-200 focus:border-blue-500"
                            required>
                            <option value=""> Select Course </option>
                        </select>
                        <div id="courseError" class="invalid-feedback text-red-500 text-xs mt-1"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="scholar_no" class="form-label">Scholar Number *</label>
                        <input type="text" name="scholar_no" id="scholar_no" class="form-control" pattern="[a-zA-Z0-9]+"
                            title="Scholar number must contain only letters and digits." required>
                        <div id="scholarNoError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender *</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value=""> Select Gender </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <div id="genderError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <!-- <label for="waiver_document" class="form-label">Fee Waiver Document</label>
                                                    <input type="file" name="attachment" id="waiver_document" class="form-control">
                                                    <div id="waiverDocumentError" class="invalid-feedback"></div> -->

                        <div class="row">
                            <!-- This will take full width (like colspan="2") -->
                            <div class="col-md-12 mt-3">
                                <label for="waiver_document" class="form-label">Fee Waiver Document</label>
                                <input type="file" name="attachment" id="waiver_document" class="form-control">
                                <div id="waiverDocumentError" class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-12" id="waiver_document_attachments">
                            </div>

                        </div>

                    </div>

                </div>

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
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="is_postpaid" id="is_postpaid"
                                value="1">
                            <label class="form-check-label" for="is_postpaid">Postpaid</label>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea name="remarks" id="remarks" rows="3" class="form-control"></textarea>
                        <div id="remarksError" class="invalid-feedback"></div>
                    </div>
                </div>

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
                        <input type="text" name="local_guardian_name" id="local_guardian_name" class="form-control">
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
                        <input type="text" name="number" id="number" class="form-control" pattern="[0-9]{10}">
                        <div id="numberError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="parent_no" class="form-label">Parent's Contact Number</label>
                        <input type="text" name="parent_no" id="parent_no" class="form-control" pattern="[0-9]{10}">
                        <div id="parentNoError" class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_no" class="form-label">Local Guardian's Contact Number</label>
                        <input type="text" name="guardian_no" id="guardian_no" class="form-control"
                            pattern="[0-9]{10}">
                        <div id="guardianNoError" class="invalid-feedback"></div>
                    </div>
                </div>

                <h5 class="bg-primary text-white p-2">Preferences</h5>
                <!-- <div class="mb-3">
                                                <label class="form-label">Food Preference *</label>
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="food_preference" id="food_veg" value="Veg" required>
                                                        <label class="form-check-label" for="food_veg">Veg</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="food_preference" id="food_nonveg" value="Non-Veg">
                                                        <label class="form-check-label" for="food_nonveg">Non-Veg</label>
                                                    </div>
                                                </div>
                                                <div id="foodPreferenceError" class="invalid-feedback"></div>
                                            </div> -->

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

                <!-- Stay Duaration -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- Stay Duaration -->
                        <div class="mb-3">
                            <label for="months" class="form-label">Stay Duration *</label>
                            <select name="months" id="months" class="form-select" required>
                                <option value=""> Select Type </option>
                                <option value="1">Temporary (1 Month)</option>
                                <option value="3">Regular (3 Months)</option>
                                <option value="6">Regular (6 Months)</option>
                                <option value="9">Temporary (9 Month)</option>
                                <option value="12">Regular (12 Months)</option>
                            </select>
                            <div id="stayDurationError" class="invalid-feedback"></div>
                        </div>

                        <div class="row g-4 mb-4">
                            <!-- Complementary Accessories -->
                            <div class="col-md-6 col-sm-12">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-primary">🎁 Complementary Accessories</h6>
                                    </div>
                                    <div class="d-flex flex-wrap p-2" id="default-accessories">
                                        <p class="text-muted mb-0">Loading complementary accessories...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Accessories -->
                            <div class="col-md-6 col-sm-12">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 text-success">🧩 Additional Accessories (Optional)</h6>
                                    </div>
                                    <div class="d-flex flex-wrap p-2" id="additional-accessories">
                                        <p class="text-muted mb-0">Loading additional accessories...</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 p-2">
                        <!-- Fee Breakdown -->
                        <div class="card bg-light border-0 shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Invoice Summary</h5>
                            </div>

                            <div class="card-body p-0">
                                <!-- Fee Breakdown -->
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="table-primary">
                                            <tr>
                                                <th scope="col">Fee Component</th>
                                                <th scope="col">Charge Type</th>
                                                <th scope="col">Base Rate</th>
                                                <th scope="col">Computed Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="fee-breakdown-body">
                                            <!-- Fee rows injected dynamically -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-light">
                                                <td colspan="3" class="text-end"><strong>Total Fee</strong></td>
                                                <td id="fee-total"><strong>₹0</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Accessory Charges -->
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-sm mb-0">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th scope="col">Accessory Item</th>
                                                <th scope="col">Unit Cost</th>
                                                <th scope="col">Duration (Months)</th>
                                                <th scope="col">Computed Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="accessory-breakdown-body">
                                            <!-- Accessory rows injected dynamically -->
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-light">
                                                <td colspan="3" class="text-end"><strong>Total Accessories</strong>
                                                </td>
                                                <td id="accessory-total"><strong>₹0</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Grand Total -->
                                <div class="text-end mt-4 px-3">
                                    <h5 class="mb-0">Grand Total: <span id="grand-total">₹0</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="form-check mb-3 mt-2">
                    <input class="form-check-input" type="checkbox" name="verify" id="verify">
                    <label class="form-check-label" for="verify">
                        Please check this box to verify.
                    </label>
                    <div id="verifyError" class="invalid-feedback"></div>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    Update Form
                </button>
                <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div>
            </div>
        </form>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            let guest_id = window.location.pathname.replace(/\/$/, "").split("/").pop();

            let defaultAccessoryHeadIds = [];
            let allAccessories = [];
            // console.log('dd', defaultAccessoryHeadIds);
            // console.log('all', allAccessories);
            function displayError(field, message) {
                $('#' + field + 'Error').text(message);
                $('[name="' + field + '"]').addClass('is-invalid');
            }

            function clearError(field) {
                $('#' + field + 'Error').text('');
                $('[name="' + field + '"]').removeClass('is-invalid');
            }

            // Set global headers once
            $.ajaxSetup({
                // headers: {
                //     'Authorization': 'Bearer YOUR_TOKEN',
                //     'X-Requested-With': 'XMLHttpRequest',
                //     'token': localStorage.getItem('token'),
                //     'auth-id': localStorage.getItem('auth-id')
                // }
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                },
            });

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

                if (!$('input[name="food_preference"]:checked').length) valid = false;
                if (!$('input[name="room_preference"]:checked').length) valid = false;

                // $('#submitBtn').prop('disabled', !valid);
            }

            $('#fee_waiver').change(toggleConditionalFields);

            $('#registrationForm input, #registrationForm select, #registrationForm textarea').on(
                'input blur change',
                function() {
                    clearError(this.name);
                    checkFormValidity();
                });

            // $('#verify').change(checkFormValidity);

            checkFormValidity();
            toggleConditionalFields();
            $.ajax({
                // url: '/api/admission/accessories/active',
                url: '/api/accessories/active',
                type: 'GET',
                dataType: 'json',
                // headers: {
                //     'Authorization': 'Bearer YOUR_TOKEN', // 👈 add your headers here
                //     'X-Requested-With': 'XMLHttpRequest',
                //     'token': localStorage.getItem('token'),
                //     'auth-id': localStorage.getItem('auth-id')
                // },
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                },
                success: function(data) {
                    allAccessories = data.data;
                    let defaultHTML = '',
                        additionalHTML = '';

                    if (allAccessories.length === 0) {
                        defaultHTML = '<p>No free accessories available.</p>';
                        additionalHTML = '<p>No additional accessories available.</p>';
                    } else {
                        // console.log("ac", allAccessories);
                        // $.each(allAccessories, function(i, acc) {
                        //     if (parseFloat(acc.price) === 0) {
                        //         defaultAccessoryHeadIds.push(acc.id);
                        //         defaultHTML +=
                        //             `<div class="text-gray-700 py-1">${acc.accessory_head.name}</div>`;
                        //     } else {
                        //         additionalHTML += `<div class="form-check">
                    //     <input class="form-check-input" type="checkbox" value="${acc.id}" name="accessories[]" id="accessory-${acc.id}">
                    //     <label class="form-check-label" for="accessory-${acc.id}">
                    //         ${acc.accessory_head.name} (${parseFloat(acc.price).toFixed(2)} INR)
                    //     </label>
                    // </div>`;
                        //     }
                        // });


                        // $.each(allAccessories, function (i, acc) {
                        //     const price = parseFloat(acc.price);
                        //     const name = acc.accessory_head?.name || 'Unnamed';
                        //     const id = acc.id;

                        //     if (price === 0) {
                        //         // Push default accessory ID for backend tracking
                        //         defaultAccessoryHeadIds.push(id);

                        //         // Render as non-interactive item with hidden input
                        //         defaultHTML += `
                    //             <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    //                 <div class="fw-semibold text-muted">${name}</div>
                    //                 <span class="badge bg-success">Included</span>
                    //             </div>
                    //             <input type="hidden" name="accessories[]" value="${id}">
                    //         `;
                        //     } else {
                        //         // Render as interactive checkbox for optional accessories
                        //         additionalHTML += `
                    //             <div class="form-check mb-2">
                    //                 <input class="form-check-input" type="checkbox" value="${id}" name="accessories[]" id="accessory-${id}">
                    //                 <label class="form-check-label" for="accessory-${id}">
                    //                     <span class="fw-medium">${name}</span>
                    //                     <span class="text-muted">(${price.toFixed(2)} INR)</span>
                    //                 </label>
                    //             </div>
                    //         `;
                        //     }
                        // });

                        $.each(allAccessories, function(i, acc) {
                            const price = parseFloat(acc.price);
                            const name = acc.accessory_head?.name || 'Unnamed';
                            const id = acc.id;

                            if (price === 0) {
                                // defaultAccessoryHeadIds.push(id);

                                defaultHTML += `
                                    <div class="accessory-box flex-grow-1 me-3 mb-3">
                                        <div class="bg-light border rounded px-3 py-2 d-flex justify-content-between align-items-center">
                                            <span class="text-muted fw-semibold">${name}</span>
                                            <span class="badge bg-success">Included</span>
                                        </div>
                                        <input type="hidden" name="accessories[]" value="${id}">
                                    </div>
                                `;
                            } else {
                                additionalHTML += `
                                    <div class="accessory-box flex-grow-1 me-3 mb-3">
                                        <div class="bg-light border rounded px-3 py-2">
                                            <div class="form-check d-flex justify-content-between align-items-center">
                                                <input class="form-check-input me-2" type="checkbox" value="${id}" name="accessories[]" id="accessory-${id}">
                                                <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="accessory-${id}">
                                                    <span class="fw-medium">${name}</span>
                                                    <span class="text-muted">₹ ${price.toFixed(2)}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        });




                    }

                    $('#default-accessories').html(defaultHTML);
                    $('#additional-accessories').html(additionalHTML);
                },
                error: function() {
                    $('#errorMessageText').text('Error loading accessories.');
                    $('#errorMessage').removeClass('hidden');
                }
            });

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

                if (!$('input[name="room_preference"]:checked').length) {
                    displayError('room_preference', 'Select bed preference.');
                    valid = false;
                }


                let formData = new FormData(this);
                // console.log('formdata', formData);

                // process.kill(process.pid); // Terminates the current process

                // $.each(defaultAccessoryHeadIds, function(i, id) {
                //     formData.append('accessory_head_ids[]', id);
                // });

                // $('input[name="accessories[]"]:checked').each(function() {
                //     formData.append('accessory_head_ids[]', $(this).val());
                // });
                // formData.append('accessories[]', id);


                console.log('formdata', formData);
                // process.kill(process.pid); // Terminates the current process
                formData.set('fee_waiver', $('#fee_waiver').is(':checked') ? '1' : '0');
                formData.set('bihar_credit_card', $('#bihar_credit_card').is(':checked') ? '1' : '0');
                formData.set('tnsd', $('#tnsd').is(':checked') ? '1' : '0');
                formData.set('is_postpaid', $('#is_postpaid').is(':checked') ? '1' : '0');

                formData.set('is_verified', $('#verify').is(':checked') ? '1' : '0');
                formData.set('remarks', $('#remarks').val().trim());

                if ($('#fee_waiver').is(':checked') && $('#waiver_document')[0].files.length > 0) {
                    formData.append('attachment', $('#waiver_document')[0].files[0]);
                }

                // $('#submitBtn').prop('disabled', true);
                $('#loading').removeClass('hidden');
                formData.append('_method', 'PUT');
                // console.log("Submitting form with data:", Array.from(formData.entries()));
                // $.ajax({
                //     url: '/api/admission/verify-guest/' + guest_id,
                //     method: 'POST',
                //     data: formData,
                //     contentType: false,
                //     processData: false,
                //     headers: {
                //         // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //         'token': localStorage.getItem('token'),
                //         'auth-id': localStorage.getItem('auth-id')
                //     },
                //     success: function (response) {
                //         $('#loading').addClass('hidden');
                //         if (response.success) {
                //             // $('#registrationForm').hide();
                //             $('#registrationSuccessContainer').removeClass('hidden');
                //             setTimeout(() => {
                //                 window.location.href = '/admission/guest/forms';
                //             }, 3000);
                //         } else {
                //             $('#errorMessageText').text(response.message || 'Registration failed.');
                //             $('#errorMessage').removeClass('hidden');
                //         }
                //     },
                //     error: function (xhr) {
                //         $('#loading').addClass('hidden');
                //         $('#errorMessage').removeClass('hidden');
                //         let response = xhr.responseJSON;
                //         if (response && response.errors) {
                //             $.each(response.errors, function (field, msgs) {
                //                 displayError(field, msgs[0]);
                //             });
                //         } else {
                //             $('#errorMessageText').text('An error occurred. Try again.');
                //         }
                //     }
                // });


                $.ajax({
                    url: '/api/admission/verify-guest/' + guest_id,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    beforeSend: function() {
                        $('#loading').removeClass('hidden');
                    },
                    success: function(response) {
                        $('#loading').addClass('hidden');

                        if (response.success) {
                            const guest = response.data?.guest;

                            if (guest?.is_verified == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Guest Verified',
                                    text: 'The guest has been successfully verified and updated.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Guest Updated',
                                    text: 'Guest details were updated, but verification was not selected.',
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }

                            $('#registrationSuccessContainer').removeClass('hidden');
                            setTimeout(() => {
                                window.location.href = '/admission/guest/forms';
                            }, 3000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Verification Failed',
                                text: response.message ||
                                    'Unable to verify guest. Please check the details.'
                            });
                        }
                    },

                    error: function(xhr) {
                        $('#loading').addClass('hidden');
                        let response = xhr.responseJSON;

                        if (response && response.errors) {
                            let errorList = Object.values(response.errors).map(msgs => msgs[0])
                                .join('<br>');
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorList
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Something went wrong while verifying the guest. Please try again.'
                            });
                        }
                    }
                });

            });



            // // Load faculties
            // $.getJSON('/api/admission/faculties/active', function (data) {
            //     let facultySelect = $('#faculty_id');
            //     facultySelect.empty().append('<option value="">-- Select Faculty --</option>');
            //     $.each(data.data, function (i, faculty) {
            //         facultySelect.append(`<option value="${faculty.id}">${faculty.name}</option>`);
            //     });
            // }).fail(function () {
            //     displayError('faculty_id', 'Error loading faculties.');
            // });
            // Load faculties


            $.ajax({
                url: '/api/faculties/active',
                type: 'GET',
                dataType: 'json',
                // headers: {
                //     'Authorization': 'Bearer YOUR_TOKEN', // 👈 add your headers here
                //     'X-Requested-With': 'XMLHttpRequest',
                //     'token': localStorage.getItem('token'),
                //     'auth-id': localStorage.getItem('auth-id')
                // },
                headers: {
                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    'Accept': 'application/json'
                },
                success: function(data) {
                    let facultySelect = $('#faculty_id');
                    facultySelect.empty().append('<option value=""> Select Faculty </option>');
                    $.each(data.data, function(i, faculty) {
                        facultySelect.append(
                            `<option value="${faculty.id}">${faculty.name}</option>`);
                    });
                    // ✅ Now load guest data AFTER faculty list is ready
                    loadGuestData();
                },
                error: function() {
                    displayError('faculty_id', 'Error loading faculties.');
                }
            });

            function loadGuestData() {
                $.ajax({
                    // url: `/api/admin/guests/${guest_id}`,
                    url: `/api/admin/guests/${guest_id}`,
                    method: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    success: function(response) {
                        // console.log(response);
                        if (response.success) {
                            let guest = response.data;
                            let accessories = guest.accessories || [];
                            let accessoryIds = accessories
                                .map(acc => acc.item_id);
                            accessoryIds.forEach(function(id) {
                                console.log(id);
                                $(`input[name="accessories[]"][value="${id}"]`).prop("checked",
                                    true);
                            });
                            $('#name').val(guest.name);
                            $('#email').val(guest.email);
                            $('#faculty_id').val(guest.faculty_id);
                            $('#department_id').val(guest.department_id);
                            $('#scholar_no').val(guest.scholar_no);
                            $('#gender').val(guest.gender);
                            guest.fee_waiver == 1 ? $('#fee_waiver').prop('checked', true) : 'N/A';
                            guest.bihar_credit_card == 1 ? $('#bihar_credit_card').prop('checked',
                                    true) :
                                'N/A';
                            guest.tnsd == 1 ? $('#tnsd').prop('checked', true) : 'N/A';
                            guest.is_postpaid == 1 ? $('#is_postpaid').prop('checked', true) : 'N/A';

                            let department_id = guest.department_id;
                            $.ajax({
                                url: '/api/admin/departments?faculty_id=' + guest.faculty_id,
                                type: 'GET',
                                // headers: {
                                //     'token': localStorage.getItem('token'),
                                //      'auth-id': localStorage.getItem('auth-id'),
                                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                // },
                                headers: {
                                    'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                    'Accept': 'application/json'
                                },
                                success: function(response) {
                                    if (response.success && Array.isArray(response.data)) {
                                        let options =
                                            '<option value=""> Select Department </option>';
                                        response.data.forEach(function(department) {
                                            options +=
                                                `<option value="${department.id}">${department.name}</option>`;
                                        });
                                        $('#department_id').html(options);
                                        $('#department_id').val(department_id);
                                    } else {
                                        showAlert("danger", response.message ||
                                            "No departments found for this faculty.");
                                    }
                                },
                                error: function(xhr) {
                                    showAlert("danger",
                                        "An error occurred while fetching departments.");
                                }
                            });

                            let course_id = guest.course_id;
                            $.ajax({
                                url: '/api/departments/' + guest.department_id + '/courses',
                                type: 'GET',
                                headers: {
                                    'token': localStorage.getItem('token'),
                                    'auth-id': localStorage.getItem('auth-id'),
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    if (response.success && Array.isArray(response.data)) {
                                        let options =
                                            '<option value=""> Select Course </option>';
                                        response.data.forEach(function(course) {
                                            options +=
                                                `<option value="${course.id}">${course.name}</option>`;
                                        });
                                        $('#course_id').html(options);
                                        $('#course_id').val(course_id);
                                    } else {
                                        showAlert("danger", response.message ||
                                            "No courses found for this department.");
                                    }
                                },
                                error: function(xhr) {
                                    showAlert("danger",
                                        "An error occurred while fetching courses.");
                                }
                            });

                            // $('#department_id').val(guest.department_id);
                            $('#course_id').val(guest.course_id);

                            $('#remarks').val(guest.remarks);
                            $('#fathers_name').val(guest.fathers_name);
                            $('#mothers_name').val(guest.mothers_name);
                            $('#local_guardian_name').val(guest.local_guardian_name);
                            //bed_preference
                            switch (guest.room_preference) {
                                case 'Single':
                                    $("#room_single").prop("checked", true);
                                    break;
                                case 'Double':
                                    $("#room_double").prop("checked", true);
                                    break;
                                default:
                                    $("#room_triple").prop("checked", true);
                                    break;
                            }
                            $('#emergency_no').val(guest.emergency_no);
                            $('#number').val(guest.number);
                            $('#parent_no').val(guest.parent_no);
                            $('#guardian_no').val(guest.guardian_no);
                            $('#room_preference').val(guest.room_preference);
                            // $('#food_preference').val(guest.food_preference);
                            $('#months').val(guest.months);
                            // console.log(guest.accessories);
                            if (Array.isArray(guest.accessories)) {
                                guest.accessories.forEach(function(item) {
                                    $(`input[name="accessories[]"][value="${item.id}"]`).prop(
                                        "checked", true);
                                });
                            }

                            if (guest.attachment_path) {
                                $('#waiver_document_attachments').html(
                                    `<a style='text-decoration:none;' target='_new' href='${guest.attachment_path}'>Waiver Document</a>`
                                );
                            }

                            if (guest.is_verified == 1) {
                                $("#verify").prop("checked", true);
                            }

                            // Trigger fee preview after guest is loaded
                            triggerFeePreview();
                        }
                    }

                });
            }

            // Load departments based on selected faculty
            $('#faculty_id').change(function() {
                let facultyId = $(this).val();
                let departmentSelect = $('#department_id');
                departmentSelect.empty().append('<option value="">  Select Department  </option>');

                if (facultyId) {
                    $.ajax({
                        url: `/api/admission/faculties/${facultyId}/departments`,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'Authorization': 'Bearer YOUR_TOKEN', // 👈 add your headers here
                            'X-Requested-With': 'XMLHttpRequest',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id')
                        },
                        success: function(data) {
                            $.each(data.data, function(i, department) {
                                departmentSelect.append(
                                    `<option value="${department.id}">${department.name}</option>`
                                );
                            });
                        },
                        error: function() {
                            displayError('department_id', 'Error loading departments.');
                        }
                    });
                }

            });
            // Load courses based on selected department
            $('#department_id').change(function() {
                let departmentId = $(this).val();
                let courseSelect = $('#course_id');
                courseSelect.empty().append('<option value="">  Select Course </option>');

                if (departmentId) {
                    $.ajax({
                        url: `/api/admission/departments/${departmentId}/courses`,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'Authorization': 'Bearer YOUR_TOKEN', // 👈 put your header(s) here
                            'X-Requested-With': 'XMLHttpRequest',
                            'token': localStorage.getItem('token'),
                            'auth-id': localStorage.getItem('auth-id')
                        },
                        success: function(data) {
                            $.each(data.data, function(i, course) {
                                courseSelect.append(
                                    `<option value="${course.id}">${course.name}</option>`
                                );
                            });
                        },
                        error: function() {
                            displayError('course_id', 'Error loading courses.');
                        }
                    });
                }

            });

            // Fee Calculation using Api
            function triggerFeePreview() {
                const months = parseInt($('#months').val()) || 1;
                const accessoryIds = Array.from(document.querySelectorAll('input[name="accessories[]"]:checked'))
                    .map(el => parseInt(el.value));

                $.ajax({
                    url: `/api/guests/${guest_id}/invoice-preview`,
                    method: 'POST',
                    contentType: 'application/json',
                    dataType: 'json',
                    data: JSON.stringify({
                        months,
                        accessory_ids: accessoryIds
                    }),
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    //     'token': localStorage.getItem('token'),
                    //     'auth-id': localStorage.getItem('auth-id')
                    // },
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        renderFeeBreakdown(response);
                    },
                    error: function() {
                        $('#fee-breakdown').html(
                            '<div class="alert alert-danger">Failed to load fee info.</div>');
                    }
                });
            }

            // fee Rendering
            // function renderFeeBreakdown(data) {
            // const tbody = $('#breakdown-body');
            // const totalCell = $('#breakdown-total');
            // tbody.empty();

            // let total = 0;

            // data.fees.forEach(fee => {
            //     const row = `
        //     <tr>
        //         <td>${fee.name}</td>
        //         <td>${fee.is_one_time ? 'One-Time' : 'Recurring'}</td>
        //         <td>₹${parseFloat(fee.amount).toFixed(2)}</td>
        //         <td>₹${parseFloat(fee.calculated_amount).toFixed(2)}</td>
        //     </tr>
        //     `;
            //     tbody.append(row);
            //     total += parseFloat(fee.calculated_amount);
            // });

            // data.accessories.forEach(acc => {
            //     const row = `
        //     <tr>
        //         <td>${acc.name}</td>
        //         <td>Accessory</td>
        //         <td>₹${parseFloat(acc.price).toFixed(2)}</td>
        //         <td>₹${parseFloat(acc.calculated_amount).toFixed(2)}</td>
        //     </tr>
        //     `;
            //     tbody.append(row);
            //     total += parseFloat(acc.calculated_amount);
            // });

            // totalCell.html(`<strong>₹${total.toLocaleString()}</strong>`);
            // }

            function renderFeeBreakdown(data) {
                const feeBody = $('#fee-breakdown-body');
                const feeTotalCell = $('#fee-total');
                const accessoryBody = $('#accessory-breakdown-body');
                const accessoryTotalCell = $('#accessory-total');
                const grandTotalCell = $('#grand-total');

                feeBody.empty();
                accessoryBody.empty();

                let feeTotal = 0;
                let accessoryTotal = 0;
                const months = parseInt($('#months').val()) || 1;

                // Render fees
                data.fees.forEach(fee => {
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
                data.accessories.forEach(acc => {
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
            }


            $(document).ready(function() {
                $('#months').on('change', triggerFeePreview);
                $('#accessory-options').on('change', 'input[name="accessories[]"]', triggerFeePreview);

                // Initial trigger after guest loads
                setTimeout(triggerFeePreview, 500); // slight delay to ensure accessories are rendered
            });

            $(document).on('change', 'input[name="accessories[]"]', triggerFeePreview);

        });
    </script>
@endpush
