@extends('layouts.app')

@section('content')
    <div class="body-div px-4 py-1 mb-3">

        <!-- Tabs -->
        @include('settings.tabs')

        <div class="tab-content" id="myTabContent">

            <!-- Survey Tab -->
            <div class="tab-pane fade show active">
                <div class="listtable">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="form-select filter-option headerDropdown">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" class="form-control filterInput" placeholder=" Search">
                        </div>
                        {{-- @can('setting_create_survey') --}}
                        <div class="filter-container-end">
                            <a href="{{ route('settings.surveycreate') }}"><button class="listbtn"><i
                                        class="fas fa-plus pe-2"></i>Create Survey</button></a>
                        </div>
                        {{-- @endcan --}}
                    </div>

                    <div class="table-wrapper">
                        <table class="example table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="width: 200px;">Survey Title</th>
                                    <th>Survey Description</th>
                                    <th>No. Of Questions</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($survey_data as $survey)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $survey->title }}</td>
                                        <td>{{ $survey->description }}</td>
                                        <td>{{ $survey->questions_count }}</td>
                                        <td><span
                                                class="{{ $survey->status == 'active' ? 'text-success' : 'text-danger' }}">{{ ucwords($survey->status) }}
                                        <td>
                                            {{-- @can('setting_edit_survey') --}}
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($survey->status == 'active')
                                                    <a
                                                        href="{{ url('survey-status-update/' . $survey->id . '/inactive') }}"><i
                                                            class="fas fa-circle-xmark text-danger" data-bs-toggle="tooltip"
                                                            data-bs-title="Inactive"></i></a>
                                                @else
                                                    <a href="{{ url('survey-status-update/' . $survey->id . '/active') }}"><i
                                                            class="fas fas fa-circle-check text-success"
                                                            data-bs-toggle="tooltip" data-bs-title="Active"></i></a>
                                                @endif
                                                <a href="{{ route('settings.surveyedit', $survey->id) }}"
                                                    data-bs-toggle="tooltip" data-bs-title="Edit"><i
                                                        class="fa-solid fa-pen-to-square"></i></a>
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

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
        });
    </script>
@endsection
