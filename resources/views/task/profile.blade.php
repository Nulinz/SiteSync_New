@extends ('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

    <style>
        .mainbdy {
            margin-top: 10px;
            display: block;
        }

        .comm {
            font-size: 0.70rem;
            font-weight: 500;
        }

        .comm-des {
            font-size: 0.80rem;
            color: #635c5c;
            font-weight: 400;
        }
    </style>

    <div class="body-div px-4 py-1 mb-4">
        @php
            $last = $taskDetails->first(); // If $tasks is a Laravel Collection
        @endphp

        <div class="body-head mb-3">
            <h4 class="m-0">Task View</h4>
            <form action="{{ route('comment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="task_id" value="{{ $last->id }}">
                <div class="d-flex align-items-center justify-content-end">
                    <div>
                        {{-- <label for="">Comment</label> --}}
                        <input type="text" class="form-control" placeholder="Comments..." name="desp">
                    </div>

                    <button type="submit" class="btn btn-outline-primary"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
        <div class="mainbdy">
            <div class="p-3 rounded-2 form-div">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-xl-12 left-content border-0">
                        <div class="container ps-0 pe-2" id="timelinecards">
                            <div class="timeline">

                                @foreach ($taskDetails as $taskDetail)
                                    <div class="entry">
                                        <div class="title">
                                            <h3>{{ $taskDetail->created_user->name }}</h3>
                                            {{-- <h6>{{ $taskDetail['created_time'] }}</h6> --}}
                                        </div>
                                        <div class="entrybody">
                                            <div class="taskname mb-2">
                                                <div class="tasknameleft">
                                                    <i class="fa-solid fa-circle text-warning"></i>
                                                    <h6 class="mb-0">{{ $taskDetail->title }}</h6>
                                                </div>
                                               @if ($taskDetail['file_attachment'])
                                                <div class="tasknamefile">
                                                    <h6 class="mb-0">
                                                        <a href="{{ Storage::disk('s3')->url($taskDetail->file_attachment) }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="View Attachment"
                                                        target="_blank">
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </a>

                                                        <a href="{{ Storage::disk('s3')->url($taskDetail->file_attachment) }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="Download Attachment"
                                                        target="_blank"
                                                        download>
                                                            <i class="fa-solid fa-download"></i>
                                                        </a>
                                                    </h6>
                                                </div>
                                              @endif
                                            </div>
                                            <div class="taskdescp mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <div class="tasknameleft">
                                                        <h6 class="mb-0">{{ $taskDetail->description }}</h6>
                                                        <h5 class="mb-0">{{ $taskDetail->user->name }}</h5>
                                                    </div>

                                                    {{-- <a class="btn btn-sm text-dark p-0 mb-0" data-bs-toggle="modal"
                                                        data-bs-target="#completedModal" data-bs-title="Comment">
                                                        <i class="fas fa-comment"></i></a>
                                                    </a> --}}
                                                </div>
                                                <div class="taskdate mt-2">
                                                    <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                        {{ date('d-m-Y', strtotime($taskDetail->end_timestamp)) }}
                                                    </h6>
                                                    <!-- <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                        {{ date('H:i a', strtotime($taskDetail->end_timestamp)) }}
                                                    </h6> -->
                                                </div>
                                            </div>
                                        </div>


                                        @foreach ($taskDetail->comments as $comment)
                                            <div class="entrybody">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="">
                                                        <i class="fas fa-comment"></i>
                                                        <strong
                                                            class="comm">{{ $comment->created_user->name ?? 'Unknown' }}:</strong>
                                                        <span class="comm-des">{{ $comment->desp ?? 'No comment' }} </span>
                                                    </div>
                                                    <h6 class="comm mb-0"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                        {{ date('h:i a', strtotime($comment->created_at)) }}
                                                    </h6>
                                                </div>
                                            </div>
                                        @endforeach
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>


                <!-- Update Assign Modal -->
                <div class="modal fade" id="completedModal" tabindex="-1" aria-labelledby="completedModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title fs-5" id="completedModalLabel">Add Comment</h4>
                                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="" class="row" id="assign_task"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="col-sm-12 col-md-12 mt-3 mb-3">
                                        {{-- <label for="title" class="col-form-label">Add Comment</label> --}}
                                        <textarea name="" class="form-control" id="" rows="2" placeholder="Enter your comment"></textarea>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-center mx-auto">
                                        <button type="submit" class="modalbtn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
