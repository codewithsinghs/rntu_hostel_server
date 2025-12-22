@extends('resident.layout')

@section('content')
    <!-- Data Card -->
    <style>
        .status-table td {
            padding: 10px 0px;
            text-align: center;
        }
    </style>
    <!-- Accessories Overview -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs">Accessories Overview</div>

                <!-- Accessories Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>My Accessories</p>
                            <h3 id="summary-accessories">0</h3>
                            <p class="subline">Total accessories issued to you</p>
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Requests</p>
                            <h3 id="summary-pending-requests">0</h3>
                            <p class="subline">Awaiting approval from warden</p>
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Approved Requests</p>
                            <h3 id="summary-approved-requests">0</h3>
                            <p class="subline">Successfully approved and issued</p>
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Payments</p>
                            <h3 id="summary-pending-payments">₹0</h3>
                            <p class="subline">Payment Pending for accessories.</p>
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
                <div id="responseMessage" class="mt-3"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#accessoryRequestCollapse" aria-expanded="false"
                    aria-controls="accessoryRequestCollapse">

                    <span class="breadcrumbs">Request New / Replacement of Accessory</span>
                    <span class="btn btn-primary">Accessory Request</span>

                </button>


                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="accessoryRequestCollapse">
                    <!-- Form -->

                    <form id="accessoryForm">

                        @csrf

                        <div class="inpit-boxxx">

                            <span class="input-set">

                                <label for="accessory_head_id" class="form-label">Select Accessory</label>
                                <select class="form-select" id="accessory_head_id" name="accessory_head_id" required>
                                    <option selected value="">Select an accessory</option>
                                </select>
                            </span>

                            {{-- <span class="input-set">
                                <label for="SelectHostel">Quantity</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Quantity</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </span> --}}

                            {{-- <span class="input-set">
                                <label for="SelectHostel">Request Type</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Request Type</option>
                                    <option value="New Issue">New Issue</option>
                                    <option value="Replacement">Replacement</option>
                                </select>
                            </span> --}}

                            <span class="input-set">
                                <label for="duration">Duration</label>
                                <select class="form-select" id="duration" name="duration" required
                                    aria-label="Default select example">
                                    <option selected>Select Duration</option>
                                    <option value="1 Month">1 Month</option>
                                    <option value="3 Months">3 Months</option>
                                    <option value="6 Months">6 Months</option>
                                    <option value="1 Year">1 Year</option>
                                </select>
                            </span>

                        </div>

                        {{-- <div class="reason">
                            <label for="Reason">Reason (optional)</label>
                            <textarea name="Purpose" id="Purpose" placeholder="Reason"></textarea>
                        </div>


                        <div class="reason">
                            <label for="photo" class="form-label">Upload Supporting Photo/Document (Optional):</label>
                            <input type="file" id="photo" name="photo" accept="image/*">
                        </div> --}}

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
                <div class="breadcrumbs"><a>Recent Requests List</a></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Accessory</th>
                                <th>Date</th>
                                <th>Price (INR)</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Status</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>

                        <tbody id="recent-requests-body">
                            <tr>
                                <td colspan="11" style="text-align:center; padding:20px;">
                                    Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        /* ---------------------------------------------------------
                               ✅ GLOBAL CONFIG — Reusable for all API calls
                            --------------------------------------------------------- */
        const API = {
            token: localStorage.getItem('token'),
            headers: {
                "Authorization": `Bearer ${localStorage.getItem('token')}`,
                "Accept": "application/json",
                "Content-Type": "application/json"
            }
        };

        function showMessage(target, message, type = "success") {
            document.getElementById(target).innerHTML =
                `<div class="alert alert-${type}">${message}</div>`;
        }

        /* ---------------------------------------------------------
           ✅ LOAD ACCESSORIES (Dropdown)
        --------------------------------------------------------- */
        function loadActiveAccessories() {
            $.ajax({
                url: "/api/resident/accessories/active",
                method: "GET",
                headers: API.headers,
                success: function(response) {
                    const select = $("#accessory_head_id");
                    select.html('<option value="">Select an accessory</option>');

                    response.data.forEach(item => {
                        const name = item.accessory_head?.name ?? "Unnamed";
                        select.append(
                            `<option value="${item.accessory_head_id}">
                        ${name} - ₹${item.price} / month
                    </option>`
                        );
                    });
                },
                error: function() {
                    showMessage("responseMessage", "Error fetching accessories.", "danger");
                }
            });
        }

        /* ---------------------------------------------------------
           ✅ SUBMIT ACCESSORY REQUEST FORM
        --------------------------------------------------------- */
        function handleAccessoryForm() {
            $("#accessoryForm").on("submit", function(e) {
                e.preventDefault();

                const accessoryHeadId = $("#accessory_head_id").val();
                const duration = $("#duration").val();

                if (!accessoryHeadId) {
                    showMessage("responseMessage", "Please select an accessory.", "danger");
                    return;
                }

                $.ajax({
                    url: "/api/resident/accessories",
                    method: "POST",
                    headers: API.headers,
                    data: JSON.stringify({
                        accessory_head_id: accessoryHeadId,
                        duration: duration
                    }),
                    success: function(response) {
                        showMessage("responseMessage", response.message, "success");
                        $("#accessoryForm")[0].reset();
                        loadRecentRequests(); // ✅ Refresh table after submission
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message ?? "Error adding accessory.";
                        showMessage("responseMessage", msg, "danger");
                    }
                });
            });
        }

        /* ---------------------------------------------------------
           ✅ LOAD RECENT ACCESSORY REQUESTS (Table)
        --------------------------------------------------------- */
        function loadRecentRequests() {
            fetch("/api/resident/recent-accessory", {
                    headers: API.headers
                })
                .then(res => res.json())
                .then(response => {

                    // ✅ Update Summary Cards
                    if (response.summary) {
                        document.getElementById("summary-accessories").innerText = response.summary.accessory_count;
                        document.getElementById("summary-pending-requests").innerText = response.summary
                            .pending_requests;
                        document.getElementById("summary-approved-requests").innerText = response.summary
                            .approved_requests;
                        document.getElementById("summary-pending-payments").innerText = "₹" + response.summary
                            .pending_payments;
                    }
                    const tbody = document.getElementById("recent-requests-body");
                    tbody.innerHTML = "";

                    if (!response.data || response.data.length === 0) {
                        tbody.innerHTML = `
                    <tr>
                        <td colspan="11" class="text-center py-3">
                            No recent requests found.
                        </td>
                    </tr>`;
                        return;
                    }

                    response.data.forEach((item, index) => {
                        tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.accessory_name}</td>
                        <td>${item.created_at}</td>
                        <td>${item.price}</td>
                        <td>${item.month !== null ? item.month + " Month" : calculateMonths(item.from_date, item.to_date)}</td>
                        <td>${item.total_amount}</td>
                        <td>${item.from_date}</td>
                        <td>${item.to_date}</td>
                     
                        <td>
                            <span style="color:${getStatusColor(item.status)}; font-weight:bold;">
                                ${item.status}
                            </span>
                        </td>

                        
                    </tr>`;
                    });
                })
                .catch(() => {
                    document.getElementById("recent-requests-body").innerHTML = `
                <tr>
                    <td colspan="11" class="text-center py-3 text-danger">
                        Failed to load data.
                    </td>
                </tr>`;
                });
        }

        function calculateMonths(fromDate, toDate) {
            if (!fromDate || !toDate) return "-";

            const start = new Date(fromDate);
            const end = new Date(toDate);

            let months =
                (end.getFullYear() - start.getFullYear()) * 12 +
                (end.getMonth() - start.getMonth());

            return months + " Month";
        }

        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case "paid":
                    return "green";

                case "pending":
                    return "orange";

                case "partial-paid":
                case "partial":
                    return "#b8860b"; // dark golden

                case "cancelled":
                case "canceled":
                    return "red";

                default:
                    return "black"; // fallback
            }
        }


        // <td>
        //                     <button class="view-btn" onclick="viewDetail(${item.id})">
        //                         View Detail
        //                     </button>
        //                 </td>
        /* ---------------------------------------------------------
           ✅ VIEW DETAIL REDIRECT
        --------------------------------------------------------- */
        function viewDetail(id) {
            window.location.href = `/resident/request-detail/${id}`;
        }

        /* ---------------------------------------------------------
           ✅ INITIALIZE EVERYTHING
        --------------------------------------------------------- */
        document.addEventListener("DOMContentLoaded", function() {
            loadActiveAccessories();
            handleAccessoryForm();
            loadRecentRequests();
        });
    </script>
@endpush
