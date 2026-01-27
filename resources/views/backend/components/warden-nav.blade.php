<!-- Dashboard -->
<p>Wardeb Dashboard</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.dashboard') }}"
            class="nav-link {{ request()->routeIs('warden.dashboard') ? 'active' : '' }}" id="dashboardTab">
            <span><i class="bi bi-speedometer2"></i></span> Dashboard
        </a>
    </li>
</ul>

<!-- Master Data Management -->
<!-- <p>Master Data Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.faculties') }}"
            class="nav-link {{ request()->routeIs('warden.faculties') ? 'active' : '' }}" id="facultiesTab">
            <span><i class="bi bi-building"></i></span> Faculties
        </a>
    </li>
    <li>
        <a href="{{ route('warden.departments') }}"
            class="nav-link {{ request()->routeIs('warden.departments') ? 'active' : '' }}" id="departmentsTab">
            <span><i class="bi bi-door-open"></i></span> Departments
        </a>
    </li>
    <li>
        <a href="{{ route('warden.courses') }}"
            class="nav-link {{ request()->routeIs('warden.courses') ? 'active' : '' }}" id="coursesTab">
            <span><i class="bi bi-folder"></i></span> Courses
        </a>
    </li>
</ul> -->

<!-- Building & Room Management -->
<p>Building & Room Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.building') }}"
            class="nav-link {{ request()->routeIs('warden.building') ? 'active' : '' }}" id="buildingsTab">
            <span><i class="bi bi-building"></i></span> Buildings
        </a>
    </li>
    <li>
        <a href="{{ route('warden.rooms') }}" class="nav-link {{ request()->routeIs('warden.rooms') ? 'active' : '' }}"
            id="roomsTab">
            <span><i class="bi bi-door-open"></i></span> Rooms
        </a>
    </li>
    <li>
        <a href="{{ route('warden.beds') }}" class="nav-link {{ request()->routeIs('warden.beds') ? 'active' : '' }}"
            id="bedsTab">
            <span><i class="bi bi-bed-fill"></i></span> Beds
        </a>
    </li>
    <li>
        <a href="{{ route('warden.assignbed') }}"
            class="nav-link {{ request()->routeIs('warden.assignbed') ? 'active' : '' }}" id="assignbedTab">
            <span><i class="bi bi-arrow-left-right"></i></span> Assign Beds
        </a>
    </li>
</ul>

<!-- Resident & Guest Management -->
<p>Resident & Guest Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.residents') }}"
            class="nav-link {{ request()->routeIs('warden.residents') ? 'active' : '' }}" id="residentsTab">
            <span><i class="bi bi-people"></i></span> Residents
        </a>
    </li>
    <li>
        <a href="{{ route('warden.guest.pending') }}"
            class="nav-link {{ request()->routeIs('warden.guest.pending') ? 'active' : '' }}" id="pendingGuestsTab">
            <span><i class="bi bi-person-exclamation"></i></span> Pending Guests
        </a>
    </li>
    <li>
        <a href="{{ route('warden.paid.guests') }}"
            class="nav-link {{ request()->routeIs('warden.paid.guests') ? 'active' : '' }}" id="paidGuestsTab">
            <span><i class="bi bi-currency-rupee"></i></span> Paid Guests
        </a>
    </li>
</ul>

<!-- Request Management -->
<p>Request Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.leave_requests') }}"
            class="nav-link {{ request()->routeIs('warden.leave_requests') ? 'active' : '' }}" id="leaveRequestsTab">
            <span><i class="bi bi-calendar-x"></i></span> Leave Requests
        </a>
    </li>
    <li>
        <a href="{{ route('warden.room_change') }}"
            class="nav-link {{ request()->routeIs('warden.room_change') ? 'active' : '' }}" id="roomChangesTab">
            <span><i class="bi bi-arrow-repeat"></i></span> Room Change Requests
        </a>
    </li>
    <li>
        <a href="{{ route('warden.checkout') }}"
            class="nav-link {{ request()->routeIs('warden.checkout') ? 'active' : '' }}" id="checkoutRequestsTab">
            <span><i class="bi bi-box-arrow-right"></i></span> Checkout Requests
        </a>
    </li>
</ul>

<!-- Financial Management -->
<p>Financial Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.pendingpayments') }}"
            class="nav-link {{ request()->routeIs('warden.pendingpayments') ? 'active' : '' }}" id="pendingpayments">
            <span><i class="bi bi-cash-coin"></i></span> Pending Payments
        </a>
    </li>
    <li>
        <a href="{{ url('/warden/fine') }}" class="nav-link {{ request()->is('warden/fine') ? 'active' : '' }}"
            id="wardenAssignFine">
            <span><i class="bi bi-cash-coin"></i></span> Assign Fine
        </a>
    </li>
</ul>

<!-- Staff & Role Management -->
<!-- <p>Staff & Role Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.admin_list') }}"
            class="nav-link {{ request()->routeIs('warden.admin_list') ? 'active' : '' }}" id="adminTab">
            <span><i class="bi bi-person-badge"></i></span> Admins
        </a>
    </li>
    <li>
        <a href="{{ route('warden.hods') }}" class="nav-link {{ request()->routeIs('warden.hods') ? 'active' : '' }}"
            id="hodsTab">
            <span><i class="bi bi-person-badge"></i></span> HODs
        </a>
    </li>
    <li>
        <a href="{{ route('warden.staff') }}" class="nav-link {{ request()->routeIs('warden.staff') ? 'active' : '' }}"
            id="staffTab">
            <span><i class="bi bi-person-badge"></i></span> Staff
        </a>
    </li>
</ul> -->

<!-- Assets & Services -->
<p>Assets & Services</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.accessories') }}"
            class="nav-link {{ request()->routeIs('warden.accessories') ? 'active' : '' }}" id="accessoriesTab">
            <span><i class="bi bi-box-seam"></i></span> Accessories
        </a>
    </li>
    <li>
        <a href="{{ url('/warden/add-accessory') }}"
            class="nav-link {{ request()->is('warden/add-accessory') ? 'active' : '' }}"
            id="adminSendAccessoryToResident">
            <span><i class="bi bi-person-plus"></i></span> Send Accessory
        </a>
    </li>
    <!-- <li>
        <a href="{{ url('/warden/subscribe-resident') }}"
            class="nav-link {{ request()->is('warden/subscribe-resident') ? 'active' : '' }}" id="subscribeResidentTab">
            <span><i class="bi bi-person-plus"></i></span> Subscribe Resident
        </a>
    </li> -->
</ul>

<!-- Communication -->
<p>Communication</p>
<ul class="nav">
    <li>
        <a href="{{ route('warden.grievances') }}"
            class="nav-link {{ request()->routeIs('warden.grievances') ? 'active' : '' }}" id="grievancesTab">
            <span><i class="bi bi-exclamation-circle"></i></span> Grievances
        </a>
    </li>
    <li>
        <a href="{{ route('warden.feedbacks') }}"
            class="nav-link {{ request()->routeIs('warden.feedbacks') ? 'active' : '' }}" id="feedbackTab">
            <span><i class="bi bi-chat-dots"></i></span> Feedback
        </a>
    </li>
    <li>
        <a href="{{ route('warden.notices') }}"
            class="nav-link {{ request()->routeIs('warden.notices') ? 'active' : '' }}" id="noticesTab">
            <span><i class="bi bi-megaphone"></i></span> Notices
        </a>
    </li>
</ul>
