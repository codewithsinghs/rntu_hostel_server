<p>Guest Dashboard</p>
<ul class="nav">
    {{-- <li><a href="{{ url('/resident/dashboard') }}">
             <span><i class="fas fa-home"></i></span>Home Dashboard</a>
     </li> --}}
    <li>
        <a href="{{ url('guest/dashboard') }}" class="{{ Request::is('guest/dashboard') ? 'active' : '' }}">
            <span><i class="fas fa-home"></i></span>Guest Dashboard
        </a>
    </li>

</ul>
