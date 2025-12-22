@extends('admin.layout')

@section('content')

    <!-- top-breadcrumbs -->
    <div class="top-breadcrumbs">
        <div class="breadcrumbs"><a>Overview</a></div>
    </div>

    <!-- Card -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a href="">Check-In / Check-Out Details</a></div>

                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Check-In-Out</p>
                            <h3>1,500</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Hostel Management.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Check-Out</p>
                            <h3>400</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/livingroom.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Check-In</p>
                            <h3>50</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Pending Check-Out Request</p>
                            <h3>550</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Pending Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Check-In / Check-Out from Campus</a></div>

                <div class="overflow-auto">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Resident Scholar ID</th>
                                <th>Resident Name</th>
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
                                <td>20230145</td>
                                <td>Rajat Pradhan</td>
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
                                <td>20230132</td>
                                <td>Sangeeta Kumari</td>
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
                                <td>20230167</td>
                                <td>Priya Sharma</td>
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

            </div>
        </div>
    </section>

    <!-- Recent Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Recent Check-In / Check-Out from Campus</a></div>

                <div class="overflow-auto">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Resident Scholar ID</th>
                                <th>Resident Name</th>
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>20230145</td>
                                <td>Rajat Pradhan</td>
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
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>20230132</td>
                                <td>Sangeeta Kumari</td>
                                <td>Science</td>
                                <td>Biotechnology</td>
                                <td>Lotus Hostel</td>
                                <td>25/05/2025</td>
                                <td>26/05/2025</td>
                                <td>5:00 PM</td>
                                <td>30/05/2025</td>
                                <td>12:00 PM</td>
                                <td>Going for internship</td>
                                <td><span style="color: green; font-weight: bold;">Admin</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>20230167</td>
                                <td>Priya Sharma</td>
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
                            </tr>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </section>

    <!--  Pending Popup-->
    <div class="modal fade" id="ViewStatus" tabindex="-1" aria-labelledby="ViewStatusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="top">
                        <div class="pop-title">Check-In / Check-Out Details</div>
                    </div>

                    <div class="middle">

                        <span class="input-set">
                            <label for="ResidentIS">Resident ID</label>
                            <input type="text" id="ResidentName" name="ResidentName" placeholder="454546213" disabled>
                        </span>

                        <span class="input-set">
                            <label for="ResidentName">Hostel Name</label>
                            <input type="text" id="ResidentName" name="ResidentName" placeholder="Hostel One" disabled>
                        </span>

                        <span class="input-set">
                            <label for="ResidentName">Floor No.</label>
                            <input type="text" id="ResidentName" name="ResidentName" placeholder="3" disabled>
                        </span>

                        <span class="input-set">
                            <label for="RoomNo">Room No.</label>
                            <input type="text" id="RoomNo" name="RoomNo" placeholder="201" disabled>
                        </span>

                        <span class="input-set">
                            <label for="Destination ">Destination</label>
                            <input type="text" id="Destination " name="Destination " placeholder="Enter Destination "
                                disabled>
                        </span>

                        <span class="input-set">
                            <label for="ExitDate&Time">Exit Date</label>
                            <input type="date" id="ExitDate&Time" name="ExitDate&Time" placeholder="20/02/25">
                        </span>

                        <span class="input-set">
                            <label for="ExpectedReturnDate&Time">Expected Return Date</label>
                            <input type="date" id="ExpectedReturnDate&Time" name="ExpectedReturnDate&Time"
                                placeholder="20/02/25" disabled>
                        </span>


                        <span class="input-set">
                            <label for="TransportMode">Transport Mode</label>
                            <select class="form-select" aria-label="Default select example" disabled>
                                <option>Select Room</option>
                                <option value="Bus">Bus </option>
                                <option value="Auto">Auto </option>
                                <option value="Personal Vehicle">Personal Vehicle </option>
                                <option value="Other" selected>Other </option>
                            </select>
                        </span>

                    </div>

                    <div class="full-width-i">
                        <span class="input-set">
                            <label for="Purpose">Purpose of Exit</label>
                            <textarea name="Purpose" id="Purpose" placeholder="Lorem lorem Lorem loremLorem"
                                disabled></textarea>
                        </span>
                    </div>

                    <div class="full-width-i">
                        <span class="input-set">
                            <label for="Remarks ">Remarks</label>
                            <textarea name="Remarks " id="Remarks " placeholder="Lorem lorem Lorem loremLorem"
                                disabled></textarea>
                        </span>
                    </div>


                    <div class="full-width-i">

                        <span class="input-set">
                            <label for="Reason">Write Message</label>
                            <input type="text" id="Reason" name="Reason"
                                placeholder="We are approve your request. Please contact us for further information ">
                        </span>
                    </div>

                    <div class="bottom-btn">
                        <button type="button" class="red" data-bs-dismiss="modal" aria-label="Close"> Reject
                        </button>
                        <button type="button" class="green"> Approve
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection