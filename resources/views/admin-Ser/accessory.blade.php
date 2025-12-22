@extends('admin.layout')

@section('content')

    @include('backend.components.responsive')

    <div class="container mt-4">


        <div class="mt-5 mb-3">
            <h2>Accessory</h2>
        </div>

        <!-- Create Bed Form -->
        <div class="mb-4"
            style="border: 1px solid #2125294d; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);overflow: auto;">
            <div
                style="padding: 10px; margin-bottom: 20px; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.2rem;">
                Add Accessory
            </div>

            <div id="accessoryFormMessage" class="mt-3"></div>

            <form id="accessoryForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="accessory_head_id" class="form-label">Accessory Name</label>
                        <div class="input-group">
                            <select name="accessory_head_id" id="accessory_head_id" class="form-control" required>
                                <option value="">Select Accessory</option>
                            </select>
                            <button type="button" class="btn btn-outline-secondary" id="openAccessoryHeadModal"
                                title="Add Accessory Head">+</button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Enter Price" required
                            step="0.01">
                    </div>

                    <div class="col-md-3">
                        <div>&nbsp;</div>
                        <label class="form-check-label" for="is_default">Is Default?</label>
                        <input type="checkbox" class="form-check-input" name="is_default" id="is_default">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Submit Accessory</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- All Active Accessories List Table -->
        <div class="mb-4"
            style="border: 1px solid #2125294d; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);overflow: auto;">
            <div
                style="padding: 10px; margin-bottom: 20px; width: 100%; background: #0d2858; color: #fff; border-radius: 10px; text-align: center; font-size: 1.2rem;">
                All Active Accessories List
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Accessory Name</th>
                        <th>Price</th>
                        <th>Default</th>
                    </tr>
                </thead>
                <tbody id="activeAccessoriesTableBody">
                    <tr>
                        <td colspan="6" class="text-center">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


    <div class="modal fade" id="accessoryHeadModal" tabindex="-1" aria-labelledby="accessoryHeadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="accessoryHeadForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accessoryHeadModalLabel">Add Accessory Head</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Accessory Head Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Enter Accessory Head Name" required>
                        </div>
                        <div id="accessoryHeadMessage" class="mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Create</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <!-- ✅ Include jQuery + DataTables + Buttons extensions -->
    @include('backend.components.datatable-lib')


    <script>
        let accessoryHeadMap = {};
        const accessoryHeadModal = new bootstrap.Modal(document.getElementById('accessoryHeadModal'));

        document.getElementById('openAccessoryHeadModal').addEventListener('click', () => {
            // Clear previous messages when opening the modal
            const messageDiv = document.getElementById('accessoryHeadMessage');
            messageDiv.textContent = '';
            messageDiv.classList.remove('text-success', 'text-danger');
            document.getElementById('accessoryHeadForm').reset(); // Also reset the form
            accessoryHeadModal.show();
        });

        async function loadAccessoryHeads() {
            try {
                const response = await fetch('/api/admin/accessories-master', {
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                });
                const data = await response.json();
                const accessoryHeads = Array.isArray(data) ? data : data.data || [];

                const accessoryHeadSelect = document.getElementById('accessory_head_id');
                accessoryHeadSelect.innerHTML = '<option value="">Select Accessory</option>';
                accessoryHeadMap = {};

                accessoryHeads.forEach((head) => {
                    const option = document.createElement('option');
                    option.value = head.id;
                    option.textContent = head.name;
                    accessoryHeadSelect.appendChild(option);
                    accessoryHeadMap[head.id] = head.name;
                });

                loadActiveAccessories();
            } catch (error) {
                console.error('Failed to fetch accessory heads:', error);
            }
        }

        async function loadActiveAccessories() {
            try {
                const response = await fetch('/api/admin/accessories/active', {
                    headers: {
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                });
                const data = await response.json();
                const activeAccessories = data.data || [];

                const tbody = document.getElementById('activeAccessoriesTableBody');
                tbody.innerHTML = '';

                if (activeAccessories.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4">No active accessories found.</td></tr>';
                    return;
                }

                activeAccessories.forEach((accessory, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>${accessoryHeadMap[accessory.accessory_head_id] || 'Unknown'}</td>
                                <td>${accessory.price}</td>
                                <td>${accessory.is_default ? 'Yes' : 'No'}</td>
                            `;
                    tbody.appendChild(row);

                });

                // Datatable
                InitializeDatatable();

            } catch (error) {
                console.error('Failed to fetch active accessories:', error);
                document.getElementById('activeAccessoriesTableBody').innerHTML =
                    '<tr><td colspan="4">Error loading data.</td></tr>';
            }
        }

        document.getElementById('accessoryHeadForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const name = document.getElementById('name').value;
            const messageDiv = document.getElementById('accessoryHeadMessage');

            // Clear previous messages/classes
            messageDiv.textContent = '';
            messageDiv.classList.remove('text-success', 'text-danger');

            try {
                const response = await fetch('/api/admin/accessories-master/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify({
                        name
                    })
                });

                const data = await response.json();

                // Check for success based on the API response's 'success' property
                if (response.ok && data.success === true) { // Check both HTTP status and API's success flag
                    messageDiv.classList.add('text-success');
                    messageDiv.textContent = data.message ||
                        "Accessory head added successfully!"; // Use provided message or a default
                    document.getElementById('accessoryHeadForm').reset();
                    // Close modal after a short delay to allow user to read success message
                    setTimeout(() => {
                        accessoryHeadModal.hide();
                    }, 1500); // Hide after 1.5 seconds
                    await loadAccessoryHeads();
                } else {
                    // Handle non-2xx responses or API-specific errors
                    let errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data.message ||
                        'An unknown error occurred.';
                    messageDiv.classList.add('text-danger');
                    messageDiv.innerHTML = errors; // Use innerHTML for potential <br> tags
                }
            } catch (error) {
                messageDiv.classList.add('text-danger');
                messageDiv.textContent = 'Something went wrong. Please try again.';
                console.error(error);
            }
        });

        document.getElementById('accessoryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const accessoryHeadId = document.getElementById('accessory_head_id').value;
            const price = document.getElementById('price').value;
            const isDefault = document.getElementById('is_default').checked;
            const messageDiv = document.getElementById('accessoryFormMessage');

            // Clear previous messages/classes
            messageDiv.textContent = '';
            messageDiv.classList.remove('text-success', 'text-danger');

            try {
                const response = await fetch('/api/admin/accessories/create-or-update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    },
                    body: JSON.stringify({
                        accessory_head_id: accessoryHeadId,
                        price,
                        is_default: isDefault,
                    })
                });

                const data = await response.json();

                // Updated condition to check for both HTTP success and API's 'success' flag
                if (response.ok && data.success === true) {
                    messageDiv.classList.add('text-success');
                    messageDiv.textContent = data.message ||
                        "Accessory added/updated successfully!"; // Use provided message or a default
                    document.getElementById('accessoryForm').reset();
                    document.getElementById('price').disabled = false;
                    loadActiveAccessories();
                } else {
                    const errors = data.errors ? Object.values(data.errors).flat().join('<br>') : data
                        .message || 'An unknown error occurred.';
                    messageDiv.classList.add('text-danger');
                    messageDiv.innerHTML = errors;
                }
            } catch (error) {
                messageDiv.classList.add('text-danger');
                messageDiv.textContent = 'Something went wrong. Please try again.';
                console.error(error);
            }
        });

        // Handle price field based on checkbox
        document.getElementById('is_default').addEventListener('change', function () {
            const priceField = document.getElementById('price');
            if (this.checked) {
                priceField.value = "0.00";
                priceField.disabled = true;
            } else {
                priceField.disabled = false;
                priceField.value = "";
            }
        });

        // Initial load of accessory heads when the page loads
        loadAccessoryHeads();
    </script>

    <style>
        .form-container {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 30px;
            border-radius: 10px;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }

        /* Ensure success messages are always green */
        .text-success {
            color: #28a745 !important;
        }

        /* Ensure error messages are always red */
        .text-danger {
            color: #dc3545 !important;
        }
    </style>
@endpush