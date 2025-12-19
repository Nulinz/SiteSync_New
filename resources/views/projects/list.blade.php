@extends ('layouts.app')

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
                <h6 class="head2h6"><a href="{{ route('project.index') }}">My Projects</a></h6>
            </div>
            @can('project_create')
                <div class="sidebodybtn">
                    <a href="{{ route('project.create') }}"><button class="listbtn"><i class="fas fa-plus pe-2"></i>Add
                            Project</button></a>
                </div>
            @endcan
        </div>

        <div class="container-fluid px-0 mt-3 listtable">
            <div class="filter-container row">
                <div class="filter-container-start">
                    <select class="headerDropdown form-select filter-option">
                        <option value="All" selected>All</option>
                    </select>
                    <input type="text" id="customSearch" class="form-control filterInput" placeholder=" Search">
                </div>

                <div class="filter-container-end">
                    <div class="d-flex gap-2">
                        <a href=""><button class="exportbtn"><i class="fas fa-print pe-1"></i> Print</button></a>
                    </div>
                </div>
            </div>

            <div class="table-wrapper">
                <table class="example table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project Code</th>
                            <th>Project Name</th>
                            <th>Client Name</th>
                            <th>Location</th>
                            {{-- <th>Progress</th> --}}
                            {{-- <th>Status</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $project->project_id }}</td>
                                <td>{{ $project->project_name }}</td>
                                <td>{{ $project->client_name }}</td>
                                <td>{{ $project->pro_city }}</td>
                                {{-- <td>
                                    <div class="progress-container">
                                        <div class="linear-progress">
                                            <div class="progress-bar" id="progress-bar-{{$project->id}}"></div>
                                        </div>
                                        <div class="progress-value" id="progress-value-{{$project->id}}"></div>
                                    </div>
                                </td> --}}
                                {{-- <td><span class="text-warning">{{ucwords($project->status)}}</span> --}}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('project.show', $project->id) }}" data-bs-toggle="tooltip"
                                            data-bs-title="View Profile"><i
                                                class="fas fa-arrow-up-right-from-square"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

    @php

        /*
    <script>
        // Progress Bar
        function updateProgress(progressBarId, progressValueId, targetPercentage) {
            let progressBar = document.querySelector(`#${progressBarId}`);
            let progressValue = document.querySelector(`#${progressValueId}`);
            let progressStartValue = 0;
            let speed = 50;

            function update() {
                progressValue.textContent = `${progressStartValue}%`;
                progressBar.style.width = `${progressStartValue}%`;
                if (progressStartValue < targetPercentage) {
                    progressStartValue++;
                }
            }
            update();
            setInterval(update, speed);
        }
        updateProgress("progress-bar-1", "progress-value-1", 80);
        updateProgress("progress-bar-2", "progress-value-2", 100);

        @foreach ($project_progress as $project_id => $percentage)
            updateProgress("progress-bar-{{ $project_id }}", "progress-value-{{ $project_id }}", {{ $percentage }});
        @endforeach
    </script>

    */
    @endphp

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
            link.download = "Project-List.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
