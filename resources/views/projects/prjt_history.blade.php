@extends ('layouts.app')

@section('content')
    <style>
        .table thead th {
            background-color: var(--theadbg) !important;
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

        <div class="mainbdy">

            <div class="body-head">
                <h4 class="m-0">History Details</h4>
            </div>

            <div class="empdetails">
                <div class="listtable px-0">

                    <div class="table-wrapper">
                        <table class="table" id="historyTable">
                            <thead>
                                <tr>
                                    <th>File Type</th>
                                    <th>Title</th>
                                    <th>Version</th>
                                    <th>Uploaded By</th>
                                    <th>Uploaded On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    //  dd($entry->last()->uploaded_on);
                                @endphp
                                @foreach ($entry as $et)
                                    <tr>
                                        <td>{{ $et->drawing->file_type }}</td>
                                        <td>{{ $et->drawing->title }}</td>
                                        <td>{{ $et->version }}</td>
                                        <td>{{ $et->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($et->uploaded_on)->format('d-m-Y') }}</td>
                                        <td><span class="text-danger">{{ $et->status }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                               <a href="{{ Storage::disk('s3')->url('draw/' . $et->file_attachment) }}" target="_blank">
                                                    <i class="fas fa-cloud-arrow-down"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
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
            // Initialize each table
            initTable('#surveyTable', '#filterInput1');
            initTable('#drawingTable', '#filterInput2');
            initTable('#progressTable', '#filterInput3');
            initTable('#qcTable', '#filterInput4');
            initTable('#snagTable', '#filterInput5');
            initTable('#historyTable', '#filterInput6');
            initTable('#budgetTable', '#filterInput7');
            initTable('#paymentTable', '#filterInput8');
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
