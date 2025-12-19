<div class="empdetails">
    <div class="listtable">
        <div class="profilelisthead row">
            <div class="profileleft col-sm-12 col-md-6">
                <button class="profilefilterbtn profilefilterbtn5 active"
                    onclick="filterList('all', 'snagTable', 'profilefilterbtn5', 'noDataRow5')">All</button>
<button class="profilefilterbtn profilefilterbtn5"  onclick="filterList('new', 'snagTable', 'profilefilterbtn5', 'noDataRow5')">New</button>
<button class="profilefilterbtn profilefilterbtn5" onclick="filterList('pending', 'snagTable', 'profilefilterbtn5', 'noDataRow5')">Pending</button>
<button class="profilefilterbtn profilefilterbtn5" onclick="filterList('completed', 'snagTable', 'profilefilterbtn5', 'noDataRow5')">Completed</button>
<button class="profilefilterbtn profilefilterbtn5" onclick="filterList('approved', 'snagTable', 'profilefilterbtn5', 'noDataRow5')">Approved</button>

            </div>
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput5" class="form-control" placeholder=" Search">
                @can('add-snag')
                    <a data-bs-toggle="modal" data-bs-target="#addsnag" class="btn btn-sm btn-outline-dark"><i
                            class="fas fa-plus"></i> Snag</a>
                @endcan
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="snagTable">
                <thead>
                    <tr>
                        <th>Snag Category</th>
                        <th>Description</th>
                        <th>Assigned User</th>
                        <th>Due Date</th>
                        <th>Approved By</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>
<tbody>
    @foreach ($ent_snags as $ent_snag)
        @php
            $currentDateTime = now();
            $endDateTime = \Carbon\Carbon::parse($ent_snag->due_date);

            if ($ent_snag->status == 'completed') {
                $statusClass = 'text-success';
                $statusName = 'Completed';
            } elseif ($ent_snag->status == 'approved') {
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
            <td>{{ $ent_snag->snag->category }}</td>
            <td>{{ $ent_snag->description }}</td>
            <td>{{ $ent_snag->user->name }}</td>
            <td>{{ date('d-m-Y', strtotime($ent_snag->due_date)) }}</td>
            <td>{{ $ent_snag->approved }}</td>
            <td>{{ $ent_snag->location }}</td>
            <td><span class="{{ $statusClass }}">{{ $statusName }}</span></td>
            <td>
                <button class="btn btn-sm btn-outline-primary comment-btn" 
                        data-snag-id="{{ $ent_snag->id }}" 
                        data-assigned-to="{{ $ent_snag->user->name }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#commentModal">
                    <i class="fas fa-comments"></i>
                    {{-- Uncomment if you want to show comment count --}}
                    {{-- @if($ent_snag->comment_count > 0)
                        <span class="badge bg-danger ms-1">{{ $ent_snag->comment_count }}</span>
                    @endif --}}
                </button>
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    @if ($ent_snag->file_attachment)
                       <a href="{{ Storage::disk('s3')->url('snag/' . $ent_snag->file_attachment) }}" target="_blank">
                            <i class="fas fa-cloud-arrow-down"></i>
                        </a>

                    @endif

                    @if ($ent_snag->status == 'completed')
                        @can('approve-snag')
                            <a onclick="return confirm('Are you sure to Approve?')"
                               href="{{ url('project-snag-status-update/' . $ent_snag->id . '/approved') }}">
                                <i class="fas fa-circle-check" data-bs-toggle="tooltip" data-bs-title="Approve"></i>
                            </a>
                        @endcan
                        <a data-bs-toggle="modal" data-bs-target="#viewsnag"
                           data-snag-ans="{{ $ent_snag->id }}" data-type='snag' class="view">
                           <i class="fas fa-eye"></i>
                        </a>
                    @elseif ($ent_snag->status == 'approved')
                        <i class="fas fa-circle-check text-success" data-bs-toggle="tooltip" data-bs-title="Approved"></i>
                        <a data-bs-toggle="modal" data-bs-target="#viewsnag"
                           data-snag-ans="{{ $ent_snag->id }}" data-type='snag' class="view">
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

<!-- Add Snag Modal -->
<div class="modal fade" id="addsnag" tabindex="-1" aria-labelledby="addsnagLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-file-circle-exclamation"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addsnagLabel">Add Snag</h4>
                <form method="post" action="{{ route('project.snag_store') }}" enctype="multipart/form-data"
                    id="snag_add">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="category_id" class="col-form-label">Category</label>
                        <select class="form-select" name="category_id" id="category_id">
                            <option value="" selected disabled>Select Options</option>
                            @foreach ($snags as $snag)
                                <option value="{{ $snag->id }}">{{ $snag->category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="description" class="col-form-label">Description</label>
                        <textarea rows="2" class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="assigned_to" class="col-form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to">
                            <option value="" selected disabled>Select Employee</option>
                            @foreach ($project_employees as $employee)
                                @if ($employee->id != auth()->user()->id)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="due_date" class="col-form-label">Due Date</label>
                        <input type="date" class="form-control" name="due_date" id="due_date" min="1000-01-01"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="due_date" class="col-form-label">Location</label>
                        <input type="text" class="form-control" name="location" id="">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="snagfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="snagfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="snagtext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" id="snagfile" name="file_attachment"
                                onchange="updateFileName('snagfile', 'snagtext')">
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

<!-- Comment Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-comments"></i></h5>
                </div>
                <h5 class="modal-title" id="commentModalLabel">Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Comments Display Area -->
                <div id="commentsContainer" style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
                    <div id="loadingComments" class="text-center">
                        <i class="fas fa-spinner fa-spin"></i> Loading comments...
                    </div>
                    <div id="commentsContent"></div>
                </div>
                
                <!-- Add Comment Form -->
                <div class="border-top pt-3">
                    <form id="addCommentForm">
                        <input type="hidden" id="snagId" name="snag_id">
                        <div class="mb-3">
                            <label for="commentText" class="form-label">Add Comment:</label>
                            <textarea class="form-control" id="commentText" name="comment" rows="3" 
                                      placeholder="Enter your comment here..." required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitComment">
                                <i class="fas fa-paper-plane"></i> Send Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Snag -->
<div class="modal fade" id="viewsnag" tabindex="-1" aria-labelledby="viewsnagLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-file-circle-exclamation"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" id="snag">
            </div>
        </div>
    </div>
</div>

<script src={{ asset('assets/js/form_script.js') }}></script>

<script>
    $(document).ready(function() {
        // Existing edit button script
        $(document).on("click", ".edit_button_snag", function() {
            $('#edit_id_snag').val($(this).attr('data_id'));
            $('#edit_category_id_snag').val($(this).attr('data_category_id'));
            $('#edit_description_snag').val($(this).attr('data_description'));
            $('#edit_assigned_to_snag').val($(this).attr('data_assigned_to'));
            $('#edit_due_date_snag').val($(this).attr('data_due_date'));
            $('#edit_snagtext').text($(this).attr('data_file_name'));
        });

        // Comment Modal Handler
        $('.comment-btn').on('click', function() {
            const snagId = $(this).data('snag-id');
            const assignedTo = $(this).data('assigned-to');
            
            $('#snagId').val(snagId);
            $('#commentModalLabel').text(`Comments for ${assignedTo}'s Snag`);
            
            // Load existing comments
            loadComments(snagId);
        });

        // Add Comment Form Submission
        $('#addCommentForm').on('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                snag_id: $('#snagId').val(),
                comment: $('#commentText').val(),
                _token: "{{ csrf_token() }}"
            };

            $('#submitComment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

            $.ajax({
                url: "{{ route('snag.comment.store') }}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#commentText').val('');
                        loadComments(formData.snag_id);
                        
                        // Update comment count in table
                        updateCommentCount(formData.snag_id);
                        
                        // Show success message
                        showMessage('Comment added successfully!', 'success');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error adding comment:', error);
                    showMessage('Error adding comment. Please try again.', 'error');
                },
                complete: function() {
                    $('#submitComment').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Comment');
                }
            });
        });

        // Load Comments Function
        function loadComments(snagId) {
            $('#loadingComments').show();
            $('#commentsContent').empty();

            $.ajax({
                url: `{{ url('snag-comments') }}/${snagId}`,
                method: 'GET',
                success: function(response) {
                    $('#loadingComments').hide();
                    
                    if (response.success && response.comments.length > 0) {
                        let commentsHtml = '';
                        
                        response.comments.forEach(function(comment) {
                            const commentDate = new Date(comment.created_at).toLocaleString();
                            const isCurrentUser = comment.user_id == {{ auth()->id() }};
                            const alignClass = isCurrentUser ? 'text-end' : 'text-start';
                            const bgClass = isCurrentUser ? 'bg-primary text-white' : 'bg-light';
                            
                            commentsHtml += `
                                <div class="mb-3 ${alignClass}">
                                    <div class="d-inline-block p-3 rounded ${bgClass}" style="max-width: 70%;">
                                        <div class="fw-bold mb-1">${comment.user.name}</div>
                                        <div class="mb-2">${comment.comment}</div>
                                        <small class="opacity-75">${commentDate}</small>
                                    </div>
                                </div>
                            `;
                        });
                        
                        $('#commentsContent').html(commentsHtml);
                    } else {
                        $('#commentsContent').html('<div class="text-center text-muted">No comments yet. Be the first to comment!</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#loadingComments').hide();
                    $('#commentsContent').html('<div class="text-center text-danger">Error loading comments.</div>');
                    console.error('Error loading comments:', error);
                }
            });
        }

        // Update Comment Count in Table
        function updateCommentCount(snagId) {
            $.ajax({
                url: `{{ url('snag-comments') }}/${snagId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        const commentBtn = $(`.comment-btn[data-snag-id="${snagId}"]`);
                        const existingBadge = commentBtn.find('.badge');
                        
                        if (response.comments.length > 0) {
                            if (existingBadge.length) {
                                existingBadge.text(response.comments.length);
                            } else {
                                commentBtn.append(`<span class="badge bg-danger ms-1">${response.comments.length}</span>`);
                            }
                        }
                    }
                }
            });
        }

        // Show Message Function
        function showMessage(message, type) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999;" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('body').append(alertHtml);
            
            // Auto remove after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        }
    });

    // Existing view script
    $('.view').on('click', function() {
        var snag_ans = $(this).data('snag-ans');
        var type = $(this).data('type');
        $.ajax({
            url: "{{ route('ans.ajax') }}",
            method: 'POST',
            data: {
                ajax_id: snag_ans,
                type: type,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                $('#viewsnag .modal-body').html(res.data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
</script>


<style>
    /* Professional Chat UI Styles */

/* Modal Customization */
#commentModal .modal-dialog {
    max-width: 600px;
}

#commentModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

#commentModal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.5rem;
    border-bottom: none;
}

#commentModal .modal-header .usericon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
}

#commentModal .modal-header .usericon i {
    font-size: 18px;
}

#commentModal .modal-title {
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
}

#commentModal .btn-close {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    opacity: 1;
    filter: brightness(0) invert(1);
    width: 30px;
    height: 30px;
}

/* Comments Container */
#commentsContainer {
    max-height: 350px;
    overflow-y: auto;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 1rem;
}

/* Custom Scrollbar */
#commentsContainer::-webkit-scrollbar {
    width: 6px;
}

#commentsContainer::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 3px;
}

#commentsContainer::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}

#commentsContainer::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

/* Comment Bubbles */
.comment-bubble {
    max-width: 75%;
    margin-bottom: 12px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.comment-bubble.current-user {
    margin-left: auto;
}

.comment-bubble.other-user {
    margin-right: auto;
}

.comment-content {
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
}

.comment-bubble.current-user .comment-content {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-bottom-right-radius: 6px;
}

.comment-bubble.other-user .comment-content {
    background: white;
    color: #333;
    border: 1px solid #e0e6ed;
    border-bottom-left-radius: 6px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.comment-meta {
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 4px;
    font-weight: 500;
}

.comment-author {
    font-weight: 600;
    font-size: 0.8rem;
    margin-bottom: 4px;
}

.comment-bubble.current-user .comment-author {
    color: rgba(255, 255, 255, 0.9);
}

.comment-bubble.other-user .comment-author {
    color: #667eea;
}

.comment-text {
    font-size: 0.9rem;
    line-height: 1.4;
    margin: 0;
}

/* Add Comment Section */
.add-comment-section {
    border-top: 1px solid #e0e6ed;
    padding-top: 1rem;
    background: white;
}

.add-comment-section .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.comment-input {
    border: 2px solid #e0e6ed;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    resize: vertical;
    min-height: 80px;
}

.comment-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
}

.comment-input::placeholder {
    color: #9ca3af;
}

/* Send Button */
.send-comment-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    padding: 10px 24px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.send-comment-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.send-comment-btn:active {
    transform: translateY(0);
}

.send-comment-btn:disabled {
    opacity: 0.7;
    transform: none;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Loading States */
.loading-comments {
    text-align: center;
    padding: 2rem;
    color: #667eea;
}

.loading-comments i {
    font-size: 1.5rem;
    margin-bottom: 8px;
}

.no-comments {
    text-align: center;
    padding: 2rem;
    color: #9ca3af;
    font-style: italic;
}

/* Success/Error Messages */
.alert {
    border-radius: 8px;
    font-size: 0.9rem;
    padding: 12px 16px;
}

.alert-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
}

.alert-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
}

/* Comment Button in Table */
.comment-btn {
    position: relative;
    border: 1px solid #667eea;
    color: #667eea;
    border-radius: 6px;
    padding: 6px 10px;
    transition: all 0.3s ease;
}

.comment-btn:hover {
    background: #667eea;
    color: white;
}

.comment-btn .badge {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ef4444;
    border: 2px solid white;
    border-radius: 50%;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    #commentModal .modal-dialog {
        margin: 10px;
        max-width: calc(100% - 20px);
    }
    
    .comment-bubble {
        max-width: 85%;
    }
    
    #commentsContainer {
        max-height: 250px;
        padding: 0.75rem;
    }
    
    .comment-content {
        padding: 10px 14px;
    }
    
    .comment-input {
        min-height: 70px;
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

/* Improved Typography */
.comment-text {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.5;
}

.comment-meta {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Subtle Animations */
.comment-bubble:hover .comment-content {
    transform: translateY(-1px);
    transition: transform 0.2s ease;
}

.comment-bubble.current-user .comment-content:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.comment-bubble.other-user .comment-content:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>