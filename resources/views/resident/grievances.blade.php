@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Grievances / Complaints Overview</a></div>

                <!-- Grievances / Complaints Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Complaints Filed</p>
                            <h3 id="total-complaints">0</h3>

                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Complaints</p>
                            <h3 id="pending-complaints">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Resolved Complaints</p>
                            <h3 id="resolved-complaints">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/approved.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Rejected Complaints</p>
                            <h3 id="rejected-complaints">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/min.png') }}" alt="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- Raise Complaint -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div id="alert" class="alert d-none"></div>
                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#grievanceCollapse" aria-expanded="false"
                    aria-controls="grievanceCollapse">

                    <span class="breadcrumbs">Complaint / Grievance</span>
                    <span class="btn btn-primary">Raise Complaint / Grievance</span>

                </button>


                <div class="collapse" id="grievanceCollapse">

                    <!-- Form -->
                    <form id="grievanceForm">

                        @csrf

                        <!-- Hidden Resident ID input -->
                        <input type="hidden" id="resident_id" name="resident_id">

                        <!-- Hidden Created By input -->
                        <input type="hidden" id="created_by" name="created_by">

                        <!-- Hidden Token ID input -->
                        <input type="hidden" id="token_id" name="token_id">

                        <div class="inpit-boxxx">

                            <span class="input-set">
                                <label for="type_of_complaint">Select Complaint Category</label>
                                <select class="form-select" id="type_of_complaint" name="type_of_complaint"
                                    aria-label="Default select example">
                                    <option value="" disabled selected>Select Category</option>
                                    <option value="room">Room Related</option>
                                    <option value="mess">Mess / Food Quality</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="security">Security</option>
                                    <option value="Behavior">Behavior</option>
                                    <option value="Cleanliness">Cleanliness</option>
                                    <option value="Noise">Noise</option>
                                    <option value="others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="other_complaint">Specify (if Other Category) :</label>
                                <textarea id="other_complaint" name="other_complaint" placeholder="Purpose "></textarea>
                            </span>

                            <span class="input-set">
                                <label for="FromDate">From Date</label>
                                <input type="date" id="FromDate" name="FromDate" placeholder="Select From Date">
                            </span>

                            <span class="input-set">
                                <label for="Attachment">Attachment (Optional)</label>
                                <input type="file" id="Attachment" name="Attachment">
                            </span>



                        </div>



                        <div class="reason">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Description " required></textarea>
                        </div>


                        <div class="reason">
                            <label for="photo" class="form-label">Upload Supporting Photo/Document (Optional):</label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                        </div>

                        <button type="submit" class="submitted">Submit Request</button>


                    </form>
                    <!-- Form End -->

                </div>
            </div>
    </section>

    <!-- Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Complaints list</a></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th>Sr No</th>
                                <th>Complaint ID</th>
                                <th>Complaint Type</th>
                                <!-- <th>From Date</th> -->
                                <th>Description</th>
                                <th>Date Filed</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="grievanceList">

                            <tr>
                                <td colspan="4" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- From Grievance Status Page -->
    <div id="viewGrievanceModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                                background: rgba(0, 0, 0, 0.5); z-index: 999; align-items: center; justify-content: center;">
        <div
            style="background: #fff; padding: 20px; border-radius: 10px; width: 400px; max-height: 600px;
                                                    display: flex; flex-direction: column; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <h5 class="text-center mb-3">Grievance Chat</h5>

            <div id="chatContainer"
                style="flex-grow: 1; overflow-y: auto; padding: 10px;
                                                        border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; max-height: 350px;">
            </div>

            <form id="chatForm" style="display: flex;">
                <input type="text" id="chatMessage" class="form-control" placeholder="Type your message..."
                    style="flex-grow: 1; margin-right: 5px;">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>

            <button type="button" class="btn btn-secondary mt-2" id="closeViewModalBtn">Close</button>
        </div>
    </div>

    <div id="customModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                                background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: #fff; padding: 20px; border-radius: 10px; width: 300px; text-align: center;
                                                    box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <p id="modalMessage" style="margin-bottom: 20px;"></p>
            <button id="modalConfirmBtn" class="btn btn-primary" style="display: none; margin-right: 10px;">Yes</button>
            <button id="modalCloseBtn" class="btn btn-secondary">OK</button>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            function generateTokenId() {
                const randomNum = Math.floor(Math.random() * 1000000);
                return 'grievance-' + randomNum;
            }

            $('#type_of_complaint').change(function() {
                if ($(this).val() === 'Other') {
                    $('#other_complaint_group').show();
                } else {
                    $('#other_complaint_group').hide();
                    $('#other_complaint').val('');
                }
            });

            $('#grievanceForm').submit(function(event) {
                event.preventDefault();

                $('#token_id').val(generateTokenId());

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ url('api/resident/grievances/submit') }}",
                    type: "POST",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#alert').removeClass('d-none alert-danger').addClass('alert-success')
                            .text('Grievance submitted successfully!').show();
                        $('#grievanceForm')[0].reset();
                        $('#other_complaint_group').hide();
                    },
                    error: function(xhr) {
                        let errorMessage = xhr.responseJSON?.message ||
                            'Error submitting grievance. Please try again.';
                        $('#alert').removeClass('d-none alert-success').addClass('alert-danger')
                            .text(errorMessage).show();
                    }
                });
            });
        });
    </script>

    <!-- From Grievance Status Page -->
    <script>
        // Get current user details from Laravel Blade
        const currentUserRole = 'resident';
        let currentGrievanceId = null;
        let currentGrievanceStatus = '';
        let currentResidentId = null;

        // --- Custom Modal Functions ---
        const customModal = document.getElementById('customModal');
        const modalMessage = document.getElementById('modalMessage');
        const modalConfirmBtn = document.getElementById('modalConfirmBtn');
        const modalCloseBtn = document.getElementById('modalCloseBtn');

        let resolveModalPromise; // To handle confirmation callback

        /**
         * Displays a custom alert message.
         * @param {string} message The message to display.
         */
        function showAlert(message) {
            modalMessage.textContent = message;
            modalConfirmBtn.style.display = 'none'; // Hide confirm button for alerts
            modalCloseBtn.textContent = 'OK'; // Set close button text to OK
            customModal.style.display = 'flex';
            // Set up listener for closing the alert
            modalCloseBtn.onclick = () => {
                customModal.style.display = 'none';
            };
        }

        /**
         * Displays a custom confirmation dialog.
         * @param {string} message The confirmation message.
         * @returns {Promise<boolean>} A promise that resolves to true if confirmed, false otherwise.
         */
        function showConfirm(message) {
            return new Promise((resolve) => {
                resolveModalPromise = resolve; // Store resolve function for later use
                modalMessage.textContent = message;
                modalConfirmBtn.style.display = 'inline-block'; // Show confirm button for confirmations
                modalCloseBtn.textContent = 'Cancel'; // Set close button text to Cancel
                customModal.style.display = 'flex';

                // Set up listeners for confirmation
                modalConfirmBtn.onclick = () => {
                    customModal.style.display = 'none';
                    resolveModalPromise(true);
                };
                modalCloseBtn.onclick = () => {
                    customModal.style.display = 'none';
                    resolveModalPromise(false);
                };
            });
        }
        // --- End Custom Modal Functions ---


        /**
         * Fetches and displays the list of grievances for the current resident.
         */
        function fetchGrievances() {
            fetch(`/api/resident/grievances`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {


                    // ✅ Update Summary Cards
                    if (data.summary) {
                        const summary = data.summary;
                        document.getElementById("total-complaints").innerText = summary
                            .total_complaints;
                        document.getElementById("resolved-complaints").innerText = summary
                            .resolved_complaints;
                        document.getElementById("pending-complaints").innerText = summary
                            .pending_complaints;
                        document.getElementById("rejected-complaints").innerText = summary
                            .rejected_complaints;
                    }

                    const grievanceList = document.getElementById("grievanceList");
                    grievanceList.innerHTML = ""; // Clear existing list

                    // Check if data.data exists and has grievances
                    if (!data.data || data.data.length === 0) {
                        grievanceList.innerHTML =
                            `<tr><td colspan="4" class="text-center">No grievances found.</td></tr>`;
                        return;
                    }

                    // Iterate over the grievances and append to the table
                    data.data.forEach((grievance, index) => {
                        const status = grievance.status.toLowerCase();
                        currentResidentId = grievance
                            .resident_id; // Store current resident ID for chat responses
                        grievanceList.innerHTML += `
                                                                <tr>
                                                                    <td>${index + 1}</td>
                                                                      <td>${grievance.token_id}</td>
                                                                    <td>${grievance.type_of_complaint}</td>
                                                  
                                                                    <td>${grievance.description}</td>
                                                                     <td>${grievance.date}</td>
                                                                    <td>${grievance.status}</td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-info" onclick="viewGrievance(${grievance.id})">View</button>
                                                                        ${status !== 'closed' ? `<button class="btn btn-sm btn-danger" onclick="closeGrievance(${grievance.id})">Close</button>` : ''}
                                                                    </td>
                                                                </tr>
                                                            `;
                    });
                })
                .catch(error => {
                    console.error("Error fetching grievances:", error);
                    document.getElementById("grievanceList").innerHTML =
                        `<tr><td colspan="4" class="text-danger text-center">Failed to load grievances.</td></tr>`;
                });
        }

        /**
         * Displays the chat modal for a specific grievance and fetches its details.
         * @param {number} grievanceId The ID of the grievance to view.
         */
        function viewGrievance(grievanceId) {
            currentGrievanceId = grievanceId;

            fetch(`/api/resident/grievances/${grievanceId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    const grievance = data.data; // Access the grievance object directly from data.data
                    if (!grievance) {
                        showAlert("Grievance details not found.");
                        return;
                    }
                    currentGrievanceStatus = grievance.status;

                    const chatContainer = document.getElementById("chatContainer");
                    chatContainer.innerHTML = ""; // Clear existing chat messages

                    // Display initial grievance description as the first message
                    const initialMessageDiv = document.createElement('div');
                    initialMessageDiv.style.textAlign = 'right';
                    initialMessageDiv.style.marginBottom = '10px';
                    initialMessageDiv.innerHTML = `
                                                            <div style="display: inline-block; background: #e9ecef; padding: 10px; border-radius: 10px; max-width: 75%; word-wrap: break-word;">
                                                                <small><strong>Grievance Submitted</strong></small><br>
                                                                ${grievance.description}
                                                            </div>
                                                        `;
                    chatContainer.appendChild(initialMessageDiv);


                    // Display responses
                    grievance.responses.forEach(response => {
                        // Determine if the response is from the current resident or an admin
                        // Assuming 'responded_by' for residents refers to 'resident_id' and for admins refers to 'user_id'
                        // This logic might need refinement based on how your API distinguishes between resident and admin responses.
                        const isFromCurrentResident = response.responded_by == localStorage.getItem('auth-id');
                        const sender = isFromCurrentResident ? 'You' : 'Admin'; // Simplified for demonstration
                        console.log(isFromCurrentResident);

                        const align = sender === 'You' ? 'right' : 'left';
                        const color = sender === 'You' ? '#d4edda' :
                            '#f8d7da'; // Green for 'You', Red for 'Admin'
                        const maxWidth = '75%';

                        const messageDiv = document.createElement('div');
                        messageDiv.style.textAlign = align;
                        messageDiv.style.marginBottom = '10px';

                        messageDiv.innerHTML = `
                                                                <div style="display: inline-block; background: ${color}; padding: 10px; border-radius: 10px; max-width: ${maxWidth}; word-wrap: break-word;">
                                                                    <small><strong>${sender}</strong></small><br>
                                                                    ${response.description}
                                                                </div>
                                                            `;
                        chatContainer.appendChild(messageDiv);
                    });

                    // Disable chat input if grievance is closed
                    const chatForm = document.getElementById("chatForm");
                    if (grievance.status.toLowerCase() === "closed") {
                        chatForm.style.display = "none";
                    } else {
                        chatForm.style.display = "flex";
                    }

                    chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to bottom of chat
                    document.getElementById("viewGrievanceModal").style.display = "flex"; // Show the modal
                })
                .catch(error => {
                    console.error("Error fetching grievance details:", error);
                    showAlert("Failed to fetch grievance details.");
                });
        }

        /**
         * Closes a specific grievance after user confirmation.
         * @param {number} grievanceId The ID of the grievance to close.
         */
        async function closeGrievance(grievanceId) {
            const confirmed = await showConfirm("Are you sure you want to close this grievance?");
            if (!confirmed) return;

            fetch(`/api/resident/grievances/close/${grievanceId}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(response => {
                    if (response.ok) {
                        fetchGrievances(); // Reload the list to reflect the status change
                    } else {
                        showAlert("Failed to close grievance.");
                    }
                })
                .catch(error => {
                    console.error("Error closing grievance:", error);
                    showAlert("Failed to close grievance.");
                });
        }

        // Event listener for sending chat messages
        document.getElementById("chatForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent default form submission
            const message = document.getElementById("chatMessage").value.trim();

            if (message === "") {
                showAlert("Please type a message.");
                return;
            }

            fetch(`/api/resident/grievances/respond/${currentGrievanceId}`, {
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        description: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("chatMessage").value = ""; // Clear message input
                    viewGrievance(currentGrievanceId); // Reload messages to show the new one
                })
                .catch(error => {
                    console.error("Error sending message:", error);
                    showAlert("Failed to send message.");
                });
        });

        // Event listener for closing the view grievance modal
        document.getElementById("closeViewModalBtn").onclick = function() {
            document.getElementById("viewGrievanceModal").style.display = "none";
        };

        // Fetch grievances when the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", fetchGrievances);
    </script>
@endpush
