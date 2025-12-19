<!-- Web View Sidebar -->
<aside>
    <div class="flex-shrink-0 sidebar">
        <div class="nav col-md-11">
            <a href="./index.php" class="w-100">
                <img src="{{ asset('assets/images/logo.png') }}" alt="" height="50px" class="mx-auto lightLogo">
            </a>
            <a href="./index.php" class="w-100">
                <img src="{{ asset('assets/images/logo_1.png') }}" alt="" height="50px" class="mx-auto darkLogo"
                    style="display: none;">
            </a>
        </div>
        <ul class="list-unstyled mt-5 ps-0">
            @include('layouts.menu')
        </ul>
    </div>
</aside>

<!-- Responsive Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <div class="user ps-1">
            @include('layouts.user')
        </div>
        <button type="button" class="btn-close bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="flex-shrink-0 sidebar">
            <ul class="list-unstyled mt-2 ps-0">
                @include('layouts.menu')
            </ul>
        </div>
    </div>
</div>