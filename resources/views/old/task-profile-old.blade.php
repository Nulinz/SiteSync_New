@extends ('layouts.app')

@section('content')


    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tasktimeline.css') }}">

    <style>
        .mainbdy {
            margin-top: 10px;
            display: block;
        }
    </style>

    <div class="body-div px-4 py-1 mb-4">

        <div class="body-head mb-3">
            <h4 class="m-0">Task View</h4>
        </div>
        <div class="mainbdy">
            <div class="p-3 rounded-2 form-div">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-xl-12 left-content border-0">
                        <div class="container ps-0 pe-2" id="timelinecards">
                            <div class="timeline">

                                @foreach($taskDetails as $taskDetail)
                                    <div class="entry {{$taskDetail['status']}}">
                                        <div class="title">
                                            <h3>{{$taskDetail['created_user']}}</h3>
                                            <h6>{{$taskDetail['created_time']}}</h6>
                                        </div>
                                        <div class="entrybody">
                                            <div class="taskname mb-2">
                                                <div class="tasknameleft">
                                                    @php
                                                        $priority = $taskDetail['priority'];
                                                        if ($priority == "Low") {
                                                            $color = "text-primary";
                                                        } else if ($priority == "Medium") {
                                                            $color = "text-warning";
                                                        } else if ($priority == "High") {
                                                            $color = "text-danger";
                                                        } else {
                                                            $color = "";
                                                        }
                                                    @endphp
                                                    <i class="fa-solid fa-circle {{$color}}"></i>
                                                    <h6 class="mb-0">{{$taskDetail['title']}}</h6>
                                                </div>
                                                @if($taskDetail['file_attachment'])
                                                    <div class="tasknamefile">
                                                        <h6 class="mb-0">
                                                            <a href="{{ $taskDetail['file_attachment'] }}" data-bs-toggle="tooltip"
                                                                data-bs-title="Attachment" download><i
                                                                    class="fa-solid fa-paperclip"></i></a>
                                                        </h6>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="taskcategory mb-2">
                                                <h6 class="mb-0"><span class="category">{{$taskDetail['category']}}</span> /
                                                    <span class="subcat">{{$taskDetail['sub_category']}}</span>
                                                </h6>
                                            </div>
                                            <div class="taskdescp mb-2">
                                                <h5 class="mb-0">{{$taskDetail['project']}}</h5>
                                            </div>
                                            <div class="taskdescp mb-2">
                                                <h6 class="mb-0">{{$taskDetail['description']}}</h6>
                                                <h5 class="mb-0">{{$taskDetail['user']}}</h5>
                                            </div>
                                            <div class="taskdate mb-2">
                                                <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                                                    {{$taskDetail['start_date']}}
                                                </h6>
                                                <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                                    {{$taskDetail['end_date']}}
                                                </h6>
                                            </div>
                                            <div class="taskdate">
                                                <h6 class="m-0 startdate"><i class="fas fa-hourglass-start"></i>&nbsp;
                                                    {{$taskDetail['start_time']}}
                                                </h6>
                                                <h6 class="m-0 enddate"><i class="fas fa-hourglass-end"></i>&nbsp;
                                                    {{$taskDetail['end_time']}}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection