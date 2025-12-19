@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="sidebodydiv px-4 py-1 mb-3">
        <div class="sidebodyhead">
            <div class="sidebodyh6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('settings.index') }}">Settings /</a></h6>
                <h6 class="head2h6"><a href="{{ route('settings.rolescreate') }}">Create Roles</a></h6>
            </div>
        </div>

        <div class="container-fluid px-0">
            <form action="{{route('settings.rolesstore')}}" method="post">
                @csrf
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingleft">
                        <div class="settingmaindiv mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Basic Details</h5>
                                    <h6 class="mb-0">Define the role name, the project it is associated with,
                                        and
                                        the
                                        individual or team responsible, ensuring clear task ownership and
                                        project
                                        alignment.</h6>
                                </div>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid px-0 maindiv border-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-xl-12 mb-3 pe-2 inputs">
                                            <label for="project_id">Project Name</label>
                                            <select class="form-select" name="project_id" id="project_id" required>
                                                <option value="" selected disabled>Select Options</option>
                                                @foreach($projects as $project)
                                                    <option value="{{$project->id}}">{{$project->project_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="role_title">Role Title</label>
                                            <input type="text" class="form-control" name="role_title" id="role_title"
                                                required>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="employee_id">Assign To</label>
                                            <select class="form-select" name="employee_id" id="employee_id" required>
                                                <option value="" selected disabled>Select Employee</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingright">
                        <div class="settingmaindiv mb-2">
                            <div class="settingheader">
                                <div>
                                    <h5 class="mb-2">Permissions & Notification</h5>
                                    <h6 class="mb-0">Define the actions the role is authorized to perform and
                                        specify
                                        the updates or alerts they will receive to ensure they can effectively
                                        execute
                                        tasks and stay informed about project developments.</h6>
                                </div>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid px-0 maindiv border-0">

                                    <!-- Project Details -->
                                    <div class="row mb-2">
                                        <h5 class="mb-0">Project Details</h5>
                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Permissions</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="project_detail_permission[]"
                                                        value="View and update project timelines" id="radio1">
                                                    <label class="mb-0" for="radio1">
                                                        View and update project timelines.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="project_detail_permission[]"
                                                        value="Edit project budget details" id="radio2">
                                                    <label class="mb-0" for="radio2">
                                                        Edit project budget details.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="project_detail_permission[]"
                                                        value="Upload and manage project documents" id="radio3">
                                                    <label class="mb-0" for="radio3">
                                                        Upload and manage project documents.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Notifications</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="project_detail_notification[]"
                                                        value="View and update project timelines" id="checkbox1">
                                                    <label class="mb-0" for="checkbox1">
                                                        View and update project timelines.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="project_detail_notification[]"
                                                        value="Edit project budget details" id="checkbox2">
                                                    <label class="mb-0" for="checkbox2">
                                                        Edit project budget details.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="project_detail_notification[]"
                                                        value="Upload and manage project documents" id="checkbox3">
                                                    <label class="mb-0" for="checkbox3">
                                                        Upload and manage project documents.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Recce -->
                                    <div class="row mb-2">
                                        <h5 class="mb-0">Recce (Site Inspection)</h5>
                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Permissions</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="site_inspection_permission"
                                                        value="Schedule site inspections" id="radio4">
                                                    <label class="mb-0" for="radio4">
                                                        Schedule site inspections.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="site_inspection_permission"
                                                        value="Submit recce findings and recommendations" id="radio5">
                                                    <label class="mb-0" for="radio5">
                                                        Submit recce findings and recommendations.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="site_inspection_permission"
                                                        value="Assign inspection tasks to the team" id="radio6">
                                                    <label class="mb-0" for="radio6">
                                                        Assign inspection tasks to the team.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="site_inspection_permission"
                                                        value="Access and upload recce photos and documents" id="radio7">
                                                    <label class="mb-0" for="radio7">
                                                        Access and upload recce photos and documents.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Notifications</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="site_inspection_notification[]"
                                                        value="Alerts for scheduled recce visits" id="checkbox4">
                                                    <label class="mb-0" for="checkbox4">
                                                        Alerts for scheduled recce visits.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="site_inspection_notification[]"
                                                        value="Updates on recce report submissions" id="checkbox5">
                                                    <label class="mb-0" for="checkbox5">
                                                        Updates on recce report submissions.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="site_inspection_notification[]"
                                                        value="Notification of recce task completion" id="checkbox6">
                                                    <label class="mb-0" for="checkbox6">
                                                        Notification of recce task completion.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="site_inspection_notification[]"
                                                        value="Alert for missing data in site inspection reports"
                                                        id="checkbox7">
                                                    <label class="mb-0" for="checkbox7">
                                                        Alert for missing data in site inspection reports.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="site_inspection_notification[]"
                                                        value="Reminder for site inspection follow-ups" id="checkbox8">
                                                    <label class="mb-0" for="checkbox8">
                                                        Reminder for site inspection follow-ups.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Design and Planning -->
                                    <div class="row mb-2">
                                        <h5 class="mb-0">Design and Planning</h5>
                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Permissions</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="design_planning_permission"
                                                        value="Approve and update architectural designs" id="radio8">
                                                    <label class="mb-0" for="radio8">
                                                        Approve and update architectural designs.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="design_planning_permission"
                                                        value="Edit construction plans and blueprints" id="radio9">
                                                    <label class="mb-0" for="radio9">
                                                        Edit construction plans and blueprints.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="design_planning_permission"
                                                        value="Review and manage utility layouts" id="radio10">
                                                    <label class="mb-0" for="radio10">
                                                        Review and manage utility layouts.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="radio" name="design_planning_permission"
                                                        value="Track design revision history" id="radio11">
                                                    <label class="mb-0" for="radio11">
                                                        Track design revision history.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-6 col-xl-6 mb-3 pe-2 inputs">
                                            <label for="radio">Notifications</label>
                                            <div class="d-block form-check ps-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="design_planning_notification[]"
                                                        value="Alerts for design approval requests" id="checkbox9">
                                                    <label class="mb-0" for="checkbox9">
                                                        Alerts for design approval requests.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="design_planning_notification[]"
                                                        value="Notifications for design changes" id="checkbox10">
                                                    <label class="mb-0" for="checkbox10">
                                                        Notifications for design changes.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="design_planning_notification[]"
                                                        value="Task updates for the planning team" id="checkbox11">
                                                    <label class="mb-0" for="checkbox11">
                                                        Task updates for the planning team.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="design_planning_notification[]"
                                                        value="Reminder for upcoming design submission deadlines"
                                                        id="checkbox12">
                                                    <label class="mb-0" for="checkbox12">
                                                        Reminder for upcoming design submission deadlines.
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection