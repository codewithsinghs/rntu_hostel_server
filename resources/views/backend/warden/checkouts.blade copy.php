@extends('resident.layout')


@section('content')
    <style>
        div.dt-buttons {
            display: none;
        }

        .toggle-text {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }

        .toggle-text:hover {
            color: darkblue;
        }

        .view-mode input,
        .view-mode select,
        .view-mode textarea {
            background-color: #f8f9fa !important;
            border: 1px solid #e0e0e0 !important;
            pointer-events: none;
        }

        .view-mode label {
            font-weight: 600;
            color: #495057;
        }

        .view-mode .form-control:disabled {
            opacity: 1;
        }
    </style>

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>CheckOut Details</a></div>
    </div>

    <!-- Overview Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Overview</a></div>

                <!-- Overview -->
                {{-- <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Leave Requests</p>
                            <h3 id="total_leaves">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
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
                            <p>Leave Approved</p>
                            <h3 id="total-leaves-taken">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/approved.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Rejected / Cancelled</p>
                            <h3> <span id="total-leaves-rejected"></span> / <span id="total-leaves-cancelled"></span></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/min.png') }}" alt="" />
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
    </section>

    <!-- Table section -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Checkout Info</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Faculty">+ Add
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        Faculty</button> -->
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center"
                        onclick="RecordsModal.openCreate()" aria-label="Apply for leave">
                        {{-- <i class="fa fa-plus me-1" aria-hidden="true"></i> --}}
                        <i class="fa fa-calendar-plus me-1" aria-hidden="true"></i>
                        <span>Initiate Checkout</span>
                    </button>

                </div>

                <div class="table-responsive">
                    <table id="recordsTable" class="table status-table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th width="160">Resident</th>
                                <th>Academic Info</th>
                                <th>Hostel Info</th>
                                <th>Exit Date</th>
                                <th>Submission Date</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <!-- Records Modal -->
    <div class="modal fade" id="recordsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title mpop-title" id="recordsModalTitle">Checkout Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- ===== Clearance Content Start ===== --}}

                    <form id="clearanceForm">

                        @csrf
                        <input type="hidden" id="clearance_checkout_id" name="checkout_id">

                        <!-- Resident Info -->
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div id="clearanceResidentInfo" class="small text-muted">
                                    <!-- Populated dynamically -->
                                </div>
                            </div>
                        </div>

                        <!-- Subscriptions Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Refundable</th>
                                        <th>Status</th>
                                        <th>Penalty</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="clearanceTableBody">
                                    <!-- Rows injected by JS -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="text-right mt-2">
                            <strong>
                                Total Penalty: ₹ <span id="totalPenalty">0.00</span>
                            </strong>
                        </div>

                    </form>

                    {{-- ===== Clearance Content End ===== --}}


                    <!-- View Checkout Info (Read-only) -->
                    <div id="checkoutDashboardView" class="d-none">

                        <!-- ================= STATUS CARD ================= -->
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-body d-flex justify-content-between align-items-center">

                                <div>
                                    <h5 id="dashTitle" class="fw-bold mb-1"></h5>
                                    <p id="dashSubMessage" class="text-muted mb-0"></p>
                                </div>

                                <div class="text-end">
                                    <span id="dashBadge" class="badge px-4 py-2 fs-6 mb-2 d-block"></span>

                                    <div id="dashCTAContainer"></div>
                                </div>

                            </div>
                        </div>

                        <!-- ================= REQUEST DETAILS ================= -->
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-header bg-light fw-semibold">
                                Checkout Request Details
                            </div>
                            <div class="card-body">

                                <div class="row g-4">

                                    <div class="col-md-4">
                                        <small class="text-muted">Resident Name</small>
                                        <div id="d_resident" class="fw-semibold"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted">Scholar Number</small>
                                        <div id="d_scholar" class="fw-semibold"></div>
                                    </div>

                                    <div class="col-md-4">
                                        <small class="text-muted">Requested Exit Date</small>
                                        <div id="d_exit_date" class="fw-semibold"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <small class="text-muted">Request Status</small>
                                        <div id="d_status" class="fw-semibold"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <small class="text-muted">Request Submitted On</small>
                                        <div id="d_submitted_on" class="fw-semibold"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <small class="text-muted">Reason / Description</small>
                                        <div id="d_description" class="fw-semibold"></div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- ================= FINANCIAL SUMMARY ================= -->
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-header bg-light fw-semibold">
                                Financial Settlement Summary
                            </div>
                            <div class="card-body">

                                <div class="row g-4 text-center">

                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <small class="text-muted">Total Charges</small>
                                            <div id="f_total" class="fw-bold fs-5 mt-1"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <small class="text-muted">Amount Paid</small>
                                            <div id="f_paid" class="fw-bold fs-5 text-success mt-1"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <small class="text-muted">Outstanding Dues</small>
                                            <div id="f_pending" class="fw-bold fs-5 text-danger mt-1"></div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="border rounded p-3">
                                            <small class="text-muted">Final Balance</small>
                                            <div id="f_net" class="fw-bold fs-5 mt-1"></div>
                                        </div>
                                    </div>

                                </div>

                                <div id="financialAlert" class="alert mt-4 d-none"></div>

                            </div>
                        </div>


                        <!-- ================= TIMELINE ================= -->
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-light fw-semibold">
                                Checkout Process Status
                            </div>
                            <div class="card-body">
                                <div id="timelineSteps"></div>
                            </div>
                        </div>

                    </div>

                    {{-- <form id="recordsForm" method="POST" action="{{ route('resident.checkout.store') }}" novalidate> --}}
                    <form id="recordsForm" method="POST" novalidate>
                        @csrf

                        <input type="hidden" id="record_id" name="id">
                        {{-- <input type="hidden" id="name" name="name"> --}}
                        <input type="hidden" id="scholar" name="scholar">

                        <input type="hidden" id="resident_id" name="resident_id">

                        <div class="row">
                            <!-- Requested Exit Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Resident Name</label>
                                <input type="text" id="resident_name" name="resident_name" class="form-control"
                                    placeholder="Resident Name">
                            </div>

                            {{-- <div class="mb-3">
                                <label class="form-label">Resident</label>
                                <select class="form-select" name="resident_id" required>
                                    @foreach ($residents as $resident)
                                        <option value="{{ $resident->id }}">
                                            {{ $resident->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <!-- Requested Exit Date -->
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Requested Exit Date</label>
                                <input type="date" id="requested_exit_date" name="requested_exit_date"
                                    class="form-control" min="{{ now()->toDateString() }}" required>
                            </div>

                            <!-- Description Field -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea rows="3" class="form-control" id="description" name="description"
                                    placeholder="Provide details about your checkout..." required></textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="form-text">Please provide a detailed reason (max 500 characters).</small>
                                    <small class="form-text text-muted"><span id="char_count">0</span>/500</small>
                                </div>
                                <div id="description_error" class="invalid-feedback"></div>
                            </div>

                            <!-- Refund Toggle -->
                            <div class="mb-3">
                                <div class="form-check ">
                                    <input class="form-check-input" type="checkbox" id="refundExpected"
                                        name="refund_expected" value="1">
                                    <label class="form-check-label" for="refundExpected">
                                        I expect refund of caution money
                                    </label>
                                </div>
                            </div>

                            <!-- Dates Section -->
                            <div class="d-none mb-3" id="refundSection">
                                <div class="card mb-4 ">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Bank Account Details ( For Refund )</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="bank_name" class="form-label">Bank Name *</label>
                                                <input type="text" class="form-control" id="bank_name"
                                                    name="bank_name" placeholder="Bank Name" required>
                                                <div class="invalid-feedback" id="bank_name_error"></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="ifsc_code" class="form-label">IFSC Code *</label>
                                                <input type="text" class="form-control" id="ifsc_code"
                                                    name="ifsc_code" placeholder="IFSC Code" required>
                                                <div class="invalid-feedback" id="ifsc_code_error"></div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="account_holder" class="form-label">Account Holder Name
                                                    *</label>
                                                <input type="text" class="form-control" id="account_holder"
                                                    placeholder="Account Holder Name" name="account_holder" required>
                                                <div class="invalid-feedback" id="account_holder_error"></div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="account_number" class="form-label">Account Number *</label>
                                                <input type="text" class="form-control" id="account_number"
                                                    placeholder="Bank Account Number" name="account_number" required>
                                                <div class="invalid-feedback" id="account_number_error"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card-footer bg-light">
                                        <small class="mb-0 text-muted">Refund will be provided in above Account in online
                                            mode
                                            only ( If Any )</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Attachment -->
                            <div class="mb-4">
                                <label for="attachment" class="form-label">Supporting Documents (optional)</label>
                                <input type="file" class="form-control" id="attachment" name="attachment"
                                    accept=".pdf,.jpg,.png,.jpeg,.doc,.docx">
                                <small class="form-text">Upload supporting documents like medical certificate, invitation,
                                    etc.
                                    (Max: 5MB)</small>
                                <div class="invalid-feedback" id="attachment_error"></div>
                            </div>

                            <!-- Add this after the description field -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="declaration" name="declaration"
                                        required>
                                    <label class="form-check-label" for="declaration">
                                        I hereby declare that the information provided above is true and correct to the best
                                        of
                                        my knowledge.
                                        I understand that providing false information may lead to disciplinary action.
                                    </label>
                                    <div class="invalid-feedback" id="declaration_error">
                                        You must accept the declaration to proceed
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            {{-- <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>

                                <button type="submit" name="action" value="draft" class="btn btn-outline-info">
                                    Save Draft
                                </button>

                                <button type="submit" name="action" value="submit" class="btn btn-primary">
                                    Submit Checkout
                                </button>
                            </div> --}}

                            {{-- <div class="bottom-btn">
                                <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                                <button type="submit" class="blue" id="recordsSubmitBtn">
                                    Submit Application
                                </button>
                            </div> --}}
                    </form>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fas fa-times me-1"></i>
                            {{-- <i class="fas fa-ban"></i>
                            <i class="fas fa-times-circle"></i>
                            <i class="fas fa-times"></i>
                            <i class="fas fa-window-close"></i> --}}
                            Cancel
                        </button>

                        {{-- <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <i class="fas fa-torii-gate"></i>
                            <i class="fas fa-ticket-alt"></i>
                            <i class="fas fa-id-card"></i>
                            <i class="fas fa-door-open"></i>
                             Cancel
                        </button> --}}

                        <button type="submit" name="action" form="recordsForm" value="draft" id="recordsDraftBtn"
                            class="btn btn-outline-info">
                            {{-- <i class="fas fa-times me-1"></i>
                            <i class="fas fa-pen"></i>
                            <i class="far fa-file-alt"></i> --}}
                            <i class="fas fa-file-pen"></i>
                            Save Draft
                        </button>

                        <button type="submit" name="action" form="recordsForm" value="submit" id="recordsSubmitBtn"
                            class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            {{-- <i class="fas fa-share-square"></i>
                            <i class="fas fa-upload"></i>
                            <i class="fas fa-check"></i> --}}
                            Submit Checkout
                        </button>

                        <button type="button" class="btn btn-danger" onclick="ClearanceModule.submit('rejected')">
                            Reject
                        </button>

                        <button type="button" class="btn btn-success" onclick="ClearanceModule.submit('approved')">
                            Approve Clearance
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Clearance Modal -->
    <div class="modal fade" id="clearanceModal" tabindex="-1" role="dialog" aria-labelledby="clearanceModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" id="clearanceModalLabel">
                        Warden Clearance Inspection
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <!-- Resident Info -->
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div id="clearanceResidentInfo" class="small text-muted">
                                <!-- Dynamic Content -->
                            </div>
                        </div>
                    </div>

                    <!-- Subscriptions -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light font-weight-bold">
                            Issued Subscriptions / Accessories
                        </div>

                        <div class="card-body p-0">

                            <form id="clearanceForm">

                                @csrf
                                <input type="hidden" id="clearance_checkout_id" name="checkout_id">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="20%">Item</th>
                                                <th width="15%">Category</th>
                                                <th width="15%">Refundable</th>
                                                <th width="15%">Status</th>
                                                <th width="15%">Penalty</th>
                                                <th width="20%">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody id="clearanceTableBody">
                                            <!-- Dynamic Rows -->
                                        </tbody>
                                    </table>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="mt-3 text-right">
                        <h6 class="font-weight-bold">
                            Total Penalty: ₹ <span id="totalPenalty">0.00</span>
                        </h6>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cancel
                    </button>

                    <button type="button" class="btn btn-danger" onclick="submitClearance('rejected')">
                        Reject
                    </button>

                    <button type="button" class="btn btn-success" onclick="submitClearance('approved')">
                        Approve Clearance
                    </button>

                </div>

            </div>
        </div>
    </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            $('#refundExpected').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#refundSection').removeClass('d-none');
                } else {
                    $('#refundSection').addClass('d-none');
                    $('#refundSection input').val('');
                }
            });

        });
    </script>

    <script>
        const API_TOKEN = localStorage.getItem('token');
        let CURRENT_USER_ROLE = [];

        $.ajaxSetup({
            beforeSend: function(xhr) {
                const token = localStorage.getItem('token');
                if (token) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                }
                xhr.setRequestHeader('Accept', 'application/json');
            }
        });


        let recordsTable;

        $(document).ready(function() {
            RecordsTable.init();
            RecordsForm.init();
        });

        // DATATABLE

        const RecordsTable = {

            init() {
                // Destroy existing table if present
                if ($.fn.DataTable.isDataTable('#recordsTable')) {
                    $('#recordsTable').DataTable().destroy();
                    $('#recordsTable').empty();
                }

                recordsTable = $('#recordsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    responsive: {
                        details: {
                            type: 'column',
                            target: 0 // first column is the toggle button
                        }
                    },
                    ajax: {
                        url: "{{ route('checkouts.index') }}",

                        dataSrc: function(res) {
                            // CURRENT_USER_ROLE = Array.isArray(res.meta.roles) ?
                            //     res.meta.roles : [res.meta.roles];

                            // ✅ Update Summary Cards
                            if (res && res.summary) {
                                const summary = res.summary;
                                console.log('Leave Summary:', summary);
                                document.getElementById("total_leaves").innerText = summary
                                    .total_leaves ?? 0;
                                document.getElementById("total-leaves-taken").innerText = summary
                                    .approved ?? 0;
                                document.getElementById("total-leaves-pending").innerText = summary
                                    .pending ?? 0;
                                document.getElementById("total-leaves-rejected").innerText = summary
                                    .rejected ?? 0;
                                document.getElementById("total-leaves-cancelled").innerText = summary
                                    .cancelled ?? 0;

                            }

                            return res.data ?? [];

                        }


                    },

                    columns: [{
                            // 🔹 Dedicated control column for responsive toggle
                            data: null,
                            defaultContent: '',
                            className: 'dtr-control',
                            orderable: false,
                            searchable: false,
                            width: '1%'
                        },

                        {
                            data: null,
                            title: '#',
                            orderable: false,
                            searchable: false,
                            width: '1%',
                            render: function(data, type, row, meta) {
                                return meta.settings._iDisplayStart + meta.row + 1;
                            }
                        },

                        {
                            data: 'resident_name', // Add this line
                            title: 'Resident Info',
                            render: function(data, type, row) {
                                let name = row.resident_name || '<span class="text-muted">N/A</span>';
                                let scholarNo = row.scholar_number ||
                                    '<span class="text-muted">N/A</span>';
                                let course = row.course || '<span class="text-muted">N/A</span>';

                                return `
                                        <div>
                                            <strong>${name}</strong><br>
                                            Enrollment: <strong>${scholarNo}</strong><br>
                                           
                                        </div>
                                    `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'course', // Add this line
                            title: 'Academic Info',
                            render: function(data, type, row) {
                                let course = row.course || '<span class="text-muted">N/A</span>';


                                return `
                                        
                                            ${course}
                                         
                                       
                                    `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'hostel', // Add this line
                            title: 'Hostel Info',
                            render: function(data, type, row) {
                                let hostel = row.hostel || '<span class="text-muted">N/A</span>';

                                let room = row.room_number ||
                                    '<span class="text-muted">N/A</span>';
                                let bed = row.bed_number ||
                                    '<span class="text-muted">N/A</span>';
                                return `
                                        <div>
                                            <strong>${hostel}</strong><br>
                                           Room Number: <strong>${room}</strong><br>
                                            Bed Number: <strong>${bed}</strong>
                                        </div>
                                    `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        // {
                        //     data: 'type',
                        //     title: 'Leave Details',
                        //     render: function(data, type, row) {
                        //         function formatExpandable(text) {
                        //             if (!text) return '<span class="text-muted">N/A</span>';
                        //             let safeText = $('<div>').text(text).html(); // escape HTML
                        //             if (safeText.length > 50) {
                        //                 let shortText = safeText.substring(0, 50) + '...';
                        //                 return `
                    //                     <span class="short-text">${shortText}</span>
                    //                     <a href="javascript:void(0)" class="toggle-text">Show more</a>
                    //                     <span class="full-text d-none">${safeText}</span>
                    //                 `;
                        //             }
                        //             return safeText;
                        //         }

                        //         let leaveType = row.type || '<span class="text-muted">N/A</span>';
                        //         let reason = formatExpandable(row.reason);
                        //         let description = formatExpandable(row.description);

                        //         // ✅ Only build attachment link if file exists
                        //         let attachment;
                        //         if (row.attachment && row.attachment.trim() !== '') {
                        //             let encoded = btoa(row.attachment); // Base64 encode
                        //             attachment =
                        //                 `<a href="/files/${encoded}" target="_blank">View Attachment</a>`;
                        //         } else {
                        //             attachment = '<span class="text-muted">No Attachment</span>';
                        //         }

                        //         return `
                    //             <div>
                    //                 <strong>Type:</strong> ${leaveType} <br>
                    //                 <strong>Reason:</strong> ${reason} <br>
                    //                 <strong>Description:</strong> ${description} <br>
                    //                 <strong>Attachment:</strong> ${attachment}
                    //             </div>
                    //         `;
                        //     },
                        //     defaultContent: '<span class="text-muted">N/A</span>'
                        // },

                        {
                            data: 'requested_exit_date',
                            title: 'Exit Date',
                            render: function(data, type, row) {
                                let start = row.requested_exit_date ||
                                    '<span class="text-muted">N/A</span>';

                                return `
                                    <div>
                                        ${start}<br>
                                    </div>
                                `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'created_at',
                            title: 'Submission Date'
                        },

                        {
                            data: 'status',
                            title: 'Status',
                            render: s => badge(s)
                        },

                        {
                            data: null,
                            title: 'Actions',
                            orderable: false,
                            searchable: false,
                            render: function(row) {
                                return renderActionButtons(row);
                            }
                        },


                    ],

                    columnDefs: [{
                            className: 'dtr-control',
                            orderable: false,
                            targets: 0
                        },
                        {
                            responsivePriority: 1,
                            targets: -1
                        },
                        {
                            responsivePriority: 2,
                            targets: 2
                        },
                        {
                            responsivePriority: 3,
                            targets: 3
                        },
                        {
                            responsivePriority: 4,
                            targets: 4
                        },
                        {
                            responsivePriority: 5,
                            targets: 6
                        }
                    ],

                    order: [
                        [1, 'desc']
                    ],

                    dom: `
                                                                                                                                                                                                                                                                                                                                <'row mb-2'
                                                                                                                                                                                                                                                                                                                                    <'col-12 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start'l>
                                                                                                                                                                                                                                                                                                                                    <'col-12 col-md-5 d-flex align-items-center justify-content-center'B>
                                                                                                                                                                                                                                                                                                                                    <'col-12 col-md-4 d-flex align-items-center justify-content-center justify-content-md-end'f>
                                                                                                                                                                                                                                                                                                                                >
                                                                                                                                                                                                                                                                                                                                <'row'<'col-12'tr>>
                                                                                                                                                                                                                                                                                                                                <'row mt-2'
                                                                                                                                                                                                                                                                                                                                    <'col-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>
                                                                                                                                                                                                                                                                                                                                    <'col-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>
                                                                                                                                                                                                                                                                                                                                >
                                                                                                                                                                                                                                                                                                                            `,

                    // buttons: [{
                    //         extend: "copy",
                    //         className: "btn btn-sm btn-outline-primary me-1"
                    //     },
                    //     {
                    //         extend: "csv",
                    //         className: "btn btn-sm btn-outline-success me-1"
                    //     },
                    //     {
                    //         extend: "excel",
                    //         className: "btn btn-sm btn-outline-info me-1"
                    //     },
                    //     {
                    //         extend: "pdfHtml5",
                    //         className: "btn btn-sm btn-outline-danger me-1"
                    //     },
                    //     {
                    //         extend: "print",
                    //         className: "btn btn-sm btn-outline-secondary"
                    //     }
                    // ],

                    // drawCallback: function() {
                    //     // ✅ Force responsive recalc after table render
                    //     if (this.responsive) this.responsive.recalc();
                    // }

                    drawCallback: function() {
                        if (recordsTable && recordsTable.responsive) {
                            recordsTable.responsive.recalc();
                        }
                    },

                    initComplete: function() {
                        bindActionButtonEvents();
                    },


                });

            },

            reload() {
                if (recordsTable) {
                    recordsTable.ajax.reload(null, false);
                    recordsTable.responsive.recalc();
                }
            }
        };

        function renderActionButtons(row) {
            const isPending = row.status === 'draft' || row.status === 'submitted';

            let buttons = `<div class="btn-group btn-group-sm" role="group">`;

            // ✅ Always show View button
            buttons +=
                `
                                                                                                                                                                            <button class="btn btn-outline-primary bg-info view-btn" data-id="${row.id}" 
                                                                                                                                                                                    title="View Record">
                                                                                                                                                                                <i class="fas fa-eye"></i>
                                                                                                                                                                            </button>`;

            // ✏️ Edit button (only if allowed + pending)
            if (row.can_edit && isPending) {
                buttons +=
                    `
                                                                                                                                                                                <button class="btn btn-outline-warning edit-btn" data-id="${row.id}" 
                                                                                                                                                                                        title="Edit Record">
                                                                                                                                                                                    <i class="fas fa-edit"></i>
                                                                                                                                                                                </button>`;
            }

            if (row.can_approve) {
                buttons += `<button class="btn btn-sm btn-success me-1"
                                onclick="approveCheckout(${row.id})">
                                Approve
                            </button>`;
            }

            if (row.can_reject) {
                buttons += `<button class="btn btn-sm btn-danger me-1"
                                onclick="rejectCheckout(${row.id})">
                                Reject
                            </button>`;
            }

            if (row.can_process_payment) {
                buttons += `<button class="btn btn-sm btn-warning me-1"
                                onclick="processPayment(${row.id})">
                                Process Payment
                            </button>`;
            }

            if (row.can_complete) {
                buttons += `<button class="btn btn-sm btn-dark"
                                onclick="completeCheckout(${row.id})">
                                Mark Complete
                            </button>`;
            }
            if (row.can_approve) {
                buttons += `<button class="btn btn-sm btn-primary"
                                onclick="openClearance(${row.id})">
                                Clearance
                            </button>`;
            }

            // 🗑 Delete button (only if allowed + pending)
            // if (row.can_delete && isPending) {
            //     buttons += `
        //                                                                                     <button class="btn btn-outline-danger delete-btn" data-id="${row.id}" 
        //                                                                                             title="Delete Record">
        //                                                                                         <i class="fas fa-trash"></i>
        //                                                                                     </button>`;
            // }

            // 🪪 Gatepass button (only if allowed)
            if (row.can_view_gatepass) {
                buttons +=
                    `
                                                                                                                                        <button class="btn btn-outline-success gatepass-btn" data-id="${row.id}" title="View Gatepass"  onclick="RecordsModal.openGatepass(${row.id})">
                                                                                                                                                                <i class="fas fa-id-card"></i>
                                                                                                                                                            </button>`;

            }

            // if (row.can_cancel && canCancelLeave(row)) {
            //     buttons += `
        //         <button class="btn btn-outline-secondary cancel-btn" data-id="${row.id}" title="Cancel Leave"  onclick="RecordsModal.cancelLeave(${row.id})">
        //                             <i class="fas fa-times-circle"></i>
        //                         </button>`;

            // }

            if (row.can_cancel && canCancelLeave(row)) {
                buttons +=
                    `
                                                                                                                                            <button class="btn btn-outline-secondary cancel-btn" data-id="${row.id}" title="Cancel Leave"  onclick="RecordsModal.cancelLeave(${row.id})">
                                                                                                                                                                <i class="fas fa-ban"></i>
                                                                                                                                                            </button>`;

            }

            buttons += `</div>`;

            return buttons || '<span class="text-muted">No Action</span>';
        }

        function canCancelLeave(row) {
            const startDate = new Date(row.start_date);
            const today = new Date();
            return startDate > today;
        }

        function bindActionButtonEvents() {
            const actions = {
                'view-btn': (id) => RecordsModal.openView(id),
                'edit-btn': (id) => RecordsModal.openEdit(id),
                'delete-btn': (id) => RecordsModal.delete(id),
                'gatepass-btn': (id) => RecordsModal.openGatepass(id),

                'cancel-btn': (id) => RecordsModal.cancelLeave(id), // 👈 added cancel
            };

            // Attach one delegated handler for all
            $(document).on('click', '.view-btn, .edit-btn, .delete-btn, .gatepass-btn .cancel-btn', function() {
                const id = $(this).data('id');
                const classes = $(this).attr('class').split(/\s+/);

                // Find which action matches
                for (const cls of classes) {
                    if (actions[cls]) {
                        actions[cls](id);
                        break;
                    }
                }
            });
        }

        // BADGE HELPER     
        function badge($status) {
            switch ($status) {
                case 'draft':
                    return '<span class="badge bg-secondary">Draft</span>';
                case 'submitted':
                    return '<span class="badge bg-info text-dark">Submitted</span>';
                case 'in_clearance':
                    return '<span class="badge bg-warning text-dark">In Clearance</span>';
                case 'financial_review':
                    return '<span class="badge bg-primary">Financial Review</span>';
                case 'payment_pending':
                    return '<span class="badge bg-warning text-dark">Payment Pending</span>';
                case 'refund_pending':
                    return '<span class="badge bg-warning text-dark">Refund Pending</span>';
                case 'ready_for_exit':
                    return '<span class="badge bg-success">Ready for Exit</span>';
                case 'completed':
                    return '<span class="badge bg-success">Completed</span>';
                case 'cancelled':
                    return '<span class="badge bg-danger">Cancelled</span>';
                default:
                    return '<span class="badge bg-dark">Unknown</span>';
            }
        }

        function formatDateInput(dateStr) {
            const d = new Date(dateStr);

            if (isNaN(d)) {
                console.log("Invalid date string:", dateStr);
                return "";
            }

            // Format using local year, month, day
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, "0");
            const day = String(d.getDate()).padStart(2, "0");

            const formatted = `${year}-${month}-${day}`;
            console.log("Formatted date for input:", formatted);
            return formatted;
        }

        // function approveCheckout(id) {

        //     $('#actionTitle').text("Approve Checkout");
        //     $('#actionMessage').text("Are you sure you want to approve this checkout request?");
        //     $('#confirmActionBtn')
        //         .removeClass()
        //         .addClass('btn btn-success')
        //         .text('Approve')
        //         .off()
        //         .click(() => submitAction(id, 'approve'));

        //     $('#actionModal').modal('show');
        // }

        // function openInspection(id) {

        //     $.get(`/admin/checkout/${id}/inspection`, function(res) {

        //         const subs = res.data.subscriptions;

        //         let rows = '';

        //         subs.forEach(sub => {

        //             rows += `
    //         <tr>
    //             <td>${sub.name}</td>
    //             <td>${sub.category}</td>
    //             <td>₹ ${sub.refundable_amount}</td>
    //             <td>
    //                 <select class="form-select"
    //                         data-sub="${sub.id}">
    //                     <option value="">Select</option>
    //                     <option value="cleared">Cleared</option>
    //                     <option value="damaged">Damaged</option>
    //                     <option value="missing">Missing</option>
    //                 </select>
    //             </td>
    //             <td>
    //                 <input type="number"
    //                        class="form-control penalty-input"
    //                        data-sub="${sub.id}"
    //                        min="0"
    //                        value="0">
    //             </td>
    //             <td>
    //                 <input type="text"
    //                        class="form-control"
    //                        data-sub="${sub.id}">
    //             </td>
    //         </tr>
    //     `;
        //         });

        //         $('#inspectionTableBody').html(rows);

        //         $('#inspectionModal').modal('show');
        //     });
        // }

        // function submitInspection(action) {

        //     let inspectionData = [];

        //     $('#inspectionTableBody tr').each(function() {

        //         const subId = $(this).find('select').data('sub');
        //         const status = $(this).find('select').val();
        //         const penalty = $(this).find('.penalty-input').val();
        //         const remark = $(this).find('input[type="text"]').val();

        //         inspectionData.push({
        //             subscription_id: subId,
        //             status: status,
        //             penalty: penalty,
        //             remark: remark
        //         });
        //     });

        //     $.ajax({
        //         url: `/admin/checkout/submit-inspection`,
        //         type: 'POST',
        //         data: {
        //             checkout_id: CURRENT_CHECKOUT_ID,
        //             action: action,
        //             inspections: inspectionData,
        //             overall_remark: $('#overallRemark').val(),
        //         },
        //         success: function() {
        //             $('#inspectionModal').modal('hide');
        //             $('#checkoutTable').DataTable().ajax.reload();
        //             Swal.fire('Success', 'Inspection submitted.', 'success');
        //         }
        //     });
        // }





        // MODAL HANDLER     
        const RecordsModal = {

            // setMode(mode) {
            //     //   RecordsForm.init();
            //     const isView = mode === 'view';
            // $('#recordsForm input, #recordsForm select')
            //     .prop('readonly', isView)
            //     .prop('disabled', isView);
            // $('#recordsSubmitBtn').toggle(!isView);
            // $('#recordsModalTitle').text({
            //     add: 'Request Checkout',
            //     edit: 'Edit Checkout',
            //     view: 'View Checkout'
            // } [mode]);
            // },
            setMode(mode) {
                this.mode = mode;
                const isView = mode === 'view';

                $('#recordsForm input, #recordsForm select')
                    .prop('readonly', isView)
                    .prop('disabled', isView);
                $('#recordsSubmitBtn').toggle(!isView);
                $('#recordsDraftBtn').toggle(!isView);

                if (mode === 'view') {
                    $('.modal-title').text('Checkout Details');
                } else if (mode === 'edit') {
                    $('.modal-title').text('Edit Checkout');
                    $('#checkoutLifecycleSection').addClass('d-none');
                    $('#recordFormWrapper').removeClass('view-mode');
                } else {
                    $('.modal-title').text('New Checkout Request');
                    $('#checkoutLifecycleSection').addClass('d-none');
                    $('#recordFormWrapper').removeClass('view-mode');
                }
            },

            openCreate() {
                RecordsForm.reset();
                this.setMode('add');

                $('#checkoutDashboardView').addClass('d-none');
                $('#recordsForm').show();


                // Clear form fields when creating new record
                $('#resident_id').val('161');
                $('#name').val('Dummy');
                $('#scholar').val('AUJAY');
                $('#apply_date').val('');


                // FacultySelect.load();
                // $('#department_id').html('<option value="">Select Department</option>');
                $('#recordsModal').modal('show');
            },

            openEdit(id) {
                RecordsForm.reset();
                this.setMode('edit');
                const url = "{{ route('checkout.show', ':id') }}".replace(':id', id);

                $.ajax({
                    url,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    success: res => {
                        $('#checkoutDashboardView').addClass('d-none');

                        $('#recordsForm').show();
                        $('#recordsDraftBtn').addClass('d-none');

                        const c = res.data;
                        console.log(c);

                        // Basic resident + leave info
                        $('#record_id').val(c.id);
                        $('#resident_name').val(c.resident.name);
                        $('#scholar').val(c.resident.scholar_no);
                        $('#description').val(c.checkout.description);
                        // Normalize dates for input fields 
                        // $('#requested_exit_date').val(formatDateInput(c.checkout.requested_exit_date));
                        $('#requested_exit_date').val(formatDateForInput(c.checkout.requested_exit_date));

                        // Show pretty display if needed 
                        // $('#requested_exit_date').val(formatDateForDisplay(c.requested_exit_date));
                        // Duration auto-calc 
                        // $('#duration').val(c.duration ?? calculateDuration(c.start_date, c
                        //     .end_date));

                        $('#status').val(c.checkout.status);

                        // Populate approvals from JSON
                        if (c.approvals && Array.isArray(c.approvals)) {
                            const hod = c.approvals.find(a => a.role.toLowerCase() === 'hod');
                            const admin = c.approvals.find(a => a.role.toLowerCase() === 'admin');

                            if (hod) {
                                $('#hod_status').val(hod.status);
                                $('#hod_remarks').val(hod.remarks);
                                $('#hod_action_at').val(hod.action_at);
                            }

                            if (admin) {
                                $('#admin_status').val(admin.status);
                                $('#admin_remarks').val(admin.remarks);
                                $('#admin_action_at').val(admin.action_at);
                            }
                        }

                        $('#recordsModal').modal('show');
                    },
                    error: () => Swal.fire('Error', 'Unable to load leave record', 'error')
                });
            },

            openView(id) {

                RecordsForm.reset();
                this.setMode('view');

                const url = "{{ route('checkout.show', ':id') }}".replace(':id', id);

                $.ajax({
                    url,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    success: res => {

                        $('#recordsForm').hide();
                        $('#checkoutDashboardView').removeClass('d-none');


                        // renderCheckoutView(res.data);
                        renderCheckoutDashboard(res.data);


                        //         const c = res.data;
                        //         const checkout = c.checkout;
                        //         // const financial = c.financial_summary;
                        //         const financial = c.status_view.financial_summary;
                        //         console.log(financial);
                        //         const status_view = c.status_view;

                        //         // ========== BASIC INFO ==========
                        //         $('#record_id').val(c.id);
                        //         $('#resident_name').val(c.resident.name);
                        //         $('#scholar').val(c.resident.scholar_no);
                        //         $('#description').val(checkout.description ?? '');
                        //         $('#requested_exit_date').val(formatDateForInput(checkout.requested_exit_date));
                        //         $('#status').val(checkout.stage);

                        //         // ========== ENABLE VIEW MODE UI ==========
                        //         $('#recordFormWrapper').addClass('view-mode');
                        //         $('#recordForm input, #recordForm select, #recordForm textarea')
                        //             .prop('disabled', true);

                        //         // ========== SHOW LIFECYCLE PANEL ==========
                        //         $('#checkoutLifecycleSection').removeClass('d-none');

                        //         $('#viewStatusLabel').text(checkout.label);
                        //         $('#viewStatusDescription').text(checkout.description);

                        //         $('#viewStatusBadge')
                        //             .removeClass()
                        //             .addClass('badge bg-' + checkout.badge)
                        //             .text(checkout.label);

                        //         // ========== FINANCIAL DATA ==========
                        //         $('#viewTotalInvoices').text('₹ ' + financial.total_invoices);
                        //         $('#viewTotalPaid').text('₹ ' + financial.total_paid);
                        //         $('#viewPendingDues').text('₹ ' + financial.pending_dues);
                        //         $('#viewNetPosition').text('₹ ' + financial.net_position);

                        //         // ========== TIMELINE ==========
                        //         let timelineHtml = '';

                        //         status_view.timeline.forEach(step => {
                        //             timelineHtml += `
                    //     <li class="list-group-item d-flex justify-content-between align-items-center">
                    //         <span>${step.step}</span>
                    //         <span class="badge bg-${step.completed ? 'success' : 'secondary'}">
                    //             ${step.completed ? 'Completed' : 'Pending'}
                    //         </span>
                    //     </li>
                    // `;
                        //         });

                        //         $('#viewTimeline').html(timelineHtml);

                        $('#recordsModal').modal('show');
                    },
                    error: () => Swal.fire('Error', 'Unable to load record', 'error')
                });
            },
            // openView(id) {
            //     RecordsForm.reset();
            //     this.setMode('view');
            //     const url = "{{ route('checkout.show', ':id') }}".replace(':id', id);
            //     $.ajax({
            //         url,
            //         headers: {
            //             Authorization: 'Bearer ' + localStorage.getItem('token'),
            //             Accept: 'application/json'
            //         },
            //         success: res => {
            //             const c = res.data;
            //             // Basic resident + leave info
            //              // Basic resident + leave info
            //             $('#record_id').val(c.id);
            //             $('#resident_name').val(c.resident.name);
            //             $('#scholar').val(c.resident.scholar_no);
            //             $('#description').val(c.checkout.description);
            //             // Normalize dates for input fields 
            //             // $('#requested_exit_date').val(formatDateInput(c.checkout.requested_exit_date));
            //             $('#requested_exit_date').val(formatDateForInput(c.checkout.requested_exit_date));

            //             // Show pretty display if needed 
            //             // $('#requested_exit_date').val(formatDateForDisplay(c.requested_exit_date));
            //             // Duration auto-calc 
            //             // $('#duration').val(c.duration ?? calculateDuration(c.start_date, c
            //             //     .end_date));

            //             $('#status').val(c.checkout.status);



            //             // disable inputs for view mode 
            //             $('#recordForm input, #recordForm select, #recordForm textarea')
            //                 .prop('disabled',
            //                     true);

            //             $('#recordsModal').modal('show');
            //         },
            //         error: () => Swal.fire('Error', 'Unable to load faculty', 'error')
            //     });
            // },

            delete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(result => {

                    if (!result.isConfirmed) return;

                    const url = "{{ route('resident.leaves.destroy', ':id') }}".replace(':id',
                        id);

                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: res => {
                            Swal.fire(
                                'Deleted!',
                                res.message ?? 'Course deleted successfully',
                                'success'
                            );

                            RecordsTable.reload();
                        },

                        error: xhr => {
                            let message = 'Unable to delete course';

                            if (xhr.status === 404) {
                                message = 'Course not found';
                            } else if (xhr.status === 409) {
                                message = xhr.responseJSON?.message ??
                                    'Course is in use and cannot be deleted';
                            } else if (xhr.responseJSON?.message) {
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire('Error', message, 'error');
                        }
                    });
                });
            },

            cancelLeave(id) {
                // console.log('Cancel leave called for ID:', id);
                Swal.fire({
                    title: 'Cancel Leave Request?',
                    text: 'Are you sure you want to cancel this leave request?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it',
                    cancelButtonText: 'No'
                }).then(result => {

                    if (!result.isConfirmed) return;

                    const url = "{{ route('resident.leaves.cancel', ':id') }}".replace(':id',
                        id);
                    //   const response = await fetch(`${CONFIG.API_BASE}/${this.currentLeaveId}/cancel`,

                    Swal.fire({
                        title: 'Cancelling...',
                        text: 'Please wait',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            // _method: 'PUT', // Laravel expects PUT for update
                            status: 'cancelled' // 👈 mark as cancelled
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content'),
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cancelled!',
                                text: res.message ??
                                    'Leave request has been cancelled.',
                                timer: 3000,
                                showConfirmButton: false
                            });

                            $('#viewEditLeaveModal').modal('hide');
                            RecordsTable.reload();
                        },
                        error: xhr => {
                            let message = 'Unable to cancel leave request';

                            if (xhr.status === 404) {
                                message = 'Leave not found';
                            } else if (xhr.status === 409) {
                                message = xhr.responseJSON?.message ??
                                    'Leave cannot be cancelled at this stage';
                            } else if (xhr.responseJSON?.message) {
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire('Error', message, 'error');
                        }
                    });
                });
            },

            openGatepass(id) {
                // const row = button.closest("tr");
                // const request = JSON.parse(row.getAttribute("data-request").replace(/&apos;/g, "'"));

                // const row = button.closest("tr");

                // const leaveId = row.getAttribute("data-id"); // ✅ get ID from row
                // const leaveId = button.getAttribute("data-id");

                const leaveId = id;

                // try {
                // const apiUrl = `/api/resident/leaves/${leaveId}`;

                // fetch(apiUrl, {
                //         method: "GET",
                //         headers: {
                //             'Authorization': `Bearer ${localStorage.getItem('token')}`,
                //             'Accept': 'application/json'
                //         },
                //     })
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
                        receiptContainer.innerHTML =
                            `
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
                                                                                                                                            <div class="col-6"><small class="text-muted">Name</small><div>${data.resident_name || ''}</div></div>
                                                                                                                                            <div class="col-6"><small class="text-muted">Scholar No.</small><div>${data.resident_scholar_no || ''}</div></div>
                                                                                                                                            <div class="col-6"><small class="text-muted">Mobile</small><div>${data.resident_mobile || 'N/A'}</div></div>
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
                                                                                                                                            ${data.qr_code_base64 ? 
                                                                                                                                                `<img src="data:image/png;base64,${data.qr_code_base64}" class="img-fluid mb-2" style="max-width:180px;" alt="QR">` : 
                                                                                                                                                '<div class="text-muted py-3"><i class="fas fa-qrcode fa-2x"></i><p>No QR Code</p></div>'
                                                                                                                                            }
                                                                                                                                            <!-- ${data.token ? `
                                                                                                                                                                        <div class="small mt-2">
                                                                                                                                                                            <small class="text-muted d-block">Token</small>
                                                                                                                                                                            <code class="bg-light p-1 rounded">${data.token}</code>
                                                                                                                                                                        </div>
                                                                                                                                                                    ` : ''} 
                                                                                                                                                        -->
                                                                                                                                            <p class="small text-muted mt-2">Scan to verify</p>
                                                                                                                                        </div>

                                                                                                                                        ${data.status ? `
                                                                                                                                                                <div class="mt-3">
                                                                                                                                                                    <h5 class="border-bottom pb-2"><i class="fas fa-check-circle me-2"></i>Status</h5>
                                                                                                                                                                    <div class="row g-2">
                                                                                                                                                                        <div class="col-12 text-center">
                                                                                                                                                                            <small class="text-muted ">Approval Status</small>
                                                                                                                                                                            <div>${getStatusBadge(data.status)}</div>
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

                        // button.innerHTML = originalButtonHTML;
                        // button.disabled = false;

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
                        // button.innerHTML = originalButtonHTML;
                        // button.disabled = false;
                    });

            },

          


        };

        function   openClearance(id) {

                const url = "{{ route('checkout.clearance', ':id') }}".replace(':id', id);

                $.ajax({
                    url,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    success: function(res) {

                        // Hide other sections
                        $('#recordsForm').hide();
                        $('#checkoutDashboardView').addClass('d-none');

                        // Show clearance section
                        $('#clearanceSection').removeClass('d-none');

                        // Populate data
                        $('#clearance_checkout_id').val(res.checkout_id);

                        $('#clearanceResidentInfo').html(`
                            <strong>${res.resident.name}</strong><br>
                            Scholar No: ${res.resident.scholar_no}<br>
                            Room: ${res.resident.room}
                        `);

                        // Render table...
                        renderClearanceRows(res.subscriptions);

                        // IMPORTANT: Open same working modal
                        $('#recordsModal').modal('show');
                    },
                    error: function() {
                        Swal.fire('Error', 'Unable to load clearance', 'error');
                    }
                });
            }

            function renderClearanceRows(subscriptions) {

    let rows = '';

    if (!subscriptions || subscriptions.length === 0) {
        rows = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No subscriptions found
                </td>
            </tr>
        `;
    } else {

        subscriptions.forEach(function(sub) {

            rows += `
                <tr>
                    <td>${sub.service_name || '-'}</td>
                    <td>${sub.category || '-'}</td>
                    <td>₹ ${sub.refundable || 0}</td>

                    <td>
                        <select class="form-control status-select"
                                data-id="${sub.id}"
                                data-refundable="${sub.refundable || 0}">
                            <option value="cleared">Cleared</option>
                            <option value="penalty">Penalty</option>
                        </select>
                    </td>

                    <td>
                        <input type="number"
                               class="form-control penalty-input"
                               value="0"
                               min="0">
                    </td>

                    <td>
                        <input type="text"
                               class="form-control remarks-input">
                    </td>
                </tr>
            `;
        });
    }

    $('#clearanceTableBody').html(rows);
}


        // function openClearance(id) {

        //     $('#clearanceForm')[0].reset();

        //     const url = "{{ route('checkout.clearance', ':id') }}".replace(':id', id);

        //     $.ajax({
        //         url: url,
        //         headers: {
        //             Authorization: 'Bearer ' + localStorage.getItem('token'),
        //             Accept: 'application/json'
        //         },
        //         success: function(res) {

        //             // ========== BASIC INFO ==========
        //             $('#clearance_checkout_id').val(res.checkout_id);

        //             $('#clearanceResidentInfo').html(`
    //         <strong>${res.resident.name}</strong><br>
    //         Scholar No: ${res.resident.scholar_no}<br>
    //         Room: ${res.resident.room}
    //     `);

        //             // ========== SUBSCRIPTIONS ==========
        //             let rows = '';

        //             res.subscriptions.forEach(sub => {

        //                 rows += `
    //             <tr>
    //                 <td>${sub.service_name}</td>
    //                 <td>${sub.category ?? '-'}</td>
    //                 <td>₹ ${sub.refundable ?? 0}</td>

    //                 <td>
    //                     <select class="form-select status-select"
    //                             data-id="${sub.id}"
    //                             data-refundable="${sub.refundable ?? 0}">
    //                         <option value="cleared">Cleared</option>
    //                         <option value="penalty">Penalty</option>
    //                     </select>
    //                 </td>

    //                 <td>
    //                     <input type="number"
    //                            class="form-control penalty-input"
    //                            value="0"
    //                            min="0">
    //                 </td>

    //                 <td>
    //                     <input type="text"
    //                            class="form-control remarks-input">
    //                 </td>
    //             </tr>
    //         `;
        //             });

        //             $('#clearanceTableBody').html(rows);

        //             calculateTotalPenalty();

        //             // ========== OPEN MODAL ==========
        //             $('#clearanceModal').modal('show');
        //         },
        //         error: function() {
        //             Swal.fire('Error', 'Unable to load clearance data', 'error');
        //         }
        //     });
        // }

        // $(document).on('change', '.status-select', function() {

        //     let refundable = parseFloat($(this).data('refundable')) || 0;
        //     let row = $(this).closest('tr');
        //     let penaltyField = row.find('.penalty-input');

        //     if ($(this).val() === 'penalty') {
        //         penaltyField.val(refundable);
        //     } else {
        //         penaltyField.val(0);
        //     }

        //     calculateTotalPenalty();
        // });

        // $(document).on('input', '.penalty-input', calculateTotalPenalty);

        // function calculateTotalPenalty() {

        //     let total = 0;

        //     $('.penalty-input').each(function() {
        //         total += parseFloat($(this).val()) || 0;
        //     });

        //     $('#totalPenalty').text(total.toFixed(2));
        // }

        // function submitClearance(action) {

        //     const checkoutId = $('#clearance_checkout_id').val();
        //     const url = "{{ route('checkout.clearance.submit') }}";

        //     let items = {};

        //     $('#clearanceTableBody tr').each(function() {

        //         const subId = $(this).find('.status-select').data('id');

        //         items[subId] = {
        //             status: $(this).find('.status-select').val(),
        //             amount: $(this).find('.penalty-input').val(),
        //             remarks: $(this).find('.remarks-input').val()
        //         };
        //     });

        //     $.ajax({
        //         url: url,
        //         method: "POST",
        //         headers: {
        //             Authorization: 'Bearer ' + localStorage.getItem('token'),
        //             Accept: 'application/json'
        //         },
        //         data: {
        //             _token: "{{ csrf_token() }}",
        //             checkout_id: checkoutId,
        //             action: action,
        //             items: items
        //         },
        //         success: function(res) {

        //             $('#clearanceModal').modal('hide');
        //             $('#checkoutTable').DataTable().ajax.reload(null, false);

        //             Swal.fire('Success', res.message, 'success');
        //         },
        //         error: function(xhr) {

        //             if (xhr.responseJSON?.errors) {
        //                 Swal.fire('Validation Error', 'Please check inputs.', 'error');
        //             } else {
        //                 Swal.fire('Error', 'Something went wrong.', 'error');
        //             }
        //         }
        //     });
        // }


        // window.ClearanceModule = (function() {

        //     let tableSelector = '#clearanceTableBody';
        //     let modalSelector = '#clearanceModal';

        //     function open(id) {

        //         if (!id) {
        //             console.error('Checkout ID missing');
        //             return;
        //         }

        //         const urlTemplate = "{{ route('checkout.clearance', ':id') }}";
        //         const url = urlTemplate.replace(':id', id);

        //         $.ajax({
        //             url: url,
        //             headers: {
        //                 Authorization: 'Bearer ' + localStorage.getItem('token'),
        //                 Accept: 'application/json'
        //             },
        //             success: function(res) {

        //                 if (!res || !res.checkout_id) {
        //                     Swal.fire('Error', 'Invalid response from server', 'error');
        //                     return;
        //                 }

        //                 render(res);
        //                 calculateTotal();

        //                 $(modalSelector).modal('show');
        //             },
        //             error: function(xhr) {

        //                 console.error(xhr);

        //                 Swal.fire(
        //                     'Error',
        //                     xhr.responseJSON?.message || 'Unable to load clearance',
        //                     'error'
        //                 );
        //             }
        //         });
        //     }

        //     function render(res) {

        //         $('#clearance_checkout_id').val(res.checkout_id);

        //         $('#clearanceResidentInfo').html(`
        //     <strong>${res.resident?.name || '-'}</strong><br>
        //     Scholar No: ${res.resident?.scholar_no || '-'}<br>
        //     Room: ${res.resident?.room || '-'}
        // `);

        //         let rows = '';

        //         if (!res.subscriptions || res.subscriptions.length === 0) {
        //             rows = `
        //         <tr>
        //             <td colspan="6" class="text-center text-muted">
        //                 No subscriptions found
        //             </td>
        //         </tr>
        //     `;
        //         } else {

        //             res.subscriptions.forEach(function(sub) {

        //                 rows += `
        //             <tr>
        //                 <td>${sub.service_name || '-'}</td>
        //                 <td>${sub.category || '-'}</td>
        //                 <td>₹ ${sub.refundable || 0}</td>

        //                 <td>
        //                     <select class="form-control status-select"
        //                             data-id="${sub.id}"
        //                             data-refundable="${sub.refundable || 0}">
        //                         <option value="cleared">Cleared</option>
        //                         <option value="penalty">Penalty</option>
        //                     </select>
        //                 </td>

        //                 <td>
        //                     <input type="number"
        //                            class="form-control penalty-input"
        //                            value="0"
        //                            min="0">
        //                 </td>

        //                 <td>
        //                     <input type="text"
        //                            class="form-control remarks-input">
        //                 </td>
        //             </tr>
        //         `;
        //             });
        //         }

        //         $(tableSelector).html(rows);
        //     }

        //     function calculateTotal() {

        //         let total = 0;

        //         $('.penalty-input').each(function() {
        //             total += parseFloat($(this).val()) || 0;
        //         });

        //         $('#totalPenalty').text(total.toFixed(2));
        //     }

        //     function submit(action) {

        //         const checkoutId = $('#clearance_checkout_id').val();

        //         if (!checkoutId) {
        //             Swal.fire('Error', 'Checkout ID missing', 'error');
        //             return;
        //         }

        //         const url = "{{ route('checkout.clearance.submit') }}";

        //         let items = {};

        //         $(tableSelector + ' tr').each(function() {

        //             const select = $(this).find('.status-select');

        //             if (!select.length) return;

        //             const subId = select.data('id');

        //             items[subId] = {
        //                 status: select.val(),
        //                 amount: $(this).find('.penalty-input').val() || 0,
        //                 remarks: $(this).find('.remarks-input').val() || ''
        //             };
        //         });

        //         $.ajax({
        //             url: url,
        //             method: 'POST',
        //             headers: {
        //                 Authorization: 'Bearer ' + localStorage.getItem('token'),
        //                 Accept: 'application/json'
        //             },
        //             data: {
        //                 _token: "{{ csrf_token() }}",
        //                 checkout_id: checkoutId,
        //                 action: action,
        //                 items: items
        //             },
        //             success: function(res) {

        //                 $(modalSelector).modal('hide');

        //                 if ($.fn.DataTable.isDataTable('#checkoutTable')) {
        //                     $('#checkoutTable').DataTable().ajax.reload(null, false);
        //                 }

        //                 Swal.fire('Success', res.message || 'Processed successfully', 'success');
        //             },
        //             error: function(xhr) {

        //                 console.error(xhr);

        //                 Swal.fire(
        //                     'Error',
        //                     xhr.responseJSON?.message || 'Something went wrong',
        //                     'error'
        //                 );
        //             }
        //         });
        //     }

        //     // Events
        //     $(document).on('change', '.status-select', function() {

        //         const refundable = parseFloat($(this).data('refundable')) || 0;
        //         const row = $(this).closest('tr');
        //         const penaltyInput = row.find('.penalty-input');

        //         if ($(this).val() === 'penalty') {
        //             penaltyInput.val(refundable);
        //         } else {
        //             penaltyInput.val(0);
        //         }

        //         calculateTotal();
        //     });

        //     $(document).on('input', '.penalty-input', calculateTotal);

        //     return {
        //         open: open,
        //         submit: submit
        //     };

        // })();


        // FORM HANDLER     
        const RecordsForm = {

            init() {
                // console.log('RecordsForm initialized');
                $('#recordsForm').on('submit', this.submit.bind(this));

                // Prevent past dates in date inputs
                this.preventPastDates();

                // Handle attachment requirement based on type
                // $('#recordsForm').on('change', '[name="type"], [name="reason"], [name="leave_category"]',
                $('#recordsForm').on('change', '[name="type"], [name="reason"]',
                    this.checkAttachmentRequirement.bind(this));

                // Initialize on modal show
                $('#recordsModal').on('show.bs.modal', () => {
                    this.checkAttachmentRequirement();
                });
            },

            // Add this method
            preventPastDates() {
                const today = new Date().toISOString().split('T')[0];
                $('[name="start_date"]').attr('min', today);

                // Update end date min when start date changes
                $('[name="start_date"]').on('change', function() {
                    $('[name="end_date"]').attr('min', $(this).val());
                });
            },

            // Add this method
            checkAttachmentRequirement() {
                const type = $('[name="type"]').val();
                const reason = $('[name="reason"]').val();
                // const category = $('[name="leave_category"]').val();
                const $attachmentInput = $('[name="attachment"]');
                const $requirementNote = $('#attachment_requirement_note');
                const $requirementText = $('#attachment_required_text');

                // Cases where attachment is mandatory
                const mandatoryCases = {
                    type: ['medical', 'official', 'emergency', 'semester_break', 'special'],
                    reason: ['doctor_appointment', 'hospital_visit', 'accident', 'document_work',
                        'govt_office'
                    ],
                    // category: ['semester_break', 'special']
                };

                let isRequired = false;
                let requirementMessage = '';

                // Check if attachment is required
                if (mandatoryCases.type.includes(type)) {
                    isRequired = true;
                    requirementMessage = 'Attachment is required for this type of leave.';
                }

                if (mandatoryCases.reason.includes(reason)) {
                    isRequired = true;
                    requirementMessage = 'Supporting document is required for this reason.';
                }

                // if (mandatoryCases.category.includes(category)) {
                //     isRequired = true;
                //     if (category === 'semester_break') {
                //         requirementMessage = 'Parent/Guardian consent letter is required for semester break leave.';
                //     } else if (category === 'special') {
                //         requirementMessage = 'Special permission requires supporting documents.';
                //     }
                // }

                // Update UI
                if (isRequired) {
                    $attachmentInput.prop('required', true);
                    $requirementText.text(requirementMessage);
                    $requirementNote.slideDown(300);
                    $attachmentInput.closest('.mb-4').find('small')
                        .html('Upload supporting documents <span class="text-danger">(Required)</span>');
                } else {
                    $attachmentInput.prop('required', false);
                    $requirementNote.slideUp(300);
                    $attachmentInput.closest('.mb-4').find('small')
                        .text(
                            'Upload supporting documents like medical certificate, invitation, etc. (Max: 5MB)'
                        );
                }
            },


            submit(e) {
                e.preventDefault();
                if (!this.validate()) return;

                const id = $('#record_id').val();
                const url = id ?
                    "{{ route('resident.leaves.update', ':id') }}".replace(':id', id) :
                    "{{ route('checkout.initiate') }}";

                const formData = new FormData($('#recordsForm')[0]);

                // Override reason before sending 
                // const selectedReason = this.getSelectedReason();
                // formData.set('reason', selectedReason); // replaces "other" with custom text

                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: res => {
                        Swal.fire('Success', res.message, 'success');
                        $('#recordsModal').modal('hide');
                        RecordsTable.reload();
                    },
                    error: xhr => this.handleError(xhr)
                });
            },

            validate() {
                let ok = true;
                this.clearErrors();

                // Validate description (required field)
                // const description = $('[name="description"]').val();
                // if (!description || description.trim() === '') {
                //     this.error('description', 'Description is required');
                //     ok = false;
                // } else if (description.length > 500) {
                //     this.error('description', 'Description cannot exceed 500 characters');
                //     ok = false;
                // }

                // Validate attachment when required
                const $attachmentInput = $('[name="attachment"]');
                if ($attachmentInput.prop('required')) {
                    const files = $attachmentInput[0].files;
                    if (!files || files.length === 0) {
                        this.error('attachment', 'Supporting document is required for this leave type');
                        ok = false;
                    } else {
                        // Validate file size (5MB max)
                        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                        if (files[0].size > maxSize) {
                            this.error('attachment', 'File size exceeds 5MB limit');
                            ok = false;
                        }

                        // Validate file type
                        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
                        if (!allowedTypes.includes(files[0].type)) {
                            this.error('attachment', 'Only PDF, JPEG, and PNG files are allowed');
                            ok = false;
                        }
                    }
                }


                // Validate checkbox fields (like declaration)
                $('#recordsForm input[type="checkbox"][required]').each((_, el) => {
                    const $el = $(el);
                    const name = $el.attr('name');

                    if (!$el.prop('checked')) {
                        this.error(name, $el.data('error-message') || 'This field is required');
                        ok = false;
                    }
                });

                $('#recordsForm [name]').each((_, el) => {
                    const $el = $(el);
                    const name = $el.attr('name');
                    if ($el.prop('disabled') || !$el.is(':visible')) return;
                    if (!$el.prop('required')) return;

                    const type = ($el.attr('type') || '').toLowerCase();
                    const tag = el.tagName.toLowerCase();
                    let value = $el.val();

                    if (type === 'file' && (!$el[0].files || !$el[0].files.length) && $el.data(
                            'existing') !==
                        true) {
                        this.error(name, 'This file is required');
                        ok = false;
                        return;
                    }

                    if (type === 'checkbox' && $(`[name="${name}"]:checked`).length === 0) {
                        this.error(name, 'Please select at least one option');
                        ok = false;
                        return;
                    }

                    if (type === 'radio' && $(`[name="${name}"]:checked`).length === 0) {
                        this.error(name, 'Please select an option');
                        ok = false;
                        return;
                    }

                    // Handle "other_reason" validation only when visible and required
                    if (name === 'other_reason' && $el.is(':visible') && $el.prop('required')) {
                        if (!value || !value.trim()) {
                            this.error(name, 'Please specify the reason');
                            ok = false;
                        }
                        return;
                    }

                    if ((value === null || value === '' || value === undefined || (tag ===
                            'input' && !value
                            .trim()))) {
                        this.error(name, 'This field is required');
                        ok = false;
                    }
                });

                // Additional validation for description length
                // const description = $('[name="description"]').val();
                // if (description && description.length > 500) {
                //     this.error('description', 'Description cannot exceed 500 characters');
                //     ok = false;
                // }

                return ok;
            },

            handleError(xhr) {
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    const allMessages = [];

                    Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                        if (Array.isArray(messages)) {
                            this.error(field, messages[0]);
                            allMessages.push(...messages);
                        } else {
                            this.error(field, messages);
                            allMessages.push(messages);
                        }
                    });

                    Swal.fire('Error', allMessages.join('<br>'), 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong', 'error');
                }
            },


            error(field, message) {
                const $field = $(`[name="${field}"]`);
                if (!$field.length) return;

                if ($field.attr('type') === 'checkbox') {
                    $field.addClass('is-invalid');
                    $field.closest('.form-check').addClass('is-invalid');
                } else {
                    $field.addClass('is-invalid');
                }

                const $error = $(`#${field}_error`);
                if ($error.length) $error.text(message).show();
            },

            clearErrors() {
                $('#recordsForm .is-invalid').removeClass('is-invalid');
                $('#recordsForm .form-check.is-invalid').removeClass('is-invalid');
                $('#recordsForm .invalid-feedback').text('').hide();
            },

            reset() {
                $('#recordsForm')[0].reset();
                $('#record_id').val('');
                this.clearErrors();

                $('[name="reason"]').empty()
                    .append('<option value="">Select Type First</option>')
                    .prop('disabled', true);

                $('#other_reason_container').hide();
                $('#duration_display').text('');

                $('#attachment_requirement_note').hide();
                $('[name="attachment"]').prop('required', false);

                const today = new Date().toISOString().split('T')[0];
                $('[name="start_date"]').attr('min', today);
                $('[name="end_date"]').attr('min', '');

                $('#char_count').text('0');
                $('#description').removeClass('is-invalid');
                $('#description_error').hide();

                $('#declaration').prop('checked', false);
            }
        };

        function getStatusIcon(status) {
            switch (status.toLowerCase()) {
                case 'approved':
                    return '<i class="fas fa-check-circle text-success"></i>';
                case 'rejected':
                    return '<i class="fas fa-times-circle text-danger"></i>';
                case 'pending':
                    return '<i class="fas fa-hourglass-half text-warning"></i>';
                default:
                    return '<i class="fas fa-info-circle text-muted"></i>';
            }
        }


        function renderCheckoutView(data) {

            // console.log(data);
            const checkout = data.checkout;
            const statusView = data.status_view;
            const financial = statusView.financial_summary;

            $('#checkoutSummarySection').removeClass('d-none');

            // ==========================
            // STATUS TITLE + MESSAGE
            // ==========================

            $('#summaryTitle').text(checkout.label);

            let message = '';

            if (financial.pending_dues > 0) {
                message = "You have outstanding dues that must be cleared before checkout can be approved.";
            } else if (financial.net_position > 0) {
                message = "You are eligible for a refundable balance after final approval.";
            } else {
                message = "All financial requirements are satisfied. Awaiting final approval.";
            }

            $('#summaryMessage').text(message);

            $('#summaryBadge')
                .removeClass()
                .addClass('badge bg-' + checkout.badge)
                .text(checkout.label);

            // ==========================
            // FINANCIAL DATA
            // ==========================

            $('#f_total').text("₹ " + financial.total_invoices);
            $('#f_paid').text("₹ " + financial.total_paid);
            $('#f_pending').text("₹ " + financial.pending_dues);

            const net = financial.net_position;
            const netEl = $('#f_net');

            if (net > 0) {
                netEl
                    .removeClass()
                    .addClass('fw-bold fs-5 text-success')
                    .text("₹ " + net + " Refundable");
            } else if (net < 0) {
                netEl
                    .removeClass()
                    .addClass('fw-bold fs-5 text-danger')
                    .text("₹ " + Math.abs(net) + " Payable");
            } else {
                netEl
                    .removeClass()
                    .addClass('fw-bold fs-5 text-secondary')
                    .text("Settled");
            }

            // ==========================
            // FINANCIAL NOTICE
            // ==========================

            const notice = $('#financialNotice');
            notice.addClass('d-none');

            if (financial.pending_dues > 0) {
                notice
                    .removeClass()
                    .addClass('alert alert-danger mt-4')
                    .text("Checkout cannot proceed until outstanding dues are cleared.");
            } else if (net > 0) {
                notice
                    .removeClass()
                    .addClass('alert alert-info mt-4')
                    .text("Refund will be processed within 7–10 working days after approval.");
            }

            // ==========================
            // TIMELINE
            // ==========================

            let timelineHtml = '<div class="d-flex flex-column gap-3">';

            statusView.timeline.forEach(step => {

                timelineHtml += `
            <div class="d-flex align-items-center justify-content-between border rounded p-3">
                <div>
                    <div class="fw-semibold">${step.step}</div>
                </div>
                <div>
                    <span class="badge bg-${step.completed ? 'success' : 'secondary'}">
                        ${step.completed ? 'Completed' : 'Pending'}
                    </span>
                </div>
            </div>
        `;
            });

            timelineHtml += '</div>';

            $('#timelineSteps').html(timelineHtml);
        }


        function renderCheckoutDashboard(data) {

            // const summary = data.checkout;
            const checkout = data.checkout;
            const statusView = data.status_view;
            const financial = statusView.financial_summary;

            // ================= STATUS HEADER =================

            // console.log(statusView);
            $('#dashTitle').text(statusView.label);

            let subMessage = "";

            if (financial.pending_dues > 0) {
                subMessage = "Please clear outstanding dues to proceed with checkout approval.";
            } else if (financial.net_position > 0) {
                subMessage = "Refund will be processed after approval.";
            } else {
                subMessage = "Your checkout request is being processed.";
            }

            $('#dashSubMessage').text(subMessage);

            $('#dashBadge')
                .removeClass()
                .addClass('badge bg-' + statusView.badge)
                .text(statusView.label);

            const ctaContainer = $('#dashCTAContainer');
            ctaContainer.html('');

            if (financial.pending_dues > 0) {

                ctaContainer.html(`
        <button class="btn btn-danger btn-sm mt-2">
            Pay Outstanding Dues
        </button>
        <button class="btn btn-outline-secondary btn-sm mt-2 ms-2">
            View Invoices
        </button>
    `);

            } else if (financial.net_position > 0) {

                ctaContainer.html(`
        <button class="btn btn-success btn-sm mt-2">
            Track Refund Status
        </button>
    `);

            } else if (checkout.stage === 'under_review') {

                ctaContainer.html(`
        <button class="btn btn-outline-primary btn-sm mt-2">
            Contact Administration
        </button>
    `);

            } else if (checkout.stage === 'approved') {

                ctaContainer.html(`
        <button class="btn btn-primary btn-sm mt-2">
            Download Clearance Receipt
        </button>
    `);

            }


            // ================= REQUEST DETAILS =================

            $('#d_resident').text(data.resident.name);
            $('#d_scholar').text(data.resident.scholar_no);
            $('#d_exit_date').text(formatDateForDisplay(data.checkout.requested_exit_date));
            $('#d_status').text(checkout.label);
            $('#d_submitted_on').text(formatDateForDisplay(data.checkout.created_at));
            $('#d_description').text(data.checkout.description ?? '—');

            // ================= FINANCIAL =================

            $('#f_total').text("₹ " + financial.total_invoices);
            $('#f_paid').text("₹ " + financial.total_paid);
            $('#f_pending').text("₹ " + financial.pending_dues);

            const net = financial.net_position;

            if (net > 0) {
                $('#f_net')
                    .removeClass()
                    .addClass('fw-bold fs-5 text-success')
                    .text("₹ " + net + " Refundable");
            } else if (net < 0) {
                $('#f_net')
                    .removeClass()
                    .addClass('fw-bold fs-5 text-danger')
                    .text("₹ " + Math.abs(net) + " Payable");
            } else {
                $('#f_net')
                    .removeClass()
                    .addClass('fw-bold fs-5 text-secondary')
                    .text("Settled");
            }

            const alertBox = $('#financialAlert').addClass('d-none');

            if (financial.pending_dues > 0) {
                alertBox
                    .removeClass()
                    .addClass('alert alert-danger mt-4')
                    .text("Checkout cannot be approved until all dues are cleared.");
            } else if (net > 0) {
                alertBox
                    .removeClass()
                    .addClass('alert alert-info mt-4')
                    .text("Refund will be credited within 7–10 working days after final approval.");
            }

            // ================= TIMELINE =================

            let timelineHtml = '<div class="d-flex flex-column gap-3">';

            statusView.timeline.forEach(step => {

                timelineHtml += `
            <div class="d-flex justify-content-between align-items-center border rounded p-3">
                <div class="fw-semibold">${step.step}</div>
                <span class="badge bg-${step.completed ? 'success' : 'secondary'}">
                    ${step.completed ? 'Completed' : 'Pending'}
                </span>
            </div>
        `;
            });

            timelineHtml += '</div>';

            $('#timelineSteps').html(timelineHtml);
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

            // const printWindow = window.open('', '_blank', 'width=800,height=600');

            const iframe = document.createElement('iframe');
            iframe.style.cssText = 'position:fixed; left:-9999px; width:0; height:0; border:0;';
            document.body.appendChild(iframe);
            const printWindow = iframe.contentWindow;

            printWindow.document.write(
                `
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
                                                                                                                                                <div class="info-value">${data.resident_mobile || 'N/A'}</div>
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

                                                                                                                                            ${data.admin_action_at ? `
                                                                                                                                                            <div class="info-item">
                                                                                                                                                                <div class="info-label">Disposal Date</div>
                                                                                                                                                                <div class="info-value">${(data.admin_action_at ? data.admin_action_at : '')}</div>
                                                                                                                                                            </div>
                                                                                                                                                            ` : ''}

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
                                                                                                                                                                    ${data.qr_code_base64 ? 
                                                                                                                                                                        `<img src="data:image/png;base64,${data.qr_code_base64}" alt="QR Code">` : 
                                                                                                                                                                        `<div style="height:150px; display:flex; align-items:center; justify-content:center; border:1px dashed #ccc; color:#999;">
                                                                                                                                                    No QR Code
                                                                                                                                                </div>`
                                                                                                                                                                    }
                                                                                                                                                                </div>
                                                                                                                                                                <!-- ${data.token ? `
                                                                                                                                            <div>
                                                                                                                                                <div class="info-label">Token</div>
                                                                                                                                                <div class="token">${data.token}</div>
                                                                                                                                            </div>
                                                                                                                                            ` : ''} -->
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
                                                                                                                        `
            );

            printWindow.document.close();

            // Restore button
            setTimeout(() => {
                printBtn.innerHTML = originalHTML;
                printBtn.disabled = false;
            }, 1000);

            // setTimeout(() => window.print(), 300);
            // window.onafterprint = () => setTimeout(() => window.close(), 100);



            setTimeout(() => {
                printWindow.print();

                printWindow.onafterprint = function() {
                    setTimeout(() => {
                        if (iframe.parentNode) {
                            iframe.parentNode.removeChild(iframe);
                        }
                    }, 100);
                };

                // Fallback
                setTimeout(() => {
                    if (iframe.parentNode) {
                        iframe.parentNode.removeChild(iframe);
                    }
                }, 3000);
            }, 500);
        }
    </script>
@endpush
