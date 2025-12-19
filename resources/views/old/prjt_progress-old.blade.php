<div class="empdetails">
    <div class="mt-3 listtable">
        <div class="profilelisthead row mb-3">
            <div class="profileleft col-sm-12 col-md-6">
                <button class="profilefilterbtn profilefilterbtn3 active"
                    onclick="filterList('all', 'progressTable', 'profilefilterbtn3', 'noDataRow3')">All</button>
                <button class="profilefilterbtn profilefilterbtn3"
                    onclick="filterList('completed', 'progressTable', 'profilefilterbtn3', 'noDataRow3')">Completed</button>
                <button class="profilefilterbtn profilefilterbtn3"
                    onclick="filterList('pending', 'progressTable', 'profilefilterbtn3', 'noDataRow3')">To Do</button>
            </div>
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput3" class="form-control" placeholder=" Search">
                <a data-bs-toggle="modal" data-bs-target="#addmaintask"><button class="profilelistbtn"><i
                            class="fas fa-plus"></i></button></a>
                <a data-bs-toggle="modal" data-bs-target="#addsubtask"><button class="profilelistbtn"><i
                            class="fas fa-plus-minus"></i></button></a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="progressTable">
                <thead>
                    <tr>
                        <th style="width: 300px">Task Name</th>
                        <th style="width: 250px">Description</th>
                        <th style="width: 200px">Assigned User</th>
                        <th style="width: 200px">Start Date</th>
                        <th style="width: 200px">End Date</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                                        <tr data-status="{{$task->status}}">
                                            <td>{{$task->title}}</td>
                                            <td>{{$task->description}}</td>
                                            <td>{{$task->user->name}}</td>
                                            <td>{{date('Y-m-d', strtotime($task->start_timestamp))}}</td>
                                            <td>{{date('Y-m-d', strtotime($task->end_timestamp))}}</td>
                                            @php
                                                if ($task->status == 'in_progress') {
                                                    $statusClass = 'text-warning';
                                                    $statusName = 'In Progress';
                                                } elseif ($task->status == 'on_hold') {
                                                    $statusClass = 'text-primary';
                                                    $statusName = 'On Hold';
                                                } elseif ($task->status == 'pending') {
                                                    $statusClass = 'text-danger';
                                                    $statusName = 'To Do';
                                                } elseif ($task->status == 'completed') {
                                                    $statusClass = 'text-success';
                                                    $statusName = 'Completed';
                                                }
                                            @endphp
                                            <td><span class="{{$statusClass}}">{{ucwords($statusName)}}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a data_id="{{$task->id}}" class="task_view_button"><i class="fas fa-eye"></i></a>
                                                    <a data-bs-toggle="modal" class="edit_button_task" data-bs-target="#editmaintask"
                                                        data_id="{{$task->id}}" data_title="{{$task->title}}"
                                                        data_description="{{$task->description}}" data_assigned_to="{{$task->assigned_to}}"
                                                        data_start_date="{{date('Y-m-d', strtotime($task->start_timestamp))}}"
                                                        data_end_date="{{date('Y-m-d', strtotime($task->end_timestamp))}}"
                                                        data_file_name="{{basename($task->file_attachment)}}"><i
                                                            class="fas fa-pen-to-square"></i></a>
                                                    <a href="{{ url('/download_file', urlencode($task->file_attachment)) }}"><i
                                                            class="fas fa-cloud-arrow-down"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end taskOffcanvas" tabindex="-1" id="mainTask" aria-labelledby="mainTaskLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">

        <!-- Body Header -->
        <div class="offcanvas-header px-0">
            <div class="offcanvas-left">
                <h5 class="offcanvas-title" id="mainTaskLabel">Main Task Name</h5>
                <h6 class="m-0" id="mainTaskDescriptionLabel">Cras pharetra mi tristique sapien vestibulum lobortis. Nam
                    eget bibendum metus.</h6>
            </div>
            <div class="offcanvas-right">
                <div class="progress-container">
                    <div class="linear-progress">
                        <div class="progress-bar" id="progress-bar-1"></div>
                    </div>
                    <div class="progress-value" id="progress-value-1"></div>
                </div>
            </div>
        </div>

        <!-- Body Timeline -->
        <div class="container ps-0 pe-2" id="timelinecards">
            <div class="timeline progress_task_preview">



            </div>
        </div>


    </div>
</div>

<!-- Add Main Task Modal -->
<div class="modal fade" id="addmaintask" tabindex="-1" aria-labelledby="addmaintaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-bars-progress"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addmaintaskLabel">Add Main Task</h4>
                <form method="post" action="{{ route('project.task_store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="project_id" value="{{$project->id}}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="title" class="col-form-label">Main Task Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea rows="2" class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="start_date" class="col-form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="end_date" class="col-form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="assigned_to" class="col-form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to">
                            <option value="" selected disabled>Select User</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="taskfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="taskfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" alt="">
                            </div>
                            <div class="text">
                                <span id="tasktext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="taskfile" name="file_attachment" multiple
                                onchange="updateFileName('taskfile', 'tasktext')">
                        </label>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Main Task Modal -->
<div class="modal fade" id="editmaintask" tabindex="-1" aria-labelledby="editmaintaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-bars-progress"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="editmaintaskLabel">Edit Main Task</h4>
                <form method="post" action="{{ route('project.task_store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_id_progress">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_title_progress" class="col-form-label">Main Task Title</label>
                        <input type="text" class="form-control" name="title" id="edit_title_progress">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_description_progress" class="col-form-label">Description</label>
                        <textarea rows="2" class="form-control" name="description"
                            id="edit_description_progress"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_start_date_progress" class="col-form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="edit_start_date_progress"
                            min="1000-01-01" max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_end_date_progress" class="col-form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="edit_end_date_progress"
                            min="1000-01-01" max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_assigned_to_progress" class="col-form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="edit_assigned_to_progress">
                            <option value="" selected disabled>Select User</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edittaskfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="edittaskfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" alt="">
                            </div>
                            <div class="text">
                                <span id="edittasktext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="edittaskfile" name="file_attachment" multiple
                                onchange="updateFileName('edittaskfile', 'edittasktext')">
                        </label>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Sub Task Modal -->
<div class="modal fade" id="addsubtask" tabindex="-1" aria-labelledby="addsubLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-diagram-next"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addsubLabel">Add Sub Task</h4>
                <form method="post" action="{{ route('project.task_store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="parent_task_id" class="col-form-label">Main Task</label>
                        <select class="form-select" name="parent_task_id" id="parent_task_id">
                            <option value="" selected disabled>Select Main Task</option>
                            @foreach($tasks as $task)
                                <option value="{{$task->id}}">{{$task->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="title" class="col-form-label">Sub Task Title</label>
                        <input type="text" class="form-control" name="title" id="title">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea rows="2" class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="start_date" class="col-form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="start_date" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="end_date" class="col-form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="end_date" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="assigned_to" class="col-form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to">
                            <option value="" selected disabled>Select User</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="subtaskfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="subtaskfile">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                    <path
                                        d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L296 392c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z" />
                                </svg>
                            </div>
                            <div class="text">
                                <span id="subtasktext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="subtaskfile" multiple name="file_attachment"
                                onchange="updateFileName('subtaskfile', 'subtasktext')">
                        </label>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Task Timeline Progress Bar -->
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
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function () {
        $(document).on("click", ".edit_button_task", function () {
            $('#edit_id_progress').val($(this).attr('data_id'));
            $('#edit_title_progress').val($(this).attr('data_title'));
            $('#edit_description_progress').val($(this).attr('data_description'));
            $('#edit_start_date_progress').val($(this).attr('data_start_date'));
            $('#edit_end_date_progress').val($(this).attr('data_end_date'));
            $('#edit_assigned_to_progress').val($(this).attr('data_assigned_to'));
            $('#edittasktext').text($(this).attr('data_file_name'));
        });

        $(document).on("click", ".task_view_button", function () {

            let postData = {
                _token: "{{ csrf_token() }}",
                task_id: $(this).attr('data_id')
            };

            $.ajax({
                url: "{{route('project.task_view')}}",
                type: 'POST',
                data: JSON.stringify(postData),
                contentType: 'application/json',
                success: function (response) {
                    $('.progress_task_preview').html(response.data.task_html);
                    $('#mainTaskLabel').text(response.data.task_title);
                    $('#mainTaskDescriptionLabel').text(response.data.task_description);
                    updateProgress("progress-bar-1", "progress-value-1", response.data.task_percentage);
                    var offcanvasElement = new bootstrap.Offcanvas(document.getElementById("mainTask"));
                    offcanvasElement.show();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('Error saving survey!');
                }
            });

        });
    });
</script>