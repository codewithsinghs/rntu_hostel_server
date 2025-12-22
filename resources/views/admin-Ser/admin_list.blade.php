@extends('admin.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">


        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2>Admin Management</h2>

            <a href="/admin/admin/create" class="btn btn-primary p-3">
                <i class="fas fa-plus"></i> Create Admin
            </a>
        </div>

        <div class="container mt-4">

            <!-- Admin List Table -->
            <div class="cust_box p-4 mt-4">
                <div class="cust_heading mb-3">
                    Admin List
                </div>

                <div id="mainResponseMessage" class="mt-3"></div> {{-- Message container for the page --}}

                <table class="table table-bordered" id="adminList">
                    <thead class="table-dark">
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center">Loading admin members...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

@endsection

    @push('scripts')
        <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
        @include('backend.components.datatable-lib')

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
                    fetch("{{ url('/api/admin/admin-list') }}", {
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
                            const adminList = document.querySelector("#adminList tbody");
                            // Assuming the staff data is directly under the 'data' key
                            const adminMembers = response.data;
                            const allRoles = ['accountant', 'admission', 'admin', 'superadmin', 'gym_manager',
                                'mess_manager'
                            ]; // Define all possible roles

                            adminList.innerHTML = ""; // Clear existing content

                            if (!response.success || !Array.isArray(adminMembers) || adminMembers.length === 0) {
                                adminList.innerHTML =
                                    `<tr><td colspan="6" class="text-center">No admin found.</td></tr>`;
                                if (!response.success && response.message) {
                                    showCustomMessageBox(response.message, 'info'); // Use info for "no staff found"
                                }
                                return;
                            }

                            adminMembers.forEach((admin, index) => {
                                let roles = admin.roles && admin.roles.length > 0 ? admin.roles.map(role =>
                                    role.name).join(', ') : 'N/A';
                                let row = `
                                    <tr data-id="${admin.id}">
                                        <td>${index + 1}</td>
                                        <td class="name">${admin.name || 'N/A'}</td>
                                        <td class="email">${admin.email || 'N/A'}</td>
                                        <td class="roles">${roles}</td>
                                        <td class="status">${admin.status == '1' ? 'Active' : 'Not Active'}</td>
                                        <td>
                                        <a class="btn btn-sm btn-warning me-1" href="/admin/admin/edit/${admin.id}">Edit</a>
                                        </td>
                                    </tr>
                                `;
                                adminList.innerHTML += row;
                            });

                            if (response.message) {
                                showCustomMessageBox(response.message, 'success');
                            }

                            // Datatable
                            InitializeDatatable();

                        })
                        .catch(error => {
                            console.error('Error fetching admin:', error);
                            document.querySelector("#adminList tbody").innerHTML = `
                                <tr><td colspan="6" class="text-center text-danger">Failed to load admin.</td></tr>
                            `;
                            showCustomMessageBox(error.message || 'Failed to load admin.', 'danger');
                        });
                }

            });
        </script>
    @endpush