@extends('resident.layout')

@section('content')

    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Guest Overview</a></div>

                <!-- Guest Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Requests</p>
                            <h3>5</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Approved Guests</p>
                            <h3>20</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Requests</p>
                            <h3>2</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png')}}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Rejected Requests</p>
                            <h3>5</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/min.png')}}" alt="" />
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <!-- Visitors / Guests Details -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Visitors / Guests Details</a></div>

                <!-- Form -->

                <form>

                    @csrf

                    <div class="inpit-boxxx">

                        <span class="input-set">
                            <label for="GuestName">Guest Name</label>
                            <input type="text" id="GuestName" name="GuestName" placeholder="Rajat Pradhan">
                        </span>

                        <span class="input-set">
                            <label for="GuestContactNumber">Guest Contact Number</label>
                            <input type="text" id="GuestContactNumber" name="GuestContactNumber" placeholder="7024393158">
                        </span>

                        <span class="input-set">
                            <label for="VisitDate">Visit Date</label>
                            <input type="date" id="VisitDate" name="VisitDate">
                        </span>

                        <span class="input-set">
                            <label for="VisitTime">Visit Time</label>
                            <input type="time" id="VisitTIme" name="VisitTIme">
                        </span>

                        <span class="input-set">
                            <label for="Realation">Realation of Visitor</label>
                            <input type="text" id="Realation" name="Realation" placeholder="Brother">
                        </span>

                        <span class="input-set">
                            <label for="StayDuration">Stay Duration (In Days) </label>
                            <input type="text" id="StayDuration" name="StayDuration" placeholder="1 Day">
                        </span>

                    </div>

                    <div class="reason">
                        <label for="">Purpose of Visit</label>
                        <textarea placeholder="Purpose "></textarea>
                    </div>


                    <div class="reason">
                        <label for="photo" class="form-label">Upload Supporting Photo/Document (Optional):</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>

                    <button type="submit" class="submitted">Submit Request</button>


                </form>

                <!-- Form End -->

            </div>
        </div>
    </section>


    <!-- Table -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a>Recent Visitors</a></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Guest Name</th>
                                <th>Relation</th>
                                <th>Visit Date</th>
                                <th>Visit Time</th>
                                <th>Stay Duration</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>GR-101</td>
                                <td>Rahul Sharma</td>
                                <td>Brother</td>
                                <td>2025-08-15</td>
                                <td>10:00 AM</td>
                                <td>2 Hours</td>
                                <td>Meeting</td>
                                <td><span style="color: green; font-weight: bold;">Approved</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>
                            <tr>
                                <td>GR-102</td>
                                <td>Priya Verma</td>
                                <td>Sister</td>
                                <td>2025-08-12</td>
                                <td>05:00 PM</td>
                                <td>1 Night</td>
                                <td>Family Visit</td>
                                <td><span style="color: orange; font-weight: bold;">Pending</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>
                            <tr>
                                <td>GR-103</td>
                                <td>Vikram Singh</td>
                                <td>Friend</td>
                                <td>2025-08-10</td>
                                <td>03:00 PM</td>
                                <td>3 Hours</td>
                                <td>Work Discussion</td>
                                <td><span style="color: red; font-weight: bold;">Rejected</span></td>
                                <td><button class="view-btn">View Detail</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    
@endsection