<style>
    .file,
    .link {
        display: none;
    }
</style>

<div class="empdetails">
    <div class="listtable">
        <div class="profilelisthead row d-flex justify-content-end">
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput9" class="form-control" placeholder=" Search">
                @can('doc_create')
                    <a data-bs-toggle="modal" data-bs-target="#addDocument" class="btn btn-sm btn-outline-dark"><i
                            class="fas fa-plus"></i> Document</a>
                @endcan

            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="documentTable">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pro_docs as $doc)
                        <tr>
                            <td>{{ $doc->type }}</td>
                            <td>{{ $doc->title }}</td>
                            <td>{{ $doc->desp }}.</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <a
                                    <a href="{{ $doc->type == 'Document' 
                                            ? Storage::disk('s3')->url('pro_docs/' . $doc->link) 
                                            : $doc->link }}" 
                                    target="_blank">
                                        <i class="fas fa-{{ $doc->type == 'Document' ? 'cloud-arrow-down' : 'link' }}"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td>Link</td>
                        <td>Work Update Link</td>
                        <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt, magnam.</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href=""><i class="fas fa-link"></i></a>
                            </div>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Documents -->
<div class="modal fade" id="addDocument" tabindex="-1" aria-labelledby="addDocumentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-file"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="">Add Document</h4>
                <form method="POST" action="{{ route('add.pro_docs') }}" enctype="multipart/form-data" id="docs_add">
                    <input hidden type="text" class="form-control" name="project_id" value="{{ $project_id }}"
                        required>
                    @csrf
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="dl_type" class="col-form-label">Type</label>
                        <select class="form-select" name="doc_type" id="dl_type" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="Document">Document</option>
                            <option value="Link">Link</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="dl_title" class="col-form-label">Title</label>
                        <input type="text" class="form-control" name="doc_title" id="dl_title" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="dl_description" class="col-form-label">Description</label>
                        <textarea rows="2" class="form-control" name="doc_description" id="dl_description" required></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1 link">
                        <label for="dl_link" class="col-form-label">URL Link</label>
                        <input type="text" class="form-control" name="doc_link" id="dl_link" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1 file">
                        <label for="dl_file" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="dl_file">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="dl_text" class="text-center">Upload File</span>
                            </div>
                            <input type="file" name="file_attachment_doc" id="dl_file"
                                onchange="updateFileName('dl_file', 'dl_text')" required>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        $("#dl_type").change(function() {
            var dltype = $("#dl_type").val();
            var link = $(".link").find('input, select');
            var file = $(".file").find('input, select');
            $('.link, .file').hide();
            link.prop('required', false);
            file.prop('required', false);
            if (dltype === 'Document') {
                $('.file').show();
                file.prop('required', true);
            } else if (dltype === 'Link') {
                $('.link').show();
                link.prop('required', true);
            }
        })
    })
</script>
