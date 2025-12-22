@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <!-- breadcrumbs -->
        <div class="breadcrumbs"><a href="">Alerts & Updates</a></div>
        <!-- Popup Btn -->
        <span>
            <button class="add-btn" type="button" data-bs-toggle="modal" data-bs-target="#Addnotification">+ Add
                Alert / Notification</button>
        </span>
    </div>

    <!-- Recent Updates List Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Recent Updates List</a></div>

                <div class="overflow-auto">

                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Alert Title</th>
                                <th>Message / Description</th>
                                <th>Type of Alert</th>
                                <th>Target Audience</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Priority Level</th>
                                <th>Attachment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Water Supply Maintenance</td>
                                <td>Water will be unavailable from 10:00 AM to 2:00 PM due to maintenance.</td>
                                <td>Maintenance / General</td>
                                <td>All Students</td>
                                <td>18-08-2025</td>
                                <td>18-08-2025</td>
                                <td>Medium</td>
                                <td><a href="#">maintenance_notice.pdf</a></td>
                                <td style="color: green; font-weight: bold;">Active</td>
                                <td>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewnotification">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>2</td>
                                <td>Fee Payment Reminder</td>
                                <td>Mess fee for August is due by 15 Aug. Late fee applies after due date.</td>
                                <td>Reminder</td>
                                <td>Specific Hostel (A-Block)</td>
                                <td>12-08-2025</td>
                                <td>15-08-2025</td>
                                <td>High</td>
                                <td>—</td>
                                <td style="color: green; font-weight: bold;">Active</td>
                                <td>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewnotification">View</button>
                                </td>
                            </tr>

                            <tr>
                                <td>3</td>
                                <td>Fire Drill</td>
                                <td>Mandatory mock fire drill at 5:00 PM. Assemble at the main courtyard.</td>
                                <td>Urgent / Emergency</td>
                                <td>All Students & Staff</td>
                                <td>10-08-2025</td>
                                <td>10-08-2025</td>
                                <td>High</td>
                                <td><a href="#">evacuation_map.png</a></td>
                                <td style="color: red; font-weight: bold;">Expired</td>
                                <td>
                                    <button class="view-btn" data-bs-toggle="modal"
                                        data-bs-target="#viewnotification">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </section>


    <!-- Recent Updates List Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Recent Updates List</a></div>

                <div class="overflow-auto">

                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Message From</th>
                                <th>Message</th>
                                <th>From Date</th>
                                <th>To Date</th>
                            </tr>
                        </thead>
                        <tbody id="noticesTable">
                            <tr>
                                <td colspan="5" class="text-center">Loading notices...</td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </section>

    <!-- All Popup -->

    <!--  popup List -->
    <div class="modal fade" id="RequestAccessories" tabindex="-1" aria-labelledby="RequestAccessoriesLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Payement</div>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Close
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!--  Create Status -->
    <div class="modal fade" id="Addnotification" tabindex="-1" aria-labelledby="AddnotificationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Create Alert / Notification</div>
                    </div>

                    <div id="messageContainer"></div>

                    <form id="createNoticeForm">

                        <div class="middle">

                            <span class="input-set">
                                <label for="DesiredRoom">Type of Alert / Notification</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option value="Important" selected>Important </option>
                                    <option value="Payments">Payments </option>
                                    <option value="Fee">Fee </option>
                                    <option value="Subscription">Subscription </option>
                                    <option value="Urgent">Urgent </option>
                                    <option value="Hostel">Hostel </option>
                                    <option value="Holidays">Holidays </option>
                                    <option value="Events">Events </option>
                                    <option value="Reminder">Reminder </option>
                                    <option value="Information">Information </option>
                                    <option value="General">General </option>
                                    <option value="Room Update">Room Update </option>
                                    <option value="Other">Other </option>

                                </select>
                            </span>

                            <span class="input-set">
                                <label for="AlertTitle">Alert Title</label>
                                <input type="text" id="AlertTitle" name="AlertTitle" placeholder="Enter Title">
                            </span>

                        </div>
        
                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="message_from">Message From</label>
                                <textarea id="message_from" name="message_from" required
                                    placeholder="Admin"></textarea>
                            </span>
                        </div>

                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="3" required></textarea>
                            </span>
                        </div>


                        <div class="middle mt-3">

                            <span class="input-set">
                                <label for="TargetAudience">Target Audience</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option value="All" selected>All </option>
                                    <option value="Students">Residents </option>
                                    <option value="Staff">Staff </option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="TargetAudience">Target Hostel</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option value="All" selected>All Hostel</option>
                                    <option value="Hostel 1">Hostel 1</option>
                                    <option value="Hostel 2">Hostel 2</option>
                                    <option value="Hostel 3">Hostel 3</option>
                                    <option value="Hostel 4">Hostel 4</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="PriorityLevel">Priority Level</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option value="All" selected>Select Priority</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="Attachment">Attachment (Optional)</label>
                                <input type="file" id="Attachment" name="Attachment">
                            </span>

                            <span class="input-set">
                                <label for="from_date">From Date</label>
                                <input type="date" id="from_date" name="from_date" required>
                            </span>

                            <span class="input-set">
                                <label for="to_date">To Date</label>
                                <input type="date" id="to_date" name="to_date" required>
                            </span>


                        </div>


                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel </button>
                            <button type="button" class="orange"> Save as Draff </button>
                            <button type="submit" class="green"> Publish </button>
                        </div>

                    </form>

                </div>

            </div>
        </div>
    </div>

    <!--  View Alert Details -->
    <div class="modal fade" id="viewnotification" tabindex="-1" aria-labelledby="viewnotificationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Alert / Notification Details</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="Typeof">Type of Alert / Notification</label>
                            <input type="text" id="Typeof" name="Typeof" placeholder="Subscription" disabled>
                        </span>

                        <span class="input-set">
                            <label for="AlertTitle">Alert Title</label>
                            <input type="text" id="AlertTitle" name="AlertTitle" placeholder="Water Supply Maintenance"
                                disabled>
                        </span>

                    </div>

                    <div class="full-width-i">
                        <span class="input-set">
                            <label for="Message/Description">Message / Description</label>
                            <textarea id="Message/Description" name="Message/Description"
                                placeholder="Detailed content of the alert/notification." disabled></textarea>
                        </span>
                    </div>

                    <div class="middle mt-3">

                        <span class="input-set">
                            <label for="TargetAudience">Target Audience</label>
                            <input type="text" id="TargetAudience" name="TargetAudience" placeholder="Residents" disabled>
                        </span>

                        <span class="input-set">
                            <label for="TargetHostel">Target Hostel</label>
                            <input type="text" id="TargetHostel" name="TargetHostel" placeholder="All Hostel" disabled>
                        </span>

                        <span class="input-set">
                            <label for="Status">Status</label>
                            <input type="text" class="status-leave green" id="Status" name="Status" placeholder="Active"
                                disabled>
                        </span>

                        <span class="input-set">
                            <label for="Status">Priority Level</label>
                            <input type="text" class="status-leave red" id="Status" name="Status" placeholder="High"
                                disabled>
                        </span>

                        <span class="input-set">
                            <label for="Attachment">Attachment (Optional)</label>
                            <input type="text" id="Attachment" name="Attachment" placeholder="pdf.pdf">
                        </span>

                        <span class="input-set">
                            <label for="StartDate">Start Date</label>
                            <input type="text" id="StartDate" name="StartDate" placeholder="20/02/25" disabled>
                        </span>

                        <span class="input-set">
                            <label for="EndDate">End Date</label>
                            <input type="text" id="EndDate" name="EndDate" placeholder="20/02/25" disabled>
                        </span>

                    </div>


                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Close </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchNotices(); // Fetch notices when page loads

            function fetchNotices() {
                fetch("{{ url('/api/admin/notices') }}", {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'token': localStorage.getItem('token'),
                        'auth-id': localStorage.getItem('auth-id')
                    }
                })
                    .then(response => response.json())
                    .then(response => {
                        console.log("Raw response:", response); // Debug line (optional)
                        const notices = response.data || []; // Safely extract notices array

                        let tableBody = document.getElementById("noticesTable");
                        tableBody.innerHTML = ""; // Clear previous content

                        if (!Array.isArray(notices) || notices.length === 0) {
                            tableBody.innerHTML = `<tr><td colspan="5" class="text-center">No notices found.</td></tr>`;
                            return;
                        }

                        // Populate the table with notices
                        notices.forEach((notice, index) => {
                            tableBody.innerHTML += `
                                                                                                <tr>
                                                                                                    <td>${index + 1}</td>
                                                                                                    <td>${notice.message_from}</td>
                                                                                                    <td>${notice.message}</td>
                                                                                                    <td>${notice.from_date}</td>
                                                                                                    <td>${notice.to_date}</td>
                                                                                                </tr>
                                                                                            `;
                        });

                        // Datatable
                        InitializeDatatable();

                    })
                    .catch(error => {
                        console.error("Error fetching notices:", error);
                        document.getElementById("noticesTable").innerHTML = `
                                                                                            <tr><td colspan="5" class="text-center text-danger">Failed to load notices.</td></tr>`;
                    });
            }
        });
    </script>


    <!-- create noties page -->

    <script>
        document.getElementById("createNoticeForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            let formData = {
                message_from: document.getElementById("message_from").value,
                message: document.getElementById("message").value,
                from_date: document.getElementById("from_date").value,
                to_date: document.getElementById("to_date").value
            };

            fetch("{{ url('/api/admin/notices') }}", {  // Directly calling the API
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    'token': localStorage.getItem('token'),
                    'auth-id': localStorage.getItem('auth-id')
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        window.location.href = "{{ url('/admin/notices') }}"; // Redirect to Notices List
                    } else {
                        alert("Failed to create notice.");
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while creating the notice.");
                });
        });
    </script>
@endpush