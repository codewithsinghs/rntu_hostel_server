<!-- Dashboard -->
<p>Admin Dashboard</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.dashboard') }}"
            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" id="dashboardTab">
            <span><i class="bi bi-speedometer2"></i></span> Dashboard
        </a>
    </li>
</ul>

<!-- Master Data Management -->
<p>Master Data Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.faculties') }}"
            class="nav-link {{ request()->routeIs('admin.faculties') ? 'active' : '' }}" id="facultiesTab">
            <span><i class="bi bi-building"></i></span> Faculties
        </a>
    </li>
    <li>
        <a href="{{ route('admin.departments') }}"
            class="nav-link {{ request()->routeIs('admin.departments') ? 'active' : '' }}" id="departmentsTab">
            <span><i class="bi bi-door-open"></i></span> Departments
        </a>
    </li>
    <li>
        <a href="{{ route('admin.courses') }}"
            class="nav-link {{ request()->routeIs('admin.courses') ? 'active' : '' }}" id="coursesTab">
            <span><i class="bi bi-folder"></i></span> Courses
        </a>
    </li>
</ul>

<!-- Building & Room Management -->
<p>Building & Room Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.building') }}"
            class="nav-link {{ request()->routeIs('admin.building') ? 'active' : '' }}" id="buildingsTab">
            <span><i class="bi bi-building"></i></span> Buildings
        </a>
    </li>
    <li>
        <a href="{{ route('admin.rooms') }}" class="nav-link {{ request()->routeIs('admin.rooms') ? 'active' : '' }}"
            id="roomsTab">
            <span><i class="bi bi-door-open"></i></span> Rooms
        </a>
    </li>
    <li>
        <a href="{{ route('admin.beds') }}" class="nav-link {{ request()->routeIs('admin.beds') ? 'active' : '' }}"
            id="bedsTab">
            <span><i class="bi bi-bed-fill"></i></span> Beds
        </a>
    </li>
    <li>
        <a href="{{ route('admin.assignbed') }}"
            class="nav-link {{ request()->routeIs('admin.assignbed') ? 'active' : '' }}" id="assignbedTab">
            <span><i class="bi bi-arrow-left-right"></i></span> Assign Beds
        </a>
    </li>
</ul>

<!-- Resident & Guest Management -->
<p>Resident & Guest Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.residents') }}"
            class="nav-link {{ request()->routeIs('admin.residents') ? 'active' : '' }}" id="residentsTab">
            <span><i class="bi bi-people"></i></span> Residents
        </a>
    </li>
    <li>
        <a href="{{ route('guest.pending') }}"
            class="nav-link {{ request()->routeIs('guest.pending') ? 'active' : '' }}" id="pendingGuestsTab">
            <span><i class="bi bi-person-exclamation"></i></span> Pending Guests
        </a>
    </li>
    <li>
        <a href="{{ route('admin.paid.guests') }}"
            class="nav-link {{ request()->routeIs('admin.paid.guests') ? 'active' : '' }}" id="paidGuestsTab">
            <span><i class="bi bi-currency-rupee"></i></span> Paid Guests
        </a>
    </li>
</ul>

<!-- Request Management -->
<p>Request Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.leave_requests') }}"
            class="nav-link {{ request()->routeIs('admin.leave_requests') ? 'active' : '' }}" id="leaveRequestsTab">
            <span><i class="bi bi-calendar-x"></i></span> Leave Requests
        </a>
    </li>
    <li>
        <a href="{{ route('admin.room_change') }}"
            class="nav-link {{ request()->routeIs('admin.room_change') ? 'active' : '' }}" id="roomChangesTab">
            <span><i class="bi bi-arrow-repeat"></i></span> Room Change Requests
        </a>
    </li>
    <li>
        <a href="{{ route('admin.checkout') }}"
            class="nav-link {{ request()->routeIs('admin.checkout') ? 'active' : '' }}" id="checkoutRequestsTab">
            <span><i class="bi bi-box-arrow-right"></i></span> Checkout Requests
        </a>
    </li>
</ul>

<!-- Financial Management -->
<p>Financial Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.pendingpayments') }}"
            class="nav-link {{ request()->routeIs('admin.pendingpayments') ? 'active' : '' }}" id="pendingpayments">
            <span><i class="bi bi-cash-coin"></i></span> Pending Payments
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/fine') }}" class="nav-link {{ request()->is('admin/fine') ? 'active' : '' }}"
            id="adminAssignFine">
            <span><i class="bi bi-cash-coin"></i></span> Assign Fine
        </a>
    </li>
</ul>

<!-- Staff & Role Management -->
<p>Staff & Role Management</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.admin_list') }}"
            class="nav-link {{ request()->routeIs('admin.admin_list') ? 'active' : '' }}" id="adminTab">
            <span><i class="bi bi-person-badge"></i></span> Admins
        </a>
    </li>
    <li>
        <a href="{{ route('admin.hods') }}" class="nav-link {{ request()->routeIs('admin.hods') ? 'active' : '' }}"
            id="hodsTab">
            <span><i class="bi bi-person-badge"></i></span> HODs
        </a>
    </li>
    <li>
        <a href="{{ route('admin.staff') }}" class="nav-link {{ request()->routeIs('admin.staff') ? 'active' : '' }}"
            id="staffTab">
            <span><i class="bi bi-person-badge"></i></span> Staff
        </a>
    </li>
</ul>

<!-- Assets & Services -->
<p>Assets & Services</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.accessories') }}"
            class="nav-link {{ request()->routeIs('admin.accessories') ? 'active' : '' }}" id="accessoriesTab">
            <span><i class="bi bi-box-seam"></i></span> Accessories
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/add-accessory') }}"
            class="nav-link {{ request()->is('admin/add-accessory') ? 'active' : '' }}"
            id="adminSendAccessoryToResident">
            <span><i class="bi bi-person-plus"></i></span> Send Accessory
        </a>
    </li>
    <li>
        <a href="{{ url('/admin/subscribe-resident') }}"
            class="nav-link {{ request()->is('admin/subscribe-resident') ? 'active' : '' }}" id="subscribeResidentTab">
            <span><i class="bi bi-person-plus"></i></span> Subscribe Resident
        </a>
    </li>
</ul>

<!-- Communication -->
<p>Communication</p>
<ul class="nav">
    <li>
        <a href="{{ route('admin.grievances') }}"
            class="nav-link {{ request()->routeIs('admin.grievances') ? 'active' : '' }}" id="grievancesTab">
            <span><i class="bi bi-exclamation-circle"></i></span> Grievances
        </a>
    </li>
    <li>
        <a href="{{ route('admin.feedbacks') }}"
            class="nav-link {{ request()->routeIs('admin.feedbacks') ? 'active' : '' }}" id="feedbackTab">
            <span><i class="bi bi-chat-dots"></i></span> Feedback
        </a>
    </li>
    <li>
        <a href="{{ route('admin.notices') }}"
            class="nav-link {{ request()->routeIs('admin.notices') ? 'active' : '' }}" id="noticesTab">
            <span><i class="bi bi-megaphone"></i></span> Notices
        </a>

    </li>
</ul>
