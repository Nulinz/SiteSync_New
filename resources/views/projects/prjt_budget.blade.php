<div class="empdetails">
    <div class="listtable">
        <div class="profilelisthead row">
            <div class="profileright justify-content-end col-sm-12 col-md-12">
                <input type="text" id="filterInput7" class="form-control" style="width: 35%" placeholder=" Search">
                <a><button class="profilelistbtn addtablebtn"><i class="fas fa-plus"></i></button></a>
            </div>
        </div>

        <div class="table-wrapper budget-table">
            <table class="table" id="budgetTable">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>UOM</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-status="">
                        <td>
                            <div>
                                <select class="form-select" name="category">
                                    <option value="" selected disabled>Select Category</option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select" name="items">
                                    <option value="" selected disabled>Select Item</option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <textarea rows="1" class="form-control" name="description"></textarea>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select class="form-select" name="uom">
                                    <option value="" selected disabled>Select UOM</option>
                                    <option value=""></option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control tableinput" name="qty">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control tableinput" name="rate">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control tableinput" name="amt">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a><i class="fas fa-circle-plus text-success addButton"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Category Total</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div>
                                <input type="text" class="form-control tableinput" name="total" id="total" readonly>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    const tableWrapper = document.querySelector(".budget-table");
    document.addEventListener("DOMContentLoaded", function () {
        const addButton = document.querySelector(".addButton");

        addButton.addEventListener("click", function () {
            addNewRow("budgetTable");
        });

        document.querySelector(".addtablebtn").addEventListener("click", function () {
            appendNewTable();
        });
    });

    function addNewRow(tableId) {
        const tableBody = document.querySelector(`#${tableId} tbody`);
        let newRow = document.createElement("tr");
        newRow.setAttribute("data-status", "");

        newRow.innerHTML = `
        <td><div><input type="text" class="form-control catinput" name="category" readonly></div></td>
        <td><div><select class="form-select" name="items"><option value="" selected disabled>Select Item</option><option value=""></option></select></div></td>
        <td><div><textarea rows="1" class="form-control" name="description"></textarea></div></td>
        <td><div><select class="form-select" name="uom"><option value="" selected disabled>Select UOM</option><option value=""></option></select></div></td>
        <td><div><input type="text" class="form-control tableinput" name="qty"></div></td>
        <td><div><input type="text" class="form-control tableinput" name="rate"></div></td>
        <td><div><input type="text" class="form-control tableinput" name="amt"></div></td>
        <td><div class="d-flex align-items-center gap-2"><a class="remove-row"><i class="fas fa-circle-minus text-danger"></i></a></div></td>
    `;

        tableBody.appendChild(newRow);
        newRow.querySelector(".remove-row").addEventListener("click", function (e) {
            e.preventDefault();
            newRow.remove();
        });
    }

    function appendNewTable() {
        const originalTable = document.getElementById("budgetTable");
        const newTable = originalTable.cloneNode(true);

        let tableCount = document.querySelectorAll(".budget-table .table").length + 1;
        const newTableId = `table${tableCount}`;
        newTable.id = newTableId;

        // Remove old rows from cloned table body and reset inputs
        const newTableBody = newTable.querySelector("tbody");
        newTableBody.innerHTML = "";

        let emptyRow = document.createElement("tr");
        emptyRow.innerHTML = `
        <td><div><select class="form-select catinput" name="category"><option value="" selected disabled>Select Category</option><option value=""></option></select></div></td>
        <td><div><select class="form-select" name="items"><option value="" selected disabled>Select Item</option><option value=""></option></select></div></td>
        <td><div><textarea rows="1" class="form-control" name="description"></textarea></div></td>
        <td><div><select class="form-select" name="uom"><option value="" selected disabled>Select UOM</option><option value=""></option></select></div></td>
        <td><div><input type="text" class="form-control tableinput" name="qty"></div></td>
        <td><div><input type="text" class="form-control tableinput" name="rate"></div></td>
        <td><div><input type="text" class="form-control tableinput" name="amt"></div></td>
        <td><div class="d-flex align-items-center gap-2"><a><i class="fas fa-circle-plus text-success addButton"></i></a></div></td>
    `;
        newTableBody.appendChild(emptyRow);

        tableWrapper.appendChild(newTable);

        // Update the add button event listener for the new table
        newTable.querySelector(".addButton").addEventListener("click", function () {
            addNewRow(newTableId);
        });
    }
</script>


<!-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const addButton = document.querySelector(".addButton");

        // Get the table body
        const tableBody = document.querySelector("#budgetTable tbody");

        addButton.addEventListener("click", function () {
            // Create a new row
            let newRow = document.createElement("tr");
            newRow.setAttribute("data-status", "");

            // Define columns
            newRow.innerHTML = `
                <td>
                    <div>
                        <input type="text" class="form-control catinput" name="category" id="category" readonly>
                    </div>
                </td>
                <td>
                    <div>
                        <select class="form-select" name="items">
                            <option value="" selected disabled>Select Item</option>
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <textarea rows="1" class="form-control" name="description"></textarea>
                    </div>
                </td>
                <td>
                    <div>
                        <select class="form-select" name="uom">
                            <option value="" selected disabled>Select UOM</option>
                            <option value=""></option>
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control tableinput" name="qty">
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control tableinput" name="rate">
                    </div>
                </td>
                <td>
                    <div>
                        <input type="text" class="form-control tableinput" name="amt">
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <a class="remove-row"><i class="fas fa-circle-minus text-danger"></i></a>
                    </div>
                </td>
            `;

            // Append new row to the table
            tableBody.appendChild(newRow);

            // Add event listener to remove button
            newRow.querySelector(".remove-row").addEventListener("click", function (e) {
                e.preventDefault();
                newRow.remove();
            });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".addtablebtn").addEventListener("click", function () {
            appendNewTable();
        });
    });

    function appendNewTable() {
        const tableWrapper = document.querySelector(".budget-table");

        // Clone the existing table
        const originalTable = document.getElementById("budgetTable");
        const newTable = originalTable.cloneNode(true);

        // Generate a new ID for the cloned table
        const newTableId = `table${document.querySelectorAll(".table").length + 1}`;
        newTable.id = newTableId;

        // Clear input fields in the cloned table
        newTable.querySelectorAll("input, select, textarea").forEach(element => {
            if (element.tagName === "SELECT") {
                element.selectedIndex = 0;
            } else {
                element.value = "";
            }
        });

        // Append new table to the wrapper
        tableWrapper.appendChild(newTable);
    }
</script> -->