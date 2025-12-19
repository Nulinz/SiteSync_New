@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Category Tab -->
            <div class="tab-pane show active">
                <div class="listtable">
                    <div class="filter-container row mb-3">
                        <div class="filter-container-start">
                            <select class="form-select filter-option headerDropdown" id="headerDropdown">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" class="form-control filterInput" placeholder=" Search">
                        </div>

                        <div class="filter-container-end">
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#addcategory"><i
                                    class="fas fa-plus pe-2"></i>Create Category</button>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Category Title</th>
                                    <th>Category Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$category->category}}</td>
                                        <td>{{$category->category_title}}</td>
                                        <td>{{$category->category_description}}</td>
                                        <td><span
                                                class="{{ ($category->status == 'active') ? 'text-success' : 'text-danger' }}">{{ucwords($category->status)}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">

                                                @if($category->status == "active")
                                                    <a href="{{url('category-status-update/' . $category->id . '/inactive')}}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a href="{{url('category-status-update/' . $category->id . '/active')}}"><i
                                                            class="fas fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                            data-bs-title="Active"></i></a>
                                                @endif

                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data-bs-target="#editcategory" data_id="{{$category->id}}"
                                                    data_category="{{$category->category}}"
                                                    data_category_title="{{$category->category_title}}"
                                                    data_category_description="{{$category->category_description}}"></i>
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


    <!-- Add Category Modal -->
    <div class="modal fade" id="addcategory" tabindex="-1" aria-labelledby="addcategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-bars-progress"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="addcategoryLabel">Create Category</h4>
                    <form action="{{route('settings.categorystore')}}" method="post">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category" class="col-form-label">Category</label>
                            <input type="text" class="form-control" name="category" id="category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category_title" class="col-form-label">Category Title</label>
                            <input type="text" class="form-control" name="category_title" id="category_title" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category_description" class="col-form-label">Category Description</label>
                            <textarea rows="2" class="form-control" name="category_description" id="category_description"
                                required></textarea>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editcategory" tabindex="-1" aria-labelledby="editcategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-bars-progress"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="editcategoryLabel">Edit Category</h4>
                    <form action="{{route('settings.categorystore')}}" method="post">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_category" class="col-form-label">Category</label>
                            <input type="text" class="form-control" name="category" id="edit_category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_category_title" class="col-form-label">Category Title</label>
                            <input type="text" class="form-control" name="category_title" id="edit_category_title" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_category_description" class="col-form-label">Category Description</label>
                            <textarea rows="2" class="form-control" name="category_description"
                                id="edit_category_description" required></textarea>
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
                $('#edit_category').val($(this).attr('data_category'));
                $('#edit_category_title').val($(this).attr('data_category_title'));
                $('#edit_category_description').val($(this).attr('data_category_description'));
            });

        });
    </script>

@endsection