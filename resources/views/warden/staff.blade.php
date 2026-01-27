@extends('warden.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Staff Management</h2>

            <a href="/admin/staff/create" class="btn btn-primary p-3">
                <i class="fas fa-plus"></i> Create Staff
            </a>

        </div>

        <div class="container mt-4">

            <!-- Staff List Table -->
            <div class="mb-4"
                style="border: 1px solid #2125294d; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);overflow: auto;">
                <div
                    style="padding: 10px; margin-bottom: 20px; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.2rem;">
                    Staff List
                </div>

                <div id="mainResponseMessage" class="mt-3"></div> {{-- Message container for the page --}}

                <table class="table table-bordered" id="staffList">
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

@endsection

    @push('scripts')
        <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
        @include('backend.components.datatable-lib')

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
                                                    <a class="btn btn-sm btn-warning me-1" href="/admin/staff/edit/${staff.id}">Edit</a>
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
    @endpush