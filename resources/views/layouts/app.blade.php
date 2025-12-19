@include('layouts.header')

<div class="main">
    @include('layouts.sidebar')

    <div class="body">
        @include('layouts.navbar')
        @yield('content')
    </div>

</div>

@include('layouts.footer')