@extends('admin.layout')


@section('content')
    {{-- <style>
        /* Hide expand/collapse icon if no columns are hidden */
        table.dataTable.dtr-inline.collapsed td.dtr-control:before {
            display: none;
        }
    </style> --}}
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

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Faculties List</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Record">+ Add
                                                                                                                                                                                                                                        Record</button> -->
                    <button class="btn btn-primary btn-sm" onclick="RecordModal.openCreate()">
                        <i class="fa fa-plus"></i> Add Record
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="recordTable" class="table status-table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Hostel Name</th>
                                <th>Code</th>
                                <th>Floors</th>
                                <th>Hostel Type</th>
                                <th>Status</th>
                                <th>University</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </section>

    {{-- @include('hostels.modal') --}}
    <div class="modal fade" id="recordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title mpop-title" id="recordModalTitle">Add Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <form id="recordForm">
                        @csrf
                        <input type="hidden" id="record_id">

                        <!-- Hostel Name -->
                        <div class="mb-3">
                            <label class="form-label">Hostel Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Hostel Name">
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>

                        <!-- Hostel Code -->
                        <div class="mb-3">
                            <label class="form-label">Building Code (Optional)</label>
                            <input type="text" class="form-control" id="code" name="code"
                                placeholder="Building Code">
                            <div class="invalid-feedback" id="code_error"></div>
                        </div>

                        <!-- University -->
                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <select id="university_id" name="university_id" class="form-select"></select>
                            <div class="invalid-feedback" id="university_id_error"></div>
                        </div>

                        <!-- Gender -->
                        <div class="mb-3"> <label class="form-label">Type</label> <select id="type" name="type"
                                class="form-select">
                                <option value="">Hostel Type</option>
                                <option value="male">Boys</option>
                                <option value="female">Girls</option>
                                <option value="mixed">Co-ed</option>
                            </select>
                            <div class="invalid-feedback" id="gender_error"></div>
                        </div>

                        <!-- Floors -->
                        {{-- <div class="mb-3"> <label class="form-label">Floors</label> <input type="number"
                                class="form-control" id="floors" name="floors" min="1"
                                placeholder="Enter number of floors">
                            <div class="invalid-feedback" id="floors_error"></div>
                        </div> --}}
                        <div class="mb-3">
                            <label class="form-label">Floors</label>
                            <select id="floors" name="floors" class="form-select">
                                <option value="">Select Number Of Floors</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                            <div class="invalid-feedback" id="floors_error"></div>
                        </div>


                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="status_error"></div>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close">
                                Cancel</button>
                            <button type="submit" class="blue" id="recordSubmitBtn"> Submit</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        /* ======================================================
            | GLOBAL AJAX SETUP
            ====================================================== */
        const API_TOKEN = localStorage.getItem('token');

        $.ajaxSetup({
            headers: {
                'Authorization': API_TOKEN ? `Bearer ${API_TOKEN}` : '',
                'Accept': 'application/json'
            }
        });

        /* ======================================================
         | GLOBAL STATE
         ====================================================== */
        let recordTable = null;
        let UNIVERSITY_CACHE = [];

        /* ======================================================
         | INIT
         ====================================================== */
        $(document).ready(function() {
            RecordTable.init();
            RecordForm.init();
        });

        /* ======================================================
         | DATATABLE MODULE (SERVER-SIDE)
         ====================================================== */
        const RecordTable = {

            init() {

                if ($.fn.DataTable.isDataTable('#recordTable')) {
                    recordTable.destroy();
                    $('#recordTable').empty();
                }

                recordTable = $('#recordTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    searching: true,
                    ordering: true,

                    responsive: {
                        details: {
                            type: 'column',
                            target: 0
                        }
                    },

                    ajax: {
                        url: "{{ route('hostels.index') }}",
                        type: "GET",
                        dataSrc: function(json) {

                            // Cache universities ONCE
                            if (json.meta && json.meta.universities) {
                                UNIVERSITY_CACHE = json.meta.universities;
                            }

                            return json.data;
                        }
                    },

                    columns: [{
                            data: null,
                            className: 'dtr-control',
                            orderable: false,
                            searchable: false,
                            width: '1%',
                            defaultContent: ''
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
                            data: 'name',
                            title: 'Hostel Name'
                        },
                        {
                            data: 'code',
                            title: 'Hostel Code'
                        },
                        {
                            data: 'floors',
                            title: 'Floors'
                        },
                        {
                            data: 'type',
                            title: 'Hostel Type',
                            render: function(data, type, row) {
                                if (!data) return ''; // handle empty/null

                                const val = data.toLowerCase().trim();
                                if (val === 'male') return 'Boys';
                                if (val === 'female') return 'Girls';
                                if (val === 'mixed' || val === 'co-ed') return 'Mixed';

                                return data; // fallback: show original if no match
                            }
                        },
                        {
                            data: 'status',
                            title: 'Status',
                            render: function(s) {
                                return s === 1 ?
                                    '<span class="badge bg-success">Active</span>' :
                                    '<span class="badge bg-danger">Inactive</span>';
                            }
                        },
                        {
                            data: 'university',
                            title: 'University',
                            defaultContent: '<span class="text-muted">N/A</span>'
                        },
                        {
                            data: 'id',
                            title: 'Actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-nowrap',
                            render: function(id) {
                                return `
                            <button class="btn btn-sm btn-primary" onclick="RecordModal.openEdit(${id})">Edit</button>
                            <button class="btn btn-sm btn-info" onclick="RecordModal.openView(${id})">View</button>
                            <button class="btn btn-sm btn-danger" onclick="RecordModal.delete(${id})">Delete</button>
                            
                        `;
                            }
                        }
                    ],
                    //   <button class="btn btn-sm btn-danger" onclick="RecordModal.delete(${id})">Delete</button>

                    // order: [
                    //     [1, 'desc']
                    // ],

                    dom: `
                        <'row mb-2'
                            <'col-md-3'l>
                            <'col-md-5 text-center'B>
                            <'col-md-4'f>
                        >
                        <'row'<'col-12'tr>>
                        <'row mt-2'
                            <'col-md-5'i>
                            <'col-md-7'p>
                        >
                    `,

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
                    ],

                    drawCallback: function() {
                        if (this.responsive) this.responsive.recalc();
                    }
                });
            },

            reload() {
                if (recordTable) {
                    recordTable.ajax.reload(null, false);
                }
            }
        };

        /* ======================================================
         | UNIVERSITY SELECT HELPER
         ====================================================== */
        const UniversitySelect = {
            load(selectedId = null) {

                let options = '<option value="">Select University</option>';

                if (!UNIVERSITY_CACHE.length) {
                    options += '<option value="">No universities available</option>';
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
         | MODAL HANDLER 
         ====================================================== */
        const RecordModal = {

            setMode(mode) {
                const viewOnly = mode === 'view';

                $('#recordForm input, #recordForm select')
                    .prop('readonly', viewOnly)
                    .prop('disabled', viewOnly);

                $('#recordSubmitBtn').toggle(!viewOnly);

                $('#recordModalTitle').text({
                    add: 'Add Hostel',
                    edit: 'Edit Hostel',
                    view: 'View Hostel'
                } [mode]);
            },

            openCreate() {
                RecordForm.reset();
                this.setMode('add');
                UniversitySelect.load();
                $('#recordModal').modal('show');
            },

            openEdit(id) {
                this.loadAndOpen(id, 'edit');
            },

            openView(id) {
                this.loadAndOpen(id, 'view');
            },

            loadAndOpen(id, mode) {
                RecordForm.reset();
                this.setMode(mode);

                $.get("{{ route('hostels.show', ':id') }}".replace(':id', id))
                    .done(res => {
                        const d = res.data;
                        $('#record_id').val(d.id);
                        $('#name').val(d.name);
                        $('#type').val(d.type) ?? '';

                        $('#code').val(d.code) ?? '';
                        // Clear old options 
                        // $('#floors').empty();
                        // // Always start from 2 
                        // for (let i = 2; i <= d.floors + 1; i++) {
                        //     $('#floors').append($('<option>', {
                        //         value: i,
                        //         text: i
                        //     }));
                        // }

                        // // Preselect existing value 
                        $('#floors').val(d.floors);

                        $('#status').val(d.status);
                        UniversitySelect.load(d.university_id);
                        $('#recordModal').modal('show');
                    })
                    .fail(() => Swal.fire('Error', 'Unable to load record', 'error'));
            },

            delete(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('hostels.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE'
                    }).done(() => {
                        Swal.fire('Deleted!', 'Record removed successfully', 'success');
                        RecordTable.reload();
                    });
                });
            }
        };

        /* ======================================================
         | FORM HANDLER
         ====================================================== */
        const RecordForm = {

            init() {
                $('#recordForm').on('submit', this.submit.bind(this));
            },

            submit(e) {
                e.preventDefault();
                if (!this.validate()) return;

                const id = $('#record_id').val();
                const url = id ?
                    "{{ route('hostels.update', ':id') }}".replace(':id', id) :
                    "{{ route('hostels.store') }}";

                const formData = new FormData($('#recordForm')[0]);
                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                }).done(res => {
                    Swal.fire('Success', res.message, 'success');
                    $('#recordModal').modal('hide');
                    RecordTable.reload();
                }).fail(xhr => this.handleError(xhr));
            },

            validate() {
                this.clearErrors();
                let valid = true;

                $('#recordForm [required]').each((_, el) => {
                    const $el = $(el);
                    if (!$el.val()) {
                        this.error($el.attr('name'), 'This field is required');
                        valid = false;
                    }
                });

                return valid;
            },

            // handleError(xhr) {
            //     if (xhr.status === 422) {
            //         Object.entries(xhr.responseJSON.errors).forEach(([f, m]) => {
            //             this.error(f, m[0]);
            //         });
            //     } else {
            //         Swal.fire('Error', 'Something went wrong', 'error');
            //     }
            // },

            handleError(xhr) {

                this.clearErrors();

                // Laravel validation error
                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {

                        const $input = $(`[name="${field}"]`);

                        if (!$input.length) return;

                        $input.addClass('is-invalid');

                        let $error = $(`#${field}_error`);

                        // auto-create error container if missing
                        if (!$error.length) {
                            $error = $('<div>')
                                .attr('id', `${field}_error`)
                                .addClass('invalid-feedback')
                                .insertAfter($input);
                        }

                        $error.text(messages[0]).show();
                    });

                    return;
                }

                // Other errors
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Something went wrong',
                    'error'
                );
            },


            error(field, message) {
                const $f = $(`[name="${field}"]`);
                $f.addClass('is-invalid');
                $(`#${field}_error`).text(message).show();
            },

            clearErrors() {
                $('#recordForm .is-invalid').removeClass('is-invalid');
                $('#recordForm .invalid-feedback').hide().text('');
            },

            reset() {
                $('#recordForm')[0].reset();
                $('#record_id').val('');
                this.clearErrors();
            }
        };
    </script>
@endpush
