@extends('admin.layout')

@section('content')


    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview</a></div>
        <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#additem">+ Add
            Item</button>
    </div>

    <!-- Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Items Details</a></div>
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Items</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/4.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Stock</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Using Items</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/Room Management/3.png')}}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Maintaince or Damage</p>
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
                <div class="top-breadcrumbs">
                    <div class="breadcrumbs p-0"><a class="p-0">Items List</a></div>
                </div>

                <div class="overflow-auto">

                    <div id="alert-container"></div>

                    <table class="status-table">
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
        </div>
    </section>


    <!-- Add Item Popup-->
    <div class="modal fade" id="additem" tabindex="-1" aria-labelledby="additemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Items</div>
                    </div>

                    <form id="accessoryForm">

                        <div class="middle">

                            <span class="input-set">
                                <label for="accessory_head_id">Accessory Name</label>
                                <select name="accessory_head_id" id="accessory_head_id" required>
                                    <option value="">Select Accessory</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" placeholder="Enter Price" required step="0.01">
                            </span>

                        </div>

                        <div class="full-width-i">
                            <div class="form-field">
                                <div class="check-flex form-check">
                                    <input type="checkbox" class="form-check-input" name="is_default" id="is_default">
                                    <label class="form-check-label" for="is_default">Is Default?</label>
                                </div>
                            </div>
                        </div>

                        <div id="accessoryFormMessage" class="mt-2"></div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="button" class="blue" id="openAccessoryHeadModal" title="Add Accessory Head"> Add
                                Accessory Head</button>
                            <button type="submit" class="blue"> Add Item</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>


    <!-- Add Accessory Head Popup-->
    <div class="modal fade" id="accessoryHeadModal" tabindex="-1" aria-labelledby="accessoryHeadModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Accessory Head</div>
                    </div>

                    <div class="middle"><span style="display:none">Empty</span></div>

                    <div id="accessoryHeadMessage" class="mt-2"></div>

                    <form id="accessoryHeadForm">

                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="name">Accessory Head Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter Accessory Head Name" required>
                            </span>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="submit" class="blue"> Add</button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>

        let accessoryHeadMap = {};

        const addItemModalEl = document.getElementById('additem');
        const accessoryHeadModalEl = document.getElementById('accessoryHeadModal');

        const addItemModal = bootstrap.Modal.getOrCreateInstance(addItemModalEl);
        const accessoryHeadModal = bootstrap.Modal.getOrCreateInstance(accessoryHeadModalEl);

        document.getElementById('openAccessoryHeadModal').addEventListener('click', function () {
            // Clear message
            const messageDiv = document.getElementById('accessoryHeadMessage');
            messageDiv.textContent = '';
            messageDiv.classList.remove('text-success', 'text-danger');

            // Reset form
            document.getElementById('accessoryHeadForm').reset();

            // Hide first modal
            addItemModal.hide();

            // Wait & open second modal
            setTimeout(() => {
                accessoryHeadModal.show();
            }, 100);
        });

        accessoryHeadModalEl.addEventListener('hidden.bs.modal', function () {
            addItemModal.show();
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
                        "Accessory added successfully!";

                    document.getElementById('accessoryForm').reset();
                    document.getElementById('price').disabled = false;

                    await loadActiveAccessories();

                    // AUTO CLOSE MODAL
                    setTimeout(() => {
                        addItemModal.hide();
                    }, 100);
                }
                else {
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
                // priceField.disabled = true;
                const price = isDefault ? 0 : document.getElementById('price').value;
            } else {
                priceField.disabled = false;
                priceField.value = "";
            }
        });

        // Initial load of accessory heads when the page loads
        loadAccessoryHeads();
    </script>

    <script>
        if (response.ok && data.success === true) {
            messageDiv.classList.add('text-success');
            messageDiv.textContent = data.message || "Item added successfully";

            document.getElementById('accessoryForm').reset();
            document.getElementById('price').disabled = false;

            loadActiveAccessories();

            setTimeout(() => {
                addItemModal.hide();
            }, 1000);
        }

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