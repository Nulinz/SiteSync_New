@extends('layouts.app')

@section('content')
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_stages.css') }}">

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <h4 class="m-0 text-uppercase">Overview</h4>
        </div>

        <!-- Tabs -->
        <div class="container-fluid px-0 header">
            <div class="container px-0 mt-2 tabbtns">
                <div class="my-2">
                    <a href="{{ route('dashboard.index') }}"><button
                            class="dashtabs {{ Request::routeIs('dashboard.index') ? 'active' : '' }}">Overview</button></a>
                </div>
                <div class="my-2">
                    <a href="{{ route('dashboard.mydashboard') }}"><button
                            class="dashtabs {{ Request::routeIs('dashboard.mydashboard') ? 'active' : '' }}">My
                            Dashboard</button></a>
                </div>
            </div>
        </div>

        <div class="container-fluid px-0 mt-2 stages">
            <div class="row">
                <!-- Inprogress -->
                <div class="col-sm-12 col-md-4 col-xl-4 px-2">
                    <div class="stagemain">
                        <div class="inprogress">
                            <div class="inprgct">
                                <h6 class="m-0">In Progress</h6>
                            </div>
                            <div class="inprgno totalno">
                                <h6 class="m-0 text-end">{{ count($inprogress_tasks) }}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag inprogress-list" data_status="in_progress">

                                @foreach ($inprogress_tasks as $inprogress_task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{ $inprogress_task->id }}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">
                                                <i class="fa-solid fa-circle {{ $inprogress_task->priority_color }}"></i>
                                                <h6 class="mb-0">{{ $inprogress_task->title }}</h6>
                                            </div>
                                           @if ($inprogress_task->file_attachment)
                                            <div class="tasknamefile">
                                                <h6 class="mb-0">
                                                <a href="{{ env('AWS_URL') . $inprogress_task->file_attachment }}" 
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Attachment"
                                                target="_blank">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                </a>
                                                </h6>
                                            </div>
                                        @endif

                                        </div>
                                        
                                        <!-- Custom Status Display -->
                                        <div class="taskstatus mb-2">
                                            <span class="badge {{ $inprogress_task->custom_status_class }} bg-opacity-25 border border-1 rounded-pill px-3 py-1">
                                                {{ $inprogress_task->custom_status }}
                                            </span>
                                        </div>
                                        
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{ $inprogress_task->project->project_name }}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <div class="d-flex justify-content-between">
                                                <div class="tasknameleft">
                                                    <h6 class="mb-0">{{ $inprogress_task->description }}</h6>
                                                    <h5 class="mb-0">{{ $inprogress_task->created_user->name }}</h5>
                                                </div>

                                                <a href="{{ route('task.show', ['id' => $inprogress_task->id]) }}"
                                                    class="btn btn-sm text-dark"><i
                                                        class="fas fa-external-link-alt"></i></a>
                                            </div>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{ date('d-m-Y', strtotime($inprogress_task->end_timestamp)) }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Completed -->
                <div class="col-sm-12 col-md-4 col-xl-4 px-2">
                    <div class="stagemain">
                        <div class="completed">
                            <div class="completedct">
                                <h6 class="m-0">Completed</h6>
                            </div>
                            <div class="completedno totalno">
                                <h6 class="m-0 text-end">{{ count($completed_tasks) }}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag completed-list" data_status="completed">

                                @foreach ($completed_tasks as $completed_task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{ $completed_task->id }}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">
                                                <i class="fa-solid fa-circle {{ $completed_task->priority_color }}"></i>
                                                <h6 class="mb-0">{{ $completed_task->title }}</h6>
                                            </div>
                                            @if ($completed_task->file_attachment)
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0">
                                                <a href="{{ env('AWS_URL') . $completed_task->file_attachment }}"
                                                target="_blank"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="View Attachment">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                </a>

                                                <a href="{{ env('AWS_URL') . $completed_task->file_attachment }}"
                                                target="_blank"
                                                data-bs-toggle="tooltip"
                                                data-bs-title="Download Attachment"
                                                download>
                                                    <i class="fa-solid fa-download"></i>
                                                </a>

                                                    </h6>
                                                </div>
                                            @endif

                                            <a href="{{ route('task.show', ['id' => $completed_task->id]) }}"
                                                class="btn btn-sm text-dark"><i class="fas fa-external-link-alt"></i></a>
                                        </div>
                                        
                                        <!-- Custom Status Display -->
                                        <div class="taskstatus mb-2">
                                            <span class="badge {{ $completed_task->custom_status_class }} bg-opacity-25 border border-1 rounded-pill px-3 py-1">
                                                {{ $completed_task->custom_status }}
                                            </span>
                                        </div>
                                        
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{ $completed_task->project->project_name }}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h6 class="mb-0">{{ $completed_task->description }}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{ $completed_task->created_user->name }}</h5>
                                                @if ($completed_task->is_assigned == 0 && $completed_task->status == 'completed' && $completed_task->close_status == 0)
                                                    <a class="mb-0 btn_completedModal" data-bs-toggle="modal"
                                                        data_project_id="{{ $completed_task->project_id }}"
                                                        data_task_id="{{ $completed_task->id }}"
                                                        data_parent_id={{ $completed_task->parent_task_id }}
                                                        data-bs-target="#completedModal">
                                                        <button class="taskassignbtn">Assign</button>
                                                    </a>
                                                    <a class="mb-0 btn_closeModal" data-bs-toggle="modal"
                                                        data_project_id="{{ $completed_task->project_id }}"
                                                        data_task_id="{{ $completed_task->id }}"
                                                        data_parent_id={{ $completed_task->parent_task_id }}
                                                        data-bs-target="#closeModal">
                                                        <button class="taskassignbtn">close</button>
                                                    </a>
                                                @else
                                                    @if ($completed_task->is_assigned == 1)
                                                        <h6 class="text-success fw-semibold">Assigned</h6>
                                                    @else
                                                        <h6 class="fw-semibold {{ $completed_task->custom_status_class }}">{{ $completed_task->custom_status }}</h6>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{ date('d-m-Y', strtotime($completed_task->end_timestamp)) }}
                                            </h6>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Update close Modal -->
    <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="closeModalLabel">Close Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('close.task') }}" class="row" id="assign_task_close"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="close_task_id" id="close_task_id">

                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="file" class="col-form-label">File</label>
                            <label class="custom-file-upload" for="file_attachment">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                                </div>
                                <div class="text">
                                    <span id="file_attachment_text" class="text-center">Upload File</span>
                                </div>
                                <input type="file" name="close_file" id="file_attachment" 
                                    onchange="updateFileName('file_attachment', 'file_attachment_text')">
                            </label>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="description" class="col-form-label">Reamarks</label>
                            <textarea class="form-control" name="close_description" id="description" placeholder="Enter Close remarks"></textarea>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto">
                            <button type="submit" class="modalbtn">close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Assign Modal -->
    <div class="modal fade" id="completedModal" tabindex="-1" aria-labelledby="completedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="completedModalLabel">Assign Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('task.task_store') }}" class="row" id="assign_task"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="project_id" class="project_id">
                        <input type="hidden" name="old_task_id" id="task_id">
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="project_id">Project Name</label>
                            <select class="form-select project_id" id="sel_project_id" autofocus disabled>
                                <option value="" selected disabled>Select Projects</option>
                                @foreach ($projects as $project)
                                    <option data_assigned_to="{{ implode(',', $project->assigned_to ?? []) }}"
                                        value="{{ $project->id }}">
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="title" class="col-form-label">Task Title</label>
                            <input type="text" class="form-control" name="title" id="title"
                                placeholder="Enter Task Title">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="assigned_to" class="col-form-label">Assign To</label>
                            <select class="form-select" name="assigned_to" id="assigned_to">
                                <option value="" selected disabled>Select Employee</option>
                                @foreach ($employees as $employee)
                                    @if ($employee->id != auth()->user()->id)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-12 col-md-12">
                            <label for="enddate" class="col-form-label">Due Date</label>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 mb-3">
                                    <input type="date" class="form-control" name="enddate" id="enddate"
                                        pattern="\d{4}-\d{2}-\d{2}" min="{{ date('Y-m-d') }}" max="9999-12-31">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="priority" class="col-form-label">Priority</label>
                            <select class="form-select" name="priority" id="priority">
                                <option value="" selected disabled>Select Options</option>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="file" class="col-form-label">File</label>
                            <label class="custom-file-upload" for="file_attachment_modal">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                                </div>
                                <div class="text">
                                    <span id="file_attachment_modal_text" class="text-center">Upload File</span>
                                </div>
                                <input type="file" name="file_attachment" id="file_attachment_modal"
                                    onchange="updateFileName('file_attachment_modal', 'file_attachment_modal_text')">
                            </label>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="description" class="col-form-label">Task Desctiption</label>
                            <textarea class="form-control" name="description" id="description" placeholder="Enter Task Desctiption"></textarea>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto">
                            <button type="submit" class="modalbtn">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script src="{{ asset('assets/js/form_script.js') }}"></script>

    <!-- Draggable Card JS / Total Card JS -->
    <script>
        $(document).ready(function() {
            $(".todo-list, .inprogress-list, .completed-list, .onhold-list").sortable({
                connectWith: ".drag",
                placeholder: "ui-sortable-placeholder",
                start: function(event, ui) {
                    ui.item.addClass("dragging");
                },
                stop: function(event, ui) {
                    $(".draggablecard").removeClass("last-dragged");
                    ui.item.addClass("last-dragged");
                    ui.item.removeClass("dragging");

                    var task_status = ui.item.parent().attr("data_status");
                    var task_id = ui.item.attr("data_id");

                    console.log(task_status);

                    let postData = {
                        _token: "{{ csrf_token() }}",
                        task_id: task_id,
                        task_status: task_status
                    };

                    $.ajax({
                        url: "{{ route('dashboard.task_status_update') }}",
                        type: 'POST',
                        data: JSON.stringify(postData),
                        contentType: 'application/json',
                        success: function(response) {
                            if (task_status == "completed") {
                                location.href = "";
                            } else {
                                updateEmptyState();
                                updateTotalCards();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Error saving survey!');
                        }
                    });

                }
            }).disableSelection();

            function updateEmptyState() {
                $(".todo-list, .inprogress-list, .completed-list, .onhold-list").each(function() {
                    if ($(this).children(".draggablecard").length === 0) {
                        if ($(this).find(".empty-message").length === 0) {
                            $(this).append(
                                '<div class="empty-message" style="color: var(--primary)">No tasks available</div>'
                            );
                        }
                    } else {
                        $(this).find(".empty-message").remove();
                    }
                });
            }

            $(".todo-list, .inprogress-list, .completed-list, .onhold-list").on("sortover", function() {
                $(this).find(".empty-message").remove();
            });

            function updateTotalCards() {
                const columns = document.querySelectorAll('.col-xl-3');

                columns.forEach(function(column) {
                    const draggableCards = column.querySelectorAll('.draggablecard');
                    const totalNoElement = column.querySelector('.totalno h6');
                    if (totalNoElement) {
                        totalNoElement.textContent = draggableCards.length;
                    }
                });
            }
            updateTotalCards();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".completed-list .draggablecard").forEach(function(card) {
                card.setAttribute("draggable", "false");

                card.addEventListener("dragstart", function(event) {
                    event.preventDefault();
                    event.stopPropagation();
                });

                card.addEventListener("mousedown", function(event) {
                    event.stopPropagation();
                });
            });
        });

        $(document).ready(function() {
            $(document).on("click", ".btn_completedModal", function() {
                $('.project_id').val($(this).attr('data_project_id'));
                $('#task_id').val($(this).attr('data_task_id'));

                $("#assigned_to").val("");
                var assignedToIds = $("#sel_project_id option:selected").attr("data_assigned_to");
                var assignedToArray = assignedToIds ? assignedToIds.split(",") : [];
                $("#assigned_to option").each(function() {
                    var optionValue = $(this).val();
                    if (assignedToArray.includes(optionValue) || optionValue == "") {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $(document).on("click", ".btn_closeModal", function() {
                $('#close_task_id').val($(this).attr('data_task_id'));
            });
        });
    </script>
@endsection