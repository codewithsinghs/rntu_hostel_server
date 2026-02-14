@extends('resident.layout')

@section('content')
    <!-- Data Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Overview</a></div>

                <!-- Overview -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Leave Requests</p>
                            <h3 id="total-requests">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Leave Approved</p>
                            <h3 id="total-leaves-taken">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/approved.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Requests</p>
                            <h3 id="total-leaves-pending">2</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Rejected Requests</p>
                            <h3 id="total-leaves-rejected">12</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/min.png') }}" alt="" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Apply Leave -->
    {{-- <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- message -->
                <div id="message" class="alert d-none"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#leaveRequestCollapse" aria-expanded="false"
                    aria-controls="leaveRequestCollapse">

                    <span class="breadcrumbs">Leave Request Form</span>
                    <span class="btn btn-primary">Apply for Leave</span>

                </button>

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="leaveRequestCollapse">
                    <!-- Form -->
                    <form id="leaveRequestForm" enctype="multipart/form-data">

                        @csrf <!-- CSRF Token -->

                        <div class="inpit-boxxx">

                            <span class="input-set">
                                <label for="LeaveType">Leave Type</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected="">Select Leave Type</option>
                                    <option value="personal">Personal</option>
                                    <option value="medical">Medical</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="FromDate">Enter Days</label>
                                <input type="text">
                            </span>

                            <span class="input-set">
                                <label for="from_date" class="form-label">From Date:</label>
                                <input type="date" id="from_date" name="from_date" required>
                            </span>

                            <span class="input-set">
                                <label for="to_date" class="form-label">To Date:</label>
                                <input type="date" id="to_date" name="to_date" required>
                            </span>




                            <div class="reason">
                                <label for="photo" class="form-label">Supporting Photo/Document (Optional):</label>
                                <input type="file" id="photo" name="photo" accept="image/*">
                            </div>

                            <div class="reason">
                                <label for="reason" class="form-label">Reason:</label>
                                <textarea id="reason" name="reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="submitted">Submit Request</button>

                    </form>
                    <!-- Form End -->
                </div>

            </div>
        </div>
    </section> --}}

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- Message -->
                <div id="message" class="alert d-none"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#leaveRequestCollapse" aria-expanded="false"
                    aria-controls="leaveRequestCollapse">
                    <span class="breadcrumbs">Leave Request Form</span>
                    <span class="btn btn-primary">Apply for Leave</span>
                </button>

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="leaveRequestCollapse">
                    <!-- Form -->
                    <form id="leaveRequestForm" enctype="multipart/form-data">
                        @csrf <!-- CSRF Token -->

                        <div class="inpit-boxxx">

                            <!-- Leave Type with professional options -->
                            <span class="input-set">
                                <label for="type" class="form-label">Leave Type *</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Select Leave Type</option>
                                    <option value="medical">Medical Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                    <option value="casual">Casual Leave</option>
                                    <option value="parental">Parental/Family Leave</option>
                                    <option value="festival">Festival Leave</option>
                                    <option value="official">Academic/Official Leave</option>
                                    <option value="semester_break">Semester Break</option>
                                    <option value="exam">Exam Related Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="other">Other</option>
                                </select>
                                <small class="form-text text-muted">Select appropriate leave category</small>
                            </span>

                            <!-- Reason with intelligent filtering -->
                            <span class="input-set">
                                <label for="reason" class="form-label">Reason *</label>
                                <select class="form-select" id="reason" name="reason" required>
                                    <option value="" selected disabled>Select Reason</option>
                                    <!-- Options will be populated dynamically based on leave type -->
                                </select>
                                <small class="form-text text-muted">Choose appropriate reason for selected leave
                                    type</small>
                            </span>

                            <!-- Custom Reason (Only shows when 'Other' is selected) -->
                            <span class="input-set" id="custom_reason_container" style="display: none;">
                                <label for="custom_reason" class="form-label">Specify Custom Reason *</label>
                                <input type="text" id="custom_reason" name="custom_reason" class="form-control"
                                    placeholder="Please specify your reason">
                            </span>

                            <!-- Dates -->
                            <span class="input-set">
                                <label for="start_date" class="form-label">From Date *</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" required>
                                <small class="form-text text-muted">Leave start date</small>
                            </span>

                            <span class="input-set">
                                <label for="end_date" class="form-label">To Date *</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" required>
                                <small class="form-text text-muted">Leave end date</small>
                            </span>

                            <!-- Duration Display -->
                            <span class="input-set">
                                <label class="form-label">Duration</label>
                                <div class="form-control" id="duration_display" style="background-color: #f8f9fa;">
                                    <span id="days_count">0</span> day(s)
                                </div>
                                <small class="form-text text-muted">Total leave days</small>
                            </span>

                            <!-- Emergency Contact (for emergency leaves) -->
                            <span class="input-set" id="emergency_contact_container" style="display: none;">
                                <label for="emergency_contact" class="form-label">Emergency Contact Number *</label>
                                <input type="tel" id="emergency_contact" name="emergency_contact"
                                    class="form-control" placeholder="Emergency contact number">
                                <small class="form-text text-muted">Required for emergency leaves</small>
                            </span>

                            <!-- Medical Certificate (for medical leaves) -->
                            <span class="input-set" id="medical_certificate_container" style="display: none;">
                                <label for="medical_certificate" class="form-label">Medical Certificate *</label>
                                <input type="file" id="medical_certificate" name="medical_certificate"
                                    class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">Upload medical certificate (PDF/Image)</small>
                            </span>

                            <!-- Supporting Documents -->
                            <div class="input-set">
                                <label for="attachment" class="form-label">Supporting Documents (Optional):</label>
                                <input type="file" id="attachment" name="attachment" class="form-control "
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="padding-top:16px;">
                                <small class="form-text text-muted">Upload supporting documents if any</small>
                            </div>

                            <!-- Additional Details -->
                            <div class="reason p-0">
                                <label for="description" class="form-label">Additional Details (Optional):</label>
                                <textarea id="description" name="description" rows="3" class="form-control"
                                    placeholder="Provide any additional information about your leave"></textarea>
                                <small class="form-text text-muted">Maximum 500 characters</small>
                            </div>

                        </div>
                        <!-- Declaration -->
                        <div class="form-checks mt-3 mb-3 p-4">
                            <input class="form-check-input " type="checkbox" id="declaration" required>
                            <label class="form-check-label" for="declaration">
                                I declare that the information provided is true and correct. I understand that
                                providing false information may lead to disciplinary action.
                            </label>
                        </div>

                        <!-- Previous Leave Status (Optional) -->
                        <div class="alert alert-info" id="leave_status_alert" style="display: none;">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                <span id="leave_status_message"></span>
                            </small>
                        </div>
                        <button type="submit" class="submitted">Submit Request</button>

                    </form>
                    <!-- Form End -->
                </div>

            </div>
        </div>
    </section>

    <!-- Add this CSS for better styling -->
    <style>
        .common-con-tainer {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .inpit-boxxx {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .input-set {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .input-set label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .input-set .form-select,
        .input-set .form-control {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .input-set .form-select:focus,
        .input-set .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .form-text {
            font-size: 12px;
            color: #7f8c8d;
        }

        .reason {
            grid-column: 1 / -1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .reason textarea {
            min-height: 100px;
            resize: vertical;
        }

        .submitted {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            width: 100%;
            margin-top: 20px;
        }

        .submitted:hover {
            background: linear-gradient(135deg, #2980b9, #1c6ea4);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .submitted:active {
            transform: translateY(0);
        }

        .form-check {
            padding-left: 1.5em;
            margin: 15px 0;
        }

        .form-check-input {
            margin-top: 0.3em;
        }

        .form-check-label {
            font-size: 14px;
            color: #555;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px 15px;
            border-radius: 6px;
        }

        optgroup {
            font-weight: 600;
            color: #2c3e50;
            font-style: normal;
        }

        optgroup option {
            font-weight: normal;
            padding-left: 20px;
        }

        #duration_display {
            font-weight: 600;
            color: #2c3e50;
            display: flex;
            align-items: center;
            padding: 10px 12px;
        }

        @media (max-width: 768px) {
            .inpit-boxxx {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('leaveRequestForm');
                    const typeSelect = document.getElementById('type');
                    const reasonSelect = document.getElementById('reason');
                    const customReasonContainer = document.getElementById('custom_reason_container');
                    const customReasonInput = document.getElementById('custom_reason');
                    const fromDateInput = document.getElementById('start_date');
                    const toDateInput = document.getElementById('end_date');
                    const durationDisplay = document.getElementById('duration_display');
                    const daysCount = document.getElementById('days_count');
                    const emergencyContactContainer = document.getElementById('emergency_contact_container');
                    const medicalCertificateContainer = document.getElementById('medical_certificate_container');
                    const declarationCheckbox = document.getElementById('declaration');
                    const leaveStatusAlert = document.getElementById('leave_status_alert');
                    const leaveStatusMessage = document.getElementById('leave_status_message');

                    // Set minimum date to today
                    const today = new Date().toISOString().split('T')[0];
                    fromDateInput.min = today;
                    toDateInput.min = today;



                    // Handle reason selection
                    reasonSelect.addEventListener('change', function() {
                        if (this.value === 'other') {
                            customReasonContainer.style.display = 'flex';
                            customReasonInput.required = true;
                        } else {
                            customReasonContainer.style.display = 'none';
                            customReasonInput.required = false;
                        }
                    });

                    // Handle leave type selection
                    typeSelect.addEventListener('change', function() {
                        const type = this.value;

                        // Show/hide emergency contact for emergency leaves
                        if (type === 'emergency') {
                            emergencyContactContainer.style.display = 'flex';
                            document.getElementById('emergency_contact').required = true;
                        } else {
                            emergencyContactContainer.style.display = 'none';
                            document.getElementById('emergency_contact').required = false;
                        }

                        // Show/hide medical certificate for medical leaves
                        // if (type === 'medical') {
                        //     medicalCertificateContainer.style.display = 'flex';
                        //     document.getElementById('medical_certificate').required = true;
                        // } else {
                        //     medicalCertificateContainer.style.display = 'none';
                        //     document.getElementById('medical_certificate').required = false;
                        // }
                    });

                    // Calculate duration when dates change
                    function calculateDuration() {
                        if (fromDateInput.value && toDateInput.value) {
                            const fromDate = new Date(fromDateInput.value);
                            const toDate = new Date(toDateInput.value);

                            if (toDate < fromDate) {
                                daysCount.textContent = 'Invalid';
                                durationDisplay.style.color = '#dc3545';
                                return;
                            }

                            const timeDiff = Math.abs(toDate - fromDate);
                            const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                            daysCount.textContent = diffDays;
                            durationDisplay.style.color = '#28a745';

                            Check leave duration limits
                            if (diffDays > 7) {
                                leaveStatusAlert.style.display = 'block';
                                leaveStatusMessage.textContent = 'Leave exceeds 7 days. May require special approval.';
                                leaveStatusAlert.className = 'alert alert-warning';
                            } else if (diffDays > 3) {
                                leaveStatusAlert.style.display = 'block';
                                leaveStatusMessage.textContent = 'Leave requires HOD approval.';
                                leaveStatusAlert.className = 'alert alert-info';
                            } else {
                                leaveStatusAlert.style.display = 'none';
                            }
                        }
                    }

                    fromDateInput.addEventListener('change', function() {
                        toDateInput.min = this.value;
                        calculateDuration();
                    });

                    toDateInput.addEventListener('change', calculateDuration);

                    // Form validation
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        // Basic validation
                        if (!declarationCheckbox.checked) {
                            alert('Please accept the declaration to proceed.');
                            return;
                        }

                        if (fromDateInput.value && toDateInput.value) {
                            const fromDate = new Date(fromDateInput.value);
                            const toDate = new Date(toDateInput.value);

                            if (toDate < fromDate) {
                                alert('End date cannot be before start date.');
                                return;
                            }

                            const timeDiff = Math.abs(toDate - fromDate);
                            const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                            if (diffDays > 30) {
                                if (!confirm(
                                        'Leave exceeds 30 days. Are you sure you want to apply for such a long leave?'
                                    )) {
                                    return;
                                }
                            }
                        }

                        // Prepare form data
                        const formData = new FormData(form);

                        // Add calculated duration
                        if (daysCount.textContent !== 'Invalid') {
                            formData.append('duration', daysCount.textContent);
                        }

                        // Show loading state
                        //     const submitBtn = form.querySelector('.submitted');
                        //     const originalText = submitBtn.textContent;
                        //     submitBtn.innerHTML =
                        //         '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
                        //     submitBtn.disabled = true;

                        //     // Submit form via AJAX
                        //     fetch('/api/resident/leaves', {
                        //             method: 'POST',
                        //             headers: {
                        //                 'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        //                 'Accept': 'application/json'
                        //             },
                        //             body: formData
                        //         })
                        //         .then(async response => {
                        //             const data = await response.json();
                        //             const messageDiv = document.getElementById("message");

                        //             if (response.ok) {
                        //                 messageDiv.className = "alert alert-success";
                        //                 messageDiv.textContent = data.message ||
                        //                     "Leave request submitted successfully!";
                        //                 messageDiv.classList.remove("d-none");
                        //                 form.reset();
                        //                 leaveStatusAlert.style.display = 'none';

                        //                 // Trigger event for other components to update
                        //                 document.dispatchEvent(new CustomEvent('leaveSubmitted'));

                        //                 // Collapse the form after successful submission
                        //                 const collapse = document.getElementById('leaveRequestCollapse');
                        //                 const bsCollapse = new bootstrap.Collapse(collapse, {
                        //                     toggle: false
                        //                 });
                        //                 bsCollapse.hide();

                        //             } else {
                        //                 messageDiv.className = "alert alert-danger";
                        //                 messageDiv.textContent = data.error || "Error submitting request.";
                        //                 messageDiv.classList.remove("d-none");

                        //                 if (data.messages) {
                        //                     for (const field in data.messages) {
                        //                         console.error(`${field}: ${data.messages[field].join(', ')}`);
                        //                     }
                        //                 }
                        //             }
                        //         })
                        //         .catch(error => {
                        //             console.error("Unexpected error:", error);
                        //             const messageDiv = document.getElementById("message");
                        //             messageDiv.className = "alert alert-danger";
                        //             messageDiv.textContent = "An unexpected error occurred.";
                        //             messageDiv.classList.remove("d-none");
                        //         })
                        //         .finally(() => {
                        //             submitBtn.textContent = originalText;
                        //             submitBtn.disabled = false;
                        //         });
                        // });

                        // Pre-populate reason based on leave type
                        typeSelect.addEventListener('change', function() {
                            const type = this.value;
                            const reasonMapping = {
                                'medical': 'Select Medical Reason',
                                'emergency': 'Select Emergency Reason',
                                'casual': 'Select Reason for Casual Leave',
                                'parental': 'Select Family Reason',
                                'festival': 'Select Festival/Event',
                                'official': 'Select Academic Reason',
                                'other': 'other'
                            };

                            if (reasonMapping[type]) {
                                reasonSelect.innerHTML =
                                    `<option value="" selected disabled>${reasonMapping[type]}</option>` +
                                    reasonSelect.innerHTML.substring(reasonSelect.innerHTML.indexOf(
                                        '<optgroup'));
                            }
                        });

                        // Initialize tooltips
                        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                        tooltips.forEach(el => new bootstrap.Tooltip(el));
                    });
    </script> --}}

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('leaveRequestForm');
            const typeSelect = document.getElementById('type');
            const reasonSelect = document.getElementById('reason');
            const customReasonContainer = document.getElementById('custom_reason_container');
            const customReasonInput = document.getElementById('custom_reason');
            const fromDateInput = document.getElementById('start_date');
            const toDateInput = document.getElementById('end_date');
            const durationDisplay = document.getElementById('duration_display');
            const daysCount = document.getElementById('days_count');
            const emergencyContactContainer = document.getElementById('emergency_contact_container');
            const medicalCertificateContainer = document.getElementById('medical_certificate_container');
            const declarationCheckbox = document.getElementById('declaration');
            const leaveStatusAlert = document.getElementById('leave_status_alert');
            const leaveStatusMessage = document.getElementById('leave_status_message');
            const messageDiv = document.getElementById('message');

            // Define reason options for each leave type
            const reasonOptions = {
                'medical': `
            <optgroup label="Medical Reasons">
                <option value="fever_cold">Fever/Cold</option>
                <option value="stomach_issue">Stomach Issue</option>
                <option value="headache_migraine">Headache/Migraine</option>
                <option value="medical_checkup">Medical Checkup</option>
                <option value="dental_treatment">Dental Treatment</option>
                <option value="eye_checkup">Eye Checkup</option>
                <option value="vaccination">Vaccination</option>
                <option value="hospitalization">Hospitalization</option>
                <option value="accident">Accident/Injury</option>
                <option value="covid_symptoms">COVID-19 Symptoms</option>
                <option value="physical_therapy">Physical Therapy</option>
                <option value="mental_health">Mental Health Consultation</option>
            </optgroup>
            <optgroup label="Documentation Required">
                <option value="medical_certificate">Medical Certificate</option>
                <option value="prescription_followup">Prescription Follow-up</option>
                <option value="test_reports">Test Reports Collection</option>
            </optgroup>
        `,

                'emergency': `
            <optgroup label="Emergency Situations">
                <option value="family_emergency">Family Emergency</option>
                <option value="accident_emergency">Accident Emergency</option>
                <option value="home_emergency">Home Emergency</option>
                <option value="natural_disaster">Natural Disaster</option>
                <option value="financial_emergency">Financial Emergency</option>
                <option value="legal_emergency">Legal Emergency</option>
            </optgroup>
            <optgroup label="Urgent Requirements">
                <option value="urgent_document">Urgent Document Work</option>
                <option value="passport_visa">Passport/Visa Emergency</option>
                <option value="vehicle_breakdown">Vehicle Breakdown</option>
                <option value="property_emergency">Property Emergency</option>
            </optgroup>
        `,

                'casual': `
            <optgroup label="Personal Requirements">
                <option value="bank_work">Bank Work</option>
                <option value="document_work">Document Work</option>
                <option value="shopping">Essential Shopping</option>
                <option value="personal_meeting">Personal Meeting</option>
                <option value="vehicle_service">Vehicle Service/Repair</option>
                <option value="property_work">Property Related Work</option>
                <option value="marriage_function">Marriage Function</option>
                <option value="religious_function">Religious Function</option>
            </optgroup>
            <optgroup label="Personal Well-being">
                <option value="mental_break">Mental Break/Relaxation</option>
                <option value="family_time">Family Time</option>
                <option value="personal_development">Personal Development</option>
            </optgroup>
        `,

                'parental': `
            <optgroup label="Family Visits">
                <option value="parent_visit">Parent Visit</option>
                <option value="sibling_visit">Sibling Visit</option>
                <option value="family_reunion">Family Reunion</option>
                <option value="home_town_visit">Home Town Visit</option>
            </optgroup>
            <optgroup label="Family Events">
                <option value="family_marriage">Marriage in Family</option>
                <option value="family_function">Family Function</option>
                <option value="birth_anniversary">Birth/Anniversary</option>
                <option value="death_in_family">Death in Family</option>
                <option value="religious_ceremony">Religious Ceremony</option>
            </optgroup>
            <optgroup label="Family Support">
                <option value="family_support">Family Support Required</option>
                <option value="parent_health">Parent Health Issues</option>
                <option value="childcare">Childcare Requirements</option>
            </optgroup>
        `,

                'festival': `
            <optgroup label="Major Festivals">
                <option value="diwali">Diwali Celebration</option>
                <option value="holi">Holi Celebration</option>
                <option value="eid">Eid Celebration</option>
                <option value="christmas">Christmas Celebration</option>
                <option value="dussehra">Dussehra</option>
                <option value="navratri">Navratri</option>
                <option value="ganesh_chaturthi">Ganesh Chaturthi</option>
                <option value="rakhi">Raksha Bandhan</option>
            </optgroup>
            <optgroup label="Regional Festivals">
                <option value="pongal">Pongal</option>
                <option value="onam">Onam</option>
                <option value="bihu">Bihu</option>
                <option value="durga_puja">Durga Puja</option>
                <option value="regional_festival">Regional Festival</option>
            </optgroup>
            <optgroup label="Family Celebrations">
                <option value="birthday">Birthday Celebration</option>
                <option value="anniversary">Family Anniversary</option>
                <option value="engagement">Engagement Ceremony</option>
            </optgroup>
        `,

                'official': `
            <optgroup label="Academic Requirements">
                <option value="exam_preparation">Exam Preparation</option>
                <option value="project_work">Project Work</option>
                <option value="thesis_work">Thesis/Dissertation Work</option>
                <option value="research_work">Research Work</option>
                <option value="library_study">Library Study</option>
                <option value="group_study">Group Study Session</option>
            </optgroup>
            <optgroup label="College Activities">
                <option value="seminar">Seminar/Conference</option>
                <option value="workshop">Workshop/Training</option>
                <option value="college_event">College Event Participation</option>
                <option value="cultural_event">Cultural Event</option>
                <option value="sports_event">Sports Event</option>
                <option value="placement">Placement Activity</option>
            </optgroup>
            <optgroup label="Official Work">
                <option value="internship">Internship Related</option>
                <option value="industrial_visit">Industrial Visit</option>
                <option value="field_work">Field Work</option>
                <option value="department_work">Department Work</option>
            </optgroup>
        `,

                'semester_break': `
            <optgroup label="Break Activities">
                <option value="home_visit">Home Visit</option>
                <option value="vacation_travel">Vacation Travel</option>
                <option value="skill_development">Skill Development Course</option>
                <option value="internship_training">Internship/Training</option>
                <option value="competitive_exam">Competitive Exam Preparation</option>
            </optgroup>
            <optgroup label="Personal Development">
                <option value="language_course">Language Course</option>
                <option value="hobby_development">Hobby Development</option>
                <option value="sports_training">Sports Training</option>
                <option value="volunteer_work">Volunteer Work</option>
            </optgroup>
        `,

                'exam': `
            <optgroup label="Exam Preparation">
                <option value="final_exam_prep">Final Exam Preparation</option>
                <option value="midterm_prep">Mid-term Exam Preparation</option>
                <option value="competitive_exam">Competitive Exam Preparation</option>
                <option value="entrance_exam">Entrance Exam Preparation</option>
                <option value="revision_time">Revision Time</option>
            </optgroup>
            <optgroup label="Exam Related">
                <option value="exam_day">Exam Day</option>
                <option value="practical_exam">Practical Exam</option>
                <option value="viva_voce">Viva Voce/Oral Exam</option>
                <option value="project_evaluation">Project Evaluation</option>
                <option value="result_verification">Result Verification</option>
            </optgroup>
            <optgroup label="Post-Exam">
                <option value="answer_sheet">Answer Sheet Verification</option>
                <option value="reevaluation">Re-evaluation Process</option>
            </optgroup>
        `,

                'personal': `
            <optgroup label="Personal Commitments">
                <option value="banking_financial">Banking/Financial Work</option>
                <option value="government_office">Government Office Work</option>
                <option value="legal_work">Legal Work</option>
                <option value="medical_appointment">Medical Appointment</option>
                <option value="vehicle_related">Vehicle Related Work</option>
                <option value="property_related">Property Related Work</option>
                <option value="insurance_work">Insurance Work</option>
            </optgroup>
            <optgroup label="Personal Development">
                <option value="interview">Job Interview</option>
                <option value="training_course">Training Course</option>
                <option value="certification_exam">Certification Exam</option>
                <option value="skill_workshop">Skill Workshop</option>
                <option value="career_counseling">Career Counseling</option>
            </optgroup>
            <optgroup label="Social Commitments">
                <option value="friend_wedding">Friend's Wedding</option>
                <option value="social_function">Social Function</option>
                <option value="community_service">Community Service</option>
                <option value="religious_activity">Religious Activity</option>
            </optgroup>
        `,

                'other': `
            <optgroup label="Miscellaneous">
                <option value="other">Other (Specify in details)</option>
            </optgroup>
        `
            };

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            if (fromDateInput) fromDateInput.min = today;
            if (toDateInput) toDateInput.min = today;

            // Helper function to get display label for leave type
            function getTypeLabel(type) {
                const typeLabels = {
                    'medical': 'Medical',
                    'emergency': 'Emergency',
                    'casual': 'Casual',
                    'parental': 'Family',
                    'festival': 'Festival',
                    'official': 'Academic/Official',
                    'semester_break': 'Semester Break',
                    'exam': 'Exam',
                    'personal': 'Personal',
                    'other': 'Specific'
                };
                return typeLabels[type] || 'Appropriate';
            }

            // Handle leave type selection - filter reasons
            if (typeSelect && reasonSelect) {
                typeSelect.addEventListener('change', function() {
                    const type = this.value;

                    // Get appropriate options for the selected type
                    let optionsHTML = reasonOptions[type] || reasonOptions['other'];

                    // Always include "Other" option at the end
                    if (type !== 'other' && !optionsHTML.includes('other')) {
                        optionsHTML +=
                            `<optgroup label="Other"><option value="other">Other (Specify below)</option></optgroup>`;
                    }

                    // Update the select with filtered options
                    reasonSelect.innerHTML = `
                <option value="" selected disabled>Select ${getTypeLabel(type)} Reason</option>
                ${optionsHTML}
            `;

                    // Clear custom reason field if it exists
                    if (customReasonContainer && customReasonInput) {
                        customReasonContainer.style.display = 'none';
                        customReasonInput.required = false;
                        customReasonInput.value = '';
                    }

                    // Show/hide emergency contact for emergency leaves
                    if (type === 'emergency' && emergencyContactContainer) {
                        emergencyContactContainer.style.display = 'flex';
                        const emergencyContactInput = document.getElementById('emergency_contact');
                        if (emergencyContactInput) emergencyContactInput.required = true;
                    } else if (emergencyContactContainer) {
                        emergencyContactContainer.style.display = 'none';
                        const emergencyContactInput = document.getElementById('emergency_contact');
                        if (emergencyContactInput) emergencyContactInput.required = false;
                    }

                    // Show/hide medical certificate for medical leaves
                    if (type === 'medical' && medicalCertificateContainer) {
                        medicalCertificateContainer.style.display = 'flex';
                        const medicalCertificateInput = document.getElementById('medical_certificate');
                        if (medicalCertificateInput) medicalCertificateInput.required = true;
                    } else if (medicalCertificateContainer) {
                        medicalCertificateContainer.style.display = 'none';
                        const medicalCertificateInput = document.getElementById('medical_certificate');
                        if (medicalCertificateInput) medicalCertificateInput.required = false;
                    }
                });
            }

            // Handle reason selection for custom reason
            if (reasonSelect && customReasonContainer && customReasonInput) {
                reasonSelect.addEventListener('change', function() {
                    if (this.value === 'other') {
                        customReasonContainer.style.display = 'flex';
                        customReasonInput.required = true;
                        customReasonInput.focus();
                    } else {
                        customReasonContainer.style.display = 'none';
                        customReasonInput.required = false;
                        customReasonInput.value = '';
                    }
                });
            }

            // Calculate duration when dates change
            function calculateDuration() {
                if (fromDateInput && toDateInput && fromDateInput.value && toDateInput.value) {
                    const fromDate = new Date(fromDateInput.value);
                    const toDate = new Date(toDateInput.value);

                    if (toDate < fromDate) {
                        if (daysCount) daysCount.textContent = 'Invalid';
                        if (durationDisplay) durationDisplay.style.color = '#dc3545';
                        return;
                    }

                    const timeDiff = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                    if (daysCount) daysCount.textContent = diffDays;
                    if (durationDisplay) durationDisplay.style.color = '#28a745';

                    // Check leave duration limits
                    if (leaveStatusAlert && leaveStatusMessage) {
                        if (diffDays > 7) {
                            leaveStatusAlert.style.display = 'block';
                            leaveStatusMessage.textContent = 'Leave exceeds 7 days. May require special approval.';
                            leaveStatusAlert.className = 'alert alert-warning';
                        } else if (diffDays > 3) {
                            leaveStatusAlert.style.display = 'block';
                            leaveStatusMessage.textContent = 'Leave requires HOD approval.';
                            leaveStatusAlert.className = 'alert alert-info';
                        } else {
                            leaveStatusAlert.style.display = 'none';
                        }
                    }
                }
            }

            // Date change events
            if (fromDateInput) {
                fromDateInput.addEventListener('change', function() {
                    if (toDateInput) {
                        toDateInput.min = this.value;
                        calculateDuration();
                    }
                });
            }

            if (toDateInput) {
                toDateInput.addEventListener('change', calculateDuration);
            }

            // Function to show field errors
            function showFieldErrors(errors) {
                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                for (const [fieldName, messages] of Object.entries(errors)) {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (!field) continue;

                    // Add error class
                    field.classList.add('is-invalid');

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = Array.isArray(messages) ? messages.join(', ') : messages;

                    // Insert after field
                    field.parentNode.insertBefore(errorDiv, field.nextSibling);
                }
            }

            // Clear errors on input
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(field => {
                    field.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            this.classList.remove('is-invalid');
                            const errorDiv = this.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.remove();
                            }
                        }
                    });
                });
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Clear previous messages and errors
                    if (messageDiv) {
                        messageDiv.classList.add('d-none');
                        messageDiv.textContent = '';
                    }

                    // Basic validation
                    if (declarationCheckbox && !declarationCheckbox.checked) {
                        alert('Please accept the declaration to proceed.');
                        return;
                    }

                    if (fromDateInput && toDateInput && fromDateInput.value && toDateInput.value) {
                        const fromDate = new Date(fromDateInput.value);
                        const toDate = new Date(toDateInput.value);

                        if (toDate < fromDate) {
                            alert('End date cannot be before start date.');
                            return;
                        }

                        const timeDiff = Math.abs(toDate - fromDate);
                        const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                        if (diffDays > 30) {
                            if (!confirm(
                                    'Leave exceeds 30 days. Are you sure you want to apply for such a long leave?'
                                    )) {
                                return;
                            }
                        }
                    }

                    // Prepare form data
                    const formData = new FormData(form);

                    // Add calculated duration
                    if (daysCount && daysCount.textContent !== 'Invalid') {
                        formData.append('duration', daysCount.textContent);
                    }

                    // Show loading state
                    const submitBtn = form.querySelector('.submitted');
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch('/api/resident/leaves', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Success
                            if (messageDiv) {
                                messageDiv.className = 'alert alert-success';
                                messageDiv.textContent = data.message ||
                                    'Leave request submitted successfully!';
                                messageDiv.classList.remove('d-none');
                            }

                            form.reset();

                            if (leaveStatusAlert) {
                                leaveStatusAlert.style.display = 'none';
                            }

                            // Trigger event for other components to update
                            document.dispatchEvent(new CustomEvent('leaveSubmitted'));

                            // Collapse the form after successful submission
                            const collapse = document.getElementById('leaveRequestCollapse');
                            if (collapse) {
                                const bsCollapse = new bootstrap.Collapse(collapse, {
                                    toggle: false
                                });
                                bsCollapse.hide();
                            }

                        } else {
                            // Error handling
                            if (data.status === 'error' && data.errors) {
                                // Show field errors
                                showFieldErrors(data.errors);

                                // Show general message
                                if (messageDiv) {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message ||
                                        'Please correct the errors below.';
                                    messageDiv.classList.remove('d-none');
                                }
                            } else {
                                // Other errors
                                if (messageDiv) {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message || data.error ||
                                        'Error submitting request.';
                                    messageDiv.classList.remove('d-none');
                                }
                            }
                        }

                    } catch (error) {
                        console.error('Unexpected error:', error);
                        if (messageDiv) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.textContent = 'An unexpected error occurred.';
                            messageDiv.classList.remove('d-none');
                        }
                    } finally {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (tooltips.length > 0) {
                tooltips.forEach(el => new bootstrap.Tooltip(el));
            }
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('leaveRequestForm');
            const typeSelect = document.getElementById('type');
            const reasonSelect = document.getElementById('reason');
            const customReasonContainer = document.getElementById('custom_reason_container');
            const customReasonInput = document.getElementById('custom_reason');
            const fromDateInput = document.getElementById('start_date');
            const toDateInput = document.getElementById('end_date');
            const durationDisplay = document.getElementById('duration_display');
            const daysCount = document.getElementById('days_count');
            const emergencyContactContainer = document.getElementById('emergency_contact_container');
            const medicalCertificateContainer = document.getElementById('medical_certificate_container');
            const declarationCheckbox = document.getElementById('declaration');
            const leaveStatusAlert = document.getElementById('leave_status_alert');
            const leaveStatusMessage = document.getElementById('leave_status_message');
            const messageDiv = document.getElementById('message');

            // Define reason options for each leave type
            const reasonOptions = {
                'medical': `
            <optgroup label="Medical Reasons">
                <option value="fever_cold">Fever/Cold</option>
                <option value="stomach_issue">Stomach Issue</option>
                <option value="headache_migraine">Headache/Migraine</option>
                <option value="medical_checkup">Medical Checkup</option>
                <option value="dental_treatment">Dental Treatment</option>
                <option value="eye_checkup">Eye Checkup</option>
                <option value="vaccination">Vaccination</option>
                <option value="hospitalization">Hospitalization</option>
                <option value="accident">Accident/Injury</option>
                <option value="covid_symptoms">COVID-19 Symptoms</option>
                <option value="physical_therapy">Physical Therapy</option>
                <option value="mental_health">Mental Health Consultation</option>
            </optgroup>
            <optgroup label="Documentation Required">
                <option value="medical_certificate">Medical Certificate</option>
                <option value="prescription_followup">Prescription Follow-up</option>
                <option value="test_reports">Test Reports Collection</option>
            </optgroup>
        `,

                'emergency': `
            <optgroup label="Emergency Situations">
                <option value="family_emergency">Family Emergency</option>
                <option value="accident_emergency">Accident Emergency</option>
                <option value="home_emergency">Home Emergency</option>
                <option value="natural_disaster">Natural Disaster</option>
                <option value="financial_emergency">Financial Emergency</option>
                <option value="legal_emergency">Legal Emergency</option>
            </optgroup>
            <optgroup label="Urgent Requirements">
                <option value="urgent_document">Urgent Document Work</option>
                <option value="passport_visa">Passport/Visa Emergency</option>
                <option value="vehicle_breakdown">Vehicle Breakdown</option>
                <option value="property_emergency">Property Emergency</option>
            </optgroup>
        `,

                'casual': `
            <optgroup label="Personal Requirements">
                <option value="bank_work">Bank Work</option>
                <option value="document_work">Document Work</option>
                <option value="shopping">Essential Shopping</option>
                <option value="personal_meeting">Personal Meeting</option>
                <option value="vehicle_service">Vehicle Service/Repair</option>
                <option value="property_work">Property Related Work</option>
                <option value="marriage_function">Marriage Function</option>
                <option value="religious_function">Religious Function</option>
            </optgroup>
            <optgroup label="Personal Well-being">
                <option value="mental_break">Mental Break/Relaxation</option>
                <option value="family_time">Family Time</option>
                <option value="personal_development">Personal Development</option>
            </optgroup>
        `,

                'parental': `
            <optgroup label="Family Visits">
                <option value="parent_visit">Parent Visit</option>
                <option value="sibling_visit">Sibling Visit</option>
                <option value="family_reunion">Family Reunion</option>
                <option value="home_town_visit">Home Town Visit</option>
            </optgroup>
            <optgroup label="Family Events">
                <option value="family_marriage">Marriage in Family</option>
                <option value="family_function">Family Function</option>
                <option value="birth_anniversary">Birth/Anniversary</option>
                <option value="death_in_family">Death in Family</option>
                <option value="religious_ceremony">Religious Ceremony</option>
            </optgroup>
            <optgroup label="Family Support">
                <option value="family_support">Family Support Required</option>
                <option value="parent_health">Parent Health Issues</option>
                <option value="childcare">Childcare Requirements</option>
            </optgroup>
        `,

                'festival': `
            <optgroup label="Major Festivals">
                <option value="diwali">Diwali Celebration</option>
                <option value="holi">Holi Celebration</option>
                <option value="eid">Eid Celebration</option>
                <option value="christmas">Christmas Celebration</option>
                <option value="dussehra">Dussehra</option>
                <option value="navratri">Navratri</option>
                <option value="ganesh_chaturthi">Ganesh Chaturthi</option>
                <option value="rakhi">Raksha Bandhan</option>
            </optgroup>
            <optgroup label="Regional Festivals">
                <option value="pongal">Pongal</option>
                <option value="onam">Onam</option>
                <option value="bihu">Bihu</option>
                <option value="durga_puja">Durga Puja</option>
                <option value="regional_festival">Regional Festival</option>
            </optgroup>
            <optgroup label="Family Celebrations">
                <option value="birthday">Birthday Celebration</option>
                <option value="anniversary">Family Anniversary</option>
                <option value="engagement">Engagement Ceremony</option>
            </optgroup>
        `,

                'official': `
            <optgroup label="Academic Requirements">
                <option value="exam_preparation">Exam Preparation</option>
                <option value="project_work">Project Work</option>
                <option value="thesis_work">Thesis/Dissertation Work</option>
                <option value="research_work">Research Work</option>
                <option value="library_study">Library Study</option>
                <option value="group_study">Group Study Session</option>
            </optgroup>
            <optgroup label="College Activities">
                <option value="seminar">Seminar/Conference</option>
                <option value="workshop">Workshop/Training</option>
                <option value="college_event">College Event Participation</option>
                <option value="cultural_event">Cultural Event</option>
                <option value="sports_event">Sports Event</option>
                <option value="placement">Placement Activity</option>
            </optgroup>
            <optgroup label="Official Work">
                <option value="internship">Internship Related</option>
                <option value="industrial_visit">Industrial Visit</option>
                <option value="field_work">Field Work</option>
                <option value="department_work">Department Work</option>
            </optgroup>
        `,

                'semester_break': `
            <optgroup label="Break Activities">
                <option value="home_visit">Home Visit</option>
                <option value="vacation_travel">Vacation Travel</option>
                <option value="skill_development">Skill Development Course</option>
                <option value="internship_training">Internship/Training</option>
                <option value="competitive_exam">Competitive Exam Preparation</option>
            </optgroup>
            <optgroup label="Personal Development">
                <option value="language_course">Language Course</option>
                <option value="hobby_development">Hobby Development</option>
                <option value="sports_training">Sports Training</option>
                <option value="volunteer_work">Volunteer Work</option>
            </optgroup>
        `,

                'exam': `
            <optgroup label="Exam Preparation">
                <option value="final_exam_prep">Final Exam Preparation</option>
                <option value="midterm_prep">Mid-term Exam Preparation</option>
                <option value="competitive_exam">Competitive Exam Preparation</option>
                <option value="entrance_exam">Entrance Exam Preparation</option>
                <option value="revision_time">Revision Time</option>
            </optgroup>
            <optgroup label="Exam Related">
                <option value="exam_day">Exam Day</option>
                <option value="practical_exam">Practical Exam</option>
                <option value="viva_voce">Viva Voce/Oral Exam</option>
                <option value="project_evaluation">Project Evaluation</option>
                <option value="result_verification">Result Verification</option>
            </optgroup>
            <optgroup label="Post-Exam">
                <option value="answer_sheet">Answer Sheet Verification</option>
                <option value="reevaluation">Re-evaluation Process</option>
            </optgroup>
        `,

                'personal': `
            <optgroup label="Personal Commitments">
                <option value="banking_financial">Banking/Financial Work</option>
                <option value="government_office">Government Office Work</option>
                <option value="legal_work">Legal Work</option>
                <option value="medical_appointment">Medical Appointment</option>
                <option value="vehicle_related">Vehicle Related Work</option>
                <option value="property_related">Property Related Work</option>
                <option value="insurance_work">Insurance Work</option>
            </optgroup>
            <optgroup label="Personal Development">
                <option value="interview">Job Interview</option>
                <option value="training_course">Training Course</option>
                <option value="certification_exam">Certification Exam</option>
                <option value="skill_workshop">Skill Workshop</option>
                <option value="career_counseling">Career Counseling</option>
            </optgroup>
            <optgroup label="Social Commitments">
                <option value="friend_wedding">Friend's Wedding</option>
                <option value="social_function">Social Function</option>
                <option value="community_service">Community Service</option>
                <option value="religious_activity">Religious Activity</option>
            </optgroup>
        `,

                'other': `
            <optgroup label="Miscellaneous">
                <option value="other">Other (Specify in details)</option>
            </optgroup>
        `
            };

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            if (fromDateInput) fromDateInput.min = today;
            if (toDateInput) toDateInput.min = today;

            // Helper function to get display label for leave type
            function getTypeLabel(type) {
                const typeLabels = {
                    'medical': 'Medical',
                    'emergency': 'Emergency',
                    'casual': 'Casual',
                    'parental': 'Family',
                    'festival': 'Festival',
                    'official': 'Academic/Official',
                    'semester_break': 'Semester Break',
                    'exam': 'Exam',
                    'personal': 'Personal',
                    'other': 'Specific'
                };
                return typeLabels[type] || 'Appropriate';
            }

            // Track if custom reason is required
            let isCustomReasonRequired = false;

            // Handle leave type selection - filter reasons
            if (typeSelect && reasonSelect) {
                typeSelect.addEventListener('change', function() {
                    const type = this.value;

                    // Get appropriate options for the selected type
                    let optionsHTML = reasonOptions[type] || reasonOptions['other'];

                    // Always include "Other" option at the end
                    if (type !== 'other' && !optionsHTML.includes('other')) {
                        optionsHTML +=
                            `<optgroup label="Other"><option value="other">Other (Specify below)</option></optgroup>`;
                    }

                    // Update the select with filtered options
                    reasonSelect.innerHTML = `
                <option value="" selected disabled>Select ${getTypeLabel(type)} Reason</option>
                ${optionsHTML}
            `;

                    // Clear custom reason field if it exists
                    if (customReasonContainer && customReasonInput) {
                        customReasonContainer.style.display = 'none';
                        customReasonInput.value = '';
                        isCustomReasonRequired = false;
                    }

                    // Show/hide emergency contact for emergency leaves
                    if (type === 'emergency' && emergencyContactContainer) {
                        emergencyContactContainer.style.display = 'flex';
                        const emergencyContactInput = document.getElementById('emergency_contact');
                        if (emergencyContactInput) emergencyContactInput.required = true;
                    } else if (emergencyContactContainer) {
                        emergencyContactContainer.style.display = 'none';
                        const emergencyContactInput = document.getElementById('emergency_contact');
                        if (emergencyContactInput) emergencyContactInput.required = false;
                    }

                    // Show/hide medical certificate for medical leaves
                    if (type === 'medical' && medicalCertificateContainer) {
                        medicalCertificateContainer.style.display = 'flex';
                        const medicalCertificateInput = document.getElementById('medical_certificate');
                        if (medicalCertificateInput) medicalCertificateInput.required = true;
                    } else if (medicalCertificateContainer) {
                        medicalCertificateContainer.style.display = 'none';
                        const medicalCertificateInput = document.getElementById('medical_certificate');
                        if (medicalCertificateInput) medicalCertificateInput.required = false;
                    }
                });
            }

            // Handle reason selection for custom reason
            if (reasonSelect && customReasonContainer && customReasonInput) {
                reasonSelect.addEventListener('change', function() {
                    if (this.value === 'other') {
                        customReasonContainer.style.display = 'flex';
                        isCustomReasonRequired = true;
                        customReasonInput.focus();
                    } else {
                        customReasonContainer.style.display = 'none';
                        customReasonInput.value = '';
                        isCustomReasonRequired = false;
                    }
                });
            }

            // Calculate duration when dates change
            function calculateDuration() {
                if (fromDateInput && toDateInput && fromDateInput.value && toDateInput.value) {
                    const fromDate = new Date(fromDateInput.value);
                    const toDate = new Date(toDateInput.value);

                    if (toDate < fromDate) {
                        if (daysCount) daysCount.textContent = 'Invalid';
                        if (durationDisplay) durationDisplay.style.color = '#dc3545';
                        return;
                    }

                    const timeDiff = Math.abs(toDate - fromDate);
                    const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                    if (daysCount) daysCount.textContent = diffDays;
                    if (durationDisplay) durationDisplay.style.color = '#28a745';

                    // Check leave duration limits
                    if (leaveStatusAlert && leaveStatusMessage) {
                        if (diffDays > 7) {
                            leaveStatusAlert.style.display = 'block';
                            leaveStatusMessage.textContent = 'Leave exceeds 7 days. May require special approval.';
                            leaveStatusAlert.className = 'alert alert-warning';
                        } else if (diffDays > 3) {
                            leaveStatusAlert.style.display = 'block';
                            leaveStatusMessage.textContent = 'Leave requires HOD approval.';
                            leaveStatusAlert.className = 'alert alert-info';
                        } else {
                            leaveStatusAlert.style.display = 'none';
                        }
                    }
                }
            }

            // Date change events
            if (fromDateInput) {
                fromDateInput.addEventListener('change', function() {
                    if (toDateInput) {
                        toDateInput.min = this.value;
                        calculateDuration();
                    }
                });
            }

            if (toDateInput) {
                toDateInput.addEventListener('change', calculateDuration);
            }

            // Function to show field errors
            function showFieldErrors(errors) {
                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                for (const [fieldName, messages] of Object.entries(errors)) {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (!field) continue;

                    // Add error class
                    field.classList.add('is-invalid');

                    // Create error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = Array.isArray(messages) ? messages.join(', ') : messages;

                    // Insert after field
                    field.parentNode.insertBefore(errorDiv, field.nextSibling);
                }
            }

            // Clear errors on input
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(field => {
                    field.addEventListener('input', function() {
                        if (this.classList.contains('is-invalid')) {
                            this.classList.remove('is-invalid');
                            const errorDiv = this.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.remove();
                            }
                        }
                    });
                });
            }

            // Custom validation function
            function validateForm() {
                let isValid = true;

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                // Check required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        markFieldError(field, 'This field is required');
                        isValid = false;
                    }
                });

                // Check if reason is "other" and custom reason is filled
                if (reasonSelect && reasonSelect.value === 'other') {
                    if (!customReasonInput || !customReasonInput.value.trim()) {
                        if (customReasonInput) {
                            markFieldError(customReasonInput, 'Please specify your custom reason');
                        } else {
                            markFieldError(reasonSelect, 'Please provide details for "Other" reason');
                        }
                        isValid = false;
                    }
                }

                // Check date validation
                if (fromDateInput && toDateInput && fromDateInput.value && toDateInput.value) {
                    const fromDate = new Date(fromDateInput.value);
                    const toDate = new Date(toDateInput.value);

                    if (toDate < fromDate) {
                        markFieldError(toDateInput, 'End date cannot be before start date');
                        isValid = false;
                    }
                }

                // Check declaration
                if (declarationCheckbox && !declarationCheckbox.checked) {
                    markFieldError(declarationCheckbox, 'You must accept the declaration');
                    isValid = false;
                }

                return isValid;
            }

            // Helper function to mark field errors
            function markFieldError(field, message) {
                field.classList.add('is-invalid');

                let errorDiv = field.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    field.parentNode.insertBefore(errorDiv, field.nextSibling);
                }

                errorDiv.textContent = message;
            }

            // Form submission
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    // Clear previous messages
                    if (messageDiv) {
                        messageDiv.classList.add('d-none');
                        messageDiv.textContent = '';
                    }

                    // Validate form before submission
                    if (!validateForm()) {
                        // Show general error message
                        if (messageDiv) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.textContent = 'Please correct the errors below.';
                            messageDiv.classList.remove('d-none');
                        }
                        return;
                    }

                    // Additional validation for long leaves
                    if (fromDateInput && toDateInput && fromDateInput.value && toDateInput.value) {
                        const fromDate = new Date(fromDateInput.value);
                        const toDate = new Date(toDateInput.value);
                        const timeDiff = Math.abs(toDate - fromDate);
                        const diffDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;

                        if (diffDays > 30) {
                            if (!confirm(
                                    'Leave exceeds 30 days. Are you sure you want to apply for such a long leave?'
                                )) {
                                return;
                            }
                        }
                    }

                    // Prepare form data
                    const formData = new FormData(form);

                    // Add custom reason to form data if reason is "other"
                    if (reasonSelect && reasonSelect.value === 'other' && customReasonInput &&
                        customReasonInput.value.trim()) {
                        formData.set('reason', customReasonInput.value.trim());
                    }

                    // Add calculated duration
                    if (daysCount && daysCount.textContent !== 'Invalid') {
                        formData.append('duration', daysCount.textContent);
                    }

                    // Show loading state
                    const submitBtn = form.querySelector('.submitted');
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch('/api/resident/leaves', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Success
                            if (messageDiv) {
                                messageDiv.className = 'alert alert-success';
                                messageDiv.textContent = data.message ||
                                    'Leave request submitted successfully!';
                                messageDiv.classList.remove('d-none');
                            }

                            // Reset form
                            form.reset();

                            // Reset custom reason state
                            if (customReasonContainer) customReasonContainer.style.display = 'none';
                            if (customReasonInput) customReasonInput.value = '';
                            isCustomReasonRequired = false;

                            if (leaveStatusAlert) {
                                leaveStatusAlert.style.display = 'none';
                            }

                            // Trigger event for other components to update
                            document.dispatchEvent(new CustomEvent('leaveSubmitted'));

                            // Collapse the form after successful submission
                            const collapse = document.getElementById('leaveRequestCollapse');
                            if (collapse) {
                                const bsCollapse = new bootstrap.Collapse(collapse, {
                                    toggle: false
                                });
                                bsCollapse.hide();
                            }

                        } else {
                            // Error handling - handle backend validation errors
                            if (data.status === 'error' && data.errors) {
                                // Show field errors from backend
                                showFieldErrors(data.errors);

                                // Show general message
                                if (messageDiv) {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message ||
                                        'Please correct the errors below.';
                                    messageDiv.classList.remove('d-none');
                                }
                            } else {
                                // Other errors from backend
                                if (messageDiv) {
                                    messageDiv.className = 'alert alert-danger';
                                    messageDiv.textContent = data.message || data.error ||
                                        'Error submitting request.';
                                    messageDiv.classList.remove('d-none');
                                }
                            }
                        }

                    } catch (error) {
                        console.error('Unexpected error:', error);
                        if (messageDiv) {
                            messageDiv.className = 'alert alert-danger';
                            messageDiv.textContent = 'An unexpected error occurred. Please try again.';
                            messageDiv.classList.remove('d-none');
                        }
                    } finally {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }

            // Initialize tooltips
            const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (tooltips.length > 0) {
                tooltips.forEach(el => new bootstrap.Tooltip(el));
            }
        });
    </script>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Leave Request List</a></div>

                <div class="table-container">
                    <div class="overflow-auto">
                        <table class="status-table" cellspacing="0" cellpadding="8" width="100%"
                            id="leaveRequestList">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Reason</th>
                                    <th>Description</th>
                                    <th>Duration</th>
                                    <th>HOD Status</th>
                                    <th>Warden Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>1</td>
                                    <td>Medical</td>
                                    <td>2025-08-01</td>
                                    <td>2025-08-03</td>
                                    <td>3</td>
                                    <td>Fever and rest</td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><a class="view-btn">Cancel Request</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Personal</td>
                                    <td>2025-07-20</td>
                                    <td>2025-07-21</td>
                                    <td>2</td>
                                    <td>Family function</td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><a class="view-btn">Cancel Request</a></td>
                                </tr> --}}
                            </tbody>
                        </table>
                        <p id="no-requests" class="text-danger text-center mt-3" style="display: none;">No leave requests
                            found.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Modal for View Receipt -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-id-card me-2"></i>Gate Pass
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="receiptDetails" class="p-4"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    {{-- <button type="button" class="btn btn-outline-primary" id="downloadReceiptBtn">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Leave Modal -->
    <div class="modal fade" id="editLeaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Leave Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLeaveForm" enctype="multipart/form-data">
                        <div id="edit-form-message" class="alert d-none"></div>
                        <input type="hidden" id="edit_leave_id" name="id">

                        <div class="mb-3">
                            <label for="edit_type" class="form-label required">Leave Type</label>
                            <select class="form-select" id="edit_type" name="type" required>
                                <option value="">-- Select Type --</option>
                                <option value="casual">Casual Leave</option>
                                <option value="medical">Medical Leave</option>
                                <option value="emergency">Emergency Leave</option>
                                <option value="vacation">Vacation Leave</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_reason" class="form-label required">Reason</label>
                            <input type="text" class="form-control" id="edit_reason" name="reason" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_start_date" class="form-label required">Start Date</label>
                                <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_end_date" class="form-label required">End Date</label>
                                <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_attachment" class="form-label">Attachment</label>
                            <input type="file" class="form-control" id="edit_attachment" name="attachment">
                            <div class="form-text" id="current-attachment"></div>
                        </div>

                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle me-1"></i> Only pending requests can be edited</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteLeaveBtn">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <button type="button" class="btn btn-primary" id="updateLeaveBtn">
                        <i class="fas fa-save me-1"></i> Update Request
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    //
    <script>
        //     document.getElementById("leaveRequestForm")?.addEventListener("submit", function(event) {
        //         event.preventDefault();

        //         // const residentId = localStorage.getItem('auth-id');
        //         const form = this;

        //         const formData = new FormData(form); // Automatically includes the file input

        //         fetch(`/api/resident/leaves`, {
        //                 method: "POST",
        //                 headers: {
        //                     'Authorization': `Bearer ${localStorage.getItem('token')}`,
        //                     'Accept': 'application/json'
        //                 },
        //                 body: formData
        //             })
        //             .then(async response => {
        //                 const data = await response.json();
        //                 const messageDiv = document.getElementById("message");

        //                 if (response.ok) {
        //                     messageDiv.className = "alert alert-success";
        //                     messageDiv.textContent = data.message || "Leave request submitted successfully!";
        //                     messageDiv.classList.remove("d-none");
        //                     form.reset();
        //                 } else {
        //                     messageDiv.className = "alert alert-danger";
        //                     messageDiv.textContent = data.error || "Error submitting request.";
        //                     messageDiv.classList.remove("d-none");

        //                     if (data.messages) {
        //                         for (const field in data.messages) {
        //                             console.error(`${field}: ${data.messages[field].join(', ')}`);
        //                         }
        //                     }
        //                 }
        //             })
        //             .catch(error => {
        //                 console.error("Unexpected error:", error);
        //                 const messageDiv = document.getElementById("message");
        //                 messageDiv.className = "alert alert-danger";
        //                 messageDiv.textContent = "An unexpected error occurred.";
        //                 messageDiv.classList.remove("d-none");
        //             });
        //     });
        // 








        /**
         * Form Submission Handler with Error Management
         * Reusable for both create and edit operations
         */
        class FormSubmissionHandler {
            constructor(config = {}) {
                this.config = {
                    formId: 'leaveRequestForm',
                    messageContainerId: 'message',
                    apiEndpoint: '/api/resident/leaves',
                    method: 'POST',
                    tokenKey: 'token',
                    successRedirect: null,
                    resetOnSuccess: true,
                    showLoading: true,
                    ...config
                };

                this.form = document.getElementById(this.config.formId);
                this.messageContainer = document.getElementById(this.config.messageContainerId);
                this.isSubmitting = false;

                this.init();
            }

            init() {
                if (!this.form) {
                    console.error(`Form with ID "${this.config.formId}" not found`);
                    return;
                }

                this.form.addEventListener('submit', (e) => this.handleSubmit(e));
                this.setupDateValidation();
                this.setupFileValidation();
            }

            async handleSubmit(event) {
                event.preventDefault();

                if (this.isSubmitting) return;

                // Validate form
                if (!this.validateForm()) {
                    return;
                }

                this.isSubmitting = true;
                this.clearAllErrors();
                this.showMessage('Submitting your request...', 'info');

                try {
                    const formData = new FormData(this.form);

                    // Add any additional data
                    this.addAdditionalFormData(formData);

                    // Validate files if any
                    this.validateFiles(formData);

                    const response = await fetch(this.config.apiEndpoint, {
                        method: this.config.method,
                        headers: this.getHeaders(),
                        body: formData
                    });

                    await this.handleResponse(response);

                } catch (error) {
                    console.error('Form submission error:', error);
                    this.showMessage(
                        error.message || 'An unexpected error occurred. Please try again.',
                        'danger'
                    );
                } finally {
                    this.isSubmitting = false;
                }
            }

            getHeaders() {
                const headers = {
                    'Authorization': `Bearer ${localStorage.getItem(this.config.tokenKey)}`,
                    'Accept': 'application/json'
                };

                // Don't set Content-Type for FormData - browser sets it automatically with boundary
                return headers;
            }

            async handleResponse(response) {
                const data = await response.json();

                if (response.ok) {
                    this.handleSuccess(data);
                } else {
                    this.handleError(data);
                }
            }

            handleSuccess(data) {
                this.showMessage(
                    data.message || 'Operation completed successfully!',
                    'success',
                    true // auto-hide
                );

                if (this.config.resetOnSuccess) {
                    this.form.reset();
                    this.clearAllErrors();
                }

                // Dispatch success event
                document.dispatchEvent(new CustomEvent('formSubmissionSuccess', {
                    detail: {
                        data,
                        form: this.form
                    }
                }));

                // Redirect if configured
                if (this.config.successRedirect) {
                    setTimeout(() => {
                        window.location.href = this.config.successRedirect;
                    }, 2000);
                }

                // Hide form collapse if exists
                const collapseElement = this.form.closest('.collapse');
                if (collapseElement) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapseElement);
                    if (bsCollapse) {
                        setTimeout(() => bsCollapse.hide(), 1500);
                    }
                }
            }

            handleError(data) {
                let errorMessage = data.error || 'An error occurred. Please check the form and try again.';

                // Show field-specific errors if available
                if (data.messages && typeof data.messages === 'object') {
                    this.displayFieldErrors(data.messages);
                    errorMessage = 'Please correct the errors below.';
                }

                // Show validation errors if available
                if (data.errors && typeof data.errors === 'object') {
                    this.displayValidationErrors(data.errors);
                    errorMessage = 'Please correct the validation errors.';
                }

                this.showMessage(errorMessage, 'danger');

                // Dispatch error event
                document.dispatchEvent(new CustomEvent('formSubmissionError', {
                    detail: {
                        data,
                        form: this.form
                    }
                }));
            }

            validateForm() {
                let isValid = true;

                // Clear previous errors
                this.clearAllErrors();

                // Validate required fields
                const requiredFields = this.form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        this.markFieldError(field, 'This field is required');
                        isValid = false;
                    }
                });

                // Validate dates
                const fromDate = this.form.querySelector('[name="from_date"], [name="start_date"]');
                const toDate = this.form.querySelector('[name="to_date"], [name="end_date"]');

                if (fromDate && toDate && fromDate.value && toDate.value) {
                    const start = new Date(fromDate.value);
                    const end = new Date(toDate.value);

                    if (end < start) {
                        this.markFieldError(toDate, 'End date must be after start date');
                        isValid = false;
                    }

                    // Check minimum leave duration
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                    if (diffDays < 1) {
                        this.markFieldError(toDate, 'Minimum leave duration is 1 day');
                        isValid = false;
                    }
                }

                // Validate declaration checkbox
                const declarationCheckbox = this.form.querySelector('#declaration');
                if (declarationCheckbox && !declarationCheckbox.checked) {
                    this.markFieldError(declarationCheckbox, 'You must accept the declaration');
                    isValid = false;
                }

                return isValid;
            }

            markFieldError(field, message) {
                // Add error class to field
                field.classList.add('is-invalid');

                // Find or create error message container
                let errorContainer = field.nextElementSibling;

                if (!errorContainer || !errorContainer.classList.contains('invalid-feedback')) {
                    errorContainer = document.createElement('div');
                    errorContainer.className = 'invalid-feedback';

                    // Insert after field
                    field.parentNode.insertBefore(errorContainer, field.nextSibling);
                }

                errorContainer.textContent = message;

                // Scroll to first error
                if (!this.firstErrorField) {
                    this.firstErrorField = field;
                    setTimeout(() => {
                        field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 100);
                }
            }

            clearFieldError(field) {
                field.classList.remove('is-invalid');

                const errorContainer = field.nextElementSibling;
                if (errorContainer && errorContainer.classList.contains('invalid-feedback')) {
                    errorContainer.textContent = '';
                }
            }

            clearAllErrors() {
                // Clear all invalid states
                this.form.querySelectorAll('.is-invalid').forEach(field => {
                    field.classList.remove('is-invalid');
                });

                // Clear all error messages
                this.form.querySelectorAll('.invalid-feedback').forEach(container => {
                    container.textContent = '';
                });

                this.firstErrorField = null;
            }

            displayFieldErrors(messages) {
                for (const [fieldName, errors] of Object.entries(messages)) {
                    const field = this.form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        const errorMessage = Array.isArray(errors) ? errors.join(', ') : errors;
                        this.markFieldError(field, errorMessage);
                    }
                }
            }

            displayValidationErrors(errors) {
                for (const [fieldName, errorList] of Object.entries(errors)) {
                    const field = this.form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        const errorMessage = Array.isArray(errorList) ? errorList.join(', ') : errorList;
                        this.markFieldError(field, errorMessage);
                    }
                }
            }

            showMessage(message, type = 'info', autoHide = false) {
                if (!this.messageContainer) return;

                this.messageContainer.className = `alert alert-${type} alert-dismissible fade show`;
                this.messageContainer.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas ${this.getMessageIcon(type)} me-2"></i>
                <div class="flex-grow-1">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
                this.messageContainer.classList.remove('d-none');

                if (autoHide && type === 'success') {
                    setTimeout(() => {
                        if (this.messageContainer) {
                            this.messageContainer.classList.add('d-none');
                        }
                    }, 5000);
                }
            }

            getMessageIcon(type) {
                const icons = {
                    'success': 'fa-check-circle',
                    'danger': 'fa-exclamation-circle',
                    'warning': 'fa-exclamation-triangle',
                    'info': 'fa-info-circle'
                };
                return icons[type] || 'fa-info-circle';
            }

            setupDateValidation() {
                const today = new Date().toISOString().split('T')[0];

                // Set minimum dates for all date inputs
                this.form.querySelectorAll('input[type="date"]').forEach(input => {
                    input.min = today;
                });

                // Link start and end dates
                const startDateInput = this.form.querySelector('[name="from_date"], [name="start_date"]');
                const endDateInput = this.form.querySelector('[name="to_date"], [name="end_date"]');

                if (startDateInput && endDateInput) {
                    startDateInput.addEventListener('change', () => {
                        endDateInput.min = startDateInput.value;
                        if (endDateInput.value && new Date(endDateInput.value) < new Date(startDateInput
                                .value)) {
                            endDateInput.value = startDateInput.value;
                        }
                        this.clearFieldError(endDateInput);
                    });

                    endDateInput.addEventListener('change', () => {
                        this.clearFieldError(endDateInput);
                    });
                }
            }

            setupFileValidation() {
                const fileInputs = this.form.querySelectorAll('input[type="file"]');

                fileInputs.forEach(input => {
                    input.addEventListener('change', () => {
                        this.clearFieldError(input);

                        if (input.files.length > 0) {
                            const file = input.files[0];
                            const maxSize = 5 * 1024 * 1024; // 5MB
                            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg',
                                'application/pdf'
                            ];

                            if (file.size > maxSize) {
                                this.markFieldError(input, 'File size must be less than 5MB');
                                input.value = '';
                            } else if (!allowedTypes.includes(file.type) && !file.name.match(
                                    /\.(jpg|jpeg|png|pdf)$/i)) {
                                this.markFieldError(input, 'Only JPG, PNG, and PDF files are allowed');
                                input.value = '';
                            }
                        }
                    });
                });
            }

            validateFiles(formData) {
                const files = formData.getAll('attachment') || [];
                files.push(...(formData.getAll('photo') || []));
                files.push(...(formData.getAll('medical_certificate') || []));

                files.forEach(file => {
                    if (file.size > 5 * 1024 * 1024) {
                        throw new Error('File size exceeds 5MB limit');
                    }

                    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.pdf'];
                    const fileName = file.name.toLowerCase();
                    const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                    // if (!isValidExtension) {
                    //     throw new Error('Invalid file type. Only JPG, PNG, and PDF files are allowed');
                    // }
                });
            }

            addAdditionalFormData(formData) {
                // Add duration if not already in form
                const fromDate = this.form.querySelector('[name="from_date"], [name="start_date"]');
                const toDate = this.form.querySelector('[name="to_date"], [name="end_date"]');

                if (fromDate && toDate && fromDate.value && toDate.value) {
                    const start = new Date(fromDate.value);
                    const end = new Date(toDate.value);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

                    if (!formData.has('duration')) {
                        formData.append('duration', diffDays.toString());
                    }
                }

                // Add user ID from localStorage
                const userId = localStorage.getItem('auth-id');
                if (userId && !formData.has('resident_id')) {
                    formData.append('resident_id', userId);
                }
            }

            setLoading(isLoading) {
                const submitButton = this.form.querySelector('button[type="submit"]');
                if (!submitButton) return;

                if (isLoading) {
                    submitButton.disabled = true;
                    const originalText = submitButton.innerHTML;
                    submitButton.dataset.originalContent = originalText;
                    submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Processing...
            `;
                } else {
                    submitButton.disabled = false;
                    if (submitButton.dataset.originalContent) {
                        submitButton.innerHTML = submitButton.dataset.originalContent;
                    }
                }
            }

            // Public method to reset form and errors
            resetForm() {
                this.form.reset();
                this.clearAllErrors();
                if (this.messageContainer) {
                    this.messageContainer.classList.add('d-none');
                }
            }

            // Public method to populate form with data (for edit)
            populateForm(data) {
                if (!data || typeof data !== 'object') return;

                Object.entries(data).forEach(([key, value]) => {
                    const field = this.form.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'checkbox' || field.type === 'radio') {
                            field.checked = value;
                        } else if (field.type === 'file') {
                            // File inputs can't be set programmatically for security
                        } else {
                            field.value = value || '';
                        }
                    }
                });

                this.clearAllErrors();
            }
        }

        // Usage for leave request form
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form handler for submission
            const leaveFormHandler = new FormSubmissionHandler({
                formId: 'leaveRequestForm',
                messageContainerId: 'message',
                apiEndpoint: '/api/resident/leaves',
                method: 'POST',
                resetOnSuccess: true
            });

            // Optional: Pre-fetch any data needed
            // initializeLeaveForm();
        });

        // Reusable for edit form
        class EditFormHandler extends FormSubmissionHandler {
            constructor(leaveId, config = {}) {
                super({
                    formId: 'editLeaveForm',
                    messageContainerId: 'edit-form-message',
                    apiEndpoint: `/api/resident/leaves/${leaveId}`,
                    method: 'PUT',
                    resetOnSuccess: false,
                    ...config
                });

                this.leaveId = leaveId;
            }

            async loadLeaveData() {
                try {
                    const response = await fetch(`/api/resident/leaves/${this.leaveId}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem('token')}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.populateForm(data.data);
                        return data.data;
                    } else {
                        throw new Error('Failed to load leave data');
                    }
                } catch (error) {
                    console.error('Error loading leave data:', error);
                    throw error;
                }
            }

            // Override handleSuccess for edit specific behavior
            handleSuccess(data) {
                super.handleSuccess(data);

                // Dispatch edit specific event
                document.dispatchEvent(new CustomEvent('leaveUpdated', {
                    detail: {
                        data,
                        leaveId: this.leaveId
                    }
                }));
            }
        }

        // Example usage for edit modal
        function openEditLeaveModal(leaveId) {
            const editHandler = new EditFormHandler(leaveId);

            editHandler.loadLeaveData()
                .then(data => {
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editLeaveModal'));
                    modal.show();

                    // Setup delete button
                    document.getElementById('deleteLeaveBtn').onclick = () => {
                        if (confirm('Are you sure you want to delete this leave request?')) {
                            deleteLeave(leaveId);
                        }
                    };
                })
                .catch(error => {
                    alert('Failed to load leave data: ' + error.message);
                });
        }

        // Delete function
        async function deleteLeave(leaveId) {
            if (!confirm('Are you sure you want to delete this leave request? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/api/resident/leaves/${leaveId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();

                    // Show success message
                    const handler = new FormSubmissionHandler();
                    handler.showMessage(data.message || 'Leave deleted successfully', 'success', true);

                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editLeaveModal'));
                    modal.hide();

                    // Dispatch delete event
                    document.dispatchEvent(new CustomEvent('leaveDeleted', {
                        detail: {
                            leaveId
                        }
                    }));

                } else {
                    throw new Error('Failed to delete leave');
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Error deleting leave: ' + error.message);
            }
        }
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#leaveRequestList tbody");
            const noRequestsMsg = document.getElementById("no-requests");

            const apiUrl = `/api/resident/leaves`;

            fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {

                    // ✅ Update Summary Cards
                    if (data.data.summary) {
                        document.getElementById("total-requests").innerText = data.data.summary
                            .total_leaves;
                        document.getElementById("total-leaves-taken").innerText = data.data.summary
                            .approved;
                        document.getElementById("total-leaves-pending").innerText = data.data.summary
                            .pending;
                        document.getElementById("total-leaves-rejected").innerText = data.data.summary
                            .rejected;
                    }

                    tableBody.innerHTML = "";

                    if (data.data.requests && data.data.requests.length > 0) {
                        data.data.requests.forEach((request, index) => {

                            tableBody.innerHTML += `
                                <tr data-request='${JSON.stringify(request).replace(/'/g, "&apos;")}'>
                                    <td>${index + 1}</td>
                                    <td>${request.name}</td>
                                    <td>${request.type}</td>
                                   <td>${truncateWithExpand(request.reason ?? 'N/A', 40)}</td>
                                    <td>${truncateWithExpand(request.description ?? 'N/A', 40)}</td>

                                    <td>
                                        Start: ${request.start_date ?? 'N/A'}<br>
                                        End: ${request.end_date ?? 'N/A'}<br>
                                        Applied: ${request.applied_at ?? 'N/A'}
                                    </td>
                                    <td>
                                    ${ request.hod_status !== 'pending' ? `${request.hod_remarks ?? 'N/A'} <br> ${request.hod_action_at ?? 'N/A'}` : `${getStatusBadge(request.hod_status)}` }
                                    </td>
                                    <td>
                                        ${ request.admin_status === 'rejected' ? `${getStatusBadge(request.admin_status)} <br> ${request.admin_remarks ?? 'N/A'} <br> ${request.admin_action_at ?? 'N/A'}` : `${getStatusBadge(request.admin_status)}` }
                                        </td>
                                    <!-- <td data-id="${request.id}"><button class="btn btn-primary" onclick="viewReceipt(this)">View Receipt</button></td> -->
                                     <td>
                                    ${request.admin_status === 'pending' ? `
                                    <button type="button" class="btn btn-outline-primary view-receipt-btn" 
                                data-id="${request.id}" data-bs-toggle="tooltip" title="View Gate Pass">
                            <i class="fas fa-eye"></i>
                        </button>
                            <button type="button" class="btn btn-outline-warning edit-leave-btn" 
                                    data-id="${request.id}" data-bs-toggle="tooltip" title="Edit Request">
                                <i class="fas fa-edit"></i>
                            </button>
                        ` : ''}

                                    <button 
                                        class="btn btn-primary" 
                                        data-id="${request.id}" 
                                        onclick="viewReceipt(this)">
                                        View Receipt
                                    </button>
                                </td>

                                    
                                </tr>
                            `;
                        });
                    } else {
                        noRequestsMsg.style.display = "block";
                    }

                })
                .catch(error => {
                    console.error("Error fetching leave requests:", error);
                    tableBody.innerHTML =
                        `<tr><td colspan="8" class="text-center text-danger">Failed to load leave requests.</td></tr>`;
                });
        });



        // function viewReceipt(button) {
        //     // Store original button content for restoration
        //     const originalButtonContent = button.innerHTML;
        //     const originalButtonClass = button.className;

        //     // Show loading state on button
        //     button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
        //     button.disabled = true;
        //     button.className = originalButtonClass.replace('btn-primary', 'btn-secondary');

        //     const leaveId = button.getAttribute("data-id");
        //     const apiUrl = `/api/resident/leaves/${leaveId}`;

        //     // Show a subtle loading overlay in the modal container
        //     const receiptContainer = document.getElementById('receiptDetails');
        //     if (receiptContainer) {
        //         receiptContainer.innerHTML = `
    //             <div class="text-center py-5 receipt-loading">
    //                 <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
    //                     <span class="visually-hidden">Loading gate pass...</span>
    //                 </div>
    //                 <p class="text-muted">Loading gate pass details...</p>
    //             </div>
    //         `;
        //     }

        //     // Show modal immediately to give feedback
        //     const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        //     receiptModal.show();

        //     // Add CSS for animations
        //     const style = document.createElement('style');
        //     style.textContent = `
    //             .receipt-loading {
    //                 animation: fadeIn 0.3s ease;
    //             }
    //             @keyframes fadeIn {
    //                 from { opacity: 0; transform: translateY(-10px); }
    //                 to { opacity: 1; transform: translateY(0); }
    //             }
    //             .receipt-card {
    //                 animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    //             }
    //             @keyframes slideUp {
    //                 from { opacity: 0; transform: translateY(20px); }
    //                 to { opacity: 1; transform: translateY(0); }
    //             }
    //             .info-item {
    //                 transition: background-color 0.2s ease;
    //                 padding: 4px 8px;
    //                 border-radius: 4px;
    //             }
    //             .info-item:hover {
    //                 background-color: rgba(13, 110, 253, 0.05);
    //             }
    //             .status-badge {
    //                 transition: transform 0.2s ease, box-shadow 0.2s ease;
    //             }
    //             .status-badge:hover {
    //                 transform: translateY(-1px);
    //                 box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    //             }
    //         `;
        //     document.head.appendChild(style);

        //     fetch(apiUrl, {
        //             method: "GET",
        //             headers: {
        //                 'Authorization': `Bearer ${localStorage.getItem('token')}`,
        //                 'Accept': 'application/json'
        //             },
        //         })
        //         .then(async response => {
        //             if (!response.ok) {
        //                 throw new Error(`HTTP ${response.status}`);
        //             }
        //             return response.json();
        //         })
        //         .then(response => {
        //             const data = response.data;

        //             // Format dates for better UX
        //             const formatDate = (dateStr) => {
        //                 if (!dateStr) return 'N/A';
        //                 const date = new Date(dateStr);
        //                 return date.toLocaleDateString('en-IN', {
        //                     day: 'numeric',
        //                     month: 'short',
        //                     year: 'numeric'
        //                 });
        //             };

        //             const formatDateTime = (dateStr) => {
        //                 if (!dateStr) return 'N/A';
        //                 const date = new Date(dateStr);
        //                 return date.toLocaleString('en-IN', {
        //                     day: 'numeric',
        //                     month: 'short',
        //                     year: 'numeric',
        //                     hour: '2-digit',
        //                     minute: '2-digit'
        //                 });
        //             };

        //             // Create status badge with icons
        //             const getStatusBadge = (status) => {
        //                 if (!status) return '<span class="badge bg-secondary">N/A</span>';

        //                 const statusMap = {
        //                     'pending': {
        //                         class: 'bg-warning text-dark',
        //                         icon: 'clock',
        //                         label: 'Pending'
        //                     },
        //                     'approved': {
        //                         class: 'bg-success',
        //                         icon: 'check-circle',
        //                         label: 'Approved'
        //                     },
        //                     'rejected': {
        //                         class: 'bg-danger',
        //                         icon: 'times-circle',
        //                         label: 'Rejected'
        //                     }
        //                 };

        //                 const statusInfo = statusMap[status.toLowerCase()] || {
        //                     class: 'bg-info',
        //                     icon: 'info-circle',
        //                     label: status
        //                 };

        //                 return `
    //                     <span class="badge ${statusInfo.class} status-badge d-inline-flex align-items-center gap-1">
    //                         <i class="fas fa-${statusInfo.icon}"></i>
    //                         ${statusInfo.label}
    //                             </span>
    //                     `;
        //             };

        //             // Build enhanced gate pass layout
        //             const receiptDetails = `
    //                     <div class="receipt-card">
    //                         <!-- Header with logo and title -->
    //                         <div class="receipt-header text-center mb-4">
    //                             <div class="institute-header mb-3">
    //                                 <!-- <img src="/images/logo.png" alt="Institute Logo" class="img-fluid mb-2" style="max-height: 70px;"> -->
    //                                 <h2 class="text-primary fw-bold mb-1">Student Gate Pass</h2>
    //                                 <p class="text-muted small mb-0">Pass ID: <span class="fw-semibold">#${data.id || 'N/A'}</span></p>
    //                             </div>
    //                             <div class="border-top border-bottom py-2">
    //                                 <div class="row">
    //                                     <div class="col">
    //                                         <small class="text-muted"><i class="fas fa-calendar me-1"></i>Generated: ${new Date().toLocaleString()}</small>
    //                                     </div>
    //                                     <div class="col">
    //                                         <small class="text-muted"><i class="fas fa-user me-1"></i>Valid for: ${data.name || 'Student'}</small>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Main content -->
    //                         <div class="row g-4">
    //                             <!-- Student Information -->
    //                             <div class="col-lg-8">
    //                                 <div class="card border-0 shadow-sm h-100">
    //                                     <div class="card-header bg-primary bg-opacity-10 border-primary border-start-0 border-end-0 border-top-0 border-3">
    //                                         <h5 class="mb-0 text-primary">
    //                                             <i class="fas fa-user-graduate me-2"></i>Student Details
    //                                         </h5>
    //                                     </div>
    //                                     <div class="card-body">
    //                                         <div class="row g-3">
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Student Name</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-user text-primary"></i>
    //                                                     <span>${data.name || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Room Number</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-bed text-primary"></i>
    //                                                     <span>${data.room_number || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Email</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-envelope text-primary"></i>
    //                                                     <span>${data.email || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Mobile</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-phone text-primary"></i>
    //                                                     <span>${data.mobile || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Course</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-graduation-cap text-primary"></i>
    //                                                     <span>${data.course || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Department</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-building text-primary"></i>
    //                                                     <span>${data.department || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>

    //                             <!-- QR Code Section -->
    //                             <div class="col-lg-4">
    //                                 <div class="card border-0 shadow-sm h-100">
    //                                     <div class="card-header bg-success bg-opacity-10 border-success border-start-0 border-end-0 border-top-0 border-3">
    //                                         <h5 class="mb-0 text-success">
    //                                             <i class="fas fa-qrcode me-2"></i>Verification
    //                                         </h5>
    //                                     </div>
    //                                     <div class="card-body d-flex flex-column align-items-center justify-content-center">
    //                                         <div id="qrcode" class="mb-3">
    //                                             ${data.qr_code ? 
    //                                                 `<img src="data:image/png;base64,${data.qr_code}" 
        //                                                         class="img-fluid border rounded p-2 shadow-sm" 
        //                                                         style="max-width: 200px;"
        //                                                         alt="Gate Pass QR Code">` 
    //                                                 : '<div class="text-center text-muted"><i class="fas fa-qrcode fa-3x mb-2"></i><p>QR Code Not Available</p></div>'
    //                                             }
    //                                         </div>

    //                                         ${data.token ? `
        //                                                 <div class="verification-token text-center mb-3">
        //                                                     <div class="alert alert-light border">
        //                                                         <h6 class="mb-2"><i class="fas fa-shield-alt me-1"></i>Verification Token</h6>
        //                                                         <code class="bg-dark text-white p-2 rounded d-block">${data.token}</code>
        //                                                         <small class="text-muted mt-1 d-block">Use this token for manual verification</small>
        //                                                     </div>
        //                                                 </div>
        //                                             ` : ''}

    //                                         <div class="status-section text-center">
    //                                             <h6 class="text-muted mb-2">Current Status</h6>
    //                                             <div class="d-flex flex-wrap gap-2 justify-content-center">
    //                                                 ${getStatusBadge(data.status || data.admin_status || 'pending')}
    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Leave Details -->
    //                         <div class="card border-0 shadow-sm mt-4">
    //                             <div class="card-header bg-info bg-opacity-10 border-info border-start-0 border-end-0 border-top-0 border-3">
    //                                 <h5 class="mb-0 text-info">
    //                                     <i class="fas fa-calendar-alt me-2"></i>Leave Details
    //                                 </h5>
    //                             </div>
    //                             <div class="card-body">
    //                                 <div class="row g-3">
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Leave Type</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-tag text-info"></i>
    //                                             <span class="text-capitalize">${data.type || 'N/A'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Purpose</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-bullseye text-info"></i>
    //                                             <span>${data.reason || 'N/A'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-12 info-item">
    //                                         <label class="form-label text-muted small mb-1">Description</label>
    //                                         <div class="fw-semibold">
    //                                             <i class="fas fa-align-left text-info me-2"></i>
    //                                             <span>${data.description || 'No additional details provided'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">From Date</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-calendar-plus text-info"></i>
    //                                             <span>${formatDate(data.from_date || data.start_date)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">To Date</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-calendar-minus text-info"></i>
    //                                             <span>${formatDate(data.to_date || data.end_date)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Applied On</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-paper-plane text-info"></i>
    //                                             <span>${formatDateTime(data.applied_on || data.applied_at)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Duration</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-clock text-info"></i>
    //                                             <span>
    //                                                 ${(() => {
    //                                                     const start = new Date(data.start_date || data.from_date);
    //                                                     const end = new Date(data.end_date || data.to_date);
    //                                                     if (!start || !end) return 'N/A';
    //                                                     const diffTime = Math.abs(end - start);
    //                                                     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    //                                                     return `${diffDays + 1} day${diffDays > 0 ? 's' : ''}`;
    //                                                 })()}
    //                                             </span>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Approval Timeline -->
    //                         ${data.hod_status || data.admin_status ? `
        //                                 <div class="card border-0 shadow-sm mt-4">
        //                                     <div class="card-header bg-warning bg-opacity-10 border-warning border-start-0 border-end-0 border-top-0 border-3">
        //                                         <h5 class="mb-0 text-warning">
        //                                             <i class="fas fa-history me-2"></i>Approval Timeline
        //                                         </h5>
        //                                     </div>
        //                                     <div class="card-body">
        //                                         <div class="timeline">
        //                                             <!-- Applied -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-paper-plane ${data.applied_on ? 'text-success' : 'text-secondary'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">Applied</h6>
        //                                                     <div class="text-muted small mb-1">${formatDateTime(data.applied_on || data.applied_at)}</div>
        //                                                 </div>
        //                                             </div>

        //                                             <!-- HOD Review -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-user-tie ${data.hod_status === 'approved' ? 'text-success' : 
        //                                                                             data.hod_status === 'rejected' ? 'text-danger' : 
        //                                                                             'text-warning'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">HOD Review</h6>
        //                                                     <div class="d-flex align-items-center gap-2 mb-1">
        //                                                         ${getStatusBadge(data.hod_status || 'pending')}
        //                                                         ${data.hod_action_at ? `<small class="text-muted">${formatDateTime(data.hod_action_at)}</small>` : ''}
        //                                                     </div>
        //                                                     ${data.hod_remarks ? `<p class="small mb-0 text-muted"><i class="fas fa-comment me-1"></i>${data.hod_remarks}</p>` : ''}
        //                                                 </div>
        //                                             </div>

        //                                             <!-- Admin Review -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-user-cog ${data.admin_status === 'approved' ? 'text-success' : 
        //                                                                             data.admin_status === 'rejected' ? 'text-danger' : 
        //                                                                             'text-warning'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">Admin Review</h6>
        //                                                     <div class="d-flex align-items-center gap-2 mb-1">
        //                                                         ${getStatusBadge(data.admin_status || 'pending')}
        //                                                         ${data.admin_action_at ? `<small class="text-muted">${formatDateTime(data.admin_action_at)}</small>` : ''}
        //                                                     </div>
        //                                                     ${data.admin_remarks ? `<p class="small mb-0 text-muted"><i class="fas fa-comment me-1"></i>${data.admin_remarks}</p>` : ''}
        //                                                 </div>
        //                                             </div>
        //                                         </div>
        //                                     </div>
        //                                 </div>
        //                             ` : ''}

    //                         <!-- Footer -->
    //                         <div class="receipt-footer text-center mt-4 pt-3 border-top">
    //                             <div class="row">
    //                                 <div class="col-md-6">
    //                                     <small class="text-muted">
    //                                         <i class="fas fa-clock me-1"></i>
    //                                         Hostel Timings: 
    //                                         <span class="fw-semibold">IN ${data.hostel_in_time || 'N/A'}</span> | 
    //                                         <span class="fw-semibold">OUT ${data.hostel_out_time || 'N/A'}</span>
    //                                     </small>
    //                                 </div>
    //                                 <div class="col-md-6">
    //                                     <small class="text-muted">
    //                                         <i class="fas fa-info-circle me-1"></i>
    //                                         This gate pass is electronically generated and valid only with QR code verification
    //                                     </small>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 `;

        //             // Add CSS for timeline
        //             const timelineStyle = document.createElement('style');
        //             timelineStyle.textContent = `
    //                     .timeline {
    //                         position: relative;
    //                         padding-left: 40px;
    //                     }
    //                     .timeline-step {
    //                         position: relative;
    //                         margin-bottom: 25px;
    //                         padding-bottom: 10px;
    //                     }
    //                     .timeline-step:last-child {
    //                         margin-bottom: 0;
    //                         padding-bottom: 0;
    //                     }
    //                     .timeline-step::before {
    //                         content: '';
    //                         position: absolute;
    //                         left: -30px;
    //                         top: 5px;
    //                         bottom: -10px;
    //                         width: 2px;
    //                         background: linear-gradient(to bottom, #dee2e6, transparent);
    //                     }
    //                     .timeline-step:last-child::before {
    //                         background: #dee2e6;
    //                         height: 15px;
    //                     }
    //                     .timeline-marker {
    //                         position: absolute;
    //                         left: -40px;
    //                         top: 0;
    //                         width: 20px;
    //                         height: 20px;
    //                         display: flex;
    //                         align-items: center;
    //                         justify-content: center;
    //                         background: white;
    //                         border-radius: 50%;
    //                         border: 2px solid #dee2e6;
    //                     }
    //                     .timeline-marker i {
    //                         font-size: 0.8rem;
    //                     }
    //                     .timeline-content {
    //                         background: white;
    //                         padding: 10px 15px;
    //                         border-radius: 8px;
    //                         border-left: 3px solid #0d6efd;
    //                         box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    //                     }
    //                 `;
        //             document.head.appendChild(timelineStyle);

        //             // Update the modal content
        //             document.getElementById('receiptDetails').innerHTML = receiptDetails;

        //             // Add copy functionality for token
        //             if (data.token) {
        //                 const tokenElement = document.querySelector('.verification-token code');
        //                 if (tokenElement) {
        //                     tokenElement.style.cursor = 'pointer';
        //                     tokenElement.title = 'Click to copy token';
        //                     tokenElement.addEventListener('click', function() {
        //                         navigator.clipboard.writeText(this.textContent).then(() => {
        //                             const originalText = this.textContent;
        //                             this.textContent = 'Copied!';
        //                             this.classList.add('bg-success');
        //                             setTimeout(() => {
        //                                 this.textContent = originalText;
        //                                 this.classList.remove('bg-success');
        //                             }, 2000);
        //                         });
        //                     });
        //                 }
        //             }

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;
        //             button.className = originalButtonClass;

        //             // Add print event listener
        //             document.getElementById('printReceiptBtn').addEventListener('click', function() {
        //                 printGatePass(data);
        //             });

        //         })
        //         .catch(error => {
        //             console.error("Error fetching leave:", error);

        //             // Show error state in modal
        //             document.getElementById('receiptDetails').innerHTML = `
    //                 <div class="text-center py-5">
    //                     <div class="error-icon mb-3">
    //                         <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
    //                     </div>
    //                     <h4 class="text-danger mb-3">Unable to Load Gate Pass</h4>
    //                     <p class="text-muted mb-4">${error.message || 'There was an error loading the gate pass details. Please try again.'}</p>
    //                     <div class="d-flex justify-content-center gap-2">
    //                         <button class="btn btn-outline-primary" onclick="viewReceipt(this)" data-id="${leaveId}">
    //                             <i class="fas fa-redo me-1"></i> Retry
    //                         </button>
    //                         <button class="btn btn-secondary" data-bs-dismiss="modal">
    //                             <i class="fas fa-times me-1"></i> Close
    //                         </button>
    //                     </div>
    //                 </div>
    //             `;

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;
        //             button.className = originalButtonClass;
        //         });
        // }

        // // Enhanced print function
        // function printGatePass(data) {
        //     const printBtn = document.getElementById('printReceiptBtn');
        //     const originalContent = printBtn.innerHTML;

        //     // Show loading on print button
        //     printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Preparing...';
        //     printBtn.disabled = true;

        //     // Use timeout to ensure DOM is ready
        //     setTimeout(() => {
        //         const receiptContent = document.querySelector('#receiptDetails .receipt-card');
        //         if (!receiptContent) return;

        //         const printWindow = window.open('', '_blank', 'width=900,height=800');
        //         const now = new Date();
        //         const docTitle =
        //             `GatePass_${data.name || 'Student'}_${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

        //         printWindow.document.write(`
    //     <!DOCTYPE html>
    //     <html>
    //     <head>
    //         <title>${docTitle}</title>
    //         <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    //         <style>
    //             @media print {
    //                 @page { margin: 20px; }
    //                 body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    //                 .print-header { 
    //                     text-align: center; 
    //                     margin-bottom: 30px;
    //                     border-bottom: 2px solid #333;
    //                     padding-bottom: 20px;
    //                 }
    //                 .print-header h1 { 
    //                     color: #2c3e50; 
    //                     margin: 10px 0 5px 0;
    //                     font-size: 28px;
    //                 }
    //                 .print-header .subtitle { 
    //                     color: #7f8c8d; 
    //                     font-size: 14px;
    //                 }
    //                 .section-card {
    //                     border: 1px solid #ddd;
    //                     border-radius: 8px;
    //                     padding: 20px;
    //                     margin-bottom: 20px;
    //                     break-inside: avoid;
    //                 }
    //                 .section-title {
    //                     color: #3498db;
    //                     border-bottom: 2px solid #3498db;
    //                     padding-bottom: 8px;
    //                     margin-bottom: 15px;
    //                     font-size: 18px;
    //                 }
    //                 .info-grid {
    //                     display: grid;
    //                     grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    //                     gap: 15px;
    //                     margin-bottom: 20px;
    //                 }
    //                 .info-item {
    //                     margin-bottom: 12px;
    //                 }
    //                 .info-label {
    //                     font-weight: 600;
    //                     color: #7f8c8d;
    //                     font-size: 13px;
    //                     margin-bottom: 3px;
    //                 }
    //                 .info-value {
    //                     font-weight: 500;
    //                     color: #2c3e50;
    //                     font-size: 14px;
    //                 }
    //                 .qr-section {
    //                     text-align: center;
    //                     padding: 20px;
    //                     border: 1px dashed #ddd;
    //                     border-radius: 8px;
    //                     margin: 20px 0;
    //                 }
    //                 .qr-section img {
    //                     max-width: 150px;
    //                     height: auto;
    //                 }
    //                 .status-badge {
    //                     display: inline-block;
    //                     padding: 4px 12px;
    //                     border-radius: 20px;
    //                     font-size: 12px;
    //                     font-weight: 600;
    //                     margin: 2px;
    //                 }
    //                 .badge-approved { background: #d4edda; color: #155724; }
    //                 .badge-pending { background: #fff3cd; color: #856404; }
    //                 .badge-rejected { background: #f8d7da; color: #721c24; }
    //                 .timeline-print {
    //                     margin: 20px 0;
    //                     padding-left: 20px;
    //                     border-left: 2px solid #3498db;
    //                 }
    //                 .timeline-item {
    //                     margin-bottom: 15px;
    //                     position: relative;
    //                 }
    //                 .timeline-item::before {
    //                     content: '';
    //                     position: absolute;
    //                     left: -25px;
    //                     top: 5px;
    //                     width: 10px;
    //                     height: 10px;
    //                     border-radius: 50%;
    //                     background: #3498db;
    //                 }
    //                 .footer {
    //                     text-align: center;
    //                     margin-top: 40px;
    //                     padding-top: 20px;
    //                     border-top: 1px solid #ddd;
    //                     font-size: 11px;
    //                     color: #7f8c8d;
    //                 }
    //                 .watermark {
    //                     opacity: 0.1;
    //                     position: fixed;
    //                     top: 50%;
    //                     left: 50%;
    //                     transform: translate(-50%, -50%);
    //                     font-size: 80px;
    //                     color: #000;
    //                     pointer-events: none;
    //                     z-index: -1;
    //                 }
    //                 .no-print { display: none !important; }
    //             }
    //             body { margin: 30px; }
    //         </style>
    //     </head>
    //     <body>
    //         <div class="watermark">GATE PASS</div>

    //         <div class="print-header">
    //             <h1>STUDENT GATE PASS</h1>
    //             <div class="subtitle">
    //                 Pass ID: #${data.id || 'N/A'} | Generated: ${new Date().toLocaleString()}
    //             </div>
    //         </div>

    //         <div class="section-card">
    //             <h3 class="section-title">Student Information</h3>
    //             <div class="info-grid">
    //                 <div class="info-item">
    //                     <div class="info-label">Student Name</div>
    //                     <div class="info-value">${data.name || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Room Number</div>
    //                     <div class="info-value">${data.room_number || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Email</div>
    //                     <div class="info-value">${data.email || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Mobile</div>
    //                     <div class="info-value">${data.mobile || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Course</div>
    //                     <div class="info-value">${data.course || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Department</div>
    //                     <div class="info-value">${data.department || 'N/A'}</div>
    //                 </div>
    //             </div>
    //         </div>

    //         <div class="section-card">
    //             <h3 class="section-title">Leave Details</h3>
    //             <div class="info-grid">
    //                 <div class="info-item">
    //                     <div class="info-label">Leave Type</div>
    //                     <div class="info-value">${data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Purpose</div>
    //                     <div class="info-value">${data.reason || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">From Date</div>
    //                     <div class="info-value">${new Date(data.start_date || data.from_date).toLocaleDateString()}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">To Date</div>
    //                     <div class="info-value">${new Date(data.end_date || data.to_date).toLocaleDateString()}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Applied On</div>
    //                     <div class="info-value">${new Date(data.applied_at || data.applied_on).toLocaleString()}</div>
    //                 </div>
    //             </div>
    //         </div>

    //         ${data.qr_code ? `
        //                 <div class="qr-section">
        //                     <h3 class="section-title">Verification QR Code</h3>
        //                     <img src="data:image/png;base64,${data.qr_code}" alt="QR Code">
        //                     ${data.token ? `<div style="margin-top: 10px;"><strong>Token:</strong> ${data.token}</div>` : ''}
        //                 </div>
        //             ` : ''}

    //         ${data.hod_status || data.admin_status ? `
        //                 <div class="section-card">
        //                     <h3 class="section-title">Approval Status</h3>
        //                     <div style="margin-bottom: 15px;">
        //                         <strong>HOD Status:</strong> 
        //                         <span class="status-badge ${data.hod_status === 'approved' ? 'badge-approved' : 
        //                                                    data.hod_status === 'rejected' ? 'badge-rejected' : 
        //                                                    'badge-pending'}">
        //                             ${data.hod_status || 'Pending'}
        //                         </span>
        //                         ${data.hod_remarks ? `<div style="margin-top: 5px; font-size: 12px;">${data.hod_remarks}</div>` : ''}
        //                     </div>
        //                     <div>
        //                         <strong>Admin Status:</strong> 
        //                         <span class="status-badge ${data.admin_status === 'approved' ? 'badge-approved' : 
        //                                                    data.admin_status === 'rejected' ? 'badge-rejected' : 
        //                                                    'badge-pending'}">
        //                             ${data.admin_status || 'Pending'}
        //                         </span>
        //                         ${data.admin_remarks ? `<div style="margin-top: 5px; font-size: 12px;">${data.admin_remarks}</div>` : ''}
        //                     </div>
        //                 </div>
        //             ` : ''}

    //         <div class="footer">
    //             <div>Generated by Leave Management System • ${new Date().toLocaleString()}</div>
    //             <div style="margin-top: 5px;">
    //                 <strong>Hostel Timings:</strong> IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}
    //             </div>
    //             <div style="margin-top: 5px; font-size: 10px;">
    //                 This is an electronically generated document. Valid only with QR code verification.
    //             </div>
    //         </div>
    //     </body>
    //     </html>
    // `);

        //         printWindow.document.close();
        //         printWindow.focus();

        //         // Wait for content to load then print
        //         setTimeout(() => {
        //             printWindow.print();
        //             printWindow.close();
        //         }, 500);

        //         // Restore print button
        //         printBtn.innerHTML = originalContent;
        //         printBtn.disabled = false;

        //     }, 500);
        // }

        function viewReceipt(button) {
            const originalButtonHTML = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
            button.disabled = true;

            const leaveId = button.getAttribute("data-id");
            const receiptContainer = document.getElementById('receiptDetails');

            receiptContainer.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
            <p class="text-muted">Loading...</p>
        </div>
    `;

            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();

            fetch(`/api/resident/leaves/${leaveId}`, {
                    method: "GET",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(async res => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then(({
                    data
                }) => {
                    const formatDate = d => d ? new Date(d).toLocaleDateString('en-IN') : 'N/A';
                    const formatDateTime = d => d ? new Date(d).toLocaleString('en-IN') : 'N/A';

                    const getStatusBadge = (s) => {
                        const map = {
                            'pending': {
                                class: 'bg-warning text-dark',
                                icon: 'clock'
                            },
                            'approved': {
                                class: 'bg-success',
                                icon: 'check-circle'
                            },
                            'rejected': {
                                class: 'bg-danger',
                                icon: 'times-circle'
                            }
                        };
                        const status = s?.toLowerCase();
                        const info = map[status] || {
                            class: 'bg-info',
                            icon: 'info-circle'
                        };
                        return `<span class="badge ${info.class}"><i class="fas fa-${info.icon} me-1"></i>${s || 'N/A'}</span>`;
                    };

                    // <p class="text-muted small">ID: #${data.id || ''} • ${formatDateTime(new Date())}</p>
                    receiptContainer.innerHTML = `
            <div class="receipt-card">
                <div class="text-center mb-4">
                    <h3 class="text-primary mb-2">Student Gate Pass</h3>
                    <p class="text-muted small"> ${formatDateTime(new Date())}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h5 class="border-bottom pb-2"><i class="fas fa-user-graduate me-2"></i>Student Info</h5>
                            <div class="row g-2">
                                <div class="col-6"><small class="text-muted">Name</small><div>${data.name || ''}</div></div>
                                <div class="col-6"><small class="text-muted">Scholar No.</small><div>${data.scholar_no || ''}</div></div>
                                <div class="col-6"><small class="text-muted">Mobile</small><div>${data.mobile || 'N/A'}</div></div>
                                <div class="col-6"><small class="text-muted">Hostel</small><div>${data.hostel_name || ''} </div></div>
                                <div class="col-6"><small class="text-muted">Room</small><div>${data.room_number || 'N/A'}</div></div>
                                <div class="col-6"><small class="text-muted">Course</small><div>${data.course || 'N/A'}</div></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <h5 class="border-bottom pb-2"><i class="fas fa-calendar-alt me-2"></i>Leave Details</h5>
                            <div class="row g-2">
                                <div class="col-6"><small class="text-muted">Type</small><div>${data.type || 'N/A'}</div></div>
                                <div class="col-6"><small class="text-muted">Reason</small><div>${data.reason || 'N/A'}</div></div>
                                <div class="col-6"><small class="text-muted">From</small><div>${formatDate(data.start_date || data.from_date)}</div></div>
                                <div class="col-6"><small class="text-muted">To</small><div>${formatDate(data.end_date || data.to_date)}</div></div>
                                <div class="col-6"><small class="text-muted">Applied On</small><div>${(data.applied_on || data.applied_on ? data.applied_on : '')}</div></div>
                                <div class="col-6"><small class="text-muted">Action Date</small><div>${(data.admin_action_at || data.admin_action_at ? data.admin_action_at : 'Not yet processed')}</div></div>
                            </div>
                        </div>
                        
                       <!-- ${data.hod_status || data.admin_status ? `
                                                                        <div class="mt-3">
                                                                            <h5 class="border-bottom pb-2 mt-3"><i class="fas fa-check-circle me-2"></i>Approval Status</h5>
                                                                            <div class="row g-2">
                                                                                <div class="col-6">
                                                                                    <small class="text-muted">HOD</small>
                                                                                    <div>${getStatusBadge(data.hod_status)}</div>
                                                                                    ${data.hod_remarks ? `<small class="text-muted">${data.hod_remarks}</small>` : ''}
                                                                                </div>
                                                                                <div class="col-6">
                                                                                    <small class="text-muted">Admin</small>
                                                                                    <div>${getStatusBadge(data.admin_status)}</div>
                                                                                    ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        ` : ''} -->
                    </div>
                    
                    <div class="col-md-4">
                        <div class="border-start ps-3">
                            <h5 class="border-bottom pb-2 text-center"><i class="fas fa-qrcode me-2"></i>Verification</h5>
                            <div class="text-center">
                                ${data.qr_code ? 
                                    `<img src="data:image/png;base64,${data.qr_code}" class="img-fluid mb-2" style="max-width:180px;" alt="QR">` : 
                                    '<div class="text-muted py-3"><i class="fas fa-qrcode fa-2x"></i><p>No QR Code</p></div>'
                                }
                                ${data.token ? `
                                                                                    <div class="small mt-2">
                                                                                        <small class="text-muted d-block">Token</small>
                                                                                        <code class="bg-light p-1 rounded">${data.token}</code>
                                                                                    </div>
                                                                                ` : ''}
                                <p class="small text-muted mt-2">Scan to verify</p>
                            </div>

                           <!-- ${data.status ? `
                                                                            <div class="mt-3">
                                                                                <h5 class="border-bottom pb-2"><i class="fas fa-check-circle me-2"></i>Approval Status</h5>
                                                                                <div class="row g-2">
                                                                                    <div class="col-12">
                                                                                        <small class="text-muted">Leave Status</small>
                                                                                        <div>${getStatusBadge(data.status)}</div>
                                                                                        ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        ` : ''}
                                                                        -->
                                                                        ${data.status ? `
  <div class="mt-3">
    <h5 class="border-bottom pb-2">
      ${getStatusIcon(data.status)} Status
    </h5>
    <div class="row g-2">
      <div class="col-12 text-center">
        <small class="text-muted">Approval Status</small>
        <div class="status-badge">${getStatusBadge(data.status)}</div>
        ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
      </div>
    </div>
  </div>
` : ''}

                        </div>
                    </div>
                </div>
                
                <div class="mt-3 pt-3 border-top small text-muted">
                    <div class="row">
                        <div class="col">Hostel Timings: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
                        <div class="col text-end">Generated electronically</div>
                    </div>
                </div>
            </div>
        `;

                    button.innerHTML = originalButtonHTML;
                    button.disabled = false;

                    // Setup token copy
                    const tokenEl = receiptContainer.querySelector('code');
                    if (tokenEl) {
                        tokenEl.style.cursor = 'pointer';
                        tokenEl.onclick = () => {
                            navigator.clipboard.writeText(tokenEl.textContent);
                            const original = tokenEl.textContent;
                            tokenEl.textContent = 'Copied!';
                            setTimeout(() => tokenEl.textContent = original, 1500);
                        };
                    }

                    // Setup print
                    document.getElementById('printReceiptBtn').onclick = () => smartPrint(data);

                }).catch(err => {
                    console.error(err);
                    receiptContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
                <h5 class="text-danger">Error Loading</h5>
                <p class="text-muted">${err.message || 'Please try again'}</p>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="viewReceipt(this)" data-id="${leaveId}">
                    <i class="fas fa-redo me-1"></i> Retry
                </button>
            </div>
        `;
                    button.innerHTML = originalButtonHTML;
                    button.disabled = false;
                });
        }

        // Smart compact print function
        function smartPrint(data) {
            const printBtn = document.getElementById('printReceiptBtn');
            const originalHTML = printBtn.innerHTML;
            printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
            printBtn.disabled = true;

            const format = d => d ? new Date(d).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) : 'N/A';
            const now = new Date().toLocaleString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Print configuration - control what prints
            const printConfig = {
                showLogo: true,
                showQr: true,
                showStudentInfo: true,
                showLeaveDetails: true,
                showStatus: true,
                showFooter: true,
                compactMode: true // Reduced spacing for compact print
            };

            const printWindow = window.open('', '_blank', 'width=800,height=600');

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Gate Pass #${data.id || ''}</title>
                    <style>
                        @media print {
                            @page { margin: 15mm; }
                            body { 
                                font-family: Arial, sans-serif; 
                                margin: 0; 
                                padding: 0;
                                font-size: 12px;
                                line-height: 1.3;
                            }
                            .no-print { display: none !important; }
                            .print-only { display: block !important; }
                        }
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 20px;
                            font-size: 12px;
                            line-height: 1.3;
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: ${printConfig.compactMode ? '15px' : '20px'};
                            padding-bottom: ${printConfig.compactMode ? '10px' : '15px'};
                            border-bottom: 2px solid #000;
                        }
                        .header img { 
                            max-height: 60px; 
                            margin-bottom: ${printConfig.compactMode ? '5px' : '10px'};
                        }
                        .header h1 { 
                            margin: 0; 
                            font-size: ${printConfig.compactMode ? '18px' : '20px'};
                            color: #000;
                        }
                        .header .subtitle {
                            font-size: 11px;
                            color: #666;
                            margin-top: 3px;
                        }
                        .content { 
                            display: flex; 
                            margin: ${printConfig.compactMode ? '15px 0' : '20px 0'};
                        }
                        .left { 
                            flex: 1; 
                            padding-right: ${printConfig.compactMode ? '15px' : '20px'};
                        }
                        .right { 
                            width: 160px; 
                            text-align: center;
                            border-left: 1px solid #ddd;
                            padding-left: ${printConfig.compactMode ? '10px' : '15px'};
                        }
                        .section {
                            margin-bottom: ${printConfig.compactMode ? '12px' : '15px'};
                            page-break-inside: avoid;
                        }
                        .section-title {
                            font-weight: bold;
                            font-size: 13px;
                            color: #333;
                            margin-bottom: ${printConfig.compactMode ? '6px' : '8px'};
                            padding-bottom: ${printConfig.compactMode ? '3px' : '4px'};
                            border-bottom: 1px solid #ccc;
                        }
                        .info-grid {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            gap: ${printConfig.compactMode ? '8px' : '10px'};
                        }
                        .info-item {
                            margin-bottom: ${printConfig.compactMode ? '5px' : '6px'};
                        }
                        .info-label {
                            font-weight: 600;
                            color: #555;
                            font-size: 11px;
                            margin-bottom: 1px;
                        }
                        .info-value {
                            color: #000;
                            font-size: 12px;
                        }
                        .qr-container {
                            margin: ${printConfig.compactMode ? '10px 0' : '15px 0'};
                        }
                        .qr-container img {
                            width: ${printConfig.compactMode ? '130px' : '150px'};
                            height: ${printConfig.compactMode ? '130px' : '150px'};
                            border: 1px solid #ddd;
                            padding: 5px;
                            background: white;
                        }
                        .badge {
                            display: inline-block;
                            padding: 2px 6px;
                            border-radius: 3px;
                            font-size: 10px;
                            font-weight: bold;
                        }
                        .badge-approved { background: #28a745; color: white; }
                        .badge-pending { background: #ffc107; color: #000; }
                        .badge-rejected { background: #dc3545; color: white; }
                        .token {
                            font-family: monospace;
                            font-size: 10px;
                            background: #f8f9fa;
                            padding: 4px 6px;
                            border-radius: 3px;
                            margin-top: 5px;
                            word-break: break-all;
                        }
                        .footer {
                            text-align: center;
                            margin-top: ${printConfig.compactMode ? '15px' : '20px'};
                            padding-top: ${printConfig.compactMode ? '8px' : '10px'};
                            border-top: 1px solid #ddd;
                            font-size: 10px;
                            color: #666;
                        }
                        .watermark {
                            opacity: 0.03;
                            position: fixed;
                            top: 30%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            font-size: 100px;
                            font-weight: bold;
                            color: #000;
                            z-index: -1;
                        }
                    </style>
                </head>
                <body>
                    ${printConfig.showLogo ? `
                                                                    <div class="header">
                                                                        <img src="https://hostel.rntu.ac.in/frontend/img/main-logo.png" alt="Logo">
                                                                        <h1>GATE PASS</h1>
                                                                        <div class="subtitle"> ${now}</div>
                                                                    </div>
                                                                    ` : `<h1 class="header">GATE PASS #${data.id || ''}</h1>`}
                    
                    <div class="content">
                        ${printConfig.showStudentInfo || printConfig.showLeaveDetails ? `
                                                                        <div class="left">
                                                                            ${printConfig.showStudentInfo ? `
                            <div class="section">
                                <div class="section-title">STUDENT INFORMATION</div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Name</div>
                                        <div class="info-value">${data.name || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Scholar</div>
                                        <div class="info-value">${data.scholar_no || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Hostel</div>
                                        <div class="info-value">${data.hostel_name || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Room No.</div>
                                        <div class="info-value">${data.room_number || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Mobile</div>
                                        <div class="info-value">${data.mobile || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Course</div>
                                        <div class="info-value">${data.course || 'N/A'}</div>
                                    </div>
                                </div>
                            </div>
                            ` : ''}
                                                                            
                                                                            ${printConfig.showLeaveDetails ? `
                            <div class="section">
                                <div class="section-title">LEAVE DETAILS</div>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Type</div>
                                        <div class="info-value">${data.type || 'N/A'}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Disposal</div>
                                        <div class="info-value">${(data.admin_action_at ? data.admin_action_at : '')}</div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">From</div>
                                        <div class="info-value">${format(data.start_date || data.from_date)}</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">To</div>
                                        <div class="info-value">${format(data.end_date || data.to_date)}</div>
                                    </div>
                                    
                                </div>
                            </div>
                            ` : ''}
                                                                            <!--
                                                                            ${printConfig.showStatus && (data.hod_status || data.admin_status) ? `
                                    <div class="section">
                                        <div class="section-title">STATUS</div>
                                        <div class="info-grid">
                                            <div class="info-item">
                                                <div class="info-label">HOD</div>
                                                <div class="info-value">
                                                    <span class="badge ${data.hod_status === 'approved' ? 'badge-approved' : 
                                                                    data.hod_status === 'rejected' ? 'badge-rejected' : 
                                                                    'badge-pending'}">
                                                        ${data.hod_status || 'Pending'}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <div class="info-label">Admin</div>
                                                <div class="info-value">
                                                    <span class="badge ${data.admin_status === 'approved' ? 'badge-approved' : 
                                                                    data.admin_status === 'rejected' ? 'badge-rejected' : 
                                                                    'badge-pending'}">
                                                        ${data.admin_status || 'Pending'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ` : ''}

                                                                            -->
                                                                        </div>
                                                                        ` : ''}
                        
                        ${printConfig.showQr ? `
                                                                        <div class="right">
                                                                            <div class="section">
                                                                                <div class="section-title">VERIFICATION</div>
                                                                                <div class="qr-container">
                                                                                    ${data.qr_code ? 
                                                                                        `<img src="data:image/png;base64,${data.qr_code}" alt="QR Code">` : 
                                                                                        `<div style="height:150px; display:flex; align-items:center; justify-content:center; border:1px dashed #ccc; color:#999;">
                                            No QR Code
                                        </div>`
                                                                                    }
                                                                                </div>
                                                                                ${data.token ? `
                                    <div>
                                        <div class="info-label">Token</div>
                                        <div class="token">${data.token}</div>
                                    </div>
                                    ` : ''}
                                                                                <div style="margin-top:10px; font-size:11px; font-weight:bold;">SCAN TO VERIFY</div>
                                                                            </div>

                                                                             ${printConfig.showStatus && (data.status) ? `
                                    <div class="section">
                                        <!-- <div class="section-title">STATUS</div> -->
                                        
                                            <div class="info-item text-center">
                                                <div class="info-label"></div>
                                                <div class="info-value">
                                                    <span class="badge ${data.status === 'approved' ? 'badge-approved' : 
                                                                    data.status === 'rejected' || 'Rejected' ? 'badge-rejected' : 
                                                                    'badge-pending'}">
                                                        ${data.status || 'Pending'}
                                                    </span>
                                                </div>
                                            </div>
                                        
                                        ${data.status === 'rejected' || 'Rejected' && data.admin_remarks ? `
                                                                                        <div style="margin-top:5px; font-size:10px; color:#555;">
                                                                                            <strong>Remarks:</strong> ${data.admin_remarks}
                                                                                        </div>
                                                                                    ` : ''}
                                    </div>
                                    ` : ''}
                                                                       
                                                                            <div style="margin-top:40px; font-size:12px;">
                                                                                <strong>Authorised Sign..</strong>
                                                                                </div>
                                                                        </div>
                                                                        ` : ''}
                    </div>
                    
                    ${printConfig.showFooter ? `
                                                                    <div class="footer">
                                                                        <div>Generated by Leave Management System • ${new Date().toLocaleString('en-IN')}</div>
                                                                        <div>Hostel: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
                                                                        <div style="margin-top:2px; font-size:9px;">Electronically generated • Valid with QR verification</div>
                                                                    </div>
                                                                    ` : ''}
                    
                    <div class="watermark print-only">GATE PASS</div>
                    
                    <div class="no-print" style="text-align:center; margin-top:20px;">
                        <button onclick="window.print()" style="padding:8px 20px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
                            Print Now
                        </button>
                        <button onclick="window.close()" style="padding:8px 20px; background:#6c757d; color:white; border:none; border-radius:4px; margin-left:10px; cursor:pointer;">
                            Close
                        </button>
                    </div>
                    
                    
                </body>
                </html>
            `);

            

            printWindow.document.close();

            // Restore button
            setTimeout(() => {
                printBtn.innerHTML = originalHTML;
                printBtn.disabled = false;
            }, 1000);

             setTimeout(() => window.print(), 300);
                window.onafterprint = () => setTimeout(() => window.close(), 100);
        }


        function getStatusBadge(status) {
            if (!status) return '<span class="badge bg-secondary">N/A</span>';

            switch (status.toLowerCase()) {
                case 'pending':
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                case 'approved':
                    return '<span class="badge bg-success">Approved</span>';
                case 'rejected':
                    return '<span class="badge bg-danger">Rejected</span>';
                case 'cancelled':
                    return '<span class="badge bg-secondary">Cancelled</span>';
                default:
                    return `<span class="badge bg-info">${status}</span>`;
            }
        }
    </script>

    <script>
        function truncateWithExpand(text, limit = 80) {
            if (!text) return 'N/A';
            text = text.toString();
            if (text.length <= limit) return text;

            const truncated = text.substring(0, limit) + '...';
            // Use encodeURIComponent to safely store full text
            return `
                <span class="truncated" data-full="${encodeURIComponent(text)}" data-limit="${limit}">
                    ${truncated}
                    <span class="expand" style="color:blue;cursor:pointer">more</span>
                </span>
            `;
        }

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("expand")) {
                const span = e.target.closest(".truncated");
                const fullText = decodeURIComponent(span.getAttribute("data-full"));
                span.innerHTML = fullText +
                    ' <span class="collapse" style="color:red;cursor:pointer">less</span>';
            }
            if (e.target.classList.contains("collapse")) {
                const span = e.target.closest(".truncated");
                const fullText = decodeURIComponent(span.getAttribute("data-full"));
                const limit = span.getAttribute("data-limit");
                const truncated = fullText.substring(0, limit) + '...';
                span.innerHTML = truncated +
                    ' <span class="expand" style="color:blue;cursor:pointer">more</span>';
            }
        });


        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    customClass: 'square-tooltip'
                });
            });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        // Example: token from API response 
        const token = "abc123securetoken";

        // Verification URL 
        const verifyUrl = `https://yourdomain.com/leave/verify/${token}`;

        Generate QR new QRCode(document.getElementById("qrcode"), {
            text: verifyUrl,
            width: 128,
            height: 128
        });
    </script> --}}
@endpush
