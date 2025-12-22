<!-- Overview -->
<p>Overview</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/dashboard') }}" class="{{ Request::is('resident/dashboard') ? 'active' : '' }}">
            <span><i class="fa-solid fa-bars-progress"></i></span>Dashboard
        </a>
    </li>
</ul>

<!-- My Hostel -->
<p>My Hostel</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/room-details') }}" class="{{ Request::is('resident/room-details') ? 'active' : '' }}">
            <span><i class="fa-solid fa-house"></i></span>My Room Details
        </a>
    </li>
    {{-- <li>
        <a href="{{ url('/resident/in-out-details') }}"
            class="{{ Request::is('resident/in-out-details') ? 'active' : '' }}">
            <span><i class="fa-solid fa-hotel"></i></span>Daily In Outs
        </a>
    </li> --}}
    <!-- <li>
        <a href="{{ url('/resident/room_change_status') }}"
            class="{{ Request::is('resident/room_change_status') ? 'active' : '' }}">
            <span><i class="fas fa-home text-warning"></i></span>Room Change Status
        </a>
    </li> -->
    <li>
        <a href="{{ url('/resident/accessories') }}" class="{{ Request::is('resident/accessories') ? 'active' : '' }}">
            <span><i class="fas fa-tools"></i></span>Accessories
        </a>
    </li>
</ul>

<!-- Attendance & Leave  -->
<p>Attendance & Leave </p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/leave-request') }}"
            class="{{ Request::is('resident/leave-request') ? 'active' : '' }}">
            <span><i class="fas fa-calendar-alt"></i></span>Leaves
        </a>
    </li>
</ul>

<!-- Grievance & Feedback -->
<p>Support & Services</p>
<ul class="nav">
    <li>
        <a href="{{ route('resident.grievances') }}" class="{{ Request::is('resident/grievances') ? 'active' : '' }}">
            <span><i class="fas fa-exclamation-circle"></i></span>Grievances / Complaints
        </a>
    </li>
    <!-- <li>
        <a href="{{ url('/resident/grievance_status') }}"
            class="{{ Request::is('resident/grievance_status') ? 'active' : '' }}">
            <span><i class="fas fa-file-alt"></i></span>Grievance Status
        </a>
    </li> -->
    <li>
        <a href="{{ url('/resident/feedback') }}" class="{{ Request::is('resident/feedback') ? 'active' : '' }}">
            <span><i class="fas fa-comment-dots"></i></span>Feedback
        </a>
    </li>
</ul>

<!-- Payments & Notices -->
<p>Payments & Notices</p>
<ul class="nav">
    <li>
        <a href="{{ url('resident/payment') }}" class="{{ Request::is('resident/payment') ? 'active' : '' }}">
            <span><i class="fas fa-money-bill"></i></span>Payment History
        </a>
    </li>
    <li>
        <a href="{{ url('/resident/fine') }}" class="{{ Request::is('resident/fine') ? 'active' : '' }}">
            <span><i class="fas fa-gavel"></i></span>Fine
        </a>
    </li>

</ul>

<!-- Subscription & Checkout -->
<p>Subscription & Checkout</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/subscriptions') }}"
            class="{{ Request::is('resident/subscriptions') ? 'active' : '' }}">
            <span><i class="fas fa-list-alt"></i></span>Subscriptions
        </a>
    </li>
    <!-- <li>
        <a href="{{ route('resident.checkout') }}" class="{{ Request::is('resident/checkout') ? 'active' : '' }}">
            <span><i class="fas fa-door-open"></i></span>Initiate Checkout
        </a>
    </li>-->
    <li>
        <a href="{{ route('resident.checkout') }}" class="{{ Request::is('resident/checkout') ? 'active' : '' }}">
            <span><i class="fas fa-clipboard-check"></i></span>Final Checkout
        </a>
    </li>
</ul>

<!-- Visitors -->
{{-- <p>Visitors</p>
<ul class="nav">

    <li>
        <a href="{{ route('resident.visitors') }}" class="{{ Request::is('resident/visitors') ? 'active' : '' }}">
            <span><i class="fa-solid fa-person-walking-luggage"></i></span>Visitor/Guest Request
        </a>
    </li> --}}
</ul>

{{-- <!-- Communication & Alerts -->
<p>Communication & Alerts</p>
<ul class="nav">
    <li>
        <a href="{{ url('/resident/notices') }}" class="{{ Request::is('resident/notices') ? 'active' : '' }}">
            <span><i class="fas fa-bell"></i></span>Notification & Alerts
        </a>
    </li>
</ul> --}}
