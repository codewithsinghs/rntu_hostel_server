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
                <input type="hidden" name="_token" value="7OQGhMSuh1zvv8wSr4e6lBEfFyCk2vpvdVeLFign" autocomplete="off">

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
                    <button type="submit" class="edit-btn" id="submitBtn" disabled>
                        Register
                    </button>
                    <div id="loading" class="mt-3 text-center text-muted d-none">Submitting...</div>
                </section>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Optional: Add interactivity or analytics tracking
        console.log('Services page loaded');
    </script>
@endpush
