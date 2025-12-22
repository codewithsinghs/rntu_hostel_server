@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Overview</a></div>

                <!-- Overview -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Leave Requests</p>
                            <h3 id="total-requests">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Leave Approved</p>
                            <h3 id="total-leaves-taken">0</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/approved.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Requests</p>
                            <h3 id="total-leaves-pending">2</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Rejected Requests</p>
                            <h3 id="total-leaves-rejected">12</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/min.png') }}" alt="" />
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Apply Leave -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- message -->
                <div id="message" class="alert d-none"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#leaveRequestCollapse" aria-expanded="false"
                    aria-controls="leaveRequestCollapse">

                    <span class="breadcrumbs">Leave Request Form</span>
                    <span class="btn btn-primary">Apply for Leave</span>

                </button>

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="leaveRequestCollapse">
                    <!-- Form -->
                    <form id="leaveRequestForm" enctype="multipart/form-data">

                        @csrf <!-- CSRF Token -->

                        <div class="inpit-boxxx">

                            <span class="input-set">
                                <label for="LeaveType">Leave Type</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected="">Select Leave Type</option>
                                    <option value="personal">Personal</option>
                                    <option value="medical">Medical</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="FromDate">Enter Days</label>
                                <input type="text">
                            </span>

                            <span class="input-set">
                                <label for="from_date" class="form-label">From Date:</label>
                                <input type="date" id="from_date" name="from_date" required>
                            </span>

                            <span class="input-set">
                                <label for="to_date" class="form-label">To Date:</label>
                                <input type="date" id="to_date" name="to_date" required>
                            </span>




                            <div class="reason">
                                <label for="photo" class="form-label">Supporting Photo/Document (Optional):</label>
                                <input type="file" id="photo" name="photo" accept="image/*">
                            </div>

                            <div class="reason">
                                <label for="reason" class="form-label">Reason:</label>
                                <textarea id="reason" name="reason" rows="3" required></textarea>
                            </div>
                        </div>
                        <button type="submit" class="submitted">Submit Request</button>

                    </form>
                    <!-- Form End -->
                </div>

            </div>
        </div>
    </section>

    <!-- Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Leave Request List</a></div>

                <div class="table-container">
                    <div class="overflow-auto">
                        <table class="status-table" cellspacing="0" cellpadding="8" width="100%" id="leaveRequestList">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Leave Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>HOD Status</th>
                                    <th>Warden Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Medical</td>
                                    <td>2025-08-01</td>
                                    <td>2025-08-03</td>
                                    <td>3</td>
                                    <td>Fever and rest</td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><a class="view-btn">Cancel Request</a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Personal</td>
                                    <td>2025-07-20</td>
                                    <td>2025-07-21</td>
                                    <td>2</td>
                                    <td>Family function</td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                    <td><a class="view-btn">Cancel Request</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <p id="no-requests" class="text-danger text-center mt-3" style="display: none;">No leave requests
                            found.
                        </p>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <script>
        document.getElementById("leaveRequestForm")?.addEventListener("submit", function(event) {
            event.preventDefault();

            // const residentId = localStorage.getItem('auth-id');
            const form = this;

            const formData = new FormData(form); // Automatically includes the file input

            fetch(`/api/resident/leave`, {
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async response => {
                    const data = await response.json();
                    const messageDiv = document.getElementById("message");

                    if (response.ok) {
                        messageDiv.className = "alert alert-success";
                        messageDiv.textContent = data.message || "Leave request submitted successfully!";
                        messageDiv.classList.remove("d-none");
                        form.reset();
                    } else {
                        messageDiv.className = "alert alert-danger";
                        messageDiv.textContent = data.error || "Error submitting request.";
                        messageDiv.classList.remove("d-none");

                        if (data.messages) {
                            for (const field in data.messages) {
                                console.error(`${field}: ${data.messages[field].join(', ')}`);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error("Unexpected error:", error);
                    const messageDiv = document.getElementById("message");
                    messageDiv.className = "alert alert-danger";
                    messageDiv.textContent = "An unexpected error occurred.";
                    messageDiv.classList.remove("d-none");
                });
        });
    </script>

    <!-- END -->





    <!-- Leave Request Status Page Script Start ----------------------------------------------------------------------------------- -->

    <!-- Modal for View Receipt -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">Leave Request Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="receiptDetails"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">Print Receipt</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#leaveRequestList tbody");
            const noRequestsMsg = document.getElementById("no-requests");

            const apiUrl = `/api/resident/leave-requests`;

            fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {

                    // ✅ Update Summary Cards
                    if (data.data.summary) {
                        document.getElementById("total-requests").innerText = data.data.summary
                            .total_leaves;
                        document.getElementById("total-leaves-taken").innerText = data.data.summary
                            .approved;
                        document.getElementById("total-leaves-pending").innerText = data.data.summary
                            .pending;
                        document.getElementById("total-leaves-rejected").innerText = data.data.summary
                            .rejected;
                    }

                    tableBody.innerHTML = "";

                    if (data.data.requests && data.data.requests.length > 0) {
                        data.data.requests.forEach((request, index) => {
                            const fromDate = new Date(request.from_date).toLocaleDateString('en-GB', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });
                            const toDate = new Date(request.to_date).toLocaleDateString('en-GB', {
                                day: 'numeric',
                                month: 'long',
                                year: 'numeric'
                            });

                            const from = new Date(request.from_date);
                            const to = new Date(request.to_date);
                            const diffDays = Math.floor((to - from) / (1000 * 60 * 60 * 24));
                            const totalDays = diffDays === 0 ? 1 : diffDays + 1;

                            tableBody.innerHTML += `
                                <tr data-request='${JSON.stringify(request).replace(/'/g, "&apos;")}'>
                                    <td>${index + 1}</td>
                                    <td>${request.reason ?? 'N/A'}</td>
                                    <td>${fromDate}</td>
                                    <td>${toDate}</td>
                                    <td>${totalDays}</td>
                                    <td>${request.reason}</td>
                                    <td>${request.hod_status}</td>
                                    <td>${request.admin_status}</td>
                                    <td><button class="btn btn-primary" onclick="viewReceipt(this)">View Receipt</button></td>
                                </tr>
                            `;
                        });
                    } else {
                        noRequestsMsg.style.display = "block";
                    }

                })
                .catch(error => {
                    console.error("Error fetching leave requests:", error);
                    tableBody.innerHTML =
                        `<tr><td colspan="8" class="text-center text-danger">Failed to load leave requests.</td></tr>`;
                });
        });

        // View Receipt using row data
        function viewReceipt(button) {
            const row = button.closest("tr");
            const request = JSON.parse(row.getAttribute("data-request").replace(/&apos;/g, "'"));

            const receiptDetails = `
                        <div class="card border-primary">
                            <div class="card-header">
                                <h5>Leave Request Receipt</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Resident Name:</strong> ${request.resident?.user?.name ?? 'N/A'}</p>
                                <p><strong>From Date:</strong> ${new Date(request.from_date).toLocaleDateString()}</p>
                                <p><strong>To Date:</strong> ${new Date(request.to_date).toLocaleDateString()}</p>
                                <p><strong>Reason:</strong> ${request.reason}</p>
                                <p><strong>HOD Status:</strong> ${request.hod_status}</p>
                                <p><strong>Admin Status:</strong> ${request.admin_status}</p>
                            </div>
                        </div>
                    `;
            document.getElementById('receiptDetails').innerHTML = receiptDetails;

            var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        }

        // Print the receipt
        document.getElementById('printReceiptBtn')?.addEventListener('click', function() {
            const printContent = document.getElementById('receiptDetails').innerHTML;
            const printWindow = window.open('', '', 'width=600,height=600');
            printWindow.document.write('<html><head><title>Print Receipt</title></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });
    </script>
@endsection
