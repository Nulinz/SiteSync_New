@extends('layouts.app')

@section('content')
    <style>
        .table thead th {
            background-color: var(--theadbg) !important;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            background: transparent;
            border-bottom: 2px solid transparent;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: transparent;
            border-color: transparent;
            border-bottom: 2px solid #007bff;
        }

        .tab-content {
            border: none;
            padding: 0;
            margin-top: 20px;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
        }

        /* Status filter tabs styling */
        .status-filter-tabs {
            border-bottom: none;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 4px;
            border-radius: 8px;
            display: inline-flex;
            gap: 0;
        }

        .status-filter-tabs .nav-link {
            border: none;
            color: #6c757d;
            background: transparent;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            padding: 8px 16px;
            margin: 0;
            transition: all 0.2s ease;
            position: relative;
        }

        .status-filter-tabs .nav-link:hover {
            color: #495057;
            background-color: rgba(255, 255, 255, 0.5);
        }

        .status-filter-tabs .nav-link.active {
            color: #495057;
            background-color: #ffffff;
            border: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }

        .status-filter-tabs .nav-item {
            margin: 0;
        }

        /* No data message styling */
        .no-data-message {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
            font-size: 16px;
        }

        .no-data-message i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Hide table body when no data, but keep headers */
        .table-no-data tbody {
            display: none;
        }
    </style>

    <div class="body-div px-4 py-1">
        <div class="body-head">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head2h6">Task Management</h6>
            </div>
            @can('task_create')
                <div class="sidebodybtn">
                    <a href="{{ route('task.create') }}"><button class="listbtn"><i class="fas fa-plus pe-2"></i>Add
                            Task</button></a>
                </div>
            @endcan
        </div>

        <!-- Main Tab Navigation -->
        <div class="mt-3">
            <ul class="nav nav-tabs" id="taskTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="created-tab" data-bs-toggle="tab" data-bs-target="#created" type="button" role="tab" aria-controls="created"
                        aria-selected="true">
                        Created Tasks
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="assigned-tab" data-bs-toggle="tab" data-bs-target="#assigned" type="button" role="tab" aria-controls="assigned"
                        aria-selected="false">
                        Assigned Tasks
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="taskTabContent">
            <!-- Created Tasks Tab -->
            <div class="tab-pane fade show active" id="created" role="tabpanel" aria-labelledby="created-tab">
                <!-- Status Filter Tabs for Created Tasks -->
                <div class="mt-3">
                    <ul class="nav nav-tabs status-filter-tabs" id="createdStatusTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="created-all-tab" data-status="all" type="button">
                                All
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="created-approved-tab" data-status="new" type="button">
                                New
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="created-completed-tab" data-status="completed" type="button">
                                Completed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="created-pending-tab" data-status="pending" type="button">
                                Pending
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="created-approved-tab" data-status="approved" type="button">
                                Approved
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="container-fluid listtable px-0">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="headerDropdown form-select filter-option" id="createdFilter">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" id="createdSearch" class="form-control filterInput" placeholder="Search">
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="table" id="createdTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task Title</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Assigned To</th>
                                    <th>Status</th>
                                    <th>File</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($createdTasks as $task)
                                    <tr data-status="{{ $task->status_filter }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->project->project_name ?? 'General' }}</td>
                                        <td>{{ date('d-m-Y', strtotime($task->end_timestamp)) }}</td>
                                        @php
                                            $priorityClass = match ($task->priority) {
                                                'Low' => 'text-primary',
                                                'Medium' => 'text-warning',
                                                'High' => 'text-danger',
                                                default => 'text-secondary',
                                            };
                                        @endphp
                                        <td><span class="{{ $priorityClass }}">{{ $task->priority }}</span></td>
                                        <td>{{ $task->user->name ?? '-' }}</td>
                                        <td><span class="{{ $task->status_class }}">{{ $task->custom_status }}</span></td>
                                        <td>
                                            @if ($task->close_request && $task->close_request->status == 'approved' && $task->close_request->file)
                                                <a href="{{ Storage::disk('s3')->url('task/' . $task->close_request->file) }}" target="_blank" class="text-primary"
                                                    data-bs-toggle="tooltip" data-bs-title="View File">
                                                    <i class="fas fa-file-alt"></i> View
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($task->close_request)
                                                    @if ($task->close_request->status == 'pending')
                                                        <button class="taskassignbtn btn_closeModal" data-task_id="{{ $task->close_request->id }}" data-bs-toggle="modal"
                                                            data-bs-target="#closeModal" data-bs-toggle="tooltip" data-bs-title="Approve Close Request">
                                                            Approve
                                                        </button>
                                                    @else
                                                        <span class="taskassignbtn">{{ ucfirst($task->close_request->status) }}</span>
                                                    @endif
                                                @endif
                                                <a href="{{ route('task.show', $task->id) }}" data-bs-toggle="tooltip" data-bs-title="View Flow"><i
                                                        class="fas fa-arrow-up-right-from-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <!-- No Data Message for Created Tasks -->
                    <div class="no-data-message" id="createdNoData" style="display: none;">
                        <i class="fas fa-inbox"></i>
                        <div>No Data Available</div>
                    </div>
                </div>
            </div>

            <!-- Assigned Tasks Tab -->
            <div class="tab-pane fade" id="assigned" role="tabpanel" aria-labelledby="assigned-tab">
                <!-- Status Filter Tabs for Assigned Tasks -->
                <div class="mt-3">
                    <ul class="nav nav-tabs status-filter-tabs" id="assignedStatusTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="assigned-all-tab" data-status="all" type="button">
                                All
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="created-approved-tab" data-status="new" type="button">
                                New
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assigned-completed-tab" data-status="completed" type="button">
                                Completed
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assigned-pending-tab" data-status="pending" type="button">
                                Pending
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="assigned-approved-tab" data-status="approved" type="button">
                                Approved
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="container-fluid listtable px-0">
                    <div class="filter-container row">
                        <div class="filter-container-start">
                            <select class="headerDropdown form-select filter-option" id="assignedFilter">
                                <option value="All" selected>All</option>
                            </select>
                            <input type="text" id="assignedSearch" class="form-control filterInput" placeholder="Search">
                        </div>
                    </div>

                    <div class="table-wrapper">
                        <table class="table" id="assignedTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Task Title</th>
                                    <th>Project</th>
                                    <th>Created By</th>
                                    <th>Due Date</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignedTasks as $task)
                                    <tr data-status="{{ $task->status_filter }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $task->title }}</td>
                                        <td>{{ $task->project->project_name ?? 'General' }}</td>
                                        <td>{{ $task->created_user->name ?? '-' }}</td>
                                        <td>{{ date('d-m-Y H:i:s', strtotime($task->end_timestamp)) }}</td>
                                        @php
                                            $priorityClass = match ($task->priority) {
                                                'Low' => 'text-primary',
                                                'Medium' => 'text-warning',
                                                'High' => 'text-danger',
                                                default => 'text-secondary',
                                            };
                                        @endphp
                                        <td><span class="{{ $priorityClass }}">{{ $task->priority }}</span></td>
                                        <td><span class="{{ $task->status_class }}">{{ $task->custom_status }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ route('task.show', $task->id) }}" data-bs-toggle="tooltip" data-bs-title="View Flow"><i
                                                        class="fas fa-arrow-up-right-from-square"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <!-- No Data Message for Assigned Tasks -->
                    <div class="no-data-message" id="assignedNoData" style="display: none;">
                        <i class="fas fa-inbox"></i>
                        <div>No Data Available</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Task Approval Modal (same as close task page) -->
    <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="closeModalLabel">Close Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="cl">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Close Task Details Modal -->
    <div class="modal fade" id="viewCloseModal" tabindex="-1" aria-labelledby="viewCloseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="viewCloseModalLabel">Task Close Request Details</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="closeTaskDetails">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        // Initialize DataTables for each tab
        function initializeDataTable(tableId, filterId, searchId) {
            var table = $(tableId).DataTable({
                paging: true,
                searching: true,
                ordering: true,
                bDestroy: true,
                info: false,
                responsive: true,
                pageLength: 10,
                dom: '<"top"f>rt<"bottom"lp><"clear">',
            });

            // Setup filters
            $(tableId + " thead th").each(function(index) {
                var headerText = $(this).text();
                if (headerText != "" && headerText.toLowerCase() != "action") {
                    $(filterId).append('<option value="' + index + '">' + headerText + "</option>");
                }
            });

            $(searchId).on("keyup", function() {
                var selectedColumn = $(filterId).val();
                if (selectedColumn !== "All") {
                    table.column(selectedColumn).search($(this).val()).draw();
                } else {
                    table.search($(this).val()).draw();
                }
            });

            $(filterId).on("change", function() {
                $(searchId).val("");
                table.search("").columns().search("").draw();
            });

            return table;
        }

        // Filter table by status and show/hide no data message
        function filterTableByStatus(tableId, status, noDataId) {
            var table = $(tableId).DataTable();
            var visibleRowCount = 0;

            if (status === 'all') {
                // Show all rows
                table.rows().nodes().to$().show();
                visibleRowCount = table.rows().nodes().to$().length;
            } else {
                // Hide all rows first
                table.rows().nodes().to$().hide();

                // Show only rows matching the status
                table.rows().nodes().to$().each(function() {
                    var rowStatus = $(this).data('status');
                    if (rowStatus === status) {
                        $(this).show();
                        visibleRowCount++;
                    }
                });
            }

            // Show/hide no data message but keep table headers visible
            if (visibleRowCount === 0) {
                $(tableId).addClass('table-no-data');
                $(noDataId).show();
            } else {
                $(tableId).removeClass('table-no-data');
                $(noDataId).hide();
            }

            table.draw(false);
        }

        $(document).ready(function() {
            // Initialize DataTables for all tabs
            var createdTable = initializeDataTable('#createdTable', '#createdFilter', '#createdSearch');
            var assignedTable;

            // Handle Created Tasks Status Filter Tabs
            $('#createdStatusTabs .nav-link').on('click', function(e) {
                e.preventDefault();

                // Remove active class from all tabs
                $('#createdStatusTabs .nav-link').removeClass('active');

                // Add active class to clicked tab
                $(this).addClass('active');

                // Get status and filter table
                var status = $(this).data('status');
                filterTableByStatus('#createdTable', status, '#createdNoData');
            });

            // Handle Assigned Tasks Status Filter Tabs
            $('#assignedStatusTabs .nav-link').on('click', function(e) {
                e.preventDefault();

                // Remove active class from all tabs
                $('#assignedStatusTabs .nav-link').removeClass('active');

                // Add active class to clicked tab
                $(this).addClass('active');

                // Get status and filter table
                var status = $(this).data('status');
                filterTableByStatus('#assignedTable', status, '#assignedNoData');
            });

            // Initialize assigned table when its tab is shown
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("data-bs-target");

                if (target === '#assigned' && !assignedTable) {
                    assignedTable = initializeDataTable('#assignedTable', '#assignedFilter', '#assignedSearch');
                }
            });

            // Handle close task approval modal (same as close task page)
            $('.btn_closeModal').on('click', function() {
                var task_id = $(this).data('task_id');

                $.ajax({
                    url: "{{ route('close.task_ajax') }}",
                    method: 'POST',
                    data: {
                        task_id: task_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $('#cl').html(res.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        $('#cl').html('<p class="text-danger">Error loading task details.</p>');
                    }
                });
            });

            // Handle close task view button (for details only)
            $('.view-close-task').on('click', function() {
                var taskId = $(this).data('task-id');

                $.ajax({
                    url: "{{ route('close.task_ajax') }}",
                    type: 'POST',
                    data: {
                        task_id: taskId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#closeTaskDetails').html(response.data);
                        }
                    },
                    error: function() {
                        $('#closeTaskDetails').html('<p class="text-danger">Error loading task details.</p>');
                    }
                });
            });

            // Handle form submission inside modal (if your close task ajax returns a form)
            $(document).on('submit', '#closeTaskForm', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var submitButton = $(this).find('button[type="submit"]');
                var originalText = submitButton.text();

                submitButton.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            // Close modal
                            $('#closeModal').modal('hide');

                            // Show success message
                            alert(response.message || 'Task close request processed successfully!');

                            // Reload the page to reflect changes
                            location.reload();
                        } else {
                            alert(response.message || 'Something went wrong!');
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred while processing the request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                    },
                    complete: function() {
                        submitButton.prop('disabled', false).text(originalText);
                    }
                });
            });
        });
    </script>
@endsection
