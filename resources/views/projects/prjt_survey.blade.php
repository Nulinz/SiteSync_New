<style>
    .profileleft {
        background-color: var(--theadbg);
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        align-items: center;
        justify-content: flex-start;
        padding: 6px 10px;
        border-radius: 5px;
    }
</style>

<div class="empdetails">
    <div class="listtable">
        <div class="profilelisthead row">
            <div class="profileleft col-sm-12 col-md-6">
                <button class="profilefilterbtn profilefilterbtn1 active"
                    onclick="filterList('all', 'surveyTable', 'profilefilterbtn1', 'noDataRow1')">All</button>
                <button class="profilefilterbtn profilefilterbtn1"
                    onclick="filterList('new', 'surveyTable', 'profilefilterbtn1', 'noDataRow1')">New</button>
                <button class="profilefilterbtn profilefilterbtn1"
                    onclick="filterList('completed', 'surveyTable', 'profilefilterbtn1', 'noDataRow1')">Completed</button>
                <button class="profilefilterbtn profilefilterbtn1"
                    onclick="filterList('pending', 'surveyTable', 'profilefilterbtn1', 'noDataRow1')">Pending</button>
                <button class="profilefilterbtn profilefilterbtn1"
                    onclick="filterList('approved', 'surveyTable', 'profilefilterbtn1', 'noDataRow1')">Approved</button>
            </div>

            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput1" class="form-control" placeholder=" Search">
                @can('add-survey')
                    <a data-bs-toggle="modal" data-bs-target="#addsurvey" class="btn bnt-sm bg-white">
                        <i class="fas fa-plus"></i> Survey
                    </a>
                @endcan
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="surveyTable">
                <thead>
                    <tr>
                        <th>Survey Name</th>
                        <th>Assigned User</th>
                        <th>Instruction</th>
                        <th>Due Date</th>
                        <th>Approved By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ent_surveys as $ent_survey)
                        @php
                            $currentDateTime = now();
                            $endDateTime = \Carbon\Carbon::parse($ent_survey->due_date);

                            if ($ent_survey->status == 'completed') {
                                $statusClass = 'text-success';
                                $statusName = 'Completed';
                            } elseif ($ent_survey->status == 'approved') {
                                $statusClass = 'text-success';
                                $statusName = 'Approved';
                            } elseif ($currentDateTime->greaterThan($endDateTime)) {
                                $statusClass = 'text-danger';
                                $statusName = 'Pending';
                            } else {
                                $statusClass = 'text-primary';
                                $statusName = 'New';
                            }
                        @endphp

                        <tr data-status="{{ strtolower($statusName) }}">
                            <td>{{ $ent_survey->survey->title }}</td>
                            <td>{{ $ent_survey->user->name }}</td>
                            <td>{{ $ent_survey->instruction }}</td>
                            <td>{{ date('d-m-Y', strtotime($ent_survey->due_date)) }}</td>
                            <td>{{ $ent_survey->approved }}</td>

                            <td><span class="{{ $statusClass }}">{{ $statusName }}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    @if ($ent_survey->file_attachment)
                                        <a href="{{ Storage::disk('s3')->url('survey/' . $ent_survey->file_attachment) }}" target="_blank">
                                            <i class="fas fa-cloud-arrow-down"></i>
                                        </a>
                                    @endif


                                    @if ($ent_survey->status == 'completed')
                                        @can('approve-survey')
                                            <a onclick="return confirm('Are you sure to Approve?')"
                                                href="{{ url('project-survey-status-update/' . $ent_survey->id . '/approved') }}">
                                                <i class="fas fa-circle-check" data-bs-toggle="tooltip"
                                                    data-bs-title="Approve"></i>
                                            </a>
                                        @endcan
                                        <a data-bs-toggle="modal" data-bs-target="#viewsurvey" class="view2"
                                            data-sur-ans="{{ $ent_survey->id }}" data-type="survey">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @elseif ($ent_survey->status == 'approved')
                                        <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip"
                                            data-bs-title="Approved"></i>
                                        <a data-bs-toggle="modal" data-bs-target="#viewsurvey" class="view2"
                                            data-sur-ans="{{ $ent_survey->id }}" data-type="survey">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Add Survey Modal -->
<div class="modal fade" id="addsurvey" tabindex="-1" aria-labelledby="addsurveyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-file-pen"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addsurveyLabel">Add Survey</h4>
                <form method="post" action="{{ route('project.survey_store') }}" enctype="multipart/form-data"
                    id="survey_add">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="survey_id" class="col-form-label">Survey Title</label>
                        <select class="form-select" name="survey_id" id="survey_id">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($surveys as $survey)
                                <option value="{{ $survey->id }}">{{ $survey->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="instruction" class="col-form-label">Instruction</label>
                        <textarea rows="2" class="form-control" name="instruction" id="instruction"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="assigned_to" class="col-form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to">
                            <option value="" selected disabled>Select User</option>
                            @foreach ($project_employees as $employee)
                                @if ($employee->id != auth()->user()->id)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="due_date" class="col-form-label">Due Date</label>
                        <input type="date" class="form-control" min="{{ date('Y-m-d') }}" max="9999-12-31"
                            name="due_date" id="due_date" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="survey_file" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="survey_file">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="surveytext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" name="file_attachment" id="survey_file"
                                onchange="updateFileName('survey_file', 'surveytext')" />
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

<!-- View Survey -->
<div class="modal fade" id="viewsurvey" tabindex="-1" aria-labelledby="viewsurveyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-file-pen"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="surveyPdfContent">

            </div>
            <div class="modal-footer">
                <button onclick="downloadSurveyPdf()" class="btn btn-primary">Download PDF</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).on("click", ".edit_button_survey", function() {
            $('#edit_id').val($(this).attr('data_id'));
            $('#edit_survey_id').val($(this).attr('data_survey_id'));
            $('#edit_instruction').val($(this).attr('data_instruction'));
            $('#edit_assigned_to').val($(this).attr('data_assigned_to'));
            $('#edit_due_date').val($(this).attr('data_due_date'));
            $('#edit_surveytext').text($(this).attr('data_file_name'));
        });
    });
</script>

<script>
    $('.view2').on('click', function() {
        var sur_ans = $(this).data('sur-ans');
        var type = $(this).data('type');
        $.ajax({
            url: "{{ route('ans.ajax') }}",
            method: 'POST', // Method should be a string,
            data: {
                ajax_id: sur_ans,
                type: type,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                $('#viewsurvey .modal-body').html(res.data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // Handle errors gracefully
            }
        });
    });
</script>

<!-- html2pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function loadAnswer(id, type) {
        $.ajax({
            url: "{{ route('ans.ajax') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                ajax_id: id,
                type: type
            },
            success: function(response) {
                $('#surveyPdfContent').html(response.data);
                $('#viewsurvey').modal('show');
            },
            error: function() {
                alert("Failed to load data.");
            }
        });
    }

    function downloadSurveyPdf() {
        const element = document.getElementById('surveyPdfContent');

        const opt = {
            margin: 0.5,
            filename: 'survey-report.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'a4',
                orientation: 'portrait'
            }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>