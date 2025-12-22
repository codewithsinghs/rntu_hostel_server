

<!-- Resident Dashboard -->
<p>Resident Dashboard</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/dashboard') }}" class="{{ Request::is('resident/dashboard') ? 'active' : '' }}">
            <span><i class="fas fa-home"></i></span>Home Dashboard
        </a>
    </li>
</ul>

<!-- Leave & Room Services -->
<p>Leave & Room Services</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/leave-request') }}"
            class="{{ Request::is('resident/leave-request') ? 'active' : '' }}">
            <span><i class="fas fa-calendar-alt"></i></span>Submit Leave Request
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/leave_request_status') }}"
            class="{{ Request::is('resident/leave_request_status') ? 'active' : '' }}">
            <span><i class="fas fa-hourglass-half"></i></span>Track Leave Status
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/room-change') }}" class="{{ Request::is('resident/room-change') ? 'active' : '' }}">
            <span><i class="fas fa-exchange-alt"></i></span>Request Room Change
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/room_change_status') }}"
            class="{{ Request::is('resident/room_change_status') ? 'active' : '' }}">
            <span><i class="fas fa-home text-warning"></i></span>Room Change Status
        </a>
    </li>
</ul>

<!-- Grievance & Feedback -->
<p>Grievance & Feedback</p>
<ul class="nav">
    <li>
        <a href="{{ route('resident.submit_grievance') }}"
            class="{{ Request::is('resident/submit_grievance') ? 'active' : '' }}">
            <span><i class="fas fa-exclamation-circle"></i></span>Submit Grievance
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/grievance_status') }}"
            class="{{ Request::is('resident/grievance_status') ? 'active' : '' }}">
            <span><i class="fas fa-file-alt"></i></span>Grievance Status
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/feedback') }}" class="{{ Request::is('resident/feedback') ? 'active' : '' }}">
            <span><i class="fas fa-comment-dots"></i></span>Submit Feedback
        </a>
    </li>
</ul>

<!-- Payments & Notices -->
<p>Payments & Notices</p>
<ul class="nav">
    <li>
        <a href="{{ url('resident/payment') }}" class="{{ Request::is('resident/payment') ? 'active' : '' }}">
            <span><i class="fas fa-money-bill"></i></span>Pending Payments
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/fine') }}" class="{{ Request::is('resident/fine') ? 'active' : '' }}">
            <span><i class="fas fa-gavel text-danger"></i></span>Fine Payment Details
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/notices') }}" class="{{ Request::is('resident/notices') ? 'active' : '' }}">
            <span><i class="fas fa-bell"></i></span>View Notices
        </a>
    </li>
</ul>

<!-- Accessories -->
<p>Accessories</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/accessories') }}"
            class="{{ Request::is('resident/accessories') ? 'active' : '' }}">
            <span><i class="fas fa-tools"></i></span>Manage Accessories
        </a>
    </li>
</ul>

<!-- Subscription & Checkout -->
<p>Subscription & Checkout</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/subscription_type') }}"
            class="{{ Request::is('resident/subscription_type') ? 'active' : '' }}">
            <span><i class="fas fa-list-alt"></i></span>Subscription Options
        </a>
    </li>
    <li>
        <a href="{{ route('resident.checkout') }}" class="{{ Request::is('resident/checkout') ? 'active' : '' }}">
            <span><i class="fas fa-door-open"></i></span>Initiate Checkout
        </a>
    </li>
    <li>
        <a href="{{ route('resident.checkout.status') }}"
            class="{{ Request::is('resident/checkout/status') ? 'active' : '' }}">
            <span><i class="fas fa-clipboard-check"></i></span>Checkout Status
        </a>
    </li>
</ul>
