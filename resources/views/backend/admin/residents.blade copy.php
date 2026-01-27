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
                            <h3>53,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Checked-In Residents</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Approvals</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Vacant Beds</p>
                            <h3>1,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div>
                    </div>
                </div>

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
                        <i class="fa fa-plus"></i> Add Resident
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
                                    <th></th> <!-- 🔥 Responsive control column -->
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
                                    <th>Faculty</th>
                                    <th>Department</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th> <!-- ✅ REQUIRED -->
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

                            <span class="input-set">
                                <label>Check-In Date</label>
                                {{-- <input type="date" id="edit_check_in_date"> --}}
                                {{-- <input type="datetime-local" id="edit_check_in_date">
                                <small id="check_in_display"></small> --}}
                                <input type="text" class="datetime-picker" id="edit_check_in_date">

                            </span>

                            <span class="input-set">
                                <label>Gender</label>
                                <select id="edit_gender">
                                    <option value="">Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Father's Name</label>
                                <input type="text" id="edit_fatherName">
                            </span>

                            <span class="input-set">
                                <label>Mother's Name</label>
                                <input type="text" id="edit_motherName">
                            </span>

                            <span class="input-set">
                                <label>Guardian's Name</label>
                                <input type="text" id="edit_guardianName">
                            </span>

                            <span class="input-set">
                                <label>Parent's Contact</label>
                                <input type="text" id="edit_parenContact">
                            </span>
                            <span class="input-set">
                                <label>Guardian's Contact</label>
                                <input type="text" id="edit_guardianContact">
                            </span>

                            <span class="input-set">
                                <label class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="status_error"></div>
                            </span>

                        </div>


                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="submit" class="blue"> Update Resident</button>
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
        $(document).ready(function() {

            // Fetch residents when the document is ready
            fetchResidents();

            // Function to show a custom message box
            function showCustomMessageBox(message, type = 'info') {
                const messageContainer = $('#responseMessage');
                messageContainer.html(`<div class="alert alert-${type}">${message}</div>`);
                setTimeout(() => messageContainer.empty(), 3000); // Clear after 3 seconds
            }
        });

        // Function to fetch residents
        function fetchResidents() {
            const token = localStorage.getItem("token");

            $.ajax({
                url: "{{ url('/api/admin/residents') }}",
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token'),
                    Accept: 'application/json'
                },

                success: function(response) {
                    const residents = response.data;
                    const residentList = $("#residentList");
                    residentList.empty();

                    if (!Array.isArray(residents) || residents.length === 0) {
                        residentList.append(`<tr>
                                                                            <td colspan="14" class="text-center">No residents found.</td>
                                                                            </tr>`);
                        return;
                    }

                    residents.forEach((resident, index) => {
                        const guest = resident.guest || {};
                        const bed = resident.bed || {};
                        const hostel = resident.hostel || {};
                        const academic = resident.academic || {};

                        residentList.append(`
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${resident.scholar_no ?? 'N/A'}</td>
                                            <td>${resident.name ?? 'N/A'}</td>
                                            <td>${resident.email ?? 'N/A'}</td>
                                            <td>${resident.gender ?? 'N/A'}</td>
                                            <td>${resident.mobile ?? 'N/A'}</td>
                                            <td>${resident.check_in_date
                                                ? new Date(resident.check_in_date).toLocaleDateString()
                                                : 'N/A'}</td>
                                            <td>${hostel?.bed ?? 'N/A'}</td>
                                            <td>${hostel?.room ?? 'N/A'}</td>
                                            <td>${hostel?.building ?? 'N/A'}</td>
                                            <td>${academic.faculty ?? 'N/A'}</td>
                                            <td>${academic.department ?? 'N/A'}</td>
                                            <td>${academic.course ?? 'N/A'}</td>
                                            <td>${resident.status ?? 'N/A'}</td>
                                            <td>${new Date(resident.created_at).toLocaleString()}</td>
                                            <td>
                                            <a href="{{ route('admin.create_hod') }}" class="btn btn-sm btn-outline-dark">View Profile</a>
                                                <button 
                                                    class="btn btn-sm btn-outline-dark editResidentBtn"
                                                    data-id="${resident.id}"
                                                    data-scholar="${resident.scholar_no ?? ''}"
                                                    data-name="${resident.name ?? ''}"
                                                    data-email="${resident.email ?? ''}"
                                                    data-gender="${resident.gender ?? ''}"
                                                    data-mobile="${resident.mobile ?? ''}"
                                                    data-doj="${resident.date_of_joining ?? ''}">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                            `);
                    });

                    // ✅ Destroy existing DataTable instance before reinitializing
                    if ($.fn.DataTable.isDataTable('#residentTable')) {
                        $('#residentTable').DataTable().destroy();
                    }

                    // Datatable
                    InitializeDatatable();
                },

                error: function(xhr) {
                    console.error("Error fetching residents:", xhr);
                    $("#residentList").html(`<tr>
                                                                        <td colspan="10" class="text-danger text-center">Error loading residents.</td>
                                                                    </tr>`);
                    showCustomMessageBox("Failed to load residents.", 'danger'); // Display error message
                }
            });


            /* ================= OPEN EDIT MODAL ================= */
            $(document).on('click', '.editResidentBtn', function() {
                $('#edit_resident_id').val($(this).data('id'));
                $('#edit_scholar_no').val($(this).data('scholar'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_email').val($(this).data('email'));
                $('#edit_gender').val($(this).data('gender'));
                $('#edit_mobile').val($(this).data('mobile'));
                $('#edit_date_of_joining').val($(this).data('doj'));

                $('#editResidentModal').modal('show');
            });


            /* ================= UPDATE RESIDENT ================= */
            $('#editResidentForm').submit(function(e) {
                e.preventDefault();

                let id = $('#edit_resident_id').val();
                const token = localStorage.getItem("token");
                $.ajax({
                    url: `/api/admin/residents/${id}`,
                    type: 'PUT',
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    data: {
                        scholar_no: $('#edit_scholar_no').val(),
                        name: $('#edit_name').val(),
                        email: $('#edit_email').val(),
                        gender: $('#edit_gender').val(),
                        mobile: $('#edit_mobile').val(),
                        date_of_joining: $('#edit_date_of_joining').val(),
                    },
                    success: function() {
                        $('#editResponse').html(
                            `<div class="alert alert-success">Updated Successfully</div>`);

                        setTimeout(() => {
                            $('#editResidentModal').modal('hide');
                            fetchResidents();
                        }, 1000);
                    },
                    error: function() {
                        $('#editResponse').html(`<div class="alert alert-danger">Update Failed</div>`);
                    }
                });
            });

            function getStatusClass(status) {
                switch (status) {
                    case 'active':
                        return 'bg-success text-white';
                    case 'pending':
                        return 'bg-warning text-dark';
                    case 'approved':
                        return 'bg-success';
                    case 'verified':
                        return 'bg-primary';
                    case 'rejected':
                        return 'bg-danger';
                    default:
                        return 'bg-secondary';
                }
            }

        }
    </script> --}}

    {{-- <script>
        (function($) {
            "use strict";

            /* ===============================
             * Resident Module
             * =============================== */
            const ResidentApp = {

                config: {
                    apiUrl: "{{ url('/api/admin/residents') }}",
                    token: localStorage.getItem('token'),
                    table: '#residentTable',
                    tbody: '#residentList',
                },

                init() {
                    this.bindEvents();
                    this.fetchResidents();
                },

                /* ===============================
                 * API Layer
                 * =============================== */
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

                /* ===============================
                 * Fetch Residents
                 * =============================== */
                fetchResidents() {
                    this.showTableLoader();

                    this.apiRequest(this.config.apiUrl)
                        .done(res => this.renderTable(res.data))
                        .fail(err => this.handleError(err, 'Failed to load residents'));
                },

                /* ===============================
                 * Render Table
                 * =============================== */
                renderTable(residents) {
                    const tbody = $(this.config.tbody).empty();

                    if (!Array.isArray(residents) || !residents.length) {
                        tbody.append(
                            `<tr><td colspan="16" class="text-center text-muted">No records found</td></tr>`);
                        return;
                    }

                    residents.forEach((r, i) => {
                        tbody.append(this.rowTemplate(r, i));
                    });

                    this.initDataTable();
                },

                rowTemplate(r, index) {
                    return `
                        <tr>
                              <td class="dtr-control"></td> <!-- 🔥 REQUIRED -->
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
                             <td>${r.check_in_date ?? 'N/A'}</td>
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
                            <td>
                                <button class="btn btn-sm btn-outline-primary view-profile"
                                        data-id="${r.id}">
                                    View
                                </button>
                                <button class="btn btn-sm btn-outline-dark editResidentBtn"
                                        data-id="${r.id}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    `;
                },


                /* ===============================
                 * Profile Modal
                 * =============================== */
                loadProfile(id) {
                    $('#profileModal').modal('show');
                    $('#profileModalBody').html(`<div class="text-center py-5">Loading...</div>`);

                    this.apiRequest(`${this.config.apiUrl}/${id}`)
                        .done(res => this.renderProfile(res.data))
                        .fail(err => this.handleError(err, 'Failed to load profile'));
                },

                renderProfile(data) {
                    $('#profileModalBody').html(`
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <h6>Personal Info</h6>
                                    <p><strong>Name:</strong> ${data.name}</p>
                                    <p><strong>Email:</strong> ${data.email}</p>
                                    <p><strong>Mobile:</strong> ${data.mobile}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Academic Info</h6>
                                    <p><strong>University:</strong> ${data.academic?.university}</p>
                                    <p><strong>Faculty:</strong> ${data.academic?.faculty}</p>
                                    <p><strong>Department:</strong> ${data.academic?.department}</p>
                                    <p><strong>Course:</strong> ${data.academic?.course}</p>
                                </div>
                                <div class="col-md-12">
                                    <h6>Hostel Info</h6>
                                    <p>${data.hostel?.building} → ${data.hostel?.room} → Bed ${data.hostel?.bed}</p>
                                </div>
                            </div>
                        `);
                },

                /* ===============================
                 * Utilities
                 * =============================== */
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
                //     //  setTimeout(() => {
                //     $(this.config.table).DataTable({
                //         responsive: true
                //     });
                //         // }, 50); // ⬅ tiny delay, huge difference
                // },


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
                //                 }, // Action column
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
                initDataTable() {
                    if ($.fn.DataTable.isDataTable(this.config.table)) {
                        $(this.config.table).DataTable().destroy();
                    }

                    setTimeout(() => {
                        $(this.config.table).DataTable({
                            responsive: {
                                details: {
                                    type: 'column', // 🔥 enables dtr-control
                                    target: 0 // 🔥 first column
                                }
                            },
                            autoWidth: false,
                            columnDefs: [{
                                    className: 'dtr-control',
                                    orderable: false,
                                    targets: 0 // 🔥 control column
                                },
                                {
                                    responsivePriority: 1,
                                    targets: -1 // Action
                                },
                                {
                                    responsivePriority: 2,
                                    targets: 2 // Scholar No
                                },
                                {
                                    responsivePriority: 3,
                                    targets: 3 // Name
                                }
                            ],
                            order: [
                                [1, 'asc']
                            ]
                        });
                    }, 50);
                },


                showTableLoader() {
                    $(this.config.tbody).html(
                        `<tr><td colspan="16" class="text-center text-muted">Loading...</td></tr>`
                    );
                },

                handleError(xhr, message) {
                    console.error(message, xhr);
                    alert(message);
                },

                bindEvents() {
                    $(document).on('click', '.view-profile', e => {
                        this.loadProfile($(e.currentTarget).data('id'));
                    });

                    $(document).on('click', '.editResidentBtn', e => {
                        this.openEditModal($(e.currentTarget).data('id'));
                    });

                    $('#editResidentForm').on('submit', e => {
                        e.preventDefault();
                        this.submitEdit();
                    });
                },

                //
                openEditModal(id) {
                    this.resetEditForm();
                    $('#editResidentModal').modal('show');

                    this.apiRequest(`${this.config.apiUrl}/${id}`)
                        .done(res => {
                            const r = res.data;
                            console.log('res', r);
                            $('#edit_id').val(r.id);
                            $('#edit_scholar_no').val(r.scholar_no);
                            $('#edit_name').val(r.name);
                            $('#edit_email').val(r.email);
                            if (r.mobile) {
                                $('#edit_mobile').val(r.mobile).show();
                            } else {
                                $('#edit_mobile').val(r.number).show();
                            }

                            $('#edit_gender').val(r.gender);
                        })
                        .fail(err => this.handleFormError(err, 'Failed to load resident'));
                },

                validateEditForm() {
                    let valid = true;

                    const rules = {
                        edit_name: 'Name is required',
                        edit_email: 'Valid email is required',
                        edit_mobile: 'Mobile must be 10 digits'
                    };

                    Object.entries(rules).forEach(([id, msg]) => {
                        const el = $('#' + id);
                        el.removeClass('is-invalid');

                        if (!el.val() || (id === 'edit_mobile' && !/^\d{10}$/.test(el.val()))) {
                            el.addClass('is-invalid');
                            el.next('.invalid-feedback').text(msg);
                            valid = false;
                        }
                    });

                    return valid;
                },

                submitEdit() {
                    if (!this.validateEditForm()) return;

                    const btn = $('#editResidentForm button[type=submit]');
                    btn.prop('disabled', true);
                    btn.find('.spinner-border').removeClass('d-none');

                    const id = $('#edit_id').val();

                    this.apiRequest(`${this.config.apiUrl}/${id}`, 'PUT', {
                            scholar_no: $('#edit_scholar_no').val(),
                            name: $('#edit_name').val(),
                            email: $('#edit_email').val(),
                            mobile: $('#edit_mobile').val(),
                            gender: $('#edit_gender').val(),
                        })
                        .done(() => {
                            $('#editFormAlert').html(
                                `<div class="alert alert-success">Updated successfully</div>`
                            );
                            setTimeout(() => {
                                $('#editResidentModal').modal('hide');
                                this.fetchResidents();
                            }, 800);
                        })
                        .fail(err => this.handleFormError(err))
                        .always(() => {
                            btn.prop('disabled', false);
                            btn.find('.spinner-border').addClass('d-none');
                        });
                },

                handleFormError(xhr, fallback = 'Update failed') {
                    $('#editFormAlert').html('');

                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            const input = $('#edit_' + key);
                            input.addClass('is-invalid');
                            input.next('.invalid-feedback').text(errors[key][0]);
                        });
                        return;
                    }

                    const msg = xhr.responseJSON?.message || fallback;
                    $('#editFormAlert').html(`<div class="alert alert-danger">${msg}</div>`);
                },

                resetEditForm() {
                    $('#editResidentForm')[0].reset();
                    $('#editFormAlert').html('');
                    $('#editResidentForm .is-invalid').removeClass('is-invalid');
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
                              <td class="dtr-control"></td> <!-- 🔥 REQUIRED -->
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
                             <td>${r.check_in_date ?? 'N/A'}</td>
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
                            <td>
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

                     $('#edit_fatherName').val(data.fathers_name ?? '');
                      $('#edit_motherName').val(data.mothers_name ?? '');
                       $('#edit_guardianName').val(data.guardians_name ?? '');
                        $('#edit_name').val(data.name ?? '');
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
                            gender: $('#edit_gender').val(),
                            mobile: $('#edit_mobile').val(),
                            parent_no: $('#edit_parent_no').val(),
                            guardian_no: $('#edit_guardian_no').val(),
                            fathers_name: $('#edit_fathers_name').val(),
                            mothers_name: $('#edit_mothers_name').val(),
                            check_in_date: $('#edit_check_in_date').val(),

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
            //             <'row mb-2'
            //                 <'col-12 col-md-3 d-flex align-items-center justify-content-center justify-content-md-start'l>
            //                 <'col-12 col-md-5 d-flex align-items-center justify-content-center'B>
            //                 <'col-12 col-md-4 d-flex align-items-center justify-content-center justify-content-md-end'f>
            //             >
            //             <'row'<'col-12'tr>>
            //             <'row mt-2'
            //                 <'col-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>
            //                 <'col-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>
            //             >
            //         `,
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
                                        className: 'btn btn-sm btn-outline-primary me-1'
                                    },
                                    {
                                        extend: 'csv',
                                        className: 'btn btn-sm btn-outline-success me-1'
                                    },
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-sm btn-outline-info me-1'
                                    },
                                    {
                                        extend: 'pdfHtml5',
                                        className: 'btn btn-sm btn-outline-danger me-1'
                                    },
                                    {
                                        extend: 'print',
                                        className: 'btn btn-sm btn-outline-secondary'
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

            };

            $(document).ready(() => ResidentApp.init());

        })(jQuery);
    </script>
@endpush
