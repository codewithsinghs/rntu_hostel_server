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
@endsection

@push('scripts')

{{-- $(document).ready(function() {
    let table = $('#leaveTable').DataTable({
        ajax: '/api/resident/leaves',
        
        columns: [
            { data: 'id' },
            { data: 'employee_name' },
            { data: 'type' },
            { data: 'from_date' },
            { data: 'to_date' },
            { data: 'status' },
            { data: null, render: function(data) {
                return `
                  <button class="btn btn-sm btn-info edit" data-id="${data.id}">Edit</button>
                  <button class="btn btn-sm btn-danger delete" data-id="${data.id}">Delete</button>
                `;
            }}
        ]
    });

    // Open modal for new leave
    $('#btnAdd').click(function() {
        $('#leaveForm')[0].reset();
        $('#leaveId').val('');
        $('#leaveModal').modal('show');
    });

    // Edit leave
    $('#leaveTable').on('click', '.edit', function() {
        let id = $(this).data('id');
        $.get(`/api/leaves/${id}`, function(res) {
            $('#leaveId').val(res.id);
            $('#type').val(res.type);
            $('#from_date').val(res.from_date);
            $('#to_date').val(res.to_date);
            $('#leaveModal').modal('show');
        });
    });

    // Save leave (create/update)
    $('#leaveForm').submit(function(e) {
        e.preventDefault();
        let id = $('#leaveId').val();
        let payload = {
            type: $('#type').val(),
            from_date: $('#from_date').val(),
            to_date: $('#to_date').val()
        };
        let method = id ? 'PUT' : 'POST';
        let url = id ? `/api/leaves/${id}` : '/api/leaves';

        $.ajax({
            url: url,
            method: method,
            data: payload,
            success: function() {
                $('#leaveModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    // Delete leave
    $('#leaveTable').on('click', '.delete', function() {
        let id = $(this).data('id');
        if(confirm('Are you sure?')) {
            $.ajax({
                url: `/api/leaves/${id}`,
                method: 'DELETE',
                success: function() {
                    table.ajax.reload();
                }
            });
        }
    });
}); --}}

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

        function viewReceipt(button) {
            // const row = button.closest("tr");
            // const request = JSON.parse(row.getAttribute("data-request").replace(/&apos;/g, "'"));

            const row = button.closest("tr");

            // const leaveId = row.getAttribute("data-id"); // ✅ get ID from row
            const leaveId = button.getAttribute("data-id");

            // try {
            const apiUrl = `/api/resident/leaves/${leaveId}`;

            fetch(apiUrl, {
                    method: "GET",
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    },
                })
                // const response = await fetch(`/api/leaves/${leaveId}`, {
                //     headers: {
                //         'Accept': 'application/json'
                //     }
                // });
                // if (!response.ok) {
                //     throw new Error(`Server error: ${response.status}`);
                // }
                // const request = await response.json();
                // const data = request.data; // because we wrapped in success()
                .then(res => res.json()).then(response => {
                    const data = response.data;

                    console.log("Fetched leave data:", data);
                    // Build professional gate pass layout
                    const receiptDetails = `
                                <div class="text-center mb-3">
                                    <!-- Logo 
                                    <img src="/images/logo.png" alt="Institute Logo" style="height:80px;"> -->
                                    <!-- Title -->
                                    <h2 class="mt-2">Student Gate Pass</h2>
                                </div>

                                <div class="card shadow-sm border-primary">
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Left column: Student Info -->
                                            <div class="col-md-8">
                                                <p><strong>Student Name:</strong> ${data.name ?? 'N/A'}</p>
                                                <p><strong>Room No.:</strong> ${data.room_number ?? 'N/A'}</p>
                                                <p><strong>Hostel IN Time:</strong> ${data.hostel_in_time ?? 'N/A'}</p>
                                                <p><strong>Hostel OUT Time:</strong> ${data.hostel_out_time ?? 'N/A'}</p>
                                                <p><strong>Date:</strong> ${data.hostel_out_time ?? 'N/A'}</p>
                                                <p><strong>Purpose:</strong> ${data.hostel_out_time ?? 'N/A'}</p>
                                                <p><strong>Email:</strong> ${data.email ?? 'N/A'}</p>
                                                <p><strong>Mobile:</strong> ${data.mobile ?? 'N/A'}</p>
                                                <p><strong>Course:</strong> ${data.course ?? 'N/A'}</p>
                                                <p><strong>Department:</strong> ${data.department ?? 'N/A'}</p>
                                                <p><strong>From Date:</strong> ${data.from_date ?? 'N/A'}</p>
                                                <p><strong>To Date:</strong> ${data.to_date ?? 'N/A'}</p>
                                                <p><strong>Applied On:</strong> ${data.applied_on ?? 'N/A'}</p>
                                                <p><strong>Status:</strong> ${data.status ?? 'N/A'}</p>
                                                <p><strong>Remarks:</strong> ${data.remarks ?? 'N/A'}</p>
                                                <p><strong>Action Date:</strong> ${data.action_at ?? 'N/A'}</p>
                                            </div>

                                            <!-- Right column: QR -->
                                            <div class="col-md-4 text-center" style="border-left:1px solid #ddd;">
                                                <div id="qrcode"></div>
                                                ${data.token ? `<p class="mt-2 text-muted">Scan to verify</p>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                    document.getElementById('receiptDetails').innerHTML = receiptDetails;

                    // Show modal
                    var receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
                    receiptModal.show();

                    // Inject QR (from API base64 or client-side)
                    if (data.qr_code) {
                        document.getElementById("qrcode").innerHTML =
                            `<img src="data:image/png;base64,${data.qr_code}" width="150" height="150" />`;
                    }

                    // } catch (error) {
                    //     console.error("Failed to load leave:", error);
                    //     alert("Unable to load leave details. Please try again.");
                    // }
                }).catch(err => {
                    console.error("Error fetching leave:", err);
                    alert("Unable to load leave details.");
                });

            // } catch (error) {
            //     console.error("Failed to load leave:", error);
            //     alert("Unable to load leave details. Please try again.");
            // }

        }




        document.getElementById('printReceiptBtn')?.addEventListener('click', function() {
            const receipt = document.getElementById('receiptDetails');

            const leftContent = receipt.querySelector('.col-md-8')?.innerHTML || '';
            const qrContent = receipt.querySelector('#qrcode')?.innerHTML || '';

            const printWindow = window.open('', '', 'width=800,height=800');

            // Set a custom title — this becomes the suggested filename when saving as PDF
            // const docTitle = `GatePass_${new Date().toISOString().slice(0,10)}`;
            const now = new Date();
            const docTitle =
                `GatePass_${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(now.getDate()).padStart(2,'0')}_${String(now.getHours()).padStart(2,'0')}${String(now.getMinutes()).padStart(2,'0')}${String(now.getSeconds()).padStart(2,'0')}`;

            printWindow.document.write(`
                <html>
                <head>
                    <title>${docTitle}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 30px; }
                        .header { text-align: center; margin-bottom: 20px; }
                        .header img { height: 80px; }
                        .header h2 { margin-top: 50px; margin-bottom: 50px; font-size: 24px; text-transform: uppercase; }
                        .content { display: flex; justify-content: space-between; align-items: flex-start; }
                        .left { width: 65%; }
                        .right { width: 30%; text-align: center; }
                        .right img { width: 150px; height: 150px; }
                        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <img src="https://hostel.rntu.ac.in/frontend/img/main-logo.png" alt="Institute Logo" max-width="250">
                        <h2 class>Student Gate Pass</h2>
                    </div>
                    <div class="content">
                        <div class="left">${leftContent}</div>
                        <div class="right">${qrContent}<p class="mt-2 text-muted">Scan to verify</p></div>
                    </div>
                    <div class="footer">
                        Generated by Leave Management System • ${new Date().toLocaleString()}
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.print();
        });

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
