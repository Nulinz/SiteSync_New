@extends ('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_main.css') }}">

    <div class="body-div px-4 py-1">
        <div class="body-head">
            <h4 class="m-0 text-uppercase">Dashboard</h4>
        </div>

        <!-- Tabs -->
        <div class="container-fluid px-0 header">
            <div class="container px-0 mt-2 tabbtns">
                <div class="my-2">
                    <a href="{{ route('dashboard.index') }}"><button
                            class="dashtabs {{ Request::routeIs('dashboard.index') ? 'active' : '' }}">Overview</button></a>
                </div>
                <div class="my-2">
                    <a href="{{ route('dashboard.mydashboard') }}"><button
                            class="dashtabs {{ Request::routeIs('dashboard.mydashboard') ? 'active' : '' }}">My
                            Dashboard</button></a>
                </div>
            </div>
        </div>

        <div class="container px-0 mt-3">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <div>
                                <h6 class="card1h6 mb-1">Active Projects</h6>
                                <h6 class="card2h6 mb-1">All General information appear in this field</h6>
                            </div>
                        </div>
                        <div id="chart0"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards d-none">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <div>
                                <h6 class="card1h6 mb-1">Delayed / On Schedule</h6>
                                <h6 class="card2h6 mb-1">All General information appear in this field</h6>
                            </div>
                            <select class="form-select" name="cluster" id="cluster">
                                <option value="" selected disabled>Select</option>
                                <option value="">1</option>
                                <option value="">2</option>
                            </select>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-xl-6 mb-3 cards">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <div>
                                <h6 class="card1h6 mb-1">Pending Task</h6>
                                <h6 class="card2h6 mb-1">All General information appear in this field</h6>
                            </div>
                            <select class="form-select d-none" name="cluster" id="cluster">
                                <option value="" selected disabled>Select</option>
                                <option value="">1</option>
                                <option value="">2</option>
                            </select>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards d-none">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <div>
                                <h6 class="card1h6 mb-1">Survey Tracker</h6>
                                <h6 class="card2h6 mb-1">All General information appear in this field</h6>
                            </div>
                        </div>
                        <div class="cardtable">
                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #123</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #456</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #789</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #012</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #345</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12 col-xl-12 mb-2">
                                <div class="cardtblhead">
                                    <div>
                                        <h5 class="mb-0">Project A</h5>
                                        <h5 class="mb-0 text-success">Site #678</h5>
                                    </div>
                                    <div class="d-block ms-auto">
                                        <h6 class="mb-0">13-12-2024</h6>
                                        <h6 class="mb-0">02.00 PM</h6>
                                    </div>
                                </div>
                                <div class="cardtblcnt">
                                    <h6 class="mb-0">In a laoreet purus. Integer turpis quam, laoreet id orci
                                        nec, ultrices lacinia nunc. Aliquam erat vo</h6>
                                </div>
                                <hr>
                                <div class="cardtblfoot">
                                    <div>
                                        <h6 class="mb-0 text-danger"><i class="fas fa-flag"></i>&nbsp;
                                            17-12-2025</h6>
                                        <h6 class="mb-0 text-danger">10.00 AM</h6>
                                    </div>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <h5 class="mb-0">Assign To</h5>
                                        <div class="imgplot">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg1">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg2">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg3">
                                            <img src="{{ asset('assets/images/avatar.png') }}" alt=""
                                                class="plotimg4">
                                            <img src="{{ asset('assets/images/avatar_1.png') }}" alt=""
                                                class="plotimg5">
                                            <img src="{{ asset('assets/images/avatar_2.png') }}" alt=""
                                                class="plotimg6">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 col-xl-4 mb-3 cards d-none">
                    <div class="cardsdiv">
                        <div class="cardshead">
                            <div>
                                <h6 class="card1h6 mb-1">Snag Tracker</h6>
                                <h6 class="card2h6 mb-1">All General information appear in this field</h6>
                            </div>
                            <select class="form-select" name="cluster" id="cluster">
                                <option value="" selected disabled>Select</option>
                                <option value="">1</option>
                                <option value="">2</option>
                            </select>
                        </div>
                        <div class="cardtable">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Assign To</th>
                                        <th>Deadline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-danger"></i>
                                                        Sheik</h5>
                                                    <h6 class="mb-0">Cracked wall near lobby</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">In Progress</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-primary"></i>
                                                        Sabari</h5>
                                                    <h6 class="mb-0">Broken window in Block 4, floor 5</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Resolved</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-warning"></i>
                                                        Naveen</h5>
                                                    <h6 class="mb-0">Paint touch-up required in floor6</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Closed</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-danger"></i>
                                                        Sugan</h5>
                                                    <h6 class="mb-0">Damaged flooring in hall block 5</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Resolved</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-primary"></i>
                                                        Venkat</h5>
                                                    <h6 class="mb-0">Broken window in Block 4, floor 5</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Closed</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-warning"></i>
                                                        Hari</h5>
                                                    <h6 class="mb-0">Paint touch-up required in floor6</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Closed</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-danger"></i>
                                                        Saravanan</h5>
                                                    <h6 class="mb-0">Cracked wall near lobby</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Closed</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h5 class="mb-0"><i class="fas fa-circle text-primary"></i>
                                                        Bala Krishnan</h5>
                                                    <h6 class="mb-0">Paint touch-up required in floor6</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div>
                                                    <h6 class="mb-0">Closed</h6>
                                                    <h5 class="mb-0 text-danger">18/09/2024</h5>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    @php
        use Illuminate\Support\Js;

        // Assuming $project_progress is an array of arrays like: [['Project A', 75], ['Project B', 50]]

    @endphp

    <!-- Charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Script -->
    <!-- Chart 0 -->
    <script>
        var seriesData = [];
        var labelsData = [];
        var colorsData = [];

        @foreach ($project_progress as $project)
            seriesData.push({{ Js::from($project['in_progress_count']) }}); // e.g., progress value
            labelsData.push({{ Js::from($project['project_name']) }}); // e.g., project name
            colorsData.push("{{ '#' . sprintf('%06X', mt_rand(0, 0xffffff)) }}");
        @endforeach

        console.log(seriesData, labelsData, colorsData);

        var options = {
            series: seriesData,
            labels: labelsData,
            colors: colorsData,
            chart: {
                type: 'donut',
                height: 315,
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: false
            },
            responsive: [{
                breakpoint: 1098,
                options: {
                    chart: {
                        height: 315,
                    },
                    legend: {
                        show: false,
                    }
                },
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart0"), options);
        chart.render();
    </script>

    <!-- Chart 1 -->
    <script>
        var options = {
            series: [{
                name: 'Delayed',
                data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 30, 60, 99]
            }, {
                name: 'Scheduled',
                data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 100, 60, 20]
            }],
            chart: {
                type: 'bar',
                height: 315
            },
            colors: ['#8979FF', '#FFCB69'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '60%',
                    borderRadiusApplication: 'end',
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                labels: {
                    style: {
                        fontSize: '8px'
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
    </script>

    <!-- Chart 2 -->
    <script>
        var options = {
            series: [{
                name: 'Pending',
                data: {!! json_encode($pending_task_data) !!}
            }],
            chart: {
                type: 'bar',
                height: 300,
            },
            colors: ['#A652BB'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    borderRadiusApplication: 'end'
                },
            },
            dataLabels: {
                enabled: false
            },
            // legend: {
            //     show: false
            // },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: {!! json_encode($pending_task_month) !!},
                labels: {
                    style: {
                        fontSize: '8px'
                    }
                },
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val
                    }
                }
            },
            responsive: [{
                breakpoint: 1024,
                options: {
                    legend: {
                        show: false,
                    }
                },
                xaxis: {
                    categories: ['J', 'F', 'M', 'A', 'M', 'J', 'Jl', 'A', 'S', 'O', 'N', 'D'],
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    }
                },
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    </script>
@endsection
