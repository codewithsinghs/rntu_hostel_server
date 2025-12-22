@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a href="">Overview</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#BedAssign">+ Assign Bed</button>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a href="">Bed Assignment Details</a></div>

                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Assign Bed</p>
                            <h3>500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Occupied Beds</p>
                            <h3>400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/2.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Unassign Bed</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Damage Bed</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/4.png') }}" alt="">
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
                <div class="breadcrumbs"><a href="">Beds List</a></div>

                <div class="overflow-auto">
                    <table class="status-table">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Resident Scholar ID</th>
                            <th>Resident Name</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Hostel Name</th>
                            <th>Floor</th>
                            <th>Room Number</th>
                            <th>Bed Type</th>
                            <th>Bed Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>20230145</td>
                            <td>Rajat Pradhan</td>
                            <td>Engineering</td>
                            <td>Computer Science</td>
                            <td>Sunrise Hostel</td>
                            <td>1st Floor</td>
                            <td>101</td>
                            <td>Single</td>
                            <td>B1</td>
                            <td><span style="color: green; font-weight: bold;">Occupied</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View Detail</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>20230132</td>
                            <td>Sangeeta Kumari</td>
                            <td>Science</td>
                            <td>Biotechnology</td>
                            <td>Sunrise Hostel</td>
                            <td>1st Floor</td>
                            <td>102</td>
                            <td>Double</td>
                            <td>B2</td>
                            <td><span style="color: orange; font-weight: bold;">Reserved</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View Detail</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>20230167</td>
                            <td>Priya Sharma</td>
                            <td>Management</td>
                            <td>MBA</td>
                            <td>Lotus Hostel</td>
                            <td>2nd Floor</td>
                            <td>205</td>
                            <td>Single</td>
                            <td>B3</td>
                            <td><span style="color: red; font-weight: bold;">Vacant</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View Detail</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- Bed Assign Popup-->
    <div class="modal fade" id="BedAssign" tabindex="-1" aria-labelledby="BedAssignLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Bed Assign</div>
                    </div>

                    <div id="responseMessage" class="mt-3"></div>

                    <form id="assignBedForm">

                        <div class="middle">

                            <span class="input-set">
                                <label for="resident_id">Select Resident</label>
                                <select class="form-select" id="resident_id" name="resident_id" required>
                                    <option value="">Select Resident</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="faculty">Select Faculty</label>
                                <select class="form-select" id="faculty" name="faculty">
                                    <option value="">Select Faculty</option>
                                    <option value="Faculty 1">Faculty 1</option>
                                    <option value="Faculty 2">Faculty 2</option>
                                    <option value="Faculty 3">Faculty 3</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="department">Select Department</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Select Department</option>
                                    <option value="Department 1">Department 1</option>
                                    <option value="Department 2">Department 2</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="building_id">Select Hostel</label>
                                <select class="form-select" id="building_id" name="building_id" required>
                                    <option value="">Select Hostel</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="floor">Select Floor</label>
                                <select class="form-select" id="floor" name="floor">
                                    <option value="">Select Floor</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="room_id">Select Room</label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="">Select Room</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="bed_id">Select Bed</label>
                                <select class="form-select" id="bed_id" name="bed_id" required>
                                    <option value="">Select Bed</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="status">Select Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Select Status</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Reserved">Reserved</option>
                                    <option value="Vacant">Vacant</option>
                                </select>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue" id="assignBedSubmit"> Assign Bed </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        // selectors
        const $building = $('#building_id');
        const $room = $('#room_id');
        const $bed = $('#bed_id');
        const $resident = $('#resident_id');
        const $response = $('#responseMessage');
        const $submitBtn = $('#assignBedSubmit');
        const bedAssignModalEl = document.getElementById('BedAssign');

        // central headers function
        function authHeaders() {
            return {
                'token': localStorage.getItem('token'),
                'auth-id': localStorage.getItem('auth-id')
            };
        }

        // simple message helper
        function showCustomMessageBox(message, type = 'info') {
            const alertType = (type === 'danger') ? 'danger' : (type === 'success') ? 'success' : 'info';
            $response.html(`<div class="alert alert-${alertType}">${message}</div>`);
            setTimeout(() => $response.empty(), 3500);
        }

        // generic ajax error handler
        function handleAjaxError(xhr, fallback) {
            let errorMessage = fallback;
            if (xhr && xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr && xhr.status === 0) {
                errorMessage = "Could not connect to the server. Please check your network connection.";
            }
            showCustomMessageBox(errorMessage, 'danger');
            console.error(fallback, xhr);
        }

        // load buildings
        function loadBuildings() {
            $.ajax({
                url: '/api/admin/buildings',
                type: 'GET',
                headers: authHeaders(),
                success(response) {
                    $building.empty().append('<option value="">Select Hostel</option>');
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(b => $building.append(`<option value="${b.id}">${b.name}</option>`));
                    } else {
                        console.error('Invalid buildings response', response);
                        showCustomMessageBox('Error loading buildings.', 'danger');
                    }
                },
                error(xhr) { handleAjaxError(xhr, 'Failed to load buildings.'); }
            });
        }

        // load unassigned residents
        function loadResidents() {
            $.ajax({
                url: '/api/admin/residents/unassigned',
                type: 'GET',
                headers: authHeaders(),
                success(response) {
                    $resident.empty().append('<option value="">Select Resident</option>');
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(r => {
                            const name = r.name ?? 'N/A';
                            const scholar = r.scholar_number ?? 'N/A';
                            const gender = r.gender ?? 'N/A';
                            const label = `${name} | Scholar: ${scholar} | Gender: ${gender}`;
                            $resident.append(`<option value="${r.id}">${label}</option>`);
                        });
                    } else {
                        console.error('Invalid residents response', response);
                        showCustomMessageBox('Error loading residents.', 'danger');
                    }
                },
                error(xhr) { handleAjaxError(xhr, 'Failed to load residents.'); }
            });
        }

        // load rooms for building
        function loadRooms(buildingId) {
            $room.empty().append('<option value="">Select Room</option>');
            $bed.empty().append('<option value="">Select Bed</option>');
            if (!buildingId) return;
            $.ajax({
                url: `/api/admin/buildings/${buildingId}/rooms`,
                type: 'GET',
                headers: authHeaders(),
                success(response) {
                    if (response.success && Array.isArray(response.data)) {
                        response.data.forEach(room => $room.append(`<option value="${room.id}">${room.room_number}</option>`));
                    } else {
                        console.error('Invalid rooms response', response);
                        showCustomMessageBox('Error loading rooms for selected building.', 'danger');
                    }
                },
                error(xhr) { handleAjaxError(xhr, 'Failed to load rooms.'); }
            });
        }

        // load available beds for room
        function loadBeds(roomId) {
            $bed.empty().append('<option value="">Select Bed</option>');
            if (!roomId) return;
            $.ajax({
                url: `/api/admin/rooms/${roomId}/available-beds`,
                type: 'GET',
                headers: authHeaders(),
                success(response) {
                    if (response.success && Array.isArray(response.data)) {
                        if (response.data.length === 0) {
                            $bed.append('<option disabled>No available beds</option>');
                        } else {
                            response.data.forEach(bd => $bed.append(`<option value="${bd.id}">Bed Number ${bd.bed_number}</option>`));
                        }
                    } else {
                        console.error('Invalid beds response', response);
                        showCustomMessageBox('Error loading available beds for selected room.', 'danger');
                    }
                },
                error(xhr) { handleAjaxError(xhr, 'Failed to load available beds.'); }
            });
        }

        // refresh unassigned residents (used after assign)
        function refreshResidents() {
            loadResidents();
        }

        // event bindings
        $building.on('change', function () {
            const buildingId = $(this).val();
            loadRooms(buildingId);
        });

        $room.on('change', function () {
            const roomId = $(this).val();
            loadBeds(roomId);
        });

        // assign bed form submit
        $('#assignBedForm').on('submit', function (e) {
            e.preventDefault();

            const formData = {
                resident_id: $resident.val(),
                bed_id: $bed.val(),
                // include other fields if required by API: building_id, room_id, status, etc.
                building_id: $building.val(),
                room_id: $room.val(),
                status: $('#status').val()
            };

            // basic validation
            if (!formData.resident_id || !formData.bed_id) {
                showCustomMessageBox('Please select resident and bed.', 'danger');
                return;
            }

            $submitBtn.prop('disabled', true).text('Assigning...');

            $.ajax({
                url: "{{ url('/api/admin/assign-bed') }}",
                type: 'POST',
                data: formData,
                headers: authHeaders(),
                success(response) {
                    showCustomMessageBox(response.message || 'Bed assigned successfully', 'success');
                    // reset form
                    $('#assignBedForm')[0].reset();
                    $room.html('<option value="">Select Room</option>');
                    $bed.html('<option value="">Select Bed</option>');
                    refreshResidents();

                    // close modal (Bootstrap 5)
                    const modalInstance = bootstrap.Modal.getInstance(bedAssignModalEl) || bootstrap.Modal.getOrCreateInstance(bedAssignModalEl);
                    modalInstance.hide();
                },
                error(xhr) {
                    handleAjaxError(xhr, 'Failed to assign bed.');
                },
                complete() {
                    $submitBtn.prop('disabled', false).text('Assign Bed');
                }
            });
        });

        // initial load
        loadBuildings();
        loadResidents();
    });
</script>
@endpush
