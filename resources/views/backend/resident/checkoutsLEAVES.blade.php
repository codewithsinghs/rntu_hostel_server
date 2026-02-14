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
    </style>

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Leaves Management</a></div>
    </div>

    <!-- Overview Card -->
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
                </div>

            </div>
        </div>
    </section>

    <!-- Table section -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Leaves List</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Faculty">+ Add
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                Faculty</button> -->
                    <button type="button" class="btn btn-primary btn-sm d-flex align-items-center"
                        onclick="RecordsModal.openCreate()" aria-label="Apply for leave">
                        {{-- <i class="fa fa-plus me-1" aria-hidden="true"></i> --}}
                        <i class="fa fa-calendar-plus me-1" aria-hidden="true"></i>
                        <span>Apply Leave</span>
                    </button>

                </div>

                <div class="table-responsive">
                    <table id="recordsTable" class="table status-table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th width="160">Resident</th>
                                {{-- <th>Enroll. No.</th> --}}
                                {{-- <th>Room / Bed</th> --}}
                                <th>Leave Type / Reason</th>
                                {{-- <th>Reason</th> --}}
                                <th>From / To Date</th>
                                {{-- <th>To </th> --}}
                                {{-- <th>Attachment </th> --}}
                                {{-- <th>Hod Status </th>
                                <th>Hod Remark </th>
                                <th>Hod Approved At </th> --}}
                                <th width="140">Hod Action </th>
                                <th width="140">Admin Action </th>
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
                    <h5 class="modal-title mpop-title" id="recordsModalTitle">Leave Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Student Info (Read-only) -->
                    <div class="row mb-4">
                        {{-- <div class="col-md-6">
                            <div class="info-box">
                                <label class="form-label text-muted">Resident Name</label>
                                <div class="form-control-plaintext border-bottom pb-2" id="name_display">-</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <label class="form-label text-muted">Scholar Number</label>
                                <div class="form-control-plaintext border-bottom pb-2" id="scholar_display">-</div>
                            </div>
                        </div> --}}
                    </div>

                    <form id="recordsForm" novalidate>
                        @csrf
                        <input type="hidden" id="record_id" name="id">
                        <input type="hidden" id="name" name="name">
                        <input type="hidden" id="scholar" name="scholar">

                        <div class="row">
                            <!-- Type Field -->
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Leave Type *</label>
                                {{-- <select name="type" id="type" class="form-control" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="medical">Medical Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="academic">Academic Leave</option>
                                    <option value="official">Official Work</option>
                                    <option value="emergency">Emergency Leave</option>
                                    <option value="semester_break">Semester Break</option>
                                    <option value="sports">Sports Activity</option>
                                    <option value="cultural">Cultural Activity</option>
                                    <option value="local_guardian">Local Guardian Visit</option>
                                    <option value="half_day">Half Day Leave</option>
                                    <option value="night_out">Night Out</option>
                                    <option value="other">Other</option>
                                </select> --}}
                                <select name="type" id="type" class="form-select" required>
                                    <option value="">Select Leave Type</option>
                                    @foreach (config('leaves') as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                    <option value="other">Other</option>
                                </select>

                                <div class="invalid-feedback" id="type_error"></div>
                            </div>


                            <!-- Reason Field -->
                            <div class="col-md-6 mb-3">
                                <label for="reason" class="form-label">Reason *</label>
                                <select name="reason" id="reason" class="form-control" required disabled>
                                    <option value="">Select Type First</option>
                                </select>
                                <div class="invalid-feedback" id="reason_error"></div>
                            </div>
                        </div>

                        <!-- Other Reason Input -->
                        <div class="mb-3" id="other_reason_container" style="display: none;">
                            <label for="other_reason" class="form-label">Specify Other Reason *</label>
                            <input type="text" name="other_reason" id="other_reason" class="form-control"
                                placeholder="Please specify the reason in detail...">
                            <div class="invalid-feedback" id="other_reason_error"></div>
                        </div>

                        <!-- Description -->
                        {{-- <div class="mb-3">
                            <label for="description" class="form-label">Detailed Description</label>
                            <textarea rows="3" class="form-control" id="description" name="description"
                                placeholder="Provide additional details about your leave..."></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="form-text">Please provide a detailed reason (max 500 characters).</small>
                                <small class="form-text text-muted"><span id="char_count">0</span>/500</small>
                            </div>
                            <div id="description_error" class="invalid-feedback"></div>
                        </div> --}}

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea rows="3" class="form-control" id="description" name="description"
                                placeholder="Provide details about your leave..." required></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="form-text">Please provide a detailed reason (max 500 characters).</small>
                                <small class="form-text text-muted"><span id="char_count">0</span>/500</small>
                            </div>
                            <div id="description_error" class="invalid-feedback"></div>
                        </div>

                        <!-- emergency_contact_container -->
                        <div class="mb-3" id="emergency_contact_container" style="display: none;">
                            <label for="emergency_contact" class="form-label">Emergency Contact Number *</label>
                            <input type="tel" class="form-control" id="emergency_contact" name="emergency_contact"
                                placeholder="Enter emergency contact number">
                            <small class="form-text">Provide a contact number where you can be reached during emergency
                                leave</small>
                            <div class="invalid-feedback" id="emergency_contact_error"></div>
                        </div>

                        <!-- Dates Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Leave Duration</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Start Date *</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            required>
                                        <div class="invalid-feedback" id="start_date_error"></div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">End Date *</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            required>
                                        <div class="invalid-feedback" id="end_date_error"></div>
                                    </div>
                                </div>
                                <!-- Duration Display -->
                                <div class="alert alert-info py-2 mb-0" id="duration_display">
                                    Select start and end dates to calculate duration
                                </div>
                            </div>
                        </div>

                        <!-- Attachment -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label">Supporting Documents (optional)</label>
                            <input type="file" class="form-control" id="attachment" name="attachment"
                                accept=".pdf,.jpg,.png,.jpeg,.doc,.docx">
                            <small class="form-text">Upload supporting documents like medical certificate, invitation, etc.
                                (Max: 5MB)</small>
                            <div class="invalid-feedback" id="attachment_error"></div>
                        </div>

                        <!-- Applied Date (Auto-filled) -->
                        <div class="mb-3">
                            <label class="form-label">Applied On</label>
                            <input type="text" class="form-control bg-light" id="apply_date" name="apply_date"
                                readonly>
                        </div>

                        <!-- Add this after the description field -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="declaration" name="declaration"
                                    required>
                                <label class="form-check-label" for="declaration">
                                    I hereby declare that the information provided above is true and correct to the best of
                                    my knowledge.
                                    I understand that providing false information may lead to disciplinary action.
                                </label>
                                <div class="invalid-feedback" id="declaration_error">
                                    You must accept the declaration to proceed
                                </div>
                            </div>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                            <button type="submit" class="blue" id="recordsSubmitBtn">
                                Submit Application
                            </button>


                        </div>
                    </form>
                </div>

                {{-- <div class="modal-footer">
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
                </div> --}}

            </div>
        </div>
    </div>

    <!-- Leave Action Modal -->
    <div class="modal fade" id="leaveActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="leaveActionForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" id="leave_id">
                <input type="hidden" id="leave_action">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveActionTitle">Leave Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <select id="leave_reason" class="form-select">
                                <option value="">-- Select --</option>
                            </select>
                            <div class="invalid-feedback">Please select a reason</div>
                            <div class="invalid-feedback" id="remarks_error"></div>

                        </div>

                        <div class="mb-3 d-none" id="customReasonBox">
                            <label class="form-label">Custom Reason</label>
                            <textarea id="leave_custom_reason" class="form-control" rows="3"></textarea>
                            <div class="invalid-feedback">Custom reason required</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" id="leave_attachment" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Attachment Modal -->
    <div class="modal fade" id="attachmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- large modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attachment Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- File will be injected here -->
                    <iframe id="attachmentFrame" src="" width="100%" height="600px"
                        style="border:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <!-- Modal for View Receipt -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Leave Request Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">Print Receipt</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
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
                        url: "{{ route('resident.leaves.index') }}",

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
                                let scholarNo = row.resident_scholar_no ||
                                    '<span class="text-muted">N/A</span>';
                                let room = row.resident_room_number ||
                                    '<span class="text-muted">N/A</span>';
                                let bed = row.resident_bed_number ||
                                    '<span class="text-muted">N/A</span>';

                                return `
                                        <div>
                                            <strong>${name}</strong><br>
                                            Enrollment: ${scholarNo}<br>
                                            Room: ${room}, Bed: ${bed}
                                        </div>
                                    `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'type',
                            title: 'Leave Details',
                            render: function(data, type, row) {
                                function formatExpandable(text) {
                                    if (!text) return '<span class="text-muted">N/A</span>';
                                    let safeText = $('<div>').text(text).html(); // escape HTML
                                    if (safeText.length > 50) {
                                        let shortText = safeText.substring(0, 50) + '...';
                                        return `
                                            <span class="short-text">${shortText}</span>
                                            <a href="javascript:void(0)" class="toggle-text">Show more</a>
                                            <span class="full-text d-none">${safeText}</span>
                                        `;
                                    }
                                    return safeText;
                                }

                                let leaveType = row.type || '<span class="text-muted">N/A</span>';
                                let reason = formatExpandable(row.reason);
                                let description = formatExpandable(row.description);

                                // ✅ Only build attachment link if file exists
                                let attachment;
                                if (row.attachment && row.attachment.trim() !== '') {
                                    let encoded = btoa(row.attachment); // Base64 encode
                                    attachment =
                                        `<a href="/files/${encoded}" target="_blank">View Attachment</a>`;
                                } else {
                                    attachment = '<span class="text-muted">No Attachment</span>';
                                }

                                return `
                                    <div>
                                        <strong>Type:</strong> ${leaveType} <br>
                                        <strong>Reason:</strong> ${reason} <br>
                                        <strong>Description:</strong> ${description} <br>
                                        <strong>Attachment:</strong> ${attachment}
                                    </div>
                                `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'start_date',
                            title: 'Leave Period',
                            render: function(data, type, row) {
                                let start = row.start_date || '<span class="text-muted">N/A</span>';
                                let end = row.end_date || '<span class="text-muted">N/A</span>';

                                return `
                                    <div>
                                        Start: ${start}<br>
                                        End: ${end}
                                    </div>
                                `;
                            },
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },

                        {
                            data: 'hod_status',
                            title: 'HOD Action',
                            orderable: true,
                            render: function(data, type, row) {

                                if (type !== 'display') {
                                    return row.hod_status || 'pending';
                                }

                                const status = row.hod_status ?? 'pending';
                                const remarks = row.hod_remarks ?? '';
                                const approvedAt = row.hod_action_at ?? '';

                                let html = `
                                    <div class="d-flex flex-column gap-1">
                                        <div>${badge(status)}</div>

                                        ${approvedAt ? `
                                                                                                                                                                                                                                                                                                                                <small class="text-muted">
                                                                                                                                                                                                                                                                                                                                    <i class="bi bi-clock"></i> ${approvedAt}
                                                                                                                                                                                                                                                                                                                                </small>
                                                                                                                                                                                                                                                                                                                            ` : ''}

                                        ${remarks ? `
                                                                                                                                                                                                                                                                                                                                <small class="text-truncate text-secondary"
                                                                                                                                                                                                                                                                                                                                    style="max-width: 220px"
                                                                                                                                                                                                                                                                                                                                    title="${remarks}">
                                                                                                                                                                                                                                                                                                                                    <i class="bi bi-chat-left-text"></i> ${remarks}
                                                                                                                                                                                                                                                                                                                                </small>
                                                                                                                                                                                                                                                                                                                            ` : ''}
                                         ${row.hod_attachment && row.hod_attachment.trim() !== '' ? ` <small> <a href="/files/${btoa(row.hod_attachment)}" target="_blank">View Attachment</a> </small> ` : ''}
                                `;

                                // ✅ HOD buttons
                                if (
                                    CURRENT_USER_ROLE?.includes('hod') &&
                                    row.hod_status === 'pending'
                                ) {
                                    html += `
                                        <div class="mt-1">
                                            <button
                                                class="btn btn-sm btn-success me-1"
                                                onclick="LeaveActions.open(${row.id}, 'hod_approve')">
                                                Approve
                                            </button>

                                            <button
                                                class="btn btn-sm btn-danger"
                                                onclick="LeaveActions.open(${row.id}, 'hod_reject')">
                                                Reject
                                            </button>
                                        </div>
                                    `;
                                }

                                html += `</div>`;
                                return html;
                            }
                        },

                        {
                            data: 'admin_status',
                            title: 'ADMIN Action',
                            orderable: true,
                            render: function(data, type, row) {
                                if (type === 'display') {
                                    const status = row.admin_status ?? 'pending';
                                    const remarks = row.admin_remarks ?? '';
                                    const approvedAt = row.admin_action_at ?? '';
                                    let encoded = btoa(row.admin_attachment);

                                    let html = `
                                        <div class="d-flex flex-column gap-1">
                                            <div>${badge(status)}</div>

                                            ${approvedAt ? `
                                                                                                                                                                                                                                                                                                                                    <small class="text-muted">
                                                                                                                                                                                                                                                                                                                                        <i class="bi bi-clock"></i> ${approvedAt}
                                                                                                                                                                                                                                                                                                                                    </small>
                                                                                                                                                                                                                                                                                                                                ` : ''}

                                            ${remarks ? `
                                                                                                                                                                                                                                                                                                                                    <small class="text-truncate text-secondary"
                                                                                                                                                                                                                                                                                                                                        style="max-width: 220px"
                                                                                                                                                                                                                                                                                                                                        title="${remarks}">
                                                                                                                                                                                                                                                                                                                                        <i class="bi bi-chat-left-text"></i> ${remarks}
                                                                                                                                                                                                                                                                                                                                    </small>
                                                                                                                                                                                                                                                                                                                                ` : ''}
                                            ${row.admin_attachment && row.admin_attachment.trim() !== '' ? 
                                            ` <small> <a href="/files/${btoa(row.admin_attachment)}" target="_blank">View Attachment</a> </small>
                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                ` : ''}
                                    `;
                                    // ` < small > < a href = "javascript:void(0)" onclick = "openAttachmentModal('${encoded}')" > View Attachment </a> </small > 
                                // ✅ Admin buttons
                                if (
                                    CURRENT_USER_ROLE?.includes('admin') &&
                                    row.hod_status === 'approved' &&
                                    row.admin_status === 'pending'
                                ) {
                                    html +=
                                        `
                                                                                                                                                                                                                                                                                        <div class="mt-1">
                                                                                                                                                                                                                                                                                            <button class="btn btn-sm btn-success me-1"
                                                                                                                                                                                                                                                                                                onclick="LeaveActions.open(${row.id}, 'admin_approve')">
                                                                                                                                                                                                                                                                                                Approve
                                                                                                                                                                                                                                                                                            </button>

                                                                                                                                                                                                                                                                                            <button class="btn btn-sm btn-danger"
                                                                                                                                                                                                                                                                                                onclick="LeaveActions.open(${row.id}, 'admin_reject')">
                                                                                                                                                                                                                                                                                                Reject
                                                                                                                                                                                                                                                                                            </button>
                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                    `;
                                }

                                html += `</div>`;
                                return html;
                            }

                            return row.admin_status || 'pending';
                        }
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
        const isPending = row.hod_status === 'pending' && row.admin_status === 'pending';

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
            buttons += `
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
            buttons += `
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
    function badge(status) {
        switch (status) {
            case 'pending':
            case 0:
                return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'approved':
            case 1:
                return '<span class="badge bg-success">Approved</span>';
            case 'rejected':
            case 2:
                return '<span class="badge bg-danger">Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }


    // MODAL HANDLER     
    const RecordsModal = {

        setMode(mode) {
            //   RecordsForm.init();
            const isView = mode === 'view';
            $('#recordsForm input, #recordsForm select')
                .prop('readonly', isView)
                .prop('disabled', isView);
            $('#recordsSubmitBtn').toggle(!isView);
            $('#recordsModalTitle').text({
                add: 'Add Record',
                edit: 'Edit Record',
                view: 'View Record'
            } [mode]);
        },

        openCreate() {
            RecordsForm.reset();
            this.setMode('add');
            // Clear form fields when creating new record
            $('#name').val('');
            $('#scholar').val('');
            $('#apply_date').val('');


            // FacultySelect.load();
            // $('#department_id').html('<option value="">Select Department</option>');
            $('#recordsModal').modal('show');
        },

        openEdit(id) {
            RecordsForm.reset();
            this.setMode('edit');
            const url = "{{ route('resident.leaves.show', ':id') }}".replace(':id', id);

            $.ajax({
                url,
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token'),
                    Accept: 'application/json'
                },
                success: res => {
                    const c = res.data;
                    console.log(c);

                    // Basic resident + leave info
                    $('#record_id').val(c.id);
                    // $('#name').val(c.resident_name);
                    // $('#scholar').val(c.resident_scholar_no);
                    // Set type first
                    // $('#type').val(c.type.toLowerCase());
                    // $('#type').off('change');
                    // $('#type').val(c.type.toLowerCase()).trigger('change'); // will call updateReasonOptions()
                    $('#type').val(c.type?.toLowerCase() || "").trigger('change');
                    // Populate reasons based on type
                    // RecordsForm.updateReasonOptions(); // refresh reasons based on type

                    // Normalize reason value
                    // const reasonValue = (c.reason || '').trim().toLowerCase();

                    // Try to select it$('#reason').val(c.reason.toLowerCase());
                    // $('#reason').val((c.reason || '').trim().toLowerCase());
                    // console.log('Trying to select reason:', c.reason.toLowerCase()); console.log('Options now:', $('#reason').find('option').map((i,opt)=>opt.value).get()); console.log('Selected value after set:', $('#reason').val());

                    // If not found, fall back to "other"
                    // if ($('#reason').val() !== reasonValue) {
                    //     $('#reason').val('other');
                    //     $('[name="other_reason"]').val(c.reason); // prefill custom reason
                    // }

                    // Handle "Other" case
                    //RecordsForm.toggleOtherReason(); // handle "Other" case

                    setTimeout(() => {
                        const reasonValue = (c.reason || '').trim().toLowerCase();
                        $('#reason').val(reasonValue);
                        if ($('#reason').val() !== reasonValue) {
                            $('#reason').val('other');
                            $('[name="other_reason"]').val(c.reason);
                        }
                        RecordsForm.toggleOtherReason();
                    }, 100);

                    $('#description').val(c.description);
                    // $('#start_date').val(c.start_date);
                    // $('#end_date').val(c.end_date);
                    // $('#apply_date').val(c.apply_date);
                    // Normalize dates for input fields 
                    $('#start_date').val(formatDateForInput(c.start_date));
                    $('#end_date').val(
                        formatDateForInput(c.end_date));
                    // Show pretty display if needed 
                    $('#apply_date').val(formatDateForDisplay(c.apply_date));
                    // Duration auto-calc 
                    $('#duration').val(c.duration ?? calculateDuration(c.start_date, c
                        .end_date));

                    $('#status').val(c.status);

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
            const url = "{{ route('resident.leaves.show', ':id') }}".replace(':id', id);
            $.ajax({
                url,
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token'),
                    Accept: 'application/json'
                },
                success: res => {
                    const c = res.data;
                    // Basic resident + leave info
                    $('#record_id').val(c.id);
                    // $('#name').val(c.resident.name);
                    // $('#scholar').val(c.resident.scholar_no);

                    // $('#type').val(c.type.toLowerCase());
                    // RecordsForm.updateReasonOptions();
                    // $('#reason').val(c.reason.toLowerCase());
                    // RecordsForm.toggleOtherReason();
                    // $('#type').val(c.type.toLowerCase()).trigger('change'); // will call updateReasonOptions()

                    $('#type').val(c.type?.toLowerCase() || "").trigger('change');

                    setTimeout(() => {
                        const reasonValue = (c.reason || '').trim().toLowerCase();
                        $('#reason').val(reasonValue);
                        if ($('#reason').val() !== reasonValue) {
                            $('#reason').val('other');
                            $('[name="other_reason"]').val(c.reason);
                        }
                        RecordsForm.toggleOtherReason();
                    }, 100);

                    $('#description').val(c.description);
                    // $('#start_date').val(c.start_date);
                    // $('#end_date').val(c.end_date);
                    // $('#apply_date').val(c.apply_date);
                    // Normalize dates for input fields 
                    $('#start_date').val(formatDateForInput(c.start_date));
                    $(
                        '#end_date').val(formatDateForInput(c.end_date));
                    // Show pretty display if needed 
                    $('#apply_date').val(formatDateForDisplay(c.apply_date));
                    // Duration auto-calc 
                    $('#duration').val(c.duration ?? calculateDuration(c.start_date, c
                        .end_date));

                    $('#status').val(c.status);

                    // Populate approvals from JSON
                    if (c.approvals && Array.isArray(c.approvals)) {
                        const hod = c.approvals.find(a => a.role.toLowerCase() ===
                            'hod');
                        const admin = c.approvals.find(a => a.role.toLowerCase() ===
                            'admin');

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

                    // disable inputs for view mode 
                    $('#recordForm input, #recordForm select, #recordForm textarea')
                        .prop('disabled',
                            true);

                    $('#recordsModal').modal('show');
                },
                error: () => Swal.fire('Error', 'Unable to load faculty', 'error')
            });
        },

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
            console.log('Cancel leave called for ID:', id);
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
                                                                `);

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

    // FORM HANDLER     
    const RecordsForm = {

        init() {
            console.log('RecordsForm initialized');
            $('#recordsForm').on('submit', this.submit.bind(this));

            // Handle type change to update reason options
            $('#recordsForm').on('change', '[name="type"]', this.updateReasonOptions.bind(this));

            // Handle date changes for duration calculation
            $('#recordsForm').on('change', '[name="start_date"], [name="end_date"]', this
                .calculateDuration.bind(
                    this));

            // Handle reason change to show/hide other reason input
            $('#recordsForm').on('change', '[name="reason"]', this.toggleOtherReason.bind(this));

            // $('#description').on('input', function() {
            //     const length = $(this).val().length;
            //     $('#char_count').text(length);

            //     // Real-time validation feedback
            //     if (length === 0) {
            //         $(this).addClass('is-invalid');
            //         $('#description_error').text('Description is required').show();
            //     } else if (length > 500) {
            //         $(this).addClass('is-invalid');
            //         $('#description_error').text('Description cannot exceed 500 characters').show();
            //     } else {
            //         $(this).removeClass('is-invalid');
            //         $('#description_error').hide();
            //     }
            // });

            // Prevent past dates in date inputs
            this.preventPastDates();

            // Handle attachment requirement based on type
            // $('#recordsForm').on('change', '[name="type"], [name="reason"], [name="leave_category"]',
            $('#recordsForm').on('change', '[name="type"], [name="reason"]',
                this.checkAttachmentRequirement.bind(this));

            // Initialize on modal show
            $('#recordsModal').on('show.bs.modal', () => {
                this.updateReasonOptions();
                this.calculateDuration();

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

        // Add this method to toggle emergency contact visibility
        toggleEmergencyContact() {
            const type = $('[name="type"]').val();
            const $emergencyContainer = $('#emergency_contact_container');
            const $emergencyInput = $('[name="emergency_contact"]');

            if (type === 'emergency') {
                $emergencyContainer.slideDown(300);
                $emergencyInput.prop('required', true);
            } else {
                $emergencyContainer.slideUp(300);
                $emergencyInput.prop('required', false).val('');
                $emergencyInput.removeClass('is-invalid');
                $('#emergency_contact_error').hide();
            }
        },

        submit(e) {
            e.preventDefault();
            if (!this.validate()) return;

            const id = $('#record_id').val();
            const url = id ?
                "{{ route('resident.leaves.update', ':id') }}".replace(':id', id) :
                "{{ route('resident.leaves.store') }}";

            const formData = new FormData($('#recordsForm')[0]);

            // Override reason before sending 
            const selectedReason = this.getSelectedReason();
            formData.set('reason', selectedReason); // replaces "other" with custom text

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

        updateReasonOptions() {
            const type = $('[name="type"]').val();
            console.log('Updating reasons for type:', type);
            const $reasonSelect = $('[name="reason"]');
            console.log('Reason select element:', $reasonSelect);
            const otherReasonContainer = $('#other_reason_container');

            if (type) {
                console.log('Type selected:', type);
                // Enable reason field
                $reasonSelect.prop('disabled', false);

                // Get reasons based on type
                const reasons = this.getReasonsForType(type);

                // Clear and populate options
                $reasonSelect.empty();
                $reasonSelect.append('<option value="">Select Reason</option>');

                reasons.forEach(reason => {
                    $reasonSelect.append(
                        `<option value="${reason.value}">${reason.label}</option>`);
                });

                // Add "Other" option
                $reasonSelect.append('<option value="other">Other (Please specify)</option>');

                // Show/hide other reason input based on current selection
                this.toggleOtherReason();
            } else {
                // Disable reason field if no type selected
                $reasonSelect.prop('disabled', true).empty().append(
                    '<option value="">Select Type First</option>');
                otherReasonContainer.hide();
            }
        },

        getReasonsForType(type) {
            // Hostler-specific reasons for different leave types
            const reasonMap = {
                'medical': [{
                        value: 'doctor_appointment',
                        label: 'Doctor Appointment'
                    },
                    {
                        value: 'hospital_visit',
                        label: 'Hospital Visit'
                    },
                    {
                        value: 'medical_checkup',
                        label: 'Medical Checkup'
                    },
                    {
                        value: 'dental',
                        label: 'Dental Treatment'
                    },
                    {
                        value: 'ophthalmology',
                        label: 'Eye Checkup'
                    },
                    {
                        value: 'pharmacy',
                        label: 'Medicine Purchase'
                    }
                ],
                'personal': [{
                        value: 'family_visit',
                        label: 'Family Visit'
                    },
                    {
                        value: 'marriage',
                        label: 'Marriage Ceremony'
                    },
                    {
                        value: 'festival',
                        label: 'Festival Celebration'
                    },
                    {
                        value: 'birthday',
                        label: 'Birthday/Family Function'
                    },
                    {
                        value: 'shopping',
                        label: 'Essential Shopping'
                    },
                    {
                        value: 'bank_work',
                        label: 'Bank Work (Personal)'
                    }
                ],
                'academic': [{
                        value: 'library_study',
                        label: 'Library Study'
                    },
                    {
                        value: 'project_work',
                        label: 'Project Work Outside'
                    },
                    {
                        value: 'seminar',
                        label: 'Seminar/Conference'
                    },
                    {
                        value: 'book_purchase',
                        label: 'Book Purchase'
                    },
                    {
                        value: 'lab_work',
                        label: 'Lab Work (External)'
                    },
                    {
                        value: 'exam_preparation',
                        label: 'Exam Preparation'
                    }
                ],
                'official': [{
                        value: 'document_work',
                        label: 'Document Work'
                    },
                    {
                        value: 'govt_office',
                        label: 'Government Office'
                    },
                    {
                        value: 'police_verification',
                        label: 'Police Verification'
                    },
                    {
                        value: 'passport',
                        label: 'Passport/Ration Card Work'
                    },
                    {
                        value: 'certificate',
                        label: 'Certificate Attestation'
                    }
                ],
                'emergency': [{
                        value: 'family_emergency',
                        label: 'Family Emergency'
                    },
                    {
                        value: 'accident',
                        label: 'Accident'
                    },
                    {
                        value: 'natural_calamity',
                        label: 'Natural Calamity'
                    },
                    {
                        value: 'urgent_call',
                        label: 'Urgent Family Call'
                    }
                ],
                'semester_break': [{
                        value: 'winter_break',
                        label: 'Winter Break'
                    },
                    {
                        value: 'summer_break',
                        label: 'Summer Break'
                    },
                    {
                        value: 'mid_sem_break',
                        label: 'Mid-Semester Break'
                    },
                    {
                        value: 'end_sem_break',
                        label: 'End of Semester'
                    }
                ],
                'sports': [{
                        value: 'tournament',
                        label: 'Sports Tournament'
                    },
                    {
                        value: 'practice',
                        label: 'Practice Session'
                    },
                    {
                        value: 'coaching',
                        label: 'Coaching Camp'
                    },
                    {
                        value: 'equipment',
                        label: 'Sports Equipment Purchase'
                    }
                ],
                'cultural': [{
                        value: 'competition',
                        label: 'Cultural Competition'
                    },
                    {
                        value: 'rehearsal',
                        label: 'Rehearsal/Practice'
                    },
                    {
                        value: 'performance',
                        label: 'Performance/Show'
                    },
                    {
                        value: 'workshop',
                        label: 'Workshop/Training'
                    }
                ],
                'local_guardian': [{
                        value: 'visit',
                        label: 'Visit Local Guardian'
                    },
                    {
                        value: 'function',
                        label: 'Guardian Family Function'
                    },
                    {
                        value: 'emergency_help',
                        label: 'Emergency Help Required'
                    }
                ]
            };

            return reasonMap[type] || [{
                value: 'general',
                label: 'General Reason'
            }];
        },

        getSelectedReason() {
            const reason = $('[name="reason"]').val();
            if (reason === 'other') {
                return $('[name="other_reason"]').val().trim();
            }
            return reason;
        },

        toggleOtherReason() {
            const selectedReason = $('[name="reason"]').val();
            const otherReasonContainer = $('#other_reason_container');
            const otherReasonInput = $('[name="other_reason"]');

            if (selectedReason === 'other') {
                otherReasonContainer.slideDown(300);
                otherReasonInput.prop('required', true);
            } else {
                otherReasonContainer.slideUp(300);
                otherReasonInput.prop('required', false).val('');
            }
        },

        calculateDuration() {
            const startDate = $('[name="start_date"]').val();
            const endDate = $('[name="end_date"]').val();
            const $durationField = $('#duration_display');

            if (!startDate || !endDate) {
                $durationField.text('');
                return;
            }

            const start = new Date(startDate);
            const end = new Date(endDate);

            // Check if end date is before start date
            if (end < start) {
                $durationField.html(
                    '<span class="text-danger">End date cannot be before start date!</span>');
                $('[name="end_date"]').addClass('is-invalid');
                return;
            }

            // Calculate difference in days
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) +
                1; // +1 to include both dates

            // Format duration display
            let durationText = '';
            if (diffDays === 1) {
                durationText = '1 day';
            } else if (diffDays <= 7) {
                durationText = `${diffDays} days`;
            } else {
                const weeks = Math.floor(diffDays / 7);
                const remainingDays = diffDays % 7;

                if (remainingDays === 0) {
                    durationText = `${weeks} week${weeks > 1 ? 's' : ''}`;
                } else {
                    durationText =
                        `${weeks} week${weeks > 1 ? 's' : ''} ${remainingDays} day${remainingDays > 1 ? 's' : ''}`;
                }
            }

            $durationField.html(
                `<strong>Duration:</strong> ${durationText} (${diffDays} day${diffDays > 1 ? 's' : ''} total)`
            );
            $('[name="end_date"]').removeClass('is-invalid');
        },

        validate() {
            let ok = true;
            this.clearErrors();

            // Validate dates are not in past
            const startDate = $('[name="start_date"]').val();
            const endDate = $('[name="end_date"]').val();
            const today = new Date().toISOString().split('T')[0];

            if (startDate) {
                if (new Date(startDate) < new Date(today)) {
                    this.error('start_date', 'Start date cannot be in the past');
                    ok = false;
                }
            }

            if (endDate) {
                if (new Date(endDate) < new Date(today)) {
                    this.error('end_date', 'End date cannot be in the past');
                    ok = false;
                }
            }

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (end < start) {
                    this.error('end_date', 'End date cannot be before start date');
                    ok = false;
                }

                // Optional: Validate if leave is within reasonable duration (e.g., 30 days max)
                const maxDays = 30;
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays > maxDays) {
                    this.error('end_date',
                        `Leave cannot exceed ${maxDays} days. Please contact administration for longer leaves.`
                    );
                    ok = false;
                }
            }

            // Validate semester break duration (example: max 15 days)
            const type = $('[name="type"]').val();
            if (type === 'semester_break' && startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

                if (diffDays > 15) {
                    this.error('end_date', 'Semester break leave cannot exceed 15 days');
                    ok = false;
                }
            }

            // Validate description (required field)
            // const description = $('[name="description"]').val();
            // if (!description || description.trim() === '') {
            //     this.error('description', 'Description is required');
            //     ok = false;
            // } else if (description.length > 500) {
            //     this.error('description', 'Description cannot exceed 500 characters');
            //     ok = false;
            // }

            // Validate emergency contact for emergency leaves

            const emergencyContact = $('[name="emergency_contact"]').val();

            if (type === 'emergency') {
                if (!emergencyContact || emergencyContact.trim() === '') {
                    this.error('emergency_contact',
                        'Emergency contact is required for emergency leaves');
                    ok = false;
                } else if (!/^[0-9]{10,15}$/.test(emergencyContact.replace(/\D/g, ''))) {
                    this.error('emergency_contact', 'Please enter a valid phone number (10-15 digits)');
                    ok = false;
                }
            }


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
    </script>
@endpush
