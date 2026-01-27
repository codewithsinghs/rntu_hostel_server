@extends('warden.layout')

@section('content')
<div class="container mt-4">
        <div class="d-flex justify-content-between mt-5 mb-3">
            <h2 class="mb-3">Edit Admin</h2>
        </div>

            <div class="mb-4 cust_box">
                <div class="cust_heading p-3">
                    <h4 class="mb-0">Admin Details Update</h4>
                </div>
                <div>
                    <div id="messageContainer"></div>

                    <form id="updateAdminForm">
                        @csrf {{-- This is for standard form submission security --}}
                        {{-- Add a hidden input to easily access the CSRF token via JavaScript --}}
                        <input type="hidden" id="csrf_token_field" value="{{ csrf_token() }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="">Select Role</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">Update Admin</button>
                        <a href="{{ route('admin.admin_list') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
    fetchRoles();
    document.getElementById("updateAdminForm").addEventListener("submit", updateAdmin);

    function getCsrfToken() {
        // Now getting the CSRF token from the hidden input field we added
        const csrfTokenField = document.getElementById('csrf_token_field');
        if (csrfTokenField) {
            return csrfTokenField.value;
        } else {
            console.error("CRITICAL ERROR: CSRF token hidden input field (id='csrf_token_field') not found. Ensure it's present in the form.");
            showCustomMessageBox("Security error: CSRF token missing. Please refresh the page or contact support.", 'danger');
            return null; // Return null to indicate failure
        }
    }

    function fetchRoles() {
        fetch("{{ url('/api/admin/roles') }}", {
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
                        rolesSelect.innerHTML += `<option value="${role.name}">${role.fullname}</option>`;
                    });
                } else {
                    console.error("API response for roles was not successful or data is missing:", response);
                    showCustomMessageBox(response.message || "Error loading roles.", 'danger');
                }
            })
            .catch(error => {
                console.error("Error loading roles:", error);
                showCustomMessageBox("Failed to load roles. Please try again.", 'danger');
            });
    }
    let id = window.location.pathname.replace(/\/$/, "").split("/").pop();
    $.ajax({
        url: `{{ url('/api/admin/admin') }}/${id}`,
        type: 'GET',
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            'token': localStorage.getItem('token'), // Include token for authentication
            'auth-id': localStorage.getItem('auth-id') // Include auth-id for authorization
        },
        success: function(response) {
            if (response.success) {
                const admin = response.data;
                document.getElementById("name").value = admin.name || '';
                document.getElementById("email").value = admin.email || '';
                document.getElementById("role").value = admin.roles && admin.roles.length > 0 ? admin.roles[0].name : '';
                document.getElementById("status").value = admin.status != null ? String(admin.status) : '';
            } else {
                showCustomMessageBox(response.message || "Failed to load admin details.", 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching admin details:", error);
            showCustomMessageBox("An error occurred while fetching admin details. Please try again.", 'danger');
        }
    });

function updateAdmin(event) {
    event.preventDefault();
    let id = window.location.pathname.replace(/\/$/, "").split("/").pop();

    const csrfToken = getCsrfToken();
    if (!csrfToken) {
        return; // Stop the form submission if token is not available
    }

    let formData = {
        name: document.getElementById("name").value.trim(),
        email: document.getElementById("email").value.trim(),
        role: document.getElementById("role").value,
        status: document.getElementById("status").value
    };

    fetch(`{{ url('/api/admin/admin/update') }}/${id}`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken, 
            "token": localStorage.getItem("token"),
            "auth-id": localStorage.getItem("auth-id")
        },
        body: JSON.stringify(formData)
    })
    .then(async response => {
        const data = await response.json(); // ✅ parse JSON once

        if (!response.ok) {
            throw new Error(data.message || `HTTP error! Status: ${response.status}`);
        }

        // ✅ success case
        if (data.success) {
            showCustomMessageBox(data.message || "Admin Updated successfully!", "success");
            const form = document.getElementById("createAdminForm");
            if (form) form.reset(); // ✅ prevent "null reset" error
        } else {
            showCustomMessageBox(data.message || "Something went wrong.", "danger");
        }
    })
    .catch(error => {
        console.error("Error in updation admin:", error);
        showCustomMessageBox(error.message || "Failed to update admin. Please try again.", "danger");
    });
}

});
</script>
@endsection