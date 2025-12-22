






<!-- Resident Dashboard -->
<p>Resident Dashboard</p>
<ul class="nav">
    {{-- <li><a href="{{ url('/resident/dashboard') }}">
             <span><i class="fas fa-home"></i></span>Home Dashboard</a>
     </li> --}}
    <li  class="{{ Request::is('resident/dashboard') ? 'active' : '' }}">
        <a href="{{ url('/resident/dashboard') }}"
            class="nav-link {{ Request::is('resident/dashboard') ? 'active' : '' }}">
            <span><i class="fas fa-home"></i></span>Home Dashboard
        </a>
    </li>

</ul>

<!-- Leave & Room Services -->
<p>Leave & Room Services</p>
<ul class="nav">
    <li class="{{ Request::is('resident/leave-request') ? 'active' : '' }}">
        <a href="{{ url('/resident/leave-request') }}">
            <span><i class="fas fa-calendar-alt"></i></span>Submit Leave Request
        </a>
    </li>
    <li class="{{ Request::is('resident/leave_request_status') ? 'active' : '' }}">
        <a href="{{ url('/resident/leave_request_status') }}">
            <span><i class="fas fa-hourglass-half"></i></span>Track Leave Status
        </a>
    </li>
    <li class="{{ Request::is('resident/room-change') ? 'active' : '' }}">
        <a href="{{ url('/resident/room-change') }}">
            <span><i class="fas fa-exchange-alt"></i></span>Request Room Change
        </a>
    </li>
    <li class="{{ Request::is('resident/room_change_status') ? 'active' : '' }}">
        <a href="{{ url('/resident/room_change_status') }}">
            <span><i class="fas fa-home text-warning"></i></span>Room Change Status
        </a>
    </li>
</ul>





<!-- Grievance & Feedback -->
<p>Grievance & Feedback</p>
<ul class="nav">
    <li><a href="{{ route('resident.submit_grievance') }}">
            <span><i class="fas fa-exclamation-circle"></i></span>Submit Grievance</a>
    </li>
    <li><a href="{{ url('/resident/grievance_status') }}">
            <span><i class="fas fa-file-alt"></i></span>Grievance Status</a>
    </li>
    <li><a href="{{ url('/resident/feedback') }}">
            <span><i class="fas fa-comment-dots"></i></span>Submit Feedback</a>
    </li>
</ul>

<!-- Payments & Notices -->
<p>Payments & Notices</p>
<ul class="nav">
    <li><a href="{{ url('/resident/payment') }}">
            <span><i class="fas fa-money-bill"></i></span>Pending Payments</a>
    </li>
    <li><a href="{{ url('/resident/fine') }}">
            <span><i class="fas fa-gavel text-danger"></i></span>Fine Payment Details</a>
    </li>
    <li><a href="{{ url('/resident/notices') }}">
            <span><i class="fas fa-bell"></i></span>View Notices</a>
    </li>
</ul>

<p>Accessories</p>
<ul class="nav">
    <li><a href="{{ url('/resident/accessories') }}">
            <span><i class="fas fa-tools"></i></span>Manage Accessories</a>
    </li>
</ul>


<!-- Subscription & Checkout -->
<p>Subscription & Checkout</p>
<ul class="nav">
    <!-- Optional: Uncomment if needed -->
    <!-- <li><a href="{{ url('/resident/subscription') }}">
            <span><i class="fas fa-clipboard-list"></i></span>Manage Subscription</a>
        </li> -->
    <li><a href="{{ url('/resident/subscription_type') }}">
            <span><i class="fas fa-list-alt"></i></span>Subscription Options</a>
    </li>
    <li><a href="{{ route('resident.checkout') }}">
            <span><i class="fas fa-door-open"></i></span>Initiate Checkout</a>
    </li>
    <li><a href="{{ route('resident.checkout.status') }}">
            <span><i class="fas fa-clipboard-check"></i></span>Checkout Status</a>
    </li>
    {{-- <li><a href="#">
             <span><i class="fas fa-sign-out-alt"></i></span>Log Out</a>
     </li> --}}
</ul>
