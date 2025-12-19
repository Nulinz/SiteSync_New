<div class="empdetails">
    <div class="mt-3 listtable">
        <div class="profilelisthead row mb-3">
            <div class="aprvrjt col-sm-12 col-md-6" style="visibility: hidden;">
                <button type="button" data_type="approved" class="formbtn action_btn"><i class="fas fa-check pe-2"></i>
                    Approve</button>
                <button type="button" data_type="rejected" class="formbtn action_btn"><i class="fas fa-xmark pe-2"></i>
                    Reject</button>
            </div>
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput2" class="form-control" placeholder=" Search">
                <!-- <a data-bs-toggle="modal" data-bs-target="#adddrawing"><button class="profilelistbtn"><i
                            class="fas fa-plus"></i></button></a> -->
                <button class="rejected_table_btn profilelistbtn"><i class="fa-solid fa-clock-rotate-left"></i></button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="drawingTable">
                <thead>
                    <tr>
                        <th style="width: 200px">Type</th>
                        <th style="width: 300px">Title</th>
                        <th style="width: 250px">Version</th>
                        <th style="width: 250px">Uploaded By</th>
                        <th style="width: 200px">Uploaded On</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ent_drawings as $ent_drawing)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($ent_drawing['entry_drawing_id'])
                                                        <input type="checkbox" data_id="{{$ent_drawing['entry_drawing_id']}}"
                                                            class="checkbox drawing_checkbox">
                                                    @else
                                                        <input type="checkbox" disabled>
                                                    @endif
                                                    {{$ent_drawing['file_type']}}
                                                </div>
                                            </td>
                                            <td>{{$ent_drawing['title']}}</td>
                                            <td>{{$ent_drawing['version'] ?? '-'}}</td>
                                            <td>{{$ent_drawing['uploaded_by'] ?? '-'}}</td>
                                            <td>{{ ($ent_drawing['uploaded_on']) ? date('d M Y, h:i A', strtotime($ent_drawing['uploaded_on'])) : '-'}}
                                            </td>
                                            @php
                                                $statusClass = "";
                                                if ($ent_drawing['status'] == 'approved') {
                                                    $statusClass = 'text-success';
                                                } elseif ($ent_drawing['status'] == 'rejected') {
                                                    $statusClass = 'text-danger';
                                                } elseif ($ent_drawing['status'] == 'pending') {
                                                    $statusClass = 'text-warning';
                                                }
                                            @endphp
                                            <td><span class="{{$statusClass}}">{{ucwords($ent_drawing['status'])}}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <a class="edit_button_drawing" data_drawing_id="{{$ent_drawing['id']}}"
                                                        data-bs-toggle="modal" data-bs-target="#adddrawing"><i
                                                            class="fa-solid fa-upload"></i></a>

                                                    @if($ent_drawing['entry_drawing_id'])
                                                        <a href="{{ url('/download_file', urlencode($ent_drawing['file_attachment'])) }}"><i
                                                                class="fa-solid fa-cloud-arrow-down"></i></a>
                                                        <a class="rowaddbtn" data_title="{{$ent_drawing['title']}}"
                                                            data_type="{{$ent_drawing['file_type']}}" data_id="{{$ent_drawing['id']}}"><i
                                                                class="fa-solid fa-circle-plus text-success"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table" id="rejectTable" style="display: none;">
                <thead>
                    <tr>
                        <th style="width: 200px">Type</th>
                        <th style="width: 300px">Title</th>
                        <th style="width: 250px">Version</th>
                        <th style="width: 250px">Uploaded By</th>
                        <th style="width: 200px">Uploaded On</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejected_ent_drawings as $ent_drawing)
                                        <tr>
                                            <td>{{$ent_drawing->file_type}}</td>
                                            <td>{{$ent_drawing->title}}</td>
                                            <td>{{$ent_drawing->version ?? '-'}}</td>
                                            <td>{{$ent_drawing->uploaded_by ?? '-'}}</td>
                                            <td>{{ ($ent_drawing->uploaded_on) ? date('d M Y, h:i A', strtotime($ent_drawing->uploaded_on)) : '-'}}
                                            </td>
                                            @php
                                                if ($ent_drawing->status == 'approved') {
                                                    $statusClass = 'text-success';
                                                } elseif ($ent_drawing->status == 'rejected') {
                                                    $statusClass = 'text-danger';
                                                } elseif ($ent_drawing->status == 'pending') {
                                                    $statusClass = 'text-warning';
                                                }
                                            @endphp
                                            <td><span class="{{$statusClass}}">{{ucwords($ent_drawing->status)}}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <!-- <a class="edit_button_drawing" data_drawing_id="{{$ent_drawing->id}}"
                                                                                                                                                                                                    data-bs-toggle="modal" data-bs-target="#adddrawing"><i
                                                                                                                                                                                                        class="fa-solid fa-upload"></i></a> -->
                                                    <a href=""><i class="fa-solid fa-cloud-arrow-down"></i></a>
                                                    <!-- <a class="rowaddbtn" data_title="{{$ent_drawing->title}}"
                                                                                                                                                                                                    data_type="{{$ent_drawing->file_type}}" data_id="{{$ent_drawing->id}}"><i
                                                                                                                                                                                                        class="fa-solid fa-circle-plus text-success"></i></a> -->
                                                </div>
                                            </td>
                                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Edit Drawing Modal -->
<div class="modal fade" id="adddrawing" tabindex="-1" aria-labelledby="adddrawingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fa-solid fa-compass-drafting"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="adddrawingLabel">Add Drawing</h4>
                <form method="post" action="{{ route('project.drawing_store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id_drawing" name="drawing_id" />
                    <input type="hidden" value="{{$project->id}}" name="project_id" />
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="version" class="col-form-label">Version</label>
                        <input type="text" class="form-control" name="version">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="edit_drawingfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="edit_drawingfile">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                    <path
                                        d="M144 480C64.5 480 0 415.5 0 336c0-62.8 40.2-116.2 96.2-135.9c-.1-2.7-.2-5.4-.2-8.1c0-88.4 71.6-160 160-160c59.3 0 111 32.2 138.7 80.2C409.9 102 428.3 96 448 96c53 0 96 43 96 96c0 12.2-2.3 23.8-6.4 34.6C596 238.4 640 290.1 640 352c0 70.7-57.3 128-128 128l-368 0zm79-217c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l39-39L296 392c0 13.3 10.7 24 24 24s24-10.7 24-24l0-134.1 39 39c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-80-80c-9.4-9.4-24.6-9.4-33.9 0l-80 80z" />
                                </svg>
                            </div>
                            <div class="text">
                                <span id="edit_drawingtext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="edit_drawingfile" name="file_attachment"
                                onchange="updateFileName('edit_drawingfile', 'edit_drawingtext')">
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

    $(document).ready(function () {
        $('.rejected_table_btn').click(function () {
            $('#drawingTable, #rejectTable').toggle();
        });
    });

    $('.action_btn').click(function () {
        let action = $(this).attr('data_type');
        let data_ids = [];
        $('.drawing_checkbox:checked').each(function () {
            data_ids.push($(this).attr('data_id'));
        });

        let postData = {
            _token: "{{ csrf_token() }}",
            data_ids: data_ids,
            action: action
        };

        $.ajax({
            url: "{{route('project.drawing_status_update')}}",
            type: 'POST',
            data: JSON.stringify(postData),
            contentType: 'application/json',
            success: function (response) {
                location.href = "";
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Error saving survey!');
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Select all rowaddbtn elements
        document.querySelectorAll(".rowaddbtn").forEach(button => {
            button.addEventListener("click", function () {
                // Get data attributes from the clicked button
                let dataTitle = this.getAttribute("data_title");
                let dataType = this.getAttribute("data_type");
                let dataId = this.getAttribute("data_id");

                // Get the table body
                const drawingTableBody = document.querySelector("#drawingTable tbody");

                // Create a new row
                let newRow = document.createElement("tr");

                newRow.innerHTML = `
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" class="checkbox" name="type_checkbox">
                        <label for="">${dataType}</label>
                    </div>
                </td>
                <td>${dataTitle}</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <a class="edit_button_drawing" data_drawing_id="${dataId}" data-bs-toggle="modal" data-bs-target="#adddrawing">
                            <i class="fa-solid fa-upload"></i>
                        </a>
                        <a href=""><i class="fa-solid fa-cloud-arrow-down"></i></a>
                        <a class="remove-row"><i class="fa-solid fa-circle-minus text-danger"></i></a>
                    </div>
                </td>
            `;

                // Append new row to the table
                drawingTableBody.appendChild(newRow);

                // Add event listener to remove button
                newRow.querySelector(".remove-row").addEventListener("click", function (e) {
                    e.preventDefault();
                    newRow.remove();
                });
            });
        });
    });

</script>

<script>
    const checkboxes = document.querySelectorAll('.checkbox');
    const approvalDiv = document.querySelector('.aprvrjt');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            if (anyChecked) {
                approvalDiv.style.visibility = 'visible';
            } else {
                approvalDiv.style.visibility = 'hidden';
            }
        });
    });


    $(document).on("click", ".edit_button_drawing", function () {
        $('#edit_id_drawing').val($(this).attr('data_drawing_id'));
    });
</script>