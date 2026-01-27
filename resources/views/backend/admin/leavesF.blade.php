@extends('admin.layout')


@section('content')
    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Leaves Management</a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a href="">Leaves Overview</a></div>

                <div class="card-ds-bottom">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Departments</p>
                            <h3 id="stat-faculties">500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Departments</p>
                            <h3 id="stat-departments">400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Courses</p>
                            <h3 id="stat-courses">50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png') }}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Leaves List</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Faculty">+ Add
                                                                                                                                                                                                                                                                                                                                                        Faculty</button> -->
                    <button class="btn btn-primary btn-sm" onclick="RecordsModal.openCreate()">
                        <i class="fa fa-plus"></i> Add Course
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
                                <th>Attachment </th>
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

    {{-- @include('faculties.modal') --}}
    <div class="modal fade" id="recordsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title mpop-title" id="recordsModalTitle">Add Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <form id="recordsForm">
                        @csrf
                        <input type="hidden" id="record_id">
                        {{-- <div class="modal-header top">
                        <h5 class="pop-title" id="recordsModalTitle">Add Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div> --}}
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Faculty</label>
                            <select id="faculty_id" name="faculty_id" class="form-select"></select>
                            <div class="invalid-feedback" id="faculty_id_error"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select id="department_id" name="department_id" class="form-select"></select>
                            <div class="invalid-feedback" id="department_id_error"></div>
                        </div>



                        {{-- <div class="mb-3">
                            <label class="form-label">Faculty University</label>
                            <input type="text" class="form-control" id="university">
                            <div class="invalid-feedback" id="university_error"></div>
                        </div> --}}
                        <!-- Hidden input to store the faculty_id -->
                        {{-- <input type="hidden" id="faculty_id"> --}}

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status_error"></div>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close">
                                Cancel</button>
                            <button type="submit" class="blue" id="recordsSubmitBtn"> Submit</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    @endsection


    @push('scripts')
        <script>
            const API_TOKEN = localStorage.getItem('token');
            let CURRENT_USER_ROLE = [];

            // $.ajaxSetup({
            //     headers: {
            //         'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : '',
            //         'Accept': 'application/json'
            //     }
            // });

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
            let FACULTY_CACHE = [];

            let DEPARTMENT_CACHE = [];

            $(document).ready(function() {
                RecordsTable.init();
                RecordsForm.init();
            });

            /* ======================================================
             * DATATABLE
             * ====================================================== */
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
                            url: "{{ route('manage.leaves.index') }}",
                            dataSrc: function(res) {
                                CURRENT_USER_ROLE = Array.isArray(res.meta.roles) ?
                                    res.meta.roles : [res.meta.roles];
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
                            // {
                            //     data: 'resident.name',
                            //     title: 'Name',
                            //     defaultContent: '<span class="text-muted">N/A</span>'
                            // },
                            // {
                            //     data: 'resident.scholar_no',
                            //     title: 'Enrollment',
                            //     defaultContent: '<span class="text-muted">N/A</span>'
                            // },
                            // {
                            //     data: 'resident.room_number',
                            //     // title: 'room_number'
                            //     orderable: false,
                            //     searchable: false,
                            // },

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
                            // {
                            //     data: 'type',
                            //     title: 'Leave type',
                            //     defaultContent: ''
                            // },
                            // {
                            //     data: 'reason',
                            //     // title: 'reason',
                            //     // defaultContent: ''
                            // },
                            {
                                // data: null,
                                data: 'type', // Add this line
                                title: 'Leave Details',
                                render: function(data, type, row) {
                                    let leaveType = row.type || '<span class="text-muted">N/A</span>';
                                    let reason = row.reason || '<span class="text-muted">N/A</span>';

                                    return `
                                        <div>
                                            Type: ${leaveType} <br>
                                            Reason: ${reason}
                                        </div>
                                    `;
                                },
                                defaultContent: '<span class="text-muted">N/A</span>'
                            },
                            // {
                            //     data: 'start_date',
                            //     // title: 'reason',
                            //     // defaultContent: ''
                            // },
                            // {
                            //     data: 'end_date',
                            //     // title: 'reason',
                            //     // defaultContent: ''
                            // },
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
                                data: 'attachment',
                                title: 'Attachment',
                                render: file => file ?
                                    `<a href="${file}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>` :
                                    '<span class="text-muted">No File</span>'
                            },

                            // {
                            //     data: 'hod_status',
                            //     title: 'HOD Status',
                            //     render: s => {
                            //         switch (s) {
                            //             case 'approved':
                            //                 return '<span class="badge bg-success">Approved</span>';
                            //             case 'rejected':
                            //                 return '<span class="badge bg-danger">Rejected</span>';
                            //             default:
                            //                 return '<span class="badge bg-warning text-dark">Pending</span>';
                            //         }
                            //     }
                            // },
                            // {
                            //     data: 'hod_status',
                            //     title: 'HOD Status',
                            //     render: s => badge(s)
                            // },

                            // {
                            //     data: 'hod_remarks',
                            //     title: 'HOD Remarks'
                            // },
                            // {
                            //     data: 'hod_approved_at',
                            //     title: 'HOD Approved At'
                            // },
                            // {
                            //     data: null,
                            //     title: 'HOD Action',
                            //     // orderable: false,
                            //     render: row => {

                            //         const status = row.hod_status ?? 'pending';
                            //         const remarks = row.hod_remarks ?? '';
                            //         const approvedAt = row.hod_action_at ?? '';

                            //         return `
                    //             <div class="d-flex flex-column gap-1">
                    //                 <div>${badge(status)}</div>

                    //                 ${approvedAt ? `
                            //                                                                                                             <small class="text-muted">
                            //                                                                                                                 <i class="bi bi-clock"></i> ${approvedAt}
                            //                                                                                                             </small>
                            //                                                                                                         ` : ''}

                    //                 ${remarks ? `
                            //                                                                                                             <small class="text-truncate text-secondary"
                            //                                                                                                                 style="max-width: 220px"
                            //                                                                                                                 title="${remarks}">
                            //                                                                                                                 <i class="bi bi-chat-left-text"></i> ${remarks}
                            //                                                                                                             </small>
                            //                                                                                                         ` : ''}
                    //             </div>
                    //         `;
                            //     }
                            // },
                            {
                                data: 'hod_status', // Add this line - this tells DataTables what field to sort by
                                title: 'HOD Action',
                                orderable: true,
                                render: function(data, type, row) {
                                    // For display purposes only
                                    if (type === 'display') {
                                        const status = row.hod_status ?? 'pending';
                                        const remarks = row.hod_remarks ?? '';
                                        const approvedAt = row.hod_action_at ?? '';

                                        return `
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
                </div>
            `;
                                    }

                                    // For sorting and filtering, return the raw data
                                    return row.hod_status || 'pending';
                                }
                            },
                            // {
                            //     data: null,
                            //     title: 'ADMIN Action',
                            //     // orderable: false,
                            //     render: row => {

                            //         const status = row.admin_status ?? 'pending';
                            //         const remarks = row.admin_remarks ?? '';
                            //         const approvedAt = row.admin_action_at ?? '';

                            //         return `
                    //             <div class="d-flex flex-column gap-1">
                    //                 <div>${badge(status)}</div>

                    //                 ${approvedAt ? `
                            //                                                                                                             <small class="text-muted">
                            //                                                                                                                 <i class="bi bi-clock"></i> ${approvedAt}
                            //                                                                                                             </small>
                            //                                                                                                         ` : ''}

                    //                 ${remarks ? `
                            //                                                                                                             <small class="text-truncate text-secondary"
                            //                                                                                                                 style="max-width: 220px"
                            //                                                                                                                 title="${remarks}">
                            //                                                                                                                 <i class="bi bi-chat-left-text"></i> ${remarks}
                            //                                                                                                             </small>
                            //                                                                                                         ` : ''}
                    //             </div>
                    //         `;
                            //     }
                            // },
                            {
                                data: 'admin_status', // Add this line - this tells DataTables what field to sort by
                                title: 'ADMIN Action',
                                orderable: true,
                                render: function(data, type, row) {
                                    // For display purposes only
                                    if (type === 'display') {
                                        const status = row.admin_status ?? 'pending';
                                        const remarks = row.admin_remarks ?? '';
                                        const approvedAt = row.admin_action_at ?? '';

                                        return `
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
                </div>
            `;
                                    }

                                    // For sorting and filtering, return the raw data
                                    return row.admin_status || 'pending';
                                }
                            },
                            // {
                            //     data: 'admin_attachment',
                            //     title: 'Admin Attachment',
                            //     render: file => file ?
                            //         `<a href="${file}" target="_blank" class="btn btn-sm btn-outline-primary">View</a>` :
                            //         '<span class="text-muted">No File</span>'
                            // },
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
                                render: function(data) {

                                    const roles = CURRENT_USER_ROLE || [];

                                    let buttons = '';

                                    // HOD actions
                                    if (roles.includes('hod') && data.hod_status === 'pending') {
                                        buttons += `
                <button class="btn btn-sm btn-success me-1"
                    onclick="LeaveActions.submit(${data.id}, 'hod_approve')">
                    Approve
                </button>
                <button class="btn btn-sm btn-danger"
                    onclick="LeaveActions.submit(${data.id}, 'hod_reject')">
                    Reject
                </button>
            `;
                                    }

                                    // Admin actions (only after HOD approval)
                                    if (
                                        roles.includes('admin') &&
                                        data.hod_status === 'approved' &&
                                        data.admin_status === 'pending'
                                    ) {
                                        buttons += `
                <button class="btn btn-sm btn-success me-1"
                    onclick="LeaveActions.submit(${data.id}, 'admin_approve')">
                    Approve
                </button>
                <button class="btn btn-sm btn-danger"
                    onclick="LeaveActions.submit(${data.id}, 'admin_reject')">
                    Reject
                </button>
            `;
                                    }

                                    return buttons || '<span class="text-muted">No Action</span>';
                                }
                            },

                            // {
                            //     data: null,
                            //     title: 'Action',
                            //     orderable: false,
                            //     searchable: false,
                            //     className: 'text-nowrap',
                            //     render: function(data) {

                            //         const roles = CURRENT_USER_ROLE || [];

                            //         // HOD actions
                            //         if (roles.includes('hod')) {
                            //             if (data.hod_status === 'pending') {
                            //                 return `
                    //                     <button class="btn btn-success btn-sm act-approve" data-id="${data.id}">Approve</button>
                    //                     <button class="btn btn-danger btn-sm act-reject" data-id="${data.id}">Reject</button>
                    //                 `;
                            //             }
                            //             return `<span class="text-muted">Already ${data.hod_status}</span>`;
                            //         }

                            //         // Admin actions
                            //         if (roles.includes('admin')) {
                            //             if (data.hod_status !== 'approved') {
                            //                 return `<span class="text-muted">Waiting for HOD</span>`;
                            //             }

                            //             if (data.admin_status === 'pending') {
                            //                 return `
                    //                         <button class="btn btn-success btn-sm act-approve" data-id="${data.id}">Approve</button>
                    //                         <button class="btn btn-danger btn-sm act-reject" data-id="${data.id}">Reject</button>
                    //                     `;
                            //             }

                            //             return `<span class="text-muted">Already ${data.admin_status}</span>`;
                            //         }

                            //         return '';
                            //     }
                            // },

                            //         {
                            //             data: null,
                            //             title: 'Actions',
                            //             orderable: false,
                            //             searchable: false,
                            //             className: 'text-nowrap',
                            //             render: row => `
                    //     <button class="btn btn-sm btn-info" onclick="RecordsModal.openEdit(${row.id})">Edit</button>
                    //     <button class="btn btn-sm btn-primary" onclick="RecordsModal.openView(${row.id})">View</button>
                    //     <button class="btn btn-sm btn-danger" onclick="RecordsModal.delete(${row.id})">Delete</button>
                    // `
                            //         }
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

                        buttons: [{
                                extend: "copy",
                                className: "btn btn-sm btn-outline-primary me-1"
                            },
                            {
                                extend: "csv",
                                className: "btn btn-sm btn-outline-success me-1"
                            },
                            {
                                extend: "excel",
                                className: "btn btn-sm btn-outline-info me-1"
                            },
                            {
                                extend: "pdfHtml5",
                                className: "btn btn-sm btn-outline-danger me-1"
                            },
                            {
                                extend: "print",
                                className: "btn btn-sm btn-outline-secondary"
                            }
                        ],

                        // drawCallback: function() {
                        //     // ✅ Force responsive recalc after table render
                        //     if (this.responsive) this.responsive.recalc();
                        // }

                        drawCallback: function() {
                            if (recordsTable && recordsTable.responsive) {
                                recordsTable.responsive.recalc();
                            }
                        }

                    });
                },

                reload() {
                    if (recordsTable) {
                        recordsTable.ajax.reload(null, false);
                        recordsTable.responsive.recalc();
                    }
                }
            };


            /* ======================================================
             * BADGE HELPER
             * ====================================================== */
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

            /* ======================================================
             * Faculty SELECT HELPER
             * ====================================================== */
            const FacultySelect = {
                load(selectedId = null) {
                    console.log('selected faculty', selectedId);
                    let options = '<option value="">Select Faculty</option>';
                    if (!FACULTY_CACHE.length) {
                        options += '<option value="">No faculties found</option>';
                    } else {
                        FACULTY_CACHE.forEach(f => {
                            options +=
                                `<option value="${f.id}" ${f.id == selectedId ? 'selected' : ''}>${f.name}</option>`;
                        });
                    }
                    $('#faculty_id').html(options);
                }
            };

            /* ======================================================
             * Department SELECT HELPER
             * ====================================================== */
            const DepartmentSelect = {
                load(facultyId, selectedId = null) {
                    console.log('selected department', selectedId);
                    let options = '<option value="">Select Department</option>';
                    if (!DEPARTMENT_CACHE.length) {
                        options += '<option value="">No departments found</option>';
                    } else {
                        DEPARTMENT_CACHE
                            .filter(d => d.faculty_id == facultyId)
                            .forEach(d => {
                                options +=
                                    `<option value="${d.id}" ${d.id == selectedId ? 'selected' : ''}>${d.name}</option>`;
                            });
                    }
                    $('#department_id').html(options);
                }
            };

            $(document).on('change', '#faculty_id', function() {
                DepartmentSelect.load(this.value);
            });

            /* ======================================================
             * MODAL HANDLER
             * ====================================================== */
            const RecordsModal = {
                setMode(mode) {
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
                    FacultySelect.load();
                    $('#department_id').html('<option value="">Select Department</option>');
                    $('#recordsModal').modal('show');
                },

                openEdit(id) {
                    RecordsForm.reset();
                    this.setMode('edit');
                    const url = "{{ route('manage.leaves.show', ':id') }}".replace(':id', id);
                    $.ajax({
                        url,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token'),
                            Accept: 'application/json'
                        },
                        success: res => {
                            const c = res.data;
                            $('#record_id').val(c.id);
                            $('#name').val(c.name);
                            $('#code').val(c.code ?? '');
                            FacultySelect.load(c.faculty_id);
                            DepartmentSelect.load(c.faculty_id, c.department_id);

                            $('#status').val(c.status);
                            $('#recordsModal').modal('show');
                        },
                        error: () => Swal.fire('Error', 'Unable to load department', 'error')
                    });
                },

                openView(id) {
                    RecordsForm.reset();
                    this.setMode('view');
                    const url = "{{ route('manage.leaves.show', ':id') }}".replace(':id', id);
                    $.ajax({
                        url,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token'),
                            Accept: 'application/json'
                        },
                        success: res => {
                            const c = res.data;
                            $('#record_id').val(c.id);
                            $('#name').val(c.name);
                            $('#code').val(c.code ?? '');
                            FacultySelect.load(c.faculty_id);
                            DepartmentSelect.load(c.faculty_id, c.department_id);
                            $('#status').val(c.status);
                            $('#recordsModal').modal('show');
                        },
                        error: () => Swal.fire('Error', 'Unable to load faculty', 'error')
                    });
                },

                // delete(id) {
                //     Swal.fire({
                //         title: 'Are you sure?',
                //         text: 'This action cannot be undone',
                //         icon: 'warning',
                //         showCancelButton: true,
                //         confirmButtonColor: '#d33'
                //     }).then(result => {
                //         if (result.isConfirmed) {
                //             const url = "{{ route('courses.destroy', ':id') }}".replace(':id', id);
                //             $.ajax({
                //                 url,
                //                 type: 'DELETE',
                //                 // success: () => {
                //                 success: res => {
                //                     // Swal.fire('Deleted!', 'Course removed', 'success');
                //                     Swal.fire('Deleted!', res.message, 'success');
                //                     RecordsTable.reload();
                //                 }
                //             });
                //         }
                //     });
                // }
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

                        const url = "{{ route('manage.leaves.destroy', ':id') }}".replace(':id', id);

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
                }

            };

            /* ======================================================
             * FORM HANDLER
             * ====================================================== */
            const RecordsForm = {

                init() {
                    $('#recordsForm').on('submit', this.submit.bind(this));
                },

                submit(e) {
                    e.preventDefault();
                    if (!this.validate()) return;

                    const id = $('#record_id').val();
                    const url = id ?
                        "{{ route('manage.leaves.update', ':id') }}".replace(':id', id) :
                        "{{ route('manage.leaves.store') }}";

                    const formData = new FormData($('#recordsForm')[0]);
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

                    $('#recordsForm [name]').each((_, el) => {
                        const $el = $(el);
                        const name = $el.attr('name');
                        if ($el.prop('disabled') || !$el.is(':visible')) return;
                        if (!$el.prop('required')) return;

                        const type = ($el.attr('type') || '').toLowerCase();
                        const tag = el.tagName.toLowerCase();
                        let value = $el.val();

                        if (type === 'file' && (!$el[0].files || !$el[0].files.length) && $el.data('existing') !==
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

                        if ((value === null || value === '' || value === undefined || (tag === 'input' && !value
                                .trim()))) {
                            this.error(name, 'This field is required');
                            ok = false;
                        }
                    });

                    return ok;
                },

                handleError(xhr) {
                    if (xhr.status === 422) {
                        Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                            this.error(field, messages[0]);
                        });
                    } else {
                        Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong', 'error');
                    }
                },

                error(field, message) {
                    const $field = $(`[name="${field}"]`);
                    if (!$field.length) return;
                    $field.addClass('is-invalid');
                    const $error = $(`#${field}_error`);
                    if ($error.length) $error.text(message).show();
                },

                clearErrors() {
                    $('#recordsForm .is-invalid').removeClass('is-invalid');
                    $('#recordsForm .invalid-feedback').text('').hide();
                },

                reset() {
                    $('#recordsForm')[0].reset();
                    $('#record_id').val('');
                    this.clearErrors();
                }
            };



            // const LeaveActions = {

            //     submit(id, action, currentStart = '', currentEnd = '') {

            //         const isReject = action.includes('reject');
            //         const roleLabel = action.startsWith('hod') ? 'HOD' : 'Admin';
            //         const actionLabel = isReject ? 'Reject' : 'Approve';

            //         Swal.fire({
            //             title: `${roleLabel} ${actionLabel} Leave`,
            //             html: `
    //                 <div class="mb-2 text-start">
    //                     <label class="form-label">Standard Remarks</label>
    //                     <select id="std_remark" class="form-select">
    //                         <option value="">-- Custom Remark --</option>
    //                         <option value="Approved as per policy">Approved as per policy</option>
    //                         <option value="Leave justified">Leave justified</option>
    //                         <option value="Insufficient reason provided">Insufficient reason provided</option>
    //                         <option value="Attendance constraints">Attendance constraints</option>
    //                         <option value="Not eligible for leave">Not eligible for leave</option>
    //                     </select>
    //                 </div>

    //                 <div class="mb-2 text-start">
    //                     <label class="form-label">
    //                         Remarks ${isReject ? '<span class="text-danger">*</span>' : '(optional)'}
    //                     </label>
    //                     <textarea id="remarks"
    //                         class="form-control"
    //                         rows="3"
    //                         maxlength="500"
    //                         placeholder="Enter remarks"
    //                     ></textarea>
    //                 </div>

    //                 <div class="row">
    //                     <div class="col-md-6 mb-2 text-start">
    //                         <label class="form-label">Start Date (optional)</label>
    //                         <input type="date" id="start_date" class="form-control" value="${currentStart}">
    //                     </div>
    //                     <div class="col-md-6 mb-2 text-start">
    //                         <label class="form-label">End Date (optional)</label>
    //                         <input type="date" id="end_date" class="form-control" value="${currentEnd}">
    //                     </div>
    //                 </div>

    //                 <div class="mb-2 text-start">
    //                     <label class="form-label">Attachment (optional)</label>
    //                     <input type="file" id="attachment" class="form-control">
    //                 </div>
    //             `,
            //             didOpen: () => {
            //                 const stdSelect = document.getElementById('std_remark');
            //                 const remarks = document.getElementById('remarks');

            //                 stdSelect.addEventListener('change', () => {
            //                     if (stdSelect.value) {
            //                         remarks.value = stdSelect.value;
            //                         remarks.readOnly = true;
            //                     } else {
            //                         remarks.value = '';
            //                         remarks.readOnly = false;
            //                     }
            //                 });
            //             },
            //             showCancelButton: true,
            //             confirmButtonText: 'Submit',
            //             preConfirm: () => {

            //                 const remarks = document.getElementById('remarks').value.trim();
            //                 const start = document.getElementById('start_date').value;
            //                 const end = document.getElementById('end_date').value;

            //                 if (isReject && !remarks) {
            //                     Swal.showValidationMessage('Remarks are required for rejection');
            //                     return false;
            //                 }

            //                 if (start && end && new Date(start) > new Date(end)) {
            //                     Swal.showValidationMessage('Start date cannot be after end date');
            //                     return false;
            //                 }

            //                 return {
            //                     remarks,
            //                     start_date: start || null,
            //                     end_date: end || null,
            //                     attachment: document.getElementById('attachment').files[0] ?? null
            //                 };
            //             }
            //         }).then(result => {

            //             if (!result.isConfirmed) return;

            //             const url = "{{ route('manage.leaves.update', ':id') }}".replace(':id', id);

            //             const formData = new FormData();
            //             formData.append('_method', 'PUT');
            //             formData.append('action', action);

            //             if (result.value.remarks) formData.append('remarks', result.value.remarks);
            //             if (result.value.start_date) formData.append('start_date', result.value.start_date);
            //             if (result.value.end_date) formData.append('end_date', result.value.end_date);
            //             if (result.value.attachment) formData.append('attachment', result.value.attachment);

            //             $.ajax({
            //                 url,
            //                 type: 'POST',
            //                 data: formData,
            //                 processData: false,
            //                 contentType: false,
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 },
            //                 success: res => {
            //                     Swal.fire('Success', res.message, 'success');
            //                     RecordsTable.reload();
            //                 },
            //                 error: xhr => {
            //                     Swal.fire(
            //                         'Error',
            //                         xhr.responseJSON?.message ?? 'Action failed',
            //                         'error'
            //                     );
            //                 }
            //             });
            //         });
            //     }
            // };


            // const LeaveActions = {

            //     submit(id, action) {

            //         const isReject = action.includes('reject');
            //         const roleLabel = action.startsWith('hod') ? 'HOD' : 'Admin';
            //         const actionText = isReject ? 'Reject' : 'Approve';
            //         const badgeClass = isReject ? 'danger' : 'success';

            //         Swal.fire({
            //             title: `
    //                 <div class="text-center">
    //                     <span class="badge bg-${badgeClass} mb-2">${roleLabel} ${actionText}</span>
    //                     ${roleLabel} ${actionText} Leave
    //                 </div>
    //             `,
            //             html: `
    //                 <div class="mb-2 text-start">
    //                     <label class="form-label">Standard Remarks</label>
    //                     <select id="std_remark" class="form-select">
    //                         <option value="">-- Select --</option>
    //                         <option value="Approved as per policy">Approved as per policy</option>
    //                         <option value="Leave justified">Leave justified</option>
    //                         <option value="Insufficient reason provided">Insufficient reason provided</option>
    //                         <option value="Attendance constraints">Attendance constraints</option>
    //                         <option value="Not eligible for leave">Not eligible for leave</option>
    //                         <option value="__custom__">Custom remark</option>
    //                     </select>
    //                     <div class="invalid-feedback">Please select a remark</div>
    //                 </div>

    //                 <div class="mb-2 text-start d-none" id="remark_box">
    //                     <label class="form-label">
    //                         Custom Remarks ${isReject ? '<span class="text-danger">*</span>' : ''}
    //                     </label>
    //                     <textarea id="remarks"
    //                         class="form-control"
    //                         rows="3"
    //                         maxlength="500"
    //                         placeholder="Enter custom remarks"></textarea>
    //                     <div class="invalid-feedback">Custom remarks are required</div>
    //                 </div>

    //                 <div class="mb-2 text-start">
    //                     <label class="form-label">Attachment (optional)</label>
    //                     <input type="file" id="attachment" class="form-control">
    //                 </div>
    //             `,
            //             didOpen: () => {

            //                 const stdSelect = document.getElementById('std_remark');
            //                 const remarkBox = document.getElementById('remark_box');
            //                 const remarks = document.getElementById('remarks');

            //                 stdSelect.addEventListener('change', () => {
            //                     clearInvalid(stdSelect);
            //                     clearInvalid(remarks);

            //                     if (stdSelect.value === '__custom__') {
            //                         remarkBox.classList.remove('d-none');
            //                         remarks.value = '';
            //                         remarks.focus();
            //                     } else {
            //                         remarkBox.classList.add('d-none');
            //                         remarks.value = '';
            //                     }
            //                 });

            //                 remarks.addEventListener('input', () => clearInvalid(remarks));
            //             },
            //             showCancelButton: true,
            //             confirmButtonText: 'Submit',
            //             preConfirm: () => {

            //                 const stdSelect = document.getElementById('std_remark');
            //                 const remarks = document.getElementById('remarks');

            //                 clearInvalid(stdSelect);
            //                 clearInvalid(remarks);

            //                 let finalRemark = null;

            //                 // ❌ No selection at all
            //                 if (!stdSelect.value) {
            //                     markInvalid(stdSelect, 'Please select a remark');
            //                     return false;
            //                 }

            //                 // ❌ Custom selected but empty
            //                 if (stdSelect.value === '__custom__') {
            //                     finalRemark = remarks.value.trim();

            //                     if (!finalRemark) {
            //                         markInvalid(remarks, 'Custom remarks cannot be empty');
            //                         return false;
            //                     }
            //                 } else {
            //                     finalRemark = stdSelect.value;
            //                 }

            //                 // ❌ Reject requires remarks always
            //                 if (isReject && !finalRemark) {
            //                     markInvalid(stdSelect, 'Remarks are mandatory for rejection');
            //                     return false;
            //                 }

            //                 return {
            //                     remarks: finalRemark,
            //                     attachment: document.getElementById('attachment').files[0] ?? null
            //                 };
            //             }
            //         }).then(result => {

            //             if (!result.isConfirmed) return;

            //             const url = "{{ route('manage.leaves.update', ':id') }}".replace(':id', id);

            //             const formData = new FormData();
            //             formData.append('_method', 'PUT');
            //             formData.append('action', action);
            //             formData.append('remarks', result.value.remarks);

            //             if (result.value.attachment) {
            //                 formData.append('attachment', result.value.attachment);
            //             }

            //             $.ajax({
            //                 url,
            //                 type: 'POST',
            //                 data: formData,
            //                 processData: false,
            //                 contentType: false,
            //                 headers: {
            //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //                 },
            //                 success: res => {
            //                     Swal.fire('Success', res.message, 'success');
            //                     RecordsTable.reload();
            //                 },
            //                 error: xhr => {
            //                     Swal.fire(
            //                         'Error',
            //                         xhr.responseJSON?.message ?? 'Action failed',
            //                         'error'
            //                     );
            //                 }
            //             });
            //         });
            //     }
            // };

            const LEAVE_REASONS = {
                approve: [
                    'Medical documents verified',
                    'Family emergency approved',
                    'Approved as per hostel policy',
                    'Academic / official activity',
                    'Special permission granted'
                ],
                reject: [
                    'Insufficient reason provided',
                    'Required documents missing',
                    'Leave limit exceeded',
                    'Attendance constraints',
                    'Disciplinary issue',
                    'Not eligible as per policy'
                ]
            };


            const LeaveActions = {
                submit(id, action) {

                    const isReject = action.includes('reject');
                    const roleLabel = action.startsWith('hod') ? 'HOD' : 'Admin';
                    const actionText = isReject ? 'Reject' : 'Approve';
                    const badgeClass = isReject ? 'danger' : 'success';

                    const reasons = isReject ? LEAVE_REASONS.reject : LEAVE_REASONS.approve;

                    const reasonOptions = reasons.map(r =>
                        `<option value="${r}">${r}</option>`
                    ).join('');

                    Swal.fire({
                        title: `
                <div class="text-center">
                    <span class="badge bg-${badgeClass} mb-1">${roleLabel} ${actionText}</span>
                    <div>${roleLabel} ${actionText} Leave</div>
                </div>
            `,
                        html: `
                <div class="text-start">

                    <label class="form-label mb-1">
                        ${isReject ? 'Rejection Reason' : 'Approval Reason'}
                        <span class="text-danger">*</span>
                    </label>

                    <select id="std_remark" class="form-select mb-3">
                        <option value="">-- Select --</option>
                        ${reasonOptions}
                        <option value="__custom__">Other (Custom)</option>
                    </select>
                    <div class="invalid-feedback">Please select a reason</div>

                    <textarea id="remarks"
                        class="form-control mt-3 d-none"
                        rows="3"
                        maxlength="500"
                        placeholder="Enter custom remarks"></textarea>
                    <div class="invalid-feedback">Custom remarks are required</div>

                    <input type="file"
                        id="attachment"
                        class="form-control form-control-sm mt-2">
                </div>
            `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        didOpen: () => {

                            const stdSelect = document.getElementById('std_remark');
                            const remarks = document.getElementById('remarks');

                            stdSelect.addEventListener('change', () => {
                                clearInvalid(stdSelect);
                                clearInvalid(remarks);

                                if (stdSelect.value === '__custom__') {
                                    remarks.classList.remove('d-none');
                                    remarks.value = '';
                                    remarks.focus();
                                } else {
                                    remarks.classList.add('d-none');
                                    remarks.value = '';
                                }
                            });

                            remarks.addEventListener('input', () => clearInvalid(remarks));
                        },
                        preConfirm: () => {

                            const stdSelect = document.getElementById('std_remark');
                            const remarks = document.getElementById('remarks');

                            clearInvalid(stdSelect);
                            clearInvalid(remarks);

                            if (!stdSelect.value) {
                                markInvalid(stdSelect, 'Please select a reason');
                                return false;
                            }

                            let finalRemark = stdSelect.value;

                            if (stdSelect.value === '__custom__') {
                                finalRemark = remarks.value.trim();
                                if (!finalRemark) {
                                    markInvalid(remarks, 'Custom remarks are required');
                                    return false;
                                }
                            }

                            if (isReject && !finalRemark) {
                                markInvalid(stdSelect, 'Remarks are mandatory for rejection');
                                return false;
                            }

                            return {
                                remarks: finalRemark,
                                attachment: document.getElementById('attachment').files[0] ?? null
                            };
                        }

                    }).then(result => {

                        if (!result.isConfirmed) return;

                        const url = "{{ route('manage.leaves.update', ':id') }}".replace(':id', id);

                        const formData = new FormData();
                        formData.append('_method', 'PUT');
                        formData.append('action', action);
                        formData.append('remarks', result.value.remarks);

                        if (result.value.attachment) {
                            formData.append('attachment', result.value.attachment);
                        }

                        $.ajax({
                            url,
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: res => {
                                Swal.fire('Success', res.message, 'success');
                                RecordsTable.reload();
                            },
                            error: xhr => {
                                Swal.fire(
                                    'Error',
                                    xhr.responseJSON?.message ?? 'Action failed',
                                    'error'
                                );
                            }
                        });
                    });
                }
            };


            function markInvalid(el, message = '') {
                if (!el) return;

                el.classList.add('is-invalid');

                const wrapper = el.closest('.mb-2') || el.parentElement;
                if (!wrapper) return;

                const feedback = wrapper.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.innerText = message;
                    feedback.style.display = 'block';
                }
            }

            function clearInvalid(el) {
                if (!el) return;

                el.classList.remove('is-invalid');

                const wrapper = el.closest('.mb-2') || el.parentElement;
                if (!wrapper) return;

                const feedback = wrapper.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.innerText = '';
                    feedback.style.display = 'none';
                }
            }
        </script>

        {{-- <script>
            /* ======================================================
             * GLOBALS
             * ====================================================== */
            let recordsTable;
            let CURRENT_USER_ROLE = [];

            /* ======================================================
             * AJAX DEFAULT SETUP
             * ====================================================== */
            $.ajaxSetup({
                beforeSend: function(xhr) {
                    const token = localStorage.getItem('token');
                    if (token) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + token);
                    }
                    xhr.setRequestHeader('Accept', 'application/json');
                }
            });

            /* ======================================================
             * DATE FORMATTER (IST)
             * ====================================================== */
            function formatDateIST(date) {
                if (!date) return '<span class="text-muted">N/A</span>';

                return new Date(date).toLocaleString('en-IN', {
                    timeZone: 'Asia/Kolkata',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
            }

            /* ======================================================
             * INIT
             * ====================================================== */
            $(document).ready(function() {
                RecordsTable.init();
            });

            /* ======================================================
             * DATATABLE
             * ====================================================== */
            const RecordsTable = {

                init() {
                    if ($.fn.DataTable.isDataTable('#recordsTable')) {
                        $('#recordsTable').DataTable().destroy();
                        $('#recordsTable').empty();
                    }

                    recordsTable = $('#recordsTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: {
                            details: {
                                type: 'column',
                                target: 0
                            }
                        },

                        ajax: {
                            url: "{{ route('manage.leaves.index') }}",
                            type: 'GET',
                            dataSrc: function(res) {
                                if (!res || !res.data) {
                                    console.error('Invalid response', res);
                                    return [];
                                }

                                CURRENT_USER_ROLE = Array.isArray(res.data.role) ?
                                    res.data.role :
                                    [];

                                return res.data.leaves ?? [];
                            },
                            error: function() {
                                Swal.fire('Error', 'Unable to load leave records', 'error');
                            }
                        },

                        columns: [{
                                data: null,
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
                                render: (d, t, r, m) =>
                                    m.settings._iDisplayStart + m.row + 1
                            },
                            {
                                data: 'resident.name',
                                title: 'Name',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'resident.scholar_no',
                                title: 'Enrollment',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'resident.room_number',
                                title: 'Room',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'type',
                                title: 'Leave Type'
                            },
                            {
                                data: 'reason',
                                title: 'Reason'
                            },
                            {
                                data: 'start_date',
                                title: 'Start',
                                render: d => formatDateIST(d)
                            },
                            {
                                data: 'end_date',
                                title: 'End',
                                render: d => formatDateIST(d)
                            },
                            {
                                data: 'attachment',
                                title: 'Attachment',
                            },
                            {
                                data: 'hod_status',
                                title: 'HOD Status',
                                render: s => badge(s)
                            },
                            {
                                data: 'hod_remarks',
                                title: 'HOD Remarks',
                                defaultContent: '-'
                            },
                            {
                                data: 'hod_approved_at',
                                title: 'HOD Action At',
                                render: d => formatDateIST(d)
                            },
                            {
                                data: 'admin_status',
                                title: 'Admin Status',
                                render: s => badge(s)
                            },
                            {
                                data: 'admin_remarks',
                                title: 'Admin Remarks',
                                defaultContent: '-'
                            },
                            {
                                data: 'admin_approved_at',
                                title: 'Admin Action At',
                                render: d => formatDateIST(d)
                            },
                            {
                                data: 'status',
                                title: 'Status',
                                render: s => badge(s)
                            },
                            {
                                data: null,
                                title: 'Action',
                                orderable: false,
                                searchable: false,
                                render: actionButtons
                            }
                        ],

                        drawCallback: function() {

                            // if (recordsTable.responsive) {
                            //     recordsTable.responsive.recalc();
                            // }

                            if (recordsTable) {
                                recordsTable.ajax.reload(null, false);
                                recordsTable.responsive.recalc();
                            }

                        }
                    });
                },

                reload() {
                    if (recordsTable) {
                        recordsTable.ajax.reload(null, false);
                    }
                }
            };

            /* ======================================================
             * BADGE HELPER
             * ====================================================== */
            function badge(status) {
                switch (status) {
                    case 'approved':
                        return '<span class="badge bg-success">Approved</span>';
                    case 'rejected':
                        return '<span class="badge bg-danger">Rejected</span>';
                    default:
                        return '<span class="badge bg-warning text-dark">Pending</span>';
                }
            }

            /* ======================================================
             * ACTION BUTTON RENDERER
             * ====================================================== */
            function actionButtons(row) {
                const roles = Array.isArray(CURRENT_USER_ROLE) ? CURRENT_USER_ROLE : [];

                /* ---------- HOD FLOW ---------- */
                if (roles.includes('hod')) {
                    if (row.hod_status === 'pending') {
                        return `
                    <button class="btn btn-success btn-sm act-approve" data-id="${row.id}">Approve</button>
                    <button class="btn btn-danger btn-sm act-reject" data-id="${row.id}">Reject</button>
                `;
                    }
                    return `<span class="text-muted">HOD ${row.hod_status}</span>`;
                }

                /* ---------- ADMIN FLOW ---------- */
                if (roles.includes('admin')) {
                    if (row.hod_status !== 'approved') {
                        return `<span class="text-muted">Waiting for HOD</span>`;
                    }

                    if (row.admin_status === 'pending') {
                        return `
                    <button class="btn btn-success btn-sm act-approve" data-id="${row.id}">Approve</button>
                    <button class="btn btn-danger btn-sm act-reject" data-id="${row.id}">Reject</button>
                `;
                    }

                    return `<span class="text-muted">Admin ${row.admin_status}</span>`;
                }

                return '';
            }

            /* ======================================================
             * APPROVE / REJECT HANDLER
             * ====================================================== */
            $(document).on('click', '.act-approve, .act-reject', function() {
                const id = $(this).data('id');
                const action = $(this).hasClass('act-approve') ? 'approved' : 'rejected';

                Swal.fire({
                    title: `Confirm ${action}`,
                    input: 'textarea',
                    inputLabel: 'Remarks (optional)',
                    showCancelButton: true,
                    confirmButtonText: 'Submit'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('manage.leaves.index') }}",
                        type: 'POST',
                        data: {
                            leave_id: id,
                            status: action,
                            remarks: result.value
                        },
                        success: res => {
                            Swal.fire('Success', res.message, 'success');
                            RecordsTable.reload();
                        },
                        error: xhr => {
                            Swal.fire(
                                'Error',
                                xhr.responseJSON?.message ?? 'Action failed',
                                'error'
                            );
                        }
                    });
                });
            });
        </script> --}}
    @endpush
