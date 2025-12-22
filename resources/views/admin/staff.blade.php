@extends('admin.layout')

@section('content')


    <!-- ================= TOP BAR ================= -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs p-0"><a class="p-0" href="{{ route('admin.create_staff') }}">Staff Management</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#AddStaff">
            + Add Staff
        </button>
    </div>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">Staff List</a></div>
                </div>

                <div class="overflow-auto">

                    <div id="mainResponseMessage" class="mt-3"></div>

                    <table class="status-table" id="staffList">
                        <thead class="table-dark">
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Building Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center">Loading staff members...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= ADD Staff MODAL ================= -->
    <div class="modal fade" id="AddStaff" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="top">
                        <div class="pop-title">Add Staff</div>
                    </div>

                    <div id="messageContainer"></div>

                    <form id="createStaffForm">
                        <input type="hidden" id="csrf_token_field" value="{{ csrf_token() }}">

                        <div class="middle">

                            <span class="input-set">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </span>

                            <span class="input-set">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </span>

                            <span class="input-set">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" required>
                            </span>

                            <span class="input-set">
                                <label for="role">Role</label>
                                <select id="role" name="role" required>
                                    <option value="">Select Role</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="buildings_container">Building</label>
                                <div id="buildings_container" class="checkbox-group"></div>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="blue">Add Admin</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- ================= Edit Staff MODAL ================= -->
    <div class="modal fade" id="EditStaffModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="top">
                        <div class="pop-title">Edit Staff</div>
                    </div>

                    <div id="editMessageContainer"></div>

                    <form id="editStaffForm">
                        <input type="hidden" id="edit_staff_id">
                        <input type="hidden" id="csrf_token_edit" value="{{ csrf_token() }}">

                        <div class="middle">

                            <span class="input-set">
                                <label>Full Name</label>
                                <input type="text" id="edit_name" required>
                            </span>

                            <span class="input-set">
                                <label>Email</label>
                                <input type="email" id="edit_email" required>
                            </span>

                            <span class="input-set">
                                <label>Role</label>
                                <select id="edit_role" required></select>
                            </span>

                            <span class="input-set">
                                <label>Building</label>
                                <div id="edit_buildings_container" class="checkbox-group"></div>
                            </span>

                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="blue">Update Admin</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>



@endsection

@push('scripts')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Function to show a custom message box
        function showCustomMessageBox(message, type = 'info', targetElementId = 'mainResponseMessage') {
            const messageContainer = document.getElementById(targetElementId);
            if (messageContainer) {
                messageContainer.innerHTML = ""; // Clear previous messages
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                messageContainer.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000); // Remove after 3 seconds
            } else {
                console.warn(`Message container #${targetElementId} not found.`);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchStaff();

            function fetchStaff() {
                fetch("{{ url('/api/admin/staff-list') }}", {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'token': localStorage.getItem('token'), // Include token for authentication
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    }
                })
                    .then(response => response.json())
                    .then(response => { // Changed 'data' to 'response' for consistency
                        const staffList = document.querySelector("#staffList tbody");
                        // Assuming the staff data is directly under the 'data' key
                        const staffMembers = response.data;
                        const allRoles = ['warden', 'cleaner', 'caretaker', 'security']; // Define all possible roles

                        staffList.innerHTML = ""; // Clear existing content

                        if (!response.success || !Array.isArray(staffMembers) || staffMembers.length === 0) {
                            staffList.innerHTML = `<tr><td colspan="6" class="text-center">No staff found.</td></tr>`;
                            if (!response.success && response.message) {
                                showCustomMessageBox(response.message, 'info'); // Use info for "no staff found"
                            }
                            return;
                        }

                        staffMembers.forEach((staff, index) => {
                            let roles = staff.roles && staff.roles.length > 0 ? staff.roles.map(role => role.name).join(', ') : 'N/A';
                            let buildingName = staff.buildings && staff.buildings.length > 0 ? staff.buildings.join(',') : 'N/A';
                            console.log(buildingName);
                            let row = `
                                                                                    <tr data-id="${staff.id}">
                                                                                        <td>${index + 1}</td>
                                                                                        <td class="name">${staff.name || 'N/A'}</td>
                                                                                        <td class="email">${staff.email || 'N/A'}</td>
                                                                                        <td class="roles">${roles}</td>
                                                                                        <td class="building-name">${buildingName}</td>
                                                                                        <td>
                                                                                            <button
                                                                                                class="btn btn-sm btn-warning me-1 editStaffBtn"
                                                                                                data-id="${staff.id}"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#EditStaffModal">
                                                                                                Edit
                                                                                            </button>
                                                                                        </td>
                                                                                    </tr>
                                                                                `;
                            staffList.innerHTML += row;
                        });

                        if (response.message) {
                            showCustomMessageBox(response.message, 'success');
                        }

                        // Datatable
                        InitializeDatatable();

                    })
                    .catch(error => {
                        console.error('Error fetching staff:', error);
                        document.querySelector("#staffList tbody").innerHTML = `
                                                                                <tr><td colspan="6" class="text-center text-danger">Failed to load staff.</td></tr>
                                                                            `;
                        showCustomMessageBox(error.message || 'Failed to load staff.', 'danger');
                    });
            }
        });
    </script>

    <!-- Create Staff -->

    <script>
        // Function to show a custom message box
        function showCustomMessageBox(message, type = 'info', targetElementId = 'messageContainer') {
            const messageContainer = document.getElementById(targetElementId);
            if (messageContainer) {
                messageContainer.innerHTML = ""; // Clear previous messages
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type}`;
                alertDiv.textContent = message;
                messageContainer.appendChild(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000); // Remove after 3 seconds
            } else {
                console.warn(`Message container #${targetElementId} not found.`);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            fetchBuildings();
            fetchRoles();
            document.getElementById("createStaffForm").addEventListener("submit", createStaff);

            function getCsrfToken() {
                // Now getting the CSRF token from the hidden input field we added
                const csrfTokenField = document.getElementById('csrf_token_field');
                if (csrfTokenField) {
                    return csrfTokenField.value;
                } else {
                    console.error(
                        "CRITICAL ERROR: CSRF token hidden input field (id='csrf_token_field') not found. Ensure it's present in the form."
                    );
                    showCustomMessageBox(
                        "Security error: CSRF token missing. Please refresh the page or contact support.",
                        'danger');
                    return null; // Return null to indicate failure
                }
            }

            function fetchBuildings() {
                fetch("{{ url('/api/admin/buildings') }}", {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'token': localStorage.getItem('token'), // Include token for authentication
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    }
                })
                    .then(response => response.json())
                    .then(response => {
                        let buildingSelect = document.getElementById("buildings_container");

                        const buildings = response.data;

                        if (response.success && Array.isArray(buildings)) {
                            buildings.forEach(building => {
                                buildingSelect.innerHTML +=
                                    `<label class="checkbox-wrapper"><input type="checkbox" name="buildings[]" value="${building.id}"><span class="checkmark"></span>${building.name}</label>`;
                            });
                        } else {
                            console.error("API response for buildings was not successful or data is missing:",
                                response);
                            showCustomMessageBox(response.message || "Error loading buildings.", 'danger');
                        }
                    })
                    .catch(error => {
                        console.error("Error loading buildings:", error);
                        showCustomMessageBox("Failed to load buildings. Please try again.", 'danger');
                    });
            }

            function fetchRoles() {
                fetch("{{ url('/api/admin/staff-roles') }}", {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'token': localStorage.getItem('token'), // Include token for authentication
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
                    }
                })
                    .then(response => response.json())
                    .then(response => {
                        let rolesSelect = document.getElementById("role");
                        rolesSelect.innerHTML = '<option value="">Select Role</option>';

                        const roles = response.data;
                        if (response.success && Array.isArray(roles)) {
                            roles.forEach(role => {
                                rolesSelect.innerHTML +=
                                    `<option value="${role.name}">${role.fullname}</option>`;
                            });
                        } else {
                            console.error("API response for roles was not successful or data is missing:",
                                response);
                            showCustomMessageBox(response.message || "Error loading roles.", 'danger');
                        }
                    })
                    .catch(error => {
                        console.error("Error loading roles:", error);
                        showCustomMessageBox("Failed to load roles. Please try again.", 'danger');
                    });
            }

            function createStaff(event) {
                event.preventDefault();

                const csrfToken = getCsrfToken();
                if (!csrfToken) {
                    return; // Stop the form submission if token is not available
                }

                let buildings = Array.from(document.querySelectorAll('input[name="buildings[]"]:checked'))
                    .map(cb => Number(cb.value));
                let formData = {
                    name: document.getElementById("name").value.trim(),
                    email: document.getElementById("email").value.trim(),
                    password: document.getElementById("password").value.trim(),
                    buildings: buildings,
                    role: document.getElementById("role").value
                };

                fetch("{{ url('/api/admin/staff/create') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Now correctly uses the retrieved token
                        'token': localStorage.getItem('token'), // Include token for authentication
                        'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization 
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => {
                        if (!response.ok) {
                            // If the response is not OK (e.g., 4xx or 5xx status), parse the error
                            return response.json().then(errorData => {
                                // Throw an error with the message from the API, or a generic one
                                throw new Error(errorData.message ||
                                    `HTTP error! Status: ${response.status}`);
                            });
                        }
                        return response.json(); // If OK, parse the successful response
                    })
                    .then(response => {
                        if (response.success) {
                            showCustomMessageBox(response.message || "Staff created successfully!", 'success');

                            document.getElementById("createStaffForm").reset();

                            // AUTO CLOSE MODAL
                            const addModal = bootstrap.Modal.getInstance(document.getElementById("AddStaff"));
                            addModal.hide();

                            // AUTO RELOAD TABLE AFTER SHORT DELAY
                            setTimeout(() => {
                                fetchStaff();
                            }, 500);
                        }
                    })
                    .catch(error => {
                        console.error("Error creating staff:", error);
                        showCustomMessageBox(error.message || "Failed to create staff. Please try again.",
                            'danger');
                    });
            }
        });
    </script>

    <!-- Edit Script -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /* ================= EDIT BUTTON CLICK ================= */
            document.addEventListener("click", function (e) {
                if (e.target.classList.contains("editStaffBtn")) {
                    let staffId = e.target.getAttribute("data-id");
                    document.getElementById("edit_staff_id").value = staffId;

                    loadEditRoles();
                    loadEditBuildings();
                    loadStaffDetails(staffId);
                }
            });

            /* ================= LOAD STAFF DETAILS ================= */
            function loadStaffDetails(staffId) {
                fetch(`{{ url('/api/admin/staff') }}/${staffId}`, {
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        if (!res.success) return;

                        let staff = res.data;
                        document.getElementById("edit_name").value = staff.name;
                        document.getElementById("edit_email").value = staff.email;
                        document.getElementById("edit_role").value = staff.roles[0].name;

                        setTimeout(() => {
                            staff.building_id.forEach(id => {
                                document.querySelector(`#edit_buildings_container input[value="${id}"]`).checked = true;
                            });
                        }, 300);
                    });
            }

            /* ================= LOAD ROLES ================= */
            function loadEditRoles() {
                fetch("{{ url('/api/admin/staff-roles') }}", {
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        let roleSelect = document.getElementById("edit_role");
                        roleSelect.innerHTML = "";
                        res.data.forEach(role => {
                            roleSelect.innerHTML += `<option value="${role.name}">${role.fullname}</option>`;
                        });
                    });
            }

            /* ================= LOAD BUILDINGS ================= */
            function loadEditBuildings() {
                fetch("{{ url('/api/admin/buildings') }}", {
                    headers: {
                        "Accept": "application/json",
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        let container = document.getElementById("edit_buildings_container");
                        container.innerHTML = "";
                        res.data.forEach(b => {
                            container.innerHTML += `
                                            <label class="checkbox-wrapper">
                                                <input type="checkbox" value="${b.id}">
                                                <span class="checkmark"></span>${b.name}
                                            </label>`;
                        });
                    });
            }

            /* ================= UPDATE STAFF ================= */
            document.getElementById("editStaffForm").addEventListener("submit", function (e) {
                e.preventDefault();

                let staffId = document.getElementById("edit_staff_id").value;
                let buildings = [...document.querySelectorAll('#edit_buildings_container input:checked')]
                    .map(cb => Number(cb.value));

                let payload = {
                    name: document.getElementById("edit_name").value,
                    email: document.getElementById("edit_email").value,
                    role: document.getElementById("edit_role").value,
                    buildings: buildings
                };

                fetch(`{{ url('/api/admin/staff/update') }}/${staffId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.getElementById("csrf_token_edit").value,
                        "token": localStorage.getItem('token'),
                        "auth-id": localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify(payload)
                })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            showCustomMessageBox("Staff updated successfully", "success", "mainResponseMessage");

                            // AUTO CLOSE MODAL
                            const editModal = bootstrap.Modal.getInstance(
                                document.getElementById("EditStaffModal")
                            );
                            editModal.hide();

                            // AUTO RELOAD TABLE
                            setTimeout(() => {
                                fetchStaff();
                            }, 500);
                        }
                    });
            });

        });
    </script>

    <script>
        document.getElementById("EditStaffModal").addEventListener("hidden.bs.modal", function () {
            document.getElementById("editStaffForm").reset();
            document.getElementById("edit_buildings_container").innerHTML = "";
        });

    </script>

@endpush