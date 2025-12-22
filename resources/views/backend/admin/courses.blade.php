@extends('admin.layout')


@section('content')
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
                    <div class="breadcrumbs p-0"><a class="p-0">Corses List</a></div>
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
                                <th>Faculty Name</th>
                                <th>Department</th>
                                <th>Course</th>
                                <th>Code</th>
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
                        serverSide: false,
                        autoWidth: false,
                        responsive: {
                            details: {
                                type: 'column',
                                target: 0 // first column is the toggle button
                            }
                        },
                        ajax: {
                            url: "{{ route('courses.index') }}",
                            dataSrc: function(res) {
                                FACULTY_CACHE = res.data.faculties ?? [];
                                DEPARTMENT_CACHE = res.data.departments ?? [];
                                return res.data.courses ?? [];
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
                                // 🔹 SR column (#)
                                data: null,
                                title: '#',
                                orderable: false,
                                render: (data, type, row, meta) => meta.row + 1,
                                width: '1%'
                            },
                            {
                                data: 'department.faculty.name',
                                title: 'Faculty',
                                defaultContent: '<span class="text-muted">N/A</span>'
                            },
                            {
                                data: 'department.name',
                                title: 'Department',
                                defaultContent: '<span class="text-muted">N/A</span>'
                            },
                            {
                                data: 'name',
                                title: 'Course Name'
                            },
                            {
                                data: 'code',
                                title: 'Code',
                                defaultContent: ''
                            },
                            {
                                data: 'status',
                                title: 'Status',
                                render: s => (s == 1 || s === '1') ?
                                    '<span class="badge bg-success">Active</span>' :
                                    '<span class="badge bg-danger">Inactive</span>'
                            },
                            {
                                data: null,
                                title: 'Actions',
                                orderable: false,
                                searchable: false,
                                className: 'text-nowrap',
                                render: row => `
                        <button class="btn btn-sm btn-info" onclick="RecordsModal.openEdit(${row.id})">Edit</button>
                        <button class="btn btn-sm btn-primary" onclick="RecordsModal.openView(${row.id})">View</button>
                        <button class="btn btn-sm btn-danger" onclick="RecordsModal.delete(${row.id})">Delete</button>
                    `
                            }
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
                    const url = "{{ route('courses.show', ':id') }}".replace(':id', id);
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
                    const url = "{{ route('courses.show', ':id') }}".replace(':id', id);
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

                        const url = "{{ route('courses.destroy', ':id') }}".replace(':id', id);

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
                        "{{ route('courses.update', ':id') }}".replace(':id', id) :
                        "{{ route('courses.store') }}";

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
        </script>
    @endpush
