@extends('admin.layout')

@section('content')
    <style>
        .dataTables_length {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            /* spacing between elements */
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 14px;
        }

        .datatable-select {
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview </a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Residents Details</a></div>


                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Residents</p>
                            <h3 id="totalResidents">0</h3>
                        </div>
                        {{-- <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="">
                        </div> --}}
                        <div class="">
                            <i class="fa-solid fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Active / Inactive Residents</p>
                            <h3>
                                <span id="activeResidents">0</span> / <span id="inactiveResidents">0</span>
                            </h3>
                        </div>
                        {{-- <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="">
                        </div> --}}
                        <div class="">
                            <i class="fa-solid fa-person-walking fa-2x"></i>
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Male / Female Counts</p>
                            <h3> <span id="maleCount">0</span> Male / <span id="femaleCount">0</span> Female </h3>
                        </div>
                        {{-- <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div> --}}
                        <div class="">
                            <i class="fa-solid fa-mars fa-lg"></i> / <i class="fa-solid fa-venus fa-lg"></i>
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Checked In Today</p>
                            <h3 id="checkedInToday">0</h3>
                        </div>
                        <!-- <div class="card-d-image">
                                        <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="">
                                    </div> -->
                        <div class="">
                            <i class="fa-solid fa-calendar-check fa-2x"></i>
                        </div>
                    </div>

                    {{-- <div class="card-d">
                        <div class="card-d-content">
                            <p>Inactive Residents</p>
                            <h3 id="inactiveResidents">0</h3>
                        </div>
                       <!-- <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div> -->
                        <div class="">
                            <!-- <i class="fa-solid fa-bed fa-2x"></i> -->
                             <!-- <i class="fa-solid fa-user-slash fa-2x"></i> -->
                             <i class="fa-solid fa-user-xmark fa-2x"></i>

                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Approvals</p>
                            <h3>3,720</h3>
                        </div>
                        <!-- <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="">
                        </div> -->
                        <div class="">
                            <i class="fa-solid fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                     --}}
                </div>
                {{-- <div class="row g-3 mb-4" id="residentSummary">

                    <!-- Total Residents -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-box bg-primary-subtle text-primary">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 text-muted">Total Residents</h6>
                                    <h3 class="mb-0 fw-bold" id="totalResidents">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-box bg-success-subtle text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 text-muted">Active</h6>
                                    <h3 class="mb-0 fw-bold" id="activeResidents">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inactive -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-box bg-danger-subtle text-danger">
                                    <i class="bi bi-x-circle-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 text-muted">Inactive</h6>
                                    <h3 class="mb-0 fw-bold" id="inactiveResidents">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checked In Today -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-box bg-info-subtle text-info">
                                    <i class="bi bi-calendar-check-fill"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1 text-muted">Checked In Today</h6>
                                    <h3 class="mb-0 fw-bold" id="checkedInToday">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> --}}


            </div>
        </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Residents Details</a></div>
                    <button class="btn btn-primary btn-sm" onclick="RecordsModal.openCreate()" id="addResidentBtn">
                        <i class="fa fa-plus"></i> Add Residenta
                    </button>
                </div>

                <div class="overflow-auto">
                    <div id="responseMessage" class="mt-3"></div>
                    <div class="table-responsive">
                        <table class="status-table table table-hover table-bordered w-100" id="residentTable"
                            data-show-length="true" data-show-search="true" data-show-buttons="true"
                            data-length-menu="[10,25,50,100,200]">

                            <thead>
                                <tr>
                                    <th class="noExport noVis"></th> <!-- 🔥 Responsive control column -->
                                    <th>#</th>
                                    <th>Scholar No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Check-in</th>
                                    <th>Gender</th>
                                    <th>Building</th>
                                    <th>Room</th>
                                    <th>Bed</th>
                                    <th>Father's Name</th>
                                    <th>Mother's Name</th>
                                    <th>Parent's Contact</th>
                                    <th>Guardian's Name</th>
                                    <th>Guardian's Contact</th>
                                    <th>Emergency Contact</th>
                                    <th>Faculty</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="noExport noVis">Action</th> <!-- ✅ REQUIRED -->
                                </tr>
                            </thead>

                            <tbody id="residentList"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- Edit Resident Popup-->
    <div class="modal fade" id="editResidentModal" tabindex="-1" aria-labelledby="editResidentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title mpop-title" id="residentModalTitle">Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- <div class="modal-header">
                    <h5 class="mpop-title" id="residentModalTitle">Resident</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> --}}

                <div class="modal-body">

                    {{-- <div class="top">
                        <div class="pop-title">Edit Resident</div>
                    </div> --}}

                    <div id="editResponse"></div>

                    <form id="editResidentForm" novalidate>

                        <input type="hidden" id="edit_resident_id">

                        <div class="middle gap-3 mt-1">
                            <span class="input-set">
                                <label>Scholar No</label>
                                <input type="text" id="edit_scholar_no">
                            </span>

                            <span class="input-set">
                                <label>Name</label>
                                <input type="text" id="edit_name">
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="edit_email">
                            </span>

                            <span class="input-set">
                                <label>Mobile Number</label>
                                <input type="text" id="edit_mobile">
                            </span>

                            {{-- <span class="input-set">
                                <label>Check-In Date</label>
                                <!-- <input type="date" id="edit_check_in_date"> -->
                                <!-- <input type="datetime-local" id="edit_check_in_date">
                                <small id="check_in_display"></small> -->
                                <input type="text" class="datetime-picker" id="edit_check_in_date">

                            </span> --}}

                            <span class="input-set">
                                <label>Gender</label>
                                <select id="edit_gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Father's Name</label>
                                <input type="text" id="edit_father_name">
                            </span>

                            <span class="input-set">
                                <label>Mother's Name</label>
                                <input type="text" id="edit_mother_name">
                            </span>

                            <span class="input-set">
                                <label>Guardian's Name</label>
                                <input type="text" id="edit_guardian_name">
                            </span>

                            <span class="input-set">
                                <label>Parent's Contact</label>
                                <input type="text" id="edit_parent_contact">
                            </span>
                            <span class="input-set">
                                <label>Guardian's Contact</label>
                                <input type="text" id="edit_guardian_contact">
                            </span>

                            <span class="input-set">
                                <label>Emergency Contact</label>
                                <input type="text" name="emergency_contact" id="edit_emergency_contact">
                            </span>

                            <span class="input-set">
                                <label class="form-label">Status</label>
                                <select id="editstatus" name="status" class="" required>
                                    <option value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="checkout">Checkout</option>
                                    <option value="checkedout">Checkout</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </span>

                            <span class="input-set">
                                <label>Check-Out Date</label>
                                <!-- <input type="date" id="edit_check_in_date"> -->
                                <!-- <input type="datetime-local" id="edit_check_in_date">
                                <small id="check_in_display"></small> -->
                                <input type="text" class="datetime-picker" id="edit_check_out_date">

                            </span> 

                        </div>


                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="submit" class="blue" id="residentSubmitBtn"> Update Resident</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="profileModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Resident Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="profileModalBody">
                    <div class="text-center text-muted py-5">Loading profile...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        (function($) {
            "use strict";


            const ResidentApp = {

                config: {
                    apiUrl: "{{ url('/api/admin/residents') }}",
                    token: localStorage.getItem('token'),
                    table: '#residentTable',
                    tbody: '#residentList',
                    modal: '#editResidentModal',
                },

                // dataTable: null,

                init() {
                    this.bindEvents();
                    // this.initDataTable();   // ✅ only here
                    this.fetchResidents();
                },

                apiRequest(url, method = 'GET', data = {}) {
                    return $.ajax({
                        url,
                        method,
                        data,
                        headers: {
                            Authorization: `Bearer ${this.config.token}`,
                            Accept: 'application/json'
                        }
                    });
                },

                fetchResidents() {
                    this.showTableLoader();
                    this.apiRequest(this.config.apiUrl)
                        .done(res => this.renderTable(res.data))
                        .fail(err => this.handleError(err, 'Failed to load residents'));
                },

                // renderTable(residents) {
                //     const tbody = $(this.config.tbody).empty();

                //     if (!Array.isArray(residents) || !residents.length) {
                //         tbody.append(
                //             `<tr><td colspan="16" class="text-center text-muted">No records found</td></tr>`);
                //         return;
                //     }

                //     residents.forEach((r, i) => {
                //         tbody.append(this.rowTemplate(r, i));
                //     });

                //     this.initDataTable();

                //     // // ✅ Tell DataTable to recalc layout
                //     this.dataTable.rows().invalidate().draw(false);
                // },

                // initDataTable() {
                //     if (!this.dataTable) {
                //         this.dataTable = $(this.config.table).DataTable({
                //             responsive: {
                //                 details: {
                //                     type: 'column',
                //                     target: 0 // control column
                //                 }
                //             },
                //             autoWidth: false,
                //             columnDefs: [{
                //                     className: 'dtr-control',
                //                     orderable: false,
                //                     targets: 0
                //                 }, // control column
                //                 {
                //                     responsivePriority: 1,
                //                     targets: -1
                //                 }, // Action
                //                 {
                //                     responsivePriority: 2,
                //                     targets: 2
                //                 }, // Scholar No
                //                 {
                //                     responsivePriority: 3,
                //                     targets: 3
                //                 } // Name
                //             ],
                //             order: [
                //                 [1, 'asc']
                //             ]
                //         });
                //     }
                // },

                renderTable(residents) {
                    const tbody = $(this.config.tbody);

                    // Clear current rows in DataTable if initialized
                    if (this.dataTable) {
                        this.dataTable.clear();
                    } else {
                        tbody.empty(); // fallback for first load
                    }

                    if (!Array.isArray(residents) || residents.length === 0) {
                        tbody.append(
                            `<tr><td colspan="16" class="text-center text-muted">No records found</td></tr>`);
                        if (this.dataTable) this.dataTable.draw(false);
                        return;
                    }

                    residents.forEach((r, i) => {
                        const rowNode = $(this.rowTemplate(r, i));
                        if (this.dataTable) {
                            this.dataTable.row.add(rowNode);
                        } else {
                            tbody.append(rowNode);
                        }
                    });

                    // Draw table if initialized
                    if (this.dataTable) this.dataTable.draw(false);

                    // Initialize if not done yet
                    this.initDataTable();
                },

                rowTemplate(r, index) {
                    return `
                        <tr>
                              <td class="dtr-control noExport"></td> <!-- 🔥 REQUIRED -->
                            <td>${index + 1}</td>
                            <td>
                                <a href="javascript:void(0)"
                                class="text-primary fw-semibold view-profile"
                                data-id="${r.id}">
                                ${r.scholar_no ?? 'N/A'}
                                </a>
                            </td>
                            <td>${r.name ?? 'N/A'}</td>
                            <td>${r.email ?? 'N/A'}</td>
                            <td>${r.mobile ?? 'N/A'}</td>
                          
                            

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-nowrap">${this.formatDateTime(r.check_in_date)}</span>
                                    <button type="button" 
                                            class="btn btn-link p-0 update-checkin"
                                            data-id="${r.id}" data-date="${r.check_in_date ?? ''}">
                                    <i class="bi bi-calendar-event"></i>
                                    </button>
                                </div>
                            </td>





                            <td>${r.gender ?? 'N/A'}</td>    
                             <td>${r.hostel?.building ?? 'N/A'}</td>                      
                            <td>${r.hostel?.room ?? 'N/A'}</td>
                             <td>${r.hostel?.bed ?? 'N/A'}</td>
                             <td>${r.fathers_name ?? 'N/A'}</td>
                            <td>${r.mothers_name ?? 'N/A'}</td>
                             <td>${r.parent_contact ?? 'N/A'}</td>
                             <td>${r.guardians_name ?? 'N/A'}</td>
                             <td>${r.guardians_contact ?? 'N/A'}</td>
                            <td>${r.academic?.faculty ?? 'N/A'}</td>
                            <td>${r.academic?.department ?? 'N/A'}</td>
                            <td>${r.academic?.course ?? 'N/A'}</td>
                            <td>
                                <span class="badge ${this.statusClass(r.status)}">
                                    ${r.status ?? 'N/A'}
                                </span>
                            </td>
                            <td>${r.created_at ?? 'N/A'}</td>
                            <td class="noExport">
                                <button class="btn btn-sm btn-outline-primary view-profile"
                                        data-id="${r.id}">
                                    View
                                </button>
                                <button class="btn btn-sm btn-outline-dark editResidentBtn"
                                        data-id="${r.id}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger deleteResidentBtn"
                                        data-id="${r.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                },

                // <td>
                //                 <div class="d-flex align-items-center gap-2">
                //                     <span class="text-nowrap">
                //                         ${
                //                             r.check_in_date
                //                                 ? new Date(r.check_in_date).toLocaleString()
                //                                 : '<span class="text-muted">Not set</span>'
                //                         }
                //                     </span>

                //                     <button class="btn btn-xs btn-outline-primary update-checkin"
                //                             data-id="${r.id}"
                //                             data-date="${r.check_in_date ?? ''}">
                //                         <i class="bi bi-calendar-event"></i>
                //                     </button>
                //                 </div>
                //             </td>

                // loadResident(id, type = 'edit') {
                loadResident(id = null, type = 'edit') {

                    $(this.config.modal).modal('show');
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    // ✅ ADD MODE
                    if (type === 'add') {
                        this.populateModal({}, 'add');
                        return;
                    }

                    // VIEW / EDIT
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    this.apiRequest(`${this.config.apiUrl}/${id}`)
                        .done(res => {
                            this.populateModal(res.data, type);
                        })
                        .fail(err => this.handleError(err, 'Failed to load resident'));
                },

                // populateModal(data, type = 'edit') {
                populateModal(data = {}, type = 'edit') {

                    const isView = type === 'view';
                    const isAdd = type === 'add';

                    // 🔹 Title
                    $('#residentModalTitle').text(
                        isAdd ? 'Add Resident' :
                        isView ? 'View Resident' :
                        'Edit Resident'
                    );

                    // 🔹 Button
                    $('#residentSubmitBtn')
                        .text(isAdd ? 'Create Resident' : 'Update Resident')
                        .toggle(!isView);

                    // 🔹 Reset first
                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val(isAdd ? '' : data.id);

                    // $('#edit_resident_id').val(data.id ?? '');
                    $('#edit_scholar_no').val(data.scholar_no ?? '');
                    $('#edit_name').val(data.name ?? '');
                    $('#edit_email').val(data.email ?? '');
                    $('#edit_mobile').val(data.mobile ?? data.number ?? '');
                    // $('#edit_check_in_date').val(data.check_in_date ?? '');
                    // Example: data.check_in_date = "2025-12-01T18:30:00.000000Z"
                    // if (data.check_in_date) {
                    //     // Convert to YYYY-MM-DD
                    //     const dateOnly = new Date(data.check_in_date).toISOString().split("T")[0];
                    //     $('#edit_check_in_date').val(dateOnly);
                    // } else {
                    //     $('#edit_check_in_date').val('');
                    // }
                    // if (data.check_in_date) {
                    //     const dt = new Date(data.check_in_date);
                    //     // Format as YYYY-MM-DDTHH:mm for datetime-local
                    //     const formatted = dt.toISOString().slice(0, 16);
                    //     $('#edit_check_in_date').val(formatted);

                    // }

                    // if (data.check_in_date) {
                    //     const dt = new Date(data.check_in_date);

                    //     // Get local components
                    //     const year = dt.getFullYear();
                    //     const month = String(dt.getMonth() + 1).padStart(2, '0');
                    //     const day = String(dt.getDate()).padStart(2, '0');
                    //     const hours = String(dt.getHours()).padStart(2, '0');
                    //     const minutes = String(dt.getMinutes()).padStart(2, '0');

                    //     // Format for datetime-local: YYYY-MM-DDTHH:mm
                    //     const formatted = `${year}-${month}-${day}T${hours}:${minutes}`;

                    //     $('#edit_check_in_date').val(formatted);
                    // } else {
                    //     $('#edit_check_in_date').val('');
                    // }

                    // if (data.check_in_date) {
                    //     const dt = new Date(data.check_in_date);

                    //     // Prefill input (machine format)
                    //     const year = dt.getFullYear();
                    //     const month = String(dt.getMonth() + 1).padStart(2, '0');
                    //     const day = String(dt.getDate()).padStart(2, '0');
                    //     const hours = String(dt.getHours()).padStart(2, '0');
                    //     const minutes = String(dt.getMinutes()).padStart(2, '0');
                    //     const formatted = `${year}-${month}-${day}T${hours}:${minutes}`;
                    //     $('#edit_check_in_date').val(formatted);

                    //     // Show user-friendly display
                    //     const options = {
                    //         day: '2-digit',
                    //         month: 'short',
                    //         year: 'numeric',
                    //         hour: 'numeric',
                    //         minute: 'numeric',
                    //         hour12: true
                    //     };
                    //     $('#check_in_display').text(dt.toLocaleString('en-GB', options));
                    // } else {
                    //     $('#edit_check_in_date').val('');
                    //     $('#check_in_display').text('');
                    // }

                    // When loading JSON 
                    if (data.check_in_date) {
                        picker.setDate(data.check_in_date); // accepts "2025-12-01T12:00" 
                    }
                    $('#edit_gender').val(data.gender ?? '');

                    $('#edit_father_name').val(data.fathers_name ?? '');
                    $('#edit_mother_name').val(data.mothers_name ?? '');
                    $('#edit_parent_contact').val(data.parent_contact ?? '');
                    $('#edit_guardian_name').val(data.guardians_name ?? '');
                    $('#edit_guardian_contact').val(data.guardian_contact ?? '');
                    // $('#editstatus').data(data.status);
                    // $('#editstatus').val(data.status === 'active' ? 1 : 0);
                    if (data.status) {
                        $('#editstatus').val(data.status); // e.g. "active", "pending", etc.
                    } else {
                        $('#editstatus').val(''); // or leave blank
                    }

                    // Disable fields if view-only
                    if (type === 'view') {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', true);
                    } else {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', false);
                    }

                    $('#editResponse').empty();
                },

                submitResidentForm() {
                    $('#editResidentForm').submit(e => {
                        e.preventDefault();

                        this.clearFormErrors();

                        let id = $('#edit_resident_id').val();

                        const isEdit = Boolean(id);

                        const payload = {
                            scholar_no: $('#edit_scholar_no').val(),
                            name: $('#edit_name').val(),
                            email: $('#edit_email').val(),

                            mobile: $('#edit_mobile').val(),
                            gender: $('#edit_gender').val(),
                            check_in_date: $('#edit_check_in_date').val(),

                            fathers_name: $('#edit_father_name').val(),
                            mothers_name: $('#edit_mother_name').val(),
                            parent_no: $('#edit_parent_contact').val(),
                            guardian_no: $('#edit_guardian_name').val(),
                            guardian_no: $('#edit_guardian_contact').val(),

                            status: $('#editstatus').val(),

                        };


                        // Frontend validation
                        if (!payload.name || !payload.email || !payload.scholar_no) {
                            $('#editResponse').html(
                                `<div class="alert alert-danger">Please fill all required fields.</div>`
                            );
                            return;
                        }

                        const method = id ? 'PUT' : 'POST';
                        const url = id ? `${this.config.apiUrl}/${id}` : this.config.apiUrl;

                        this.apiRequest(url, method, payload)
                            // .done(res => {
                            //     $('#editResponse').html(
                            //         `<div class="alert alert-success">${res.message}</div>`);
                            //     setTimeout(() => {
                            //         $(this.config.modal).modal('hide');
                            //         this.fetchResidents();
                            //     }, 1000);
                            // })
                            .done(res => {
                                $('#editResponse').html(
                                    `<div class="alert alert-success">${res.message}</div>`
                                );

                                Swal.fire('Success', res.message, 'success');

                                setTimeout(() => {
                                    $(this.config.modal).modal('hide');
                                    this.fetchResidents(); // ✅ SAFE now
                                }, 800);
                            })

                            // .fail(err => {
                            //     let msg = err.responseJSON?.message ?? 'Something went wrong';
                            //     $('#editResponse').html(`<div class="alert alert-danger">${msg}</div>`);
                            // });
                            .fail(xhr => {
                                if (xhr.status === 422) {
                                    // this.showFieldErrors(xhr.responseJSON.errors);
                                    this.applyFormErrors(xhr.responseJSON.errors);
                                } else {
                                    Swal.fire('Error', xhr.responseJSON?.message ||
                                        'Something went wrong', 'error');
                                }
                            });
                    });
                },

                clearFormErrors() {
                    $('#editResidentForm .is-invalid').removeClass('is-invalid');
                    $('#editResidentForm .invalid-feedback').remove();
                },

                applyFormErrors(errors) {
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');
                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },


                showFieldErrors(errors) {
                    // Object.entries(errors).forEach(([field, messages]) => {
                    //     const input = $(`#edit_${field}`);
                    //     if (!input.length) return;

                    //     input.addClass('is-invalid');
                    //     input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    // });
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');

                        // Remove old error (safety)
                        input.next('.invalid-feedback').remove();

                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },


                deleteResident(id) {
                    Swal.fire({
                        title: 'Delete Resident?',
                        text: 'This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        this.apiRequest(`${this.config.apiUrl}/${id}`, 'DELETE')
                            .done(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                this.fetchResidents();

                            })
                            .fail(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: err.responseJSON?.message ??
                                        'Unable to delete resident'
                                });
                            });
                    });
                },


                statusClass(status) {
                    return {
                        active: 'bg-success',
                        pending: 'bg-warning text-dark',
                        rejected: 'bg-danger',
                        verified: 'bg-primary'
                    } [status] || 'bg-secondary';
                },

                // initDataTable() {
                //     if ($.fn.DataTable.isDataTable(this.config.table)) {
                //         $(this.config.table).DataTable().destroy();
                //     }
                //     setTimeout(() => {
                //         $(this.config.table).DataTable({
                //             responsive: true,
                //             autoWidth: false,
                //             columnDefs: [{
                //                     responsivePriority: 1,
                //                     targets: -1
                //                 }, // Action
                //                 {
                //                     responsivePriority: 2,
                //                     targets: 1
                //                 }, // Scholar No
                //                 {
                //                     responsivePriority: 3,
                //                     targets: 2
                //                 } // Name
                //             ]
                //         });
                //     }, 50);
                // },

                // initDataTable() {

                //     if (this.dataTable) return; // 🔒 already initialized


                //     setTimeout(() => {
                //         // $(this.config.table).DataTable({
                //         this.dataTable = $(this.config.table).DataTable({
                //             responsive: {
                //                 details: {
                //                     type: 'column', // 🔥 enables dtr-control
                //                     target: 0 // 🔥 first column
                //                 }
                //             },
                //             autoWidth: false,
                //             columnDefs: [{
                //                     className: 'dtr-control',
                //                     orderable: false,
                //                     targets: 0 // 🔥 control column
                //                 },
                //                 {
                //                     responsivePriority: 1,
                //                     targets: -1 // Action
                //                 },
                //                 {
                //                     responsivePriority: 2,
                //                     targets: 2 // Scholar No
                //                 },
                //                 {
                //                     responsivePriority: 3,
                //                     targets: 3 // Name
                //                 }
                //             ],
                //             dom: `
            //                        <'row mb-2'
            //                                <'col-12 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start'l>
            //                                <'col-12 col-md-5 d-flex align-items-center justify-content-center'B>
            //                                <'col-12 col-md-4 d-flex align-items-center justify-content-center justify-content-md-end'f>
            //                            >
            //                            <'row'<'col-12'tr>>
            //                            <'row mt-2'
            //                                <'col-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>
            //                                <'col-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>
            //                            >
            //                        `,
                //             buttons: [{
                //                     extend: "copy",
                //                     className: "btn btn-sm btn-outline-primary me-1"
                //                 },
                //                 {
                //                     extend: "csv",
                //                     className: "btn btn-sm btn-outline-success me-1"
                //                 },
                //                 {
                //                     extend: "excel",
                //                     className: "btn btn-sm btn-outline-info me-1"
                //                 },
                //                 {
                //                     extend: "pdfHtml5",
                //                     className: "btn btn-sm btn-outline-danger me-1"
                //                 },
                //                 {
                //                     extend: "print",
                //                     className: "btn btn-sm btn-outline-secondary"
                //                 }
                //             ],
                //             order: [
                //                 [1, 'asc']
                //             ]
                //         });
                //     }, 50);
                // },

                initDataTable() {
                    // Prevent double init
                    if (this.dataTable) return;

                    const $table = $(this.config.table);
                    if ($table.length === 0) return;

                    // Read toggles from HTML (fallback to true)
                    const readBool = (val, def = true) => {
                        if (val === undefined || val === null || val === '') return def;
                        // Accept true/false, 'true'/'false', 1/0
                        if (typeof val === 'boolean') return val;
                        const s = String(val).toLowerCase().trim();
                        if (['true', '1', 'yes', 'y'].includes(s)) return true;
                        if (['false', '0', 'no', 'n'].includes(s)) return false;
                        return def;
                    };

                    const showLength = readBool($table.data('show-length'), true);
                    const showSearch = readBool($table.data('show-search'), true);
                    const showButtons = readBool($table.data('show-buttons'), true);

                    // Optional: custom length menu via HTML, e.g. data-length-menu="[10,25,50,100,-1]"
                    let lengthMenuAttr = $table.attr('data-length-menu');
                    let lengthMenu = undefined;
                    if (lengthMenuAttr) {
                        try {
                            const parsed = JSON.parse(lengthMenuAttr);
                            // If only numbers provided, derive labels; add “All” for -1
                            const values = Array.isArray(parsed) ? parsed : [];
                            const labels = values.map(v => (Number(v) === -1 ? 'All' : String(v)));
                            lengthMenu = [values, labels];
                        } catch (_) {
                            // ignore parse errors
                        }
                    }

                    // Build DOM row for top area (ensure spaces between columns)
                    const topCols = [];
                    if (showLength) topCols.push(
                        "<'col-12 col-md-3 d-flex align-items-center justify-content-md-start'l>");
                    if (showButtons) topCols.push(
                        "<'col-12 col-md-5 d-flex align-items-center justify-content-center'B>");
                    if (showSearch) topCols.push(
                        "<'col-12 col-md-4 d-flex align-items-center justify-content-md-end'f>");

                    const domLayout = `
                        <'row mb-2' ${topCols.join(' ')}>
                        <'row'<'col-12'tr>>
                        <'row mt-2'
                        <'col-12 col-md-5 d-flex align-items-center justify-content-md-start'i>
                        <'col-12 col-md-7 d-flex align-items-center justify-content-md-end'p>
                        >
                    `.trim();

                    // Defer init slightly if your layout isn’t ready
                    setTimeout(() => {
                        this.dataTable = $table.DataTable({
                            responsive: {
                                details: {
                                    type: 'column',
                                    target: 0
                                }
                            },
                            autoWidth: false,
                            columnDefs: [{
                                    className: 'dtr-control',
                                    orderable: false,
                                    targets: 0
                                },
                                {
                                    responsivePriority: 1,
                                    targets: -1
                                }, // Action
                                {
                                    responsivePriority: 2,
                                    targets: 2
                                }, // Scholar No
                                {
                                    responsivePriority: 3,
                                    targets: 3
                                } // Name
                            ],
                            dom: domLayout,
                            // Only set buttons option if visible; otherwise omit to avoid extension requirement
                            ...(showButtons ? {
                                buttons: [{
                                        extend: 'copy',
                                        className: 'btn btn-sm btn-outline-primary me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        className: 'btn btn-sm btn-outline-success me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-sm btn-outline-info me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        className: 'btn btn-sm btn-outline-danger me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        className: 'btn btn-sm btn-outline-secondary',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    }
                                ]

                            } : {}),
                            ...(lengthMenu ? {
                                lengthMenu
                            } : {}),
                            order: [
                                [1, 'asc']
                            ]
                        });
                    }, 50);
                },

                showTableLoader() {
                    $(this.config.tbody).html(
                        `<tr><td colspan="16" class="text-center text-muted">Loading...</td></tr>`);
                },

                handleError(xhr, message) {
                    console.error(message, xhr);
                    alert(message);
                },

                bindEvents() {
                    $(document).on('click', '#addResidentBtn', () =>
                        this.loadResident(null, 'add')
                    );

                    $(document).on('click', '.view-profile', e => this.loadResident($(e.currentTarget).data('id'),
                        'view'));
                    $(document).on('click', '.editResidentBtn', e => this.loadResident($(e.currentTarget).data(
                        'id'), 'edit'));
                    $(document).on('click', '.deleteResidentBtn', e => this.deleteResident($(e.currentTarget).data(
                        'id'), 'delete'));

                    // ✅ NEW: update check-in date
                    // $(document).on('click', '.update-checkin', e => {
                    //     this.updateCheckInDate(
                    //         $(e.currentTarget).data('id'),
                    //         $(e.currentTarget).data('date')
                    //     );
                    // });
                    // $(document).on('click', '.update-checkin', e => {
                    //     const btn = $(e.currentTarget);
                    //     const residentId = btn.data('id');
                    //     const existingDate = btn.data('date');

                    //     Swal.fire({
                    //         title: 'Update Check-In Date',
                    //         input: 'datetime-local',
                    //         inputLabel: 'Check-In Date & Time',
                    //         inputValue: existingDate ?
                    //             new Date(existingDate).toISOString().slice(0, 16) : '',
                    //         showCancelButton: true,
                    //         confirmButtonText: 'Update',
                    //         cancelButtonText: 'Cancel',
                    //         inputValidator: value => {
                    //             if (!value) {
                    //                 return 'Check-in date is required';
                    //             }
                    //         }
                    //     }).then(result => {
                    //         if (!result.isConfirmed) return;

                    //         this.updateCheckInDate(residentId, result.value);
                    //     });
                    // });
                    $(document).on('click', '.update-checkin', function() {

                        const residentId = $(this).data('id');
                        let currentDate = $(this).data('date');

                        // Convert ISO to datetime-local format
                        if (currentDate) {
                            currentDate = currentDate.replace('T', ' ').slice(0, 16);
                        }

                        Swal.fire({
                            title: 'Update Check-in Date',
                            html: `
                                <input type="datetime-local"
                                    id="checkinDate"
                                    class="form-control datetime-picker"
                                    value="${currentDate ?? ''}">
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            preConfirm: () => {
                                const value = document.getElementById('checkinDate').value;
                                if (!value) {
                                    Swal.showValidationMessage('Please select a date');
                                }
                                return value;
                            }
                        }).then(result => {
                            if (!result.isConfirmed) return;

                            ResidentApp.updateCheckInDate(residentId, result.value);
                        });

                    });


                    this.submitResidentForm();
                },

                openAddModal() {
                    $('#residentModalTitle').text('Add Resident');
                    $('#residentSubmitBtn').text('Create Resident').show();

                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val('');

                    this.toggleForm(true);

                    $('#editResidentModal').modal('show');
                },


                // updateCheckInDate(id, currentDate = '') {

                //     Swal.fire({
                //         title: 'Update Check-In Date',
                //         input: 'date',
                //         inputValue: currentDate || '',
                //         inputAttributes: {
                //             required: true
                //         },
                //         showCancelButton: true,
                //         confirmButtonText: 'Update',
                //         confirmButtonColor: '#0d6efd',
                //         cancelButtonText: 'Cancel',
                //         preConfirm: (date) => {
                //             if (!date) {
                //                 Swal.showValidationMessage('Please select a date');
                //             }
                //             return date;
                //         }
                //     }).then(result => {

                //         if (!result.isConfirmed) return;

                //         this.apiRequest(
                //                 `${this.config.apiUrl}/${id}/check-in`,
                //                 'PUT', {
                //                     check_in_date: result.value
                //                 }
                //             )
                //             .done(res => {
                //                 Swal.fire({
                //                     icon: 'success',
                //                     title: 'Updated',
                //                     text: res.message,
                //                     timer: 1200,
                //                     showConfirmButton: false
                //                 });

                //                 // ✅ reload data safely
                //                 this.fetchResidents();
                //             })
                //             .fail(err => this.handleAjaxError(err));
                //     });
                // },

                updateCheckInDate(id, checkInDate) {
                    console.log(id, checkInDate);
                    this.apiRequest(`${this.config.apiUrl}/${id}/check-in`, 'PUT', {
                            check_in_date: checkInDate
                        })
                        .done(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // 🔁 Refresh table safely
                            this.fetchResidents();
                        })
                        .fail(xhr => {
                            const msg = xhr.responseJSON?.message ?? 'Failed to update date';
                            Swal.fire('Error', msg, 'error');
                        });
                },

                formatDateTime(value) {
                    if (!value) return '<span class="text-muted">Not set</span>';

                    const d = new Date(value);
                    if (isNaN(d)) return '<span class="text-muted">Invalid</span>';

                    return d.toLocaleString('en-IN', {
                        day: '2-digit',
                        // month: '2-digit',
                        month: 'short', // ✅ Dec instead of 12
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                },

            };



            $(document).ready(() => ResidentApp.init());

        })(jQuery);
    </script> --}}

    {{-- Client Side Script --}}
    {{-- <script>
        (function($) {
            "use strict";


            const ResidentApp = {

                config: {
                    apiUrl: "{{ url('/api/admin/residents') }}",
                    token: localStorage.getItem('token'),
                    table: '#residentTable',
                    tbody: '#residentList',
                    modal: '#editResidentModal',
                },

                // dataTable: null,

                init() {
                    this.bindEvents();
                    // this.initDataTable();   // ✅ only here
                    this.fetchResidents();
                },

                apiRequest(url, method = 'GET', data = {}) {
                    return $.ajax({
                        url,
                        method,
                        data,
                        headers: {
                            Authorization: `Bearer ${this.config.token}`,
                            Accept: 'application/json'
                        }
                    });
                },

                fetchResidents() {
                    this.showTableLoader();
                    this.apiRequest(this.config.apiUrl)
                        .done(res => this.renderTable(res.data))
                        .fail(err => this.handleError(err, 'Failed to load residents'));
                },

                renderTable(residents) {
                    const tbody = $(this.config.tbody);

                    // Clear current rows in DataTable if initialized
                    if (this.dataTable) {
                        this.dataTable.clear();
                    } else {
                        tbody.empty(); // fallback for first load
                    }

                    if (!Array.isArray(residents) || residents.length === 0) {
                        tbody.append(
                            `<tr><td colspan="16" class="text-center text-muted">No records found</td></tr>`);
                        if (this.dataTable) this.dataTable.draw(false);
                        return;
                    }

                    residents.forEach((r, i) => {
                        const rowNode = $(this.rowTemplate(r, i));
                        if (this.dataTable) {
                            this.dataTable.row.add(rowNode);
                        } else {
                            tbody.append(rowNode);
                        }
                    });

                    // Draw table if initialized
                    if (this.dataTable) this.dataTable.draw(false);

                    // Initialize if not done yet
                    this.initDataTable();
                },

                rowTemplate(r, index) {
                    return `
                        <tr>
                              <td class="dtr-control noExport"></td> <!-- 🔥 REQUIRED -->
                            <td>${index + 1}</td>
                            <td>
                                <a href="javascript:void(0)"
                                class="text-primary fw-semibold view-profile"
                                data-id="${r.id}">
                                ${r.scholar_no ?? 'N/A'}
                                </a>
                            </td>
                            <td>${r.name ?? 'N/A'}</td>
                            <td>${r.email ?? 'N/A'}</td>
                            <td>${r.mobile ?? 'N/A'}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-nowrap">${this.formatDateTime(r.check_in_date)}</span>
                                    <button type="button" 
                                            class="btn btn-link p-0 update-checkin"
                                            data-id="${r.id}" data-date="${r.check_in_date ?? ''}">
                                    <i class="bi bi-calendar-event"></i>
                                    </button>
                                </div>
                            </td>

                            <td>${r.gender ?? 'N/A'}</td>    
                             <td>${r.hostel?.building ?? 'N/A'}</td>                      
                            <td>${r.hostel?.room ?? 'N/A'}</td>
                             <td>${r.hostel?.bed ?? 'N/A'}</td>
                             <td>${r.fathers_name ?? 'N/A'}</td>
                            <td>${r.mothers_name ?? 'N/A'}</td>
                             <td>${r.parent_contact ?? 'N/A'}</td>
                             <td>${r.guardians_name ?? 'N/A'}</td>
                             <td>${r.guardians_contact ?? 'N/A'}</td>
                            <td>${r.academic?.faculty ?? 'N/A'}</td>
                            <td>${r.academic?.department ?? 'N/A'}</td>
                            <td>${r.academic?.course ?? 'N/A'}</td>
                            <td>
                                <span class="badge ${this.statusClass(r.status)}">
                                    ${r.status ?? 'N/A'}
                                </span>
                            </td>
                            <td>${r.created_at ?? 'N/A'}</td>
                            <td class="noExport">
                                <button class="btn btn-sm btn-outline-primary view-profile"
                                        data-id="${r.id}">
                                    View
                                </button>
                                <button class="btn btn-sm btn-outline-dark editResidentBtn"
                                        data-id="${r.id}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger deleteResidentBtn"
                                        data-id="${r.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                },

                // loadResident(id, type = 'edit') {
                loadResident(id = null, type = 'edit') {

                    $(this.config.modal).modal('show');
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    // ✅ ADD MODE
                    if (type === 'add') {
                        this.populateModal({}, 'add');
                        return;
                    }

                    // VIEW / EDIT
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    this.apiRequest(`${this.config.apiUrl}/${id}`)
                        .done(res => {
                            this.populateModal(res.data, type);
                        })
                        .fail(err => this.handleError(err, 'Failed to load resident'));
                },

                // populateModal(data, type = 'edit') {
                populateModal(data = {}, type = 'edit') {

                    const isView = type === 'view';
                    const isAdd = type === 'add';

                    // 🔹 Title
                    $('#residentModalTitle').text(
                        isAdd ? 'Add Resident' :
                        isView ? 'View Resident' :
                        'Edit Resident'
                    );

                    // 🔹 Button
                    $('#residentSubmitBtn')
                        .text(isAdd ? 'Create Resident' : 'Update Resident')
                        .toggle(!isView);

                    // 🔹 Reset first
                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val(isAdd ? '' : data.id);

                    // $('#edit_resident_id').val(data.id ?? '');
                    $('#edit_scholar_no').val(data.scholar_no ?? '');
                    $('#edit_name').val(data.name ?? '');
                    $('#edit_email').val(data.email ?? '');
                    $('#edit_mobile').val(data.mobile ?? data.number ?? '');

                    // When loading JSON 
                    if (data.check_in_date) {
                        picker.setDate(data.check_in_date); // accepts "2025-12-01T12:00" 
                    }
                    $('#edit_gender').val(data.gender ?? '');

                    $('#edit_father_name').val(data.fathers_name ?? '');
                    $('#edit_mother_name').val(data.mothers_name ?? '');
                    $('#edit_parent_contact').val(data.parent_contact ?? '');
                    $('#edit_guardian_name').val(data.guardians_name ?? '');
                    $('#edit_guardian_contact').val(data.guardian_contact ?? '');
                    // $('#editstatus').data(data.status);
                    // $('#editstatus').val(data.status === 'active' ? 1 : 0);
                    if (data.status) {
                        $('#editstatus').val(data.status); // e.g. "active", "pending", etc.
                    } else {
                        $('#editstatus').val(''); // or leave blank
                    }

                    // Disable fields if view-only
                    if (type === 'view') {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', true);
                    } else {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', false);
                    }

                    $('#editResponse').empty();
                },

                submitResidentForm() {
                    $('#editResidentForm').submit(e => {
                        e.preventDefault();

                        this.clearFormErrors();

                        let id = $('#edit_resident_id').val();

                        const isEdit = Boolean(id);

                        const payload = {
                            scholar_no: $('#edit_scholar_no').val(),
                            name: $('#edit_name').val(),
                            email: $('#edit_email').val(),

                            mobile: $('#edit_mobile').val(),
                            gender: $('#edit_gender').val(),
                            check_in_date: $('#edit_check_in_date').val(),

                            fathers_name: $('#edit_father_name').val(),
                            mothers_name: $('#edit_mother_name').val(),
                            parent_no: $('#edit_parent_contact').val(),
                            guardian_no: $('#edit_guardian_name').val(),
                            guardian_no: $('#edit_guardian_contact').val(),

                            status: $('#editstatus').val(),

                        };

                        // Frontend validation
                        if (!payload.name || !payload.email || !payload.scholar_no) {
                            $('#editResponse').html(
                                `<div class="alert alert-danger">Please fill all required fields.</div>`
                            );
                            return;
                        }

                        const method = id ? 'PUT' : 'POST';
                        const url = id ? `${this.config.apiUrl}/${id}` : this.config.apiUrl;

                        this.apiRequest(url, method, payload)
                            .done(res => {
                                $('#editResponse').html(
                                    `<div class="alert alert-success">${res.message}</div>`
                                );

                                Swal.fire('Success', res.message, 'success');

                                setTimeout(() => {
                                    $(this.config.modal).modal('hide');
                                    this.fetchResidents(); // ✅ SAFE now
                                }, 800);
                            })

                            // .fail(err => {
                            //     let msg = err.responseJSON?.message ?? 'Something went wrong';
                            //     $('#editResponse').html(`<div class="alert alert-danger">${msg}</div>`);
                            // });
                            .fail(xhr => {
                                if (xhr.status === 422) {
                                    // this.showFieldErrors(xhr.responseJSON.errors);
                                    this.applyFormErrors(xhr.responseJSON.errors);
                                } else {
                                    Swal.fire('Error', xhr.responseJSON?.message ||
                                        'Something went wrong', 'error');
                                }
                            });
                    });
                },

                clearFormErrors() {
                    $('#editResidentForm .is-invalid').removeClass('is-invalid');
                    $('#editResidentForm .invalid-feedback').remove();
                },

                applyFormErrors(errors) {
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');
                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },


                showFieldErrors(errors) {
                    // Object.entries(errors).forEach(([field, messages]) => {
                    //     const input = $(`#edit_${field}`);
                    //     if (!input.length) return;

                    //     input.addClass('is-invalid');
                    //     input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    // });
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');

                        // Remove old error (safety)
                        input.next('.invalid-feedback').remove();

                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },

                deleteResident(id) {
                    Swal.fire({
                        title: 'Delete Resident?',
                        text: 'This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        this.apiRequest(`${this.config.apiUrl}/${id}`, 'DELETE')
                            .done(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                this.fetchResidents();

                            })
                            .fail(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: err.responseJSON?.message ??
                                        'Unable to delete resident'
                                });
                            });
                    });
                },


                statusClass(status) {
                    return {
                        active: 'bg-success',
                        pending: 'bg-warning text-dark',
                        rejected: 'bg-danger',
                        verified: 'bg-primary'
                    } [status] || 'bg-secondary';
                },

                initDataTable() {
                    // Prevent double init
                    if (this.dataTable) return;

                    const $table = $(this.config.table);
                    if ($table.length === 0) return;

                    // Read toggles from HTML (fallback to true)
                    const readBool = (val, def = true) => {
                        if (val === undefined || val === null || val === '') return def;
                        // Accept true/false, 'true'/'false', 1/0
                        if (typeof val === 'boolean') return val;
                        const s = String(val).toLowerCase().trim();
                        if (['true', '1', 'yes', 'y'].includes(s)) return true;
                        if (['false', '0', 'no', 'n'].includes(s)) return false;
                        return def;
                    };

                    const showLength = readBool($table.data('show-length'), true);
                    const showSearch = readBool($table.data('show-search'), true);
                    const showButtons = readBool($table.data('show-buttons'), true);

                    // Optional: custom length menu via HTML, e.g. data-length-menu="[10,25,50,100,-1]"
                    let lengthMenuAttr = $table.attr('data-length-menu');
                    let lengthMenu = undefined;
                    if (lengthMenuAttr) {
                        try {
                            const parsed = JSON.parse(lengthMenuAttr);
                            // If only numbers provided, derive labels; add “All” for -1
                            const values = Array.isArray(parsed) ? parsed : [];
                            const labels = values.map(v => (Number(v) === -1 ? 'All' : String(v)));
                            lengthMenu = [values, labels];
                        } catch (_) {
                            // ignore parse errors
                        }
                    }

                    // Build DOM row for top area (ensure spaces between columns)
                    const topCols = [];
                    if (showLength) topCols.push(
                        "<'col-12 col-md-3 d-flex align-items-center justify-content-md-start'l>");
                    if (showButtons) topCols.push(
                        "<'col-12 col-md-5 d-flex align-items-center justify-content-center'B>");
                    if (showSearch) topCols.push(
                        "<'col-12 col-md-4 d-flex align-items-center justify-content-md-end'f>");

                    const domLayout = `
                        <'row mb-2' ${topCols.join(' ')}>
                        <'row'<'col-12'tr>>
                        <'row mt-2'
                        <'col-12 col-md-5 d-flex align-items-center justify-content-md-start'i>
                        <'col-12 col-md-7 d-flex align-items-center justify-content-md-end'p>
                        >
                    `.trim();

                    // Defer init slightly if your layout isn’t ready
                    setTimeout(() => {
                        this.dataTable = $table.DataTable({
                            responsive: {
                                details: {
                                    type: 'column',
                                    target: 0
                                }
                            },
                            autoWidth: false,
                            columnDefs: [{
                                    className: 'dtr-control',
                                    orderable: false,
                                    targets: 0
                                },
                                {
                                    responsivePriority: 1,
                                    targets: -1
                                }, // Action
                                {
                                    responsivePriority: 2,
                                    targets: 2
                                }, // Scholar No
                                {
                                    responsivePriority: 3,
                                    targets: 3
                                } // Name
                            ],
                            dom: domLayout,
                            // Only set buttons option if visible; otherwise omit to avoid extension requirement
                            ...(showButtons ? {
                                buttons: [{
                                        extend: 'copy',
                                        className: 'btn btn-sm btn-outline-primary me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        className: 'btn btn-sm btn-outline-success me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-sm btn-outline-info me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        className: 'btn btn-sm btn-outline-danger me-1',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        className: 'btn btn-sm btn-outline-secondary',
                                        exportOptions: {
                                            columns: ':visible:not(.noExport)'
                                        }
                                    }
                                ]

                            } : {}),
                            ...(lengthMenu ? {
                                lengthMenu
                            } : {}),
                            order: [
                                [1, 'asc']
                            ]
                        });
                    }, 50);
                },

                showTableLoader() {
                    $(this.config.tbody).html(
                        `<tr><td colspan="16" class="text-center text-muted">Loading...</td></tr>`);
                },

                handleError(xhr, message) {
                    console.error(message, xhr);
                    alert(message);
                },

                bindEvents() {
                    $(document).on('click', '#addResidentBtn', () =>
                        this.loadResident(null, 'add')
                    );

                    $(document).on('click', '.view-profile', e => this.loadResident($(e.currentTarget).data('id'),
                        'view'));
                    $(document).on('click', '.editResidentBtn', e => this.loadResident($(e.currentTarget).data(
                        'id'), 'edit'));
                    $(document).on('click', '.deleteResidentBtn', e => this.deleteResident($(e.currentTarget).data(
                        'id'), 'delete'));

                    $(document).on('click', '.update-checkin', function() {

                        const residentId = $(this).data('id');
                        let currentDate = $(this).data('date');

                        // Convert ISO to datetime-local format
                        if (currentDate) {
                            currentDate = currentDate.replace('T', ' ').slice(0, 16);
                        }

                        Swal.fire({
                            title: 'Update Check-in Date',
                            html: `
                                <input type="datetime-local"
                                    id="checkinDate"
                                    class="form-control datetime-picker"
                                    value="${currentDate ?? ''}">
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            preConfirm: () => {
                                const value = document.getElementById('checkinDate').value;
                                if (!value) {
                                    Swal.showValidationMessage('Please select a date');
                                }
                                return value;
                            }
                        }).then(result => {
                            if (!result.isConfirmed) return;

                            ResidentApp.updateCheckInDate(residentId, result.value);
                        });

                    });


                    this.submitResidentForm();
                },

                openAddModal() {
                    $('#residentModalTitle').text('Add Resident');
                    $('#residentSubmitBtn').text('Create Resident').show();

                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val('');

                    this.toggleForm(true);

                    $('#editResidentModal').modal('show');
                },

                updateCheckInDate(id, checkInDate) {
                    console.log(id, checkInDate);
                    this.apiRequest(`${this.config.apiUrl}/${id}/check-in`, 'PUT', {
                            check_in_date: checkInDate
                        })
                        .done(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // 🔁 Refresh table safely
                            this.fetchResidents();
                        })
                        .fail(xhr => {
                            const msg = xhr.responseJSON?.message ?? 'Failed to update date';
                            Swal.fire('Error', msg, 'error');
                        });
                },

                formatDateTime(value) {
                    if (!value) return '<span class="text-muted">Not set</span>';

                    const d = new Date(value);
                    if (isNaN(d)) return '<span class="text-muted">Invalid</span>';

                    return d.toLocaleString('en-IN', {
                        day: '2-digit',
                        // month: '2-digit',
                        month: 'short', // ✅ Dec instead of 12
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                },

            };

            $(document).ready(() => ResidentApp.init());

        })(jQuery);
    </script> --}}

    <script>
        (function($) {
            "use strict";


            const ResidentApp = {

                config: {
                    // apiUrl: "{{ url('/api/admin/residents') }}",
                    apiUrl: "{{ url('/api/admin/manage/residents') }}",
                    token: localStorage.getItem('token'),
                    table: '#residentTable',
                    tbody: '#residentList',
                    modal: '#editResidentModal',
                },

                // dataTable: null,
                reloadTable(resetPaging = false) {
                    // if (this.dataTable && $.fn.DataTable.isDataTable(this.config.table)) {
                    //     this.dataTable.ajax.reload(null, resetPaging);
                    // }
                    if (!this.dataTable) {
                        console.warn('DataTable not initialized yet');
                        return;
                    }
                    this.dataTable.ajax.reload(null, resetPaging);
                },

                init() {
                    this.bindEvents();
                    this.initDataTable(); // ✅ only here
                    // this.fetchResidents();
                    // this.reloadTable(false);

                },

                apiRequest(url, method = 'GET', data = {}) {
                    return $.ajax({
                        url,
                        method,
                        data,
                        headers: {
                            Authorization: `Bearer ${this.config.token}`,
                            Accept: 'application/json'
                        }
                    });
                },

                fetchResidents() {
                    this.showTableLoader();
                    this.apiRequest(this.config.apiUrl)
                        .done(res => this.renderTable(res.data))
                        .fail(err => this.handleError(err, 'Failed to load residents'));
                },

                renderTable(residents) {
                    const tbody = $(this.config.tbody);

                    // Clear current rows in DataTable if initialized
                    if (this.dataTable) {
                        this.dataTable.clear();
                    } else {
                        tbody.empty(); // fallback for first load
                    }

                    if (!Array.isArray(residents) || residents.length === 0) {
                        tbody.append(
                            `<tr><td colspan="16" class="text-center text-muted">No records found</td></tr>`);
                        if (this.dataTable) this.dataTable.draw(false);
                        return;
                    }

                    residents.forEach((r, i) => {
                        const rowNode = $(this.rowTemplate(r, i));
                        if (this.dataTable) {
                            this.dataTable.row.add(rowNode);
                        } else {
                            tbody.append(rowNode);
                        }
                    });

                    // Draw table if initialized
                    if (this.dataTable) this.dataTable.draw(false);

                    // Initialize if not done yet
                    this.initDataTable();
                },

                rowTemplate(r, index) {
                    return `
                        <tr>
                             <td>${index + 1}</td>

                            <td>${index + 1}</td>
                            <td>
                                <a href="javascript:void(0)"
                                class="text-primary fw-semibold view-profile"
                                data-id="${r.id}">
                                ${r.scholar_no ?? 'N/A'}
                                </a><br>
                                            Enrollment: ${r.scholar_no ?? 'N/A'}<br>
                            </td>
                            <td>${r.name ?? 'N/A'}</td>
                            <td>${r.email ?? 'N/A'}</td>
                            <td>${r.mobile ?? 'N/A'}</td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-nowrap">${this.formatDateTime(r.check_in_date)}</span>
                                    <button type="button" 
                                            class="btn btn-link p-0 update-checkin"
                                            data-id="${r.id}" data-date="${r.check_in_date ?? ''}">
                                    <i class="bi bi-calendar-event"></i>
                                    </button>
                                </div>
                            </td>

                            <td>${r.gender ?? 'N/A'}</td>    
                             <td>${r.hostel?.building ?? 'N/A'}</td>                      
                            <td>${r.hostel?.room ?? 'N/A'}</td>
                             <td>${r.hostel?.bed ?? 'N/A'}</td>
                             <td>${r.fathers_name ?? 'N/A'}</td>
                            <td>${r.mothers_name ?? 'N/A'}</td>
                             <td>${r.parent_contact ?? 'N/A'}</td>
                             <td>${r.guardians_name ?? 'N/A'}</td>
                             <td>${r.guardians_contact ?? 'N/A'}</td>
                            <td>${r.academic?.faculty ?? 'N/A'}</td>
                            <td>${r.academic?.department ?? 'N/A'}</td>
                            <td>${r.academic?.course ?? 'N/A'}</td>
                            <td>
                                <span class="badge ${this.statusClass(r.status)}">
                                    ${r.status ?? 'N/A'}
                                </span>
                            </td>
                            <td>${r.created_at ?? 'N/A'}</td>
                            <td class="noExport">
                                <button class="btn btn-sm btn-outline-primary view-profile"
                                        data-id="${r.id}">
                                    View
                                </button>
                                <button class="btn btn-sm btn-outline-dark editResidentBtn"
                                        data-id="${r.id}">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger deleteResidentBtn"
                                        data-id="${r.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                },

                // loadResident(id, type = 'edit') {
                loadResident(id = null, type = 'edit') {

                    $(this.config.modal).modal('show');
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    // ✅ ADD MODE
                    if (type === 'add') {
                        this.populateModal({}, 'add');
                        return;
                    }

                    // VIEW / EDIT
                    $('#editResponse').html(`<div class="text-center py-5">Loading...</div>`);

                    this.apiRequest(`${this.config.apiUrl}/${id}`)
                        .done(res => {
                            this.populateModal(res.data, type);
                        })
                        .fail(err => this.handleError(err, 'Failed to load resident'));
                },

                // populateModal(data, type = 'edit') {
                populateModal(data = {}, type = 'edit') {

                    const isView = type === 'view';
                    const isAdd = type === 'add';

                    // 🔹 Title
                    $('#residentModalTitle').text(
                        isAdd ? 'Add Resident' :
                        isView ? 'View Resident' :
                        'Edit Resident'
                    );

                    // 🔹 Button
                    // $('#residentSubmitBtn')
                    //     .text(isAdd ? 'Create Resident' : 'Update Resident')
                    //     .toggle(!isView);
                    //  🔹 Button 
                    if (isView) {
                        $('#residentSubmitBtn').hide();
                        // completely hide in view mode 
                    } else {
                        $('#residentSubmitBtn').text(isAdd ? 'Create Resident' : 'Update Resident')
                            .show(); // show in add/edit mode 
                    }

                    // 🔹 Reset first
                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val(isAdd ? '' : data.id);

                    // $('#edit_resident_id').val(data.id ?? '');
                    $('#edit_scholar_no').val(data.scholar_no ?? '');
                    $('#edit_name').val(data.name ?? '');
                    $('#edit_email').val(data.email ?? '');
                    $('#edit_mobile').val(data.mobile ?? data.number ?? '');

                    // When loading JSON 
                    if (data.check_in_date) {
                        picker.setDate(data.check_in_date); // accepts "2025-12-01T12:00" 
                    }
                    // $('#edit_gender').val(data.gender ?? '');
                    $('#edit_gender').val((data.gender ?? '').trim().toLowerCase()).trigger('change');

                    $('#edit_father_name').val(data.fathers_name ?? '');
                    $('#edit_mother_name').val(data.mothers_name ?? '');
                    $('#edit_parent_contact').val(data.parent_contact ?? '');
                    $('#edit_guardian_name').val(data.guardians_name ?? '');
                    $('#edit_guardian_contact').val(data.guardian_contact ?? '');
                    $('#edit_emergency_contact').val(data.emergency_contact ?? '');
                    // $('#editstatus').data(data.status);
                    // $('#editstatus').val(data.status === 'active' ? 1 : 0);
                    if (data.status) {
                        $('#editstatus').val(data.status); // e.g. "active", "pending", etc.
                    } else {
                        $('#editstatus').val(''); // or leave blank
                    }

                    // Disable fields if view-only
                    if (type === 'view') {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', true);
                    } else {
                        $('#editResidentForm input, #editResidentForm select, #editResidentForm button[type="submit"]')
                            .prop('disabled', false);
                    }

                    $('#editResponse').empty();
                },

                submitResidentForm() {
                    $('#editResidentForm').submit(e => {
                        e.preventDefault();

                        this.clearFormErrors();

                        let id = $('#edit_resident_id').val();

                        const isEdit = Boolean(id);

                        const payload = {
                            scholar_no: $('#edit_scholar_no').val(),
                            name: $('#edit_name').val(),
                            email: $('#edit_email').val(),

                            mobile: $('#edit_mobile').val(),
                            gender: $('#edit_gender').val(),
                            check_in_date: $('#edit_check_in_date').val(),

                            fathers_name: $('#edit_father_name').val(),
                            mothers_name: $('#edit_mother_name').val(),
                            parent_contact: $('#edit_parent_contact').val(),
                            guardian_name: $('#edit_guardian_name').val(),
                            guardian_contact: $('#edit_guardian_contact').val(),
                            emergency_contact: $('#edit_emergency_contact').val(),

                            status: $('#editstatus').val(),
                            check_out_date: $('#edit_check_out_date').val(),

                        };

                        // Frontend validation
                        if (!payload.name || !payload.email || !payload.scholar_no) {
                            $('#editResponse').html(
                                `<div class="alert alert-danger">Please fill all required fields.</div>`
                            );
                            return;
                        }

                        const method = id ? 'PUT' : 'POST';
                        const url = id ? `${this.config.apiUrl}/${id}` : this.config.apiUrl;

                        this.apiRequest(url, method, payload)
                            .done(res => {
                                $('#editResponse').html(
                                    `<div class="alert alert-success">${res.message}</div>`
                                );

                                Swal.fire('Success', res.message, 'success');

                                setTimeout(() => {
                                    $(this.config.modal).modal('hide');
                                    // this.fetchResidents(); // ✅ SAFE now
                                    this.reloadTable(false);

                                }, 800);
                            })

                            // .fail(err => {
                            //     let msg = err.responseJSON?.message ?? 'Something went wrong';
                            //     $('#editResponse').html(`<div class="alert alert-danger">${msg}</div>`);
                            // });
                            .fail(xhr => {
                                if (xhr.status === 422) {
                                    // this.showFieldErrors(xhr.responseJSON.errors);
                                    this.applyFormErrors(xhr.responseJSON.errors);
                                } else {
                                    Swal.fire('Error', xhr.responseJSON?.message ||
                                        'Something went wrong', 'error');
                                }
                            });
                    });
                },

                clearFormErrors() {
                    $('#editResidentForm .is-invalid').removeClass('is-invalid');
                    $('#editResidentForm .invalid-feedback').remove();
                },

                applyFormErrors(errors) {
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');
                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },

                showFieldErrors(errors) {
                    // Object.entries(errors).forEach(([field, messages]) => {
                    //     const input = $(`#edit_${field}`);
                    //     if (!input.length) return;

                    //     input.addClass('is-invalid');
                    //     input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                    // });
                    Object.keys(errors).forEach(field => {
                        const input = $(`#edit_${field}`);

                        if (!input.length) return;

                        input.addClass('is-invalid');

                        // Remove old error (safety)
                        input.next('.invalid-feedback').remove();

                        input.after(`
                            <div class="invalid-feedback">
                                ${errors[field][0]}
                            </div>
                        `);
                    });
                },

                deleteResident(id) {
                    Swal.fire({
                        title: 'Delete Resident?',
                        text: 'This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'btn btn-danger me-2',
                            cancelButton: 'btn btn-secondary'
                        },
                        buttonsStyling: false
                    }).then(result => {
                        if (!result.isConfirmed) return;

                        this.apiRequest(`${this.config.apiUrl}/${id}`, 'DELETE')
                            .done(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // this.fetchResidents();
                                this.reloadTable(false);


                            })
                            .fail(err => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: err.responseJSON?.message ??
                                        'Unable to delete resident'
                                });
                            });
                    });
                },


                statusClass(status) {
                    return {
                        active: 'bg-success',
                        pending: 'bg-warning text-dark',
                        rejected: 'bg-danger',
                        verified: 'bg-primary'
                    } [status] || 'bg-secondary';
                },

                initDataTable() {
                    if (this.dataTable) return;

                    const $table = $(this.config.table);
                    if (!$table.length) return;

                    this.dataTable = $table.DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: {
                            details: {
                                type: 'column',
                                target: 0
                            }
                        },

                        ajax: {
                            url: this.config.apiUrl,
                            type: 'GET',
                            headers: {
                                Authorization: `Bearer ${this.config.token}`,
                                Accept: 'application/json'
                            },
                            dataSrc: function(json) {

                                // Json Summary format
                                if (json.summary) {
                                    $('#totalResidents').text(json.summary.total_residents);
                                    $('#activeResidents').text(json.summary.active_residents);
                                    $('#inactiveResidents').text(json.summary.inactive_residents);
                                    $('#maleCount').text(json.summary.male_count);
                                    $('#femaleCount').text(json.summary.female_count);
                                    $('#checkedInToday').text(json.summary.checked_in_today);
                                }

                                return json.data; // Laravel format
                            }
                        },

                        columns: [{
                                data: null,
                                orderable: false,
                                className: 'dtr-control noExport',
                                defaultContent: ''
                            },
                            {
                                data: null,
                                render: (d, t, r, meta) =>
                                    meta.row + meta.settings._iDisplayStart + 1
                            },
                            // {
                            //     data: null,
                            //     className: 'noExport',
                            //     render: (d, t, r, meta) => meta.row + 1
                            // },
                            // {
                            //     title: '#',
                            //     render: function(data, type, row, meta) {
                            //         return meta.row + 1;
                            //     }
                            // },

                            {
                                data: 'scholar_no',
                                render: (d, t, r) =>
                                    `<a href="javascript:void(0)"
                                    class="text-info fw-semibold view-profile"
                                    data-id="${r.id}">
                                    ${d ?? 'N/A'}
                                </a>`
                            },

                            {
                                data: 'name',
                                orderable: true // ✅ DB-backed
                            },
                            {
                                data: 'email',
                                orderable: true // ✅ DB-backed
                            },
                            {
                                data: 'mobile'
                            },

                            {
                                data: 'check_in_date',
                                name: 'check_in_date',
                                render: (d, t, r) => `
                                <div class="d-flex align-items-center gap-2">
                                    <span>${ResidentApp.formatDateTime(d)}</span>
                                    <button class="btn btn-link p-0 update-checkin"
                                            data-id="${r.id}" data-date="${d ?? ''}">
                                        <i class="bi bi-calendar-event"></i>
                                    </button>
                                </div>`
                            },

                            {
                                data: 'gender',
                                defaultContent: 'N/A',
                                render: function(data) {
                                    if (!data) return '<span class="text-muted">N/A</span>';

                                    return data.charAt(0).toUpperCase() + data.slice(1);
                                }
                            },
                            {
                                data: 'hostel.building',
                                name: 'building', // 👈 THIS is the key
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'hostel.room',
                                name: 'room',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'hostel.bed',
                                name: 'bed',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'fathers_name',
                                name: 'fathers_name',
                            },
                            {
                                data: 'mothers_name',
                                name: 'mothers_name',
                            },
                            {
                                data: 'parent_contact',
                            },
                            {
                                data: 'guardians_name'
                            },
                            {
                                data: 'guardians_contact'
                            },
                            {
                                data: 'emergency_contact'
                            },
                            {
                                data: 'academic.faculty'
                            },
                            {
                                data: 'academic.department'
                            },
                            {
                                data: 'academic.course'
                            },

                            {
                                data: 'status',
                                render: d => `
                                <span class="badge ${ResidentApp.statusClass(d)}">
                                    ${d ?? 'N/A'}
                                </span>`
                            },

                            {
                                data: 'created_at'
                            },

                            {
                                data: null,
                                orderable: false,
                                className: 'noExport',
                                render: r =>
                                    `
                                    <button class="btn btn-sm btn-outline-info view-profile" data-id="${r.id}">View</button>
                                    <button class="btn btn-sm btn-outline-dark editResidentBtn" data-id="${r.id}">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger deleteResidentBtn" data-id="${r.id}">Delete</button>`
                            }
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
                            [6, 'asc'] // Check-in date desc
                        ],

                        dom: "<'row mb-2'<'col-md-3'l><'col-md-5 text-center'B><'col-md-4'f>>" +
                            "<'row'<'col-12'tr>>" +
                            "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

                        // buttons: [{
                        //         extend: 'copy',
                        //         className: 'btn btn-sm btn-outline-primary',
                        //         exportOptions: {
                        //             // columns: ':visible:not(.noExport)' // Excludes responsive control and action
                        //             columns: ':not(.noExport)'
                        //             // columns: function(idx, data, node) {
                        //             //     return !$(node).hasClass('noExport') && table.column(idx)
                        //             //         .visible();
                        //             // }
                        //         }
                        //     },
                        //     {
                        //         extend: 'csv',
                        //         className: 'btn btn-sm btn-outline-success',
                        //         exportOptions: {
                        //             // columns: ':visible:not(.noExport)'
                        //             columns: ':not(.noExport)'
                        //         }
                        //     },
                        //     {
                        //         extend: 'excel',
                        //         className: 'btn btn-sm btn-outline-info',
                        //         exportOptions: {
                        //             // columns: ':visible:not(.noExport)'
                        //             columns: ':not(.noExport)'
                        //         }
                        //     },
                        //     {
                        //         extend: 'pdf',
                        //         className: 'btn btn-sm btn-outline-danger',
                        //         exportOptions: {
                        //             // columns: ':visible:not(.noExport)'
                        //             columns: ':not(.noExport)'
                        //         }
                        //     },
                        //     {
                        //         extend: 'print',
                        //         className: 'btn btn-sm btn-outline-secondary',
                        //         exportOptions: {
                        //             // columns: ':visible:not(.noExport)'
                        //             columns: ':not(.noExport)'
                        //         }
                        //     },
                        //     {
                        //         extend: 'colvis',
                        //         className: 'btn btn-sm btn-outline-dark',
                        //         text: 'Columns',
                        //         columns: ':not(.noVis)', // control what appears in ColVis
                        //         postfixButtons: ['colvisRestore']
                        //     },
                        //     // {
                        //     //     extend: 'collection',
                        //     //     text: 'Export',
                        //     //     className: 'btn btn-sm btn-outline-secondary',
                        //     //     buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                        //     // }

                        // ]
                        buttons: [{
                                extend: 'colvis',
                                className: 'btn btn-sm btn-outline-dark',
                                text: 'Columns',
                                columns: ':not(.noVis)',
                                postfixButtons: ['colvisRestore']
                            },

                            {
                                extend: 'collection',
                                text: 'Export',
                                className: 'btn btn-sm btn-outline-secondary',
                                buttons: [{
                                        extend: 'copy',
                                        title: exportTitle,
                                        filename: exportFileName,
                                        exportOptions: {
                                            columns: ':not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        title: exportTitle,
                                        filename: exportFileName,
                                        exportOptions: {
                                            columns: ':not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        title: exportTitle,
                                        filename: exportFileName,
                                        exportOptions: {
                                            columns: ':not(.noExport)'
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        title: exportTitle,
                                        filename: exportFileName,
                                        orientation: 'landscape',
                                        pageSize: 'A4',
                                        exportOptions: {
                                            columns: ':not(.noExport)'
                                        },
                                        customize: function(doc) {
                                            doc.styles.title = {
                                                fontSize: 14,
                                                alignment: 'center'
                                            };
                                        }
                                    },
                                    {
                                        extend: 'print',
                                        title: exportTitle,
                                        // exportOptions: {
                                        //     columns: ':not(.noExport)'
                                        // }
                                        exportOptions: {
                                            columns: ':not(.noExport)',
                                            format: {
                                                header: function(data) {
                                                    return data
                                                        .replace(/_/g, ' ')
                                                        .replace(/\b\w/g, l => l.toUpperCase());
                                                }
                                            }
                                            // format: {
                                            //     body: function(data, row, column) {
                                            //         if (column === 5) {
                                            //             return data === 'male' ? 'Male' :
                                            //                 data === 'female' ? 'Female' :
                                            //                 'Other';
                                            //         }
                                            //         return data;
                                            //     }
                                            // }

                                        }

                                    }
                                ]
                            }
                        ],

                        select: {
                            style: 'multi', // single | multi
                            selector: 'td:not(.noSelect)'
                        },

                    });
                },


                showTableLoader() {
                    $(this.config.tbody).html(
                        `<tr><td colspan="16" class="text-center text-muted">Loading...</td></tr>`);
                },

                handleError(xhr, message) {
                    console.error(message, xhr);
                    alert(message);
                },

                bindEvents() {
                    $(document).on('click', '#addResidentBtn', () =>
                        this.loadResident(null, 'add')
                    );

                    $(document).on('click', '.view-profile', e => this.loadResident($(e.currentTarget).data('id'),
                        'view'));
                    $(document).on('click', '.editResidentBtn', e => this.loadResident($(e.currentTarget).data(
                        'id'), 'edit'));
                    $(document).on('click', '.deleteResidentBtn', e => this.deleteResident($(e.currentTarget).data(
                        'id'), 'delete'));

                    $(document).on('click', '.update-checkin', function() {

                        const residentId = $(this).data('id');
                        let currentDate = $(this).data('date');

                        // Convert ISO to datetime-local format
                        if (currentDate) {
                            currentDate = currentDate.replace('T', ' ').slice(0, 16);
                        }

                        Swal.fire({
                            title: 'Update Check-in Date',
                            html: `
                                <input type="datetime-local"
                                    id="checkinDate"
                                    class="form-control datetime-picker"
                                    value="${currentDate ?? ''}">
                            `,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            preConfirm: () => {
                                const value = document.getElementById('checkinDate').value;
                                if (!value) {
                                    Swal.showValidationMessage('Please select a date');
                                }
                                return value;
                            }
                        }).then(result => {
                            if (!result.isConfirmed) return;

                            ResidentApp.updateCheckInDate(residentId, result.value);
                        });

                    });


                    this.submitResidentForm();
                },

                openAddModal() {
                    $('#residentModalTitle').text('Add Resident');
                    $('#residentSubmitBtn').text('Create Resident').show();

                    $('#editResidentForm')[0].reset();
                    $('#edit_resident_id').val('');

                    this.toggleForm(true);

                    $('#editResidentModal').modal('show');
                },

                updateCheckInDate(id, checkInDate) {
                    console.log(id, checkInDate);
                    this.apiRequest(`${this.config.apiUrl}/${id}/check-in`, 'PUT', {
                            check_in_date: checkInDate
                        })
                        .done(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            // 🔁 Refresh table safely
                            // this.fetchResidents();
                            this.reloadTable(false);

                        })
                        .fail(xhr => {
                            const msg = xhr.responseJSON?.message ?? 'Failed to update date';
                            Swal.fire('Error', msg, 'error');
                        });
                },

                formatDateTime(value) {
                    if (!value) return '<span class="text-muted">Not set</span>';

                    const d = new Date(value);
                    if (isNaN(d)) return '<span class="text-muted">Invalid</span>';

                    return d.toLocaleString('en-IN', {
                        day: '2-digit',
                        // month: '2-digit',
                        month: 'short', // ✅ Dec instead of 12
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                },

            };

            $(document).ready(() => ResidentApp.init());

        })(jQuery);

        const exportTitle = 'Resident Master Report';

        const exportFileName = () => {
            const d = new Date();
            return `Residents_${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`;
        };
    </script>
@endpush
