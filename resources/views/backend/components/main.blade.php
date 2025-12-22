    <!-- Overview -->
    <p>Overview</p>
    <ul class="nav">
        <li>
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" id="dashboardTab">
                <span><i class="bi bi-speedometer2 color_change"></i></span> Dashboard
            </a>
        </li>
    </ul>



    <!-- Hostel Operations -->
    <p>Hostel Operations</p>
    <ul class="nav">

        <!-- Hostel Management -->
        <li>
            <a href="{{ route('admin.building') }}"
                class="nav-link {{ request()->routeIs('admin.building') ? 'active' : '' }}" id="buildingsTab">
                <span><i class="bi bi-building color_change"></i></span> Hostel Management
            </a>
        </li>

        <!-- Room Management -->
        <li>
            <a href="{{ route('admin.rooms') }}"
                class="nav-link {{ request()->routeIs('admin.rooms') ? 'active' : '' }}" id="roomsTab">
                <span><i class="bi bi-door-open color_change"></i></span> Room Management
            </a>
        </li>

        <!-- Bed Management -->
        <li>
            <a href="{{ route('admin.beds') }}" class="nav-link {{ request()->routeIs('admin.beds') ? 'active' : '' }}"
                id="bedsTab">
                <span><i class="bi bi-file-break-fill color_change"></i></span> Bed Management
            </a>
        </li>

        <!-- Bed Assignment -->
        <li>
            <a href="{{ route('admin.assignbed') }}"
                class="nav-link {{ request()->routeIs('admin.assignbed') ? 'active' : '' }}" id="assignbedTab">
                <span><i class="bi bi-arrow-left-right color_change"></i></span> Bed Assignment
            </a>
        </li>

        <!-- Campus Check In-Out -->
        <li>
            <a href="{{ route('admin.create_faculties') }}"
                class="nav-link {{ request()->routeIs('admin.create_faculties') ? 'active' : '' }}" id="checkInOutTab">
                <i class="fa-solid fa-person-walking color_change"></i> Campus Check In-Out
            </a>
        </li>

        <!-- Mess Attendance -->
        <li>
            <a href="{{ route('admin.create_departments') }}"
                class="nav-link {{ request()->routeIs('admin.create_departments') ? 'active' : '' }}"
                id="MessAttendanceTab">
                <i class="fa-solid fa-building-user color_change"></i> Mess Attendance
            </a>
        </li>

        <!-- User Management -->
        <li>
            <a href="{{ route('admin.create_courses') }}"
                class="nav-link {{ request()->routeIs('admin.create_courses') ? 'active' : '' }}"
                id="UserManagementTab">
                <i class="fa-solid fa-user color_change"></i> User Management
            </a>
        </li>

        <!-- Admins -->
        <li>
            <a href="{{ route('admin.admin_list') }}"
                class="nav-link {{ request()->routeIs('admin.admin_list') ? 'active' : '' }}" id="adminTab">
                <span><i class="bi bi-person-badge color_change"></i></span> Admins
            </a>
        </li>

        <!-- HODs -->
        <li>
            <a href="{{ route('admin.hods') }}" class="nav-link {{ request()->routeIs('admin.hods') ? 'active' : '' }}"
                id="hodsTab">
                <span><i class="bi bi-person-badge color_change"></i></span> HODs
            </a>
        </li>

        <!-- Staff -->
        <li>
            <a href="{{ route('admin.staff') }}"
                class="nav-link {{ request()->routeIs('admin.staff') ? 'active' : '' }}" id="staffTab">
                <span><i class="bi bi-person-badge color_change"></i></span> Staff
            </a>
        </li>
    </ul>

    <!-- Academic Operations -->
    <p>Academic Operations</p>
    <ul class="nav">
        <!-- Faculty Management -->
        <li>
            <a href="{{ route('admin.faculties') }}"
                class="nav-link {{ request()->routeIs('admin.faculties') ? 'active' : '' }}" id="facultiesTab">
                <span><i class="bi bi-building color_change"></i></span> Faculty Management
            </a>
        </li>

        <!-- Department Management -->
        <li>
            <a href="{{ route('admin.departments') }}"
                class="nav-link {{ request()->routeIs('admin.departments') ? 'active' : '' }}" id="departmentsTab">
                <span><i class="bi bi-door-open color_change"></i></span> Department Management
            </a>
        </li>

        <!-- Courses Management -->
        <li>
            <a href="{{ route('admin.courses') }}"
                class="nav-link {{ request()->routeIs('admin.courses') ? 'active' : '' }}" id="coursesTab">
                <span><i class="bi bi-folder color_change"></i></span> Courses Management
            </a>
        </li>
    </ul>

    <!-- Resident & Guest Management -->
    <p>Resident & Guest Management</p>
    <ul class="nav">

        <!-- Residents -->
        <li>
            <a href="{{ route('admin.residents') }}"
                class="nav-link {{ request()->routeIs('admin.residents') ? 'active' : '' }}" id="residentsTab">
                <span><i class="bi bi-people color_change"></i></span> Residents
            </a>
        </li>

        <!-- Pending Guests -->
        <li>
            <a href="{{ route('guest.pending') }}"
                class="nav-link {{ request()->routeIs('guest.pending') ? 'active' : '' }}" id="pendingGuestsTab">
                <span><i class="bi bi-person-exclamation color_change"></i></span> Pending Guests
            </a>
        </li>

        <!-- Paid Guests -->
        <li>
            <a href="{{ route('admin.paid.guests') }}"
                class="nav-link {{ request()->routeIs('admin.paid.guests') ? 'active' : '' }}" id="paidGuestsTab">
                <span><i class="bi bi-currency-rupee color_change"></i></span> Paid Guests
            </a>
        </li>

        <!-- Leave Requests -->
        <li>
            <a href="{{ route('admin.leave_requests') }}"
                class="nav-link {{ request()->routeIs('admin.leave_requests') ? 'active' : '' }}" id="leaveRequestsTab">
                <span><i class="bi bi-calendar-x color_change"></i></span> Leave Requests
            </a>
        </li>

        <!-- Room Change Requests -->
        <li>
            <a href="{{ route('admin.room_change') }}"
                class="nav-link {{ request()->routeIs('admin.room_change') ? 'active' : '' }}" id="roomChangesTab">
                <span><i class="bi bi-arrow-repeat color_change"></i></span> Room Change Requests
            </a>
        </li>

        <!-- Final Checkout Requests -->
        <li>
            <a href="{{ route('admin.checkout') }}"
                class="nav-link {{ request()->routeIs('admin.checkout') ? 'active' : '' }}" id="checkoutRequestsTab">
                <span><i class="bi bi-box-arrow-right color_change"></i></span> Final Checkout Requests
            </a>
        </li>
    </ul>


    <!-- Financial Management -->
    <p>Financial Management</p>
    <ul class="nav">

        <!-- Pending Payments -->
        <li>
            <a href="{{ route('admin.pendingpayments') }}"
                class="nav-link {{ request()->routeIs('admin.pendingpayments') ? 'active' : '' }}" id="pendingpayments">
                <span><i class="bi bi-cash-coin color_change"></i></span> Pending Payments
            </a>
        </li>

        <!-- Assign Fine -->
        <li>
            <a href="{{ url('/admin/fine') }}" class="nav-link {{ request()->is('admin/fine') ? 'active' : '' }}"
                id="adminAssignFine">
                <span><i class="bi bi-cash-coin color_change"></i></span> Assign Fine
            </a>
        </li>
    </ul>

    <!-- Assets & Services -->
    <p>Assets & Services</p>
    <ul class="nav">

        <!-- Accessories -->
        <li>
            <a href="{{ route('admin.accessories') }}"
                class="nav-link {{ request()->routeIs('admin.accessories') ? 'active' : '' }}" id="accessoriesTab">
                <span><i class="bi bi-box-seam color_change"></i></span> Accessories
            </a>
        </li>

        <!-- Send Accessory -->
        <li>
            <a href="{{ url('/admin/add-accessory') }}"
                class="nav-link {{ request()->is('admin/add-accessory') ? 'active' : '' }}"
                id="adminSendAccessoryToResident">
                <span><i class="bi bi-person-plus color_change"></i></span> Send Accessory
            </a>
        </li>

        <!-- Subscribe Resident -->
        <li>
            <a href="{{ url('/admin/subscribe-resident') }}"
                class="nav-link {{ request()->is('admin/subscribe-resident') ? 'active' : '' }}"
                id="subscribeResidentTab">
                <span><i class="bi bi-person-plus color_change"></i></span> Subscribe Resident
            </a>
        </li>
    </ul>

    <!-- Communication -->
    <p>Communication</p>
    <ul class="nav">

        <!-- Grievances -->
        <li>
            <a href="{{ route('admin.grievances') }}"
                class="nav-link {{ request()->routeIs('admin.grievances') ? 'active' : '' }}" id="grievancesTab">
                <span><i class="bi bi-exclamation-circle color_change"></i></span> Grievances
            </a>
        </li>

        <!-- Feedback -->
        <li>
            <a href="{{ route('admin.feedbacks') }}"
                class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}" id="feedbackTab">
                <span><i class="bi bi-chat-dots color_change"></i></span> Feedback
            </a>
        </li>

        <!-- Notices -->
        <li>
            <a href="{{ route('admin.notices') }}"
                class="nav-link {{ request()->routeIs('admin.notices') ? 'active' : '' }}" id="noticesTab">
                <span><i class="bi bi-megaphone color_change"></i></span> Notices
            </a>

        </li>
    </ul>
