@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('settings.index') }}">Settings /</a></h6>
                <h6 class="head2h6"><a href="{{ route('settings.rolescreate') }}">Edit Roles</a></h6>
            </div>
        </div>

        <div class="container-fluid px-0">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('settings.role_update') }}" method="post">
                @csrf
                <input type="hidden" value="{{ $role->id }}" name="role_id" />
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-12 pe-2 settingleft">
                        <div class="settingform-div mb-2">
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
                                <div class="container-fluid px-0 form-div border-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3">
                                            <label for="role_title">Role Title</label>
                                            <input type="text" class="form-control" name="role_title"
                                                value="{{ $role->name }}" id="role_title" required>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3">
                                            <label for="description">Role Description</label>
                                            <textarea rows="1" class="form-control" name="description" id="description">{{ $role->role_description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 settingright">
                        <div class="settingform-div mb-2">
                            <div class="settingheader d-block">
                                <h5 class="mb-2">Permissions & Notifications</h5>
                                <h6 class="mb-0">Define the actions the role is authorized to perform and specify the
                                    updates or alerts they will receive to ensure they can effectively execute tasks and
                                    stay informed about project developments.</h6>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">

                                    <!-- Dashboard -->
                                    <div class="row mb-2">
                                        {{-- <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Dashboard</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox1">
                                                        <label class="mb-0" for="checkbox1">View</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Project Tab</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-profile"
                                                            id="checkbox2" @checked(in_array('tab-profile', $per))>
                                                        <label class="mb-0" for="checkbox2">Profile-Tab</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-survey"
                                                            id="checkbox2" @checked(in_array('tab-survey', $per))>
                                                        <label class="mb-0" for="checkbox2">Survey-Tab</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-drawing"
                                                            id="checkbox2" @checked(in_array('tab-drawing', $per))>
                                                        <label class="mb-0" for="checkbox2">Drawing-Tab</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-progress"
                                                            id="checkbox2" @checked(in_array('tab-progress', $per))>
                                                        <label class="mb-0" for="checkbox2">Progress-Tab</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-qc" id="checkbox2"
                                                            @checked(in_array('tab-qc', $per))>
                                                        <label class="mb-0" for="checkbox2">Qc-Tab</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-snags"
                                                            id="checkbox2" @checked(in_array('tab-snags', $per))>
                                                        <label class="mb-0" for="checkbox2">Snags-Tab</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="tab-docs/link"
                                                            id="checkbox2" @checked(in_array('tab-docs/link', $per))>
                                                        <label class="mb-0" for="checkbox2">Document-Tab</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Drag and Drop" id="checkbox3">
                                                        <label class="mb-0" for="checkbox3">Drag and Drop</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- survey tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Survey Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-survey"
                                                            @checked(in_array('add-survey', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Survey</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="view-survey"
                                                            @checked(in_array('view-survey', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View-Survey</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="approve-survey"
                                                            @checked(in_array('approve-survey', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Approve-Survey</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Drawing Tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Drawing Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-drawing"
                                                            @checked(in_array('add-drawing', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Drawing</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="view-drawing"
                                                            @checked(in_array('view-drawing', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View-Drawing</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="approve-drawing"
                                                            @checked(in_array('approve-drawing', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Approve-Drawing</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- progress Tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Progress Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-progress"
                                                            @checked(in_array('add-progress', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Progress</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-stage"
                                                            @checked(in_array('add-stage', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Stage</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="approveidrawing"
                                                            checked id="">
                                                        <label class="mb-0" for="checkbox2">Approve-Drawing</label>
                                                    </div> --}}

                                                </div>
                                            </div>
                                        </div>

                                        {{-- QC Tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">QC Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-qc"
                                                            @checked(in_array('add-qc', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-QC</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="view-qc"
                                                            @checked(in_array('view-qc', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View-QC</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="approve-qc"
                                                            @checked(in_array('approve-qc', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Approve-QC</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Snag Tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Snag Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-snag"
                                                            @checked(in_array('add-snag', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Snag</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="view-snag"
                                                            @checked(in_array('view-snag', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View-Snag</label>
                                                    </div> --}}
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="approve-snag"
                                                            @checked(in_array('approve-snag', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Approve-Snag</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- docs Tab --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Doucument Tab</h5>
                                            <div class="col-sm-12">
                                                {{-- <label for="">Permissions</label> --}}
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="doc_create"
                                                            @checked(in_array('doc_create', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">Add-Document</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="doc_view"
                                                            @checked(in_array('doc_view', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View-Document</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="doc_view1"
                                                            @checked(in_array('doc_view1', $per)) id="">
                                                        <label class="mb-0" for="checkbox2">View1-Document</label>
                                                    </div> --}}

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Projects</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="project_create"
                                                            @checked(in_array('project_create', $per)) id="checkbox3">
                                                        <label class="mb-0" for="checkbox3">Create</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="project_edit"
                                                            @checked(in_array('project_edit', $per)) id="checkbox4">
                                                        <label class="mb-0" for="checkbox4">Edit</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="project_view"
                                                            @checked(in_array('project_view', $per)) id="checkbox5">
                                                        <label class="mb-0" for="checkbox5">View</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="Assign" id="checkbox6">
                                                        <label class="mb-0" for="checkbox6">Assign</label>
                                                    </div> --}}
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="Upload File"
                                                            id="checkbox7">
                                                        <label class="mb-0" for="checkbox7">Upload File</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                        {{-- 
                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <div class="col-sm-12 notify">
                                                <label for="radio">Notifications</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Assign" id="checkbox8">
                                                        <label class="mb-0" for="checkbox8">Assign</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="File Upload" id="checkbox9">
                                                        <label class="mb-0" for="checkbox9">File Upload</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Task</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="task_create"
                                                            @checked(in_array('task_create', $per)) id="checkbox10">
                                                        <label class="mb-0" for="checkbox10">Create</label>
                                                    </div>
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="task_view"
                                                            @checked(in_array('task_view', $per)) id="checkbox11">
                                                        <label class="mb-0" for="checkbox11">View</label>
                                                    </div> --}}
                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="Assign"
                                                            id="checkbox12">
                                                        <label class="mb-0" for="checkbox12">Assign</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- notification --}}

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Notification</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="add-notification"
                                                            @checked(in_array('add-notification', $per)) id="">
                                                        <label class="mb-0" for="checkbox10">Add-Notification</label>
                                                    </div>

                                                    {{-- <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="Assign"
                                                            id="checkbox12">
                                                        <label class="mb-0" for="checkbox12">Assign</label>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>


                                        {{-- <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Settings</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_create_employee', $per)) value="setting_create_employee"
                                                            id="checkbox16">
                                                        <label class="mb-0" for="checkbox16">Create Employee</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_edit_employee', $per)) value="setting_edit_employee"
                                                            id="checkbox17">
                                                        <label class="mb-0" for="checkbox17">Edit Employee</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_view_employee', $per)) value="setting_view_employee"
                                                            id="checkbox17">
                                                        <label class="mb-0" for="checkbox17">View Employee</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_create_survey', $per)) value="setting_create_survey"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Create Survey</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_edit_survey', $per)) value="setting_edit_survey"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Edit Survey</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="setting_view_survey"
                                                            @checked(in_array('setting_view_survey', $per)) id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">view Survey</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_create_drawing', $per)) value="setting_create_drawing"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Create Drawing</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_edit_drawing', $per)) value="setting_edit_drawing"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Edit Drawing</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_view_drawing', $per)) value="setting_view_drawing"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">view Drawing</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_create_qc', $per)) value="setting_create_qc"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Create QC</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_edit_qc', $per)) value="setting_edit_qc"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Edit QC</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="setting_view_qc"
                                                            @checked(in_array('setting_view_qc', $per)) id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">View QC</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_create_snag', $per)) value="setting_create_snag"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Create Snag</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]"
                                                            @checked(in_array('setting_edit_snag', $per)) value="setting_edit_snag"
                                                            id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">Edit Snag</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" name="roles[]" value="setting_view_snag"
                                                            @checked(in_array('setting_view_snag', $per)) id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">View Snag</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Password</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Edit" id="checkbox20">
                                                        <label class="mb-0" for="checkbox20">Edit</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox21">
                                                        <label class="mb-0" for="checkbox21">View</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Update</button>
                    </div>

                </div>
            </form>
        </div>

    </div>

@endsection
