@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Category Tab -->
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
                            <button class="listbtn" data-bs-toggle="modal" data-bs-target="#addsubcategory"><i
                                    class="fas fa-plus pe-2"></i>Create Sub Category</button>
                        </div>
                    </div>

                    <div class="table-wrapper">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Sub Category Title</th>
                                    <th>Sub Category Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sub_categories as $subcategory)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$subcategory->category_name}}</td>
                                        <td>{{$subcategory->sub_category}}</td>
                                        <td>{{$subcategory->sub_category_title}}</td>
                                        <td>{{$subcategory->sub_category_description}}</td>
                                        <td><span
                                                class="{{ ($subcategory->status == 'active') ? 'text-success' : 'text-danger' }}">{{ucwords($subcategory->status)}}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($subcategory->status == "active")
                                                    <a
                                                        href="{{url('subcategory-status-update/' . $subcategory->id . '/inactive')}}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a href="{{url('subcategory-status-update/' . $subcategory->id . '/active')}}"><i
                                                            class="fas fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                                            data-bs-title="Active"></i></a>
                                                @endif
                                                <i class="fas fa-pen-to-square edit_button" data-bs-toggle="modal"
                                                    data-bs-target="#editsubcategory" data_id="{{$subcategory->id}}"
                                                    data_category_id="{{$subcategory->category_id}}"
                                                    data_sub_category="{{$subcategory->sub_category}}"
                                                    data_sub_category_title="{{$subcategory->sub_category_title}}"
                                                    data_sub_category_description="{{$subcategory->sub_category_description}}"></i>
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

    <!-- Add Sub Category Modal -->
    <div class="modal fade" id="addsubcategory" tabindex="-1" aria-labelledby="addsubcategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-diagram-next"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="addsubcategoryLabel">Create Sub Category</h4>
                    <form action="{{route('settings.subcategorystore')}}" method="post">
                        @csrf
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="category_id" class="col-form-label">Category</label>
                            <select class="form-select" name="category_id" id="category_id" required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="sub_category" class="col-form-label">Sub Category</label>
                            <input type="text" class="form-control" name="sub_category" id="sub_category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="sub_category_title" class="col-form-label">Sub Category Title</label>
                            <input type="text" class="form-control" name="sub_category_title" id="sub_category_title"
                                required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="sub_category_description" class="col-form-label">Sub Category Description</label>
                            <textarea rows="2" class="form-control" name="sub_category_description"
                                id="sub_category_description" required></textarea>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                            <button type="submit" class="modalbtn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Sub Category Modal -->
    <div class="modal fade" id="editsubcategory" tabindex="-1" aria-labelledby="editsubcategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="usericon">
                        <h5 class="mb-0"><i class="fa-solid fa-diagram-next"></i></h5>
                    </div>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 class="modal-title mb-2 fs-5" id="editsubcategoryLabel">Edit Sub Category</h4>
                    <form action="{{route('settings.subcategorystore')}}" method="post">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_category_id" class="col-form-label">Category</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                <option value="" selected disabled>Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_sub_category" class="col-form-label">Sub Category</label>
                            <input type="text" class="form-control" name="sub_category" id="edit_sub_category" required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_sub_category_title" class="col-form-label">Sub Category Title</label>
                            <input type="text" class="form-control" name="sub_category_title" id="edit_sub_category_title"
                                required>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-1">
                            <label for="edit_category_sub_description" class="col-form-label">Sub Category
                                Description</label>
                            <textarea rows="2" class="form-control" name="sub_category_description"
                                id="edit_sub_category_description" required></textarea>
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
                $('#edit_category_id').val($(this).attr('data_category_id'));
                $('#edit_sub_category').val($(this).attr('data_sub_category'));
                $('#edit_sub_category_title').val($(this).attr('data_sub_category_title'));
                $('#edit_sub_category_description').val($(this).attr('data_sub_category_description'));
            });
        });
    </script>

@endsection