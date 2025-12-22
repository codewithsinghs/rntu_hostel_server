@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <!-- breadcrumbs -->
        <div class="breadcrumbs"><a href="">Overview</a></div>
        <!-- Popup Btn -->
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddRoom">+ Add Room</button>
    </div>

    <!-- Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Room Details</a></div>

                <!-- Room Details -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Room</p>
                            <h3>500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Occupied Room</p>
                            <h3>400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Available Room</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Maintaince Room</p>
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
                <div class="breadcrumbs"><a href="">Room List</a></div>

                <div id="mainResponseMessage" class="mt-3"></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%" id="roomList">
                        <thead>
                            <tr>
                                <th>Serial No.</th>
                                <th>Room Number</th>
                                <th>Building Name</th>
                                <th>Floor No</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="room-change-list">
                            <tr>
                                <td colspan="7" class="text-center">No rooms found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this room? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Room Popup-->

    <div class="modal fade" id="AddRoom" tabindex="-1" aria-labelledby="AddRoomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Room</div>
                    </div>

                    <form id="createRoomForm">

                        <div class="middle">

                            <div id="responseMessage" class="alert" style="display: none;"></div>

                            {{-- Building --}}
                            <span class="input-set">
                                <label for="building_id">Select Building</label>
                                <select class="form-select" id="building_id" name="building_id" required>
                                    <option value="">Select Building</option>
                                </select>
                            </span>

                            {{-- Floor --}}
                            <span class="input-set">
                                <label for="floorSelect">Select Floor</label>
                                <select class="form-select" id="floorSelect" name="floor_no" required>
                                    <option value="">Select Floor</option>
                                </select>
                            </span>

                            {{-- Room number --}}
                            <span class="input-set">
                                <label for="room_number">Create Room Number</label>
                                <input type="text" id="room_number" name="room_number"
                                    placeholder="Enter Create Room Number" required>
                            </span>

                            {{-- Room Type (optional to send in API) --}}
                            <span class="input-set">
                                <label for="RoomType">Room Type</label>
                                <select class="form-select" id="RoomType" name="room_type">
                                    <option value="">Select Room Type</option>
                                    <option value="Single Bed">Single Bed</option>
                                    <option value="Double Bed">Double Bed</option>
                                    <option value="Triple Bed">Triple Bed</option>
                                </select>
                            </span>

                            {{-- Room Facility (optional to send in API) --}}
                            <span class="input-set">
                                <label for="RoomFacility">Room Facility</label>
                                <select class="form-select" id="RoomFacility" name="room_facility">
                                    <option value="">Select Facility</option>
                                    <option value="AC">AC</option>
                                    <option value="Non AC">Non AC</option>
                                </select>
                            </span>

                            {{-- Status --}}
                            <span class="input-set">
                                <label for="status">Room Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="occupied">Occupied</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal"> Cancel </button>
                            <button type="submit" class="blue"> Add Room </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {

            // Initial load of rooms
            fetchRooms();

            // Fetch buildings to populate building dropdown
            fetchBuildings();

            // Create room form submit
            $("#createRoomForm").on("submit", function (event) {
                event.preventDefault();
                createRoom();
            });

            // On building change, update floor dropdown
            $("#building_id").on("change", function () {
                const selectedBuildingId = $(this).val();
                const selectedBuildingFloors = getBuildingFloorsById(selectedBuildingId);
                updateFloorDropdown(selectedBuildingFloors);
            });
        });

        // ---------- ROOM LIST FUNCTIONS ----------

        function fetchRooms() {

            // Destroy existing DataTable if initialized
            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#roomList')) {
                $('#roomList').DataTable().destroy();
            }

            $.ajax({
                url: '/api/admin/rooms',
                type: 'GET',
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    if (response.success && Array.isArray(response.data)) {
                        let rows = '';
                        response.data.forEach(function (room, index) {
                            rows += `
                                    <tr data-id="${room.id}">
                                        <td>${index + 1}</td>
                                        <td class="room_number">${room.room_number}</td>
                                        <td class="building_name">${room.building_name || 'Unknown'}</td>
                                        <td class="floor_no">${room.floor_no || 'Unknown'}</td>
                                        <td class="status">
                                            <span class="badge ${room.status === 'available'
                                    ? 'bg-success'
                                    : (room.status === 'maintenance' ? 'bg-warning' : 'bg-danger')
                                }">
                                                ${room.status ? room.status.charAt(0).toUpperCase() + room.status.slice(1) : 'Unknown'}
                                            </span>
                                        </td>
                                        <td class="actions">
                                            <a class="btn btn-sm btn-warning me-1" href="/admin/rooms/edit/${room.id}">Edit</a>
                                            <button class="btn btn-sm btn-danger" onclick="deleteRoom(${room.id})">Delete</button>
                                        </td>
                                    </tr>
                                `;
                        });

                        $('#roomList tbody').html(rows);

                        // Reinitialize DataTable if plugin loaded
                        if ($.fn.DataTable) {
                            $('#roomList').DataTable();
                        }

                    } else {
                        $('#roomList tbody').html('<tr><td colspan="7" class="text-center">No rooms found.</td></tr>');
                    }
                },
                error: function (xhr) {
                    console.error(xhr);
                    $('#roomList tbody').html('<tr><td colspan="7" class="text-center">Error loading data</td></tr>');
                }
            });
        }

        function deleteRoom(id) {
            // Show the confirmation modal
            $('#deleteConfirmationModal').modal('show');

            // Set up the confirm delete button
            $('#confirmDeleteBtn').off('click').on('click', function () {
                $.ajax({
                    url: `/api/admin/rooms/${id}`,
                    type: 'DELETE',
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#deleteConfirmationModal').modal('hide');
                            showAlert('success', 'Room deleted successfully.');
                            fetchRooms(); // Refresh the room list
                        } else {
                            showAlert('danger', response.message || 'Failed to delete room.');
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = "Failed to delete room.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('danger', errorMessage);
                    }
                });
            });
        }

        function showAlert(type, message) {
            const box = $('#mainResponseMessage');
            box.removeClass().addClass(`alert alert-${type}`).html(message).show();
            setTimeout(function () {
                box.fadeOut();
            }, 4000);
        }

        // ---------- BUILDINGS & FLOORS ----------

        function fetchBuildings() {
            $.ajax({
                url: "/api/admin/buildings",
                type: "GET",
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                success: function (response) {
                    const buildingSelect = $("#building_id");
                    buildingSelect.empty();
                    buildingSelect.append('<option value="">Select Building</option>');
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(function (building) {
                            const option = $("<option></option>")
                                .val(building.id)
                                .text(building.name)
                                .data("floors", building.floors); // Store number of floors
                            buildingSelect.append(option);
                        });
                    } else {
                        showMessage("danger", "Invalid response from server for buildings. Expected an array in 'data' field.");
                        console.error("Invalid building data structure:", response);
                    }
                },
                error: function (xhr) {
                    let errorMessage = "Failed to load buildings.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMessage = "Could not connect to the server. Please check your network connection.";
                    }
                    showMessage("danger", `Error: ${errorMessage}`);
                    console.error("Error fetching buildings:", xhr);
                }
            });
        }

        function getBuildingFloorsById(buildingId) {
            const buildingSelect = $("#building_id");
            const selectedBuilding = buildingSelect.find(`option[value="${buildingId}"]`);
            return selectedBuilding.length ? parseInt(selectedBuilding.data("floors")) : 0;
        }

        function updateFloorDropdown(floors) {
            const floorSelect = $("#floorSelect");
            floorSelect.empty();
            floorSelect.append('<option value="">Select Floor</option>');
            if (floors > 0) {
                for (let i = 1; i <= floors; i++) {
                    floorSelect.append(`<option value="${i}">Floor ${i}</option>`);
                }
            } else {
                floorSelect.append('<option value="">No floors available</option>');
            }
        }

        // ---------- CREATE ROOM ----------

        function createRoom() {
            const roomData = {
                room_number: $("#room_number").val(),
                building_id: $("#building_id").val(),
                floor_no: $("#floorSelect").val(),
                status: $("#status").val()
                // If your API supports room_type & room_facility, you can also send:
                // room_type: $("#RoomType").val(),
                // room_facility: $("#RoomFacility").val(),
            };

            $.ajax({
                url: "/api/admin/rooms/create",
                type: "POST",
                headers: {
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id'),
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: roomData, // sending as form-encoded
                success: function (response) {
                    if (response.success) {
                        showMessage("success", response.message || "Room created successfully!");

                        // Reset form
                        $("#createRoomForm")[0].reset();
                        $("#floorSelect").empty().append('<option value="">Select Floor</option>');

                        // Refresh room list
                        fetchRooms();

                        // Close modal after short delay
                        setTimeout(function () {
                            $('#AddRoom').modal('hide');
                        }, 800);
                    } else {
                        showMessage("danger", response.message || "Error creating room. Please try again.");
                    }
                },
                error: function (xhr) {
                    let errorMessage = "An unexpected error occurred.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    showMessage("danger", `Error: ${errorMessage}`);
                    console.error("Error creating room:", xhr);
                }
            });
        }

        // ---------- MODAL MESSAGE (ADD ROOM) ----------

        function showMessage(type, message) {
            const responseMessage = $("#responseMessage");
            responseMessage.removeClass("alert-success alert-danger").addClass(`alert alert-${type}`);
            responseMessage.html(message).show();
            setTimeout(() => {
                responseMessage.fadeOut();
            }, 4000);
        }
    </script>
@endpush