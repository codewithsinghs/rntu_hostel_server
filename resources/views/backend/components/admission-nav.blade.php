  {{-- <div class="sidebar-header">
      <button id="toggle-btn-small-screen">&#9776;</button>
      <h3>Admission Cell</h3>
      <!-- <button id="toggle-btn">&#9776;</button> -->
  </div> --}}

  <!-- Resident Dashboard -->
  <p>Accountant Dashboard</p>
  <ul class="nav">
      {{-- <li><a href="{{ url('/resident/dashboard') }}">
             <span><i class="fas fa-home"></i></span>Home Dashboard</a>
     </li> --}}
      <li>
          <a href="{{ url('admission/dashboard') }}" class="{{ Request::is('admission/dashboard') ? 'active' : '' }}">
              <span><i class="fas fa-home"></i></span>Home Dashboard
          </a>
      </li>

  </ul>

  <!-- Guest mananagement -->
  <p>Guests</p>
  <ul class="nav">
      {{-- <li><a href="{{ url('/resident/dashboard') }}">
             <span><i class="fas fa-home"></i></span>Home Dashboard</a>
     </li> --}}
      <li>
          <a href="{{ route('admission.guest_forms') }}"
              class="nav-link {{ Request::is('admission/guest/forms') ? 'active' : '' }}">
              <span><i class="fas fa-user"></i></span>Guest Applications
          </a>
      </li>


  </ul>
