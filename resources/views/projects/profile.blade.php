@extends ('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/progress.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/accordion-btn.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/maintasktimeline.css') }}">

    <style>
        .table thead th {
            background-color: var(--theadbg) !important;
        }

        .profileleft {
            background-color: var(--theadbg);
            display: flex;
            /* grid-template-columns: repeat(4, 1fr); */
            /* grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); */
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            /* margin: 0px 5px; */
            border-radius: 5px;
            width: auto;
        }

        .profilefilterbtn {
            margin: 0px 5px;
        }

        @media screen and (min-width: 1098px) {

            .table td div input,
            .table td div select,
            .table td div textarea {
                padding: 8px 5px;
                font-size: 10px;
                border-radius: 5px;
            }

            .table td div .tableinput {
                width: 100px;
            }

            .table td div select,
            .table td div .catinput {
                width: 150px;
            }

            .table td div textarea {
                width: 150px;
            }
        }

        @media screen and (min-width: 767px) and (max-width: 1098px) {

            .table td div input,
            .table td div select,
            .table td div textarea {
                padding: 7px 4px;
                font-size: 8px;
                border-radius: 5px;
            }
        }

        @media screen and (max-width: 767px) {

            .table td div input,
            .table td div select,
            .table td div textarea {
                padding: 7px 4px;
                font-size: 8px;
                border-radius: 5px;
            }
        }
    </style>

    <div class="body-div px-4">
        <div class="body-head my-3">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('project.index') }}">My Projects /</a></h6>
                <h6 class="head2h6"><a href="{{ route('project.show', $project->id) }}">{{ $project->project_id }}</a></h6>
            </div>
        </div>

        <div class="mainbdy">

            <!-- Right Content -->
            <div class="contentright">
                <div class="proftabs">
                    <ul class="nav nav-tabs d-flex justify-content-start align-items-center gap-md-3 gap-xl-3"
                        id="myTab" role="tablist">
                        @include('projects.tabs')
                    </ul>
                </div>

                <div class="tab-content" id="myTabContent">

                    {{-- @can('tab-profile') --}}
                    <div class="tab-pane fade show active" id="project" role="tabpanel">
                        @include('projects.prjt_details')
                    </div>
                    {{-- @endcan
                    @can('tab-survey') --}}
                    <div class="tab-pane fade" id="survey" role="tabpanel">
                        @include('projects.prjt_survey')
                    </div>
                    {{-- @endcan
                    @can('tab-drawing') --}}
                    <div class="tab-pane fade" id="drawing" role="tabpanel">
                        @include('projects.prjt_drawing', [
                            'drawing' => $drawings,
                            'project_id' => $project_id,
                        ])
                    </div>
                    {{-- @endcan
                    @can('tab-progress') --}}
                    <div class="tab-pane fade" id="progress" role="tabpanel">
                        @include('projects.prjt_progress', [
                            'pro_progress_stage' => $pro_progress_stage,
                            'project_id' => $project_id,
                            'qc_stage' => $qcs,
                            'pro_progress_tab' => $pro_progress_tab,
                        ])
                    </div>
                    {{-- @endcan --}}
                    {{-- @can('tab-qc')
                        <div class="tab-pane fade" id="budget" role="tabpanel">
                            @include('projects.prjt_budget')
                        </div>
                    @endcan
                    @can('tab-profile')
                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            @include('projects.prjt_payment')
                        </div>
                    @endcan --}}
                    {{-- @can('tab-qc') --}}
                    <div class="tab-pane fade" id="qc" role="tabpanel">
                        @include('projects.prjt_qc')
                    </div>
                    {{-- @endcan
                    @can('tab-snags') --}}
                    <div class="tab-pane fade" id="snag" role="tabpanel">
                        @include('projects.prjt_snag')
                    </div>
                    {{-- @endcan
                    @can('tab-docs/link') --}}
                    <div class="tab-pane fade" id="document" role="tabpanel">
                        @include('projects.prjt_document', [
                            'docs' => $drawings,
                            'project_id' => $project_id,
                        ])
                    </div>
                    {{-- @endcan --}}
                </div>
            </div>


        </div>
    </div>

    <script src={{ asset('assets/js/form_script.js') }}></script>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('active_tab'))
                let activeTab = '{{ session('active_tab') }}';
                let tabTrigger = document.querySelector(`[data-bs-toggle="tab"][data-bs-target="#${activeTab}"]`);
                if (tabTrigger) {
                    let tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            @endif
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Restore active tab from localStorage

            // if (activeTab) {
            //     const tabTrigger = document.querySelector(`.profiletabs[data-bs-target="${activeTab}"]`);
            //     if (tabTrigger) {
            //         new bootstrap.Tab(tabTrigger).show();
            //     }
            // }

            // Store tab on click
            // const tabButtons = document.querySelectorAll('.profiletabs');
            // console.log(tabButtons);

            // const activeTabTarget = document.querySelector('.profiletabs.active')?.getAttribute('data-bs-target');
            // console.log('Active tab target:', activeTabTarget);
            // localStorage.setItem('activeTab', activeTabTarget);



            // tabButtons.forEach(button => {
            //     button.addEventListener('show.bs.tab', function (event) {
            //         const target = event.target.getAttribute('data-bs-target');
            //         console.log('Active tab set to:', target);
            //         localStorage.setItem('activeTab', target);

            //     });
            // });
        });
    </script>


    <!-- Datatables List -->
    <script>
        $(document).ready(function() {
            function initTable(tableId, filterInputId) {
                var table = $(tableId).DataTable({
                    "paging": false,
                    "searching": true,
                    "ordering": true,
                     "order": [0, "desc"],
                    "bDestroy": true,
                    "info": false,
                    "responsive": true,
                    "pageLength": 30,
                    "dom": '<"top"f>rt<"bottom"ilp><"clear">',
                });

                $(filterInputId).on('keyup', function() {
                    table.search($(this).val()).draw();
                });
            }
            // Initialize each table
            initTable('#surveyTable', '#filterInput1');
            // initTable('#drawingTable', '#filterInput2');
            initTable('#progressTable', '#filterInput3');
            initTable('#qcTable', '#filterInput4');
            initTable('#snagTable', '#filterInput5');
            initTable('#historyTable', '#filterInput6');
            initTable('#budgetTable', '#filterInput7');
            initTable('#paymentTable', '#filterInput8');
            initTable('#documentTable', '#filterInput9');
            initTable('#materialTable', '#filterInput10');
        });
    </script>

    <!-- Table Filter -->
    <script>
        function filterList(status, tableId, buttonClass, noDataRowId) {
            const rows = document.querySelectorAll(`#${tableId} tbody tr`);
            const buttons = document.querySelectorAll(`.${buttonClass}`);
            const noDataRow = document.querySelector(`#${noDataRowId}`);

            buttons.forEach(button => button.classList.remove('active'));
            const clickedButton = event.target;
            clickedButton.classList.add('active');

            let rowFound = false;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === 'all' || rowStatus === status) {
                    row.style.display = '';
                    rowFound = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (!rowFound) {
                if (!noDataRow) {
                    const noDataRowHTML = `
                            <tr id="${noDataRowId}">
                                <td colspan="6" class="text-center">No Data Available</td>
                            </tr>
                        `;
                    document.querySelector(`#${tableId} tbody`).innerHTML += noDataRowHTML;
                }
            } else {
                if (noDataRow) {
                    noDataRow.remove();
                }
            }
        }
    </script>
@endsection
