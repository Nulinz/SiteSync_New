<div class="empdetails">
    <div class="listtable">
        <div class="profilelisthead row">
            <div class="profileleft col-sm-12 col-md-6">
                <button class="profilefilterbtn profilefilterbtn8 active"
                    onclick="filterList('all', 'paymentTable', 'profilefilterbtn8', 'noDataRow8')">All</button>
                <button class="profilefilterbtn profilefilterbtn8"
                    onclick="filterList('completed', 'paymentTable', 'profilefilterbtn8', 'noDataRow8')">Completed</button>
                <button class="profilefilterbtn profilefilterbtn8"
                    onclick="filterList('pending', 'paymentTable', 'profilefilterbtn8', 'noDataRow8')">To Do</button>
            </div>
            <div class="profileright justify-content-end col-sm-12 col-md-6">
                <input type="text" id="filterInput8" class="form-control" placeholder=" Search">
                <a data-bs-toggle="modal" data-bs-target="#addpayment"><button class="profilelistbtn"><i
                            class="fas fa-plus"></i></button></a>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="table" id="paymentTable">
                <thead>
                    <tr>
                        <th>Bill/Inv Date</th>
                        <th>Category</th>
                        <th>Item</th>
                        <th>Stage</th>
                        <th>UOM</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>GST</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-status="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href=""><i class="fas fa-cloud-arrow-down"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Payment -->
<div class="modal fade" id="addpayment" tabindex="-1" aria-labelledby="addpaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <div class="usericon">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i></h5>
                </div>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 class="modal-title mb-2 fs-5" id="addpaymentLabel">Add Payment</h4>
                <form method="" action="">
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="invdate" class="col-form-label">Bill / Invoice Date</label>
                        <input type="date" class="form-control" name="invdate" id="invdate" min="{{date('Y-m-d')}}"
                            max="9999-12-31" pattern="\d{4}-\d{2}-\d{2}" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="item" class="col-form-label">Item</label>
                        <select class="form-select" name="item" id="item" required>
                            <option value="" selected disabled>Select Item</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="category" class="col-form-label">Category</label>
                        <select class="form-select" name="category" id="category" required>
                            <option value="" selected disabled>Select Category</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="subcategory" class="col-form-label">Sub Category</label>
                        <select class="form-select" name="subcategory" id="subcategory" required>
                            <option value="" selected disabled>Select Sub Category</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="uom" class="col-form-label">UOM</label>
                        <select class="form-select" name="uom" id="uom" required>
                            <option value="" selected disabled>Select UOM</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="qty" class="col-form-label">Quantity</label>
                        <input type="text" class="form-control" name="qty" id="qty" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="rate" class="col-form-label">Rate</label>
                        <input type="text" class="form-control" name="rate" id="rate" required>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="gst" class="col-form-label">GST</label>
                        <div class="d-flex justify-content-start align-items-center gap-5 mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="gst_radio" id="gst_yes">
                                <label class="form-check-label my-auto" for="gst_yes">Yes</label>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="gst_radio" id="gst_no">
                                <label class="form-check-label my-auto" for="gst_no">No</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1 gst-section" style="display: none;">
                        <label for="gstamt" class="col-form-label">GST Amount</label>
                        <input type="text" class="form-control" name="gstamt" id="gstamt">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="totalamt" class="col-form-label">Total Amount</label>
                        <input type="text" class="form-control" name="totalamt" id="totalamt">
                    </div>
                    <div class="col-sm-12 col-md-12 mb-1">
                        <label for="attachfile" class="col-form-label">File Attachment</label>
                        <label class="custom-file-upload" for="attachfile">
                            <div class="icon">
                                <img src="{{ asset('assets/images/upload.png') }}" height="35px" alt="">
                            </div>
                            <div class="text">
                                <span id="attachtext" class="text-center">Upload File</span>
                            </div>
                            <input type="file" name="file_attachment" id="attachfile"
                                onchange="updateFileName('attachfile', 'attachtext')" required>
                        </label>
                    </div>
                    <div class="d-flex justify-content-center align-items-center mx-auto mt-3">
                        <button type="submit" class="modalbtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function () {
        $('input[name="gst_radio"]').change(function () {
            if ($('#gst_yes').is(':checked')) {
                $('.gst-section').show();
            } else {
                $('.gst-section').hide();
            }
        });
    });
</script>