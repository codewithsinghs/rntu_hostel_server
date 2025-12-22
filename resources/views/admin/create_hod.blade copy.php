@extends('admin.layout')

@section('content')



    <div class="container mt-4">

        <div class="mt-5 mb-3">
            <h2>Create HOD</h2>
        </div>

        <div class="cust_box">
            <div class="cust_heading">
                HOD Registration
            </div>

            <div id="messageContainer"></div>

            <form id="createHODForm">
                @csrf

                <!-- Hidden -->
                <input type="hidden" id="csrf_token_field" value="{{ csrf_token() }}">

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <option value="">Select Department</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Create Admin</button>
                    </div>

                    <div class="col-md-6 mb-2 d-flex align-items-end">
                        <a href="{{ route('admin.hods') }}" class="btn btn-secondary w-100">Cancel</a>
                    </div>

                </div>
            </form>
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
            fetchHODs();
            document.getElementById("createHODForm").addEventListener("submit", createHod);

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

            function fetchHODs() {
                fetch("{{ url('/api/admin/departments') }}", {
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
                        let departmentSelect = document.getElementById("department_id");
                        departmentSelect.innerHTML = '<option value="">Select Department</option>';

                        const departments = response.data;

                        if (response.success && Array.isArray(departments)) {
                            departments.forEach(department => {
                                departmentSelect.innerHTML += `<option value="${department.id}">${department.name}</option>`;
                            });
                        } else {
                            console.error("API response for department was not successful or data is missing:", response);
                            showCustomMessageBox(response.message || "Error loading departments.", 'danger');
                        }
                    })
                    .catch(error => {
                        console.error("Error loading departments:", error);
                        showCustomMessageBox("Failed to load departments. Please try again.", 'danger');
                    });
            }

            function createHod(event) {
                event.preventDefault();

                const csrfToken = getCsrfToken();
                if (!csrfToken) {
                    return; // Stop the form submission if token is not available
                }

                let formData = {
                    name: document.getElementById("name").value.trim(),
                    email: document.getElementById("email").value.trim(),
                    password: document.getElementById("password").value.trim(),
                    department_id: document.getElementById("department_id").value,
                };

                fetch("{{ url('/api/admin/hods/create') }}", {
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
                                throw new Error(errorData.message || `HTTP error! Status: ${response.status}`);
                            });
                        }
                        return response.json(); // If OK, parse the successful response
                    })
                    .then(response => {
                        if (response.success) {
                            showCustomMessageBox(response.message || "HOD created successfully!", 'success');
                            document.getElementById("createHODForm").reset(); // Clear the form
                            // Reset the form only if it exists                
                        } else {
                            showCustomMessageBox(response.message || "Something went wrong.", 'danger');
                        }
                    })
                    .catch(error => {
                        console.error("Error creating HODs:", error);
                        showCustomMessageBox(error.message || "Failed to create HOD. Please try again.", 'danger');
                    });
            }
        });
    </script>
@endsection