@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">
        <div class="mt-5 mb-3">
            <h2>Manage Beds</h2>
        </div>

        <!-- Create Bed Form -->
        <!-- <div class="mb-4 cust_box">
            <div class="cust_heading">
                Create New Bed
            </div>

            <form id="createBedForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="buildingSelect" class="form-label">Select Building</label>
                        <select class="form-select" id="buildingSelect" name="building_id" required>
                            <option value="">-- Select Building --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="roomSelect" class="form-label">Select Room</label>
                        <select class="form-select" id="roomSelect" name="room_id" required>
                            <option value="">-- Select Room --</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bedNumber" class="form-label">Bed Number</label>
                        <input type="text" class="form-control" id="bedNumber" name="bed_number" required>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Create Bed</button>
                    </div>
                </div>
            </form>
        </div> -->

        <!-- Beds List Table -->
        <div class="mb-4"
            style="border: 1px solid #2125294d; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);overflow: auto;">
            <div
                style="padding: 10px; margin-bottom: 20px; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.2rem;">
                Beds List
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S. No</th>
                        <th>Bed Number</th>
                        <th>Room Number</th>
                        <th>Building Name</th>
                        <th>Status</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody id="bedList">
                    <tr>
                        <td colspan="6" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Edit Modal -->
    <!-- <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Bed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="editId">

                        <div class="mb-3">
                            <label for="editBedNumber" class="form-label">Bed Number</label>
                            <input type="text" class="form-control" id="editBedNumber" required>
                        </div>

                        <div class="mb-3">
                            <label for="editRoomId" class="form-label">Room ID</label>
                            <input type="number" class="form-control" id="editRoomId" required>
                        </div>

                        <div class="mb-3">
                            <label for="editStatus" class="form-label">Status</label>
                            <select id="editStatus" class="form-select">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div> -->
@endsection

@push('scripts')
    <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
    @include('backend.components.datatable-lib')

    <script>
        $(document).ready(function () {
            fetchBuildings();
            fetchBeds();

            // $("#buildingSelect").on("change", function () {
            //     const buildingId = $(this).val();
            //     fetchRoomsByBuilding(buildingId);
            // });

            // $("#createBedForm").on("submit", function (event) {
            //     event.preventDefault();
            //     createBed();
            // });

            // $("#editForm").on("submit", function (event) {
            //     event.preventDefault();
            //     updateBed();
            // });
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
                    select.empty().append('<option value="">-- Select Building --</option>');
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
            roomSelect.empty().append('<option value="">-- Select Room --</option>');
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
                                </tr>
                            `);
                        });
                        // <td>
                        //     <button class="btn btn-sm btn-warning"
                        //         onclick="openEditModal(${bed.id}, '${bed.bed_number}', ${bed.room_id}, '${bed.status}')">
                        //         Edit
                        //     </button>
                        //     <button class="btn btn-sm btn-danger" onclick="deleteBed(${bed.id})">Delete</button>
                        // </td>

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