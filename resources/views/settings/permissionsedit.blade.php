@extends ('layouts.app')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/settingsprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('settings.index') }}">Settings /</a></h6>
                <h6 class="head2h6"><a href="{{ route('settings.permissionsedit') }}">Edit Permissions</a></h6>
            </div>
        </div>

        <div class="container-fluid px-0">
            <form action="" method="post">
                <div class="row mt-3">
                    <div class="col-sm-12 col-md-12 col-xl-12 settingleft mb-3">
                        <div class="settingform-div">
                            <div class="settingheader">
                                <h5 class="m-0">Permissions</h5>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                                            <label for="roles">Roles</label>
                                            <select class="form-select" name="roles" id="roles" required>
                                                <option value="" selected disabled>Select Options</option>
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 col-xl-12 settingright">
                        <div class="settingform-div mb-2">
                            <div class="settingheader">
                                <h5 class="m-0">Permissions & Notifications</h5>
                            </div>
                            <div class="settingform">
                                <div class="container-fluid p-0 form-div border-0">

                                    <!-- Dashboard -->
                                    <div class="row mb-2">
                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
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
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">My Dashboard</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox2">
                                                        <label class="mb-0" for="checkbox2">View</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Drag and Drop" id="checkbox3">
                                                        <label class="mb-0" for="checkbox3">Drag and Drop</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Projects</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Create" id="checkbox3">
                                                        <label class="mb-0" for="checkbox3">Create</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Edit" id="checkbox4">
                                                        <label class="mb-0" for="checkbox4">Edit</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox5">
                                                        <label class="mb-0" for="checkbox5">View</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Assign" id="checkbox6">
                                                        <label class="mb-0" for="checkbox6">Assign</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Upload File" id="checkbox7">
                                                        <label class="mb-0" for="checkbox7">Upload File</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

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
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Task</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Create" id="checkbox10">
                                                        <label class="mb-0" for="checkbox10">Create</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox11">
                                                        <label class="mb-0" for="checkbox11">View</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Assign" id="checkbox12">
                                                        <label class="mb-0" for="checkbox12">Assign</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <div class="col-sm-12 notify">
                                                <label for="">Notifications</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Create" id="checkbox13">
                                                        <label class="mb-0" for="checkbox13">Create</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Assign" id="checkbox14">
                                                        <label class="mb-0" for="checkbox14">Assign</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Comments" id="checkbox15">
                                                        <label class="mb-0" for="checkbox15">Comments</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
                                            <h5 class="mb-0">Settings</h5>
                                            <div class="col-sm-12">
                                                <label for="">Permissions</label>
                                                <div class="d-block form-check ps-1">
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Create" id="checkbox16">
                                                        <label class="mb-0" for="checkbox16">Create</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Edit" id="checkbox17">
                                                        <label class="mb-0" for="checkbox17">Edit</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="View" id="checkbox18">
                                                        <label class="mb-0" for="checkbox18">View</label>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <input type="checkbox" value="Status" id="checkbox19">
                                                        <label class="mb-0" for="checkbox19">Status</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-12 col-md-3 col-xl-3 mb-4">
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
                                        </div>
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