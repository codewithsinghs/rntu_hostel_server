@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs">Room Details - <span id="university-name">-</span> </div>

                <!-- Room Details -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Hostel Name</p>
                            <h3 id="hostel-name">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Hostel Management.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Room Number</p>
                            <h3 id="room-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/livingroom.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Floor Number</p>
                            <h3 id="floor-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/floor.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Bed Number</p>
                            <h3 id="bed-number">-</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <!-- <div id="current-room-details"></div> -->

            </div>
        </div>
    </section>

    <!-- Apply room change -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <div id="alert" class="alert d-none"></div>
                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#roomChangeCollapse" aria-expanded="false"
                    aria-controls="roomChangeCollapse">

                    <span class="breadcrumbs">Room Change Request</span>
                    <span class="btn btn-primary">Apply for Room Change</span>

                </button>
                <!-- Form -->

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="roomChangeCollapse">

                    <form id="roomChangeForm">
                        @csrf
                        <div class="inpit-boxxx">
                            <div class="reason">
                                <label for="hostel">Hostel Name :</label>
                                <input type="text" id="hostel_name" name="hostel_name" class="form-control" placeholder="Hostel Name">
                            </div>

                            <div class="reason">
                                <label for="preference">Room Preference (Optional):</label>
                                <input type="text" id="preference" name="preference" class="form-control" placeholder="Room Number">
                            </div>

                            <div class="reason">
                                <label for="reason">Reason for Room Change:</label>
                                <textarea id="reason" name="reason" required placeholder="Reason for room change"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="submitted">Submit Request</button>

                    </form>
                </div>

                <!-- Form End -->

            </div>
        </div>
    </section>

    <!-- Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Leave Request List</a></div>

                <!-- <div id="mainResponseMessage" class="mt-3"></div>  -->
                <!-- Main message container for the page -->

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Current Room</th>
                                <th>Requested Room</th>
                                <th>Reason</th>
                                <th>Request Date</th>
                                <th>Warden Status</th>
                                <th>Warden Feedback</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="room-change-list">
                            {{-- <tr>
                                <td>REQ001</td>
                                <td>Room 101</td>
                                <td>Room 202</td>
                                <td>Need a quieter floor</td>
                                <td>2025-08-10</td>
                                <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                <td><span style="color: green; font-weight: bold;">Okay</span></td>
                                <td><button class="view-btn">Cancel Request</button></td>
                            </tr> --}}

                            <tr>
                                <td colspan="7" class="text-center">Loading requests...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

    <!-- Popup -->


    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mpop-title">Room Change Request Chat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="top">
                        <div class="pop-title">Room Change Request Chat</div>
                    </div> --}}
                    <div id="chatMessages" class="mb-3" style="max-height: 400px; overflow-y: auto;">
                    </div>
                    <div class="d-flex">
                        <textarea id="messageInput" class="form-control" rows="3" placeholder="Type a message..."
                            style="resize: none;"></textarea>
                        <button class="btn btn-primary ms-2" onclick="sendMessage()">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Set values from backend

            $('#roomChangeForm').submit(function(event) {
                event.preventDefault();
                let reason = $('#reason').val().trim();
                let preference = $('#preference').val().trim();
                // let csrfToken = $('input[name="_token"]').val();

                if (!reason) {
                    showAlert('Reason for room change is required!', 'danger');
                    return;
                }

                $('#submitBtn').prop('disabled', true).text('Submitting...');

                let formData = {
                    reason: reason,
                    preference: preference,
                    // _token: csrfToken
                };

                $.ajax({
                    url: "{{ url('/api/resident/room-change/request') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        "Authorization": `Bearer ${localStorage.getItem('token')}`,
                        "Accept": "application/json",
                        // "Content-Type": "application/json"
                    },
                    success: function(response) {
                        showAlert('✅ Room change request submitted successfully!', 'success');
                        $('#roomChangeForm')[0].reset();
                    },
                    error: function(xhr) {
                        let errorMessage = '❌ Error submitting request. Please try again.';

                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join(' ');
                        } else if (xhr.status === 500 && xhr.responseText.includes(
                                "foreign key constraint fails")) {
                            errorMessage = "❌ Invalid Resident ID. Please enter a valid one.";
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showAlert(errorMessage, 'danger');
                    },
                    complete: function() {
                        $('#submitBtn').prop('disabled', false).text('Submit Request');
                    }
                });
            });

            function showAlert(message, type) {
                $('#alert').removeClass('d-none alert-success alert-danger')
                    .addClass('alert-' + type).text(message).show();
                setTimeout(() => {
                    $('#alert').fadeOut();
                }, 5000);
            }
        });
    </script>

    <!-- END -->


    <!-- Room Change Status Page Js -->

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

        document.addEventListener("DOMContentLoaded", function() {

            fetchRoomChangeRequests();

            function fetchRoomChangeRequests() {
                const token = localStorage.getItem('token');

                if (!token) {
                    showCustomMessageBox("Authentication token missing. Please log in again.", "danger");
                    return;
                }

                fetch("/api/resident/room-change/requests", {
                        method: "GET",
                        headers: {
                            "Authorization": `Bearer ${token}`,
                            "Accept": "application/json"
                        }
                    })
                    .then(async response => {
                        if (response.status === 401) {
                            showCustomMessageBox("Unauthenticated. Please log in again.", "danger");
                            throw new Error("Unauthenticated");
                        }

                        if (!response.ok) {
                            const text = await response.text();
                            console.error(`HTTP error! Status: ${response.status}, Response: ${text}`);
                            throw new Error("Failed to fetch room change requests.");
                        }

                        return response.json();
                    })

                    .then(response => {
                        const roomChangeList = document.getElementById('room-change-list');
                        roomChangeList.innerHTML = "";

                        const requests = response?.data?.requests ?? [];
                        const current = response?.data?.current ?? {};

                        // Show current resident block (optional)
                        if (Object.keys(current).length > 0) {
                            // document.getElementById("current-room-details").innerHTML = `
                        //     <div class="alert alert-info mb-3">
                        //         <strong>Current Location</strong><br>
                        //         University: ${current.university ?? '-'} <br>
                        //         Building: ${current.building ?? '-'} <br>
                        //         Room: ${current.room ?? '-'} <br>
                        //         Floor: ${current.floor ?? '-'} <br>
                        //         Bed: ${current.bed ?? '-'}
                        //     </div>
                        // `;
                            // Extract current details
                            // const current = response?.data?.current ?? {};

                            // Fallback handler (clean)
                            // const universityName = current.university || 'N/A';
                            const hostelName = current.university || 'N/A';
                            const roomNumber = current.room || 'N/A';
                            const bedNumber = current.bed || 'N/A';
                            const buildingName = current.building || 'N/A';
                            // Function to convert a number to ordinal (1 → 1st, 2 → 2nd, etc.)
                            function toOrdinal(n) {
                                if (!n || isNaN(n)) return 'N/A';

                                const num = parseInt(n, 10);
                                const suffix =
                                    (num % 10 === 1 && num % 100 !== 11) ? 'st' :
                                    (num % 10 === 2 && num % 100 !== 12) ? 'nd' :
                                    (num % 10 === 3 && num % 100 !== 13) ? 'rd' : 'th';

                                // ⭐ Updated line — now superscript suffix
                                return `${num}<sup>${suffix}</sup> Floor`;
                            }
                            // ---------- FLOOR CALCULATION ----------

                            // 1) Prefer server-sent floor number (if valid)
                            let floorNumber = current.floor && !isNaN(current.floor) ?
                                toOrdinal(current.floor) :
                                null;

                            // 2) If backend floor missing → derive from room (e.g., R205 → 2nd floor)
                            if (!floorNumber || floorNumber === 'N/A') {
                                if (roomNumber && roomNumber.toString().length >= 2) {

                                    const digit = roomNumber.toString().match(/\d/); // extract first digit
                                    floorNumber = digit ? toOrdinal(digit[0]) : 'N/A';

                                } else {
                                    floorNumber = 'N/A';
                                }
                            }

                            // Apply to UI
                            document.getElementById('university-name').innerText = hostelName;
                            document.getElementById('hostel-name').innerText = buildingName;
                            document.getElementById('room-number').innerText = roomNumber;
                            // document.getElementById('floor-number').innerText = floorNumber;
                            document.getElementById('floor-number').innerHTML = floorNumber;
                            document.getElementById('bed-number').innerText = bedNumber;

                        }

                        if (!response.success || requests.length === 0) {
                            roomChangeList.innerHTML = `
                                                <tr><td colspan="7" class="text-center">No room change requests found.</td></tr>
                                            `;
                            if (!response.success && response.message) {
                                showCustomMessageBox(response.message, 'warning');
                            }
                            return;
                        }

                        requests.forEach((request, index) => {
                            let remark = request.remark?.trim() || 'No remark yet';
                            let status = request.status;
                            let isConfirmed = request.resident_agree === true || request
                                .resident_agree === 1;

                            let residentAgree = '';
                            if (isConfirmed) {
                                residentAgree = `<span class="badge bg-success">Confirmed</span>`;
                            } else if (status === 'available' || status === 'pending') {
                                // residentAgree =
                                //     `<button class="btn btn-primary btn-sm" onclick="confirmRequest(${request.id})">Confirm</button>`;
                                residentAgree =
                                    `<span class="badge bg-warning text-dark">Pending</span>`;
                            } else if (status === 'not_available') {
                                residentAgree = `<span class="badge bg-danger">Denied</span>`;
                            } else if (status === 'completed') {
                                residentAgree = `<span class="badge bg-success">Assigned</span>`;
                            } else {
                                residentAgree =
                                    `<span class="text-muted">Pending</span>`;
                            }

                            roomChangeList.innerHTML += `
                                <tr id="row-${request.id}">
                                    <td>${index + 1}</td>
                                    <td>${request.room_details}</td>
                                    <td>${request.preference ?? 'N/A'}</td>
                                    <td>${request.reason}</td>
                                <td>${request.created_at}</td>
                                    <td>${formatAction(status)}</td>
                                    <td>${remark}</td>
                                    <td id="confirm-${request.id}" class="d-flex align-items-center gap-2">
                                    ${residentAgree}
                                    <button class="btn btn-sm btn-success"
                                        onclick="openChatModal(${request.id}, '${status}', ${isConfirmed})">
                                        Chat
                                    </button>
                                </td>

                                </tr>
                            `;
                        });

                        showCustomMessageBox("Room change requests loaded successfully.", 'success');
                    })
                    .catch(error => {
                        console.error("Error fetching room change requests:", error);
                        document.getElementById("room-change-list").innerHTML =
                            `<tr><td colspan="7" class="text-center text-danger">Failed to load requests. ${error.message}</td></tr>`;
                        showCustomMessageBox("Failed to load room change requests. " + error.message, 'danger');
                    });
            }


            function formatAction(action) {
                switch (action) {
                    case 'available':
                        return `<span class="badge bg-info">Approved by Admin</span>`;
                    case 'not_available':
                        return `<span class="badge bg-danger">Denied by Admin</span>`;
                    case 'pending':
                        return `<span class="badge bg-warning">Pending</span>`;
                    case 'completed':
                        return `<span class="badge bg-primary">Assigned & Completed</span>`;
                    default:
                        return `<span class="badge bg-secondary">Unknown</span>`;
                }
            }

            window.confirmRequest = function(requestId) {
                fetch(`{{ url('/api/resident/room-change/confirm-by-resident') }}/${requestId}`, {
                        method: 'POST',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            resident_agree: true
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error(
                                    `HTTP error! Status: ${response.status}, Response: ${text}`);
                                throw new Error(
                                    `Server error or invalid response when confirming request: ${text}`
                                );
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // *** IMPORTANT CHANGE HERE: Directly update the cell instead of re-fetching the whole table ***
                            document.getElementById(`confirm-${requestId}`).innerHTML =
                                `<span class="badge bg-success">Confirmed</span>`;

                            // Optionally, update the "Admin Action" column if confirmation changes it to 'completed' on the backend
                            // This requires sending the new 'action' status back from the backend.
                            // Assuming your backend might return the updated request object, you could do:
                            // if (data.data && data.data.action === 'completed') {
                            //     const rowElement = document.getElementById(`row-${requestId}`);
                            //     if (rowElement) {
                            //         // Assuming 'Admin Action' is the 4th column (index 3)
                            //         rowElement.children[3].innerHTML = formatAction('completed');
                            //     }
                            // }

                            showCustomMessageBox(data.message ||
                                "Room change confirmed by resident successfully.", 'success');

                            // *** REMOVED: No need to call fetchRoomChangeRequests here anymore ***
                            // fetchRoomChangeRequests(currentResidentId);
                        } else {
                            showCustomMessageBox(data.message || "Failed to confirm request.", 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error confirming request:', error);
                        showCustomMessageBox('An error occurred while confirming the request. ' + error
                            .message, 'danger');
                    });
            };

            window.openChatModal = function(requestId, status, isConfirmed) {
                fetchMessages(requestId);
                const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
                chatModal.show();

                document.getElementById("chatMessages").setAttribute("data-request-id", requestId);

                const messageInput = document.getElementById("messageInput");
                const sendButton = document.querySelector("#chatModal button.btn-primary");

                // Disable chat input if the request is denied or completed
                if (status === 'not_available' || status === 'completed') {
                    messageInput.disabled = true;
                    sendButton.disabled = true;
                    if (status === 'not_available') {
                        messageInput.placeholder = "Chat is locked. This request was denied by admin.";
                    } else if (status === 'completed') {
                        messageInput.placeholder = "Chat is locked. This request has been completed.";
                    }
                } else {
                    messageInput.disabled = false;
                    sendButton.disabled = false;
                    messageInput.placeholder = "Type a message...";
                }
            };

            function fetchMessages(requestId) {
                fetch(`{{ url('/api/resident/room-change/all-messages') }}/${requestId}`, {
                        method: 'GET',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error(
                                    `HTTP error! Status: ${response.status}, Response: ${text}`);
                                throw new Error(
                                    `Server error or invalid response when fetching chat messages.`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        let chatMessages = document.getElementById('chatMessages');
                        chatMessages.innerHTML = "";

                        if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                            data.data.forEach(msg => {
                                const senderClass = msg.sender === 'resident' ? 'text-primary' :
                                    'text-success';
                                const messageHtml = `
                                                <div class="d-flex mb-2">
                                                    <div class="me-3">
                                                        <strong class="${senderClass}">${msg.sender.charAt(0).toUpperCase() + msg.sender.slice(1)}</strong>:
                                                        ${msg.message}
                                                        <small class="text-muted d-block">${new Date(msg.created_at).toLocaleString()}</small>
                                                    </div>
                                                </div>
                                            `;
                                chatMessages.innerHTML += messageHtml;
                            });
                        } else {
                            chatMessages.innerHTML = `<p>No messages found for this request.</p>`;
                        }
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(error => {
                        console.error("Error loading messages:", error);
                        document.getElementById('chatMessages').innerHTML =
                            `<p class="text-danger">Failed to load messages. ${error.message}</p>`;
                    });
            }

            window.sendMessage = function() {
                const requestId = document.getElementById("chatMessages").getAttribute("data-request-id");
                const message = document.getElementById("messageInput").value.trim();
                const sender = 'resident';

                if (!message) {
                    showCustomMessageBox("Please enter a message.", 'warning', 'chatModal');
                    return;
                }

                fetch(`{{ url('/api/resident/room-change/message') }}/${requestId}`, {
                        method: 'POST',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            message: message,
                            sender: sender
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error(
                                    `HTTP error! Status: ${response.status}, Response: ${text}`);
                                throw new Error(
                                    `Server error or invalid response when sending message.`);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            document.getElementById("messageInput").value = '';
                            fetchMessages(requestId);
                            showCustomMessageBox("Message sent!", 'success', 'chatModal');
                        } else {
                            showCustomMessageBox('Failed to send message: ' + (data.message ||
                                'Unknown error.'), 'danger', 'chatModal');
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        showCustomMessageBox('An error occurred while sending the message: ' + error
                            .message, 'danger', 'chatModal');
                    });
            };
        });
    </script>
@endpush
