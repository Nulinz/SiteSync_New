@extends('layouts.app')

@section('content')
    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Drawing Tab -->
            <div class="tab-pane show active">
                <div class="listtable">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="form-select filter-option headerDropdown" id="headerDropdown">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" class="form-control filterInput" placeholder=" Search">
                        </div>

                        {{-- @can('setting_create_drawing') --}}
                        <div class="filter-container-end">
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#adddrawing"><i
                                    class="fas fa-plus pe-2"></i>Create Drawing</button>
                        </div>
                    </div>
                    {{-- @endcan --}}

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Drawing Title</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drawings as $drawing)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $drawing->file_type }}</td>
                                        <td>{{ $drawing->title }}</td>
                                        <td>{{ $drawing->description }}</td>
                                        <td><span
                                                class="{{ $drawing->status == 'active' ? 'text-success' : 'text-danger' }}">{{ ucwords($drawing->status) }}</span>
                                        </td>
                                        <td>
                                            {{-- @can('setting_edit_drawing') --}}
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($drawing->status == 'active')
                                                    <a
                                                        href="{{ url('drawing-status-update/' . $drawing->id . '/inactive') }}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a
                                                        href="{{ url('drawing-status-update/' . $drawing->id . '/active') }}"><i
                                                            class="fas fas fa-circle-check text-success"
                                                            data-bs-toggle="tooltip" data-bs-title="Active"></i></a>
                                                @endif
                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data-bs-target="#editdrawing" data_id="{{ $drawing->id }}"
                                                    data_title="{{ $drawing->title }}"
                                                    data_description="{{ $drawing->description }}"
                                                    data_file_type="{{ $drawing->file_type }}"></i>
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

    <!-- Add Drawing Modal -->
    <div class="modal fade" id="adddrawing" tabindex="-1" aria-labelledby="adddrawingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-compass-drafting"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="adddrawingLabel">Create Drawing</h4>
                    <form action="{{ route('settings.drawingstore') }}" method="post" id="set_drawing">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="file_type" class="col-form-label">Type</label>
                            <select name="file_type" id="file_type" class="form-select" required>
                                <option value="" selected disabled>Select Options</option>
                                <option>2D Floor Plans</option>
                                <option>3D Renders</option>
                                <option>Electrical Drawings</option>
                                <option>Working Drawings</option>
                                <option>Sectional Drawings</option>
                                <option>Structural Drawings</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="title" class="col-form-label">Drawing Title</label>
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

    <!-- Edit Drawing Modal -->
    <div class="modal fade" id="editdrawing" tabindex="-1" aria-labelledby="editdrawingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-compass-drafting"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="editdrawingLabel">Edit Drawing</h4>
                    <form action="{{ route('settings.drawingstore') }}" method="post" id="set_edit_drawing">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_file_type" class="col-form-label">Type</label>
                            <select name="file_type" id="edit_file_type" class="form-select" required>
                                <option value="" selected disabled>Select Options</option>
                                <option>2D Floor Plans</option>
                                <option>3D Renders</option>
                                <option>Electrical Drawings</option>
                                <option>Working Drawings</option>
                                <option>Sectional Drawings</option>
                                <option>Structural Drawings</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_title" class="col-form-label">Drawing Title</label>
                            <input type="text" class="form-control" name="title" id="edit_title" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_description" class="col-form-label">Description</label>
                            <textarea rows="2" class="form-control" name="description" id="edit_description" required></textarea>
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
                $('#edit_title').val($(this).attr('data_title'));
                $('#edit_description').val($(this).attr('data_description'));
                $('#edit_file_type').val($(this).attr('data_file_type'));
            });
        });
    </script>
@endsection
