@extends('admin.layout')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-3">HODs Management</h2>

            <div id="mainResponseMessage" class="mt-3"></div> {{-- Message container for the page --}}

            <div class="d-flex justify-content-end mb-3">
                <a href="/admin/hods/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create HOD
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">HODs List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="hodsList">
                        <thead class="table-dark">
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="6" class="text-center">Loading HODs...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
        fetch("{{ url('/api/admin/hods-list') }}", {
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
                const hodsList = document.querySelector("#hodsList tbody");
                // Assuming the hods data is directly under the 'data' key
                const hodsMembers = response.data;
                
                hodsList.innerHTML = ""; // Clear existing content

                if (!response.success || !Array.isArray(hodsMembers) || hodsMembers.length === 0) {
                    hodsList.innerHTML = `<tr><td colspan="6" class="text-center">No HODs found.</td></tr>`;
                    if (!response.success && response.message) {
                        showCustomMessageBox(response.message, 'info'); // Use info for "no staff found"
                    }
                    return;
                }

                hodsMembers.forEach((hod, index) => {
                    let row = `
                        <tr data-id="${hod.id}">
                            <td>${index + 1}</td>
                            <td class="name">${hod.name || 'N/A'}</td>
                            <td class="email">${hod.email || 'N/A'}</td>
                            <td class="department">${hod.department.name}</td>
                            <td class="status">${hod.status==1 ? 'Active' : 'Inactive'}</td>
                            <td>
                                <a class="btn btn-sm btn-warning me-1" href="/admin/hods/edit/${hod.id}">Edit</a>
                            </td>
                        </tr>
                    `;
                    hodsList.innerHTML += row;
                });

                if (response.message) {
                    showCustomMessageBox(response.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error fetching hods:', error);
                document.querySelector("#hodsList tbody").innerHTML = `
                    <tr><td colspan="6" class="text-center text-danger">Failed to load hods.</td></tr>
                `;
                showCustomMessageBox(error.message || 'Failed to load staff.', 'danger');
            });
    }

});
</script>
@endsection
