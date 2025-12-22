@extends('admin.layout')

@section('content')

    <section class="user-management-container">
        <!-- Header section -->
        <div class="header-bar">
            <div class="section-header">
                <p> <a>Dashboard /</a> <a>Resident
                        Details</a> </p>
                <h3>Resident Details</h3>
            </div>
            <div class="search-section">
                <input type="text" placeholder="Type..." />
                <img src="{{ asset('backend/img/User Management/Icon.png') }}" alt="" />
            </div>
        </div>

        <!-- Add member block -->
        <div class="add-member">
            <div class="user-details">
                <div class="image-div">
                    <img src="{{ asset('backend/img/User Details/user-details-profile.png') }}" alt="Add Member"
                        class="profile-photo" />
                    <div class="icon-div">
                        <img src="{{ asset('backend/img/User Details/edit.png') }}" alt="" />
                    </div>
                </div>
                <div class="info">
                    <h5>Rajat Pradhan (Scholar - 455465445)</h5>
                    <p>prajat917@gmail.com</p>
                </div>
            </div>
            <div class="add-member-btn">
                <button class="active-btn">Resident On Class</button>
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#finalcheckout">Final Check-Out
                    Request</button>
                <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#PrintProfileS">Print Profile</button>
            </div>
        </div>

        <section class="profile-info-section">
            <div class="profile-info-header">
                <h3>Profile Information</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#ProfileInformation">Edit</button>
            </div>

            <div class="profile-grid">
                <div class="profile-field">
                    <label>Student Type</label>
                    <input type="text" value="Regular" />
                </div>
                <div class="profile-field">
                    <label>Full Name</label>
                    <input type="text" value="Rajat Pradhan" />
                </div>
                <div class="profile-field">
                    <label>Email Address</label>
                    <input type="text" value="prajat917@gmail.com" />
                </div>
                <div class="profile-field">
                    <label>Mobile Number</label>
                    <input type="text" value="7204833168" />
                </div>
                <div class="profile-field">
                    <label>Gender</label>
                    <input type="text" value="Male" />
                </div>
                <div class="profile-field">
                    <label>Enrollment / Scholar ID</label>
                    <input type="text" value="4554654456" />
                </div>
                <div class="profile-field address-field">
                    <label>Address</label>
                    <input type="text" value="08 Gujar Pura" />
                </div>
            </div>
        </section>

        <!-- Family Details Section -->
        <section class="info-section">
            <div class="info-header">
                <h3>Family Details</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#FamilyDetails">Edit</button>
            </div>
            <div class="info-grid">
                <div class="field">
                    <label>Father's Name</label>
                    <input type="text" value="Mr. Hariom Shahay Pradhan" />
                </div>
                <div class="field">
                    <label>Mother's Name</label>
                    <input type="text" value="Kirti Pradhan" />
                </div>
                <div class="field">
                    <label>Primary Contact Number</label>
                    <input type="text" value="7879545752" />
                </div>
                <div class="field">
                    <label>Secondary Contact Number</label>
                    <input type="text" value="7024390158" />
                </div>
            </div>

            <h4>Emergency Contact Details</h4>
            <div class="info-grid">
                <div class="field">
                    <label>Name</label>
                    <input type="text" value="Kirti Pradhan" />
                </div>
                <div class="field">
                    <label>Relationship</label>
                    <input type="text" value="Mother" />
                </div>
                <div class="field">
                    <label>Contact Number</label>
                    <input type="text" value="7879545752" />
                </div>
            </div>

            <h4>Local Guardian Details</h4>
            <div class="info-grid">
                <div class="field">
                    <label>Name</label>
                    <input type="text" value="Kirti Pradhan" />
                </div>
                <div class="field">
                    <label>Relationship</label>
                    <input type="text" value="Mother" />
                </div>
                <div class="field">
                    <label>Contact Number</label>
                    <input type="text" value="7879545752" />
                </div>
            </div>
        </section>

        <!-- Hostel Information Section -->
        <section class="info-section">
            <div class="info-header">
                <h3>Student Hostel Information</h3>
                <button class="edit-btn" data-bs-toggle="modal" data-bs-target="#HostelInformation">Edit</button>
            </div>
            <div class="info-grid">
                <div class="field">
                    <label>Hostel Name</label>
                    <input type="text" value="Hostel One" />
                </div>
                <div class="field">
                    <label>Hostel Floor</label>
                    <input type="text" value="3" />
                </div>
                <div class="field">
                    <label>Room Number</label>
                    <input type="text" value="256" />
                </div>
                <div class="field">
                    <label>Bed Number</label>
                    <input type="text" value="8987" />
                </div>
                <div class="field">
                    <label>Duration of Stay</label>
                    <input type="text" value="Full Degree" />
                </div>
                <div class="field">
                    <label>Joining Date</label>
                    <input type="text" value="25/05/2025" />
                </div>
            </div>
        </section>

        <!-- Mess Thump Time Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Mess Thump Time Table</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                    <button class="view-btn-top">View All</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Hostel</th>
                            <th>Room Number</th>
                            <th>Check Out Thump Time</th>
                            <th>Check In Thump Time </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Hostel One</td>
                            <td>304</td>
                            <td>01-Aug-2025 05:55PM</td>
                            <td>01-Aug-2025 10:22PM</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Hostel One</td>
                            <td>304</td>
                            <td>01-Aug-2025 11:22AM</td>
                            <td>01-Aug-2025 12:02PM</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- Leave List Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Leave List</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Hostel</th>
                            <th>Room Number</th>
                            <th>Leave Taken on</th>
                            <th>Duration</th>
                            <th>Expected Arrival</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Hostel One</td>
                            <td>304</td>
                            <td>01-Aug-2025</td>
                            <td>3 Days</td>
                            <td>04-Aug-2025</td>
                            <td><button class="green-btn">Approve</button></td>
                            <td>
                                <button class="view-btn" data-bs-toggle="modal"
                                    data-bs-target="#leave_request_view">View</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Hostel Two</td>
                            <td>102</td>
                            <td>02-Aug-2025</td>
                            <td>2 Days</td>
                            <td>04-Aug-2025</td>
                            <td><button class="yellow-btn">Pending</button></td>
                            <td>
                                <button class="green-btn" data-bs-toggle="modal"
                                    data-bs-target="#leave_Approvepopup">Approve</button>
                                <button class="delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#leave_Rejectpopup">Reject</button>
                                <button class="view-btn" data-bs-toggle="modal"
                                    data-bs-target="#Pending_leave_View">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- Attendace List Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Attendace List</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                    <button class="view-btn-top">View All</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Breakfast</th>
                            <th>Lunch</th>
                            <th>Snaks</th>
                            <th>Dinner</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td style="color: orange; font-weight: bold;">No</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td>
                                <a href="" class="view-btn">View</a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td style="color: orange; font-weight: bold;">No</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td style="color: green; font-weight: bold;">Yes</td>
                            <td>
                                <a href="" class="view-btn">View</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


        </section>

        <!-- Student Check Out List Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Student Check In-Out List</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Faculty</th>
                            <th>Department</th>
                            <th>Hostel Name</th>
                            <th>Date</th>
                            <th>Check-Out Date</th>
                            <th>Check-Out Time</th>
                            <th>Check-In Date</th>
                            <th>Check-In Time</th>
                            <th>Purpose</th>
                            <th>Approved By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Engineering</td>
                            <td>Computer Science</td>
                            <td>Sunrise Hostel</td>
                            <td>25/05/2025</td>
                            <td>26/05/2025</td>
                            <td>5:00 PM</td>
                            <td>30/05/2025</td>
                            <td>12:00 PM</td>
                            <td>Going for internship</td>
                            <td><span style="color: green; font-weight: bold;">Admin</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View
                                    Detail</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Science</td>
                            <td>Biotechnology</td>
                            <td>Lotus Hostel</td>
                            <td>25/05/2025</td>
                            <td>26/05/2025</td>
                            <td>5:00 PM</td>
                            <td>30/05/2025</td>
                            <td>12:00 PM</td>
                            <td>Going for internship</td>
                            <td><span style="color: orange; font-weight: bold;">Pending</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View
                                    Detail</button></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Management</td>
                            <td>MBA</td>
                            <td>Sunrise Hostel</td>
                            <td>25/05/2025</td>
                            <td>26/05/2025</td>
                            <td>5:00 PM</td>
                            <td>30/05/2025</td>
                            <td>12:00 PM</td>
                            <td>Going for internship</td>
                            <td><span style="color: red; font-weight: bold;">Rejected</span></td>
                            <td><button class="view-btn" data-bs-toggle="modal" data-bs-target="#ViewStatus">View
                                    Detail</button></td>
                        </tr>
                    </tbody>

                </table>
            </div>


        </section>

        <!-- Fine List Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Fine List</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Reason</th>
                            <th>Payment Amount</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Mess Fees</td>
                            <td>₹2,000</td>
                            <td style="color: green; font-weight: bold;">Paid</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Mess Fees</td>
                            <td>₹2,000</td>
                            <td style="color: orange; font-weight: bold;">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- Item List Table -->

        <section class="attendance-leave-section">

            <div class="table-controls">
                <h3 class="table-title">Item List</h3>
                <div>
                    <button class="add-btn">Download Excel</button>
                </div>
            </div>

            <div class="table-container">
                <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Token</th>
                            <th>Duration</th>
                            <th>Return Item</th>
                            <th>Price</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Iron</td>
                            <td>#TKN001</td>
                            <td>2 Hours</td>
                            <td>Yes</td>
                            <td>₹50</td>
                            <td style="color: green; font-weight: bold;">Paid</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Heater</td>
                            <td>#TKN002</td>
                            <td>1 Day</td>
                            <td>No</td>
                            <td>₹150</td>
                            <td style="color: orange; font-weight: bold;">Pending</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </section>

        <!-- All Popup -->

        <!-- Profile Information -->
        <div class="modal fade" id="ProfileInformation" tabindex="-1" aria-labelledby="ProfileInformationLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Edit Profile Information</div>
                        </div>

                        <div class="middle">

                            <span class="input-set">
                                <label for="StudentType">Student Type</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Student Type</option>
                                    <option value="Regular" selected>Regular</option>
                                    <option value="Credit Card">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Full Name</label>
                                <input type="text" value="Rajat Pradhan" />
                            </span>

                            <span class="input-set">
                                <label>Email Address</label>
                                <input type="text" value="prajat917@gmail.com" />
                            </span>

                            <span class="input-set">
                                <label>Mobile Number</label>
                                <input type="text" value="7204833168" />
                            </span>

                            <span class="input-set">
                                <label for="Gender">Gender</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Gender</option>
                                    <option value="Male" selected>Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Enrollment / Scholar ID</label>
                                <input type="text" value="4554654456" />
                            </span>

                            <span class="input-set">
                                <label>Address</label>
                                <input type="text" value="08 Gujar Pura" />
                            </span>

                            <span class="input-set">
                                <label>Attach Supporting Documents</label>
                                <input type="file" />
                            </span>

                        </div>

                        <div class="reason">
                            <label for="Reason">Reason for editing profile</label>
                            <textarea type="text" placeholder="Enter your reason..."></textarea>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="button" class="blue"> Send to Admin</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Hostel Information -->
        <div class="modal fade" id="HostelInformation" tabindex="-1" aria-labelledby="HostelInformationLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Edit Hostel Information</div>
                        </div>

                        <div class="middle">

                            <span class="input-set">
                                <label for="HostelName">Hostel Name</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Hostel Name</option>
                                    <option value="One" selected>One</option>
                                    <option value="Two">Two</option>
                                    <option value="Others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label for="HostelFloor">Hostel Floor</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Hostel Floor</option>
                                    <option value="One" selected>One</option>
                                    <option value="Two">Two</option>
                                    <option value="Others">Others</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Room Number</label>
                                <input type="text" value="202" />
                            </span>

                            <span class="input-set">
                                <label>Bed Number</label>
                                <input type="text" value="235" />
                            </span>

                            <span class="input-set">
                                <label for="DurationofStay">Duration of Stay</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Duration of Stay</option>
                                    <option value="3 Months" selected>3 Months</option>
                                    <option value="6 Months">6 Months</option>
                                    <option value="Full Course">Full Course</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Joining Date</label>
                                <input type="text" value="20/02/25" />
                            </span>

                            <span class="input-set">
                                <label>Attach Supporting Documents</label>
                                <input type="file" />
                            </span>

                        </div>

                        <div class="reason">
                            <label for="Reason">Reason for editing hostel</label>
                            <textarea type="text" placeholder="Enter your reason..."></textarea>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="button" class="blue"> Send to Admin</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Family Details -->
        <div class="modal fade" id="FamilyDetails" tabindex="-1" aria-labelledby="FamilyDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Edit Family Details</div>
                        </div>

                        <div class="middle">

                            <span class="input-set">
                                <label>Father's Name</label>
                                <input type="text" value="Mr. Hariom Shahay Pradhan" />
                            </span>

                            <span class="input-set">
                                <label>Mother's Name</label>
                                <input type="text" value="Kirti Pradhan" />
                            </span>

                            <span class="input-set">
                                <label>Primary Contact Number</label>
                                <input type="text" value="7879545752" />
                            </span>

                            <span class="input-set">
                                <label>Secondary Contact Number</label>
                                <input type="text" value="7024390158" />
                            </span>

                            <span class="input-set">
                                <label>Emergency Contact Name</label>
                                <input type="text" value="Kirti Pradhan" />
                            </span>

                            <span class="input-set">
                                <label for="Relationship">Relationship</label>
                                <select class="form-select" aria-label="Default select example">
                                    <option>Select Relationship</option>
                                    <option value="Mother" selected>Mother</option>
                                    <option value="Father">Father</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Brother">Brother</option>
                                </select>
                            </span>

                            <span class="input-set">
                                <label>Contact Number</label>
                                <input type="text" value="7879545752" />
                            </span>



                            <span class="input-set">
                                <label>Attach Supporting Documents</label>
                                <input type="file" />
                            </span>

                        </div>

                        <div class="reason">
                            <label for="Reason">Reason for editing family details</label>
                            <textarea type="text" placeholder="Enter your reason..."></textarea>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                            </button>
                            <button type="button" class="blue"> Send to Admin</button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- finalcheckout -->
        <div class="modal fade" id="finalcheckout" tabindex="-1" aria-labelledby="finalcheckoutLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Final Check-Out Form</div>
                        </div>

                        <div class="tabs-middle">

                            <div class="tabset">
                                <!-- Tab 1 -->
                                <input type="radio" name="tabset" id="tab1" aria-controls="HostelDetails" checked>
                                <label for="tab1">Hostel Details</label>

                                <!-- Tab 2 -->
                                <input type="radio" name="tabset" id="tab2" aria-controls="Fee Collection Details  ">
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
                                                    <input type="text" id="Occupied" name="Occupied" placeholder="Occupied">
                                                </span>
                                            </div>

                                            <div class=" col-sm-6">
                                                <span class="input-set">
                                                    <label for="Students">Students</label>
                                                    <input type="text" id="Students" name="Students" placeholder="Students">
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
                                                    <input type="text" id="Others" name="Others" placeholder="₹ 1,65,852">
                                                </span>
                                            </div>

                                        </div>

                                        <div class="row ">

                                            <div class=" col-sm-6">
                                                <span class="input-set">
                                                    <label for="Overdue">Overdue</label>
                                                    <input type="text" id="Overdue" name="Overdue" placeholder="₹ 4,85,414">
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
                                                    <input type="text" id="Ceiling Fan" name="Ceiling Fan" placeholder="33">
                                                </span>
                                            </div>

                                            <div class=" col-sm-6">
                                                <span class="input-set">
                                                    <label for="CenterTable">Center Table</label>
                                                    <input type="text" id="CenterTable" name="CenterTable" placeholder="12">
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

                        <div class="reason">
                            <label for="Reason">Reason for Cancel Check-Out</label>
                            <textarea type="text" placeholder="Enter your reason..."></textarea>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Cancel
                                Check-Out</button>
                            <button type="button" class="blue"> Approve Final Check-Out </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Pending leave View Popup-->

        <div class="modal fade" id="Pending_leave_View" tabindex="-1" aria-labelledby="Pending_leave_ViewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Pending Leave Request</div>
                        </div>

                        <div class="middle">

                            <span class="input-set">
                                <label for="HostelName">Hostel Name</label>
                                <input type="text" id="HostelName" name="HostelName" placeholder="Hostel One" disabled>
                            </span>

                            <span class="input-set">
                                <label for="RoomNumber">Room Number</label>
                                <input type="text" id="RoomNumber" name="RoomNumber" placeholder="403" disabled>
                            </span>

                            <span class="input-set">
                                <label for="LeaveTakenon">Leave Taken on</label>
                                <input type="text" id="LeaveTakenon" name="LeaveTakenon" placeholder="25/05/2025" disabled>
                            </span>

                            <span class="input-set">
                                <label for="DurationinDays">Duration in Days</label>
                                <input type="text" id="DurationinDays" name="DurationinDays" placeholder="5" disabled>
                            </span>

                            <span class="input-set">
                                <label for="ExpectedArrival">Expected Arrival</label>
                                <input type="text" id="ExpectedArrival" name="ExpectedArrival" placeholder="30/02/2025"
                                    disabled>
                            </span>

                        </div>

                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="Reason">Reason</label>
                                <input type="text" id="Reason" name="Reason"
                                    placeholder="Lorem lorem Lorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem  lorem"
                                    disabled>
                            </span>
                        </div>

                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="FeedBack">FeedBack</label>
                                <input type="text" id="FeedBack" name="FeedBack"
                                    placeholder="Lorem lorem Lorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem  lorem">
                            </span>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-toggle="modal" data-bs-target="#Rejectpopup"> Reject
                                Leave
                            </button>
                            <button type="button" class="green" data-bs-toggle="modal" data-bs-target="#Approvepopup">
                                Approve
                                Leave </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- leave request View Popup-->
        <div class="modal fade" id="leave_request_view" tabindex="-1" aria-labelledby="leave_request_viewLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Pending Leave Request</div>
                        </div>

                        <div class="middle">

                            <span class="input-set">
                                <label for="StudentName">Student Name</label>
                                <input type="text" id="StudentName" name="StudentName" placeholder="Rajat Pradhan" disabled>
                            </span>

                            <span class="input-set">
                                <label for="ScholarNumber ">Scholar Number</label>
                                <input type="text" id="ScholarNumber" name="ScholarNumber" placeholder="4256865" disabled>
                            </span>

                            <span class="input-set">
                                <label for="HostelName">Hostel Name</label>
                                <input type="text" id="HostelName" name="HostelName" placeholder="Hostel One" disabled>
                            </span>

                            <span class="input-set">
                                <label for="RoomNumber">Room Number</label>
                                <input type="text" id="RoomNumber" name="RoomNumber" placeholder="403" disabled>
                            </span>

                            <span class="input-set">
                                <label for="LeaveTakenon">Leave Taken on</label>
                                <input type="text" id="LeaveTakenon" name="LeaveTakenon" placeholder="25/05/2025" disabled>
                            </span>

                            <span class="input-set">
                                <label for="DurationinDays">Duration in Days</label>
                                <input type="text" id="DurationinDays" name="DurationinDays" placeholder="5" disabled>
                            </span>

                            <span class="input-set">
                                <label for="ExpectedArrival">Expected Arrival</label>
                                <input type="text" id="ExpectedArrival" name="ExpectedArrival" placeholder="30/02/2025"
                                    disabled>
                            </span>

                            <span class="input-set">
                                <label for="Status">Status</label>
                                <input type="text" class="status-leave green" id="Status" name="Status"
                                    placeholder="Approve" disabled>
                            </span>

                        </div>

                        <div class="full-width-i">
                            <span class="input-set">
                                <label for="Reason">Reason</label>
                                <input type="text" id="Reason" name="Reason"
                                    placeholder="Lorem lorem Lorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem loremLorem  lorem"
                                    disabled>
                            </span>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- leave_Rejectpopup -->
        <div class="modal fade" id="leave_Rejectpopup" tabindex="-1" aria-labelledby="leave_RejectpopupLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title-remove">Confirm Reject</div>
                        </div>

                        <div class="middle-content">
                            <p>Rejecting this will permanently show it from your system. Proceed with caution.
                            </p>
                        </div>

                        <div class="reason">
                            <label for="Reason">Write Reason for Rejecting Leave</label>
                            <textarea
                                type="text">We're sorry, but your leave request has been rejected by the management.</textarea>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="red"> Reject </button>
                            <button type="button" class="blue" data-bs-dismiss="modal" aria-label="Close"> Go Back </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- leave Approvepopup Popup -->
        <div class="modal fade" id="leave_Approvepopup" tabindex="-1" aria-labelledby="leave_ApprovepopupLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title-green">Confirm Approve</div>
                        </div>

                        <div class="middle-content">
                            <p>Rejecting this will permanently show it from your system. Proceed with caution.
                            </p>
                        </div>

                        <div class="bottom-btn">
                            <button type="button" class="green"> Approve </button>
                            <button type="button" class="blue" data-bs-dismiss="modal" aria-label="Close"> Go Back </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!--  Print -->
        <div class="modal fade" id="PrintProfileS" tabindex="-1" aria-labelledby="PrintProfileSLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="top">
                            <div class="pop-title">Student Application Details</div>
                        </div>

                        <div class="middle-no-grid">

                            <div class="receipt-container" id="printForm">
                                <header>
                                    <div class="school-details">
                                        <img src="{{ asset('backend/img/dashboard/register-logo.png') }}" alt="RNTU logo">
                                        <p class="addres">Bhojpur, Chiklod Road, near Bangrasiya Chouraha, Bhopal, Madhya
                                            Pradesh
                                            464993</p>
                                        <p class="addres m-0">Phone No: 091317-97517</p>
                                    </div>
                                    <div class="receipt-tag">Application Details</div>
                                </header>

                                <section class="print-heading" style="background-color: #002B5B;"> Presonal Details
                                </section>

                                <table class="fees-table-border-b">
                                    <tbody>

                                        <tr>
                                            <th>Enrollment / Scholar ID :- 4554654456</th>
                                            <th>Student Type :- Regular</th>
                                        </tr>
                                        <tr>
                                            <th>Full Name :- Rajat Pradhan</th>
                                            <th>Email Address :- prajat917@gmail.com</th>
                                        </tr>
                                        <tr>
                                            <th>Mobile Number :- 7204833168</th>
                                            <th>Gender :- Male</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="borden-no">Address :- 08 Gujar Pura</th>
                                        </tr>

                                    </tbody>
                                </table>

                                <section class="print-heading"> Family Details</section>

                                <table class="fees-table-border-b">
                                    <tbody>

                                        <tr>
                                            <th>Father's Name :- Mr. Hariom Shahay Pradhan</th>
                                            <th>Mother's Name :- Kirti Pradhan</th>
                                        </tr>
                                        <tr>
                                            <th class="borden-no">Primary Contact Number :- 7879545752</th>
                                            <th class="borden-no">Secondary Contact Number :- 7024390158</th>
                                        </tr>

                                    </tbody>
                                </table>

                                <section class="print-heading"> Emergency Contact Details</section>

                                <table class="fees-table-border-b">
                                    <tbody>

                                        <tr>
                                            <th class="borden-no">Name :- Mr. Hariom Shahay Pradhan</th>
                                            <th class="borden-no">Relationship :- Father</th>
                                            <th class="borden-no">Contact Number :- 7879545752</th>
                                        </tr>

                                    </tbody>
                                </table>

                                <section class="print-heading"> Local Guardian Details</section>

                                <table class="fees-table-border-b">
                                    <tbody>

                                        <tr>
                                            <th class="borden-no">Name :- Mr. Hariom Shahay Pradhan</th>
                                            <th class="borden-no">Relationship :- Father</th>
                                            <th class="borden-no">Contact Number :- 7879545752</th>
                                        </tr>

                                    </tbody>
                                </table>

                                <section class="print-heading"> Student Hostel Information</section>

                                <table class="fees-table-border-b">
                                    <tbody>

                                        <tr>
                                            <th>Hostel Name :- Hostel One</th>
                                            <th>Hostel Floor :- 3</th>
                                        </tr>

                                        <tr>
                                            <th>Room Number :- 203</th>
                                            <th>Bed Number :- 89</th>
                                        </tr>

                                        <tr>
                                            <th class="borden-no">Duration of Stay :- Full Degree</th>
                                            <th class="borden-no">Joining Date :- 25/05/2025</th>
                                        </tr>

                                    </tbody>
                                </table>


                            </div>

                            <div class="receipt-container" id="printForm">

                                <header>
                                    <div class="school-details">
                                        <img src="{{ asset('backend/img/dashboard/register-logo.png') }}" alt="RNTU logo">
                                        <p class="addres">Bhojpur, Chiklod Road, near Bangrasiya Chouraha, Bhopal, Madhya
                                            Pradesh
                                            464993</p>
                                        <p>Phone No: 091317-97517</p>
                                    </div>
                                    <div class="receipt-tag">Application Details</div>
                                </header>

                                <h3 class="print-heading" style="background-color: #002B5B;"> Addtional Accessory Details
                                </h3>

                                <table class="fees-table">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Items</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Demo</td>
                                            <td>300</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Demo</td>
                                            <td>460</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Demo</td>
                                            <td>300</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Demo</td>
                                            <td>1380</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <section class="summary">
                                    <p><strong>Total Amount:</strong> ₹2440</p>
                                    <p><strong>Fees Paid Amount:</strong> ₹1200</p>
                                    <p><strong>Fees Balance Amount:</strong> ₹7640</p>
                                    <p><strong>Payment By:</strong> 6/15/2025</p>
                                </section>

                                <section class="remarks">
                                    <p><strong>Remarks:</strong> test</p>
                                    <p class="note">Fees/Amount once paid will not be refundable or transferable.
                                    </p>
                                    <p class="sign">Sign: __________</p>

                                </section>

                            </div>

                        </div>


                        <div class="bottom-btn">
                            <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Close
                            </button>
                            <button type="button" class="green"> <a href="print.html">RECEIPT (PDF)</a>
                            </button>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </section>

@endsection