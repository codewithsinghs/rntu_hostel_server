<style>
    .user-section {
        gap: 8px;
    }

    .user-role-label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
        white-space: nowrap;
    }

    .user-button {
        background: none;
        border: none;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-initial {
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Hide role label on small screens or when space is tight */
    @media (max-width: 576px) {
        .user-role-label {
            display: none;
        }
    }
</style>
<!-- Header -->
<header class="topbar">
    <button id="toggle-btn">&#9776;</button>
    <div class="topbar-right">
        <input type="text" class="search-bar" placeholder="Search" />

        <div>
            <button class="nav-notification" data-bs-toggle="modal" data-bs-target="#ConfirmationPopup"><img
                    src="{{ asset('backend/img/dashboard/notification.png') }}"></button>
        </div>

        {{-- <div class="user-section">
            <span class="user-initial">A</span>
        </div>

        <div class="dropdown">
            <button class=" dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Admin
            </button>
            <ul class="dropdown-menu custom-style-ul">
                <li><a class="dropdown-item custom-style-li" href="#">Admin Profile</a></li>
                <li><a class="dropdown-item custom-style-li" href="#">Notification</a></li>
                <li><a class="dropdown-item custom-style-li" href="#">Log Out</a></li>
            </ul>
        </div> --}}

        <div class="user-section dropdown d-flex align-items-center">
            <span class="user-role-label">Admin</span>
            <button class="dropdown-toggle user-button" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="user-initial">A</span>
            </button>
            <ul class="dropdown-menu custom-style-ul">
                <li><a class="dropdown-item custom-style-li" href="#">Admin Profile</a></li>
                <li><a class="dropdown-item custom-style-li" href="#">Notification</a></li>
                <li><a class="dropdown-item custom-style-li" href="#">Log Out</a></li>
            </ul>
        </div>


    </div>
</header>

<style>
    .topbar-full {
        left: 0;
        width: 100%;
    }

    .topbar-part {
        left: 300px;
        /* match sidebar width */
        width: calc(100% - 300px);
    }

    @media (max-width: 768px) {

        .topbar-part,
        .topbar-full {
            left: 0;
            width: 100%;
        }
    }


    .topbar {
        position: fixed;
        top: 0;
        /* left: 300px;
        width: calc(100% - 300px); */
        z-index: 1000;
        transition: transform 0.4s ease, left 0.3s ease, width 0.3s ease;
    }


    .topbar.hide {
        transform: translateY(-100%);
    }

    @media only screen and (max-width: 1024px) {
        .topbar-part {
            left: 250px;
            width: calc(100% - 250px);
        }
    }
</style>
<script>
    let lastScrollY = window.scrollY;
    let timeoutId;

    const aside = document.querySelector('.sidebar');
    const header = document.querySelector('.topbar');
    const toggleBtn = document.getElementById('toggle-btn');

    function showHeader() {
        header.classList.remove('hide');
    }

    function hideHeader() {
        header.classList.add('hide');
    }

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        if (currentScrollY > lastScrollY) {
            hideHeader(); // scrolling down
        } else {
            showHeader(); // scrolling up
        }

        lastScrollY = currentScrollY;

        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            showHeader(); // show when scroll stops
        }, 250);
    });

    // const topbars = document.querySelectorAll('.topbar');

    //   if (sidebar.classList.contains('collapsed')) {
    //         topbar.classList.add('topbar-part');
    //         topbar.classList.remove('topbar-full');
    //     } else {
    //          topbar.classList.add('topbar-full');
    //         topbar.classList.remove('topbar-part');

    //     }

    const topbars = document.querySelectorAll('.topbar');

    // ✅ Initial setup
    topbars.forEach(topbar => {
        if (aside.classList.contains('collapsed')) {
            topbar.classList.add('topbar-full');
            topbar.classList.remove('topbar-part');
        } else {
            topbar.classList.add('topbar-part');
            topbar.classList.remove('topbar-full');
        }
    });

    toggleBtn.addEventListener('click', () => {
        topbars.forEach(topbar => {
            if (aside.classList.contains('collapsed')) {
                console.log('works');
                topbar.classList.remove('topbar-full');
                topbar.classList.add('topbar-part');
            } else {
                console.log('not works');

                topbar.classList.remove('topbar-part');
                topbar.classList.add('topbar-full');
            }
        });
    });

    //     function updateHeaderClass() {
    //   topbars.forEach(el => {
    //     if (sidebar.classList.contains('collapsed')) {
    //       el.classList.add('full-width');
    //     } else {
    //       el.classList.remove('full-width');
    //     }
    //   });
    // }


    // toggleBtn.addEventListener('click', () => {
    //   sidebar.classList.toggle('collapsed');

    //   if (sidebar.classList.contains('collapsed')) {
    //     header.style.left = '0px';
    //     header.style.width = '100%';
    //   } else {
    //     header.style.left = '300px';
    //     header.style.width = 'calc(100% - 300px)';
    //   }
</script>
