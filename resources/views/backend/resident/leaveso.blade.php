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
                                    <th>Name</th>
                                    <th>Leave Type</th>
                                    <th>Reason</th>
                                    <th>Description</th>
                                    <th>Duration</th>
                                    <th>HOD Status</th>
                                    <th>Warden Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
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
                                </tr> --}}
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

    <!-- Modal for View Receipt -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-id-card me-2"></i>Gate Pass
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="receiptDetails" class="p-4"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    {{-- <button type="button" class="btn btn-outline-primary" id="downloadReceiptBtn">
                        <i class="fas fa-download me-1"></i> Download PDF
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById("leaveRequestForm")?.addEventListener("submit", function(event) {
            event.preventDefault();

            // const residentId = localStorage.getItem('auth-id');
            const form = this;

            const formData = new FormData(form); // Automatically includes the file input

            fetch(`/api/resident/leaves`, {
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#leaveRequestList tbody");
            const noRequestsMsg = document.getElementById("no-requests");

            const apiUrl = `/api/resident/leaves`;

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

                            tableBody.innerHTML += `
                                <tr data-request='${JSON.stringify(request).replace(/'/g, "&apos;")}'>
                                    <td>${index + 1}</td>
                                    <td>${request.name}</td>
                                    <td>${request.type}</td>
                                   <td>${truncateWithExpand(request.reason ?? 'N/A', 40)}</td>
                                    <td>${truncateWithExpand(request.description ?? 'N/A', 40)}</td>

                                    <td>
                                        Start: ${request.start_date ?? 'N/A'}<br>
                                        End: ${request.end_date ?? 'N/A'}<br>
                                        Applied: ${request.applied_at ?? 'N/A'}
                                    </td>
                                    <td>
                                    ${ request.hod_status !== 'pending' ? `${request.hod_remarks ?? 'N/A'} <br> ${request.hod_action_at ?? 'N/A'}` : `${getStatusBadge(request.hod_status)}` }
                                    </td>
                                    <td>
                                        ${ request.admin_status === 'rejected' ? `${getStatusBadge(request.admin_status)} <br> ${request.admin_remarks ?? 'N/A'} <br> ${request.admin_action_at ?? 'N/A'}` : `${getStatusBadge(request.admin_status)}` }
                                        </td>
                                    <!-- <td data-id="${request.id}"><button class="btn btn-primary" onclick="viewReceipt(this)">View Receipt</button></td> -->
                                     <td>
                                    <button 
                                        class="btn btn-primary" 
                                        data-id="${request.id}" 
                                        onclick="viewReceipt(this)">
                                        View Receipt
                                    </button>
                                </td>

                                    
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



        // function viewReceipt(button) {
        //     // Store original button content for restoration
        //     const originalButtonContent = button.innerHTML;
        //     const originalButtonClass = button.className;

        //     // Show loading state on button
        //     button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
        //     button.disabled = true;
        //     button.className = originalButtonClass.replace('btn-primary', 'btn-secondary');

        //     const leaveId = button.getAttribute("data-id");
        //     const apiUrl = `/api/resident/leaves/${leaveId}`;

        //     // Show a subtle loading overlay in the modal container
        //     const receiptContainer = document.getElementById('receiptDetails');
        //     if (receiptContainer) {
        //         receiptContainer.innerHTML = `
    //             <div class="text-center py-5 receipt-loading">
    //                 <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
    //                     <span class="visually-hidden">Loading gate pass...</span>
    //                 </div>
    //                 <p class="text-muted">Loading gate pass details...</p>
    //             </div>
    //         `;
        //     }

        //     // Show modal immediately to give feedback
        //     const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        //     receiptModal.show();

        //     // Add CSS for animations
        //     const style = document.createElement('style');
        //     style.textContent = `
    //             .receipt-loading {
    //                 animation: fadeIn 0.3s ease;
    //             }
    //             @keyframes fadeIn {
    //                 from { opacity: 0; transform: translateY(-10px); }
    //                 to { opacity: 1; transform: translateY(0); }
    //             }
    //             .receipt-card {
    //                 animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    //             }
    //             @keyframes slideUp {
    //                 from { opacity: 0; transform: translateY(20px); }
    //                 to { opacity: 1; transform: translateY(0); }
    //             }
    //             .info-item {
    //                 transition: background-color 0.2s ease;
    //                 padding: 4px 8px;
    //                 border-radius: 4px;
    //             }
    //             .info-item:hover {
    //                 background-color: rgba(13, 110, 253, 0.05);
    //             }
    //             .status-badge {
    //                 transition: transform 0.2s ease, box-shadow 0.2s ease;
    //             }
    //             .status-badge:hover {
    //                 transform: translateY(-1px);
    //                 box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    //             }
    //         `;
        //     document.head.appendChild(style);

        //     fetch(apiUrl, {
        //             method: "GET",
        //             headers: {
        //                 'Authorization': `Bearer ${localStorage.getItem('token')}`,
        //                 'Accept': 'application/json'
        //             },
        //         })
        //         .then(async response => {
        //             if (!response.ok) {
        //                 throw new Error(`HTTP ${response.status}`);
        //             }
        //             return response.json();
        //         })
        //         .then(response => {
        //             const data = response.data;

        //             // Format dates for better UX
        //             const formatDate = (dateStr) => {
        //                 if (!dateStr) return 'N/A';
        //                 const date = new Date(dateStr);
        //                 return date.toLocaleDateString('en-IN', {
        //                     day: 'numeric',
        //                     month: 'short',
        //                     year: 'numeric'
        //                 });
        //             };

        //             const formatDateTime = (dateStr) => {
        //                 if (!dateStr) return 'N/A';
        //                 const date = new Date(dateStr);
        //                 return date.toLocaleString('en-IN', {
        //                     day: 'numeric',
        //                     month: 'short',
        //                     year: 'numeric',
        //                     hour: '2-digit',
        //                     minute: '2-digit'
        //                 });
        //             };

        //             // Create status badge with icons
        //             const getStatusBadge = (status) => {
        //                 if (!status) return '<span class="badge bg-secondary">N/A</span>';

        //                 const statusMap = {
        //                     'pending': {
        //                         class: 'bg-warning text-dark',
        //                         icon: 'clock',
        //                         label: 'Pending'
        //                     },
        //                     'approved': {
        //                         class: 'bg-success',
        //                         icon: 'check-circle',
        //                         label: 'Approved'
        //                     },
        //                     'rejected': {
        //                         class: 'bg-danger',
        //                         icon: 'times-circle',
        //                         label: 'Rejected'
        //                     }
        //                 };

        //                 const statusInfo = statusMap[status.toLowerCase()] || {
        //                     class: 'bg-info',
        //                     icon: 'info-circle',
        //                     label: status
        //                 };

        //                 return `
    //                     <span class="badge ${statusInfo.class} status-badge d-inline-flex align-items-center gap-1">
    //                         <i class="fas fa-${statusInfo.icon}"></i>
    //                         ${statusInfo.label}
    //                             </span>
    //                     `;
        //             };

        //             // Build enhanced gate pass layout
        //             const receiptDetails = `
    //                     <div class="receipt-card">
    //                         <!-- Header with logo and title -->
    //                         <div class="receipt-header text-center mb-4">
    //                             <div class="institute-header mb-3">
    //                                 <!-- <img src="/images/logo.png" alt="Institute Logo" class="img-fluid mb-2" style="max-height: 70px;"> -->
    //                                 <h2 class="text-primary fw-bold mb-1">Student Gate Pass</h2>
    //                                 <p class="text-muted small mb-0">Pass ID: <span class="fw-semibold">#${data.id || 'N/A'}</span></p>
    //                             </div>
    //                             <div class="border-top border-bottom py-2">
    //                                 <div class="row">
    //                                     <div class="col">
    //                                         <small class="text-muted"><i class="fas fa-calendar me-1"></i>Generated: ${new Date().toLocaleString()}</small>
    //                                     </div>
    //                                     <div class="col">
    //                                         <small class="text-muted"><i class="fas fa-user me-1"></i>Valid for: ${data.name || 'Student'}</small>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Main content -->
    //                         <div class="row g-4">
    //                             <!-- Student Information -->
    //                             <div class="col-lg-8">
    //                                 <div class="card border-0 shadow-sm h-100">
    //                                     <div class="card-header bg-primary bg-opacity-10 border-primary border-start-0 border-end-0 border-top-0 border-3">
    //                                         <h5 class="mb-0 text-primary">
    //                                             <i class="fas fa-user-graduate me-2"></i>Student Details
    //                                         </h5>
    //                                     </div>
    //                                     <div class="card-body">
    //                                         <div class="row g-3">
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Student Name</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-user text-primary"></i>
    //                                                     <span>${data.name || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Room Number</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-bed text-primary"></i>
    //                                                     <span>${data.room_number || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Email</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-envelope text-primary"></i>
    //                                                     <span>${data.email || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Mobile</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-phone text-primary"></i>
    //                                                     <span>${data.mobile || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Course</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-graduation-cap text-primary"></i>
    //                                                     <span>${data.course || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                             <div class="col-md-6 info-item">
    //                                                 <label class="form-label text-muted small mb-1">Department</label>
    //                                                 <div class="fw-semibold d-flex align-items-center gap-2">
    //                                                     <i class="fas fa-building text-primary"></i>
    //                                                     <span>${data.department || 'N/A'}</span>
    //                                                 </div>
    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>

    //                             <!-- QR Code Section -->
    //                             <div class="col-lg-4">
    //                                 <div class="card border-0 shadow-sm h-100">
    //                                     <div class="card-header bg-success bg-opacity-10 border-success border-start-0 border-end-0 border-top-0 border-3">
    //                                         <h5 class="mb-0 text-success">
    //                                             <i class="fas fa-qrcode me-2"></i>Verification
    //                                         </h5>
    //                                     </div>
    //                                     <div class="card-body d-flex flex-column align-items-center justify-content-center">
    //                                         <div id="qrcode" class="mb-3">
    //                                             ${data.qr_code ? 
    //                                                 `<img src="data:image/png;base64,${data.qr_code}" 
        //                                                         class="img-fluid border rounded p-2 shadow-sm" 
        //                                                         style="max-width: 200px;"
        //                                                         alt="Gate Pass QR Code">` 
    //                                                 : '<div class="text-center text-muted"><i class="fas fa-qrcode fa-3x mb-2"></i><p>QR Code Not Available</p></div>'
    //                                             }
    //                                         </div>

    //                                         ${data.token ? `
        //                                                 <div class="verification-token text-center mb-3">
        //                                                     <div class="alert alert-light border">
        //                                                         <h6 class="mb-2"><i class="fas fa-shield-alt me-1"></i>Verification Token</h6>
        //                                                         <code class="bg-dark text-white p-2 rounded d-block">${data.token}</code>
        //                                                         <small class="text-muted mt-1 d-block">Use this token for manual verification</small>
        //                                                     </div>
        //                                                 </div>
        //                                             ` : ''}

    //                                         <div class="status-section text-center">
    //                                             <h6 class="text-muted mb-2">Current Status</h6>
    //                                             <div class="d-flex flex-wrap gap-2 justify-content-center">
    //                                                 ${getStatusBadge(data.status || data.admin_status || 'pending')}
    //                                             </div>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Leave Details -->
    //                         <div class="card border-0 shadow-sm mt-4">
    //                             <div class="card-header bg-info bg-opacity-10 border-info border-start-0 border-end-0 border-top-0 border-3">
    //                                 <h5 class="mb-0 text-info">
    //                                     <i class="fas fa-calendar-alt me-2"></i>Leave Details
    //                                 </h5>
    //                             </div>
    //                             <div class="card-body">
    //                                 <div class="row g-3">
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Leave Type</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-tag text-info"></i>
    //                                             <span class="text-capitalize">${data.type || 'N/A'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Purpose</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-bullseye text-info"></i>
    //                                             <span>${data.reason || 'N/A'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-12 info-item">
    //                                         <label class="form-label text-muted small mb-1">Description</label>
    //                                         <div class="fw-semibold">
    //                                             <i class="fas fa-align-left text-info me-2"></i>
    //                                             <span>${data.description || 'No additional details provided'}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">From Date</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-calendar-plus text-info"></i>
    //                                             <span>${formatDate(data.from_date || data.start_date)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">To Date</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-calendar-minus text-info"></i>
    //                                             <span>${formatDate(data.to_date || data.end_date)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Applied On</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-paper-plane text-info"></i>
    //                                             <span>${formatDateTime(data.applied_on || data.applied_at)}</span>
    //                                         </div>
    //                                     </div>
    //                                     <div class="col-md-6 info-item">
    //                                         <label class="form-label text-muted small mb-1">Duration</label>
    //                                         <div class="fw-semibold d-flex align-items-center gap-2">
    //                                             <i class="fas fa-clock text-info"></i>
    //                                             <span>
    //                                                 ${(() => {
    //                                                     const start = new Date(data.start_date || data.from_date);
    //                                                     const end = new Date(data.end_date || data.to_date);
    //                                                     if (!start || !end) return 'N/A';
    //                                                     const diffTime = Math.abs(end - start);
    //                                                     const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    //                                                     return `${diffDays + 1} day${diffDays > 0 ? 's' : ''}`;
    //                                                 })()}
    //                                             </span>
    //                                         </div>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>

    //                         <!-- Approval Timeline -->
    //                         ${data.hod_status || data.admin_status ? `
        //                                 <div class="card border-0 shadow-sm mt-4">
        //                                     <div class="card-header bg-warning bg-opacity-10 border-warning border-start-0 border-end-0 border-top-0 border-3">
        //                                         <h5 class="mb-0 text-warning">
        //                                             <i class="fas fa-history me-2"></i>Approval Timeline
        //                                         </h5>
        //                                     </div>
        //                                     <div class="card-body">
        //                                         <div class="timeline">
        //                                             <!-- Applied -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-paper-plane ${data.applied_on ? 'text-success' : 'text-secondary'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">Applied</h6>
        //                                                     <div class="text-muted small mb-1">${formatDateTime(data.applied_on || data.applied_at)}</div>
        //                                                 </div>
        //                                             </div>

        //                                             <!-- HOD Review -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-user-tie ${data.hod_status === 'approved' ? 'text-success' : 
        //                                                                             data.hod_status === 'rejected' ? 'text-danger' : 
        //                                                                             'text-warning'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">HOD Review</h6>
        //                                                     <div class="d-flex align-items-center gap-2 mb-1">
        //                                                         ${getStatusBadge(data.hod_status || 'pending')}
        //                                                         ${data.hod_action_at ? `<small class="text-muted">${formatDateTime(data.hod_action_at)}</small>` : ''}
        //                                                     </div>
        //                                                     ${data.hod_remarks ? `<p class="small mb-0 text-muted"><i class="fas fa-comment me-1"></i>${data.hod_remarks}</p>` : ''}
        //                                                 </div>
        //                                             </div>

        //                                             <!-- Admin Review -->
        //                                             <div class="timeline-step">
        //                                                 <div class="timeline-marker">
        //                                                     <i class="fas fa-user-cog ${data.admin_status === 'approved' ? 'text-success' : 
        //                                                                             data.admin_status === 'rejected' ? 'text-danger' : 
        //                                                                             'text-warning'}"></i>
        //                                                 </div>
        //                                                 <div class="timeline-content">
        //                                                     <h6 class="mb-1">Admin Review</h6>
        //                                                     <div class="d-flex align-items-center gap-2 mb-1">
        //                                                         ${getStatusBadge(data.admin_status || 'pending')}
        //                                                         ${data.admin_action_at ? `<small class="text-muted">${formatDateTime(data.admin_action_at)}</small>` : ''}
        //                                                     </div>
        //                                                     ${data.admin_remarks ? `<p class="small mb-0 text-muted"><i class="fas fa-comment me-1"></i>${data.admin_remarks}</p>` : ''}
        //                                                 </div>
        //                                             </div>
        //                                         </div>
        //                                     </div>
        //                                 </div>
        //                             ` : ''}

    //                         <!-- Footer -->
    //                         <div class="receipt-footer text-center mt-4 pt-3 border-top">
    //                             <div class="row">
    //                                 <div class="col-md-6">
    //                                     <small class="text-muted">
    //                                         <i class="fas fa-clock me-1"></i>
    //                                         Hostel Timings: 
    //                                         <span class="fw-semibold">IN ${data.hostel_in_time || 'N/A'}</span> | 
    //                                         <span class="fw-semibold">OUT ${data.hostel_out_time || 'N/A'}</span>
    //                                     </small>
    //                                 </div>
    //                                 <div class="col-md-6">
    //                                     <small class="text-muted">
    //                                         <i class="fas fa-info-circle me-1"></i>
    //                                         This gate pass is electronically generated and valid only with QR code verification
    //                                     </small>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 `;

        //             // Add CSS for timeline
        //             const timelineStyle = document.createElement('style');
        //             timelineStyle.textContent = `
    //                     .timeline {
    //                         position: relative;
    //                         padding-left: 40px;
    //                     }
    //                     .timeline-step {
    //                         position: relative;
    //                         margin-bottom: 25px;
    //                         padding-bottom: 10px;
    //                     }
    //                     .timeline-step:last-child {
    //                         margin-bottom: 0;
    //                         padding-bottom: 0;
    //                     }
    //                     .timeline-step::before {
    //                         content: '';
    //                         position: absolute;
    //                         left: -30px;
    //                         top: 5px;
    //                         bottom: -10px;
    //                         width: 2px;
    //                         background: linear-gradient(to bottom, #dee2e6, transparent);
    //                     }
    //                     .timeline-step:last-child::before {
    //                         background: #dee2e6;
    //                         height: 15px;
    //                     }
    //                     .timeline-marker {
    //                         position: absolute;
    //                         left: -40px;
    //                         top: 0;
    //                         width: 20px;
    //                         height: 20px;
    //                         display: flex;
    //                         align-items: center;
    //                         justify-content: center;
    //                         background: white;
    //                         border-radius: 50%;
    //                         border: 2px solid #dee2e6;
    //                     }
    //                     .timeline-marker i {
    //                         font-size: 0.8rem;
    //                     }
    //                     .timeline-content {
    //                         background: white;
    //                         padding: 10px 15px;
    //                         border-radius: 8px;
    //                         border-left: 3px solid #0d6efd;
    //                         box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    //                     }
    //                 `;
        //             document.head.appendChild(timelineStyle);

        //             // Update the modal content
        //             document.getElementById('receiptDetails').innerHTML = receiptDetails;

        //             // Add copy functionality for token
        //             if (data.token) {
        //                 const tokenElement = document.querySelector('.verification-token code');
        //                 if (tokenElement) {
        //                     tokenElement.style.cursor = 'pointer';
        //                     tokenElement.title = 'Click to copy token';
        //                     tokenElement.addEventListener('click', function() {
        //                         navigator.clipboard.writeText(this.textContent).then(() => {
        //                             const originalText = this.textContent;
        //                             this.textContent = 'Copied!';
        //                             this.classList.add('bg-success');
        //                             setTimeout(() => {
        //                                 this.textContent = originalText;
        //                                 this.classList.remove('bg-success');
        //                             }, 2000);
        //                         });
        //                     });
        //                 }
        //             }

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;
        //             button.className = originalButtonClass;

        //             // Add print event listener
        //             document.getElementById('printReceiptBtn').addEventListener('click', function() {
        //                 printGatePass(data);
        //             });

        //         })
        //         .catch(error => {
        //             console.error("Error fetching leave:", error);

        //             // Show error state in modal
        //             document.getElementById('receiptDetails').innerHTML = `
    //                 <div class="text-center py-5">
    //                     <div class="error-icon mb-3">
    //                         <i class="fas fa-exclamation-triangle fa-3x text-danger"></i>
    //                     </div>
    //                     <h4 class="text-danger mb-3">Unable to Load Gate Pass</h4>
    //                     <p class="text-muted mb-4">${error.message || 'There was an error loading the gate pass details. Please try again.'}</p>
    //                     <div class="d-flex justify-content-center gap-2">
    //                         <button class="btn btn-outline-primary" onclick="viewReceipt(this)" data-id="${leaveId}">
    //                             <i class="fas fa-redo me-1"></i> Retry
    //                         </button>
    //                         <button class="btn btn-secondary" data-bs-dismiss="modal">
    //                             <i class="fas fa-times me-1"></i> Close
    //                         </button>
    //                     </div>
    //                 </div>
    //             `;

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;
        //             button.className = originalButtonClass;
        //         });
        // }

        // // Enhanced print function
        // function printGatePass(data) {
        //     const printBtn = document.getElementById('printReceiptBtn');
        //     const originalContent = printBtn.innerHTML;

        //     // Show loading on print button
        //     printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Preparing...';
        //     printBtn.disabled = true;

        //     // Use timeout to ensure DOM is ready
        //     setTimeout(() => {
        //         const receiptContent = document.querySelector('#receiptDetails .receipt-card');
        //         if (!receiptContent) return;

        //         const printWindow = window.open('', '_blank', 'width=900,height=800');
        //         const now = new Date();
        //         const docTitle =
        //             `GatePass_${data.name || 'Student'}_${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}`;

        //         printWindow.document.write(`
    //     <!DOCTYPE html>
    //     <html>
    //     <head>
    //         <title>${docTitle}</title>
    //         <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    //         <style>
    //             @media print {
    //                 @page { margin: 20px; }
    //                 body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    //                 .print-header { 
    //                     text-align: center; 
    //                     margin-bottom: 30px;
    //                     border-bottom: 2px solid #333;
    //                     padding-bottom: 20px;
    //                 }
    //                 .print-header h1 { 
    //                     color: #2c3e50; 
    //                     margin: 10px 0 5px 0;
    //                     font-size: 28px;
    //                 }
    //                 .print-header .subtitle { 
    //                     color: #7f8c8d; 
    //                     font-size: 14px;
    //                 }
    //                 .section-card {
    //                     border: 1px solid #ddd;
    //                     border-radius: 8px;
    //                     padding: 20px;
    //                     margin-bottom: 20px;
    //                     break-inside: avoid;
    //                 }
    //                 .section-title {
    //                     color: #3498db;
    //                     border-bottom: 2px solid #3498db;
    //                     padding-bottom: 8px;
    //                     margin-bottom: 15px;
    //                     font-size: 18px;
    //                 }
    //                 .info-grid {
    //                     display: grid;
    //                     grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    //                     gap: 15px;
    //                     margin-bottom: 20px;
    //                 }
    //                 .info-item {
    //                     margin-bottom: 12px;
    //                 }
    //                 .info-label {
    //                     font-weight: 600;
    //                     color: #7f8c8d;
    //                     font-size: 13px;
    //                     margin-bottom: 3px;
    //                 }
    //                 .info-value {
    //                     font-weight: 500;
    //                     color: #2c3e50;
    //                     font-size: 14px;
    //                 }
    //                 .qr-section {
    //                     text-align: center;
    //                     padding: 20px;
    //                     border: 1px dashed #ddd;
    //                     border-radius: 8px;
    //                     margin: 20px 0;
    //                 }
    //                 .qr-section img {
    //                     max-width: 150px;
    //                     height: auto;
    //                 }
    //                 .status-badge {
    //                     display: inline-block;
    //                     padding: 4px 12px;
    //                     border-radius: 20px;
    //                     font-size: 12px;
    //                     font-weight: 600;
    //                     margin: 2px;
    //                 }
    //                 .badge-approved { background: #d4edda; color: #155724; }
    //                 .badge-pending { background: #fff3cd; color: #856404; }
    //                 .badge-rejected { background: #f8d7da; color: #721c24; }
    //                 .timeline-print {
    //                     margin: 20px 0;
    //                     padding-left: 20px;
    //                     border-left: 2px solid #3498db;
    //                 }
    //                 .timeline-item {
    //                     margin-bottom: 15px;
    //                     position: relative;
    //                 }
    //                 .timeline-item::before {
    //                     content: '';
    //                     position: absolute;
    //                     left: -25px;
    //                     top: 5px;
    //                     width: 10px;
    //                     height: 10px;
    //                     border-radius: 50%;
    //                     background: #3498db;
    //                 }
    //                 .footer {
    //                     text-align: center;
    //                     margin-top: 40px;
    //                     padding-top: 20px;
    //                     border-top: 1px solid #ddd;
    //                     font-size: 11px;
    //                     color: #7f8c8d;
    //                 }
    //                 .watermark {
    //                     opacity: 0.1;
    //                     position: fixed;
    //                     top: 50%;
    //                     left: 50%;
    //                     transform: translate(-50%, -50%);
    //                     font-size: 80px;
    //                     color: #000;
    //                     pointer-events: none;
    //                     z-index: -1;
    //                 }
    //                 .no-print { display: none !important; }
    //             }
    //             body { margin: 30px; }
    //         </style>
    //     </head>
    //     <body>
    //         <div class="watermark">GATE PASS</div>

    //         <div class="print-header">
    //             <h1>STUDENT GATE PASS</h1>
    //             <div class="subtitle">
    //                 Pass ID: #${data.id || 'N/A'} | Generated: ${new Date().toLocaleString()}
    //             </div>
    //         </div>

    //         <div class="section-card">
    //             <h3 class="section-title">Student Information</h3>
    //             <div class="info-grid">
    //                 <div class="info-item">
    //                     <div class="info-label">Student Name</div>
    //                     <div class="info-value">${data.name || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Room Number</div>
    //                     <div class="info-value">${data.room_number || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Email</div>
    //                     <div class="info-value">${data.email || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Mobile</div>
    //                     <div class="info-value">${data.mobile || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Course</div>
    //                     <div class="info-value">${data.course || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Department</div>
    //                     <div class="info-value">${data.department || 'N/A'}</div>
    //                 </div>
    //             </div>
    //         </div>

    //         <div class="section-card">
    //             <h3 class="section-title">Leave Details</h3>
    //             <div class="info-grid">
    //                 <div class="info-item">
    //                     <div class="info-label">Leave Type</div>
    //                     <div class="info-value">${data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Purpose</div>
    //                     <div class="info-value">${data.reason || 'N/A'}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">From Date</div>
    //                     <div class="info-value">${new Date(data.start_date || data.from_date).toLocaleDateString()}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">To Date</div>
    //                     <div class="info-value">${new Date(data.end_date || data.to_date).toLocaleDateString()}</div>
    //                 </div>
    //                 <div class="info-item">
    //                     <div class="info-label">Applied On</div>
    //                     <div class="info-value">${new Date(data.applied_at || data.applied_on).toLocaleString()}</div>
    //                 </div>
    //             </div>
    //         </div>

    //         ${data.qr_code ? `
        //                 <div class="qr-section">
        //                     <h3 class="section-title">Verification QR Code</h3>
        //                     <img src="data:image/png;base64,${data.qr_code}" alt="QR Code">
        //                     ${data.token ? `<div style="margin-top: 10px;"><strong>Token:</strong> ${data.token}</div>` : ''}
        //                 </div>
        //             ` : ''}

    //         ${data.hod_status || data.admin_status ? `
        //                 <div class="section-card">
        //                     <h3 class="section-title">Approval Status</h3>
        //                     <div style="margin-bottom: 15px;">
        //                         <strong>HOD Status:</strong> 
        //                         <span class="status-badge ${data.hod_status === 'approved' ? 'badge-approved' : 
        //                                                    data.hod_status === 'rejected' ? 'badge-rejected' : 
        //                                                    'badge-pending'}">
        //                             ${data.hod_status || 'Pending'}
        //                         </span>
        //                         ${data.hod_remarks ? `<div style="margin-top: 5px; font-size: 12px;">${data.hod_remarks}</div>` : ''}
        //                     </div>
        //                     <div>
        //                         <strong>Admin Status:</strong> 
        //                         <span class="status-badge ${data.admin_status === 'approved' ? 'badge-approved' : 
        //                                                    data.admin_status === 'rejected' ? 'badge-rejected' : 
        //                                                    'badge-pending'}">
        //                             ${data.admin_status || 'Pending'}
        //                         </span>
        //                         ${data.admin_remarks ? `<div style="margin-top: 5px; font-size: 12px;">${data.admin_remarks}</div>` : ''}
        //                     </div>
        //                 </div>
        //             ` : ''}

    //         <div class="footer">
    //             <div>Generated by Leave Management System • ${new Date().toLocaleString()}</div>
    //             <div style="margin-top: 5px;">
    //                 <strong>Hostel Timings:</strong> IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}
    //             </div>
    //             <div style="margin-top: 5px; font-size: 10px;">
    //                 This is an electronically generated document. Valid only with QR code verification.
    //             </div>
    //         </div>
    //     </body>
    //     </html>
    // `);

        //         printWindow.document.close();
        //         printWindow.focus();

        //         // Wait for content to load then print
        //         setTimeout(() => {
        //             printWindow.print();
        //             printWindow.close();
        //         }, 500);

        //         // Restore print button
        //         printBtn.innerHTML = originalContent;
        //         printBtn.disabled = false;

        //     }, 500);
        // }

        function viewReceipt(button) {
            const originalButtonHTML = button.innerHTML;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
            button.disabled = true;

            const leaveId = button.getAttribute("data-id");
            const receiptContainer = document.getElementById('receiptDetails');

            receiptContainer.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                    <p class="text-muted">Loading...</p>
                </div>
            `;

            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();

            fetch(`/api/resident/leaves/${leaveId}`, {
                    method: "GET",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                .then(async res => {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    return res.json();
                })
                .then(({
                    data
                }) => {
                    const formatDate = d => d ? new Date(d).toLocaleDateString('en-IN') : 'N/A';
                    const formatDateTime = d => d ? new Date(d).toLocaleString('en-IN') : 'N/A';

                    const getStatusBadge = (s) => {
                        const map = {
                            'pending': {
                                class: 'bg-warning text-dark',
                                icon: 'clock'
                            },
                            'approved': {
                                class: 'bg-success',
                                icon: 'check-circle'
                            },
                            'rejected': {
                                class: 'bg-danger',
                                icon: 'times-circle'
                            }
                        };
                        const status = s?.toLowerCase();
                        const info = map[status] || {
                            class: 'bg-info',
                            icon: 'info-circle'
                        };
                        return `<span class="badge ${info.class}"><i class="fas fa-${info.icon} me-1"></i>${s || 'N/A'}</span>`;
                    };

                    // <p class="text-muted small">ID: #${data.id || ''} • ${formatDateTime(new Date())}</p>
                    receiptContainer.innerHTML = `
                        <div class="receipt-card">
                            <div class="text-center mb-4">
                                <h3 class="text-primary mb-2">Student Gate Pass</h3>
                                <p class="text-muted small"> ${formatDateTime(new Date())}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-user-graduate me-2"></i>Student Info</h5>
                                        <div class="row g-2">
                                            <div class="col-6"><small class="text-muted">Name</small><div>${data.name || ''}</div></div>
                                            <div class="col-6"><small class="text-muted">Scholar No.</small><div>${data.scholar_no || ''}</div></div>
                                            <div class="col-6"><small class="text-muted">Mobile</small><div>${data.mobile || 'N/A'}</div></div>
                                            <div class="col-6"><small class="text-muted">Hostel</small><div>${data.hostel_name || ''} </div></div>
                                            <div class="col-6"><small class="text-muted">Room</small><div>${data.room_number || 'N/A'}</div></div>
                                            <div class="col-6"><small class="text-muted">Course</small><div>${data.course || 'N/A'}</div></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="border-bottom pb-2"><i class="fas fa-calendar-alt me-2"></i>Leave Details</h5>
                                        <div class="row g-2">
                                            <div class="col-6"><small class="text-muted">Type</small><div>${data.type || 'N/A'}</div></div>
                                            <div class="col-6"><small class="text-muted">Reason</small><div>${data.reason || 'N/A'}</div></div>
                                            <div class="col-6"><small class="text-muted">From</small><div>${formatDate(data.start_date || data.from_date)}</div></div>
                                            <div class="col-6"><small class="text-muted">To</small><div>${formatDate(data.end_date || data.to_date)}</div></div>
                                            <div class="col-6"><small class="text-muted">Applied On</small><div>${(data.applied_on || data.applied_on ? data.applied_on : '')}</div></div>
                                            <div class="col-6"><small class="text-muted">Action Date</small><div>${(data.admin_action_at || data.admin_action_at ? data.admin_action_at : 'Not yet processed')}</div></div>
                                        </div>
                                    </div>

                                <!-- ${data.hod_status || data.admin_status ? `
                                                <div class="mt-3">
                                                    <h5 class="border-bottom pb-2 mt-3"><i class="fas fa-check-circle me-2"></i>Approval Status</h5>
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <small class="text-muted">HOD</small>
                                                            <div>${getStatusBadge(data.hod_status)}</div>
                                                            ${data.hod_remarks ? `<small class="text-muted">${data.hod_remarks}</small>` : ''}
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted">Admin</small>
                                                            <div>${getStatusBadge(data.admin_status)}</div>
                                                            ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
                                                        </div>
                                                    </div>
                                                </div>
                                                ` : ''} -->
                                </div>

                                <div class="col-md-4">
                                    <div class="border-start ps-3">
                                        <h5 class="border-bottom pb-2 text-center"><i class="fas fa-qrcode me-2"></i>Verification</h5>
                                        <div class="text-center">
                                            ${data.qr_code ? 
                                                `<img src="data:image/png;base64,${data.qr_code}" class="img-fluid mb-2" style="max-width:180px;" alt="QR">` : 
                                                '<div class="text-muted py-3"><i class="fas fa-qrcode fa-2x"></i><p>No QR Code</p></div>'
                                            }
                                            ${data.token ? `
                                                            <div class="small mt-2">
                                                                <small class="text-muted d-block">Token</small>
                                                                <code class="bg-light p-1 rounded">${data.token}</code>
                                                            </div>
                                                        ` : ''}
                                            <p class="small text-muted mt-2">Scan to verify</p>
                                        </div>

                                        ${data.status ? `
                                                    <div class="mt-3">
                                                        <h5 class="border-bottom pb-2"><i class="fas fa-check-circle me-2"></i>Approval Status</h5>
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <small class="text-muted">Leave Status</small>
                                                                <div>${getStatusBadge(data.status)}</div>
                                                                ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
                                                            </div>
                                                        </div>
                                                    </div>
                                                ` : ''}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-top small text-muted">
                                <div class="row">
                                    <div class="col">Hostel Timings: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
                                    <div class="col text-end">Generated electronically</div>
                                </div>
                            </div>
                        </div>
                     `;

                    button.innerHTML = originalButtonHTML;
                    button.disabled = false;

                    // Setup token copy
                    const tokenEl = receiptContainer.querySelector('code');
                    if (tokenEl) {
                        tokenEl.style.cursor = 'pointer';
                        tokenEl.onclick = () => {
                            navigator.clipboard.writeText(tokenEl.textContent);
                            const original = tokenEl.textContent;
                            tokenEl.textContent = 'Copied!';
                            setTimeout(() => tokenEl.textContent = original, 1500);
                        };
                    }

                    // Setup print
                    document.getElementById('printReceiptBtn').onclick = () => smartPrint(data);

                }).catch(err => {
                    console.error(err);
                    receiptContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
                            <h5 class="text-danger">Error Loading</h5>
                            <p class="text-muted">${err.message || 'Please try again'}</p>
                            <button class="btn btn-sm btn-outline-primary mt-2" onclick="viewReceipt(this)" data-id="${leaveId}">
                                <i class="fas fa-redo me-1"></i> Retry
                            </button>
                        </div>
                    `;
                    button.innerHTML = originalButtonHTML;
                    button.disabled = false;
                });
        }

        // Smart compact print function
        function smartPrint(data) {
            const printBtn = document.getElementById('printReceiptBtn');
            const originalHTML = printBtn.innerHTML;
            printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
            printBtn.disabled = true;

            const format = d => d ? new Date(d).toLocaleDateString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            }) : 'N/A';
            const now = new Date().toLocaleString('en-IN', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Print configuration - control what prints
            const printConfig = {
                showLogo: true,
                showQr: true,
                showStudentInfo: true,
                showLeaveDetails: true,
                showStatus: true,
                showFooter: true,
                compactMode: true // Reduced spacing for compact print
            };

            const printWindow = window.open('', '_blank', 'width=800,height=600');

            printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Gate Pass #${data.id || ''}</title>
                <style>
                    @media print {
                        @page { margin: 15mm; }
                        body { 
                            font-family: Arial, sans-serif; 
                            margin: 0; 
                            padding: 0;
                            font-size: 12px;
                            line-height: 1.3;
                        }
                        .no-print { display: none !important; }
                        .print-only { display: block !important; }
                    }
                    body { 
                        font-family: Arial, sans-serif; 
                        margin: 20px;
                        font-size: 12px;
                        line-height: 1.3;
                    }
                    .header { 
                        text-align: center; 
                        margin-bottom: ${printConfig.compactMode ? '15px' : '20px'};
                        padding-bottom: ${printConfig.compactMode ? '10px' : '15px'};
                        border-bottom: 2px solid #000;
                    }
                    .header img { 
                        max-height: 60px; 
                        margin-bottom: ${printConfig.compactMode ? '5px' : '10px'};
                    }
                    .header h1 { 
                        margin: 0; 
                        font-size: ${printConfig.compactMode ? '18px' : '20px'};
                        color: #000;
                    }
                    .header .subtitle {
                        font-size: 11px;
                        color: #666;
                        margin-top: 3px;
                    }
                    .content { 
                        display: flex; 
                        margin: ${printConfig.compactMode ? '15px 0' : '20px 0'};
                    }
                    .left { 
                        flex: 1; 
                        padding-right: ${printConfig.compactMode ? '15px' : '20px'};
                    }
                    .right { 
                        width: 160px; 
                        text-align: center;
                        border-left: 1px solid #ddd;
                        padding-left: ${printConfig.compactMode ? '10px' : '15px'};
                    }
                    .section {
                        margin-bottom: ${printConfig.compactMode ? '12px' : '15px'};
                        page-break-inside: avoid;
                    }
                    .section-title {
                        font-weight: bold;
                        font-size: 13px;
                        color: #333;
                        margin-bottom: ${printConfig.compactMode ? '6px' : '8px'};
                        padding-bottom: ${printConfig.compactMode ? '3px' : '4px'};
                        border-bottom: 1px solid #ccc;
                    }
                    .info-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: ${printConfig.compactMode ? '8px' : '10px'};
                    }
                    .info-item {
                        margin-bottom: ${printConfig.compactMode ? '5px' : '6px'};
                    }
                    .info-label {
                        font-weight: 600;
                        color: #555;
                        font-size: 11px;
                        margin-bottom: 1px;
                    }
                    .info-value {
                        color: #000;
                        font-size: 12px;
                    }
                    .qr-container {
                        margin: ${printConfig.compactMode ? '10px 0' : '15px 0'};
                    }
                    .qr-container img {
                        width: ${printConfig.compactMode ? '130px' : '150px'};
                        height: ${printConfig.compactMode ? '130px' : '150px'};
                        border: 1px solid #ddd;
                        padding: 5px;
                        background: white;
                    }
                    .badge {
                        display: inline-block;
                        padding: 2px 6px;
                        border-radius: 3px;
                        font-size: 10px;
                        font-weight: bold;
                    }
                    .badge-approved { background: #28a745; color: white; }
                    .badge-pending { background: #ffc107; color: #000; }
                    .badge-rejected { background: #dc3545; color: white; }
                    .token {
                        font-family: monospace;
                        font-size: 10px;
                        background: #f8f9fa;
                        padding: 4px 6px;
                        border-radius: 3px;
                        margin-top: 5px;
                        word-break: break-all;
                    }
                    .footer {
                        text-align: center;
                        margin-top: ${printConfig.compactMode ? '15px' : '20px'};
                        padding-top: ${printConfig.compactMode ? '8px' : '10px'};
                        border-top: 1px solid #ddd;
                        font-size: 10px;
                        color: #666;
                    }
                    .watermark {
                        opacity: 0.03;
                        position: fixed;
                        top: 30%;
                        left: 50%;
                        transform: translate(-50%, -50%) rotate(-45deg);
                        font-size: 100px;
                        font-weight: bold;
                        color: #000;
                        z-index: -1;
                    }
                </style>
            </head>
            <body>
                ${printConfig.showLogo ? `
                            <div class="header">
                                <img src="https://hostel.rntu.ac.in/frontend/img/main-logo.png" alt="Logo">
                                <h1>GATE PASS</h1>
                                <div class="subtitle"> ${now}</div>
                            </div>
                            ` : `<h1 class="header">GATE PASS #${data.id || ''}</h1>`}

                <div class="content">
                    ${printConfig.showStudentInfo || printConfig.showLeaveDetails ? `
                                <div class="left">
                                    ${printConfig.showStudentInfo ? `
                        <div class="section">
                            <div class="section-title">STUDENT INFORMATION</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Name</div>
                                    <div class="info-value">${data.name || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Scholar</div>
                                    <div class="info-value">${data.scholar_no || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Hostel</div>
                                    <div class="info-value">${data.hostel_name || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Room No.</div>
                                    <div class="info-value">${data.room_number || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Mobile</div>
                                    <div class="info-value">${data.mobile || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Course</div>
                                    <div class="info-value">${data.course || 'N/A'}</div>
                                </div>
                            </div>
                        </div>
                        ` : ''}

                                    ${printConfig.showLeaveDetails ? `
                        <div class="section">
                            <div class="section-title">LEAVE DETAILS</div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Type</div>
                                    <div class="info-value">${data.type || 'N/A'}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Disposal</div>
                                    <div class="info-value">${(data.admin_action_at ? data.admin_action_at : '')}</div>
                                </div>

                                <div class="info-item">
                                    <div class="info-label">From</div>
                                    <div class="info-value">${format(data.start_date || data.from_date)}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">To</div>
                                    <div class="info-value">${format(data.end_date || data.to_date)}</div>
                                </div>

                            </div>
                        </div>
                        ` : ''}
                                    <!--
                                    ${printConfig.showStatus && (data.hod_status || data.admin_status) ? `
                                <div class="section">
                                    <div class="section-title">STATUS</div>
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <div class="info-label">HOD</div>
                                            <div class="info-value">
                                                <span class="badge ${data.hod_status === 'approved' ? 'badge-approved' : 
                                                                data.hod_status === 'rejected' ? 'badge-rejected' : 
                                                                'badge-pending'}">
                                                    ${data.hod_status || 'Pending'}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label">Admin</div>
                                            <div class="info-value">
                                                <span class="badge ${data.admin_status === 'approved' ? 'badge-approved' : 
                                                                data.admin_status === 'rejected' ? 'badge-rejected' : 
                                                                'badge-pending'}">
                                                    ${data.admin_status || 'Pending'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ` : ''}

                                    -->
                                </div>
                                ` : ''}

                    ${printConfig.showQr ? `
                                <div class="right">
                                    <div class="section">
                                        <div class="section-title">VERIFICATION</div>
                                        <div class="qr-container">
                                            ${data.qr_code ? 
                                                `<img src="data:image/png;base64,${data.qr_code}" alt="QR Code">` : 
                                                `<div style="height:150px; display:flex; align-items:center; justify-content:center; border:1px dashed #ccc; color:#999;">
                                        No QR Code
                                    </div>`
                                            }
                                        </div>
                                        ${data.token ? `
                                <div>
                                    <div class="info-label">Token</div>
                                    <div class="token">${data.token}</div>
                                </div>
                                ` : ''}
                                        <div style="margin-top:10px; font-size:11px; font-weight:bold;">SCAN TO VERIFY</div>
                                    </div>

                                     ${printConfig.showStatus && (data.status) ? `
                                <div class="section">
                                    <!-- <div class="section-title">STATUS</div> -->

                                        <div class="info-item text-center">
                                            <div class="info-label"></div>
                                            <div class="info-value">
                                                <span class="badge ${data.status === 'approved' ? 'badge-approved' : 
                                                                data.status === 'rejected' || 'Rejected' ? 'badge-rejected' : 
                                                                'badge-pending'}">
                                                    ${data.status || 'Pending'}
                                                </span>
                                            </div>
                                        </div>

                                    ${data.status === 'rejected' || 'Rejected' && data.admin_remarks ? `
                                                <div style="margin-top:5px; font-size:10px; color:#555;">
                                                    <strong>Remarks:</strong> ${data.admin_remarks}
                                                </div>
                                            ` : ''}
                                </div>
                                ` : ''}

                                    <div style="margin-top:40px; font-size:12px;">
                                        <strong>Authorised Sign..</strong>
                                        </div>
                                </div>
                                ` : ''}
                </div>

                ${printConfig.showFooter ? `
                            <div class="footer">
                                <div>Generated by Leave Management System • ${new Date().toLocaleString('en-IN')}</div>
                                <div>Hostel: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
                                <div style="margin-top:2px; font-size:9px;">Electronically generated • Valid with QR verification</div>
                            </div>
                            ` : ''}

                <div class="watermark print-only">GATE PASS</div>

                <div class="no-print" style="text-align:center; margin-top:20px;">
                    <button onclick="window.print()" style="padding:8px 20px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
                        Print Now
                    </button>
                    <button onclick="window.close()" style="padding:8px 20px; background:#6c757d; color:white; border:none; border-radius:4px; margin-left:10px; cursor:pointer;">
                        Close
                    </button>
                </div>


            </body>
            </html>
            `);

            printWindow.document.close();

            // Restore button
            setTimeout(() => {
                printBtn.innerHTML = originalHTML;
                printBtn.disabled = false;
            }, 1000);
        }



        // function viewReceipt(button) {
        //     // Store original button content
        //     const originalButtonContent = button.innerHTML;

        //     // Show loading state
        //     button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
        //     button.disabled = true;

        //     const leaveId = button.getAttribute("data-id");
        //     const apiUrl = `/api/resident/leaves/${leaveId}`;

        //     // Show modal immediately
        //     const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        //     const receiptContainer = document.getElementById('receiptDetails');

        //     receiptContainer.innerHTML = `
        //         <div class="text-center py-5">
        //             <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
        //                 <span class="visually-hidden">Loading...</span>
        //             </div>
        //             <p class="text-muted">Loading gate pass details...</p>
        //         </div>
        //     `;

        //     receiptModal.show();

        //     fetch(apiUrl, {
        //             method: "GET",
        //             headers: {
        //                 'Authorization': `Bearer ${localStorage.getItem('token')}`,
        //                 'Accept': 'application/json'
        //             },
        //         })
        //         .then(async response => {
        //             if (!response.ok) {
        //                 throw new Error(`HTTP ${response.status}`);
        //             }
        //             return response.json();
        //         })
        //         .then(response => {
        //             const data = response.data;

        //             // Build receipt HTML
        //             const receiptDetails = `
        //             <div class="text-center mb-4">
        //                 <h2 class="text-primary mb-2">Student Gate Pass</h2>
        //                 <p class="text-muted">Pass ID: ${data.id || 'N/A'}</p>
        //             </div>
                    
        //             <div class="row">
        //                 <!-- Student Information -->
        //                 <div class="col-md-8">
        //                     <div class="card mb-3">
        //                         <div class="card-header bg-light">
        //                             <h5 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
        //                         </div>
        //                         <div class="card-body">
        //                             <div class="row">
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Student Name</small>
        //                                     <div class="fw-bold">${data.name || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Room Number</small>
        //                                     <div class="fw-bold">${data.room_number || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Email</small>
        //                                     <div>${data.email || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Mobile</small>
        //                                     <div>${data.mobile || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Course</small>
        //                                     <div>${data.course || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Department</small>
        //                                     <div>${data.department || 'N/A'}</div>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
                            
        //                     <div class="card">
        //                         <div class="card-header bg-light">
        //                             <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Leave Details</h5>
        //                         </div>
        //                         <div class="card-body">
        //                             <div class="row">
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Leave Type</small>
        //                                     <div class="fw-bold">${data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-md-6 mb-2">
        //                                     <small class="text-muted">Reason</small>
        //                                     <div>${data.reason || 'N/A'}</div>
        //                                 </div>
        //                                 <div class="col-12 mb-2">
        //                                     <small class="text-muted">Description</small>
        //                                     <div>${data.description || 'No additional details provided'}</div>
        //                                 </div>
        //                                 <div class="col-md-4 mb-2">
        //                                     <small class="text-muted">From Date</small>
        //                                     <div class="fw-bold">${new Date(data.start_date || data.from_date).toLocaleDateString()}</div>
        //                                 </div>
        //                                 <div class="col-md-4 mb-2">
        //                                     <small class="text-muted">To Date</small>
        //                                     <div class="fw-bold">${new Date(data.end_date || data.to_date).toLocaleDateString()}</div>
        //                                 </div>
        //                                 <div class="col-md-4 mb-2">
        //                                     <small class="text-muted">Applied On</small>
        //                                     <div>${new Date(data.applied_at || data.applied_on).toLocaleString()}</div>
        //                                 </div>
        //                                 ${data.hod_status ? `
        //                                         <div class="col-md-6 mb-2">
        //                                             <small class="text-muted">HOD Status</small>
        //                                             <div>
        //                                                 <span class="badge ${data.hod_status === 'approved' ? 'bg-success' : 
        //                                                                 data.hod_status === 'rejected' ? 'bg-danger' : 
        //                                                                 'bg-warning'}">
        //                                                     ${data.hod_status}
        //                                                 </span>
        //                                                 ${data.hod_remarks ? `<div class="small mt-1">${data.hod_remarks}</div>` : ''}
        //                                             </div>
        //                                         </div>
        //                                     ` : ''}
        //                                 ${data.admin_status ? `
        //                                         <div class="col-md-6 mb-2">
        //                                             <small class="text-muted">Admin Status</small>
        //                                             <div>
        //                                                 <span class="badge ${data.admin_status === 'approved' ? 'bg-success' : 
        //                                                                 data.admin_status === 'rejected' ? 'bg-danger' : 
        //                                                                 'bg-warning'}">
        //                                                     ${data.admin_status}
        //                                                 </span>
        //                                                 ${data.admin_remarks ? `<div class="small mt-1">${data.admin_remarks}</div>` : ''}
        //                                             </div>
        //                                         </div>
        //                                     ` : ''}
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
                        
        //                 <!-- QR Code Section -->
        //                 <div class="col-md-4">
        //                     <div class="card h-100">
        //                         <div class="card-header bg-light">
        //                             <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Verification</h5>
        //                         </div>
        //                         <div class="card-body d-flex flex-column align-items-center justify-content-center">
        //                             <div id="qrcode" class="mb-3">
        //                                 ${data.qr_code ? 
        //                                     `<img src="data:image/png;base64,${data.qr_code}" 
        //                                             class="img-fluid border rounded p-2" 
        //                                             style="max-width: 200px;"
        //                                             alt="QR Code">` 
        //                                     : '<div class="text-center text-muted"><i class="fas fa-qrcode fa-3x mb-2"></i><p>QR Code Not Available</p></div>'
        //                                 }
        //                             </div>
                                    
        //                             ${data.token ? `
        //                                     <div class="text-center mb-3">
        //                                         <div class="alert alert-light border">
        //                                             <small class="text-muted d-block mb-1">Verification Token</small>
        //                                             <code class="fw-bold">${data.token}</code>
        //                                         </div>
        //                                     </div>
        //                                 ` : ''}
                                    
        //                             <div class="text-center">
        //                                 <small class="text-muted">Scan QR code to verify authenticity</small>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
                    
        //             <div class="mt-3 text-center">
        //                 <small class="text-muted">
        //                     <i class="fas fa-clock me-1"></i>
        //                     Hostel Timings: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}
        //                 </small>
        //             </div>
        //         `;

        //             receiptContainer.innerHTML = receiptDetails;

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;

        //             // Setup print functionality
        //             document.getElementById('printReceiptBtn').onclick = function() {
        //                 printGatePass(data);
        //             };

        //             // Setup token copy functionality
        //             if (data.token) {
        //                 const tokenElement = receiptContainer.querySelector('code');
        //                 if (tokenElement) {
        //                     tokenElement.style.cursor = 'pointer';
        //                     tokenElement.title = 'Click to copy token';
        //                     tokenElement.addEventListener('click', function() {
        //                         navigator.clipboard.writeText(this.textContent).then(() => {
        //                             const originalText = this.textContent;
        //                             this.textContent = 'Copied!';
        //                             setTimeout(() => {
        //                                 this.textContent = originalText;
        //                             }, 2000);
        //                         });
        //                     });
        //                 }
        //             }

        //         })
        //         .catch(error => {
        //             console.error("Error fetching leave:", error);

        //             receiptContainer.innerHTML = `
        //             <div class="text-center py-5">
        //                 <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
        //                 <h4 class="text-danger mb-3">Error Loading Gate Pass</h4>
        //                 <p class="text-muted mb-4">${error.message || 'Unable to load gate pass details'}</p>
        //                 <button class="btn btn-outline-primary" onclick="viewReceipt(this)" data-id="${leaveId}">
        //                     <i class="fas fa-redo me-1"></i> Try Again
        //                 </button>
        //             </div>
        //         `;

        //             // Restore button state
        //             button.innerHTML = originalButtonContent;
        //             button.disabled = false;
        //         });
        // }

        // // Print function with left side data and right side QR
        // function printGatePass(data) {
        //     const printBtn = document.getElementById('printReceiptBtn');
        //     const originalContent = printBtn.innerHTML;

        //     // Show loading on print button
        //     printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Preparing...';
        //     printBtn.disabled = true;

        //     // Format date
        //     const formatDate = (dateStr) => {
        //         if (!dateStr) return 'N/A';
        //         return new Date(dateStr).toLocaleDateString('en-IN', {
        //             day: 'numeric',
        //             month: 'short',
        //             year: 'numeric'
        //         });
        //     };

        //     // Calculate duration
        //     const calculateDuration = () => {
        //         if (!data.start_date || !data.end_date) return 'N/A';
        //         const start = new Date(data.start_date);
        //         const end = new Date(data.end_date);
        //         const diffTime = Math.abs(end - start);
        //         const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        //         return `${diffDays + 1} day${diffDays > 0 ? 's' : ''}`;
        //     };

        //     const now = new Date();
        //     const docTitle = `GatePass_${data.id || new Date().getTime()}`;

        //     // Create print window
        //     const printWindow = window.open('', '_blank');

        //     printWindow.document.write(`
        //         <!DOCTYPE html>
        //         <html>
        //         <head>
        //             <title>${docTitle}</title>
        //             <style>
        //                 @media print {
        //                     @page { margin: 15mm; }
        //                     body { 
        //                         font-family: Arial, sans-serif; 
        //                         margin: 0;
        //                         padding: 0;
        //                         font-size: 12px;
        //                     }
        //                 }
        //                 body {
        //                     font-family: Arial, sans-serif;
        //                     margin: 20px;
        //                     font-size: 12px;
        //                 }
        //                 .print-header {
        //                     text-align: center;
        //                     margin-bottom: 20px;
        //                 }
        //                 .print-header h1 {
        //                     margin: 0 0 5px 0;
        //                     font-size: 20px;
        //                     color: #333;
        //                 }
        //                 .print-header img {
        //                     max-height: 60px;
        //                     margin-bottom: 10px;
        //                 }
        //                 .content-wrapper {
        //                     display: flex;
        //                     justify-content: space-between;
        //                     margin-top: 20px;
        //                 }
        //                 .left-content {
        //                     flex: 1;
        //                     padding-right: 20px;
        //                 }
        //                 .right-content {
        //                     width: 180px;
        //                     text-align: center;
        //                     border-left: 1px solid #ddd;
        //                     padding-left: 20px;
        //                 }
        //                 .right-content img {
        //                     width: 150px;
        //                     height: 150px;
        //                     border: 1px solid #ddd;
        //                     padding: 5px;
        //                     background: white;
        //                 }
        //                 .section {
        //                     margin-bottom: 15px;
        //                 }
        //                 .section-title {
        //                     font-weight: bold;
        //                     font-size: 13px;
        //                     color: #333;
        //                     margin-bottom: 8px;
        //                     padding-bottom: 4px;
        //                     border-bottom: 1px solid #ddd;
        //                 }
        //                 .info-grid {
        //                     display: grid;
        //                     grid-template-columns: repeat(2, 1fr);
        //                     gap: 10px;
        //                 }
        //                 .info-item {
        //                     margin-bottom: 6px;
        //                 }
        //                 .info-label {
        //                     font-weight: 600;
        //                     color: #555;
        //                     font-size: 10px;
        //                     margin-bottom: 2px;
        //                 }
        //                 .info-value {
        //                     color: #222;
        //                     font-size: 11px;
        //                 }
        //                 .footer {
        //                     text-align: center;
        //                     margin-top: 20px;
        //                     padding-top: 10px;
        //                     border-top: 1px solid #ddd;
        //                     font-size: 10px;
        //                     color: #666;
        //                 }
        //                 .badge {
        //                     display: inline-block;
        //                     padding: 2px 6px;
        //                     border-radius: 3px;
        //                     font-size: 10px;
        //                     font-weight: bold;
        //                 }
        //                 .badge-approved {
        //                     background: #28a745;
        //                     color: white;
        //                 }
        //                 .badge-pending {
        //                     background: #ffc107;
        //                     color: #212529;
        //                 }
        //                 .badge-rejected {
        //                     background: #dc3545;
        //                     color: white;
        //                 }
        //                 .token {
        //                     font-family: monospace;
        //                     font-size: 10px;
        //                     background: #f8f9fa;
        //                     padding: 4px;
        //                     border-radius: 3px;
        //                     margin-top: 5px;
        //                 }
        //             </style>
        //         </head>
        //         <body>
        //             <div class="print-header">
        //                 <!-- Replace with your actual logo URL -->
        //                 <img src="https://hostel.rntu.ac.in/frontend/img/main-logo.png" alt="Logo">
        //                 <h1>STUDENT GATE PASS</h1>
        //                 <div style="font-size: 11px; color: #666;">
        //                     ID: ${data.id || 'N/A'} | Generated: ${now.toLocaleString()}
        //                 </div>
        //             </div>
                    
        //             <div class="content-wrapper">
        //                 <!-- Left side: Student and Leave Data -->
        //                 <div class="left-content">
        //                     <!-- Student Information -->
        //                     <div class="section">
        //                         <div class="section-title">STUDENT INFORMATION</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">Student Name</div>
        //                                 <div class="info-value">${data.name || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Room Number</div>
        //                                 <div class="info-value">${data.room_number || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Email</div>
        //                                 <div class="info-value">${data.email || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Mobile</div>
        //                                 <div class="info-value">${data.mobile || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Course</div>
        //                                 <div class="info-value">${data.course || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Department</div>
        //                                 <div class="info-value">${data.department || 'N/A'}</div>
        //                             </div>
        //                         </div>
        //                     </div>
                            
        //                     <!-- Leave Details -->
        //                     <div class="section">
        //                         <div class="section-title">LEAVE DETAILS</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">Leave Type</div>
        //                                 <div class="info-value">${data.type ? data.type.charAt(0).toUpperCase() + data.type.slice(1) : 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Reason</div>
        //                                 <div class="info-value">${data.reason || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">From Date</div>
        //                                 <div class="info-value">${formatDate(data.start_date || data.from_date)}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">To Date</div>
        //                                 <div class="info-value">${formatDate(data.end_date || data.to_date)}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Duration</div>
        //                                 <div class="info-value">${calculateDuration()}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Applied On</div>
        //                                 <div class="info-value">${new Date(data.applied_at || data.applied_on).toLocaleString()}</div>
        //                             </div>
        //                         </div>
        //                     </div>
                            
        //                     <!-- Status -->
        //                     <div class="section">
        //                         <div class="section-title">APPROVAL STATUS</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">HOD Status</div>
        //                                 <div class="info-value">
        //                                     <span class="badge ${data.hod_status === 'approved' ? 'badge-approved' : 
        //                                                     data.hod_status === 'rejected' ? 'badge-rejected' : 
        //                                                     'badge-pending'}">
        //                                         ${data.hod_status || 'Pending'}
        //                                     </span>
        //                                     ${data.hod_remarks ? `<div style="font-size: 9px; margin-top: 2px;">${data.hod_remarks}</div>` : ''}
        //                                 </div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Admin Status</div>
        //                                 <div class="info-value">
        //                                     <span class="badge ${data.admin_status === 'approved' ? 'badge-approved' : 
        //                                                     data.admin_status === 'rejected' ? 'badge-rejected' : 
        //                                                     'badge-pending'}">
        //                                         ${data.admin_status || 'Pending'}
        //                                     </span>
        //                                     ${data.admin_remarks ? `<div style="font-size: 9px; margin-top: 2px;">${data.admin_remarks}</div>` : ''}
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
                        
        //                 <!-- Right side: QR Code -->
        //                 <div class="right-content">
        //                     ${data.qr_code ? `
        //                             <img src="data:image/png;base64,${data.qr_code}" alt="QR Code">
        //                             <div style="margin-top: 10px; font-weight: bold;">SCAN TO VERIFY</div>
        //                         ` : `
        //                             <div style="height: 150px; display: flex; align-items: center; justify-content: center; border: 1px dashed #ccc; color: #999;">
        //                                 QR Code Not Available
        //                             </div>
        //                         `}
                            
        //                     ${data.token ? `
        //                             <div style="margin-top: 15px;">
        //                                 <div style="font-size: 10px; font-weight: bold;">Token</div>
        //                                 <div class="token">${data.token}</div>
        //                             </div>
        //                         ` : ''}
                            
        //                     <div style="margin-top: 15px; font-size: 9px; color: #666;">
        //                         Hostel Timings:<br>
        //                         IN: ${data.hostel_in_time || 'N/A'}<br>
        //                         OUT: ${data.hostel_out_time || 'N/A'}
        //                     </div>
        //                 </div>
        //             </div>
                    
        //             <div class="footer">
        //                 Generated by Leave Management System • ${new Date().toLocaleString()}<br>
        //                 This is an electronically generated document. Valid for verification purposes only.
        //             </div>
                    
                
        //         </body>
        //         </html>
        //     `);


        //     // window.onload = function() {
        //     //     setTimeout(function() {
        //     //         window.print();
        //     //         setTimeout(function() {
        //     //             window.close();
        //     //         }, 100);
        //     //     }, 100);
        //     // };

        //     printWindow.document.close();

        //     // Restore button after print
        //     setTimeout(() => {
        //         printBtn.innerHTML = originalContent;
        //         printBtn.disabled = false;
        //     }, 1000);
        // }



        // function viewReceipt(button) {
        //     const originalButtonHTML = button.innerHTML;
        //     button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Loading...';
        //     button.disabled = true;
            
        //     const leaveId = button.getAttribute("data-id");
        //     const receiptContainer = document.getElementById('receiptDetails');
            
        //     receiptContainer.innerHTML = `
        //         <div class="text-center py-5">
        //             <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
        //             <p class="text-muted">Loading...</p>
        //         </div>
        //     `;
            
        //     const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
        //     receiptModal.show();
            
        //     fetch(`/api/resident/leaves/${leaveId}`, {
        //         method: "GET",
        //         headers: {
        //             'Authorization': `Bearer ${localStorage.getItem('token')}`,
        //             'Accept': 'application/json'
        //         },
        //     })
        //     .then(async res => {
        //         if (!res.ok) throw new Error(`HTTP ${res.status}`);
        //         return res.json();
        //     })
        //     .then(({data}) => {
        //         const formatDate = d => d ? new Date(d).toLocaleDateString('en-IN') : 'N/A';
        //         const formatDateTime = d => d ? new Date(d).toLocaleString('en-IN') : 'N/A';
                
        //         const getStatusBadge = (s) => {
        //             const map = {
        //                 'pending': {class: 'bg-warning text-dark', icon: 'clock'},
        //                 'approved': {class: 'bg-success', icon: 'check-circle'},
        //                 'rejected': {class: 'bg-danger', icon: 'times-circle'}
        //             };
        //             const status = s?.toLowerCase();
        //             const info = map[status] || {class: 'bg-info', icon: 'info-circle'};
        //             return `<span class="badge ${info.class}"><i class="fas fa-${info.icon} me-1"></i>${s || 'N/A'}</span>`;
        //         };
                
        //         receiptContainer.innerHTML = `
        //             <div class="receipt-card">
        //                 <div class="text-center mb-4">
        //                     <h3 class="text-primary mb-2">Gate Pass</h3>
        //                     <p class="text-muted small">ID: #${data.id || ''} • ${formatDateTime(new Date())}</p>
        //                 </div>
                        
        //                 <div class="row">
        //                     <div class="col-md-8">
        //                         <div class="mb-3">
        //                             <h5 class="border-bottom pb-2"><i class="fas fa-user-graduate me-2"></i>Student Info</h5>
        //                             <div class="row g-2">
        //                                 <div class="col-6"><small class="text-muted">Name</small><div>${data.name || 'N/A'}</div></div>
        //                                 <div class="col-6"><small class="text-muted">Room</small><div>${data.room_number || 'N/A'}</div></div>
        //                                 <div class="col-6"><small class="text-muted">Mobile</small><div>${data.mobile || 'N/A'}</div></div>
        //                                 <div class="col-6"><small class="text-muted">Course</small><div>${data.course || 'N/A'}</div></div>
        //                             </div>
        //                         </div>
                                
        //                         <div class="mb-3">
        //                             <h5 class="border-bottom pb-2"><i class="fas fa-calendar-alt me-2"></i>Leave Details</h5>
        //                             <div class="row g-2">
        //                                 <div class="col-6"><small class="text-muted">Type</small><div>${data.type || 'N/A'}</div></div>
        //                                 <div class="col-6"><small class="text-muted">Reason</small><div>${data.reason || 'N/A'}</div></div>
        //                                 <div class="col-6"><small class="text-muted">From</small><div>${formatDate(data.start_date || data.from_date)}</div></div>
        //                                 <div class="col-6"><small class="text-muted">To</small><div>${formatDate(data.end_date || data.to_date)}</div></div>
        //                                 <div class="col-6"><small class="text-muted">Applied</small><div>${formatDateTime(data.applied_at || data.applied_on)}</div></div>
        //                             </div>
        //                         </div>
                                
        //                         ${data.hod_status || data.admin_status ? `
        //                         <div>
        //                             <h5 class="border-bottom pb-2"><i class="fas fa-check-circle me-2"></i>Approval Status</h5>
        //                             <div class="row g-2">
        //                                 <div class="col-6">
        //                                     <small class="text-muted">HOD</small>
        //                                     <div>${getStatusBadge(data.hod_status)}</div>
        //                                     ${data.hod_remarks ? `<small class="text-muted">${data.hod_remarks}</small>` : ''}
        //                                 </div>
        //                                 <div class="col-6">
        //                                     <small class="text-muted">Admin</small>
        //                                     <div>${getStatusBadge(data.admin_status)}</div>
        //                                     ${data.admin_remarks ? `<small class="text-muted">${data.admin_remarks}</small>` : ''}
        //                                 </div>
        //                             </div>
        //                         </div>
        //                         ` : ''}
        //                     </div>
                            
        //                     <div class="col-md-4">
        //                         <div class="border-start ps-3">
        //                             <h5 class="border-bottom pb-2"><i class="fas fa-qrcode me-2"></i>Verification</h5>
        //                             <div class="text-center">
        //                                 ${data.qr_code ? 
        //                                     `<img src="data:image/png;base64,${data.qr_code}" class="img-fluid mb-2" style="max-width:180px;" alt="QR">` : 
        //                                     '<div class="text-muted py-3"><i class="fas fa-qrcode fa-2x"></i><p>No QR Code</p></div>'
        //                                 }
        //                                 ${data.token ? `
        //                                     <div class="small mt-2">
        //                                         <small class="text-muted d-block">Token</small>
        //                                         <code class="bg-light p-1 rounded">${data.token}</code>
        //                                     </div>
        //                                 ` : ''}
        //                                 <p class="small text-muted mt-2">Scan to verify</p>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 </div>
                        
        //                 <div class="mt-3 pt-3 border-top small text-muted">
        //                     <div class="row">
        //                         <div class="col">Hostel Timings: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
        //                         <div class="col text-end">Generated electronically</div>
        //                     </div>
        //                 </div>
        //             </div>
        //         `;
                
        //         button.innerHTML = originalButtonHTML;
        //         button.disabled = false;
                
        //         // Setup token copy
        //         const tokenEl = receiptContainer.querySelector('code');
        //         if (tokenEl) {
        //             tokenEl.style.cursor = 'pointer';
        //             tokenEl.onclick = () => {
        //                 navigator.clipboard.writeText(tokenEl.textContent);
        //                 const original = tokenEl.textContent;
        //                 tokenEl.textContent = 'Copied!';
        //                 setTimeout(() => tokenEl.textContent = original, 1500);
        //             };
        //         }
                
        //         // Setup print
        //         document.getElementById('printReceiptBtn').onclick = () => smartPrint(data);
                
        //     }).catch(err => {
        //         console.error(err);
        //         receiptContainer.innerHTML = `
        //             <div class="text-center py-5">
        //                 <i class="fas fa-exclamation-triangle fa-2x text-danger mb-3"></i>
        //                 <h5 class="text-danger">Error Loading</h5>
        //                 <p class="text-muted">${err.message || 'Please try again'}</p>
        //                 <button class="btn btn-sm btn-outline-primary mt-2" onclick="viewReceipt(this)" data-id="${leaveId}">
        //                     <i class="fas fa-redo me-1"></i> Retry
        //                 </button>
        //             </div>
        //         `;
        //         button.innerHTML = originalButtonHTML;
        //         button.disabled = false;
        //     });
        // }

        // Smart compact print function
        // function smartPrint(data) {
        //     const printBtn = document.getElementById('printReceiptBtn');
        //     const originalHTML = printBtn.innerHTML;
        //     printBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';
        //     printBtn.disabled = true;
            
        //     const format = d => d ? new Date(d).toLocaleDateString('en-IN', {day:'2-digit', month:'short', year:'numeric'}) : 'N/A';
        //     const now = new Date().toLocaleString('en-IN', {day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit'});
            
        //     // Print configuration - control what prints
        //     const printConfig = {
        //         showLogo: true,
        //         showQr: true,
        //         showStudentInfo: true,
        //         showLeaveDetails: true,
        //         showStatus: true,
        //         showFooter: true,
        //         compactMode: true  // Reduced spacing for compact print
        //     };
            
        //     const printWindow = window.open('', '_blank', 'width=800,height=600');
            
        //     printWindow.document.write(`
        //         <!DOCTYPE html>
        //         <html>
        //         <head>
        //             <title>Gate Pass #${data.id || ''}</title>
        //             <style>
        //                 @media print {
        //                     @page { margin: 15mm; }
        //                     body { 
        //                         font-family: Arial, sans-serif; 
        //                         margin: 0; 
        //                         padding: 0;
        //                         font-size: 12px;
        //                         line-height: 1.3;
        //                     }
        //                     .no-print { display: none !important; }
        //                     .print-only { display: block !important; }
        //                 }
        //                 body { 
        //                     font-family: Arial, sans-serif; 
        //                     margin: 20px;
        //                     font-size: 12px;
        //                     line-height: 1.3;
        //                 }
        //                 .header { 
        //                     text-align: center; 
        //                     margin-bottom: ${printConfig.compactMode ? '15px' : '20px'};
        //                     padding-bottom: ${printConfig.compactMode ? '10px' : '15px'};
        //                     border-bottom: 2px solid #000;
        //                 }
        //                 .header img { 
        //                     max-height: 60px; 
        //                     margin-bottom: ${printConfig.compactMode ? '5px' : '10px'};
        //                 }
        //                 .header h1 { 
        //                     margin: 0; 
        //                     font-size: ${printConfig.compactMode ? '18px' : '20px'};
        //                     color: #000;
        //                 }
        //                 .header .subtitle {
        //                     font-size: 11px;
        //                     color: #666;
        //                     margin-top: 3px;
        //                 }
        //                 .content { 
        //                     display: flex; 
        //                     margin: ${printConfig.compactMode ? '15px 0' : '20px 0'};
        //                 }
        //                 .left { 
        //                     flex: 1; 
        //                     padding-right: ${printConfig.compactMode ? '15px' : '20px'};
        //                 }
        //                 .right { 
        //                     width: 160px; 
        //                     text-align: center;
        //                     border-left: 1px solid #ddd;
        //                     padding-left: ${printConfig.compactMode ? '10px' : '15px'};
        //                 }
        //                 .section {
        //                     margin-bottom: ${printConfig.compactMode ? '12px' : '15px'};
        //                     page-break-inside: avoid;
        //                 }
        //                 .section-title {
        //                     font-weight: bold;
        //                     font-size: 13px;
        //                     color: #333;
        //                     margin-bottom: ${printConfig.compactMode ? '6px' : '8px'};
        //                     padding-bottom: ${printConfig.compactMode ? '3px' : '4px'};
        //                     border-bottom: 1px solid #ccc;
        //                 }
        //                 .info-grid {
        //                     display: grid;
        //                     grid-template-columns: repeat(2, 1fr);
        //                     gap: ${printConfig.compactMode ? '8px' : '10px'};
        //                 }
        //                 .info-item {
        //                     margin-bottom: ${printConfig.compactMode ? '5px' : '6px'};
        //                 }
        //                 .info-label {
        //                     font-weight: 600;
        //                     color: #555;
        //                     font-size: 11px;
        //                     margin-bottom: 1px;
        //                 }
        //                 .info-value {
        //                     color: #000;
        //                     font-size: 12px;
        //                 }
        //                 .qr-container {
        //                     margin: ${printConfig.compactMode ? '10px 0' : '15px 0'};
        //                 }
        //                 .qr-container img {
        //                     width: ${printConfig.compactMode ? '130px' : '150px'};
        //                     height: ${printConfig.compactMode ? '130px' : '150px'};
        //                     border: 1px solid #ddd;
        //                     padding: 5px;
        //                     background: white;
        //                 }
        //                 .badge {
        //                     display: inline-block;
        //                     padding: 2px 6px;
        //                     border-radius: 3px;
        //                     font-size: 10px;
        //                     font-weight: bold;
        //                 }
        //                 .badge-approved { background: #28a745; color: white; }
        //                 .badge-pending { background: #ffc107; color: #000; }
        //                 .badge-rejected { background: #dc3545; color: white; }
        //                 .token {
        //                     font-family: monospace;
        //                     font-size: 10px;
        //                     background: #f8f9fa;
        //                     padding: 4px 6px;
        //                     border-radius: 3px;
        //                     margin-top: 5px;
        //                     word-break: break-all;
        //                 }
        //                 .footer {
        //                     text-align: center;
        //                     margin-top: ${printConfig.compactMode ? '15px' : '20px'};
        //                     padding-top: ${printConfig.compactMode ? '8px' : '10px'};
        //                     border-top: 1px solid #ddd;
        //                     font-size: 10px;
        //                     color: #666;
        //                 }
        //                 .watermark {
        //                     opacity: 0.03;
        //                     position: fixed;
        //                     top: 50%;
        //                     left: 50%;
        //                     transform: translate(-50%, -50%) rotate(-45deg);
        //                     font-size: 100px;
        //                     font-weight: bold;
        //                     color: #000;
        //                     z-index: -1;
        //                 }
        //             </style>
        //         </head>
        //         <body>
        //             ${printConfig.showLogo ? `
        //             <div class="header">
        //                 <img src="https://hostel.rntu.ac.in/frontend/img/main-logo.png" alt="Logo">
        //                 <h1>GATE PASS</h1>
        //                 <div class="subtitle">ID: ${data.id || ''} | ${now}</div>
        //             </div>
        //             ` : `<h1 class="header">GATE PASS #${data.id || ''}</h1>`}
                    
        //             <div class="content">
        //                 ${printConfig.showStudentInfo || printConfig.showLeaveDetails ? `
        //                 <div class="left">
        //                     ${printConfig.showStudentInfo ? `
        //                     <div class="section">
        //                         <div class="section-title">STUDENT INFORMATION</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">Name</div>
        //                                 <div class="info-value">${data.name || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Room No.</div>
        //                                 <div class="info-value">${data.room_number || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Mobile</div>
        //                                 <div class="info-value">${data.mobile || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Course</div>
        //                                 <div class="info-value">${data.course || 'N/A'}</div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                     ` : ''}
                            
        //                     ${printConfig.showLeaveDetails ? `
        //                     <div class="section">
        //                         <div class="section-title">LEAVE DETAILS</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">Type</div>
        //                                 <div class="info-value">${data.type || 'N/A'}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">From</div>
        //                                 <div class="info-value">${format(data.start_date || data.from_date)}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">To</div>
        //                                 <div class="info-value">${format(data.end_date || data.to_date)}</div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Applied</div>
        //                                 <div class="info-value">${format(data.applied_at || data.applied_on)}</div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                     ` : ''}
                            
        //                     ${printConfig.showStatus && (data.hod_status || data.admin_status) ? `
        //                     <div class="section">
        //                         <div class="section-title">STATUS</div>
        //                         <div class="info-grid">
        //                             <div class="info-item">
        //                                 <div class="info-label">HOD</div>
        //                                 <div class="info-value">
        //                                     <span class="badge ${data.hod_status === 'approved' ? 'badge-approved' : 
        //                                                        data.hod_status === 'rejected' ? 'badge-rejected' : 
        //                                                        'badge-pending'}">
        //                                         ${data.hod_status || 'Pending'}
        //                                     </span>
        //                                 </div>
        //                             </div>
        //                             <div class="info-item">
        //                                 <div class="info-label">Admin</div>
        //                                 <div class="info-value">
        //                                     <span class="badge ${data.admin_status === 'approved' ? 'badge-approved' : 
        //                                                        data.admin_status === 'rejected' ? 'badge-rejected' : 
        //                                                        'badge-pending'}">
        //                                         ${data.admin_status || 'Pending'}
        //                                     </span>
        //                                 </div>
        //                             </div>
        //                         </div>
        //                     </div>
        //                     ` : ''}
        //                 </div>
        //                 ` : ''}
                        
        //                 ${printConfig.showQr ? `
        //                 <div class="right">
        //                     <div class="section">
        //                         <div class="section-title">VERIFICATION</div>
        //                         <div class="qr-container">
        //                             ${data.qr_code ? 
        //                                 `<img src="data:image/png;base64,${data.qr_code}" alt="QR Code">` : 
        //                                 `<div style="height:150px; display:flex; align-items:center; justify-content:center; border:1px dashed #ccc; color:#999;">
        //                                     No QR Code
        //                                 </div>`
        //                             }
        //                         </div>
        //                         ${data.token ? `
        //                             <div>
        //                                 <div class="info-label">Token</div>
        //                                 <div class="token">${data.token}</div>
        //                             </div>
        //                         ` : ''}
        //                         <div style="margin-top:10px; font-size:11px; font-weight:bold;">SCAN TO VERIFY</div>
        //                     </div>
        //                 </div>
        //                 ` : ''}
        //             </div>
                    
        //             ${printConfig.showFooter ? `
        //             <div class="footer">
        //                 <div>Generated by Leave Management System • ${new Date().toLocaleString('en-IN')}</div>
        //                 <div>Hostel: IN ${data.hostel_in_time || 'N/A'} | OUT ${data.hostel_out_time || 'N/A'}</div>
        //                 <div style="margin-top:2px; font-size:9px;">Electronically generated • Valid with QR verification</div>
        //             </div>
        //             ` : ''}
                    
        //             <div class="watermark print-only">GATE PASS</div>
                    
        //             <div class="no-print" style="text-align:center; margin-top:20px;">
        //                 <button onclick="window.print()" style="padding:8px 20px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        //                     Print Now
        //                 </button>
        //                 <button onclick="window.close()" style="padding:8px 20px; background:#6c757d; color:white; border:none; border-radius:4px; margin-left:10px; cursor:pointer;">
        //                     Close
        //                 </button>
        //             </div>
                    
                    
        //         </body>
        //         </html>
        //     `);
            

        //                 // setTimeout(() => window.print(), 300);
        //                 // window.onafterprint = () => setTimeout(() => window.close(), 100);
                
        //     printWindow.document.close();
            
        //     // Restore button
        //     setTimeout(() => {
        //         printBtn.innerHTML = originalHTML;
        //         printBtn.disabled = false;
        //     }, 1000);
        // }

        function getStatusBadge(status) {
            if (!status) return '<span class="badge bg-secondary">N/A</span>';

            switch (status.toLowerCase()) {
                case 'pending':
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                case 'approved':
                    return '<span class="badge bg-success">Approved</span>';
                case 'rejected':
                    return '<span class="badge bg-danger">Rejected</span>';
                case 'cancelled':
                    return '<span class="badge bg-secondary">Cancelled</span>';
                default:
                    return `<span class="badge bg-info">${status}</span>`;
            }
        }
    </script>

    <script>
        function truncateWithExpand(text, limit = 80) {
            if (!text) return 'N/A';
            text = text.toString();
            if (text.length <= limit) return text;

            const truncated = text.substring(0, limit) + '...';
            // Use encodeURIComponent to safely store full text
            return `
                <span class="truncated" data-full="${encodeURIComponent(text)}" data-limit="${limit}">
                    ${truncated}
                    <span class="expand" style="color:blue;cursor:pointer">more</span>
                </span>
            `;
        }

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("expand")) {
                const span = e.target.closest(".truncated");
                const fullText = decodeURIComponent(span.getAttribute("data-full"));
                span.innerHTML = fullText +
                    ' <span class="collapse" style="color:red;cursor:pointer">less</span>';
            }
            if (e.target.classList.contains("collapse")) {
                const span = e.target.closest(".truncated");
                const fullText = decodeURIComponent(span.getAttribute("data-full"));
                const limit = span.getAttribute("data-limit");
                const truncated = fullText.substring(0, limit) + '...';
                span.innerHTML = truncated +
                    ' <span class="expand" style="color:blue;cursor:pointer">more</span>';
            }
        });


        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    customClass: 'square-tooltip'
                });
            });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script>
        // Example: token from API response 
        const token = "abc123securetoken";

        // Verification URL 
        const verifyUrl = `https://yourdomain.com/leave/verify/${token}`;

        Generate QR new QRCode(document.getElementById("qrcode"), {
            text: verifyUrl,
            width: 128,
            height: 128
        });
    </script> --}}
@endpush
