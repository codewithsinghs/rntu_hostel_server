@extends('admin.layout')


@section('content')
    <style>
        /* Hide expand/collapse icon if no columns are hidden */
        table.dataTable.dtr-inline.collapsed td.dtr-control:before {
            display: none;
        }
    </style>
    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview</a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a href="">Academic Details</a></div>

                <div class="card-ds-bottom">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Faculties</p>
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
                    <div class="breadcrumbs p-0"><a class="p-0">Faculties List</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Faculty">+ Add
                                                                        Faculty</button> -->
                    <button class="btn btn-primary btn-sm" onclick="FacultyModal.openCreate()">
                        <i class="fa fa-plus"></i> Add Faculty
                    </button>
                </div>

                <!-- <div class="container-fluid">
                                                            <div class="card shadow-sm">
                                                                <div class="card-header d-flex justify-content-between">
                                                                    <h5 class="mb-0">Faculties</h5>
                                                                    <button class="btn btn-primary btn-sm" onclick="FacultyModal.openCreate()">
                                                                        <i class="fa fa-plus"></i> Add Faculty
                                                                    </button>
                                                                </div> -->



                <div class="overflow-auto">
                    <table id="facultyTable" class="table status-table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>University</th>
                                <th>Faculty Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </section>

    {{-- @include('faculties.modal') --}}
    <div class="modal fade" id="facultyModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <form id="facultyForm">
                @csrf
                <input type="hidden" id="faculty_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="facultyModalTitle">Add Faculty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Faculty Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <select id="university_id" name="university_id" class="form-select"></select>
                            <div class="invalid-feedback" id="university_id_error"></div>
                        </div>



                        {{-- <div class="mb-3">
                            <label class="form-label">Faculty University</label>
                            <input type="text" class="form-control" id="university">
                            <div class="invalid-feedback" id="university_error"></div>
                        </div> --}}
                        <!-- Hidden input to store the university_id -->
                        {{-- <input type="hidden" id="university_id"> --}}

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status_error"></div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <!-- CANCEL button should not submit -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <!-- SAVE button triggers submit -->
                        <button type="submit" class="btn btn-primary" id="facultySubmitBtn">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const API_TOKEN = localStorage.getItem('token');

        $.ajaxSetup({
            headers: {
                'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : '',
                'Accept': 'application/json'
            }

        });
    </script>

    {{-- <script src="{{ asset('js/faculty.js') }}"></script> --}}
    <script>
        let facultyTable;

        $(document).ready(function() {
            FacultyTable.init();
            FacultyForm.init();
        });

        /* ======================================================
         * DATATABLE
         * ====================================================== */
        let UNIVERSITY_CACHE = [];

        // const FacultyTable = {

        //     init() {
        //         facultyTable = $('#facultyTable').DataTable({
        //             processing: true,
        //             serverSide: false,

        //             responsive: {
        //                 details: {
        //                     type: 'column',
        //                     target: 0 // FIRST column
        //                 }
        //             },

        //             autoWidth: false,

        //             ajax: {
        //                 url: "{{ route('faculties.index') }}",
        //                 dataSrc: function(res) {

        //                     // 🔑 cache universities for later use
        //                     UNIVERSITY_CACHE = res.data.universities ?? [];

        //                     return res.data.faculties ?? [];
        //                 }
        //             },

        //             columns: [
        //                 // {
        //                 //     className: 'dtr-control',
        //                 //     orderable: false,
        //                 //     data: null,
        //                 //     defaultContent: '',
        //                 //     width: '1%',
        //                 // }, {
        //                 //     data: null,
        //                 //     title: '#',
        //                 //     orderable: false,
        //                 //     render: (data, type, row, meta) => meta.row + 1
        //                 // },
        //                 {
        //                     data: null,
        //                     title: '#',
        //                     orderable: false,
        //                     className: 'dtr-control',
        //                     width: '1%',
        //                     render: (data, type, row, meta) => meta.row + 1
        //                 },

        //                 {
        //                     data: 'university.name',
        //                     title: 'University',
        //                     defaultContent: '<span class="text-muted">N/A</span>'
        //                 },
        //                 {
        //                     data: 'name',
        //                     title: 'Faculty Name'
        //                 },
        //                 {
        //                     data: 'code',
        //                     title: 'Code',
        //                     defaultContent: ''
        //                 },
        //                 {
        //                     data: 'status',
        //                     title: 'Status',
        //                     render: s => {
        //                         const active = s === 1 || s === '1' || s === true;
        //                         return active ?
        //                             '<span class="badge bg-success">Active</span>' :
        //                             '<span class="badge bg-danger">Inactive</span>';
        //                     }
        //                 },
        //                 {
        //                     data: null,
        //                     title: 'Actions',
        //                     orderable: false,
        //                     searchable: false,
        //                     className: 'text-nowrap',
        //                     render: row => `
    //                 <button class="btn btn-sm btn-info" onclick="FacultyModal.openEdit(${row.id})">Edit</button>
    //                 <button class="btn btn-sm btn-primary" onclick="FacultyModal.openView(${row.id})">View</button>
    //                 <button class="btn btn-sm btn-danger" onclick="FacultyModal.delete(${row.id})">Delete</button>
    //             `
        //                 }
        //             ],

        //             // columnDefs: [{
        //             //         responsivePriority: 1,
        //             //         targets: 1 // University
        //             //     },
        //             //     {
        //             //         responsivePriority: 2,
        //             //         targets: 2 // Faculty Name
        //             //     },
        //             //     {
        //             //         responsivePriority: 3,
        //             //         targets: -1 // Actions
        //             //     }
        //             // ]
        //         });
        //     },

        //     reload() {
        //         facultyTable.ajax.reload(null, false);
        //     }
        // };

        const FacultyTable = {
            init() {
                // Destroy if already initialized
                if ($.fn.DataTable.isDataTable('#facultyTable')) {
                    $('#facultyTable').DataTable().destroy();
                    $('#facultyTable').empty(); // remove old table HTML to avoid duplicate rows
                }


                facultyTable = $('#facultyTable').DataTable({
                    processing: true,
                    serverSide: false,
                    autoWidth: false,

                    responsive: {
                        details: {
                            type: 'column',
                            target: 0 // first column (SR) is control
                        }
                    },

                    ajax: {
                        url: "{{ route('faculties.index') }}",
                        dataSrc: function(res) {
                            UNIVERSITY_CACHE = res.data.universities ?? [];
                            return res.data.faculties ?? [];
                        }
                    },

                    columns: [{
                            data: null,
                            title: '#',
                            orderable: false,
                            className: 'dtr-control',
                            width: '1%',
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'university.name',
                            title: 'University',
                            defaultContent: '<span class="text-muted">N/A</span>',
                            responsivePriority: 1
                        },
                        {
                            data: 'name',
                            title: 'Faculty Name',
                            responsivePriority: 2
                        },
                        {
                            data: 'code',
                            title: 'Code',
                            defaultContent: '',
                            responsivePriority: 4
                        },
                        {
                            data: 'status',
                            title: 'Status',
                            render: s => (s == 1 || s === '1') ?
                                '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-danger">Inactive</span>',
                            responsivePriority: 3
                        },
                        {
                            data: null,
                            title: 'Actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-nowrap',
                            render: row => `
            <button class="btn btn-sm btn-info" onclick="FacultyModal.openEdit(${row.id})">Edit</button>
            <button class="btn btn-sm btn-primary" onclick="FacultyModal.openView(${row.id})">View</button>
            <button class="btn btn-sm btn-danger" onclick="FacultyModal.delete(${row.id})">Delete</button>
        `,
                            responsivePriority: 5
                        }
                    ]
                });

            },

            reload() {
                if (facultyTable) facultyTable.ajax.reload(null, false);
            }
        };





        //  // ✅ Destroy existing DataTable instance before reinitializing
        //             if (FacultyTable) {
        //                 $('#facultyTable').DataTable().destroy();
        //             }

        //             // Datatable
        //             InitializeDatatable();


        const UniversitySelect = {

            load(selectedId = null) {

                let options = '<option value="">Select University</option>';

                if (!UNIVERSITY_CACHE.length) {
                    options += '<option value="">No universities found</option>';
                } else {
                    UNIVERSITY_CACHE.forEach(u => {
                        options += `
                    <option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>
                        ${u.name}
                    </option>`;
                    });
                }

                $('#university_id').html(options);
            }
        };


        /* ======================================================
         * MODAL HANDLER
         * ====================================================== */
        const FacultyModal = {
            setMode(mode) {
                const isView = mode === 'view';

                // Toggle readonly / disabled
                $('#facultyForm input, #facultyForm select')
                    .prop('readonly', isView)
                    .prop('disabled', isView);

                // Save button only for add/edit
                $('#facultySubmitBtn').toggle(!isView);

                const titles = {
                    add: 'Add Faculty',
                    edit: 'Edit Faculty',
                    view: 'View Faculty'
                };

                $('#facultyModalTitle').text(titles[mode]);
            },

            openCreate() {
                FacultyForm.reset();
                this.setMode('add');

                // $('#university_id')
                //     .empty()
                //     .append('<option value="">Select University</option>');

                UniversitySelect.load(); // 🔑 from cached data

                $('#facultyModalTitle').text('Add Faculty');
                $('#facultyModal').modal('show');
            },

            openEdit(id) {

                FacultyForm.reset();
                this.setMode('edit');
                const url = "{{ route('faculties.show', ':id') }}".replace(':id', id);

                // $.get(`/faculties/${id}`, res => {
                // $.get(url, res => {
                $.ajax({
                    url: url,
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    success: res => {
                        const d = res.data;
                        $('#faculty_id').val(d.id);
                        $('#name').val(d.name);
                        $('#code').val(d.code ?? '');
                        // university fields
                        // load universities and select the correct one
                        // loadUniversities(d.university_id);

                        // $('#university_id').val(d.university_id ?? ''); // hidden input for backend
                        // $('#university').val(d.university_name ?? ''); // visible to user

                        // 🔑 Set university select using received data
                        // $('#university_id')
                        //     .empty()
                        //     .append(`<option value="${d.university_id}" selected>
                    //     ${d.university_name}
                    // </option>`);

                        // 🔑 select university from cached list
                        UniversitySelect.load(d.university_id);

                        // $('#status').val(d.status ? 1 : 0);
                        $('#status').val(d.status); // directly set 1 or 0


                        $('#facultyModalTitle').text('Edit Faculty');
                        $('#facultyModal').modal('show');
                    },
                    error: () => {
                        Swal.fire('Error', 'Unable to load faculty', 'error');
                    }
                });
            },

            openView(id) {

                FacultyForm.reset();
                this.setMode('view');

                const url = "{{ route('faculties.show', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        Authorization: 'Bearer ' + localStorage.getItem('token'),
                        Accept: 'application/json'
                    },
                    success: res => {
                        const d = res.data;

                        $('#faculty_id').val(d.id);
                        $('#name').val(d.name);
                        $('#code').val(d.code ?? '');
                        // university fields
                        // loadUniversities(d.university_id);

                        // $('#university_id').val(d.university_id ?? ''); // hidden input for backend
                        // $('#university').val(d.university_name ?? ''); // visible to user

                        // 🔑 select university from cached list
                        UniversitySelect.load(d.university_id);

                        // $('#status').val(d.status === 'active' ? 1 : 0);
                        $('#status').val(d.status); // directly set 1 or 0

                        $('#facultyModal').modal('show');
                    },
                    error: xhr => {
                        console.error(xhr);
                        Swal.fire(
                            'Unauthenticated',
                            'Session expired or token missing. Please login again.',
                            'warning'
                        );
                    }
                });
            },

            delete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then(result => {
                    if (result.isConfirmed) {

                        const url = "{{ route('faculties.destroy', ':id') }}".replace(':id', id);

                        $.ajax({
                            // url: `/faculties/${id}`,
                            url: url,
                            type: 'DELETE',
                            success: () => {
                                Swal.fire('Deleted!', 'Faculty removed', 'success');
                                FacultyTable.reload();
                            }
                        });
                    }
                });
            }
        };

        /* ======================================================
         * FORM HANDLER
         * ====================================================== */
        // const FacultyForm = {

        //     init() {
        //         $('#facultyForm').on('submit', this.submit);
        //     },

        //     submit(e) {
        //         e.preventDefault();
        //         FacultyForm.clearErrors();

        //         const id = $('#faculty_id').val();
        //         const method = id ? 'PUT' : 'POST';
        //         // const url = id ? `/faculties/${id}` : '/faculties';
        //         const url = id ?
        //             "{{ route('faculties.update', ':id') }}".replace(':id', id) :
        //             "{{ route('faculties.store') }}";

        //         $.ajax({
        //             url: url,
        //             type: method,
        //             data: {
        //                 name: $('#name').val(),
        //                 university: $('#university').val(),
        //                 status: $('#status').val()
        //             },
        //             success: res => {
        //                 Swal.fire('Success', res.message, 'success');
        //                 $('#facultyModal').modal('hide');
        //                 FacultyTable.reload();
        //             },
        //             error: xhr => FacultyForm.handleError(xhr)
        //         });
        //     },

        //     handleError(xhr) {
        //         if (xhr.status === 422) {
        //             const errors = xhr.responseJSON.errors;
        //             Object.keys(errors).forEach(field => {
        //                 $(`#${field}`).addClass('is-invalid');
        //                 $(`#${field}_error`).text(errors[field][0]);
        //             });
        //         } else {
        //             Swal.fire(
        //                 'Error',
        //                 xhr.responseJSON?.message ?? 'Something went wrong',
        //                 'error'
        //             );
        //         }
        //     },

        //     clearErrors() {
        //         $('.is-invalid').removeClass('is-invalid');
        //         $('.invalid-feedback').text('');
        //     },

        //     reset() {
        //         $('#facultyForm')[0].reset();
        //         $('#faculty_id').val('');
        //         this.clearErrors();
        //     }
        // };

        const FacultyForm = {

            init() {
                $('#facultyForm').on('submit', this.submit.bind(this));
            },

            submit(e) {
                e.preventDefault();

                if (!this.validate()) return;

                const id = $('#faculty_id').val();
                const method = id ? 'POST' : 'POST';
                const url = id ?
                    "{{ route('faculties.update', ':id') }}".replace(':id', id) :
                    "{{ route('faculties.store') }}";

                const formData = new FormData($('#facultyForm')[0]);
                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url,
                    method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: res => {
                        Swal.fire('Success', res.message, 'success');
                        $('#facultyModal').modal('hide');
                        FacultyTable.reload();
                    },
                    error: xhr => this.handleError(xhr)
                });
            },

            // 🔑 Universal frontend validation
            validate() {

                let ok = true;
                this.clearErrors();

                $('#facultyForm')
                    .find('[name]')
                    .each((_, el) => {

                        const $el = $(el);
                        const name = $el.attr('name');
                        const type = ($el.attr('type') || '').toLowerCase();
                        const tag = el.tagName.toLowerCase();

                        // Skip disabled or hidden fields
                        if ($el.prop('disabled') || !$el.is(':visible')) {
                            return;
                        }

                        // Only validate required fields
                        if (!$el.prop('required')) {
                            return;
                        }

                        /* ===========================
                           FILE
                        ============================ */
                        if (type === 'file') {

                            // If editing & file already exists → don't force
                            if ($el.data('existing') === true) {
                                return;
                            }

                            if (!el.files || !el.files.length) {
                                this.error(name, 'This file is required');
                                ok = false;
                            }

                            return;
                        }

                        /* ===========================
                           CHECKBOX
                        ============================ */
                        if (type === 'checkbox') {

                            // Checkbox group
                            if ($(`[name="${name}"]:checked`).length === 0) {
                                this.error(name, 'Please select at least one option');
                                ok = false;
                            }

                            return;
                        }

                        /* ===========================
                           RADIO
                        ============================ */
                        if (type === 'radio') {

                            if ($(`[name="${name}"]:checked`).length === 0) {
                                this.error(name, 'Please select an option');
                                ok = false;
                            }

                            return;
                        }

                        /* ===========================
                           SELECT / TEXT / DATE / TEXTAREA
                        ============================ */
                        let value = $el.val();

                        if (value === null || value === '' || value === undefined) {
                            this.error(name, 'This field is required');
                            ok = false;
                            return;
                        }

                        // Trim text-based inputs
                        if (tag === 'input' || tag === 'textarea') {
                            if (typeof value === 'string' && !value.trim()) {
                                this.error(name, 'This field is required');
                                ok = false;
                            }
                        }

                    });

                return ok;
            },

            // 🔑 Backend validation handler (ALL INPUT TYPES)
            handleError(xhr) {

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;

                    Object.entries(errors).forEach(([field, messages]) => {
                        this.error(field, messages[0]);
                    });

                } else {
                    Swal.fire(
                        'Error',
                        xhr.responseJSON?.message ?? 'Something went wrong',
                        'error'
                    );
                }
            },

            // 🔑 Smart error injector
            // error(field, message) {

            //     const $field = $(`[name="${field}"]`);

            //     if (!$field.length) return;

            //     $field.addClass('is-invalid');

            //     const $feedback =
            //         $field.siblings('.invalid-feedback')
            //         .add($field.closest('.form-group, .mb-3').find(`[data-error-for="${field}"]`));

            //     $feedback.text(message).show();
            // },
            error(field, message) {

                const $field = $(`[name="${field}"]`);

                if (!$field.length) return;

                $field.addClass('is-invalid');

                const $error = $(`#${field}_error`);

                if ($error.length) {
                    $error.text(message).show();
                }
            },

            clearErrors() {
                $('#facultyForm .is-invalid').removeClass('is-invalid');
                $('#facultyForm .invalid-feedback').text('').hide();
            },

            reset() {
                $('#facultyForm')[0].reset();
                $('#faculty_id').val('');
                this.clearErrors();
            }
        };




        // const FacultyForm = {

        //     validate() {

        //         let ok = true;

        //         this.clearErrors();

        //         if (!$('#name').val().trim()) {
        //             this.error('name', 'Faculty name is required');
        //             ok = false;
        //         }

        //         if (!$('#university_id').val()) {
        //             this.error('university_id', 'University is required');
        //             ok = false;
        //         }

        //         return ok;
        //     },

        //     error(field, message) {
        //         $(`#${field}`).addClass('is-invalid');
        //         $(`#${field}_error`).text(message);
        //     },

        //     clearErrors() {
        //         $('.is-invalid').removeClass('is-invalid');
        //         $('.invalid-feedback').text('');
        //     },

        //     reset() {
        //         $('#facultyForm')[0].reset();
        //         this.clearErrors();
        //         $('#faculty_id').val('');
        //     }
        // };

        // $('#facultyForm').on('submit', function(e) {
        //     e.preventDefault();

        //     if (!FacultyForm.validate()) return;

        //     const id = $('#faculty_id').val();
        //     const isEdit = Boolean(id);

        //     const url = isEdit ?
        //         "{{ route('faculties.update', ':id') }}".replace(':id', id) :
        //         "{{ route('faculties.store') }}";

        //     const method = isEdit ? 'PUT' : 'POST';

        //     const token = localStorage.getItem('sanctum_token');

        //     $.ajax({
        //         url,
        //         method,
        //         headers: {
        //             Authorization: 'Bearer ' + localStorage.getItem('token'),
        //             Accept: 'application/json'
        //         },
        //         data: {
        //             name: $('#name').val(),
        //             university_id: $('#university_id').val(),
        //             status: $('#status').val()
        //         },
        //         success(res) {
        //             Swal.fire('Success', res.message, 'success');
        //             $('#facultyModal').modal('hide');
        //             FacultyTable.reload();
        //         },
        //         error(xhr) {

        //             if (xhr.status === 422) {
        //                 const errors = xhr.responseJSON.errors;
        //                 Object.keys(errors).forEach(f => {
        //                     FacultyForm.error(f, errors[f][0]);
        //                 });
        //             } else {
        //                 Swal.fire('Error', 'Something went wrong', 'error');
        //             }
        //         }
        //     });
        // });




        // function loadUniversities(selectedId = null) {
        //     const token = localStorage.getItem('sanctum_token');
        //     // const url =  url: "{{ route('faculties.index') }}",
        //     $.ajax({
        //         url: "{{ route('universities.index') }}",
        //         headers: {
        //             Authorization: 'Bearer ' + localStorage.getItem('token'),
        //             Accept: 'application/json'
        //         },
        //         success: res => {
        //             const $select = $('#university_id');
        //             $select.empty();
        //             $select.append('<option value="">Select University</option>');

        //             res.data.forEach(u => {
        //                 $select.append(
        //                     `<option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>${u.name}</option>`
        //                 );
        //             });
        //         },
        //         error: () => Swal.fire('Error', 'Unable to load universities', 'error')
        //     });
        // }
    </script>
@endpush
