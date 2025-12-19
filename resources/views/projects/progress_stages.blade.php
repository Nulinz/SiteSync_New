@extends ('layouts.app')

@section('content')
    <style>
        .table thead th {
            background-color: var(--theadbg) !important;
        }

        @media screen and (min-width: 1098px) {

            .table td div select {
                padding: 5px;
                font-size: 10px;
                font-weight: 500;
                border-radius: 5px;
            }
        }

        @media screen and (min-width: 767px) and (max-width: 1098px) {

            .table td div select {
                padding: 4px;
                font-size: 8px;
                font-weight: 500;
                border-radius: 5px;
            }
        }

        @media screen and (max-width: 767px) {

            .table td div select {
                padding: 4px;
                font-size: 8px;
                font-weight: 500;
                border-radius: 5px;
            }
        }
    </style>

    <div class="body-div px-4">
        <div class="body-head my-3">
            <div class="body-h6">
                <h6 class="head1h6"><a href="{{ route('dashboard.index') }}">Dashboard /</a></h6>
                <h6 class="head1h6"><a href="{{ route('project.index') }}">My Projects /</a></h6>
                <h6 class="head2h6"><a href="">Project Name</a></h6>
            </div>
        </div>

        @php
            $stage_group = collect($progress)->groupBy('stage');

            // dd($stage_group);
            // ->map(function ($item) {
            //     return [
            //         'id' => $item['id'],
            //         'file_type' => $item['file_type']
            //     ];
            // })->groupBy('file_type');
            // $drawingsCollection = collect($ent_drawings);

            // dd($drawingsCollection);

            //  dd($distinctFileTypes);

        @endphp

        <div class="container-fluid px-0 mt-4 listtable">

            <div class="table-wrapper">
                <form action="{{ route('progress.update') }}" method="POST">
                    @csrf
                    <table class="example table">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Activity</th>
                                <th>Duration (Days)</th>
                                <th>Planned Start</th>
                                <th>Planned End</th>
                                <th>Actual Start</th>
                                <th>Actual End</th>
                                <th>QC Sync</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $submit = 0;
                            @endphp
                            @foreach ($stage_group as $stage => $values)
                                <tr>
                                    <th>Stages</th>
                                    <th>{{ $stage }}</th>
                                    <td>{{ $values->first()->duration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($values->first()->st_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($values->first()->end_date)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($values->first()->sc_start)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($values->first()->sc_end)) }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                {{-- @dd($values); --}}

                                @foreach ($values->sortBy('sc_start') as $sub)
                                    <tr>
                                        <td>Activities</td>
                                        <td>{{ $sub->activity }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>


                                        <td>
                                            <div>
                                                {{-- <select class="form-select" name="qc_sync[{{ $sub->id }}]"
                                                    id="">
                                                    @if (is_null($sub->qc))
                                                        <option value="" selected disabled>Select Option</option>
                                                        @foreach ($qc_drop as $qc)
                                                            <option value="{{ $qc->id }}">{{ $qc->title }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        @php
                                                            $qc_title = collect($qc_drop)->firstWhere('id', $sub->qc); // ✅ Correct
                                                        @endphp
                                                        <option value="{{ $sub->qc }}">{{ $qc_title }}</option>
                                                    @endif
                                                </select> --}}
                                                @if ($sub->qc == 0)
                                                    @php
                                                        $submit++;
                                                    @endphp
                                                    <select class="form-select" name="qc_sync[{{ $sub->act_id }}]">
                                                        <option value="" disabled
                                                            {{ is_null($sub->qc) ? 'selected' : '' }}>Select Option
                                                        </option>
                                                        @foreach ($qc_drop as $qc)
                                                            <option value="{{ $qc->id }}"
                                                                {{ $sub->qc == $qc->id ? 'selected' : '' }}>
                                                                {{ $qc->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <p>{{ $qc_drop->where('id', $sub->qc)->value('title') }}</p>
                                                @endif

                                            </div>
                                        </td>
                                        <td>
                                            @if ($sub->remove)
                                                <p>Remove</p>
                                            @else
                                                <a onclick="return confirm('Are you sure you want to remove this activity?')"
                                                    href="{{ route('activity.remove', ['act_id' => $sub->act_id]) }}">Remove</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>
                    </table>
                    @if ($submit > 0)
                        <div class="d-flex justify-content-center align-items-center mt-3">
                            <button type="submit" class="formbtn">Save</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Datatables List -->
    <script>
        $(document).ready(function() {
            function initTable(tableId, filterInputId) {
                var table = $(tableId).DataTable({
                    "paging": false,
                    "searching": true,
                    "ordering": true,
                    // "order": [0, "asc"],
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
        });
    </script>
@endsection
