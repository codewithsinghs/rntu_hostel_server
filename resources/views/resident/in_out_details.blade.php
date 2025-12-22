@extends('resident.layout')

@section('content')
    <!-- Data Card -->

    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">

                <!-- breadcrumbs -->
                <div class="breadcrumbs"><a href="">Check-In / Check-Out Overview</a></div>

                <!-- Check-In / Check-Out Overview -->
                <div class="card-ds">

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Check-Out</p>
                            <h3>1522</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/Hostel Management.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Check-In</p>
                            <h3>206 </h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/logout.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Upcoming Check-outs</p>
                            <h3>232 </h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/pending.png') }}" alt="" />
                        </div>
                    </div>

                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Pending Check-Out</p>
                            <h3>23</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/date.png') }}" alt="" />
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
                <div id="response-message"></div>

                <!-- Collapse toggle button -->
                <button class="w-100 d-flex justify-content-between align-items-center" type="button"
                    data-bs-toggle="collapse" data-bs-target="#gatePassRequestCollapse" aria-expanded="false"
                    aria-controls="gatePassRequestCollapse">

                    <span class="breadcrumbs">Daily In - Outs / Gate Pass</span>
                    <span class="btn btn-primary">Gate Pass Request</span>

                </button>

                <!-- Collapsible section (collapsed by default) -->
                <div class="collapse" id="gatePassRequestCollapse">
                    <!-- Form -->
                    <form id="checkoutForm">

                        @csrf

                        <div class="inpit-boxxx">

                            <span class="input-set">
                                <label for="Checking-Out Purpose ">Checking-Out Purpose</label>
                                <input type="text" id="Checking-Out Purpose " name="Checking-Out Purpose "
                                    placeholder="Enter Checking-Out Purpose ">
                            </span>

                            <span class="input-set">
                                <label for="Destination ">Destination</label>
                                <input type="text" id="Destination " name="Destination "
                                    placeholder="Enter Destination ">
                            </span>

                            <span class="input-set">
                                <label for="date">Check-Out Date</label>
                                <input type="date" id="date" name="date" placeholder="20/02/25">
                            </span>

                            <span class="input-set">
                                <label for="ExpectedReturnDate&Time">Expected Return Date</label>
                                <input type="date" id="ExpectedReturnDate&Time" name="ExpectedReturnDate&Time"
                                    placeholder="20/02/25">
                            </span>


                            <span class="input-set">
                                <label for="TransportMode">Transport Mode (Optional) </label>
                                <select class="form-select" aria-label="Default select example">
                                    <option selected>Select Transport Mode</option>
                                    <option value="Bus">Bus </option>
                                    <option value="Auto">Auto </option>
                                    <option value="Personal Vehicle">Personal Vehicle </option>
                                    <option value="Other">Other </option>
                                </select>
                            </span>




                            <div class="reason">
                                <label for="reason">Purpose of Checking-Out</label>
                                <textarea name="reason" id="reason" placeholder="Purpose "></textarea>
                            </div>


                            <div class="reason">
                                <label for="photo" class="form-label">Supporting Photo/Document (Optional):</label>
                                <input type="file" id="photo" name="photo" accept="image/*">
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
                <div class="breadcrumbs"><a href="">Gate Pass Request List</a></div>

                <div class="overflow-auto">
                    <table class="status-table" cellspacing="0" cellpadding="8" width="100%">
                        <thead>
                            <tr>
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
                                <td>25/05/2025</td>
                                <td>26/05/2025</td>
                                <td>5:00 PM</td>
                                <td>30/05/2025</td>
                                <td>12:00 PM</td>
                                <td>Going for internship</td>
                                <td><span style="color: green; font-weight: bold;">Admin</span></td>
                                <td><button class="view-btn">Cancel Request</button></td>
                            </tr>
                            <tr>
                                <td>25/05/2025</td>
                                <td>26/05/2025</td>
                                <td>5:00 PM</td>
                                <td>30/05/2025</td>
                                <td>12:00 PM</td>
                                <td>Going for internship</td>
                                <td><span style="color: orange; font-weight: bold;">Pending</span></td>
                                <td><button class="view-btn">Cancel Request</button></td>
                            </tr>
                            <tr>
                                <td>25/05/2025</td>
                                <td>26/05/2025</td>
                                <td>5:00 PM</td>
                                <td>30/05/2025</td>
                                <td>12:00 PM</td>
                                <td>Going for internship</td>
                                <td><span style="color: red; font-weight: bold;">Rejected</span></td>
                                <td><button class="view-btn">Cancel Request</button></td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </section>
@endsection
