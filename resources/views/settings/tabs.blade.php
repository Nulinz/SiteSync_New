<div class="body-head">
    <div class="body-h6">
        <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
        <h6 class="head2h6"><a href="{{ route('settings.index') }}">Settings</a></h6>
    </div>
</div>

<div class="proftabs pb-0">
    <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 border-0" id="myTab"
        role="tablist">

        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.companyprofile') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.companyprofile', 'settings.companyadd') ? 'active' : '' }}">Company
                    Details</button></a>
        </li>

        <!-- <li class="nav-item" role="presentation">
            <a href="{{ route('settings.designation') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.designation') ? 'active' : '' }}">Designation</button></a>
        </li> -->
        {{-- @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'Admin') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.roles') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.roles') ? 'active' : '' }}">Roles</button></a>
        </li>
        {{-- @endif
        @can('setting_view_employee') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.employee') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.employee') ? 'active' : '' }}">Employee</button></a>
        </li>
        {{-- @endcan --}}

        <!-- <li class="nav-item" role="presentation">
                <a href="{{ route('settings.category') }}"><button type="button"
                        class="profiletabs {{ Request::routeIs('settings.category') ? 'active' : '' }}">Category</button></a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('settings.subcategory') }}"><button type="button"
                        class="profiletabs {{ Request::routeIs('settings.subcategory') ? 'active' : '' }}">Sub
                        Category</button></a>
            </li> -->
        {{-- @can('setting_view_survey') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.survey') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.survey') ? 'active' : '' }}">Survey</button></a>
        </li>
        {{-- @endcan
        @can('setting_view_drawing') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.drawing') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.drawing') ? 'active' : '' }}">Drawings</button></a>
        </li>
        {{-- @endcan
        @can('setting_view_qc') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.qc') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.qc') ? 'active' : '' }}">QC</button></a>
        </li>
        {{-- @endcan
        @can('setting_view_snag') --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.snag') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.snag') ? 'active' : '' }}">Snag</button></a>
        </li>
        {{-- @endcan --}}
        {{-- <li class="nav-item" role="presentation">
            <a href="{{ route('settings.item') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.item') ? 'active' : '' }}">Item</button></a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.unit') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.unit') ? 'active' : '' }}">UOM</button></a>
        </li> --}}
        {{-- <li class="nav-item" role="presentation">
            <a href="{{ route('settings.permissions') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.permissions') ? 'active' : '' }}">Permissions</button></a>
        </li> --}}
        <li class="nav-item" role="presentation">
            <a href="{{ route('settings.password') }}"><button type="button"
                    class="profiletabs {{ Request::routeIs('settings.password') ? 'active' : '' }}">Password</button></a>
        </li>
    </ul>
</div>

<link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

<style>
    .table thead th {
        background-color: var(--theadbg) !important;
    }
</style>
