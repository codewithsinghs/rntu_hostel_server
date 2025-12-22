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
                <div class="breadcrumbs"><a>Live Attendance Details</a></div>

                <div class="card-ds">
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Total Students</p>
                            <h3>53,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/student 1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>In Hostel</p>
                            <h3>49,280</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/bunk-bed.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>Outside Hostel</p>
                            <h3>3,720</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/walk.png') }}" alt="">
                        </div>
                    </div>
                    <div class="card-d">
                        <div class="card-d-content">
                            <p>New Register</p>
                            <h3>1,000</h3>
                        </div>
                        <div class="card-d-image">
                            <img src="{{ asset('backend/img/dashboard/add.png') }}" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="common-con-tainer">
        <div class="common-content">
            <section class="attendance-section">
                <div class="attendance-comparison">
                    <div class="attendance-comparison-header">
                        <h4>Attendance Comparison Chart</h4>
                        <div class="attendance-toggle">
                            <label><input type="radio" name="attendanceToggle" checked />
                                Daily</label>
                            <label><input type="radio" name="attendanceToggle" /> Weekly</label>
                            <label><input type="radio" name="attendanceToggle" /> Monthly</label>
                        </div>
                    </div>
                    <div id="dailyAttendanceChart"></div>
                </div>

                <div class="weekly-attendance">
                    <div class="header">
                        <h4>Weekly Attendance</h4>
                        <div class="date-box">29 July 2025</div>
                    </div>
                    <div id="weeklyAttendanceChart"></div>
                </div>
            </section>
        </div>
    </section>

    <!-- Thumb Time Table -->
    <section class="common-con-tainer">
        <div class="common-content">
            <div class="common-overview">
                <div class="breadcrumbs"><a>Mess Check In-Out Thumb Time</a></div>

                <div class="overflow-auto">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Student Name</th>
                                <th>Scholar Number</th>
                                <th>Hostel</th>
                                <th>Room Number</th>
                                <th>Check Out Thump Time</th>
                                <th>Check In Thump Time </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Rajat Pradhan</td>
                                <td>20213045</td>
                                <td>Hostel One</td>
                                <td>304</td>
                                <td>01-Aug-2025 05:55PM</td>
                                <td>01-Aug-2025 10:22PM</td>
                                <td>
                                    <a type="button" href="{{ route('admin.create_admin') }}" class="table-view-btn">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>Sangeeta</td>
                                <td>20213045</td>
                                <td>Hostel One</td>
                                <td>304</td>
                                <td>01-Aug-2025 11:22AM</td>
                                <td>01-Aug-2025 12:02PM</td>
                                <td>
                                    <a type="button" href="{{ route('admin.create_admin') }}" class="table-view-btn">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

@endsection