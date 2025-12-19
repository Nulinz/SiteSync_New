@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

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

                        <div class="filter-container-end">
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#adddesignation"><i
                                    class="fas fa-plus pe-2"></i>Create Designation</button>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Designation Title</th>
                                    <th>Department</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $designation)
                                    <tr>
                                        <td>{{$designation->id}}</td>
                                        <td>{{$designation->title}}</td>
                                        <td>Engineering</td>
                                        <td>{{$designation->description}}</td>
                                        <td><span
                                                class="{{ ($designation->status == 'active') ? 'text-success' : 'text-danger' }}">{{ucwords($designation->status)}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($designation->status == "active")
                                                    <a
                                                        href="{{url('designation-status-update/' . $designation->id . '/inactive')}}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a href="{{url('designation-status-update/' . $designation->id . '/active')}}"><i
                                                            class="fas fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                            data-bs-title="Active"></i></a>
                                                @endif
                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data-bs-target="#editdesignation" data_id="{{$designation->id}}"
                                                    data_department_id="{{$designation->department_id}}"
                                                    data_title="{{$designation->title}}"
                                                    data_description="{{$designation->description}}"></i>
                                            </div>
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

    <!-- Add Designation Modal -->
    <div class="modal fade" id="adddesignation" tabindex="-1" aria-labelledby="adddesignationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-id-badge"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="adddesignationLabel">Create Designation</h4>
                    <form action="{{route('settings.designationstore')}}" method="post">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="department_id" class="col-form-label">Department</label>
                            <select name="department_id" id="department_id" class="form-select" required>
                                <option value="" selected disabled>Select Options</option>
                                <option value="1">Accounts</option>
                                <option value="2">Sales</option>
                                <option value="3">Operations</option>
                                <option value="4">Design</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="title" class="col-form-label">Designation Title</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="description" class="col-form-label">Description</label>
                            <textarea rows="2" class="form-control" name="description" id="description" required></textarea>
                        </div>

                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Designation Modal -->
    <div class="modal fade" id="editdesignation" tabindex="-1" aria-labelledby="editdesignationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-id-badge"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="editdesignationLabel">Edit Designation</h4>
                    <form action="{{route('settings.designationstore')}}" method="post">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_department_id" class="col-form-label">Department</label>
                            <select name="department_id" id="edit_department_id" class="form-select" required>
                                <option value="" selected disabled>Select Options</option>
                                <option value="1">Accounts</option>
                                <option value="2">Sales</option>
                                <option value="3">Operations</option>
                                <option value="4">Design</option>
                            </select>
                            <span class="text-danger error" id="endtime_error"></span>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_title" class="col-form-label">Designation Title</label>
                            <input type="text" class="form-control" name="title" id="edit_title" required>
                            <span class="text-danger error" id="designation_error"></span>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_description" class="col-form-label">Description</label>
                            <textarea rows="2" class="form-control" name="description" id="edit_description"
                                required></textarea>
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

    <!-- Datatable -->
    <script>
        // DataTables List
        $(document).ready(function () {
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
        $(document).ready(function () {
            var table = $(".example").DataTable();
            $(".example thead th").each(function (index) {
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
            $(".filterInput").on("keyup", function () {
                var selectedColumn = $(".headerDropdown").val();
                if (selectedColumn !== "All") {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });
            $(".headerDropdown").on("change", function () {
                $(".filterInput").val("");
                table.search("").columns().search("").draw();
            });

            $(document).on("click", ".edit_button", function () {
                $('#edit_id').val($(this).attr('data_id'));
                $('#edit_department_id').val($(this).attr('data_department_id'));
                $('#edit_title').val($(this).attr('data_title'));
                $('#edit_description').val($(this).attr('data_description'));
            });

        });
    </script>

@endsection