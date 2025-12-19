@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

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

            <!-- User Tab -->
            <div class="tab-pane show active">
                <div class="listtable">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="form-select filter-option headerDropdown" id="headerDropdown">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" class="form-control filterInput" placeholder=" Search">
                        </div>

                        {{-- @can('setting_create_employee') --}}
                        <div class="filter-container-end">
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#adduser"><i
                                    class="fas fa-plus pe-2"></i>Create Employee</button>
                        </div>
                        {{-- @endcan --}}
                    </div>

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Emp Code</th>
                                    <th>Name</th>
                                    <th>Contact Number</th>
                                    <th>Email ID</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $employee->employee_code }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                 <img src="{{ $employee->image_path ? Storage::disk('s3')->url($employee->image_path) : asset('assets/images/avatar.png') }}"
                                                        height="25px" width="25px"
                                                        style="object-fit: cover; object-position: center;" class="rounded-5" alt="">
                                                    {{ $employee->name }}

                                           
                                            </div>
                                        </td>
                                        <td>{{ $employee->contact_number }}</td>
                                        <td>{{ $employee->email_id }}</td>
                                        <td>{{ $employee->designation_name }}</td>
                                        <td><span
                                                class="{{ $employee->status == 'active' ? 'text-success' : 'text-danger' }}">{{ ucwords($employee->status) }}</span>
                                        </td>
                                        <td>
                                            {{-- @can('setting_edit_employee') --}}
                                            <div class="d-flex align-items-center gap-2">

                                                @if ($employee->status == 'active')
                                                    <a
                                                        href="{{ url('employee-status-update/' . $employee->id . '/inactive') }}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a
                                                        href="{{ url('employee-status-update/' . $employee->id . '/active') }}"><i
                                                            class="fas fas fa-circle-check text-success"
                                                            data-bs-toggle="tooltip" data-bs-title="Active"></i></a>
                                                @endif

                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data-bs-target="#edituser" data_id="{{ $employee->id }}"
                                                    data_employee_code="{{ $employee->employee_code }}"
                                                    data_name="{{ $employee->name }}"
                                                    data_contact_number="{{ $employee->contact_number }}"
                                                    data_email_id="{{ $employee->email_id }}"
                                                    data_designation_id="{{ $employee->designation_id }}"
                                                    data_role_id="{{ $employee->role_id }}"
                                                    data_image_path="{{ asset($employee->image_path) }}"></i>

                                                {{-- <a
                                                    href="{{ route('settings.employee_permission', ['id' => $employee->id]) }}"><i
                                                        class="fas fas fa-circle-check text-success"
                                                        data-bs-toggle="tooltip" data-bs-title="Active"></i></a> --}}
                                            </div>
                                            {{-- @endcan --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="adduserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-user-plus"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="adduserLabel">Create Employee</h4>
                    <form action="{{ route('settings.employeestore') }}" method="post" enctype="multipart/form-data"
                        id="emp_create">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="employee_code" class="col-form-label">Employee Code</label>
                            <input type="text" class="form-control" name="employee_code" id="employee_code" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="password" class="col-form-label">Password</label>
                            <div class="inpflex">
                                <input type="password" class="form-control border-0" name="password" id="password"
                                    minlength="6" required>
                                <i class="fa-solid fa-eye-slash" id="passHide_1"
                                    onclick="togglePasswordVisibility('password', 'passShow_1', 'passHide_1')"
                                    style="display:none; cursor:pointer;"></i>
                                <i class="fa-solid fa-eye" id="passShow_1"
                                    onclick="togglePasswordVisibility('password', 'passShow_1', 'passHide_1')"
                                    style="cursor:pointer;"></i>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="contact_number" class="col-form-label">Contact Number</label>
                            <input type="number" oninput="validate_contact(this)" min="6000000000" max="9999999999"
                                class="form-control" name="contact_number" id="contact_number" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="email_id" class="col-form-label">Email ID</label>
                            <input type="email" class="form-control" name="email_id" id="email_id" required>
                        </div>

                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="role_id" class="col-form-label">Role</label>
                            <select class="form-select" name="role_id" id="role_id" required>
                                <option value="" selected disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="image_path" class="col-form-label">Profile Image</label>
                            <input type="file" class="form-control" name="image_path" id="image_path"
                                accept="image/*">
                            <img class="img-fluid mt-2 imagepreview" id="image_preview" alt="">
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="edituserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-user-pen"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="edituserLabel">Edit Employee</h4>
                    <form action="{{ route('settings.employeestore') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_employee_code" class="col-form-label">Employee Code</label>
                            <input type="text" class="form-control" name="employee_code" id="edit_employee_code"
                                required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_contact_number" class="col-form-label">Contact Number</label>
                            <input type="number" oninput="validate_contact(this)" min="6000000000" max="9999999999"
                                class="form-control" name="contact_number" id="edit_contact_number" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_email_id" class="col-form-label">Email ID</label>
                            <input type="email" class="form-control" name="email_id" id="edit_email_id" required>
                        </div>
                        {{-- @php
                            @dd($roles)
                        @endphp --}}

                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_role_id" class="col-form-label">Role</label>
                            <select class="form-select" name="role_id" id="edit_role_id" required>
                                <option value="" selected disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="image_path" class="col-form-label">Profile Image</label>
                            <input type="file" class="form-control" name="image_path" id="edit_image_path"
                                accept="image/*">
                            <img class="img-fluid mt-2 imagepreview" id="edit_image_preview"
                                src="{{ asset('assets/images/avatar.png') }}" alt="">
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src={{ asset('assets/js/form_script.js') }}></script>

    <!-- Datatable -->
    <script>
        // DataTables List
        $(document).ready(function() {
            var table = $(".example").DataTable({
                paging: true,
                searching: true,
                ordering: true,
                bDestroy: true,
                info: false,
                responsive: true,
                pageLength: 10,
                dom: '<"top"f>rt<"bottom"lp><"clear">',
            });
        });

        // List Filter
        $(document).ready(function() {
            var table = $(".example").DataTable();
            $(".example thead th").each(function(index) {
                var headerText = $(this).text();
                if (
                    headerText != "" &&
                    headerText.toLowerCase() != "action" &&
                    headerText.toLowerCase() != "progress"
                ) {
                    $(".headerDropdown").append(
                        '<option value="' + index + '">' + headerText + "</option>"
                    );
                }
            });
            $(".filterInput").on("keyup", function() {
                var selectedColumn = $(".headerDropdown").val();
                if (selectedColumn !== "All") {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });
            $(".headerDropdown").on("change", function() {
                $(".filterInput").val("");
                table.search("").columns().search("").draw();
            });

            $(document).on("click", ".edit_button", function() {
                $('#edit_id').val($(this).attr('data_id'));
                $('#edit_employee_code').val($(this).attr('data_employee_code'));
                $('#edit_name').val($(this).attr('data_name'));
                $('#edit_contact_number').val($(this).attr('data_contact_number'));
                $('#edit_email_id').val($(this).attr('data_email_id'));
                $('#edit_designation_id').val($(this).attr('data_designation_id'));
                $('#edit_role_id').val($(this).attr('data_role_id'));
                $('#edit_image_preview').attr('src', $(this).attr('data_image_path'));
            });

            $('#edit_image_path').on('change', function(event) {
                var file = event.target.files[0];
                var preview = $('#edit_image_preview');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#edit_image_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.attr('src', "{{ asset('assets/images/avatar.png') }}");
                }
            });

            $('#image_path').on('change', function(event) {
                var file = event.target.files[0];
                var preview = $('#image_preview');

                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image_preview').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.attr('src', "{{ asset('assets/images/avatar.png') }}");
                }
            });

        });
    </script>

    <script>
        function togglePasswordVisibility(inputId, showId, hideId) {
            let $input = $('#' + inputId);
            let $passShow = $('#' + showId);
            let $passHide = $('#' + hideId);

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $passShow.hide();
                $passHide.show();
            } else {
                $input.attr('type', 'password');
                $passShow.show();
                $passHide.hide();
            }
        }
    </script>

@endsection
