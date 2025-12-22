@extends('resident.layout')

@section('content')

    <head>
        <title>Resident Panel</title>
    </head>

    <!-- Dashboard content -->
    {{-- <section class="dashboard">
        <div class="dashboard-content-top-wrapper">
            <div class="hostel-overview w-100"> --}}
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <!-- breadcrumbs -->
                <div class="breadcrumbs">Overview
                    <!-- <h3 id="universityName"></h3> -->
                </div>

                <!-- Room Overview -->
                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Hostel Name</p>
                            <h3 id="hostelName"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Hostel Management.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Room Number</p>
                            <h3 id="roomNumber"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/livingroom.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Floor Number</p>
                            <h3 id="floorNumber"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/floor.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Bed Number</p>
                            <h3 id="bedNumber"></h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <!-- Graph Placeholder -->
                <div class="chart">
                    <h4>Student Attendance Chart</h4>
                    <small>Hostel Attendance Overview (Monthly)</small>
                    <div class="graph-placeholder">
                        <div id="student-registration-chart" class="graph-container"></div>
                    </div>
                </div>

            </div>

            <!-- Pending Requests / Status -->
            {{-- <div class="student-info">
                <div class="pending-requests">
                    <div class="pending-requests-header">
                        <h4>Pending Requests</h4>
                        <a href="#">View</a>
                    </div>
                    <p class="pending-requests-text">Youre All Request</p>
                    <div class="card-d-box">
                        <div class="overflow-auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Request For</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="student-name">Accessory Request</div>
                                        </td>
                                        <td>
                                            <button class="green-btn" disabled>Approved</button>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-btn">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="student-name">Room Cleaning Request</div>
                                        </td>
                                        <td>
                                            <button class="green-btn" disabled>Approved</button>
                                        </td>
                                        <td>
                                            <a href="#" class="edit-btn">View</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="student-updates">
                    <div class="pending-requests-header">
                        <h4>Check In and Out Updates</h4>
                        <a href="#">View</a>
                    </div>
                    <p class="pending-requests-text">
                        Check In and Out Status from Campus
                    </p>
                    <div class="card-d-box">
                        <div class="overflow-auto">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Name</th>
                                        <th>Check Out</th>
                                        <th>Check In</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="student-name">
                                                Rajat
                                            </div>
                                        </td>
                                        <td>Out 12:30</td>
                                        <td>In 08:30 </td>
                                        <td>
                                            <a href="#" class="edit-btn">View</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>
                                            <div class="student-name">
                                                Amresh
                                            </div>
                                        </td>
                                        <td>Out 12:30</td>
                                        <td>In 08:30 </td>
                                        <td>
                                            <a href="#" class="edit-btn">View</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        <!-- New Section  -->
        <div class="hostel-status-section">
            <h3 class="hostel-status-title">Student Status card</h3>
            <div class="hostel-status-container">
                <!--  Stay Details -->
                <div class="hostel-progress-card">
                    <div class="progress-circle">
                        <svg>
                            <circle cx="60" cy="60" r="50"></circle>
                            <circle cx="60" cy="60" r="50"></circle>
                        </svg>
                        <div class="progress-value">70%</div>
                    </div>
                    <div class="hostel-name">Stay Progress</div>
                </div>

                <!-- Personal Stay Details -->
                <div class="fee-details">
                    <h4>Personal Stay Details</h4>
                    <ul>
                        <li><span>Join Date</span> <span id="joinDate"></span></li>
                        <li><span>Contract End Date</span> <span id="contractEndDate"></span></li>
                        <li><span>Total Days Stayed</span> <span id="totalDaysStayed"></span></li>
                        <li><span>Days Remaining</span> <span id="daysRemaining"></span></li>
                        <li><span>Leave Taken</span> <span id="leaveTaken"></span></li>

                    </ul>
                </div>

                <!-- Hostel Stats -->
                <div class="hostel-stats">
                    <div class="stat-card"> <span>
                            <p>Total Leave</p> <strong><span id="totalLeaves"></span></strong>
                        </span></div>
                    <div class="stat-card"> <span>
                            <p>Mess Attendance %</p> <strong><span id="totalMA"></span> %</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Accessory Requests</p> <strong><span id="totalAccRequest"></span> Requests</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Pending Requests</p> <strong><span id="totalPR"></span> Requests</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Approved Requests</p> <strong><span id="totalAR"></span> Requests</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Rejected Requests</p> <strong><span id="totalRR"></span> Requests</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Total Guest visit</p> <strong><span id="totalGuestVisits"></span></strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Total Check In-Out</p> <strong><span id="totalInOuts"></span></strong>
                        </span> </div>

                    <!-- Action Buttons -->
                    <div class="hostel-actions">
                        {{-- <button class="edit" data-bs-toggle="modal" data-bs-target="#RequestLeave">Request Leave</button> --}}
                        <button class="edit" onclick="window.location='{{ route('resident.leave_request') }}'"> Request
                            Leave</button>
                    </div>

                    <div class="hostel-actions">
                        {{-- <button class="delete" data-bs-toggle="modal" data-bs-target="#SubmitComplaintPopup"> Submit
                            Complaint</button> --}}
                        <button class="delete" onclick="window.location='{{ route('resident.grievances') }}'"> Submit
                            Complaint</button>
                    </div>

                    <div class="hostel-actions">
                        {{-- <button class="remove" data-bs-toggle="modal" data-bs-target="#RequestAccessories">Request Accessory</button> --}}
                        <button class="remove" onclick="window.location='{{ route('resident.accessories') }}'"> Request Accessory</button>
                    </div>

                    <div class="hostel-actions">
                        {{-- <button class="add" data-bs-toggle="modal" data-bs-target="#ViewAttendance">View Attendance</button> --}}
                        <button class="add" onclick="window.location='{{ route('resident.payment') }}'"> Payments</button>
                    </div>

                </div>

            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            fetch("/api/resident/dashboard", {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(res => {
                    if (!res.success) {
                        console.warn("Failed to load dashboard data");
                        return;
                    }

                    const data = res.data;
                    const residentInfo = data.residentInfo ?? {};

                    const profile = data.profile ?? {};

                    console.log(profile.attendance);

                    // ----------- UI ELEMENTS ----------
                    // document.getElementById("universityName").innerText = residentInfo.university ?? "N/A";
                    document.getElementById("hostelName").innerText = residentInfo.hostel ?? "N/A";
                    document.getElementById("roomNumber").innerText = residentInfo.room_number ?? "N/A";
                    document.getElementById("floorNumber").innerHTML = toOrdinal(residentInfo.floor);
                    document.getElementById("bedNumber").innerText = residentInfo.bed_number ?? "N/A";

                    // Update UI
                    document.getElementById("joinDate").innerText = profile.joining_date ?? "N/A";
                    document.getElementById("contractEndDate").innerText = profile.paid_till ?? "N/A";
                    document.getElementById("totalDaysStayed").innerText = profile.staying_date ?? 0;
                    document.getElementById("daysRemaining").innerText = profile.days_remaining ?? 0;
                    document.getElementById("leaveTaken").innerText = profile.total_leaves ?? 0;

                    document.getElementById("totalLeaves").innerText = profile.total_leaves ?? "N/A";
                    document.getElementById("totalMA").innerText = profile.attendance ?? "N/A";
                    document.getElementById("totalAccRequest").innerText = profile.accessory ?? 0;
                    document.getElementById("totalPR").innerText = profile.days_remaining ?? 0;
                    document.getElementById("totalAR").innerText = profile.total_leaves ?? 0;

                    document.getElementById("totalRR").innerText = profile.total_leaves ?? 0;
                    document.getElementById("totalGuestVisits").innerText = profile.guest_visit ?? 0;
                    document.getElementById("totalInOuts").innerText = profile.total_in_outs ?? 0;


                    // ---------- ROOM CHANGE TABLE ----------
                    // const list = document.getElementById("room-change-list");
                    // list.innerHTML = "";

                    // if (data.requests.length === 0) {
                    //     list.innerHTML = `<tr><td colspan="6" class="text-center">No requests found</td></tr>`;
                    // } else {
                    //     data.requests.forEach((req, i) => {
                    //          list.innerHTML += `
                //                 <tr>
                //                     <td>${i + 1}</td>
                //                     <td>${req.reason}</td>
                //                     <td>${req.preference ?? 'N/A'}</td>
                //                     <td>${formatAction(req.action)}</td>
                //                     <td>${req.remark}</td>
                //                     <td>${formatStatus(req)}</td>
                //                 </tr>
                //             `;
                    //                     });
                    // }
                });

            // Ordinal conversion (1 → 1st, 2 → 2nd)
            function toOrdinal(num) {
                if (!num || isNaN(num)) return 'N/A';
                const n = parseInt(num);
                const suffix = (n % 10 === 1 && n % 100 !== 11) ? 'st' :
                    (n % 10 === 2 && n % 100 !== 12) ? 'nd' :
                    (n % 10 === 3 && n % 100 !== 13) ? 'rd' : 'th';
                return `${n}<sup>${suffix}</sup> Floor`;
            }

            function formatAction(a) {
                const map = {
                    pending: '<span class="badge bg-warning">Pending</span>',
                    available: '<span class="badge bg-primary">Available</span>',
                    completed: '<span class="badge bg-success">Completed</span>',
                    not_available: '<span class="badge bg-danger">Not Available</span>',
                };
                return map[a] ?? 'N/A';
            }

            function formatStatus(req) {
                if (req.action === 'pending') {
                    return `<span class="badge bg-warning">Awaiting Admin Approval</span>`;
                }
                if (req.resident_agree == 1) {
                    return `<span class="badge bg-success">Confirmed</span>`;
                }
                if (req.action === 'not_available') {
                    return `<span class="badge bg-danger">Denied</span>`;
                }
                return `<span class="badge bg-info">Processing</span>`;
            }

        });
    </script>
@endpush
