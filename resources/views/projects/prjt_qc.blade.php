<style>
    .profileleft {
        /* background-color: var(--theadbg);
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        align-items: center;
        justify-content: space-between;
        padding: 3px;
        border-radius: 5px;
        width: 25%; */
    }
</style>
<div class="empdetails">
    <div class="listtable">
        <div class="row align-items-center justify-content-between">
            <div class="profileleft col-sm-12 col-md-8 nav nav-tabs py-2">
                <button class="profilefilterbtn profilefilterbtn4 active"
                    onclick="filterList('all', 'qcTable', 'profilefilterbtn4', 'noDataRow4')">All</button>
                <button class="profilefilterbtn profilefilterbtn4"
                    onclick="filterList('new', 'qcTable', 'profilefilterbtn4', 'noDataRow4')">New</button>
                <button class="profilefilterbtn profilefilterbtn4"
                    onclick="filterList('completed', 'qcTable', 'profilefilterbtn4', 'noDataRow4')">Completed</button>
                <button class="profilefilterbtn profilefilterbtn4"
                   onclick="filterList('pending', 'qcTable', 'profilefilterbtn4', 'noDataRow4')">Pending</button>
                <button class="profilefilterbtn profilefilterbtn4"
                    onclick="filterList('approved', 'qcTable', 'profilefilterbtn4', 'noDataRow4')">Approved</button>

            </div>
            <div class="profileright justify-content-end col-sm-12 col-md-4">
                <input type="text" id="filterInput4" class="form-control w-50" placeholder=" Search">
                @can('add-qc')
                    <a data-bs-toggle="modal" data-bs-target="#addqc" class="btn btn-sm btn-outline-dark"><i
                            class="fas fa-plus"></i> QC</a>
                @endcan
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="qcTable">
                <thead>
                    <tr>
                        <th>QC Title</th>
                        <th>QC Checklist</th>
                        <th>Assigned User</th>
                        <th>Due Date</th>
                        <th>Approved By</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
<tbody>
    @foreach ($ent_qcs as $ent_qc)
        @php
            $currentDateTime = now();
            $endDateTime = \Carbon\Carbon::parse($ent_qc->due_date);

            if ($ent_qc->status == 'completed') {
                $statusClass = 'text-success';
                $statusName = 'Completed';
            } elseif ($ent_qc->status == 'approved') {
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

        {{-- Use lowercase value for data-status --}}
        <tr data-status="{{ strtolower($statusName) }}">
            <td>{{ $ent_qc->qc->title }}</td>
            <td>{{ implode(',', $ent_qc->checklist_names) ?? 'nil' }}</td>
            <td>{{ $ent_qc->user->name }}</td>
            <td>{{ date('d-m-Y', strtotime($ent_qc->due_date)) }}</td>
            <td>{{ $ent_qc->approved }}</td>
            <td><span class="{{ $statusClass }}">{{ $statusName }}</span></td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    @if ($ent_qc->file_attachment)
                        <a href="{{ Storage::disk('s3')->url('qc/' . $ent_qc->file_attachment) }}" target="_blank">
                            <i class="fas fa-cloud-arrow-down"></i>
                        </a>
                    @endif

                    @if ($ent_qc->status == 'completed')
                        @can('approve-qc')
                            <a onclick="return confirm('Are you sure to Approve?')"
                               href="{{ url('project-qc-status-update/' . $ent_qc->id . '/approved') }}">
                                <i class="fas fa-circle-check" data-bs-toggle="tooltip" data-bs-title="Approve"></i>
                            </a>
                        @endcan
                        <a data-bs-toggle="modal" data-bs-target="#viewqc" class="view1"
                           data-qc-ans="{{ $ent_qc->id }}" data-type="qc">
                           <i class="fas fa-eye"></i>
                        </a>
                    @elseif ($ent_qc->status == 'approved')
                        <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip" data-bs-title="Approved"></i>
                        <a data-bs-toggle="modal" data-bs-target="#viewqc" class="view1"
                           data-qc-ans="{{ $ent_qc->id }}" data-type="qc">
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

<!-- Add QC -->
<div class="modal fade" id="addqc" tabindex="-1" aria-labelledby="addqcLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addqcLabel">Add QC</h4>
                <form method="post" action="{{ route('project.qc_store') }}" enctype="multipart/form-data"
                    id="qc_add">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qc_title" class="col-form-label">QC Title</label>
                        <select class="form-select" name="qc_title" id="qc_title">
                            <option value="" selected disabled>Select QC Title</option>
                            @foreach ($qcs as $qc)
                                <option value="{{ $qc->id }}">{{ $qc->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qcchecklist" class="col-form-label">QC Checklist</label>
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="dropdown-center tble-dpd ">
                                <button class="w-100 text-start form-select checkdrp" type="button"
                                    data-bs-toggle="dropdown" id="assignto" aria-expanded="false">
                                    Select Options
                                </button>
                                <ul class="dropdown-menu w-100 px-2" id="roleDropdownMenu">
                                    <!-- Select All / Deselect All option - initially hidden -->
                                    <div class="d-flex align-items-center w-100 mt-1 mb-2 border-bottom pb-2 d-none" id="selectAllContainer">
                                        <!-- <input type="checkbox" class="me-2" id="selectAllCheckboxes" checked> -->
                                        <!-- <label class="my-auto fw-bold" for="selectAllCheckboxes">Select All</label> -->
                                    </div>
                                    @foreach ($qc_checklists as $qc_checklist)
                                        <div
                                            class="d-flex align-items-center w-100 mt-1 comm_checklist_div checklist_div_{{ $qc_checklist->qc_id }}">
                                            <input type="checkbox" class="me-2 checklist_checkbox" name="checklist[]"
                                                value="{{ $qc_checklist->id }}">
                                            <label class="my-auto">{{ $qc_checklist->question }}</label>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
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
                        <input type="date" class="form-control" name="due_date" min="{{ date('Y-m-d') }}"
                            max="9999-12-31" id="due_date" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qcfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="qcfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="qctext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" name="file_attachment" id="qcfile"
                                onchange="updateFileName('qcfile', 'qctext')">
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

<!-- View QC -->
<div class="modal fade" id="viewqc" tabindex="-1" aria-labelledby="viewqcLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('.comm_checklist_div').removeClass('d-flex');
        $('.comm_checklist_div').addClass('d-none');
        $('#selectAllContainer').addClass('d-none'); // Hide select all initially
        
        // Handle QC Title change
        $(document).on("change", "#qc_title", function() {
            var selectedQcId = $(this).val();
            
            // Hide all checklist divs and uncheck all checkboxes
            $('.comm_checklist_div').removeClass('d-flex');
            $('.comm_checklist_div').addClass('d-none');
            $('.checklist_checkbox').prop('checked', false);
            
            if (selectedQcId) {
                // Show checkboxes for selected QC only
                $('.checklist_div_' + selectedQcId).addClass('d-flex');
                $('.checklist_div_' + selectedQcId).removeClass('d-none');
                
                // Show select all container
                $('#selectAllContainer').removeClass('d-none');
                $('#selectAllContainer').addClass('d-flex');
                
                // Set only visible checkboxes (for selected QC) to checked by default
                $('.checklist_div_' + selectedQcId + ' .checklist_checkbox').prop('checked', true);
                
                // Set select all checkbox to checked
                $('#selectAllCheckboxes').prop('checked', true);
                
                // Update select all state
                updateSelectAllState(selectedQcId);
            } else {
                // Hide select all container if no QC selected
                $('#selectAllContainer').removeClass('d-flex');
                $('#selectAllContainer').addClass('d-none');
            }
        });

        $(document).on("change", "#edit_qc_title_qc", function() {
            $('.edit_comm_checklist_div').removeClass('d-flex');
            $('.edit_comm_checklist_div').addClass('d-none');
            $('.edit_checklist_div_' + $(this).val()).addClass('d-flex');
            $('.edit_checklist_div_' + $(this).val()).removeClass('d-none');
            $('.edit_checklist_checkbox').prop('checked', false);
        });
        
        // Handle Select All checkbox - only affects visible checkboxes
        $(document).on("change", "#selectAllCheckboxes", function() {
            var isChecked = $(this).is(':checked');
            var currentQcId = $('#qc_title').val();
            
            if (currentQcId) {
                // Only check/uncheck checkboxes that are visible for current QC
                $('.checklist_div_' + currentQcId + ':visible .checklist_checkbox').prop('checked', isChecked);
            }
        });
        
        // Handle individual checkbox changes - only for visible checkboxes
        $(document).on("change", ".checklist_checkbox", function() {
            var currentQcId = $('#qc_title').val();
            if (currentQcId) {
                updateSelectAllState(currentQcId);
            }
        });
        
        // Function to update Select All checkbox state based on visible checkboxes only
        function updateSelectAllState(qcId) {
            var totalVisibleCheckboxes = $('.checklist_div_' + qcId + ':visible .checklist_checkbox').length;
            var checkedVisibleCheckboxes = $('.checklist_div_' + qcId + ':visible .checklist_checkbox:checked').length;
            
            if (checkedVisibleCheckboxes === totalVisibleCheckboxes && totalVisibleCheckboxes > 0) {
                $('#selectAllCheckboxes').prop('checked', true).prop('indeterminate', false);
            } else if (checkedVisibleCheckboxes === 0) {
                $('#selectAllCheckboxes').prop('checked', false).prop('indeterminate', false);
            } else {
                $('#selectAllCheckboxes').prop('checked', false).prop('indeterminate', true);
            }
        }
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on("click", ".edit_button_qc", function() {
            $('#edit_id_qc').val($(this).attr('data_id'));
            $('#edit_qc_title_qc').val($(this).attr('data_qc_title')).trigger('change');
            $('#edit_assigned_to_qc').val($(this).attr('data_assigned_to'));
            $('#edit_due_date_qc').val($(this).attr('data_due_date'));
            $('#edit_qctext').text($(this).attr('data_file_name'));
            var checklist_array = $(this).attr('data_checklist'); // Get the checklist data
            if (checklist_array.length) {
                var checklist_values = checklist_array.split(','); // Convert string to an array
                checklist_values.forEach(function(value) {
                    $('.edit_checklist_checkbox[value="' + value + '"]').prop('checked', true);
                });
            }
        });
    });
</script>

<script>
    $('.view1').on('click', function() {
        var qc_ans = $(this).data('qc-ans');
        var type = $(this).data('type');
        $.ajax({
            url: "{{ route('ans.ajax') }}",
            method: 'POST', // Method should be a string,
            data: {
                ajax_id: qc_ans,
                type: type,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                $('#viewqc .modal-body').html(res.data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // Handle errors gracefully
            }
        });
    });
</script>