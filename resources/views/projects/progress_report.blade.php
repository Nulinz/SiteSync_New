<form action="{{ route('report') }}" method="post">
    @csrf
    <div class="form-div">
        <!-- Report Details -->
        <input type="hidden" name="pro_id" value={{ $project_id }}>
        <div class="row">
            <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                <label for="fromdate">From Date</label>
                <input type="date" class="form-control" name="start" id="fromdate" required>
            </div>
            <div class="col-sm-12 col-md-4 col-xl-4 mb-3 inputs">
                <label for="todate">To Date</label>
                <input type="date" class="form-control" name="end" id="todate" required>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center mt-3">
        <button type="submit1" class="formbtn">Download Report</button>
    </div>
</form>
