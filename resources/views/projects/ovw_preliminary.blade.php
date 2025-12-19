<div class="accordion" id="stages01">
    <!-- Body Timeline -->
    <div class="ovw-right-head">
        <h5 class="m-0 text-uppercase">{{ $stage_name }}</h5>
        <div class="progress-container justify-content-start justify-content-md-center mb-2">
            {{-- <div class="linear-progress">
                <div class="progress-bar" id="progress-bar-5"></div>
            </div> --}}
            {{-- <div class="progress-value" id="progress-value-5"></div> --}}
        </div>
    </div>

    <div class="container ps-0 pe-2 timelinecards">
        <div class="timeline">
            <div class="timeline">
                @php
                    // $a = [1, 2, 3, 4];
                    // dd($stages->toArray());
                @endphp


                @foreach ($stages as $stageIndex => $stage)
                    <div class="entry completed accordion-item">
                        <div class="title"></div>
                        <div class="entrybody accordion-button d-block collapsed" data-bs-toggle="collapse"
                            data-bs-target="#preCollapse{{ $stage->id }}">
                            <div class="taskct">
                                <div class="taskname">
                                    <h4 class="mb-1">{{ $stage->activity }}</h4>
                                    {{-- <h6 class="mb-1">{{ $sub->next_day }}</h6> --}}
                                </div>
                                <div class="taskimg">
                                    @php
                                        // dd($stage->act_work->first());
                                        // $file = json_decode($sub->file, true); // ✅ correct
                                    @endphp

                                    {{-- @if (is_array($sub->file))
                                            @foreach ($sub->file as $f)
                                                <img src="{{ asset('img/activity_work/' . $f) }}" alt=""
                                                    height="80px" class="me-2 mb-2">
                                            @endforeach
                                        @else
                                            <p>No files available.</p>
                                        @endif --}}

                                </div>
                            </div>
                            <div class="taskdate">
                                {{-- <h6 class="m-0 startdate"><i class="fa-regular fa-calendar"></i>&nbsp;
                                    {{ date('d-m-Y', strtotime($stage->sc_start)) }}</h6>
                                <h6 class="m-0 enddate"><i class="fas fa-flag"></i>&nbsp;
                                    {{ date('d-m-Y', strtotime($stage->sc_end)) }}
                                </h6> --}}
                            </div>
                        </div>


                        <div id="preCollapse{{ $stage->id }}" class="accordion-collapse collapse"
                            data-bs-parent="#stages01">
                            <div class="accordion-body py-0 px-2">
                                @foreach ($stage->act_work as $workIndex => $item)
                                    <script>
                                        function updateProgress_new(progressBarId, progressValueId, targetPercentage) {
                                            let progressBar = document.querySelector(`#${progressBarId}`);
                                            let progressValue = document.querySelector(`#${progressValueId}`);

                                            if (!progressBar || !progressValue) {
                                                console.warn(`Missing element: ${progressBarId} or ${progressValueId}`);
                                                return;
                                            }

                                            let progressStartValue = 0;
                                            let speed = 20;

                                            let interval = setInterval(function() {
                                                if (progressStartValue <= targetPercentage) {
                                                    progressValue.textContent = `${progressStartValue}%`;
                                                    progressBar.style.width = `${progressStartValue}%`;
                                                    progressStartValue++;
                                                } else {
                                                    clearInterval(interval);
                                                }
                                            }, speed);
                                        }

                                        updateProgress_new(
                                            "progress-bar-{{ $stageIndex }}-{{ $workIndex }}",
                                            "progress-value-{{ $stageIndex }}-{{ $workIndex }}",
                                            {{ round($item->progress ?? 0) }}
                                        );
                                    </script>
                                    @php
                                        $progressBarId = 'progress-bar-' . $stageIndex . '-' . $workIndex;
                                        $progressValueId = 'progress-value-' . $stageIndex . '-' . $workIndex;
                                    @endphp

                                    <div class="updatecard">
                                        <h4> {{ date('d-m-Y', strtotime($item->created_at)) }}</h4>
                                        <div class="updatecarddiv">
                                            <div class="updatecardleft">
                                                <div class="prgshead">
                                                    {{-- <h5>Labors: {{ $item->labour }}</h5> --}}
                                                    <div class="inprogressbtn prgsbtn">
                                                        <h6>{{ $item->status == 'save' ? 'In progress' : $item->status }}
                                                        </h6>
                                                    </div>
                                                </div>

                                                <div class="progress-container w-100 justify-content-start mb-3">
                                                    <div class="linear-progress">
                                                        <div class="progress-bar" id="{{ $progressBarId }}"></div>
                                                    </div>
                                                    <div class="progress-value" id="{{ $progressValueId }}"></div>


                                                </div>
                                                <div class="prgsbottom">
                                                    <h6>{{ $item->next_day }}</h6>
                                                    <h6>{{ $item->remarks }}</h6>
                                                </div>
                                            </div>
                                            <div class="updatecardright">
                                                @php
                                                    // dd($stage->act_work->first());
                                                    // $file = json_decode($item->file, true); // ✅ correct
                                                @endphp

                                                <div class="prgsimg mb-3">
                                                    @if (is_array($item->file))
                                                        @foreach ($item->file as $f)
                                                            <img src="{{ asset('img/activity_work/' . $f) }}"
                                                                alt="" height="80px" class="me-2 mb-2">
                                                        @endforeach
                                                    @else
                                                        <p>No files available.</p>
                                                    @endif
                                                </div>
                                                <div class="updation">
                                                    <h5>Updated By</h5>
                                                    <div class="updationname d-flex align-items-center gap-2">
                                                        <img src="{{ asset('img/employees/' . $item->cr_image) ?? null }}"
                                                            height="30px" class="rounded-5" alt="">
                                                        <h6>{{ $item->cr_name }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>
    </div>


</div>

<script>
    // document.addEventListener("DOMContentLoaded", function() {
    //     alert('hello');
    //     console.log('DOM Ready'); // ← ✅ Must run if DOM is fully loaded
    //     @foreach ($stages as $stageIndex => $stage)
    //         @foreach ($stage->act_work as $workIndex => $p_tab)
    //             updateProgress_new(
    //                 "progress-bar-{{ $stageIndex }}-{{ $workIndex }}",
    //                 "progress-value-{{ $stageIndex }}-{{ $workIndex }}",
    //                 {{ round($p_tab->progress ?? 0) }}
    //             );
    //             console.log(
    //                 "Progress for Stage {{ $stageIndex }}, Work {{ $workIndex }}: {{ round($p_tab->progress ?? 0) }}"
    //             );
    //         @endforeach
    //     @endforeach
    // });
</script>
