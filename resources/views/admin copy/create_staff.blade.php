@extends('admin.layout')

@section('content')
    <style type="text/css">
        /* Layout for group */
        .checkbox-group {
            display: flex;
            /* justify-content: space-between; equal spacing */
            align-items: center;
            gap: 20px;
            /* spacing between checkboxes */
            flex-wrap: wrap;
            /* wrap to next line if screen is small */
        }

        /* Wrapper */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #333;
        }

        /* Hide default */
        .checkbox-wrapper input[type="checkbox"] {
            display: none;
        }

        /* Custom box */
        .checkmark {
            width: 18px;
            height: 18px;
            border: 2px solid #666;
            border-radius: 4px;
            display: inline-block;
            position: relative;
            transition: 0.2s;
        }

        /* On checked */
        .checkbox-wrapper input[type="checkbox"]:checked+.checkmark {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .checkbox-wrapper input[type="checkbox"]:checked+.checkmark::after {
            content: "";
            position: absolute;
            left: 5px;
            top: 1px;
            width: 4px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-3">Create Staff</h2>

                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0">Staff Registration</h4>
                    </div>
                    <div class="card-body">
                        <div id="messageContainer"></div>

                        <form id="createStaffForm">
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
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="buildings_container" class="form-label">Building</label>
                                <div id="buildings_container" class="checkbox-group"></div>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Create Staff</button>
                            <a href="{{ route('admin.staff') }}" class="btn btn-secondary">Cancel</a>
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

        document.addEventListener("DOMContentLoaded", function() {
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
                            document.getElementById("createStaffForm").reset(); // Clear the form
                        } else {
                            showCustomMessageBox(response.message || "Something went wrong.", 'danger');
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
@endsection
