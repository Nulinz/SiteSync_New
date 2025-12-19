@extends('layouts.app')

@section('content')

    <div class="body-div px-4 py-1 mb-3">
        <div class="body-head">
            <div class="body-h6 mb-3">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('task.index') }}">Task /</a></h6>
                <h6 class="head2h6"><a href="{{ route('task.create') }}">Add Task</a></h6>
            </div>
        </div>

        <div class="body-head mb-3">
            <h4 class="m-0">Add Task</h4>
        </div>

        <form id="taskForm" method="POST" action="{{ route('task.store') }}" onsubmit="return validateForm()"
            enctype="multipart/form-data">
            @csrf
            <div class="form-div pt-0">
                <div class="body-head my-3">
                    <div>
                        <h4 class="mb-1">Task Details</h4>
                        <h6 class="m-0 head1h6">Define the unique identity of the task, including its title,
                            timestamp, and associated category, sub-category, ensuring seamless tracking and
                            management.</h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 inputs">
                        <label for="project_id">Project Name</label>
                        <select class="form-select" name="project_id" id="project_id" autofocus required>
                            <option value="" selected disabled>Select Projects</option>
                            @foreach($projects as $project)
                                <option data_assigned_to="{{implode(',', $project->assigned_to)}}" value="{{$project->id}}">
                                    {{$project->project_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="title">Task Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                        <span class="text-danger error" id="title_error"></span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="assigned_to">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to" required>
                            <option value="" selected disabled>Select Employee</option>
                            @foreach($employees as $employee)
                                @if($employee->id != auth()->user()->id)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="text-danger error" id="assigned_to_error"></span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4 mb-3 inputs">
                        <label for="category_id">Category</label>
                        <select class="form-select" name="category_id" id="category_id" required>
                            <option value="" selected disabled>Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->category}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error" id="category_id_error"></span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4 mb-3 inputs">
                        <label for="sub_category_id">Sub Category</label>
                        <select class="form-select" name="sub_category_id" id="sub_category_id" required>
                            <option value="" selected disabled>Select Sub Category</option>
                            @foreach($sub_categories as $sub_category)
                                <option value="{{$sub_category->id}}" data_category_id="{{$sub_category->category_id}}"
                                    style="display:none;">{{$sub_category->sub_category}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger error" id="sub_category_id_error"></span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-4 mb-3 inputs">
                        <label for="priority">Priority</label>
                        <select class="form-select" name="priority" id="priority" required>
                            <option value="" selected disabled>Select Priority</option>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                        <span class="text-danger error" id="priority_error"></span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="start">Start Timestamp</label>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <input type="date" class="form-control" name="startdate" id="startdate" min="1000-01-01"
                                    max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}" required>
                                <span class="text-danger error" id="startdate_error"></span>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control" name="starttime" id="starttime" required>
                                <span class="text-danger error" id="starttime_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="end">End Timestamp</label>
                        <div class="row">
                            <div class="col-sm-6 mb-2">
                                <input type="date" class="form-control" name="enddate" id="enddate" min="1000-01-01"
                                    max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}" required>
                                <span class="text-danger error" id="enddate_error"></span>
                            </div>
                            <div class="col-sm-6">
                                <input type="time" class="form-control" name="endtime" id="endtime" required>
                                <span class="text-danger error" id="endtime_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="description">Task Description</label>
                        <textarea rows="2" class="form-control" name="description" id="description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-xl-6 mb-3 inputs">
                        <label for="additional_info">Additional Information</label>
                        <textarea rows="2" class="form-control" name="additional_info" id="additional_info"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 col-xl-12 mb-3 px-2 inputs">
                        <label for="file">File Attachment</label>
                        <label class="custom-file-upload w-100 shadow-none" for="file_upload">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" alt="">
                            </div>
                            <div class="text">
                                <span id="file-text" class="text-center">Choose a file (JPEG, PNG, PDF formats, upto
                                    50MB)</span>
                            </div>
                            <input type="file" id="file_upload" class="form-control" name="file_attachment"
                                onchange="updateFileName('file_upload', 'file-text')">
                        </label>
                        <span class="text-danger error" id="file_error"></span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center mt-3">
                <button type="submit" id="sub" class="formbtn">Add Task</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function validateForm() {
            let isValid = true;
            const fields = [
                { id: 'title', message: 'Task Title is required.' },
                { id: 'assigned_to', message: 'Please select an employee.' },
                { id: 'category_id', message: 'Please select a category.' },
                { id: 'sub_category_id', message: 'Please select a sub-category.' },
                { id: 'priority', message: 'Please select a priority.' },
                { id: 'startdate', message: 'Start Date is required.' },
                { id: 'starttime', message: 'Start Time is required.' },
                { id: 'enddate', message: 'End Date is required.' },
                { id: 'endtime', message: 'End Time is required.' },
                { id: 'description', message: 'Task Description is required.' },
                { id: 'file', message: 'File attachment is required.' }
            ];

            // Loop through fields and validate input
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorLabel = document.getElementById(field.id + '_error');

                if (!input || !errorLabel) return;

                if (!input.value || input.value.trim() === '') {
                    errorLabel.textContent = field.message;
                    isValid = false;
                } else {
                    errorLabel.textContent = '';
                }
            });

            // Additional Validation: End Date-Time should be after Start Date-Time
            const startDateTime = new Date(`${document.getElementById('startdate').value}T${document.getElementById('starttime').value}`);
            const endDateTime = new Date(`${document.getElementById('enddate').value}T${document.getElementById('endtime').value}`);
            const endDateError = document.getElementById('enddate_error');
            const endTimeError = document.getElementById('endtime_error');

            if (endDateTime <= startDateTime) {
                endDateError.textContent = 'End date and time must be after start date and time.';
                endTimeError.textContent = 'End date and time must be after start date and time.';
                isValid = false;
            } else {
                endDateError.textContent = '';
                endTimeError.textContent = '';
            }


            //  // Disable the submit button to prevent double submission

            if (isValid) $('#sub').prop('disabled', true).text('Saving...');



            return isValid;
        }

        $('#category_id').change(function () {
            let selectedType = $(this).val(); // Get selected drawing type
            // Hide all options except default
            $('#sub_category_id option').hide();
            $('#sub_category_id option:first').show(); // Keep "Select Options" visible
            // Show only options that match selected drawing type
            $('#sub_category_id option[data_category_id="' + selectedType + '"]').show();
            // Reset selection
            $('#sub_category_id').val('');
        });

        $("#project_id").change(function () {
            $("#assigned_to").val("");
            var assignedToIds = $("#project_id option:selected").attr("data_assigned_to");
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

    </script>

@endsection