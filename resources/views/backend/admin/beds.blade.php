@extends('admin.layout')


@section('content')
    <style>
        /* Hide expand/collapse icon if no columns are hidden */
        /* table.dataTable.dtr-inline.collapsed td.dtr-control:before {
                        display: none;
                    } */
        /* .modal .form-control,
                .modal .form-select {
                    height: 38px;
                } */
        .form-control,
        .form-select {
            height: 38px;
            /* Bootstrap default */
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
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

    <!-- Card -->
    {{-- <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="top-breadcrumbs d-flex justify-content-between align-items-center">
                    <div class="breadcrumbs p-0"><a class="p-0">Faculties List</a></div>
                    <!-- <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Record">+ Add
                                                                                                                                                                                                                                                                                                                                        Record</button> -->
                    <button class="btn btn-primary btn-sm" onclick="Modal.openCreate()">
                        <i class="fa fa-plus"></i> Add Record
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="recordTable" class="table status-table table-bordered table-hover w-100">
                        <thead class="table-light">
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Bed Number</th>
                                <th>Type</th>
                                <th>Room Number</th>
                                <th>Hostel Name</th>
                                <th>Floors</th>
                                <th>Status</th>
                                <th>University</th>
                                <th width="180">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>
    </section> --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Beds Management</h5>
            {{-- <button class="btn btn-primary" onclick="BedForm.openCreate()">Add Bed</button> --}}
            <button type="button" class="btn btn-primary" onclick="BedModal.open('add')">
                <i class="bi bi-plus-circle me-1"></i> Add Bed
            </button>
        </div>

        <div class="card-body">
            <table id="recordTable" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Bed</th>
                        <th>Type</th>
                        <th>Room</th>
                        <th>Floor</th>
                        <th>Hostel</th>
                        <th>University</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- @include('beds.modal') --}}
    {{-- <div class="modal fade" id="recordModal" tabindex="-1">
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

                        <!-- University (readonly for restricted users) -->
                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <select id="university_id" class="form-select" disabled></select>
                        </div>

                        <!-- Hostel -->
                        <div class="mb-3">
                            <label class="form-label">Hostel Name</label>
                            <select id="building_id" name="building_id" class="form-select"></select>
                            <div class="invalid-feedback" id="building_id_error"></div>
                        </div>

                        <!-- Floor -->
                        <div class="mb-3"> <label class="form-label">Floor</label> <select id="floor_no"
                                name="floor_no" class="form-select">
                                <option value="">Room Floor</option>
                                <option value="1">1st Floor</option>
                                <option value="2">2nd Floor</option>
                                <option value="3">3rd Floor</option>
                                <option value="4">4th Floor</option>
                                <option value="5">5th Floor</option>
                            </select>
                            <div class="invalid-feedback" id="gender_error"></div>
                        </div>

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

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="2" placeholder="Optional notes"></textarea>
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
    </div> --}}
    {{-- <div class="modal fade" id="recordModal" tabindex="-1">
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

                        <!-- University (readonly for restricted users) -->
                        <div class="mb-3">
                            <label class="form-label">University</label>
                            <select id="university_id" class="form-select" disabled></select>
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

                        <!-- Room -->
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <select id="room_id" name="room_id" class="form-select" required></select>
                            <div class="invalid-feedback" id="room_id_error"></div>
                        </div>

                        <!-- Bed Number -->
                        <div class="mb-3">
                            <label class="form-label">Bed Number</label>
                            <input type="text" name="bed_number" id="bed_number" class="form-control"
                                placeholder="B1 / Upper / A" required>
                            <div class="invalid-feedback" id="bed_number_error"></div>
                        </div>

                        <!-- Bed Type -->
                        <div class="mb-3"> <label class="form-label">Type</label> <select id="bed_type"
                                name="bed_type" class="form-select">
                                <option value="">Normal</option>
                                <option value="upper">Upper</option>
                                <option value="lower">Lower</option>
                                <option value="bunk">Bunk</option>
                            </select>
                            <div class="invalid-feedback" id="bed_type_error"></div>
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

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="2" placeholder="Optional notes"></textarea>
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
    </div> --}}
    <!-- ======================================================
                                                                                | BED CREATE / EDIT / VIEW MODAL
                                                                                ====================================================== -->
    <div class="modal fade" id="recordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shadow">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="recordModalTitle">Beds Management </h5>
                    @can('beds.create')
                        <button class="btn btn-primary" onclick="BedModal.open('add')">
                            <i class="bi bi-plus-circle"></i> Add Bed
                        </button>
                    @endcan
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal Body -->
                <form id="recordForm" autocomplete="off">
                    @csrf
                    <input type="hidden" id="record_id" name="id">

                    <div class="modal-body">

                        <!-- ===============================
                                                                                                        HIERARCHY SECTION
                                                                                                    ================================ -->
                        <div class="card mb-3 border">
                            <div class="card-header bg-light fw-semibold">
                                Hostel & Room Details
                            </div>

                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Hostel -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Hostel <span class="text-danger">*</span>
                                        </label>
                                        <select id="hostel_id" name="hostel_id" class="form-select" required>
                                            <option value="">Select Hostel</option>
                                        </select>
                                    </div>

                                    <!-- Room -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            Room <span class="text-danger">*</span>
                                        </label>
                                        <select id="room_id" name="room_id" class="form-select" required>
                                            <option value="">Select Room</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- ===============================
                                                                                                        BED DETAILS
                                                                                                    ================================ -->
                        <div class="card mb-3 border">
                            <div class="card-header bg-light fw-semibold">
                                Bed Information
                            </div>

                            <div class="card-body">
                                <div class="row g-3">

                                    <!-- Bed Number -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Bed Number <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="bed_number" id="bed_number"
                                            placeholder="e.g. B-01" required>
                                    </div>

                                    <!-- Bed Type -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Bed Type
                                        </label>
                                        <select name="bed_type" id="bed_type" class="form-select">
                                            <option value="">Select Type</option>
                                            <option value="single">Single</option>
                                            <option value="bunk">Bunk</option>
                                            <option value="double">Double</option>
                                        </select>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">
                                            Bed Status <span class="text-danger">*</span>
                                        </label>

                                        <select name="status" id="status" class="form-select" required>
                                            <option value=""> Select Status </option>

                                            <option value="available">Available</option>
                                            <option value="occupied">Occupied</option>
                                            <option value="reserved">Reserved</option>
                                            <option value="maintenance">Under Maintenance</option>
                                            <option value="blocked">Blocked</option>
                                        </select>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <!-- ===============================
                                                                                                        FUTURE READY (OPTIONAL)
                                                                                                    ================================ -->
                        <!--
                                                                                                    <div class="card border">
                                                                                                        <div class="card-header bg-light fw-semibold">
                                                                                                            Advanced Settings
                                                                                                        </div>
                                                                                                        <div class="card-body">
                                                                                                            <div class="row g-3">
                                                                                                                <div class="col-md-6">
                                                                                                                    <label class="form-label">Maintenance</label>
                                                                                                                    <select class="form-select" name="maintenance">
                                                                                                                        <option value="0">No</option>
                                                                                                                        <option value="1">Yes</option>
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <div class="col-md-6">
                                                                                                                    <label class="form-label">Remarks</label>
                                                                                                                    <input type="text" class="form-control" name="remarks">
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    -->

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="submit" class="btn btn-primary" id="recordSubmitBtn">
                            Save Bed
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    {{-- <script>
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
        let ROOM_CACHE = [];

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
                        url: "{{ route('beds.index') }}",
                        type: "GET",
                        dataSrc: function(json) {

                            // Cache buildings ONCE
                            if (json.meta && json.meta.rooms) {
                                ROOM_CACHE = json.meta.rooms;
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
                            data: 'bed_number',
                            title: 'Bed Number'
                        },
                        {
                            data: 'type',
                            title: 'Type'
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
        const RoomSelect = {
            load(selectedId = null) {

                let options = '<option value="">Select Room</option>';

                if (!ROOM_CACHE.length) {
                    options += '<option value="">No rooms available</option>';
                } else {
                    ROOM_CACHE.forEach(u => {
                        options += `
                    <option value="${u.id}" ${u.id == selectedId ? 'selected' : ''}>
                        ${u.name}
                    </option>`;
                    });
                }

                $('#room_id').html(options);
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
                    add: 'Add Bed',
                    edit: 'Edit Bed',
                    view: 'View Bed'
                } [mode]);
            },

            openCreate() {
                RecordForm.reset();
                this.setMode('add');
                RoomSelect.load();
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

                $.get("{{ route('beds.show', ':id') }}".replace(':id', id))
                    .done(res => {
                        const d = res.data;
                        $('#record_id').val(d.id);
                        $('#bed_number').val(d.bed_number);
                        $('#bed_type').val(d.bed_type ?? '');
                        $('#status').val(d.status ?? '');

                        // Room info
                        $('#room_id').val(d.room.id);
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
                        // RoomSelect.load(d.university.id, () => {
                        //     $('#building_id').val(d.building.id);
                        // });

                        // Room select 
                        RoomSelect.load(d.building.id);

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
                        // RoomSelect.load(d.university_id);
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
                        url: "{{ route('beds.destroy', ':id') }}".replace(':id', id),
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
                    "{{ route('beds.update', ':id') }}".replace(':id', id) :
                    "{{ route('beds.store') }}";

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
    </script> --}}

    {{-- <script>
        /* ======================================================
     | GLOBAL APP INITIALIZATION
     ====================================================== */
        const App = {
            init() {
                Config.init();
                Utils.Ajax.init();
                Utils.Cache.init();

                Table.BedTable.init();
                Form.BedForm.init();
            }
        };

        $(document).ready(() => App.init());

        /* ======================================================
         | CONFIG: ROUTES
         ====================================================== */
        const Config = {
            init() {
                window.ROUTES = {
                    beds: {
                        index: "{{ route('beds.index') }}",
                        store: "{{ route('beds.store') }}",
                        show: id => "{{ route('beds.show', ':id') }}".replace(':id', id),
                        update: id => "{{ route('beds.update', ':id') }}".replace(':id', id),
                        delete: id => "{{ route('beds.destroy', ':id') }}".replace(':id', id)
                    }
                };
            }
        };

        /* ======================================================
         | UTILS: AJAX, CACHE, STATUS
         ====================================================== */
        const Utils = {
            Ajax: {
                init() {
                    const token = localStorage.getItem('token');
                    $.ajaxSetup({
                        headers: {
                            'Authorization': token ? `Bearer ${token}` : '',
                            'Accept': 'application/json'
                        }
                    });
                },
                get(url, success, fail) {
                    $.get(url).done(success).fail(fail);
                },
                post(url, data, success, fail) {
                    $.ajax({
                        url,
                        method: 'POST',
                        data,
                        processData: false,
                        contentType: false
                    }).done(success).fail(fail);
                },
                delete(url, success, fail) {
                    $.ajax({
                        url,
                        method: 'DELETE'
                    }).done(success).fail(fail);
                }
            },

            Cache: {
                rooms: [],
                buildings: [],
                init() {
                    this.rooms = [];
                    this.buildings = [];
                },
                set(key, data) {
                    this[key] = data;
                },
                get(key) {
                    return this[key] || [];
                }
            },

            Status: {
                map: {
                    active: 'success',
                    available: 'success',
                    occupied: 'warning',
                    reserved: 'danger',
                    maintenance: 'secondary'
                },
                badge(status) {
                    const cls = this.map[status?.toLowerCase()] || 'secondary';
                    return `<span class="badge bg-${cls}">${status}</span>`;
                },
                normalize(status) {
                    return ['active', 'available'].includes(status?.toLowerCase()) ? 'active' : status;
                }
            }
        };

        /* ======================================================
         | TABLES
         ====================================================== */
        const Table = {

            Base: {
                indexColumn() {
                    return {
                        data: null,
                        title: '#',
                        orderable: false,
                        render: (_, __, ___, meta) => meta.settings._iDisplayStart + meta.row + 1
                    };
                },
                statusColumn(key = 'status') {
                    return {
                        data: key,
                        title: 'Status',
                        render: s => Utils.Status.badge(Utils.Status.normalize(s))
                    };
                },
                actionsColumn(editCb, viewCb, deleteCb) {
                    return {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        className: 'text-nowrap',
                        render: id => `
                    <button class="btn btn-sm btn-primary" onclick="${editCb}(${id})">Edit</button>
                    <button class="btn btn-sm btn-info" onclick="${viewCb}(${id})">View</button>
                    <button class="btn btn-sm btn-danger" onclick="${deleteCb}(${id})">Delete</button>
                `
                    };
                }
            },

            BedTable: {
                table: null,

                init() {
                    if ($.fn.DataTable.isDataTable('#recordTable')) {
                        this.table.destroy();
                        $('#recordTable').empty();
                    }

                    this.table = $('#recordTable').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        ajax: {
                            url: ROUTES.beds.index,
                            type: 'GET',
                            dataSrc: json => {
                                if (json.meta?.rooms) Utils.Cache.set('rooms', json.meta.rooms);
                                if (json.meta?.buildings) Utils.Cache.set('buildings', json.meta.buildings);
                                return json.data;
                            }
                        },
                        columns: [{
                                data: null,
                                className: 'dtr-control',
                                orderable: false,
                                defaultContent: ''
                            },
                            Table.Base.indexColumn(),
                            {
                                data: 'bed_number',
                                title: 'Bed Number'
                            },
                            {
                                data: 'type',
                                title: 'Type'
                            },
                            {
                                data: 'room.room_number',
                                title: 'Room Number',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'building.name',
                                title: 'Hostel',
                                defaultContent: 'N/A'
                            },
                            {
                                data: 'floor_no',
                                title: 'Floor'
                            },
                            Table.Base.statusColumn(),
                            {
                                data: 'university.name',
                                title: 'University',
                                defaultContent: 'N/A'
                            },
                            Table.Base.actionsColumn('Modal.BedModal.edit', 'Modal.BedModal.view',
                                'Modal.BedModal.remove')
                        ],
                        order: [
                            [1, 'desc']
                        ]
                    });
                },

                reload() {
                    this.table?.ajax.reload(null, false);
                }
            }
        };

        /* ======================================================
         | MODALS
         ====================================================== */
        const Modal = {

            BedModal: {
                open(mode, id = null) {
                    Form.BedForm.reset();
                    Form.BedForm.setMode(mode);

                    if (!id) return $('#recordModal').modal('show');

                    Utils.Ajax.get(
                        ROUTES.beds.show(id),
                        res => Form.BedForm.fill(res.data),
                        () => Swal.fire('Error', 'Unable to load record', 'error')
                    );

                    $('#recordModal').modal('show');
                },

                edit(id) {
                    this.open('edit', id);
                },
                view(id) {
                    this.open('view', id);
                },

                remove(id) {
                    Swal.fire({
                        title: 'Confirm delete?',
                        icon: 'warning',
                        showCancelButton: true
                    }).then(r => {
                        if (!r.isConfirmed) return;
                        Utils.Ajax.delete(ROUTES.beds.delete(id), () => Table.BedTable.reload());
                    });
                }
            }
        };

        /* ======================================================
         | FORMS
         ====================================================== */
        const Form = {

            Base: {
                setMode(mode) {
                    const readOnly = mode === 'view';
                    $('#recordForm input, #recordForm select').prop('disabled', readOnly);
                    $('#recordSubmitBtn').toggle(!readOnly);
                },

                reset() {
                    $('#recordForm')[0].reset();
                    $('#record_id').val('');
                    ErrorHandler.clear();
                },

                fill(data) {
                    $('#record_id').val(data.id);
                    $('#bed_number').val(data.bed_number);
                    $('#bed_type').val(data.type ?? '');
                    $('#status').val(Utils.Status.normalize(data.status));

                    $('#room_id').val(data.room.id);
                    $('#building_id').val(data.building.id);

                    // Generate floors dynamically
                    const floors = data.building.floors ?? 5;
                    $('#floor_no').empty();
                    for (let i = 1; i <= floors; i++) {
                        $('#floor_no').append($('<option>', {
                            value: i,
                            text: `Floor ${i}`
                        }));
                    }
                    $('#floor_no').val(data.floor);
                },

                validate() {
                    let valid = true;
                    $('#recordForm [required]').each((_, el) => {
                        const $el = $(el);
                        if (!$el.val()) {
                            $el.addClass('is-invalid');
                            valid = false;
                        }
                    });
                    return valid;
                }
            },

            BedForm: {
                init() {
                    $('#recordForm').on('submit', e => this.submit(e));
                },

                submit(e) {
                    e.preventDefault();
                    if (!Form.Base.validate()) return;

                    const id = $('#record_id').val();
                    const formData = new FormData($('#recordForm')[0]);
                    if (id) formData.append('_method', 'PUT');

                    Utils.Ajax.post(
                        id ? ROUTES.beds.update(id) : ROUTES.beds.store,
                        formData,
                        () => {
                            Swal.fire('Success', 'Bed saved successfully', 'success');
                            $('#recordModal').modal('hide');
                            Table.BedTable.reload();
                        },
                        xhr => ErrorHandler.handle(xhr)
                    );
                },

                reset() {
                    Form.Base.reset();
                },
                fill(data) {
                    Form.Base.fill(data);
                },
                setMode(mode) {
                    Form.Base.setMode(mode);
                }
            }
        };

        /* ======================================================
         | ERROR HANDLER
         ====================================================== */
        const ErrorHandler = {
            handle(xhr) {
                this.clear();
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                        const $el = $(`[name="${field}"]`);
                        $el.addClass('is-invalid');
                        if (!$(`#${field}_error`).length) {
                            $el.after(`<div class="invalid-feedback" id="${field}_error">${messages[0]}</div>`);
                        } else {
                            $(`#${field}_error`).text(messages[0]).show();
                        }
                    });
                    return;
                }
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            },

            clear() {
                $('#recordForm .is-invalid').removeClass('is-invalid');
                $('#recordForm .invalid-feedback').hide().text('');
            }
        };
    </script> --}}


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
        | GLOBAL APP STATE
        ====================================================== */
        const AppState = {
            table: null,
            cache: {
                hostels: [],
                // rooms: []
                roomsByHostel: {} // hostel_id => rooms[]
            },
            filters: {
                hostel_id: null
            }
        };


        $(document).ready(function() {
            BedTable.init();
            BedForm.init();
        });

        const BedTable = {

            init() {

                if ($.fn.DataTable.isDataTable('#recordTable')) {
                    AppState.table.destroy();
                    $('#recordTable').empty();
                }

                AppState.table = $('#recordTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,

                    ajax: {
                        url: "{{ route('beds.index') }}",
                        type: "GET",
                        dataSrc(res) {

                            // Cache hierarchy once
                            if (res.meta) {
                                // AppState.cache.rooms = res.meta.rooms ?? [];
                                // AppState.cache.hostels = res.meta.buildings ?? [];
                                if (res.meta?.hostels) {

                                    AppState.cache.hostels = res.meta.hostels;

                                    // Build hostel → room map
                                    AppState.cache.roomsByHostel = {};
                                    res.meta.hostels.forEach(h => {
                                        AppState.cache.roomsByHostel[h.id] = h.rooms ?? [];
                                    });
                                }

                            }

                            return res.data;
                        }
                    },

                    columns: [{
                            data: null,
                            className: 'dtr-control',
                            orderable: false,
                            width: '1%',
                            defaultContent: ''
                        },
                        {
                            data: null,
                            title: '#',
                            render: (d, t, r, m) => m.settings._iDisplayStart + m.row + 1
                        },
                        {
                            data: 'bed_number',
                            title: 'Bed No'
                        },
                        {
                            data: 'type',
                            title: 'Type'
                        },
                        {
                            data: 'room_number',
                            title: 'Room'
                        },
                        {
                            data: 'hostel',
                            title: 'Hostel'
                        },
                        {
                            data: 'floor_no',
                            title: 'Floor'
                        },
                        {
                            data: 'university',
                            title: 'University'
                        },
                        {
                            data: 'status',
                            title: 'Status',
                            render: s =>
                                s === 1 ?
                                '<span class="badge bg-success">Active</span>' :
                                '<span class="badge bg-danger">Inactive</span>'
                        },
                        {
                            data: 'id',
                            title: 'Actions',
                            orderable: false,
                            render: id => `
                        <button class="btn btn-sm btn-primary" onclick="BedModal.edit(${id})">Edit</button>
                        <button class="btn btn-sm btn-info" onclick="BedModal.view(${id})">View</button>
                        <button class="btn btn-sm btn-danger" onclick="BedModal.delete(${id})">Delete</button>
                    `
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
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            },

            reload() {
                AppState.table?.ajax.reload(null, false);
            }
        };

        const HierarchySelect = {

            loadHostels(selected = null) {
                let html = '<option value="">Select Hostel</option>';

                AppState.cache.hostels.forEach(h => {
                    html += `<option value="${h.id}" ${h.id == selected ? 'selected' : ''}>
                        ${h.name}
                     </option>`;
                });

                $('#hostel_id').html(html);
            },

            loadRooms(hostelId, selected = null) {

                // const rooms = AppState.cache.rooms.filter(r => r.hostel_id == hostelId);
                let rooms = AppState.cache.roomsByHostel[hostelId] ?? [];

                let html = '<option value="">Select Room</option>';

                // rooms.forEach(r => {
                //     html += `<option value="${r.id}" ${r.id == selected ? 'selected' : ''}>
            //         ${r.room_number} (${r.available_beds} beds free)
            //      </option>`;
                // });
                if (!rooms.length) {
                    html += `<option value="">No rooms available</option>`;
                } else {
                    rooms.forEach(r => {
                        html += `
                    <option value="${r.id}" ${r.id == selected ? 'selected' : ''}>
                        ${r.room_number} (Floor ${r.floor_no})
                    </option>`;
                    });
                }

                $('#room_id').html(html);
            }
        };

        const BedModal = {

            open(mode, data = null) {

                BedForm.reset();

                const isView = mode === 'view';

                // $('#recordSubmitBtn').toggle(mode !== 'view');
                // Modal title & submit button
                $('#recordSubmitBtn').toggle(!isView);
                $('#recordModalTitle').text({
                    add: 'Add Bed',
                    edit: 'Edit Bed',
                    view: 'View Bed'
                } [mode]);

                // $('#recordForm input, select').prop('disabled', mode === 'view');
                // Lock form in view mode
                $('#recordForm input, #recordForm select')
                    .prop('disabled', isView);

                // Always load hostels first (cached)
                HierarchySelect.loadHostels();

                if (data) {
                    console.log(data);
                    $('#record_id').val(data.id);
                    $('#bed_number').val(data.bed_number);
                    $('#bed_type').val(data.type);
                    $('#status').val(data.status);
                    $('#status').val(data.status);

                    // HierarchySelect.loadHostels(data.hostel_id);
                    // HierarchySelect.loadRooms(data.hostel_id, data.room_id);
                    // Hierarchy (safe order)
                    // if (data.hierarchy.hostel.id) {
                    if (data.hostel_id) {
                        HierarchySelect.loadHostels(data.hostel_id);
                        HierarchySelect.loadRooms(
                            data.hostel_id,
                            data.room_id ?? null
                        );
                    }
                } else {
                    // Create mode → reset dependent selects
                    $('#room_id').html('<option value="">Select Room</option>');
                }

                $('#recordModal').modal('show');
            },

            edit(id) {
                BedForm.reset();
                this.fetch(id, 'edit');
            },

            view(id) {
                this.fetch(id, 'view');
            },

            fetch(id, mode) {

                $.get("{{ route('beds.show', ':id') }}".replace(':id', id))
                    .done(res => {

                        if (!res?.data) {
                            Swal.fire('Error', 'Invalid server response', 'error');
                            return;
                        }

                        this.open(mode, res.data);
                    })
                    .fail(() =>
                        Swal.fire('Error', 'Unable to load bed details', 'error')
                    );
            },

            delete(id) {
                Swal.fire({
                    title: 'Delete Bed?',
                    icon: 'warning',
                    showCancelButton: true
                }).then(r => {
                    if (!r.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('beds.destroy', ':id') }}".replace(':id', id),
                        type: 'DELETE'
                    }).done(() => {
                        Swal.fire('Deleted', 'Bed removed', 'success');
                        BedTable.reload();
                    });
                });
            }
        };

        const BedForm = {

            init() {
                $('#recordForm').on('submit', this.submit.bind(this));
                this.bindLiveValidation();
                $('#hostel_id').on('change', e => {
                    HierarchySelect.loadRooms(e.target.value);
                });
            },

            submit(e) {
                e.preventDefault();

                const id = $('#record_id').val();
                const url = id ?
                    "{{ route('beds.update', ':id') }}".replace(':id', id) :
                    "{{ route('beds.store') }}";

                const fd = new FormData(e.target);
                if (id) fd.append('_method', 'PUT');

                $.ajax({
                        url,
                        method: 'POST',
                        data: fd,
                        processData: false,
                        contentType: false
                    })
                    .done(res => {
                        Swal.fire('Success', res.message, 'success');
                        $('#recordModal').modal('hide');
                        BedTable.reload();
                    })
                    .fail(xhr => this.handleError(xhr));
            },

            // handleError(xhr) {

            //     $('.is-invalid').removeClass('is-invalid');
            //     $('.invalid-feedback').remove();

            //     if (xhr.status === 422) {
            //         Object.entries(xhr.responseJSON.errors).forEach(([f, m]) => {
            //             const el = $(`[name="${f}"]`);
            //             el.addClass('is-invalid')
            //                 .after(`<div class="invalid-feedback">${m[0]}</div>`);
            //         });
            //         return;
            //     }

            //     Swal.fire('Error', xhr.responseJSON?.message || 'Server error', 'error');
            // },
            handleError(xhr) {

                this.clearErrors();

                // FIELD / VALIDATION ERRORS
                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {

                        const $input = $(`[name="${field}"]`);

                        if (!$input.length) return;

                        $input.addClass('is-invalid');

                        let $error = $(`#${field}_error`);

                        // Create error container ONLY if missing
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

                // SYSTEM / BUSINESS ERROR
                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Something went wrong',
                    'error'
                );
            },

            bindLiveValidation() {

                $('#recordForm').on('input change', 'input, select', function() {
                    const name = $(this).attr('name');

                    $(this).removeClass('is-invalid');
                    $(`#${name}_error`).hide().text('');
                });
            },

            clearErrors() {
                $('#recordForm .is-invalid').removeClass('is-invalid');
                $('#recordForm .invalid-feedback').hide().text('');
            },

            reset() {

                const $form = $('#recordForm');

                $form[0].reset();

                $('#record_id').val('');

                // Remove validation state
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('.invalid-feedback').hide().text('');
            },

        };
    </script>
@endpush
