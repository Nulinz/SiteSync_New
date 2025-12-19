@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Snag Tab -->
            <div class="tab-pane show active">
                <div class="listtable">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="form-select filter-option headerDropdown" id="headerDropdown">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" class="form-control filterInput" placeholder=" Search">
                        </div>
                        {{-- @can('setting_create_snag') --}}
                        <div class="filter-container-end">
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#addsnag"><i
                                    class="fas fa-plus pe-2"></i>Create Snag</button>
                        </div>
                    </div>
                    {{-- @endcan --}}



                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Snag Category</th>
                                    <th>Snag Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($snags as $snag)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $snag->category }}</td>
                                        <td>{{ $snag->description }}</td>
                                        <td><span
                                                class="{{ $snag->status == 'active' ? 'text-success' : 'text-danger' }}">{{ ucwords($snag->status) }}</span>
                                        </td>
                                        <td>
                                            {{-- @can('setting_edit_snag') --}}
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($snag->status == 'active')
                                                    <a href="{{ url('snag-status-update/' . $snag->id . '/inactive') }}">
                                                        <i class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ url('snag-status-update/' . $snag->id . '/active') }}">
                                                        <i class="fas fas fa-circle-check text-success"
                                                            data-bs-toggle="tooltip" data-bs-title="Active"></i>
                                                    </a>
                                                @endif
                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data_id="{{ $snag->id }}" data_category="{{ $snag->category }}"
                                                    data_description="{{ $snag->description }}"
                                                    data-bs-target="#editsnag"></i>
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

    <!-- Add Snag Modal -->
    <div class="modal fade" id="addsnag" tabindex="-1" aria-labelledby="addsnagLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-file-circle-exclamation"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="addsnagLabel">Create Snag</h4>
                    <form action="{{ route('settings.snagstore') }}" method="post" id="set_snag">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category" class="col-form-label">Snag Category</label>
                            <input type="text" class="form-control" name="category" id="category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="description" class="col-form-label">Snag Description</label>
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

    <!-- Edit Snag Modal -->
    <div class="modal fade" id="editsnag" tabindex="-1" aria-labelledby="editsnagLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-file-circle-exclamation"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="editsnagLabel">Edit Snag</h4>
                    <form action="{{ route('settings.snagstore') }}" method="post" id="set_edit_snag">
                        @csrf
                        <input type="hidden" name="id" id="edit_id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category" class="col-form-label">Snag Category</label>
                            <input type="text" class="form-control" name="category" id="edit_category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="description" class="col-form-label">Snag Description</label>
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
                $('#edit_category').val($(this).attr('data_category'));
                $('#edit_description').val($(this).attr('data_description'));
            });
        });
    </script>

@endsection
