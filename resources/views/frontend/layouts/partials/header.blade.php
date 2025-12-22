<!-- header section  -->
<style>
    /* Hamburger styling */
    .hamburger {
        border: none;
        background: transparent;
        cursor: pointer;
        display: none;
        margin-left: auto;
    }

    .hamburger span {
        display: block;
        width: 25px;
        height: 3px;
        margin: 5px;
        background: #000;
        transition: all 0.3s ease;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 5px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(6px, -6px);
    }

    /* Responsive visibility */
    @media (max-width: 991px) {
        .hamburger {
            display: block;
        }

        .nav-container-right {
            flex-direction: column;
            align-items: flex-start;
        }

        .nav-btns {
            width: 100%;
            justify-content: start;
        }
    }

    /* Sticky header behavior */
    .main-header {
        position: sticky;
        top: 0;
        z-index: 999;
        transition: transform 0.3s ease;
        will-change: transform;
    }

    .main-header.header-hidden {
        transform: translateY(-100%);
    }


    .no-scroll {
        overflow: hidden;
        padding-right: 15px;
        /* Adjust based on scrollbar width */
    }

    .collapse {
        transition: height 0.3s ease;
    }
</style>

<header class="main-header sticky-top ">
    <nav class="navbar navbar-expand-lg nav-container container-fluid">
        <div class="nav-container-left">
            <a href="{{ url('/') }}" class="">
                <div class="navbar-brand register-header-logo">
                    <img src="{{ asset('frontend/img//main-logo.png') }}" alt="RNTU Logo" />
                </div>
            </a>
            <div class="search-bar">
                <input type="text" placeholder="Type Something" />
                <button>
                    <img src="https://img.icons8.com/ios-filled/20/000000/search--v1.png" alt="Search" />
                </button>
            </div>
        </div>

        <!-- Hamburger Toggle -->
        <button class="hamburger navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#headercollape" aria-controls="headercollape" aria-expanded="false"
            aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>

        <!-- Collapsible Menu -->
        <div class="collapse navbar-collapse justify-content-end" id="headercollape">
            <div class="nav-container-right custom d-flex flex-column flex-lg-row align-items-lg-center">
                <ul class="nav-links navbar-nav me-lg-4 mb-3 mb-lg-0">
                    <li class="nav-item"><a href="{{ url('/') }}" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#support-section" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="{{ url('/') }}#hostel-list-section" class="nav-link">Hostels</a></li>
                    <li class="nav-item"><a href="{{ url('guest/registration-status') }}" class="nav-link">Registration Status</a></li>
                </ul>

                <div class="nav-btns d-flex gap-2">
                    <a href="{{ route('guest.register') }}" class="btn btn-outline-primary btn-custom btn-register">Registration</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-login btn-custom btn-register">Login</a>                 
                </div>

                {{-- <div class="register-header-buttons-container ">
                    <button type="button" class="register-header-button text-white" id="btn-regitser">
                        <!-- Check Registration Status -->.
                    </button>
                    <button type="button" class="register-header-button text-white" id="btn-login">
                        <!-- Login Here -->.
                    </button>
                </div> --}}

            </div>
        </div>
    </nav>
</header>