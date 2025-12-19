@extends ('layouts.app')

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
                <!-- Todo -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="todo">
                            <div class="todoct">
                                <h6 class="m-0">To Do</h6>
                            </div>
                            <div class="todono totalno">
                                <h6 class="m-0 text-end">{{count($todo_tasks)}}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag todo-list" data_status="pending">

                                @foreach($todo_tasks as $todo_task)

                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{$todo_task->id}}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">

                                                <i class="fa-solid fa-circle {{taskColor($todo_task->priority)}}"></i>
                                                <h6 class="mb-0">{{$todo_task->title}}</h6>
                                            </div>
                                            @if($todo_task->file_attachment)
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('task.download', $todo_task->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                                                class="fa-solid fa-paperclip"></i></a>
                                                    </h6>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="taskcategory mb-2">
                                            <h6 class="mb-0"><span
                                                    class="category">{{$todo_task->category->category_title ?? '-'}}</span> /
                                                <span
                                                    class="subcat">{{$todo_task->sub_category->sub_category_title ?? '-'}}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{$todo_task->project->project_name}}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h6 class="mb-0">{{$todo_task->description}}</h6>
                                            <h5 class="mb-0">{{$todo_task->created_user->name}}</h5>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate"><i
                                                    class="fa-regular fa-calendar"></i>&nbsp;{{date('d/m/Y', strtotime($todo_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                {{date('d/m/Y', strtotime($todo_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{date('h:i A', strtotime($todo_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{date('h:i A', strtotime($todo_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Inprogress -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="inprogress">
                            <div class="inprgct">
                                <h6 class="m-0">In Progress</h6>
                            </div>
                            <div class="inprgno totalno">
                                <h6 class="m-0 text-end">{{count($inprogress_tasks)}}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag inprogress-list" data_status="in_progress">

                                @foreach($inprogress_tasks as $inprogress_task)

                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{$inprogress_task->id}}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">
                                                <i class="fa-solid fa-circle {{taskColor($inprogress_task->priority)}}"></i>
                                                <h6 class="mb-0">{{$inprogress_task->title}}</h6>
                                            </div>
                                            @if($inprogress_task->file_attachment)
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('task.download', $inprogress_task->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                                                class="fa-solid fa-paperclip"></i></a>
                                                    </h6>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="taskcategory mb-2">
                                            <h6 class="mb-0"><span
                                                    class="category">{{$inprogress_task->category->category_title ?? '-'}}</span>
                                                /
                                                <span
                                                    class="subcat">{{$inprogress_task->sub_category->sub_category_title ?? '-'}}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{$inprogress_task->project->project_name}}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h6 class="mb-0">{{$inprogress_task->description}}</h6>
                                            <h5 class="mb-0">{{$inprogress_task->created_user->name}}</h5>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate"><i
                                                    class="fa-regular fa-calendar"></i>&nbsp;{{date('d/m/Y', strtotime($inprogress_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                {{date('d/m/Y', strtotime($inprogress_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{date('h:i A', strtotime($inprogress_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{date('h:i A', strtotime($inprogress_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <!-- On Hold -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="onhold">
                            <div class="onholdct">
                                <h6 class="m-0">On Hold</h6>
                            </div>
                            <div class="onholdno totalno">
                                <h6 class="m-0 text-end">{{count($onhold_tasks)}}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag onhold-list" data_status="on_hold">

                                @foreach($onhold_tasks as $onhold_task)

                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{$onhold_task->id}}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">
                                                <i class="fa-solid fa-circle {{taskColor($onhold_task->priority)}}"></i>
                                                <h6 class="mb-0">{{$onhold_task->title}}</h6>
                                            </div>
                                            @if($onhold_task->file_attachment)
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('task.download', $onhold_task->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                                                class="fa-solid fa-paperclip"></i></a>
                                                    </h6>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="taskcategory mb-2">
                                            <h6 class="mb-0"><span
                                                    class="category">{{$onhold_task->category->category_title ?? '-'}}</span> /
                                                <span
                                                    class="subcat">{{$onhold_task->sub_category->sub_category_title ?? '-'}}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{$onhold_task->project->project_name}}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h6 class="mb-0">{{$onhold_task->description}}</h6>
                                            <h5 class="mb-0">{{$onhold_task->created_user->name}}</h5>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate"><i
                                                    class="fa-regular fa-calendar"></i>&nbsp;{{date('d/m/Y', strtotime($onhold_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                {{date('d/m/Y', strtotime($onhold_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{date('h:i A', strtotime($onhold_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{date('h:i A', strtotime($onhold_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Completed -->
                <div class="col-sm-12 col-md-3 col-xl-3 px-2">
                    <div class="stagemain">
                        <div class="completed">
                            <div class="completedct">
                                <h6 class="m-0">Completed</h6>
                            </div>
                            <div class="completedno totalno">
                                <h6 class="m-0 text-end">{{count($completed_tasks)}}</h6>
                            </div>
                        </div>

                        <div class="cardmain">
                            <div class="row drag completed-list" data_status="completed">

                                @foreach($completed_tasks as $completed_task)
                                    <div class="col-sm-12 col-md-11 col-xl-11 mb-2 d-block mx-auto draggablecard"
                                        data_id="{{$completed_task->id}}">
                                        <div class="taskname mb-2">
                                            <div class="tasknameleft">
                                                <i class="fa-solid fa-circle {{taskColor($completed_task->priority)}}"></i>
                                                <h6 class="mb-0">{{$completed_task->title}}</h6>
                                            </div>
                                            @if($completed_task->file_attachment)
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0"><a href="{{ route('task.download', $completed_task->id) }}"
                                                            data-bs-toggle="tooltip" data-bs-title="Attachment" download><i
                                                                class="fa-solid fa-paperclip"></i></a></h6>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="taskcategory mb-2">
                                            <h6 class="mb-0"><span
                                                    class="category">{{$completed_task->category->category_title ?? '-'}}</span>
                                                /
                                                <span
                                                    class="subcat">{{$completed_task->sub_category->sub_category_title ?? '-'}}</span>
                                            </h6>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h5 class="mb-0">{{$completed_task->project->project_name}}</h5>
                                        </div>
                                        <div class="taskdescp mb-2">
                                            <h6 class="mb-0">{{$completed_task->description}}</h6>
                                            <div class="taskdescpdiv">
                                                <h5 class="mb-0">{{$completed_task->created_user->name}}</h5>
                                                @if($completed_task->is_assigned == 0)
                                                    <a class="mb-0 btn_completedModal" data-bs-toggle="modal"
                                                        data_project_id="{{$completed_task->project_id}}"
                                                        data_task_id="{{$completed_task->id}}" data_parent_id={{ $completed_task->parent_task_id }} data-bs-target="#completedModal">
                                                        <button class="taskassignbtn">Assign</button>
                                                    </a>
                                                @else
                                                    <h6 class="text-success fw-semibold">Assigned</h6>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="taskdate mb-2">
                                            <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                                                {{date('d/m/Y', strtotime($completed_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                {{date('d/m/Y', strtotime($completed_task->end_timestamp))}}
                                            </h6>
                                        </div>
                                        <div class="taskdate">
                                            <h6 class="m-0 startdate"><i class="fa-solid fa-hourglass-start"></i>&nbsp;
                                                {{date('h:i A', strtotime($completed_task->start_timestamp))}}
                                            </h6>
                                            <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                {{date('h:i A', strtotime($completed_task->end_timestamp))}}
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

    <!-- Update Assign Modal -->
    <div class="modal fade" id="completedModal" tabindex="-1" aria-labelledby="completedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="completedModalLabel">Assign Task</h4>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('dashboard.task_store') }}" class="row"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="project_id" class="project_id">
                        <input type="hidden" name="task_id" id="task_id">
                        {{-- <input type="hidden" name="parent_id" id="parent_id"> --}}
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="project_id">Project Name</label>
                            <select class="form-select project_id" id="sel_project_id" autofocus disabled>
                                <option value="" selected disabled>Select Projects</option>
                                @foreach($projects as $project)
                                    <option data_assigned_to="{{implode(',', $project->assigned_to)}}" value="{{$project->id}}">
                                        {{$project->project_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="title" class="col-form-label">Task Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Enter Task Title">
                        </div>
                        <div class="col-sm-12 col-md-6 mb-3">
                            <label for="assigned_to" class="col-form-label">Assign To</label>
                            <select class="form-select" name="assigned_to" id="assigned_to">
                                <option value="" selected disabled>Select Employee</option>
                                @foreach($employees as $employee)
                                    @if($employee->id != auth()->user()->id)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <label for="startdate" class="col-form-label">Start Timestamp</label>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 mb-3">
                                    <input type="date" class="form-control" name="startdate" id="startdate"
                                        pattern="\d{4}-\d{2}-\d{2}" min="{{date('Y-m-d')}}" max="9999-12-31">
                                </div>
                                <div class="col-sm-12 col-md-6 mb-3">
                                    <input type="time" class="form-control" name="starttime" id="starttime">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12">
                            <label for="enddate" class="col-form-label">End Timestamp</label>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 mb-3">
                                    <input type="date" class="form-control" name="enddate" id="enddate"
                                        pattern="\d{4}-\d{2}-\d{2}" min="{{date('Y-m-d')}}" max="9999-12-31">
                                </div>
                                <div class="col-sm-12 col-md-6 mb-3">
                                    <input type="time" class="form-control" name="endtime" id="endtime">
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
                            <label class="custom-file-upload" for="file_attachment">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                                </div>
                                <div class="text">
                                    <span id="file_attachment_text" class="text-center">Upload File</span>
                                </div>
                                <input type="file" name="file_attachment" id="file_attachment"
                                    onchange="updateFileName('file_attachment', 'file_attachment_text')" required>
                            </label>
                        </div>
                        <div class="col-sm-12 col-md-12 mb-3">
                            <label for="description" class="col-form-label">Task Desctiption</label>
                            <textarea class="form-control" name="description" id="description"
                                placeholder="Enter Task Desctiption"></textarea>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mx-auto">
                            <button type="submit" class="modalbtn">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    function taskColor($priority)
    {
        if ($priority == "Low") {
            $color = "text-primary";
        } else if ($priority == "Medium") {
            $color = "text-warning";
        } else if ($priority == "High") {
            $color = "text-danger";
        } else {
            $color = "";
        }
        return $color;
    }
                                    ?>

    <!-- Script CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <!-- Draggable Card JS / Total Card JS -->
    <script>
        $(document).ready(function () {
            $(".todo-list, .inprogress-list, .completed-list, .onhold-list").sortable({
                connectWith: ".drag",
                placeholder: "ui-sortable-placeholder",
                start: function (event, ui) {
                    ui.item.addClass("dragging");
                },
                stop: function (event, ui) {
                    $(".draggablecard").removeClass("last-dragged");
                    ui.item.addClass("last-dragged");
                    ui.item.removeClass("dragging");

                    // Find the parent class
                    var task_status = ui.item.parent().attr("data_status");
                    var task_id = ui.item.attr("data_id");

                    //if(task_status != "pending") {
                    console.log(task_status);

                    let postData = {
                        _token: "{{ csrf_token() }}",
                        task_id: task_id,
                        task_status: task_status
                    };

                    $.ajax({
                        url: "{{route('dashboard.task_status_update')}}",
                        type: 'POST',
                        data: JSON.stringify(postData),
                        contentType: 'application/json',
                        success: function (response) {
                            if (task_status == "completed") {
                                location.href = "";
                            } else {
                                updateEmptyState();
                                updateTotalCards();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error:', error);
                            alert('Error saving survey!');
                        }
                    });
                    //}

                }
            }).disableSelection();

            function updateEmptyState() {
                $(".todo-list, .inprogress-list, .completed-list, .onhold-list").each(function () {
                    if ($(this).children(".draggablecard").length === 0) {
                        if ($(this).find(".empty-message").length === 0) {
                            $(this).append('<div class="empty-message" style="color: var(--primary)">No tasks available</div>');
                        }
                    } else {
                        $(this).find(".empty-message").remove();
                    }
                });
            }

            $(".todo-list, .inprogress-list, .completed-list, .onhold-list").on("sortover", function () {
                $(this).find(".empty-message").remove();
            });

            // Total Count
            function updateTotalCards() {
                const columns = document.querySelectorAll('.col-xl-3');

                columns.forEach(function (column) {
                    const draggableCards = column.querySelectorAll('.draggablecard');
                    const totalNoElement = column.querySelector('.totalno h6');
                    totalNoElement.textContent = draggableCards.length;
                });
            }
            updateTotalCards();
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".completed-list .draggablecard").forEach(function (card) {
                card.setAttribute("draggable", "false"); // Ensure it's not draggable

                // Prevent dragging by overriding drag events
                card.addEventListener("dragstart", function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                });

                card.addEventListener("mousedown", function (event) {
                    event.stopPropagation(); // Prevent mousedown events that trigger drag behavior
                });
            });
        });

        $(document).ready(function () {
            $(document).on("click", ".btn_completedModal", function () {
                // alert("hello");
                $('.project_id').val($(this).attr('data_project_id'));
                $('#task_id').val($(this).attr('data_task_id'));
                // $('#parent_id').val($(this).attr('data_parent_id'));

                $("#assigned_to").val("");
                var assignedToIds = $("#sel_project_id option:selected").attr("data_assigned_to");
                var assignedToArray = assignedToIds ? assignedToIds.split(",") : [];
                $("#assigned_to option").each(function () {
                    var optionValue = $(this).val();
                    if (assignedToArray.includes(optionValue) || optionValue == "") {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>


@endsection