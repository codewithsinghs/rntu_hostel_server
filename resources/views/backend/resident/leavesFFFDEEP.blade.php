@extends('resident.layout')

@section('content')
    <div class="container-fluid py-4">
        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div
                    class="card border-left-primary border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-requests">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div
                    class="card border-left-success border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Approved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-leaves-approved">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div
                    class="card border-left-warning border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-leaves-pending">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div
                    class="card border-left-danger border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Rejected
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="total-leaves-rejected">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apply Leave Button -->
        <div class="row mb-4">
            <div class="col-12">
                <button class="btn btn-primary btn-lg shadow-sm" data-bs-toggle="modal" data-bs-target="#applyLeaveModal">
                    <i class="fas fa-plus-circle me-2"></i> Apply for Leave
                </button>
            </div>
        </div>

        <!-- Leave Requests Table -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>My Leave Requests
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="refreshTableBtn">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter Status
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item filter-status" href="#" data-status="">All Status</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="approved">Approved</a>
                            </li>
                            <li><a class="dropdown-item filter-status" href="#" data-status="rejected">Rejected</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="leaveRequestsTable">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Duration</th>
                                <th>Applied On</th>
                                <th>HOD Status</th>
                                <th>Admin Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via DataTable -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Leave Modal -->
    <div class="modal fade" id="applyLeaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Apply for Leave
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveRequestForm" enctype="multipart/form-data">
                        <div id="form-message" class="alert d-none"></div>

                        <div class="row g-3">
                            <!-- Leave Type -->
                            <div class="col-md-6">
                                <label for="type" class="form-label required">Leave Type</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="" selected disabled>Select Leave Type</option>
                                    <option value="medical">Medical Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                    <option value="casual">Casual Leave</option>
                                    <option value="parental">Parental/Family Leave</option>
                                    <option value="festival">Festival Leave</option>
                                    <option value="official">Academic/Official Leave</option>
                                    <option value="exam">Exam Related Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Reason -->
                            <div class="col-md-6">
                                <label for="reason" class="form-label required">Reason</label>
                                <select class="form-select" id="reason" name="reason" required>
                                    <option value="" selected disabled>Select Reason</option>
                                    <!-- Dynamic options will be inserted -->
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Custom Reason (Hidden by default) -->
                            <div class="col-12" id="custom-reason-container" style="display: none;">
                                <label for="custom_reason" class="form-label required">Custom Reason</label>
                                <textarea class="form-control" id="custom_reason" name="custom_reason" rows="2"
                                    placeholder="Please specify your reason"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label required">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label required">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Duration Display -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Duration: <span id="duration-days">0</span> day(s)
                                </div>
                            </div>

                            <!-- Emergency Contact (Conditional) -->
                            <div class="col-12" id="emergency-contact-container" style="display: none;">
                                <label for="emergency_contact" class="form-label required">Emergency Contact
                                    Number</label>
                                <input type="tel" class="form-control" id="emergency_contact"
                                    name="emergency_contact" placeholder="Emergency contact number">
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Attachment -->
                            <div class="col-12">
                                <label for="attachment" class="form-label">Supporting Document (Optional)</label>
                                <input type="file" class="form-control" id="attachment" name="attachment"
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                <small class="text-muted">Max 5MB. Supported: PDF, JPG, PNG, DOC</small>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Additional Details (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Any additional information"></textarea>
                            </div>

                            <!-- Declaration -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="declaration" required>
                                    <label class="form-check-label" for="declaration">
                                        I declare that the information provided is true and correct. I understand that
                                        providing false information may lead to disciplinary action.
                                    </label>
                                    <div class="invalid-feedback">You must accept the declaration</div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="submitLeaveBtn">
                        <i class="fas fa-paper-plane me-1"></i> Submit Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View/Edit Leave Modal -->
    <div class="modal fade" id="viewEditLeaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Leave Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLeaveForm" enctype="multipart/form-data">
                        <div id="edit-form-message" class="alert d-none"></div>
                        <input type="hidden" id="edit_leave_id" name="id">

                        <!-- Read-only info for approved/rejected leaves -->
                        <div id="viewModeContent">
                            <!-- Content will be dynamically filled -->
                        </div>

                        <!-- Editable form (hidden by default) -->
                        <div id="editModeContent" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="edit_type" class="form-label required">Leave Type</label>
                                    <select class="form-select" id="edit_type" name="type" disabled>
                                        <!-- Options will be populated -->
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="edit_reason" class="form-label required">Reason</label>
                                    <input type="text" class="form-control" id="edit_reason" name="reason" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="edit_start_date" class="form-label required">Start Date</label>
                                    <input type="date" class="form-control" id="edit_start_date" name="start_date"
                                        disabled>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="edit_end_date" class="form-label required">End Date</label>
                                    <input type="date" class="form-control" id="edit_end_date" name="end_date"
                                        disabled>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-12">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit_description" name="description" rows="3" disabled></textarea>
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> Only pending leaves can be edited
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteLeaveBtn" style="display: none;">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                    <button type="button" class="btn btn-warning" id="editLeaveBtn" style="display: none;">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                    <button type="button" class="btn btn-success" id="saveLeaveBtn" style="display: none;">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                    <button type="button" class="btn btn-primary" id="viewGatePassBtn">
                        <i class="fas fa-id-card me-1"></i> View Gate Pass
                    </button>
                    <button type="button" class="btn btn-danger" id="cancelLeaveBtn" style="display: none;">
                        <i class="fas fa-times me-1"></i> Cancel Request
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gate Pass Modal -->
    <div class="modal fade" id="gatePassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-id-card me-2"></i>Gate Pass
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gatePassContent" class="p-3">
                        <!-- Gate pass will be rendered here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printGatePassBtn">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .required::after {
            content: " *";
            color: #dc3545;
        }

        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-approved {
            background-color: #28a745;
            color: white;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 4px 8px;
            font-size: 12px;
        }

        /* DataTable custom styling */
        #leaveRequestsTable_wrapper {
            padding: 0;
        }

        #leaveRequestsTable tbody tr {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        #leaveRequestsTable tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
    </style>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        // Configuration
        const CONFIG = {
            API_BASE: '/api/resident/leaves',
            TOKEN_KEY: 'token',
            USER_ID_KEY: 'auth-id'
        };

        // Main Leave Management Class
        class LeaveManagement {
            constructor() {
                this.dataTable = null;
                this.currentLeaveId = null;
                this.currentStatusFilter = '';
                this.reasonOptions = this.getReasonOptions();
                this.init();
            }

            init() {
                this.initDataTable();
                this.initEventListeners();
                this.initFormHandlers();
                this.loadSummary();
            }

            // DataTable Initialization
            initDataTable() {
                this.dataTable = $('#leaveRequestsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: CONFIG.API_BASE,
                        type: 'GET',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`
                        },
                        data: (d) => {
                            d.status_filter = this.currentStatusFilter;
                        },
                        error: (xhr) => {
                            if (xhr.status === 401) {
                                this.handleUnauthorized();
                            }
                        }
                    },
                    columns: [
                        // {
                        //         // 🔹 Dedicated control column for responsive toggle
                        //         data: null,
                        //         defaultContent: '',
                        //         className: 'dtr-control',
                        //         orderable: false,
                        //         searchable: false,
                        //         width: '1%'
                        //     },
                            {
                                // 🔹 SR column (#)
                                data: null,
                                title: '#',
                                orderable: false,
                                render: (data, type, row, meta) => meta.row + 1,
                                width: '1%'
                            },
                        {
                            data: 'type',
                            name: 'type',
                            render: (data) => this.formatLeaveType(data)
                        },
                        {
                            data: 'reason',
                            name: 'reason',
                            render: (data, type, row) => this.truncateText(data, 50)
                        },
                        {
                            data: null,
                            render: (data, type, row) => this.calculateDuration(row.start_date, row
                                .end_date)
                        },
                        {
                            data: 'applied_at',
                            name: 'applied_at',
                            render: (data) => this.formatDateTime(data)
                        },
                        {
                            data: 'hod_status',
                            name: 'hod_status',
                            render: (data) => this.getStatusBadge(data)
                        },
                        {
                            data: 'admin_status',
                            name: 'admin_status',
                            render: (data) => this.getStatusBadge(data)
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            render: (data, type, row) => this.renderActionButtons(row)
                        }
                    ],
                    order: [
                        [4, 'desc']
                    ],
                    pageLength: 10,
                    language: {
                        emptyTable: 'No leave requests found',
                        processing: '<div class="spinner-border text-primary" role="status"></div>'
                    }
                });
            }

            // Event Listeners
            initEventListeners() {
                // Refresh button
                $('#refreshTableBtn').click(() => this.refreshTable());

                // Filter status
                $('.filter-status').click((e) => {
                    e.preventDefault();
                    this.currentStatusFilter = $(e.target).data('status');
                    this.refreshTable();
                    this.updateActiveFilter(e.target);
                });

                // Table row click
                $('#leaveRequestsTable tbody').on('click', 'tr', (e) => {
                    if ($(e.target).closest('.btn').length === 0) {
                        const data = this.dataTable.row(e.currentTarget).data();
                        if (data) {
                            this.viewLeaveDetails(data.id);
                        }
                    }
                });

                // Refresh on leave events
                document.addEventListener('leaveSubmitted', () => this.refreshTable());
                document.addEventListener('leaveUpdated', () => this.refreshTable());
                document.addEventListener('leaveDeleted', () => this.refreshTable());
                document.addEventListener('leaveCancelled', () => this.refreshTable());
            }

            // Form Handlers
            initFormHandlers() {
                this.initApplyForm();
                this.initEditForm();
                this.initGatePass();
            }

            // Apply Leave Form
            initApplyForm() {
                const form = $('#leaveRequestForm');
                const typeSelect = $('#type');
                const reasonSelect = $('#reason');
                const customReasonContainer = $('#custom-reason-container');
                const customReasonInput = $('#custom_reason');
                const startDate = $('#start_date');
                const endDate = $('#end_date');
                const durationDisplay = $('#duration-days');
                const emergencyContainer = $('#emergency-contact-container');
                const submitBtn = $('#submitLeaveBtn');

                // Set minimum dates
                const today = new Date().toISOString().split('T')[0];
                startDate.attr('min', today);
                endDate.attr('min', today);

                // Leave type change - filter reasons
                typeSelect.change(() => {
                    const type = typeSelect.val();
                    this.updateReasonOptions(type, reasonSelect);
                    this.toggleEmergencyContact(type, emergencyContainer);
                });

                // Reason change - show custom reason
                reasonSelect.change(() => {
                    if (reasonSelect.val() === 'other') {
                        customReasonContainer.show();
                        customReasonInput.attr('required', true);
                    } else {
                        customReasonContainer.hide();
                        customReasonInput.attr('required', false);
                    }
                });

                // Date change - calculate duration
                startDate.add(endDate).change(() => {
                    if (startDate.val() && endDate.val()) {
                        const days = this.calculateDays(startDate.val(), endDate.val());
                        durationDisplay.text(days);
                        endDate.attr('min', startDate.val());
                    }
                });

                // Submit form
                submitBtn.click(() => this.submitLeaveForm());
            }

            // Submit Leave Form
            async submitLeaveForm() {
                const form = $('#leaveRequestForm');
                const formData = new FormData(form[0]);

                // Validate form
                if (!this.validateForm(form)) {
                    return;
                }

                // Show loading
                const originalText = $('#submitLeaveBtn').html();
                $('#submitLeaveBtn').html('<span class="spinner-border spinner-border-sm me-2"></span>Submitting...');
                $('#submitLeaveBtn').prop('disabled', true);

                try {
                    const response = await fetch(CONFIG.API_BASE, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Success
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Leave request submitted successfully',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        // Reset form and close modal
                        form[0].reset();
                        $('#custom-reason-container').hide();
                        $('#emergency-contact-container').hide();
                        $('#applyLeaveModal').modal('hide');

                        // Trigger event
                        document.dispatchEvent(new CustomEvent('leaveSubmitted'));

                        // Refresh summary
                        this.loadSummary();

                    } else {
                        // Error
                        this.handleFormErrors(form, data);
                    }

                } catch (error) {
                    console.error('Submission error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Network error. Please try again.'
                    });
                } finally {
                    $('#submitLeaveBtn').html(originalText);
                    $('#submitLeaveBtn').prop('disabled', false);
                }
            }

            // View/Edit Leave Form
            initEditForm() {
                const modal = $('#viewEditLeaveModal');

                // Edit button
                $('#editLeaveBtn').click(() => this.enableEditMode());

                // Save button
                $('#saveLeaveBtn').click(() => this.updateLeave());

                // Delete button
                $('#deleteLeaveBtn').click(() => this.deleteLeave());

                // Cancel button
                $('#cancelLeaveBtn').click(() => this.cancelLeave());

                // Close modal reset
                modal.on('hidden.bs.modal', () => {
                    this.resetEditModal();
                });
            }

            // View Leave Details
            async viewLeaveDetails(leaveId) {
                this.currentLeaveId = leaveId;

                try {
                    const response = await fetch(`${CONFIG.API_BASE}/${leaveId}`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to load leave details');

                    const data = await response.json();
                    this.populateViewModal(data.data);
                    $('#viewEditLeaveModal').modal('show');

                } catch (error) {
                    console.error('Error loading leave:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load leave details'
                    });
                }
            }

            // Populate View Modal
            populateViewModal(data) {
                const isEditable = data.hod_status === 'pending' && data.admin_status === 'pending';

                // Set view mode content
                $('#viewModeContent').html(`
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-muted">Leave Type</label>
                    <div class="form-control-plaintext">${this.formatLeaveType(data.type)}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Reason</label>
                    <div class="form-control-plaintext">${data.reason || 'N/A'}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Start Date</label>
                    <div class="form-control-plaintext">${this.formatDate(data.start_date)}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">End Date</label>
                    <div class="form-control-plaintext">${this.formatDate(data.end_date)}</div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted">Duration</label>
                    <div class="form-control-plaintext">${this.calculateDuration(data.start_date, data.end_date)}</div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted">Description</label>
                    <div class="form-control-plaintext">${data.description || 'No additional details'}</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">HOD Status</label>
                    <div>${this.getStatusBadge(data.hod_status)}</div>
                    ${data.hod_remarks ? `<small class="text-muted">${data.hod_remarks}</small>` : ''}
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted">Admin Status</label>
                    <div>${this.getStatusBadge(data.admin_status)}</div>
                    ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
                </div>
                ${data.attachment_url ? `
                    <div class="col-12">
                        <label class="form-label text-muted">Attachment</label>
                        <div>
                            <a href="${data.attachment_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-paperclip me-1"></i> View Attachment
                            </a>
                        </div>
                    </div>
                    ` : ''}
            </div>
        `);

                // Populate edit form fields
                $('#edit_type').val(data.type);
                $('#edit_reason').val(data.reason);
                $('#edit_start_date').val(data.start_date?.split('T')[0] || '');
                $('#edit_end_date').val(data.end_date?.split('T')[0] || '');
                $('#edit_description').val(data.description || '');

                // Show/hide buttons based on status
                $('#editLeaveBtn').toggle(isEditable);
                $('#deleteLeaveBtn').toggle(isEditable);
                $('#cancelLeaveBtn').toggle(isEditable && this.canCancelLeave(data));
                $('#saveLeaveBtn').hide();

                // Show view mode
                $('#viewModeContent').show();
                $('#editModeContent').hide();
            }

            // Enable Edit Mode
            enableEditMode() {
                $('#viewModeContent').hide();
                $('#editModeContent').show();
                $('#editLeaveBtn').hide();
                $('#deleteLeaveBtn').hide();
                $('#cancelLeaveBtn').hide();
                $('#saveLeaveBtn').show();

                // Enable form fields
                $('#edit_type').prop('disabled', false);
                $('#edit_reason').prop('disabled', false);
                $('#edit_start_date').prop('disabled', false);
                $('#edit_end_date').prop('disabled', false);
                $('#edit_description').prop('disabled', false);
            }

            // Update Leave
            async updateLeave() {
                const form = $('#editLeaveForm');
                const formData = new FormData(form[0]);

                try {
                    const response = await fetch(`${CONFIG.API_BASE}/${this.currentLeaveId}`, {
                        method: 'PUT',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Leave request updated successfully',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        $('#viewEditLeaveModal').modal('hide');
                        document.dispatchEvent(new CustomEvent('leaveUpdated'));

                    } else {
                        this.handleFormErrors(form, data);
                    }

                } catch (error) {
                    console.error('Update error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update leave request'
                    });
                }
            }

            // Delete Leave
            async deleteLeave() {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "This leave request will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`${CONFIG.API_BASE}/${this.currentLeaveId}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`
                        }
                    });

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Leave request has been deleted.',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        $('#viewEditLeaveModal').modal('hide');
                        document.dispatchEvent(new CustomEvent('leaveDeleted'));
                        this.loadSummary();

                    } else {
                        throw new Error('Delete failed');
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to delete leave request'
                    });
                }
            }

            // Cancel Leave
            async cancelLeave() {
                const result = await Swal.fire({
                    title: 'Cancel Leave Request?',
                    text: "Are you sure you want to cancel this leave request?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it'
                });

                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(`${CONFIG.API_BASE}/${this.currentLeaveId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    });

                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled!',
                            text: 'Leave request has been cancelled.',
                            timer: 3000,
                            showConfirmButton: false
                        });

                        $('#viewEditLeaveModal').modal('hide');
                        document.dispatchEvent(new CustomEvent('leaveCancelled'));
                        this.loadSummary();

                    } else {
                        throw new Error('Cancel failed');
                    }

                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to cancel leave request'
                    });
                }
            }

            // Gate Pass
            initGatePass() {
                $('#viewGatePassBtn').click(() => this.viewGatePass());
                $('#printGatePassBtn').click(() => this.printGatePass());
            }

            async viewGatePass() {
                try {
                    const response = await fetch(`${CONFIG.API_BASE}/${this.currentLeaveId}/gate-pass`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Failed to load gate pass');

                    const data = await response.json();
                    this.renderGatePass(data.data);
                    $('#gatePassModal').modal('show');

                } catch (error) {
                    console.error('Error loading gate pass:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to load gate pass'
                    });
                }
            }

            renderGatePass(data) {
                const html = `
            <div class="text-center mb-4">
                <h3 class="text-success">GATE PASS</h3>
                <p class="text-muted">ID: ${data.id || 'N/A'}</p>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <h5>Student Information</h5>
                        <div class="row">
                            <div class="col-6"><strong>Name:</strong> ${data.name || 'N/A'}</div>
                            <div class="col-6"><strong>Room:</strong> ${data.room_number || 'N/A'}</div>
                            <div class="col-6"><strong>Course:</strong> ${data.course || 'N/A'}</div>
                            <div class="col-6"><strong>Mobile:</strong> ${data.mobile || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h5>Leave Details</h5>
                        <div class="row">
                            <div class="col-6"><strong>Type:</strong> ${this.formatLeaveType(data.type)}</div>
                            <div class="col-6"><strong>Reason:</strong> ${data.reason || 'N/A'}</div>
                            <div class="col-6"><strong>From:</strong> ${this.formatDate(data.start_date)}</div>
                            <div class="col-6"><strong>To:</strong> ${this.formatDate(data.end_date)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 text-center">
                    ${data.qr_code ? 
                        `<img src="data:image/png;base64,${data.qr_code}" class="img-fluid mb-2" style="max-width: 150px;">
                             <p class="text-muted">Scan to verify</p>` : 
                        `<div class="alert alert-info">No QR Code Available</div>`
                    }
                </div>
            </div>
            
            <div class="mt-4 pt-3 border-top">
                <small class="text-muted">
                    Generated on ${new Date().toLocaleString()} | Valid with institute verification
                </small>
            </div>
        `;

                $('#gatePassContent').html(html);
            }

            printGatePass() {
                const content = $('#gatePassContent').html();
                const printWindow = window.open('', '_blank');

                printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Gate Pass</title>
                <style>
                    body { font-family: Arial; margin: 20px; }
                    .text-center { text-align: center; }
                    .mb-3 { margin-bottom: 15px; }
                    .mt-4 { margin-top: 20px; }
                    .border-top { border-top: 1px solid #ddd; padding-top: 10px; }
                </style>
            </head>
            <body>${content}</body>
            </html>
        `);

                printWindow.document.close();
                printWindow.print();
            }

            // Utility Methods
            validateForm(form) {
                let isValid = true;
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');

                // Check required fields
                form.find('[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('is-invalid');
                        $(this).next('.invalid-feedback').text('This field is required');
                        isValid = false;
                    }
                });

                // Check dates
                const startDate = form.find('[name="start_date"]');
                const endDate = form.find('[name="end_date"]');

                if (startDate.val() && endDate.val()) {
                    const start = new Date(startDate.val());
                    const end = new Date(endDate.val());

                    if (end < start) {
                        endDate.addClass('is-invalid');
                        endDate.next('.invalid-feedback').text('End date must be after start date');
                        isValid = false;
                    }
                }

                return isValid;
            }

            handleFormErrors(form, data) {
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const input = form.find(`[name="${field}"]`);
                        if (input.length) {
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(Array.isArray(messages) ? messages[0] :
                                messages);
                        }
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please correct the errors in the form'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message || 'An error occurred'
                    });
                }
            }

            updateReasonOptions(type, selectElement) {
                const options = this.reasonOptions[type] || this.reasonOptions['other'];
                selectElement.html(`
            <option value="" selected disabled>Select ${this.getTypeLabel(type)} Reason</option>
            ${options}
        `);
            }

            toggleEmergencyContact(type, container) {
                if (type === 'emergency') {
                    container.show();
                    container.find('input').attr('required', true);
                } else {
                    container.hide();
                    container.find('input').attr('required', false);
                }
            }

            getReasonOptions() {
                return {
                    'medical': `
                <option value="fever_cold">Fever/Cold</option>
                <option value="medical_checkup">Medical Checkup</option>
                <option value="hospitalization">Hospitalization</option>
                <option value="accident">Accident/Injury</option>
                <option value="other">Other Medical Reason</option>
            `,
                    'emergency': `
                <option value="family_emergency">Family Emergency</option>
                <option value="accident_emergency">Accident Emergency</option>
                <option value="home_emergency">Home Emergency</option>
                <option value="other">Other Emergency</option>
            `,
                    'casual': `
                <option value="bank_work">Bank Work</option>
                <option value="document_work">Document Work</option>
                <option value="personal_meeting">Personal Meeting</option>
                <option value="other">Other Personal Reason</option>
            `,
                    'other': `
                <option value="other">Other Reason</option>
            `
                };
            }

            formatLeaveType(type) {
                const types = {
                    'medical': 'Medical',
                    'emergency': 'Emergency',
                    'casual': 'Casual',
                    'parental': 'Parental',
                    'festival': 'Festival',
                    'official': 'Official',
                    'exam': 'Exam',
                    'personal': 'Personal',
                    'other': 'Other'
                };
                return types[type] || type;
            }

            getStatusBadge(status) {
                if (!status) return '<span class="badge bg-secondary">N/A</span>';

                const statusMap = {
                    'pending': {
                        class: 'badge-pending',
                        label: 'Pending'
                    },
                    'approved': {
                        class: 'badge-approved',
                        label: 'Approved'
                    },
                    'rejected': {
                        class: 'badge-rejected',
                        label: 'Rejected'
                    }
                };

                const info = statusMap[status.toLowerCase()] || {
                    class: 'badge bg-info',
                    label: status
                };
                return `<span class="status-badge ${info.class}">${info.label}</span>`;
            }

            calculateDuration(start, end) {
                if (!start || !end) return 'N/A';
                const days = this.calculateDays(start, end);
                return `${days} day${days !== 1 ? 's' : ''}`;
            }

            calculateDays(start, end) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                const diffTime = Math.abs(endDate - startDate);
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            }

            formatDate(dateString) {
                if (!dateString) return 'N/A';
                return new Date(dateString).toLocaleDateString('en-IN');
            }

            formatDateTime(dateString) {
                if (!dateString) return 'N/A';
                return new Date(dateString).toLocaleString('en-IN');
            }

            truncateText(text, length) {
                if (!text || text.length <= length) return text || 'N/A';
                return text.substring(0, length) + '...';
            }

            getTypeLabel(type) {
                const labels = {
                    'medical': 'Medical',
                    'emergency': 'Emergency',
                    'casual': 'Casual',
                    'parental': 'Family',
                    'festival': 'Festival',
                    'official': 'Academic/Official',
                    'exam': 'Exam',
                    'personal': 'Personal'
                };
                return labels[type] || 'Appropriate';
            }

            renderActionButtons(row) {
                const isPending = row.hod_status === 'pending' && row.admin_status === 'pending';

                return `
            <div class="action-buttons">
                <button class="btn btn-sm btn-outline-primary view-btn" data-id="${row.id}">
                    <i class="fas fa-eye"></i>
                </button>
                ${isPending ? `
                        <button class="btn btn-sm btn-outline-warning edit-btn" data-id="${row.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${row.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : ''}
                <button class="btn btn-sm btn-outline-success gatepass-btn" data-id="${row.id}">
                    <i class="fas fa-id-card"></i>
                </button>
            </div>
        `;
            }

            canCancelLeave(data) {
                // Can cancel if leave hasn't started yet
                const startDate = new Date(data.start_date);
                const today = new Date();
                return startDate > today;
            }

            // Table Methods
            refreshTable() {
                this.dataTable.ajax.reload(null, false);
            }

            updateActiveFilter(element) {
                $('.filter-status').removeClass('active');
                $(element).addClass('active');
            }

            // Summary Methods
            async loadSummary() {
                try {
                    const response = await fetch(`${CONFIG.API_BASE}/summary`, {
                        headers: {
                            'Authorization': `Bearer ${localStorage.getItem(CONFIG.TOKEN_KEY)}`
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.updateSummaryCards(data.data);
                    }
                } catch (error) {
                    console.error('Error loading summary:', error);
                }
            }

            updateSummaryCards(summary) {
                $('#total-requests').text(summary.total_leaves || 0);
                $('#total-leaves-approved').text(summary.approved || 0);
                $('#total-leaves-pending').text(summary.pending || 0);
                $('#total-leaves-rejected').text(summary.rejected || 0);
            }

            // Modal Reset
            resetEditModal() {
                $('#viewModeContent').show();
                $('#editModeContent').hide();
                $('#editLeaveBtn').show();
                $('#saveLeaveBtn').hide();
                $('#editLeaveForm')[0].reset();
                $('#editLeaveForm').find('.is-invalid').removeClass('is-invalid');
                $('#editLeaveForm').find('.invalid-feedback').text('');
            }

            // Auth Handling
            handleUnauthorized() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Session Expired',
                    text: 'Your session has expired. Please login again.',
                    confirmButtonText: 'Login'
                }).then(() => {
                    window.location.href = '/login';
                });
            }
        }

        // Initialize when DOM is ready
        $(document).ready(function() {
            window.leaveManager = new LeaveManagement();

            // Event delegation for action buttons
            $('#leaveRequestsTable').on('click', '.view-btn', function(e) {
                e.stopPropagation();
                const leaveId = $(this).data('id');
                window.leaveManager.viewLeaveDetails(leaveId);
            });

            $('#leaveRequestsTable').on('click', '.edit-btn', function(e) {
                e.stopPropagation();
                const leaveId = $(this).data('id');
                window.leaveManager.viewLeaveDetails(leaveId);
                // Enable edit mode after modal opens
                setTimeout(() => $('#editLeaveBtn').click(), 500);
            });

            $('#leaveRequestsTable').on('click', '.delete-btn', function(e) {
                e.stopPropagation();
                const leaveId = $(this).data('id');
                window.leaveManager.currentLeaveId = leaveId;
                window.leaveManager.deleteLeave();
            });

            $('#leaveRequestsTable').on('click', '.gatepass-btn', function(e) {
                e.stopPropagation();
                const leaveId = $(this).data('id');
                window.leaveManager.currentLeaveId = leaveId;
                window.leaveManager.viewGatePass();
            });
        });
    </script>
@endpush
