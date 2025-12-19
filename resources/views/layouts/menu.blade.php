<li class="mb-1">
    <a href="{{ route('dashboard.index') }}">
        <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('dashboard.*') ? 'active' : '' }}">
            <div class="btnname">
                <i class="bx bxs-dashboard"></i> Dashboard
            </div>
        </button>
    </a>
</li>
<li class="mb-1">
    <a href="{{ route('project.index') }}">
        <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('project.*') ? 'active' : '' }}">
            <div class="btnname">
                <i class="fa-solid fa-folder-open"></i> My Projects
            </div>
        </button>
    </a>
</li>
<li class="mb-1">
    <a href="{{ route('task.index') }}">
        <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('task.*') ? 'active' : '' }}">
            <div class="btnname">
                <i class="fa-solid fa-list-check"></i> Tasks
            </div>
        </button>
    </a>
</li>
<!-- <li class="mb-1">
    <a href="{{ route('completed.task') }}">
        <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('completed.*') ? 'active' : '' }}">
            <div class="btnname">
                <i class="fa-solid fa-list-check"></i>Completed Tasks
            </div>
        </button>
    </a>
</li>
<li class="mb-1">
    <a href="{{ route('close.task_list') }}">
        <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('close.*') ? 'active' : '' }}">
            <div class="btnname">
                <i class="fa-solid fa-list-check"></i>Close Tasks
            </div>
        </button>
    </a>
</li> -->
@if (auth()->user()->role_id == 1)
    <li class="mb-1">
        <a href="{{ route('settings.companyprofile') }}">
            <button class="asidebtn mx-auto btn-toggle {{ Request::routeIs('settings.*') ? 'active' : '' }}">
                <div class="btnname">
                    <i class="fa-solid fa-gear"></i> Settings
                </div>
            </button>
        </a>
    </li>
@endif
<li class="mb-1">
    <a href="{{ route('logout') }}">
        <button class="asidebtn mx-auto btn-toggle collapsed" aria-expanded="false">
            <div class="btnname">
                <i class="fa-solid fa-right-from-bracket" style="color: red;"></i> Logout
            </div>
        </button>
    </a>
</li>
