<div class="overview-tab mt-4">
    <div class="ovw-left nav nav-tabs row" role="tablist" id="myTab2">
        @php
            // $customOrder = ['PRELIMINARY WORKS', 'SUB-STRUCTURE WORKS', 'SUPER STRUCTURE WORKS', 'FINISHING WORKS'];

            // $sortedTabs = collect($pro_progress_tab)->sortBy(function ($item) use ($customOrder) {
            //     return array_search($item['stage'], $customOrder);
            // });

            // dd($pro_progress_tab);
        @endphp
        @foreach ($pro_progress_tab as $index => $p_tab)
            @php
                // $progressBarId = 'progress-bar-' . $index;
                // $progressValueId = 'progress-value-' . $index;
            @endphp
            <button class="progressbtn mb-2 " role="tab" data-bs-toggle="tab" type="button"
                data-bs-target="#{{ strtolower(str_replace(' ', '-', $p_tab->stage)) }}">
                <div class="btndiv">
                    <div class="btnleft">
                        <h5>{{ $p_tab->stage_name }}</h5>
                        <br>
                        <h6>No. Of Activity - {{ $p_tab->sub_count }}</h6>

                    </div>
                    <div class="btnright">
                        <div class="progress-container mb-2">
                            <h6 class="inprogressbtn prgsbtn">{{ $p_tab->status_2 }}
                            </h6>

                            {{-- <div class="linear-progress">
                                <div class="progress-bar" id="{{ $progressBarId }}"></div>
                            </div>
                            <div class="progress-value" id="{{ $progressValueId }}"></div> --}}
                        </div>
                        <br>
                        <h6>{{ \Carbon\Carbon::parse($p_tab->sc_start)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($p_tab->sc_end)->format('d-m-Y') }}
                        </h6>
                    </div>
                </div>
            </button>
        @endforeach


    </div>
    <div class="brdr"></div>
    <div class="ovw-right tab-content" id="myTab2Content">
        @foreach ($pro_progress_tab as $stage_id)
            <div class="tab-pane fade" id="{{ $stage_id->stage }}" role="tabpanel">
                {{-- @include('projects.ovw_preliminary'); --}}
            </div>
        @endforeach
        <?php /*
            <div class="tab-pane fade" id="2" role="tabpanel">
                {{-- @include('projects.ovw_preliminary'); --}}
            </div>
            <div class="tab-pane fade" id="3" role="tabpanel">  
                {{-- @include('projects.ovw_preliminary'); --}}
            </div>
            <div class="tab-pane fade" id="4" role="tabpanel">
                {{-- @include('projects.ovw_preliminary'); --}}
            </div>
            */
        ?>

    </div>
</div>

{{-- // load the tabs --}}
<script>
    $(document).ready(function() {
        let pro_id = {{ $project_id }};

        $('#myTab2 button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            let targetId = $(e.target).data('bs-target'); // e.g. #preliminary-works
            let tabPane = $(targetId);

            // console.log(tabPane);

            // Prevent reloading
            if (tabPane.data('loaded')) return;

            let key = targetId.replace('#', '');

            // alert(targetId);


            // if (key == "preliminary-works") {
            //     var stage = "PRELIMINARY WORKS";
            // } else if (key == "sub-structure-works") {
            //     var stage = "SUB-STRUCTURE WORKS";
            // } else if (key == "super-structure-works") {
            //     var stage = "SUPER STRUCTURE WORKS";
            // } else {
            //     var stage = "FINISHING WORKS";
            // }

            $.ajax({
                url: '{{ route('projects.ovw_preliminary') }}', // ONE common route
                type: 'POST',
                data: {
                    key: key,
                    pro_id: pro_id,
                    _token: "{{ csrf_token() }}"
                }, // Send key to backend
                success: function(response) {
                    // console.log(response);
                    tabPane.html(response);
                    // tabPane.data('loaded', true); // Mark as loaded
                },
                error: function() {
                    tabPane.html('<p>Error loading content.</p>');
                }
            });
        });

        // Optional: Load first tab initially
        // $('button[data-bs-toggle="tab"]').first().trigger('shown.bs.tab');
    });
</script>


<script></script>



<script>
    // Progress Bar
    // function updateProgress(progressBarId, progressValueId, targetPercentage) {
    //     let progressBar = document.querySelector(`#${progressBarId}`);
    //     let progressValue = document.querySelector(`#${progressValueId}`);
    //     let progressStartValue = 0;
    //     let speed = 50;

    //     function update() {
    //         progressValue.textContent = `${progressStartValue}%`;
    //         progressBar.style.width = `${progressStartValue}%`;
    //         if (progressStartValue < targetPercentage) {
    //             progressStartValue++;
    //         }
    //     }
    //     update();
    //     setInterval(update, speed);

    // }
    // updateProgress("progress-bar-1", "progress-value-1", 90);
    // updateProgress("progress-bar-2", "progress-value-2", 75);
    // updateProgress("progress-bar-3", "progress-value-3", 50);
    // updateProgress("progress-bar-4", "progress-value-4", 25);

    // updateProgress("progress-bar-5", "progress-value-5", 90);
    // updateProgress("progress-bar-6", "progress-value-6", 75);
    // updateProgress("progress-bar-7", "progress-value-7", 50);
    // updateProgress("progress-bar-8", "progress-value-8", 25);

    // updateProgress("progress-bar-9", "progress-value-9", 90);
    // updateProgress("progress-bar-10", "progress-value-10", 100);
</script>
