@extends('layouts.app')

@section('content')
    <style>
        .table thead th {
            background-color: var(--theadbg) !important;
        }
    </style>

    <div class="body-div px-4 py-1">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head2h6"><a href="{{ route('task.index') }}">Task</a></h6>
            </div>

        </div>

        <div class="container-fluid px-0 mt-3 listtable">
            <div class="filter-container row">
                <div class="filter-container-start">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                {{-- <div class="filter-container-end">
                    <div class="d-flex gap-2">
                        <a href=""><button class="exportbtn"><i class="fas fa-print pe-1"></i> Print</button></a>
                        <a href=""><button class="exportbtn"><i class="fas fa-file-csv pe-1"></i> Excel</button></a>
                    </div>
                </div> --}}
            </div>

            <div class="table-wrapper">
                <table class="example table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Task Title</th>
                            <th>Project</th>
                            {{-- <th>Start Date</th> --}}
                            <th>Due Date</th>

                            <th>Assigned by</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list as $task)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->project_name ?? '-' }}</td>
                                <td>{{ $task->time }}</td>
                                <td>{{ $task->user_name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($task->tc_status == 'pending')
                                            <a class="mb-0 btn_closeModal" data-bs-toggle="modal"
                                                data-task_id="{{ $task->tc_id }}" data-bs-target="#closeModal">
                                                <button class="taskassignbtn">Approve</button>
                                            </a>
                                        @else
                                            <p>{{ $task->tc_status }}</p>
                                        @endif

                                        <a href="{{ route('task.show', ['id' => $task->tk_id]) }}"
                                            class="btn btn-sm text-dark"><i class="fas fa-external-link-alt"></i></a>
                                        {{-- <a href="{{ route('task.show', $task->id) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Flow"><i class="fas fa-arrow-up-right-from-square"></i></a>
                                        <a href="{{ route('task.show', $task->id) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Flow"><i class="fas fa-arrow-up-right-from-square"></i></a> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="closeModalLabel">Close Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cl">

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $('.btn_closeModal').on('click', function() {
            var task_id = $(this).data('task_id');
            // alert(task_id)
            // var type = $(this).data('type');
            $.ajax({
                url: "{{ route('close.task_ajax') }}",
                method: 'POST', // Method should be a string,
                data: {
                    task_id: task_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    $('#cl').html(res.data);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error); // Handle errors gracefully
                }
            });
        });
    </script>

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

    <script>
        // Print / Excel
        document.getElementById("print").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var dataTable = $.fn.dataTable.isDataTable(table) ? $(table).DataTable() : null;
            var clonedTable = table.cloneNode(true);

            if (dataTable) {
                var currentPage = dataTable.page();
                dataTable.page.len(-1).draw();
                clonedTable = table.cloneNode(true);
            }

            // Remove the last column (Action) from cloned table
            clonedTable.querySelectorAll("tr").forEach(row => {
                if (row.lastElementChild) {
                    row.removeChild(row.lastElementChild);
                }
            });

            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write(`
                                                            <html>
                                                                <head>
                                                                    <title>Project Lists</title>
                                                                    <style>
                                                                        table { width: 100%; border-collapse: collapse; }
                                                                        table, th, td { border: 1px solid black; }
                                                                        th, td { padding: 8px; text-align: left; }
                                                                    </style>
                                                                </head>
                                                                <body>${clonedTable.outerHTML}</body>
                                                            </html>
                                                        `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();

            if (dataTable) {
                dataTable.page.len(10).draw();
                dataTable.page(currentPage).draw(false);
            }
        });

        document.getElementById("excel").addEventListener("click", function(e) {
            e.preventDefault();

            var table = document.querySelector(".example");
            var csv = [];
            var rows = table.querySelectorAll("tr");

            rows.forEach(row => {
                var rowData = [];
                var cells = Array.from(row.children);

                // Remove the last column (Action)
                cells.slice(0, -1).forEach(cell => {
                    var text = cell.querySelector("input") ? cell.querySelector("input").value :
                        cell.textContent.trim();
                    rowData.push('"' + text + '"');
                });

                csv.push(rowData.join(","));
            });

            var csvBlob = new Blob([csv.join("\n")], {
                type: "text/csv"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(csvBlob);
            link.download = "Task-List.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
