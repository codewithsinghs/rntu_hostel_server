
<!-- Dashboard -->
{{-- <a href="{{ route('accountant.dashboard') }}"
            class="{{ request()->routeIs('accountant.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a> --}}

<!-- Accountant Dashboard -->
<p>Accountant Dashboard</p>
<ul class="nav">
    <li>
        <a href="{{ route('accountant.dashboard') }}"
            class="nav-link {{ Request::is('accountant/dashboard') ? 'active' : '' }}">
            <span><i class="fas fa-home"></i></span>Home Dashboard
        </a>
    </li>
</ul>

<!-- Financial Management -->
<p>Financial Management</p>
<ul class="nav">
    {{-- <li>
        <a href="{{ route('accountant.fees') }}" class="nav-link {{ Request::is('accountant/fees') ? 'active' : '' }}">
            <span><i class="fas fa-coins"></i></span>Fee Collection
        </a>
    </li> --}}
    {{-- <li>
        <a href="{{ route('accountant.pendingpayments') }}" class="nav-link {{ Request::is('accountant/pendingpayments') ? 'active' : '' }}">
            <span><i class="fas fa-coins"></i></span>Painding Payments
        </a>
    </li>  --}}
    <li>
        <a href="{{ route('accountant.fines') }}"
            class="nav-link {{ Request::is('accountant/fines') ? 'active' : '' }}">
            <span><i class="fas fa-exclamation-circle"></i></span>Fines & Penalties
        </a>
    </li>
</ul>

<!-- Resident & Guest Services -->
<p>Resident & Guest Services</p>
<ul class="nav">
    {{-- <li>
        <a href="{{ route('accountant.residents') }}" class="nav-link {{ Request::is('accountant/residents') ? 'active' : '' }}">
            <span><i class="fas fa-coins"></i></span>Residents List
        </a>
    </li>  --}}
    <li>
        <a href="{{ url('/accountant/resident-payments') }}"
            class="nav-link {{ Request::is('accountant/resident-payments') ? 'active' : '' }}">
            <span><i class="fas fa-file-invoice-dollar"></i></span>Resident Payment History
        </a>
    </li>
    <li>
        <a href="{{ route('accountant.guests') }}"
            class="nav-link {{ Request::is('accountant/guests') ? 'active' : '' }}">
            <span><i class="fas fa-user-friends"></i></span>Guest Management
        </a>
    </li>
</ul>

<!-- Master Configuration -->
<p>Master Configuration</p>
<ul class="nav">
    <li>
        <a href="{{ route('accountant.fee_heads') }}"
            class="nav-link {{ Request::is('accountant/fee_heads') ? 'active' : '' }}">
            <span><i class="fas fa-cogs"></i></span>Fee Head Setup
        </a>
    </li>
        <li>
        <a href="{{ route('accountant.feemaster') }}"
            class="nav-link {{ Request::is('accountant/feemaster') ? 'active' : '' }}">
            <span><i class="fas fa-wallet"></i></span>Fee Master Setup
        </a>
    </li>

</ul>

<!-- Requests & Approvals -->
<p>Requests & Approvals</p>
<ul class="nav">
    <li>
        <a href="{{ route('accountant.account') }}"
            class="nav-link {{ Request::is('accountant/account') ? 'active' : '' }}">
            <span><i class="fas fa-sign-out-alt"></i></span>Checkout Requests
        </a>
    </li>
</ul>
