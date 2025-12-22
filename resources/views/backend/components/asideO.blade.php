<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        {{-- <button id="toggle-btn-small-screen">&#9776;</button> --}}
        <h2>Hostel Management</h2>
        <!-- <button id="toggle-btn">&#9776;</button> -->
    </div>
    <input type="text" placeholder="Search" class="search-box" />

    <!-- Overview -->
    <p>Overview</p>
    <ul class="nav">
        <li class="active"><a href="index.html"><span><img src="{{ asset('backend/img/dashboard/side menu/dashboard.png') }}"></span>
                Dashboard</a></li>
    </ul>

    <!-- Hostel Operations -->
    <p>Hostel Operations</p>
    <ul class="nav">
        <li><a href="pages/Hostel Operations/HostelManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Hostel Management.png') }}"></span>Hostel Management</a></li>
        <li><a href="pages/Hostel Operations/RoomManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Room Management.png') }}"></span>Room Management</a></li>
        <li><a href="pages/Hostel Operations/BedManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Bed Management.png') }}"></span>Bed Management</a></li>
        <li><a href="pages/Hostel Operations/BedAssignment.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Bed Assignment.png') }}"></span>Bed Assignment</a></li>
        <li><a href="pages/Hostel Operations/CampusCheck In-Out.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Campus Check In-Out.png') }}"></span>Campus Check In-Out</a>
        </li>
        <li><a href="pages/Hostel Operations/MessAttendance.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Mess Attendance.png') }}"></span>Mess Attendance</a></li>
        <li><a href="pages/Hostel Operations/MessManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Mess Attendance.png') }}"></span>Mess Management</a></li>
        <li><a href="pages/Hostel Operations/UserManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/User Management.png') }}"></span>User Management</a></li>
        <li><a href="pages/Hostel Operations/StaffManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Staff Management.png') }}"></span>Staff Management</a></li>
    </ul>

    <!-- Resident Services -->
    <p>Resident Services</p>
    <ul class="nav">
        <li><a href="pages/Resident Services/ResidentsManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Residents Management.png') }}"></span>Residents
                Management</a></li>
        <li><a href="pages/Resident Services/LeaveManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Leave Management.png') }}"></span>Leave Management</a></li>
        <li><a href="pages/Resident Services/GrievancesManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Grievances Management.png') }}"></span>Grievances
                Management</a></li>
        <li><a href="pages/Resident Services/RoomAllocation.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Room Allocation.png') }}"></span>Room Allocation</a></li>
        <li><a href="pages/Resident Services/FinalCheckout.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Final Checkout.png') }}"></span>Final Checkout</a></li>
        <li><a href="pages/Resident Services/Feedback.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Feedback.png') }}"></span>Feedback</a></li>
    </ul>

    <!-- Inventory & Logistics -->
    <p>Inventory & Logistics</p>
    <ul class="nav">
        <li><a href="pages/Inventory and Logistics/InventoryManagement.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Inventory Management.png') }}"></span>Inventory
                Management</a></li>
    </ul>

    <!-- Finance & Subscription -->
    <p>Finance & Subscription</p>
    <ul class="nav">
        <li><a href="pages/Finance & Subscription/Subscription.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Subscription.png') }}"></span>Subscription</a></li>
        <li><a href="pages/Finance & Subscription/Payments&Translations.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Payments & Translations.png') }}"></span>Payments &
                Translations</a></li>
        <li><a href="pages/Finance & Subscription/Assign Fine.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Assign Fine.png') }}"></span>Assign Fine</a></li>
    </ul>

    <!-- Communication & Alerts -->
    <p>Communication & Alerts</p>
    <ul class="nav">
        <li><a href="pages/Notifications/Notification.html"><span><img
                        src="{{ asset('backend/img/dashboard/side menu/Notification & Alerts.png') }}"></span>Notification &
                Alerts</a></li>
    </ul>

    <!-- Logout Btn -->
    <ul class="nav">
        <li><button class="logout"> <img src="{{ asset('backend/img/dashboard/side menu/logout.png') }}"> Log Out</button></li>
    </ul>

</aside>
