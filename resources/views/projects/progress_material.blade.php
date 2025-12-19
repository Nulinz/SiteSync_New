<div class="empdetails">
    <div class="listtable p-0">

        <div class="table-wrapper">
            <table class="table" id="materialTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        {{-- <th>Aggregators</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($act_mat as $am)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $am->category }}</td>
                            <td>{{ $am->unit }}</td>
                            <td>{{ $am->total }}</td>
                            {{-- <td>60</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
