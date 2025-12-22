@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <!-- breadcrumbs -->
        <div class="breadcrumbs"><a href="">Overview</a></div>
        <!-- Popup Btn -->
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddBed">+ Add Bed</button>
    </div>

    <!-- Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Beds Details</a></div>

                <!-- Room Details -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Beds</p>
                            <h3>500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Occupied Beds</p>
                            <h3>400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Available Beds</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Damage Bed</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/4.png')}}" alt="">
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

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Beds List</a></div>

                <div id="mainResponseMessage" class="mt-3"></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th>S. No</th>
                                <th>Bed Number</th>
                                <th>Room Number</th>
                                <th>Building Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="bedList">
                            <tr>
                                <td colspan="7" class="text-center">No rooms found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>


    <!-- Add Room Popup-->
    <div class="modal fade" id="AddBed" tabindex="-1" aria-labelledby="AddBedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Bed</div>
                    </div>

                    <form id="createBedForm">
                        @csrf

                        <div class="middle">

                            <span class="input-set">
                                <label for="buildingSelect">Select Hostel</label>
                                <select class="form-select" id="buildingSelect" name="building_id" required>
                                    <option selected>Select Hostel</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="SelectFloor">Select Floor</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Floor</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="roomSelect">Select Room</label>
                                <select class="form-select" id="roomSelect" name="room_id" required>
                                    <option selected>Select Room</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="BedType">Bed Type</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Floor</option>
                                    <option value="Single Bed">Single Bed</option>
                                    <option value="Double Bed">Double Bed</option>
                                    <option value="Triple Bed">Triple Bed</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="bedNumber">Create Bed Number</label>
                                <input type="text" id="bedNumber" name="bed_number" required
                                    placeholder="Enter Create Bed Number">
                            </span>

                            <span class="input-set">
                                <label for="BedStatus">Bed Status</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Occupied">Damage</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red"> Cancel </button>
                            <button type="submit" class="blue"> Creat Bed </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Edit Modal Popup-->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Bed</div>
                    </div>

                    <input type="hidden" id="editId">

                    <form id="editForm">
                        @csrf

                        <div class="middle">

                            <span class="input-set">
                                <label for="editBedNumber">Bed Number</label>
                                <input type="text" id="editBedNumber" required>
                            </span>

                            <span class="input-set">
                                <label for="editRoomId">Room ID</label>
                                <input type="number" id="editRoomId" required>
                            </span>

                            <span class="input-set">
                                <label for="editStatus">Status</label>
                                <select id="editStatus" class="form-select">
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue"> Update </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- ConfirmationPopup -->
    <div class="modal fade" id="ConfirmationPopup" tabindex="-1" aria-labelledby="ConfirmationPopupLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Confirm Deletion</div>
                    </div>

                    <div class="middle-content">
                        <p>Deleting this record will permanently remove it from your system. Proceed with caution.</p>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Delete </button>
                        <button type="button" class="blue"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')



    <script>
        $(document).ready(function () {
            fetchBuildings();
            fetchBeds();

            $("#buildingSelect").on("change", function () {
                const buildingId = $(this).val();
                fetchRoomsByBuilding(buildingId);
            });

            $("#createBedForm").on("submit", function (event) {
                event.preventDefault();
                createBed();
            });

            $("#editForm").on("submit", function (event) {
                event.preventDefault();
                updateBed();
            });
        });

        // Fetch buildings
        function fetchBuildings() {
            $.ajax({
                url: '/api/admin/buildings',
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    const select = $('#buildingSelect');
                    select.empty().append('<option value="">Select Building</option>');
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(function (building) {
                            select.append(`<option value="${building.id}">${building.name}</option>`);
                        });
                    }
                },
                error: function (error) {
                    console.error("Error fetching buildings:", error);
                }
            });
        }

        // Fetch rooms
        function fetchRoomsByBuilding(buildingId) {
            const roomSelect = $('#roomSelect');
            roomSelect.empty().append('<option value="">Select Room</option>');
            if (!buildingId) return;

            $.ajax({
                url: `/api/admin/buildings/${buildingId}/rooms`,
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(function (room) {
                            roomSelect.append(`<option value="${room.id}">${room.room_number}</option>`);
                        });
                    }
                },
                error: function (error) {
                    console.error("Error fetching rooms:", error);
                }
            });
        }

        // Fetch beds
        function fetchBeds() {
            $.ajax({
                url: '/api/admin/beds',
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },

                success: function (response) {
                    const bedList = $('#bedList');
                    bedList.empty();

                    if (response.success && Array.isArray(response.data) && response.data.length > 0) {
                        response.data.forEach(function (bed, index) {
                            bedList.append(`
                                                <tr>
                                                    <td>${index + 1}</td>
                                                    <td>${bed.bed_number}</td>
                                                    <td>${bed.room ? bed.room.room_number : 'N/A'}</td>
                                                    <td>${bed.room ? bed.room.building.name : 'N/A'}</td>
                                                    <td>
                                                        <span class="badge bg-${bed.status === 'available' ? 'success' : 'danger'}">
                                                            ${bed.status.charAt(0).toUpperCase() + bed.status.slice(1)}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning"
                                                            onclick="openEditModal(${bed.id}, '${bed.bed_number}', ${bed.room_id}, '${bed.status}')">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteBed(${bed.id})">Delete</button>
                                                    </td>
                                                </tr>
                                            `);
                        });

                        // Datatable
                        InitializeDatatable();

                    } else {
                        bedList.append('<tr><td colspan="6" class="text-center">No beds found.</td></tr>');
                    }
                },
                error: function () {
                    $('#bedList').html('<tr><td colspan="6" class="text-danger text-center">Failed to load beds.</td></tr>');
                }
            });
        }

        // Create bed
        function createBed() {
            const formData = {
                bed_number: $('#bedNumber').val(),
                room_id: $('#roomSelect').val(),
                status: 'available'
            };

            $.ajax({
                url: '/api/admin/beds/create',
                type: 'POST',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                data: formData,
                success: function (response) {
                    if (response.success) {
                        showAlert("success", response.message || "Bed created successfully!");
                        fetchBeds();
                        $('#createBedForm')[0].reset();
                    } else {
                        showAlert("danger", response.message || "Failed to create bed.");
                    }
                },
                error: function () {
                    showAlert("danger", "An error occurred while creating the bed.");
                }
            });
        }

        // Open edit modal
        function openEditModal(id, bedNumber, roomId, status) {
            $('#editId').val(id);
            $('#editBedNumber').val(bedNumber);
            $('#editRoomId').val(roomId);
            $('#editStatus').val(status);
            $('#editModal').modal('show');
        }

        // Update bed
        function updateBed() {
            const id = $('#editId').val();
            const formData = {
                bed_number: $('#editBedNumber').val(),
                room_id: $('#editRoomId').val(),
                status: $('#editStatus').val()
            };

            $.ajax({
                url: `/api/admin/beds/update/${id}`,
                type: 'PUT',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                data: formData,
                success: function (response) {
                    if (response.success) {
                        showAlert("success", response.message || "Bed updated successfully!");
                        fetchBeds();
                        $('#editModal').modal('hide');
                    } else {
                        showAlert("danger", response.message || "Failed to update bed.");
                    }
                },
                error: function () {
                    showAlert("danger", "An error occurred while updating the bed.");
                }
            });
        }

        // Delete bed
        function deleteBed(id) {
            if (confirm("Are you sure you want to delete this bed?")) {
                $.ajax({
                    url: `/api/admin/beds/${id}`,
                    type: 'DELETE',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    success: function (response) {
                        if (response.success) {
                            showAlert("success", response.message || "Bed deleted successfully!");
                            fetchBeds();
                        } else {
                            showAlert("danger", response.message || "Failed to delete bed.");
                        }
                    },
                    error: function () {
                        showAlert("danger", "An error occurred while deleting the bed.");
                    }
                });
            }
        }

        // Alert utility
        function showAlert(type, message) {
            const alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                                                    ${message}
                                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                </div>`;
            $('.container').first().prepend(alert);
        }
    </script>

@endpush