<nav class="navbar px-4">
    <div class="icons login col-sm-12 col-md-12">
        <button class="border-0 m-0 p-0 responsive_button" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <span id="navigation-icon"><i class="bx bx-menu-alt-left fs-1"></i></span>
        </button>
        <div class="navlogo">
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="40px" class="mx-auto lightLogo">
            </a>
            <a href="./index.php" class="mx-auto">
                <img src="{{ asset('assets/images/logo_1.png') }}" alt="" height="40px" class="mx-auto darkLogo"
                    style="display: none;">
            </a>
        </div>
        <!-- <div class="headimg">
            <a href="" data-bs-toggle="tooltip" data-bs-title="Notifications"><i class="bx bx-bell"></i></a>
        </div> -->
        <label class="switch-container" data-bs-toggle="tooltip" data-bs-title="Theme">
            <input type="checkbox" id="themeSwitcher">
            <span class="slider"></span>
        </label>
        <div class="user">
            @include('layouts.user')
        </div>
    </div>
</nav>