<style>
    /* .profileleft {
        background-color: var(--theadbg);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        justify-content: space-between;
        padding: 3px;
        border-radius: 5px;
        width: 25%;
    } */
</style>
<div class="empdetails">
    <div class="listtable">
        <div class="row align-items-center justify-content-between">
            <ul class="profileleft col-sm-12 col-md-6 nav nav-tabs py-2" role="tablist" id="myTab1">
                <button class="profilefilterbtn active" role="tab" data-bs-toggle="tab" type="button"
                    data-bs-target="#overview">Overview</button>
                <button class="profilefilterbtn" role="tab" data-bs-toggle="tab" type="button"
                    data-bs-target="#report">Reports</button>
                <button class="profilefilterbtn" role="tab" data-bs-toggle="tab" type="button"
                    data-bs-target="#material">Material</button>
                <a class="profilefilterbtn" href="{{ route('project.stages', ['pro_id' => $project_id]) }}">List</a>
            </ul>
            <div class="profileright justify-content-end col-sm-12 col-md-4">
                {{-- <input type="text" id="filterInput10" class="form-control w-50" placeholder=" Search"> --}}
                @can('add-stage')
                    <a data-bs-toggle="modal" data-bs-target="#addStages" class="btn btn-sm btn-outline-dark"><i
                            class="fas fa-plus"></i> Stage</a>
                    <a data-bs-toggle="modal" data-bs-target="#act_stage" class="btn btn-sm btn-outline-dark"><i
                            class="fas fa-plus"></i> Activity</a>
                @endcan
                @if ($project->progress != 'excel')
                    <a data-bs-toggle="modal" data-bs-target="#uploadStages"><button class="profilelistbtn"><i
                                class="fas fa-upload"></i></button></a>
                @endif
            </div>
        </div>

        <div class="tab-content" id="myTab1Content">
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                @include('projects.progress_overview')
            </div>
            <div class="tab-pane fade" id="report" role="tabpanel">
                @include('projects.progress_report')
            </div>
            <div class="tab-pane fade" id="material" role="tabpanel">
                @include('projects.progress_material')
            </div>

        </div>

    </div>
</div>

<!-- Add Progress Modal -->
<div class="modal fade" id="addStages" tabindex="-1" aria-labelledby="addStagesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-gauge-simple-high"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <h4 class="modal-title mb-2 fs-5" id="addStagesLabel">Add Stage</h4>
                <form method="POST" action="{{ route('progress.single') }}">
                    @csrf
                    <input type="hidden" name="pro_id" value="{{ $project_id }}">
                    {{-- <div class="col-sm-12 col-md-12 mb-1">
                        <label for="stage" class="col-form-label">Stage</label>
                        <select class="form-select" name="stage" id="stage">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($pro_progress_stage as $stage)
                                <option value="{{ $stage->stage }}">{{ $stage->stage }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="activity" class="col-form-label">Stage Title</label>
                        <input type="text" class="form-control" name="stage" id="activity">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="activity" class="col-form-label">Duration</label>
                        <input type="text" class="form-control" name="duration" id="duration">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="startdate" class="col-form-label">Start</label>
                        <input type="date" class="form-control" name="startdate" id="startdate" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="enddate" class="col-form-label">End Date</label>
                        <input type="date" class="form-control" name="enddate" id="enddate" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    {{-- <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qcsync" class="col-form-label">QC Sync</label>
                        <select class="form-select" name="qcsync" id="qcsync">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($qc_stage as $qc_stages)
                                <option value="{{ $qc_stages->id }}">{{ $qc_stages->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- add activity against the stage --}}

<!-- Add Progress Modal -->
<div class="modal fade" id="act_stage" tabindex="-1" aria-labelledby="act_stageLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-gauge-simple-high"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="act_stageLabel">Add Activity</h4>
                <form method="POST" action="{{ route('progress.single_activity') }}">
                    @csrf
                    <input type="hidden" name="pro_id" value="{{ $project_id }}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="stage" class="col-form-label">Stage</label>
                        <select class="form-select" name="stage" id="stage">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($pro_progress_stage as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->stage }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="activity" class="col-form-label">Activity Title</label>
                        <input type="text" class="form-control" name="activity" id="activity">
                    </div>

                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qcsync" class="col-form-label">QC Sync</label>
                        <select class="form-select" name="qcsync" id="qcsync">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($qc_stage as $qc_stages)
                                <option value="{{ $qc_stages->id }}">{{ $qc_stages->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Stages Modal -->
<div class="modal fade" id="uploadStages" tabindex="-1" aria-labelledby="uploadStagesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-upload"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="uploadStagesLabel">Upload Stages</h4>
                <form method="post" action="{{ route('progress.import') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="pro_id" value="{{ $project_id }}">
                    <a href="{{ asset('img/superhome_progress.xlsx') }}" download>Sample</a>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="stage_file" class="col-form-label">Add File</label>
                        <label class="custom-file-upload" for="stage_file">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="stagetext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" name="excel_file" id="stage_file"
                                onchange="updateFileName('stage_file', 'stagetext')" />
                        </label>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        {{-- <a href="{{ route('project.stages') }}"> --}}
                        <button type="submit" class="modalbtn">Save</button>
                        {{-- </a> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#stage').on('change', function() {
        var stage = $(this).val();

        if (!stage) return;

        // alert(stage);

        $.ajax({
            url: "{{ route('stage.start_date') }}",
            method: 'POST',
            data: {
                stage: stage,
                pro_id: {{ $project_id }},
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                console.log(data);

                if (data.start_date) {
                    // Set minimum date and default value
                    // $('#startdate').attr('min', data.start_date);
                    // $('#startdate').val(data.start_date); // optional: prefill the field
                    // $('#enddate').attr('min', data.end_date);
                    // $('#enddate').val(data.end_date); // optional: prefill the field
                }
                // You can populate a field with start date, like:
                // $('#start_date').val(data.start_date);
            },
            error: function(xhr) {
                // console.error('Error:', xhr.responseText);
                // alert('Something went wrong while fetching stage start date.');
            }
        });
    });
</script>
