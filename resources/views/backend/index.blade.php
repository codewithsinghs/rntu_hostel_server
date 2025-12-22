@extends('backend.layouts.app')

@section('title', 'Home Page')
@section('meta_description', 'Welcome to RNTU Hostel.')

@push('styles')
@endpush

@section('content')

    <!-- Dashboard content -->
    <section class="dashboard ">
        <div class="dashboard-content-top-wrapper">
            <div class="hostel-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Overview</a></div>

                <!-- Hostel Overview -->
                <div class="cards">
                    <div class="card-c">
                        <div class="card-content">
                            <p>Total Students</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-c">
                        <div class="card-content">
                            <p>In Hostel</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-c">
                        <div class="card-content">
                            <p>Outside Hostel</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="" />
                        </div>
                    </div>
                    <div class="card-c">
                        <div class="card-content">
                            <p>New Register</p>
                            <h3>1,000</h3>
                        </div>
                        <div class="card-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="" />
                        </div>
                    </div>
                </div>

                <!-- Graph Placeholder -->
                <div class="chart">
                    <h4>New Student Registration</h4>
                    <div class="graph-placeholder">
                        <div id="student-registration-chart" class="graph-container"></div>
                    </div>
                </div>
            </div>

            <!-- Student Requests and Updates -->
            <div class="student-info">
                <div class="pending-requests">
                    <div class="pending-requests-header">
                        <h4>Student Pending Request</h4>
                        <button> <a href="pages/Resident Services/StudentPendingRequestlist.html">View
                                All</a></button>
                    </div>
                    <p class="pending-requests-text">New Registration Request</p>
                    <div class="card-box">
                        <table>
                            <thead class="table-header">
                                <tr>
                                    <th>Sr No</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>4546546</td>
                                    <td>
                                        <div class="student-name">
                                            <img src="{{ asset('backend/img/dashboard/Avatar 1.png') }}" alt="Rajat" />
                                            Rajat
                                        </div>
                                    </td>
                                    <td>
                                        <a href="pages/Resident Services/StudentPendingRequestdetails.html"
                                            class="edit-btn">View</a>
                                        <button class="reject">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>2656655</td>
                                    <td>
                                        <div class="student-name">
                                            <img src="{{ asset('backend/img/dashboard/Avatar 2.png') }}" alt="Amresh" />
                                            Amresh
                                        </div>
                                    </td>
                                    <td>
                                        <a href="pages/Resident Services/StudentPendingRequestdetails.html"
                                            class="edit-btn">View</a>
                                        <button class="reject">Reject</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="student-updates">
                    <div class="pending-requests-header">
                        <h4>Student Updates</h4>
                        <button>View</button>
                    </div>
                    <p class="pending-requests-text">
                        Student In and Out Status from Campus
                    </p>
                    <div class="card-box">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Resident ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>4558345</td>
                                    <td>
                                        <div class="student-name">
                                            <img src="{{ asset('backend/img/dashboard/Avatar 1.png') }}" alt="Rajat" />
                                            Rajat
                                        </div>
                                    </td>
                                    <td>In 08:30 - Out 12:30</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>3685464</td>
                                    <td>
                                        <div class="student-name">
                                            <img src="{{ asset('backend/img/dashboard/Avatar 2.png') }}" alt="Amresh" />
                                            Amresh
                                        </div>
                                    </td>
                                    <td>In 08:30 - Out 12:30</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="hostel-status-section">
            <h3 class="hostel-status-title">Hostel Status</h3>
            <div class="hostel-status-container">
                <!-- Hostel Name and Progress -->
                <div class="hostel-progress-card">
                    <div class="progress-circle">
                        <svg>
                            <circle cx="60" cy="60" r="50"></circle>
                            <circle cx="60" cy="60" r="50"></circle>
                        </svg>
                        <div class="progress-value">50%</div>
                    </div>
                    <div class="hostel-name">Hostel - <span style="font-weight: 700;">Boys Hostel</span>
                    </div>
                    <div class="hostel-name">Warden - <span style="font-weight: 700;">Mr. Sharma</span> </div>
                    <div class="hostel-name">Contact Info - <span style="font-weight: 700;">7024393158</span>
                    </div>
                    <div class="hostel-name"> Security - <span style="font-weight: 700;"> Mr. Ram Babu</span>
                    </div>
                </div>

                <!-- Fee Collection -->
                <div class="fee-details">
                    <h4>Fee Collection Details</h4>
                    <ul>
                        <li><span>Expected</span> ₹ 15,65,852</li>
                        <li><span>Collected</span> ₹ 3,91,463</li>
                        <li><span>Remaining</span> ₹ 7,51,608</li>
                        <li><span>Others</span> ₹ 1,65,852</li>
                        <li><span>Overdue</span> ₹ 4,85,414</li>
                    </ul>
                </div>

                <!-- Assets -->
                <div class="assets">
                    <h4>Assets</h4>
                    <ul>
                        <li><span>Ceiling Fan</span> 50</li>
                        <li><span>Center Table</span> 12</li>
                        <li><span>Chairs</span> 30</li>
                        <li><span>Refrigerator</span> 44</li>
                        <li><span>Kettle</span> 33</li>
                    </ul>
                </div>

                <!-- Hostel Stats -->
                <div class="hostel-stats">
                    <div class="stat-card"> <span>
                            <p>Total Bed</p> <strong>550</strong>
                        </span></div>
                    <div class="stat-card"> <span>
                            <p>Total Bathroom</p> <strong>58</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Total Floors</p> <strong>3</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Total Rooms</p> <strong>458</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Occupied</p> <strong>58</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Students</p> <strong>500</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>In Hostel</p> <strong>290</strong>
                        </span> </div>
                    <div class="stat-card"> <span>
                            <p>Outside Hostel</p> <strong>210</strong>
                        </span> </div>

                    <!-- Action Buttons -->
                    <div class="hostel-actions">
                        <button class="edit" data-bs-toggle="modal" data-bs-target="#EditHostel"><span><img
                                    src="{{ asset('backend/img/dashboard/edit icon.png') }}"></span> Edit Hostel</button>
                    </div>

                    <div class="hostel-actions">
                        <button class="delete" data-bs-toggle="modal" data-bs-target="#ConfirmationPopup"><span><img
                                    src="{{ asset('backend/img/dashboard/delete icon.png') }}"></span> Delete Hostel</button>
                    </div>

                    <div class="hostel-actions">
                        <button class="remove" data-bs-toggle="modal" data-bs-target="#RemoveRoom"><span><img
                                    src="{{ asset('backend/img/dashboard/min icon.png') }}"></span> Remove Room</button>
                    </div>

                    <div class="hostel-actions">
                        <button class="add" data-bs-toggle="modal" data-bs-target="#AddRoom"><span><img
                                    src="{{ asset('backend/img/dashboard/Plus icon.png') }}"></span> Add Room</button>
                    </div>

                </div>

            </div>
        </div>

        <div class="bed-status-section">

            <div class="table-controls">
                <h3 class="table-title">Hotsel List</h3>
                <div>
                    <input type="text" id="searchInput" placeholder="Search" class="search-input" />
                    <a href="pages/Hostel Operations/HostelManagement.html" class="view-btn">Add Hostel +</a>
                    <a href="pages/Hostel Operations/HostelManagement.html" class="view-btn">View All</a>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table">
                    <thead>
                        <th>S.No</th>
                        <th>Hostel Name</th>
                        <th>Number of Floors</th>
                        <th>Total Rooms</th>
                        <th>Warden Details</th>
                        <th>Security Details</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Alpha Hostel</td>
                            <td>3</td>
                            <td>452</td>
                            <td>
                                <strong>Name:</strong> Ramesh Kumar <br>
                                <strong>Contact:</strong> 9876543210
                            </td>
                            <td>
                                <strong>Name:</strong> Ajay Singh <br>
                                <strong>Contact:</strong> 9876501234
                            </td>
                            <td style="color: green; font-weight: bold;">Active</td>
                            <td class="actions">
                                <button class="edit-btn">View</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Lily Hostel</td>
                            <td>4</td>
                            <td>356</td>
                            <td>
                                <strong>Name:</strong> Ms. Neha Gupta <br>
                                <strong>Contact:</strong> 9988776655
                            </td>
                            <td>
                                <strong>Name:</strong> Arun Mishra <br>
                                <strong>Contact:</strong> 7890654321
                            </td>
                            <td style="color: green; font-weight: bold;">Active</td>
                            <td class="actions">
                                <button class="edit-btn">View</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Sunrise Hostel</td>
                            <td>2</td>
                            <td>421</td>
                            <td>
                                <strong>Name:</strong> Mr. Pradeep Kumar <br>
                                <strong>Contact:</strong> 9001234567
                            </td>
                            <td>
                                <strong>Name:</strong> Amit Singh <br>
                                <strong>Contact:</strong> 7009876543
                            </td>
                            <td style="color: red; font-weight: bold;">Inactive</td>
                            <td class="actions">
                                <button class="edit-btn">View</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr>

            <div class="table-controls">
                <h3 class="table-title">Bed Status</h3>
                <div>
                    <input type="text" id="searchInput" placeholder="Search" class="search-input" />
                    <button class="view-btn" data-bs-toggle="modal" data-bs-target="#AddBed">Add Bed
                        +</button>
                    <a href="" class="view-btn">View All</a>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table">
                    <thead>
                        <th>S.No</th>
                        <th>Hostel Name</th>
                        <th>Floor</th>
                        <th>Room Number</th>
                        <th>Bed Number</th>
                        <th>Bed Type</th>
                        <th>Bed Condition</th>
                        <th>Status</th>
                        <th>Allocated To</th>
                        <th>Last Cleaned</th>
                        <th>Maintenance</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Hostel A</td>
                            <td>2nd Floor</td>
                            <td>204</td>
                            <td>B1</td>
                            <td>Single</td>
                            <td>Good</td>
                            <td style="color: green; font-weight: bold;">Available</td>
                            <td>-</td>
                            <td>15-Aug-2025</td>
                            <td style="color: green; font-weight: bold;">No</td>
                            <td>
                                <button class="edit-btn">Assign</button>
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Hostel A</td>
                            <td>3rd Floor</td>
                            <td>307</td>
                            <td>B2</td>
                            <td>Double</td>
                            <td>Average</td>
                            <td style="color: red; font-weight: bold;">Occupied</td>
                            <td>Rajat Pradhan</td>
                            <td>10-Aug-2025</td>
                            <td style="color: red; font-weight: bold;">Yes</td>
                            <td>
                                <button class="edit-btn">Allocated</button>
                                <button class="edit-btn">Edit</button>
                                <button class="delete-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </section>


    <!-- All Popup -->


    <!-- Add Hostel -->
    <div class="modal fade" id="AddHostel" tabindex="-1" aria-labelledby="AddHostelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Hostel</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="hostelName">Select University</label>
                            <input type="text" id="SelectUniversity" name="SelectUniversity"
                                placeholder="Select University">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Name</label>
                            <input type="text" id="hostelName" name="hostelName" placeholder="Enter Hostel Name">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Code</label>
                            <input type="text" id="hostelCode" name="hostelCode" placeholder="Enter Hostel Code">
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Numbers of Floors</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="hostelName">Hostel Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Open">Open</option>
                                <option value="Close">Close</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Add hostel </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- ConfirmationPopup -->
    <div class="modal fade" id="ConfirmationPopup" tabindex="-1" aria-labelledby="ConfirmationPopupLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Confirm Deletion</div>
                    </div>

                    <div class="middle-content">
                        <p>Deleting this record will permanently remove it from your system. Proceed with
                            caution.</p>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Delete </button>
                        <button type="button" class="blue"> Go Back </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Add Room -->
    <div class="modal fade" id="AddRoom" tabindex="-1" aria-labelledby="AddRoomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Room</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="CreateRoomNumber">Create Room Number</label>
                            <input type="text" id="CreateRoomNumber" name="CreateRoomNumber"
                                placeholder="Enter Create Room Number">
                        </span>

                        <span class="input-set">
                            <label for="RoomType">Room Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="RoomStatus">Room Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Add Room </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Remove Room -->
    <div class="modal fade" id="RemoveRoom" tabindex="-1" aria-labelledby="RemoveRoomLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title-remove">Remove Room</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectRoom">Select Room</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Room</option>
                                <option value="101">101</option>
                                <option value="102">102</option>
                                <option value="103">103</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Remove Room </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- EditHostel -->
    <div class="modal fade" id="EditHostel" tabindex="-1" aria-labelledby="EditHostelLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Edit Hostel</div>
                    </div>

                    <div class="tabs-middle">

                        <div class="tabset">
                            <!-- Tab 1 -->
                            <input type="radio" name="tabset" id="tab1" aria-controls="HostelDetails" checked>
                            <label for="tab1">Hostel Details</label>

                            <!-- Tab 2 -->
                            <input type="radio" name="tabset" id="tab2"
                                aria-controls="Fee Collection Details  ">
                            <label for="tab2">Fee Collection Details </label>

                            <!-- Tab 3 -->
                            <input type="radio" name="tabset" id="tab3" aria-controls="Assests">
                            <label for="tab3">Assests</label>

                            <div class="tab-panels">
                                <!-- Tab 1 -->
                                <section id="HostelDetails" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalBed">Total Bed</label>
                                                <input type="text" id="TotalBed" name="TotalBed"
                                                    placeholder="Total Bed">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalBathroom">Total Bathroom</label>
                                                <input type="text" id="TotalBathroom" name="TotalBathroom"
                                                    placeholder="Total Bathroom">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalFloors">Total Floors</label>
                                                <input type="text" id="TotalFloors" name="TotalFloors"
                                                    placeholder="Total Floors">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="TotalRooms">Total Rooms</label>
                                                <input type="text" id="TotalRooms" name="TotalRooms"
                                                    placeholder="Total Rooms">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Occupied">Occupied</label>
                                                <input type="text" id="Occupied" name="Occupied"
                                                    placeholder="Occupied">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Students">Students</label>
                                                <input type="text" id="Students" name="Students"
                                                    placeholder="Students">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="In Hostel">In Hostel</label>
                                                <input type="text" id="In Hostel" name="In Hostel"
                                                    placeholder="In Hostel">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="OutsideHostel">Outside Hostel</label>
                                                <input type="text" id="OutsideHostel" name="OutsideHostel"
                                                    placeholder="Outside Hostel">
                                            </span>
                                        </div>

                                    </div>

                                </section>

                                <!-- Tab 2 -->
                                <section id="Fee Collection Details " class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Expected">Expected</label>
                                                <input type="text" id="Expected" name="Expected"
                                                    placeholder="₹ 15,65,852">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="col col-sm-6lected">col col-sm-6lected</label>
                                                <input type="text" id="col col-sm-6lected" name="col col-sm-6lected"
                                                    placeholder="₹ 3,91,463">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Remaining">Remaining</label>
                                                <input type="text" id="Remaining" name="Remaining"
                                                    placeholder="₹ 7,51,608">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Others">Others</label>
                                                <input type="text" id="Others" name="Others"
                                                    placeholder="₹ 1,65,852">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Overdue">Overdue</label>
                                                <input type="text" id="Overdue" name="Overdue"
                                                    placeholder="₹ 4,85,414">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                        </div>

                                    </div>

                                </section>

                                <!-- Tab 3 -->
                                <section id="Assests" class="tab-panel">

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Ceiling Fan">Ceiling Fan</label>
                                                <input type="text" id="Ceiling Fan" name="Ceiling Fan"
                                                    placeholder="33">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="CenterTable">Center Table</label>
                                                <input type="text" id="CenterTable" name="CenterTable"
                                                    placeholder="12">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Chairs">Chairs</label>
                                                <input type="text" id="Chairs" name="Chairs" placeholder="51">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Refrigerator">Refrigerator</label>
                                                <input type="text" id="Refrigerator" name="Refrigerator"
                                                    placeholder="65">
                                            </span>
                                        </div>

                                    </div>

                                    <div class="row ">

                                        <div class=" col-sm-6">
                                            <span class="input-set">
                                                <label for="Kettle">Kettle</label>
                                                <input type="text" id="Kettle" name="Kettle" placeholder="85">
                                            </span>
                                        </div>

                                        <div class=" col-sm-6">
                                        </div>

                                </section>
                            </div>

                        </div>



                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Save Changes </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Add Room -->
    <div class="modal fade" id="AddBed" tabindex="-1" aria-labelledby="AddBedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Add Bed</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="SelectHostel">Select Hostel</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Hostel</option>
                                <option value="Hostel 1">Hostel 1</option>
                                <option value="Hostel 2">Hostel 2</option>
                                <option value="Hostel 3">Hostel 3</option>
                                <option value="Hostel 4">Hostel 4</option>
                                <option value="Hostel 5">Hostel 5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="SelectFloor">Select Floor</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="RoomType">Room Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="BedType">Bed Type</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Floor</option>
                                <option value="Single Bed">Single Bed</option>
                                <option value="Double Bed">Double Bed</option>
                                <option value="Triple Bed">Triple Bed</option>
                            </select>
                        </span>

                        <span class="input-set">
                            <label for="CreateRoomNumber">Create Bed Number</label>
                            <input type="text" id="CreateBedNumber" name="CreateBedNumber"
                                placeholder="Enter Create Bed Number">
                        </span>

                        <span class="input-set">
                            <label for="BedStatus">Bed Status</label>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Select Status</option>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                            </select>
                        </span>

                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red"> Cancel </button>
                        <button type="button" class="blue"> Creat Bed </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Page-specific JS
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Index page loaded');
        });
    </script>
@endpush
