@extends('layouts.app')

@section('content')

    <style>
        @media screen and (min-width: 1098px) {
            .filter-container h4 {
                color: var(--main);
                font-weight: 700;
                font-size: 18px;
            }
        }

        @media screen and (min-width: 768px) and (max-width: 1098px) {
            .filter-container h4 {
                color: var(--main);
                font-weight: 700;
                font-size: 18px;
            }
        }

        @media screen and (max-width: 768px) {
            .filter-container h4 {
                color: var(--main);
                width: 100%;
                font-weight: 700;
                font-size: 18px;
                text-align: center !important;
            }
        }
    </style>

    <div class="sidebodydiv px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content mt-3" id="myTabContent">

            <!-- Role Tab -->
            <div class="tab-pane show active">
                <div class="filter-container row mb-3">
                    <div class="filter-container-start">
                        <h4 class="mb-0">Roles</h4>
                    </div>
                    <div class="filter-container-end">
                        <a href="{{ route('settings.rolescreate') }}"><button class="listbtn">+ Create Role</button></a>
                    </div>
                </div>

                <div class="accordion" id="accordion1">
                    @foreach($roles as $role)
                                    <div class="accordion-item mb-2">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#settingaccord{{$role->project_id}}" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                {{$role->project->project_name}}
                                            </button>
                                        </h2>
                                        <div id="settingaccord{{$role->project_id}}" class="accordion-collapse collapse"
                                            data-bs-parent="#accordion1">
                                            <div class="accordion-body px-0 py-1">
                                                <div class="table-wrapper">
                                                    <table class="table" id="table1">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Employee Name</th>
                                                                <th>Role Name</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                        if (array_key_exists($role->project_id, $employee_array)) {
                            $i = 1;
                            foreach ($employee_array[$role->project_id] as $employee_arr) {                                     
                                                                                    ?>
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        {{$employee_arr['name']}}
                                                                    </div>
                                                                </td>
                                                                <td>{{$employee_arr['designation']}}</td>
                                                                <td><span
                                                                        class="{{ ($employee_arr['status'] == 'active') ? 'text-success' : 'text-danger' }}">{{ucwords($employee_arr['status'])}}</span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        @if($employee_arr['status'] == "active")
                                                                            <a
                                                                                href="{{url('roles-status-update/' . $employee_arr['id'] . '/inactive')}}"><i
                                                                                    class="fas fa-circle-xmark text-danger"
                                                                                    data-bs-toggle="tooltip" data-bs-title="Inactive"></i></a>
                                                                        @else
                                                                            <a
                                                                                href="{{url('roles-status-update/' . $employee_arr['id'] . '/active')}}"><i
                                                                                    class="fas fas fa-circle-check text-success"
                                                                                    data-bs-toggle="tooltip" data-bs-title="Active"></i></a>
                                                                        @endif <a class="role_edit_button"
                                                                            data_id="{{$employee_arr['id']}}"><i
                                                                                class="fas fa-user-lock"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php            $i++;
                            }
                        }
                                                                                    ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <form action="{{route('settings.rolesstore')}}" method="post">
        @csrf
        <input type="hidden" name="role_id" id="role_id">
        <div class="modal fade" id="rolepermission" tabindex="-1" aria-labelledby="rolepermissionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <div class="roleheader">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#rolepermission"
                                id="rolepermission" class="roleheadbtn">Roles</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#notifypermission"
                                id="notification" class="roleheadbtn">Notifications</button>
                        </div>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title mb-2 fs-5" id="rolepermissionLabel">Update Role</h4>
                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-folder-open"></i></h5>
                                <label for="username" class="col-form-label">Project Details</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox project_details"
                                                        name="project_detail_permission" id="role1"
                                                        value="View and update project timelines">
                                                    <label for="role1" class="my-auto">View and update project
                                                        timelines.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox project_details"
                                                        name="project_detail_permission" id="role2"
                                                        value="Edit project budget details">
                                                    <label for="role2" class="my-auto">Edit project budget
                                                        details.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox project_details"
                                                        name="project_detail_permission" id="role3"
                                                        value="Upload and manage project documents">
                                                    <label for="role3" class="my-auto">Upload and manage project
                                                        documents.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-file-circle-check"></i></h5>
                                <label for="username" class="col-form-label">Recce / Survey</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox site_inspection"
                                                        name="site_inspection_permission" id="role4"
                                                        value="Schedule site inspections">
                                                    <label for="role4" class="my-auto">Schedule site
                                                        inspections.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox site_inspection"
                                                        name="site_inspection_permission" id="role5"
                                                        value="Submit recce findings and recommendations">
                                                    <label for="role5" class="my-auto">Submit recce findings and
                                                        recommendations.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox site_inspection"
                                                        name="site_inspection_permission" id="role6"
                                                        value="Assign inspection tasks to the team">
                                                    <label for="role6" class="my-auto">Assign inspection tasks to the
                                                        team.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox site_inspection"
                                                        name="site_inspection_permission" id="role7"
                                                        value="Access and upload recce photos and documents">
                                                    <label for="role7" class="my-auto">Access and upload recce photos
                                                        and
                                                        documents.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-compass-drafting"></i></h5>
                                <label for="username" class="col-form-label">Design</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox design_planning"
                                                        name="design_planning_permission" id="role8"
                                                        value="Approve and update architectural designs">
                                                    <label for="role8" class="my-auto">Approve and update architectural
                                                        designs.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox design_planning"
                                                        name="design_planning_permission" id="role9"
                                                        value="Edit construction plans and blueprints">
                                                    <label for="role9" class="my-auto">Edit construction plans and
                                                        blueprints.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox design_planning"
                                                        name="design_planning_permission" id="role10"
                                                        value="Review and manage utility layouts">
                                                    <label for="role10" class="my-auto">Review and manage utility
                                                        layouts.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="radio" class="me-2 checkbox design_planning"
                                                        name="design_planning_permission" id="role11"
                                                        value="Track design revision history">
                                                    <label for="role11" class="my-auto">Track design revision
                                                        history.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Permission Modal -->
        <div class="modal fade" id="notifypermission" tabindex="-1" aria-labelledby="notifypermissionLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <div class="roleheader">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#rolepermission"
                                id="rolepermission" class="roleheadbtn">Roles</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#notifypermission"
                                id="notification" class="roleheadbtn">Notifications</button>
                        </div>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title mb-2 fs-5" id="notifypermissionLabel">Update Notification</h4>
                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-folder-open"></i></h5>
                                <label for="username" class="col-form-label">Project Details</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox project_details_notification"
                                                        name="project_detail_notification[]" id="notify1"
                                                        value="View and update project timelines">
                                                    <label for="notify1" class="my-auto">View and update project
                                                        timelines.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox project_details_notification"
                                                        name="project_detail_notification[]" id="notify2"
                                                        value="Edit project budget details">
                                                    <label for="notify2" class="my-auto">Edit project budget
                                                        details.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox project_details_notification"
                                                        name="project_detail_notification[]" id="notify3"
                                                        value="Upload and manage project documents">
                                                    <label for="notify3" class="my-auto">Upload and manage project
                                                        documents.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-file-circle-check"></i></h5>
                                <label for="username" class="col-form-label">Recce / Survey</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox site_inspection_notification"
                                                        name="site_inspection_notification[]" id="notify4"
                                                        value="Alerts for scheduled recce visits">
                                                    <label for="notify4" class="my-auto">Alerts for scheduled recce
                                                        visits.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox site_inspection_notification"
                                                        name="site_inspection_notification[]" id="notify5"
                                                        value="Updates on recce report submissions">
                                                    <label for="notify5" class="my-auto">Updates on recce report
                                                        submissions.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox site_inspection_notification"
                                                        name="site_inspection_notification[]" id="notify6"
                                                        value="Notification of recce task completion">
                                                    <label for="notify6" class="my-auto">Notification of recce task
                                                        completion.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox site_inspection_notification"
                                                        name="site_inspection_notification[]" id="notify7"
                                                        value="Alert for missing data in site inspection reports">
                                                    <label for="notify7" class="my-auto">Alert for missing data in site
                                                        inspection reports.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox site_inspection_notification"
                                                        name="site_inspection_notification[]" id="notify8"
                                                        value="Reminder for site inspection follow-ups">
                                                    <label for="notify8" class="my-auto">Reminder for site inspection
                                                        follow-ups.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-2 sideinput">
                            <div class="sideinpleft d-flex justify-content-start align-items-center gap-2">
                                <h5 class="usericon mb-0"><i class="fa-solid fa-compass-drafting"></i></h5>
                                <label for="username" class="col-form-label">Design</label>
                            </div>
                            <div class="sideinpright">
                                <div class="col-sm-12 col-md-12 col-xl-12">
                                    <div class="col-sm-12 col-md-12 col-xl-12">
                                        <div class="dropdown-center tble-dpd">
                                            <button class="w-100 text-start form-select checkdrp" type="button"
                                                data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                                Select Options
                                            </button>
                                            <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <input type="checkbox" name="design_planning_notification[]"
                                                        value="Alerts for design approval requests" id="checkbox9"
                                                        class="design_planning_notification">
                                                    <label class="mb-0" for="checkbox9">
                                                        Alerts for design approval requests.
                                                    </label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox design_planning_notification"
                                                        name="design_planning_notification[]" id="notify10"
                                                        value="Notifications for design changes">
                                                    <label for="notify10" class="my-auto">Notifications for design
                                                        changes.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox design_planning_notification"
                                                        name="design_planning_notification[]" id="notify11"
                                                        value="Task updates for the planning team">
                                                    <label for="notify11" class="my-auto">Task updates for the planning
                                                        team.</label>
                                                </div>
                                                <div class="d-flex align-items-center w-100 mt-1">
                                                    <input type="checkbox"
                                                        class="me-2 checkbox design_planning_notification"
                                                        name="design_planning_notification[]" id="notify12"
                                                        value="Reminder for upcoming design submission deadlines">
                                                    <label for="notify12" class="my-auto">Reminder for upcoming design
                                                        submission deadlines.</label>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            function initTable(tableId, dropdownId, filterInputId) {
                var table = $(tableId).DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "order": [0, "asc"],
                    "bDestroy": true,
                    "info": false,
                    "responsive": true,
                    "pageLength": 30,
                    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                });
                $(tableId + ' thead th').each(function (index) {
                    var headerText = $(this).text();
                    if (headerText != "" && headerText.toLowerCase() != "action") {
                        $(dropdownId).append('<option value="' + index + '">' + headerText + '</option>');
                    }
                });
                $(filterInputId).on('keyup', function () {
                    var selectedColumn = $(dropdownId).val();
                    if (selectedColumn !== 'All') {
                        table.column(selectedColumn).search($(this).val()).draw();
                    } else {
                        table.search($(this).val()).draw();
                    }
                });
                $(dropdownId).on('change', function () {
                    $(filterInputId).val('');
                    table.search('').columns().search('').draw();
                });
                $(filterInputId).on('keyup', function () {
                    table.search($(this).val()).draw();
                });

                $(document).on("click", ".role_edit_button", function () {

                    var role_id = $(this).attr('data_id');
                    $('#role_id').val(role_id);

                    let postData = {
                        _token: "{{ csrf_token() }}",
                        role_id: role_id
                    };

                    $.ajax({
                        url: "{{route('settings.role_edit')}}",
                        type: 'POST',
                        data: JSON.stringify(postData),
                        contentType: 'application/json',
                        success: function (response) {
                            $('#rolepermission').modal('show');

                            response.data.permissions.forEach(permission => {
                                console.log(`Permission: ${permission.permission} (Section: ${permission.section})`);
                                $('input.' + permission.section + '[value="' + permission.permission + '"]').prop('checked', true);
                            });

                            response.data.notifications.forEach(notification => {
                                console.log(`Permission: ${notification.notification} (Section: ${notification.section})`);
                                $('input.' + notification.section + '_notification[value="' + notification.notification + '"]').prop('checked', true);
                            });

                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                            alert('Error saving survey!');
                        }
                    });

                });

            }
            // Initialize each table
            initTable('#table1', '#headerDropdown1', '#filterInput1');
            initTable('#table2', '#headerDropdown2', '#filterInput2');
        });
    </script>

@endsection