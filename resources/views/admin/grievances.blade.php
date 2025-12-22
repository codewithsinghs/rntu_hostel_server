@extends('admin.layout')

@section('content')


    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview</a></div>
        <span>
            <button class="add-btn" type="button">Download Excel</button>
        </span>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Grievances Details</a></div>

                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Complaints</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Resolved Complaints</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Progress</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Pending</p>
                            <h3>1,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="cards-bottom">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>High Priority Complaints</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-image-1">
                            <h2>H</h2>
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Medium Priority Complaints</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-image-2">
                            <h2>M</h2>
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Low Priority Complaints</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-image-3">
                            <h2>L</h2>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <!-- Pending / New Grievances List Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Pending / New Grievances List</a></div>

                <div class="overflow-auto">

                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Student Name</th>
                                <th>Scholar Number</th>
                                <th>Room Number</th>
                                <th>Hostel</th>
                                <th>Date</th>
                                <th>Priority</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Anjali Kumari<br><small>B.Com 2nd Year</small></td>
                                <td>SCH-2023-0412</td>
                                <td>A-102</td>
                                <td>Nehru Bhawan</td>
                                <td>2025-09-05</td>
                                <td>High</td>
                                <td>Food</td>
                                <td>New</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">Resolve</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rajat Pradhan<br><small>B.Com Final</small></td>
                                <td>SCH-2021-0099</td>
                                <td>B-305</td>
                                <td>Tagore Hostel</td>
                                <td>2025-09-04</td>
                                <td>Medium</td>
                                <td>Maintenance</td>
                                <td>New</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">Resolve</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sangeeta Sharma<br><small>M.Sc 1st</small></td>
                                <td>SCH-2024-0778</td>
                                <td>C-011</td>
                                <td>Gandhi Hostel</td>
                                <td>2025-09-02</td>
                                <td>Low</td>
                                <td>WiFi</td>
                                <td>New</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">Resolve</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Vikram Singh<br><small>Diploma 3rd</small></td>
                                <td>SCH-2022-0333</td>
                                <td>D-208</td>
                                <td>Shastri Block</td>
                                <td>2025-08-30</td>
                                <td>High</td>
                                <td>Safety</td>
                                <td>New</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">Resolve</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Priya Verma<br><small>BCA 1st</small></td>
                                <td>SCH-2025-0120</td>
                                <td>E-007</td>
                                <td>Radha Niwas</td>
                                <td>2025-09-06</td>
                                <td>Medium</td>
                                <td>Mess</td>
                                <td>New</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">Resolve</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                </div>

            </div>
        </div>
    </section>

    <!-- Grievances List Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Grievances List</a></div>

                <div class="overflow-auto">

                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Student Name</th>
                                <th>Scholar Number</th>
                                <th>Room Number</th>
                                <th>Hostel</th>
                                <th>Date</th>
                                <th>Priority</th>
                                <th>Complaint</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Anjali Kumari<br><small>B.Com 2nd Year</small></td>
                                <td>SCH-2023-0412</td>
                                <td>A-102</td>
                                <td>Nehru Bhawan</td>
                                <td>2025-09-01</td>
                                <td>High</td>
                                <td>Mess food quality was poor</td>
                                <td>Resolved</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal" data-bs-target="#Approvepopup">Re
                                        Open</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Rajat Pradhan<br><small>B.Com Final</small></td>
                                <td>SCH-2021-0099</td>
                                <td>B-305</td>
                                <td>Tagore Hostel</td>
                                <td>2025-08-30</td>
                                <td>Medium</td>
                                <td>AC not working in the room</td>
                                <td>Resolved</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal" data-bs-target="#Approvepopup">Re
                                        Open</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Sangeeta Sharma<br><small>M.Sc 1st</small></td>
                                <td>SCH-2024-0778</td>
                                <td>C-011</td>
                                <td>Gandhi Hostel</td>
                                <td>2025-08-28</td>
                                <td>Low</td>
                                <td>WiFi connection issue</td>
                                <td>Resolved</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal" data-bs-target="#Approvepopup">Re
                                        Open</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Vikram Singh<br><small>Diploma 3rd</small></td>
                                <td>SCH-2022-0333</td>
                                <td>D-208</td>
                                <td>Shastri Block</td>
                                <td>2025-08-25</td>
                                <td>High</td>
                                <td>Water leakage in washroom</td>
                                <td>Resolved</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal" data-bs-target="#Approvepopup">Re
                                        Open</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Priya Verma<br><small>BCA 1st</small></td>
                                <td>SCH-2025-0120</td>
                                <td>E-007</td>
                                <td>Radha Niwas</td>
                                <td>2025-08-22</td>
                                <td>Medium</td>
                                <td>Room cleaning not done properly</td>
                                <td>Resolved</td>
                                <td>
                                    <button class="green-btn" data-bs-toggle="modal" data-bs-target="#Approvepopup">Re
                                        Open</button>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#Approvepopup">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </section>

    {{-- CSRF Token Meta Tag --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container mt-5">
        <h2 class="mb-4">Manage Grievances</h2>

        {{-- General message area for the main page --}}
        <div id="generalMessage" class="mt-3"></div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Resident Name</th>
                        <th>Type of Complaint</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th>Resident Details</th> {{-- New column for resident details action --}}
                    </tr>
                </thead>
                <tbody id="grievanceTableBody">
                    <tr>
                        <td colspan="7" class="text-center">Loading grievances...</td>
                    </tr> {{-- Updated colspan --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grievance Chat Modal --}}
    <div id="viewGrievanceModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                            background: rgba(0, 0, 0, 0.5); z-index: 999; align-items: center; justify-content: center;">
        <div
            style="background: #fff; padding: 20px; border-radius: 10px; width: 400px; max-height: 600px; display: flex; flex-direction: column; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <h5 class="text-center mb-3">Grievance Chat</h5>

            {{-- Message area for inside the chat modal --}}
            <div id="chatModalMessage" class="mt-3"></div>

            <div id="chatContainer"
                style="flex-grow: 1; overflow-y: auto; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; max-height: 350px;">
            </div>

            <form id="chatForm" style="display: flex;">
                <input type="text" id="chatMessage" class="form-control" placeholder="Type your message..."
                    style="flex-grow: 1; margin-right: 5px;">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>

            <button type="button" class="btn btn-secondary mt-2" id="closeViewModalBtn">Close</button>
        </div>
    </div>

    {{-- Resident Detail Modal (Reused for grievance table) --}}
    <div id="viewResidentModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                                            background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div
            style="background: #fff; padding: 20px; border-radius: 10px; width: 500px; max-height: 80vh; overflow-y: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
            <h5 class="text-center mb-3">Resident Details</h5>
            <div id="residentDetailsContent">
                {{-- Details will be loaded here by JavaScript --}}
            </div>
            <button type="button" class="btn btn-secondary mt-3" id="closeResidentModalBtn">Close</button>
        </div>
    </div>


    <script>
        // Get the current authenticated user's ID from Laravel Blade
        const currentUserId = localStorage.getItem('auth-id');
        let currentGrievanceId = null; // Stores the ID of the currently viewed grievance
        let grievanceStatus = null; // Stores the status of the currently viewed grievance
        let allResidentsData = []; // Stores all residents data fetched from the API
        let residentsDataLoaded = false; // Flag to indicate if resident data has been loaded

        // Function to display general messages on the page
        function displayGeneralMessage(message, type = 'info') {
            const messageDiv = document.getElementById('generalMessage');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            // Optionally, hide the message after 5 seconds
            setTimeout(() => { messageDiv.innerHTML = ''; }, 5000);
        }

        // Function to display messages within the chat modal
        function displayModalMessage(message, type = 'info') {
            const messageDiv = document.getElementById('chatModalMessage');
            messageDiv.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
            // Optionally, hide the message after 5 seconds
            setTimeout(() => { messageDiv.innerHTML = ''; }, 5000);
        }

        // Event listener for when the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", () => {
            loadAllResidentsData(); // Load all residents data first
            loadGrievances(); // Then load grievances
        });

        /**
         * Fetches all residents data and stores it globally.
         * This is called once on page load to optimize subsequent resident detail lookups.
         */
        function loadAllResidentsData() {
            fetch('/api/admin/residents', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        allResidentsData = data.data; // Store the fetched data globally
                        residentsDataLoaded = true; // Set flag to true
                        // console.log('All residents data loaded successfully:', allResidentsData);
                        // After residents data is loaded, re-render grievances to enable buttons
                        loadGrievances();
                    } else {
                        console.warn('No resident data found or API call unsuccessful:', data);
                        displayGeneralMessage('Failed to load resident data. Resident details may not be available.', 'warning');
                    }
                })
                .catch(error => {
                    console.error('Error loading all residents data:', error);
                    displayGeneralMessage('Error pre-loading resident data. Please check the API endpoint.', 'danger');
                });
        }

        /**
         * Fetches and displays the list of all grievances in the table.
         */
        function loadGrievances() {
            fetch('/api/admin/grievances', {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
            })
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('grievanceTableBody');
                    tbody.innerHTML = ''; // Clear existing table rows

                    // Check if grievances data exists and is not empty, now looking for data.data
                    if (!data.data || data.data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="7" class="text-center">No grievances found.</td></tr>`; // Updated colspan
                        return;
                    }

                    // Populate the table with grievance data, iterating over data.data
                    data.data.forEach((grievance, index) => {
                        const status = grievance.status?.trim().toLowerCase();
                        const statusDisplay = status === 'closed' ? 'Closed' : 'Pending'; // Display 'Closed' or 'Pending'

                        // Determine if the "View Resident" button should be enabled
                        const isResidentButtonDisabled = !residentsDataLoaded || !allResidentsData.some(r => r.id === grievance.resident_id);
                        const residentButtonClass = isResidentButtonDisabled ? 'btn-secondary' : 'btn-primary';
                        const residentButtonDisabledAttr = isResidentButtonDisabled ? 'disabled' : '';

                        tbody.innerHTML += `
                                                                <tr>
                                                                    <td>${index + 1}</td>
                                                                    <td>${grievance.resident_name || 'N/A'}</td>
                                                                    <td>${grievance.type_of_complaint}</td>
                                                                    <td>${grievance.description}</td>
                                                                    <td>${statusDisplay}</td>
                                                                    <td>
                                                                        <button class="btn btn-sm btn-info" onclick="viewGrievance(${grievance.id})">View</button>
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-sm ${residentButtonClass}" onclick="viewResidentDetails(${grievance.resident_id})" ${residentButtonDisabledAttr}>View Details</button>
                                                                    </td>
                                                                </tr>
                                                            `;
                    });
                })
                .catch(error => {
                    console.error('Error loading grievances:', error);
                    displayGeneralMessage('Error loading grievances. Please try again.', 'danger');
                    document.getElementById('grievanceTableBody').innerHTML = `<tr><td colspan="7" class="text-danger text-center">Error loading grievances.</td></tr>`; // Updated colspan
                });
        }

        /**
         * Fetches and displays details and chat history for a specific grievance.
         * @param {number} grievanceId - The ID of the grievance to view.
         */
        function viewGrievance(grievanceId) {
            currentGrievanceId = grievanceId; // Set the global grievance ID
            displayModalMessage('', 'info'); // Clear any previous modal messages

            fetch(`/api/admin/grievances/${grievanceId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Assuming the single grievance object is now under data.data
                    const grievance = data.data;

                    // If data.data is null or undefined, handle it
                    if (!grievance) {
                        displayGeneralMessage("Grievance details not found.", 'danger');
                        document.getElementById("viewGrievanceModal").style.display = "none";
                        return;
                    }

                    grievanceStatus = grievance.status?.trim().toLowerCase(); // Update global grievance status

                    const chatContainer = document.getElementById("chatContainer");
                    chatContainer.innerHTML = ""; // Clear previous chat messages

                    // Display chat messages or a "no messages" notice
                    if (!grievance.responses || grievance.responses.length === 0) {
                        chatContainer.innerHTML = `<div class="text-center">No messages yet.</div>`;
                    } else {
                        grievance.responses.forEach(response => {
                            // Determine if the message is from the resident or admin
                            const isFromResident = response.responded_by == grievance.resident.user.id;
                            // console.log(response.responded_by+'='+grievance.resident.user.user_id);
                            // console.log(grievance.resident.user.id);
                            const sender = isFromResident ? 'Resident' : (response.responded_by == currentUserId ? 'You' : 'Admin');

                            // Style messages based on sender
                            const align = sender === 'You' ? 'right' : 'left';
                            const color = sender === 'You' ? '#d4edda' : '#f8d7da'; // Greenish for 'You', reddish for others
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
                    }

                    // Hide the chat input form if the grievance is closed
                    const chatForm = document.getElementById("chatForm");
                    chatForm.style.display = grievanceStatus === 'closed' ? 'none' : 'flex';

                    // Scroll to the bottom of the chat container
                    chatContainer.scrollTop = chatContainer.scrollHeight;
                    // Display the grievance chat modal
                    document.getElementById("viewGrievanceModal").style.display = "flex";
                })
                .catch(error => {
                    console.error("Error fetching grievance details:", error);
                    displayGeneralMessage("Failed to load grievance chat. Please try again.", 'danger');
                });
        }

        // Event listener for sending a chat message within the modal
        document.getElementById("chatForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Check if the grievance is closed before sending a message
            if (grievanceStatus === 'closed') {
                displayModalMessage("This grievance is closed. You cannot send more messages.", 'warning');
                return;
            }

            const message = document.getElementById("chatMessage").value.trim();
            if (message === "") {
                displayModalMessage("Please type a message before sending.", 'warning');
                return;
            }

            // Get CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            // /${currentGrievanceId}
            fetch(`/api/admin/grievances/respond`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                body: JSON.stringify({
                    responded_by: currentUserId,
                    description: message,
                    id: currentGrievanceId,
                })
            })
                .then(async (response) => {
                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Failed to send message');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById("chatMessage").value = ""; // Clear the message input
                    viewGrievance(currentGrievanceId); // Reload the chat to display the new message
                })
                .catch(error => {
                    console.error("Error sending message:", error);
                    displayModalMessage("Failed to send message: " + error.message, 'danger');
                });
        });

        // Event listener for closing the grievance chat modal
        document.getElementById("closeViewModalBtn").onclick = function () {
            document.getElementById("viewGrievanceModal").style.display = "none";
        };

        /**
         * Fetches and displays details for a specific resident in a modal.
         * This function now looks up the resident from the globally stored allResidentsData.
         * @param {number} residentId - The ID of the resident to view.
         */
        function viewResidentDetails(residentId) { // Renamed from viewResident to avoid confusion
            const residentDetailsContent = document.getElementById('residentDetailsContent');
            residentDetailsContent.innerHTML = '<div class="text-center">Loading resident details...</div>'; // Loading message
            document.getElementById('viewResidentModal').style.display = 'flex'; // Show modal immediately

            fetch(`/api/admin/residents/${residentId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                }
            }) // Fetch resident by ID
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const resident = data.data;

                    if (!resident) {
                        residentDetailsContent.innerHTML = `<div class="alert alert-danger">Resident details not found for ID: ${residentId}.</div>`;
                        return;
                    }

                    // Prepare bed details
                    let bedDetails = '';
                    if (resident.bed) {
                        bedDetails = `Bed Number: ${resident.bed.bed_number || 'N/A'}`;
                    }

                    // Prepare room details (only room number and floor)
                    let roomDetails = '';
                    let floorDetails = '';
                    if (resident.bed && resident.bed.room) {
                        roomDetails = `Room Number: ${resident.bed.room.room_number || 'N/A'}`;
                        // Corrected: Access floor_no from resident.bed.room
                        floorDetails = `Floor: ${resident.bed.room.floor_no || 'N/A'}`;
                    } else if (resident.bed && resident.bed.room_id) {
                        roomDetails = `Room ID: ${resident.bed.room_id} (Room number not available)`;
                        floorDetails = 'Floor: N/A';
                    } else {
                        roomDetails = 'N/A';
                        floorDetails = 'Floor: N/A';
                    }

                    residentDetailsContent.innerHTML = `
                                                            <p><strong>Name:</strong> ${resident.name || 'N/A'}</p>
                                                            <p><strong>Email:</strong> ${resident.email || 'N/A'}</p>
                                                            <p><strong>Gender:</strong> ${resident.gender || 'N/A'}</p>
                                                            <p><strong>Scholar No.:</strong> ${resident.scholar_no || 'N/A'}</p>
                                                            <p><strong>Father's Name:</strong> ${resident.fathers_name || 'N/A'}</p>
                                                            <p><strong>Mother's Name:</strong> ${resident.mothers_name || 'N/A'}</p>
                                                            <p><strong>Current Status:</strong> ${resident.status || 'N/A'}</p>
                                                            <hr>
                                                            <h6>Bed Information:</h6>
                                                            <p>${bedDetails}</p>
                                                            <p>${roomDetails}</p>
                                                            <p>${floorDetails}</p>
                                                        `;
                })
                .catch(error => {
                    console.error('Error fetching resident details:', error);
                    residentDetailsContent.innerHTML = `<div class="alert alert-danger">Error loading resident details. ${error.message}</div>`;
                });
        }

        // Event listener for closing the resident detail modal
        document.getElementById("closeResidentModalBtn").onclick = function () {
            document.getElementById("viewResidentModal").style.display = "none";
        };
    </script>
@endsection