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
                                <th>Room Number</th>
                                <th>Hostel Name</th>
                                <th>Floors</th>
                                <th>Type</th>
                                <th>Capacity</th>
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

    {{-- @include('rooms.modal') --}}
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

                        <!-- Room Number -->
                        <div class="mb-3">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" id="room_number" name="room_number"
                                placeholder="Room Number">
                            <div class="invalid-feedback" id="room_number_error"></div>
                        </div>

                        <!-- Room Type -->
                        <div class="mb-3"> <label class="form-label">Type</label> <select id="room_type" name="room_type"
                                class="form-select">
                                <option value="">Room Type</option>
                                <option value="single">Single</option>
                                <option value="double">Double</option>
                                <option value="triple">Triple</option>
                            </select>
                            <div class="invalid-feedback" id="room_type_error"></div>
                        </div>

                        <!-- Room Capacity -->
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="text" class="form-control" id="capacity" name="capacity"
                                placeholder="Room Capacity">
                            <div class="invalid-feedback" id="capacity_error"></div>
                        </div>

                        <!-- Hostel -->
                        <div class="mb-3">
                            <label class="form-label">Hostel Name</label>
                            <select id="building_id" name="building_id" class="form-select"></select>
                            <div class="invalid-feedback" id="building_id_error"></div>
                        </div>

                        <!-- Floor -->
                        <div class="mb-3"> <label class="form-label">Floor</label> <select id="floor_no" name="floor_no"
                                class="form-select">
                                <option value="">Room Floor</option>
                                <option value="1">1st Floor</option>
                                <option value="2">2nd Floor</option>
                                <option value="3">3rd Floor</option>
                                <option value="4">4th Floor</option>
                                <option value="5">5th Floor</option>
                            </select>
                            <div class="invalid-feedback" id="gender_error"></div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="reserved">Reserved</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="inactive">Inactive</option>
                            </select>

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
        let HOSTEL_CACHE = [];

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
                        url: "{{ route('rooms.index') }}",
                        type: "GET",
                        dataSrc: function(json) {

                            // Cache buildings ONCE
                            if (json.meta && json.meta.buildings) {
                                HOSTEL_CACHE = json.meta.buildings;
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
                            data: 'room_number',
                            title: 'Room Number'
                        },
                        {
                            data: 'building',
                            title: 'Hostel Name'
                        },

                        {
                            data: 'floor',
                            title: 'Floor'
                        },

                        {
                            data: 'type',
                            title: 'Room Type'
                        },
                        {
                            data: 'capacity',
                            title: 'Capacity'
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
         | HOSTEL SELECT HELPER
         ====================================================== */
        const HostelSelect = {
            load(selectedId = null) {

                let options = '<option value="">Select Hostel</option>';

                if (!HOSTEL_CACHE.length) {
                    options += '<option value="">No hostels available</option>';
                } else {
                    HOSTEL_CACHE.forEach(u => {
                        options += `
                    <option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>
                        ${u.name}
                    </option>`;
                    });
                }

                $('#building_id').html(options);
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
                    add: 'Add Room',
                    edit: 'Edit Room',
                    view: 'View Room'
                } [mode]);
            },

            openCreate() {
                RecordForm.reset();
                this.setMode('add');
                HostelSelect.load();
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

                $.get("{{ route('rooms.show', ':id') }}".replace(':id', id))
                    .done(res => {
                        const d = res.data;
                        $('#record_id').val(d.id);
                        $('#room_number').val(d.room_number);
                        $('#room_type').val(d.room_type ?? '');
                        $('#capacity').val(d.capacity ?? '');
                        $('#status').val(d.status ?? '');

                        // Building info
                        $('#building_id').val(d.building.id);

                        // Floors belong to building
                        const totalFloors = d.building.floors;
                        const currentFloor = d.floor_no;

                        $('#floor_no').empty();
                        for (let i = 1; i <= totalFloors; i++) {
                            $('#floor_no').append(
                                $('<option>', {
                                    value: i,
                                    text: `Floor ${i}`
                                })
                            );
                        }
                        $('#floor_no').val(currentFloor);

                        // University select (if dependent)
                        // HostelSelect.load(d.university.id, () => {
                        //     $('#building_id').val(d.building.id);
                        // });

                        // Hostel select 
                        HostelSelect.load(d.building.id);

                        // // Clear old options 
                        // $('#floors').empty();
                        // // Always start from 2 
                        // for (let i = 2; i <= d.floors + 1; i++) {
                        //     $('#floors').append($('<option>', {
                        //         value: i,
                        //         text: i
                        //     }));
                        // }

                        // // // Preselect existing value 
                        // $('#floors').val(d.floors);

                        // $('#status').val(d.status);
                        // HostelSelect.load(d.university_id);
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
                        url: "{{ route('rooms.destroy', ':id') }}".replace(':id', id),
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
                    "{{ route('rooms.update', ':id') }}".replace(':id', id) :
                    "{{ route('rooms.store') }}";

                const formData = new FormData($('#recordForm')[0]);
                if (id) formData.append('_method', 'PUT');

                console.log([...formData]);
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


        $('#building_id').on('change', function() {
            const buildingId = $(this).val();

            $('#floor_no').empty().append('<option value="">Select floor</option>');

            if (!buildingId) return;

            $.get(`/buildings/${buildingId}/floors`, res => {
                for (let i = 1; i <= res.floors; i++) {
                    $('#floor_no').append(`<option value="${i}">Floor ${i}</option>`);
                }
            });
        });
    </script>
@endpush
