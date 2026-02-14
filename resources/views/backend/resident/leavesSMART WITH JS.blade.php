@extends('resident.layout')

@section('content')
    <style>
        /* Leave Management Specific Styles */

        /* Required field indicator */
        .form-label.required::after {
            content: " *";
            color: #dc3545;
        }

        /* Expandable text */
        .truncate-text {
            cursor: pointer;
        }

        .expand-text,
        .collapse-text {
            font-weight: 500;
            text-decoration: none;
        }

        .expand-text:hover,
        .collapse-text:hover {
            text-decoration: underline;
        }

        /* Timeline styling */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-step {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-step::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 0;
            bottom: -20px;
            border-left: 2px solid #dee2e6;
        }

        .timeline-step.last::before {
            display: none;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            background: white;
            padding: 5px;
        }

        /* Status badges */
        .badge.bg-warning.text-dark {
            color: #212529 !important;
        }

        /* Table row hover effects */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Modal animations */
        .modal.fade .modal-dialog {
            transform: translateY(-50px);
            transition: transform 0.3s ease-out;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        /* Loading animations */
        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .spinner-border {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                gap: 5px;
            }

            .btn-group .btn {
                border-radius: 4px !important;
                margin: 0 !important;
            }

            .timeline {
                padding-left: 20px;
            }

            .timeline-step::before {
                left: -15px;
            }

            .timeline-marker {
                left: -25px;
            }
        }

        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .btn,
            .modal-footer,
            .modal-header .btn-close {
                display: none !important;
            }

            body {
                margin: 0 !important;
                padding: 20px !important;
            }
        }

        /* Custom scrollbar for tables */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Form validation animations */
        .is-invalid {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Success animation for updated values */
        @keyframes highlight {
            0% {
                background-color: rgba(25, 135, 84, 0.2);
            }

            100% {
                background-color: transparent;
            }
        }

        .text-success.highlight {
            animation: highlight 1s ease-out;
        }
    </style>
    <!-- Data Card -->
    <div class="container-fluid py-4">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div
                    class="card border-start-primary border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
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
                    class="card border-start-success border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
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
                    class="card border-start-warning border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
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
                    class="card border-start-danger border-left-3 border-bottom-0 border-end-0 border-top-0 shadow h-100 py-2">
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

        <!-- Leave Request Form Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-plus-circle me-2"></i>Submit New Leave Request
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                    data-bs-target="#leaveFormCollapse">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="collapse show" id="leaveFormCollapse">
                <div class="card-body">
                    <form id="leaveRequestForm" enctype="multipart/form-data">
                        <div id="form-message" class="alert d-none"></div>

                        <div class="row g-3">
                            <!-- Leave Type -->
                            <div class="col-md-6">
                                <label for="type" class="form-label required">Leave Type</label>
                                <select class="form-select" id="type" name="type" required data-bs-toggle="tooltip"
                                    title="Select the type of leave you are requesting">
                                    <option value="">-- Select Type --</option>
                                    <option value="casual">Casual Leave</option>
                                    <option value="medical">Medical Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                    <option value="vacation">Vacation Leave</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="form-text">Choose the appropriate leave category</div>
                            </div>

                            <!-- Reason -->
                            <div class="col-md-6">
                                <label for="reason" class="form-label required">Reason</label>
                                <input type="text" class="form-control" id="reason" name="reason"
                                    placeholder="Enter reason for leave" required>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Provide additional details (optional)"></textarea>
                                <div class="form-text">Maximum 500 characters</div>
                            </div>

                            <!-- Dates -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label required">Start Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label required">End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>

                            <!-- Attachment -->
                            <div class="col-12">
                                <label for="attachment" class="form-label">Attachment</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="attachment" name="attachment"
                                        accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip"
                                        title="Upload supporting documents (medical certificate, etc.)">
                                        <i class="fas fa-question-circle"></i>
                                    </button>
                                </div>
                                <div class="form-text">Max file size: 5MB. Supported formats: PDF, JPG, PNG, DOC</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane me-1"></i> Submit Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leave Requests Table Card -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>My Leave History
                </h6>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="refreshBtn">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item filter-option" href="#" data-status="all">All Status</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item filter-option" href="#" data-status="pending">Pending</a>
                            </li>
                            <li><a class="dropdown-item filter-option" href="#" data-status="approved">Approved</a>
                            </li>
                            <li><a class="dropdown-item filter-option" href="#" data-status="rejected">Rejected</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="leaveRequestList">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Duration</th>
                                <th>Applied On</th>
                                <th>HOD Status</th>
                                <th>Admin Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically -->
                            <tr id="loading-row">
                                <td colspan="8" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="no-requests" class="text-center text-muted py-5 d-none">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h5>No leave requests found</h5>
                    <p class="text-muted">Submit your first leave request using the form above</p>
                </div>

                <!-- Pagination (optional, if implemented in backend) -->
                <div id="pagination-container" class="d-none">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
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
                    <button type="button" class="btn btn-outline-primary" id="downloadReceiptBtn">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </button>
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
    <script>
        // Configuration
        const CONFIG = {
            API_BASE: '/api/resident/leaves',
            TOKEN_KEY: 'token',
            MAX_FILE_SIZE: 5 * 1024 * 1024, // 5MB
            ALLOWED_FILE_TYPES: ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']
        };
    </script>

    @include('backend.resident.js.leave-management.leaveService')
    @include('backend.resident.js.leave-management.leaveFormHandler')
    @include('backend.resident.js.leave-management.leaveTableHandler')
    @include('backend.resident.js.leave-management.receiptHandler')
    @include('backend.resident.js.leave-management.editLeaveHandler')
    @include('backend.resident.js.leave-management.editLeaveHandler')
    @include('backend.resident.js.leave-management.leaveManager')

    {{-- <script src="{{ asset('resources/views/backend/resident/js/leave-management/leaveService.js') }}"></script>
    <script src="{{ asset('resources/views/backend/resident/js/leave-management/leaveFormHandler.js') }}"></script>
    <script src="{{ asset('resources/views/backend/resident/js/leave-management/leaveTableHandler.js') }}"></script>
    <script src="{{ asset('resources/views/backend/resident/js/leave-management/receiptHandler.js') }}"></script>
    <script src="{{ asset('resources/views/backend/resident/js/leave-management/editLeaveHandler.js') }}"></script>
    <script src="{{ asset('resources/views/backend/resident/js/leave-management/leaveManager.js') }}"></script> --}}
@endpush
