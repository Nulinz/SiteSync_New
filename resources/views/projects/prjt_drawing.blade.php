<div class="empdetails">
    <div class="listtable">
        <!-- <div class="profilelisthead row d-flex justify-content-end mb-3">
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" class="form-control filterInput" placeholder=" Search">
                <button class="rejected_table_btn profilelistbtn"><i class="fas fa-clock-rotate-left"></i></button>
            </div>
        </div> -->
        @php
            $distinctFileTypes = collect($drawing)->groupBy('file_type');
            // ->map(function ($item) {
            //     return [
            //         'id' => $item['id'],
            //         'file_type' => $item['file_type']
            //     ];
            // })->groupBy('file_type');
            $drawingsCollection = collect($ent_drawings);

            // dd($drawingsCollection);

            //  dd($distinctFileTypes);

        @endphp
        <div class="accordion" id="accordion01">
            @foreach ($distinctFileTypes as $file_type => $t_file)
                @php
                    $safeId = Str::slug($file_type); // Make the file type safe for HTML IDs
                @endphp
                <div class="accordion-item mb-2">
                    <h2 class="accordion-header" id="heading_{{ $safeId }}">
                        <button class="accordion-button border-0 collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_{{ $safeId }}" aria-expanded="false"
                            aria-controls="collapse_{{ $safeId }}">
                            {{ $file_type }}
                        </button>
                        @can('add-drawing')
                            <button data-data_type="{{ $file_type }}" class="listbtn px-3 pop_up" data-bs-toggle="modal"
                                data-bs-target="#addDrawing"><i class="fas fa-upload pe-2"></i> Add Drawing
                            </button>
                        @endcan
                    </h2>
                    <div id="collapse_{{ $safeId }}" class="accordion-collapse collapse"
                        data-bs-parent="#accordion01">
                        <div class="accordion-body py-0 px-2">
                            <div class="table-wrapper">
                                <table class="example table">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Version</th>
                                            <th>Uploaded By</th>
                                            <th>Uploaded On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($t_file as $sub)
                                            @php
                                                $dr = $drawingsCollection->where('id', $sub->id)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $sub['title'] }}</td>
                                                <td>{{ $dr['version'] }}</td>
                                                <td>{{ $dr['uploaded_by'] }}</td>
                                                <td>{{ $dr['uploaded_on'] ? date('d-m-Y h:i a', strtotime($dr['uploaded_on'])) : '-' }}
                                                </td>
                                                <td><span class="text-success">{{ $dr['status'] }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        {{-- @can('view-drawing') --}}
                                                        <a href="{{ Storage::disk('s3')->url('draw/' . $dr['file_attachment']) }}" target="_blank">
                                                            <i class="fas fa-cloud-arrow-down"></i>
                                                        </a>
                                                        {{-- @endcan --}}
                                                        <a href="{{ route('project.history', ['projectid' => $project_id, 'ent_id' => $sub->id]) }}"
                                                            data-bs-toggle="tooltip" data-bs-title="History"
                                                            role="tab"><i class="fas fa-clock-rotate-left"></i></a>
                                                        @if ($dr['status'] == 'pending')
                                                            @can('approve-drawing')
                                                                <a data-bs-toggle="modal" data-bs-target="#stsModal"><i
                                                                        class="fas fa-ellipsis-vertical ps-1 sts_modal"
                                                                        data-ent-id="{{ $dr['entry_drawing_id'] }}"
                                                                        aria-expanded="false"></i></a>
                                                            @endcan
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
                </div>
            @endforeach
        </div>

    </div>
</div>

<!-- Add Drawing Modal -->
<div class="modal fade" id="addDrawing" tabindex="-1" aria-labelledby="addDrawingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-compass-drafting"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="adddrawingLabel">Add Drawing</h4>
                <form method="post" action="{{ route('project.drawing_store') }}" enctype="multipart/form-data"
                    id="draw_add">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project_id }}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="filetype" class="col-form-label">File Type</label>
                        <input type="text" class="form-control" id="filetype" name="filetype" readonly>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="title" class="col-form-label">Title</label>
                        <select class="form-select" name="drawing_id" id="draw_title">
                            <option value="" selected disabled>Select Options</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="version" class="col-form-label">Version</label>
                        <input type="text" class="form-control" id="version" name="version" readonly>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="add_drawingfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="add_drawingfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="add_drawingtext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="add_drawingfile" name="file_attachment"
                                onchange="updateFileName('add_drawingfile', 'add_drawingtext')">
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

<div class="modal fade" id="stsModal" tabindex="-1" aria-labelledby="stsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content w-75 mx-auto">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-compass-drafting"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-3 fs-5" id="stsModalLabel">Update Status</h4>
                {{-- <form method="POST" id="">
                    @csrf --}}
                <input type="hidden" name="entry_drawing_id" id="entry_drawing_id">

                <div class="col-sm-12 col-md-12 mb-2">
                    <button class="formbtn w-100 d-block mx-auto approve" value="Approved">Approve</a>
                </div>
                <div class="col-sm-12 col-md-12 mb-2">
                    <button class="formbtn w-100 d-block mx-auto approve" value="Rejected">Reject</button>
                </div>
                {{-- <div class="col-sm-12 col-md-12 mb-2">
                        <button type="submit" class="formbtn w-100 d-block mx-auto" name="action"
                            value="Delete">Delete</button>
                    </div> --}}
                {{-- </form> --}}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var table = $(".example").DataTable({
            paging: false,
            searching: true,
            ordering: false,
            bDestroy: true,
            info: false,
            responsive: true,
            pageLength: 10,
            dom: '<"top"f>rt<"bottom"lp><"clear">',
        });
    });
</script>

<script>
    $('.sts_modal').on('click', function() {

        var entry_drawing_id = $(this).data('ent-id');

        // console.log(entry_drawing_id);
        $('#entry_drawing_id').val(entry_drawing_id);
    });


    var project_id = '{{ $project_id }}';
    $('.pop_up').on('click', function() {

        $('#version').val('');

        var name_id = $(this).data('data_type');
        $('#filetype').val(name_id);




        // Now, make the AJAX request (if needed)
        $.ajax({
            url: "{{ route('file_type') }}", // The endpoint you want to request
            method: 'POST', // Or 'POST' depending on what you need
            data: {
                file_type: name_id, // Send the file_type to the server
                project_id: project_id,
                _token: '{{ csrf_token() }}', // CSRF token for security
            },
            success: function(response) {
                console.log(response);
                $('#draw_title').empty();
                $('#draw_title').append('<option>Select Title</option>');

                // Add new options to the select element
                Object.entries(response.option).forEach(([id, title]) => {
                    const isPending = response.status[id] === 'pending';
                    const option = new Option(title, id);
                    if (isPending) {
                        option.disabled = true;
                    }
                    $('#draw_title').append(option);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX error: ' + status + ' - ' + error);
            }
        });
    });
    // changes for version......
    $('#draw_title').on('change', function() {
        var file_title = $(this).find(':selected').val();
        $.ajax({
            url: "{{ route('file_version') }}", // The endpoint you want to request
            method: 'POST', // Or 'POST' depending on what you need
            data: {
                file_title: file_title, // Send the file_type to the server
                project_id: project_id,
                _token: '{{ csrf_token() }}', // CSRF token for security
            },
            success: function(response) {
                $('#version').val(response.version);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error: ' + status + ' - ' + error);
            }
        });
    });

    $('.approve').on('click', function() {
        var entry_drawing_id = $('#entry_drawing_id').val();

        // alert(entry_drawing_id);

        var status = $(this).val();

        // Construct the full URL manually
        // var url = `/project-drawing-status-update?id=${entry_drawing_id}&status=${status}`;

        $.ajax({
            url: "{{ route('project.drawing_status_update') }}", // The endpoint you want to request
            method: 'POST', // Or 'POST' depending on what you need
            data: {
                id: entry_drawing_id, // Send the file_type to the server
                status: status,
                _token: '{{ csrf_token() }}', // CSRF token for security
            },
            success: function(response) {
                // window.location.href = response.url;
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error: ' + status + ' - ' + error);
            }
        });

        // Redirect

    });
</script>
