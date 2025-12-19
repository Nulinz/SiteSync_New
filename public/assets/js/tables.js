// DataTables List
$(document).ready(function () {
    var table = $(".example").DataTable({
        paging: true,
        searching: true,
        ordering: true,
        bDestroy: true,
        info: false,
        responsive: true,
        pageLength: 10,
        dom: '<"top"f>rt<"bottom"lp><"clear">',
    });
});

// List Filter
$(document).ready(function () {
    var table = $(".example").DataTable();
    $(".example thead th").each(function (index) {
        var headerText = $(this).text();
        if (
            headerText != "" &&
            headerText.toLowerCase() != "action" &&
            headerText.toLowerCase() != "progress"
        ) {
            $(".headerDropdown").append(
                '<option value="' + index + '">' + headerText + "</option>"
            );
        }
    });
    $(".filterInput").on("keyup", function () {
        var selectedColumn = $(".headerDropdown").val();
        if (selectedColumn !== "All") {
            table.column(selectedColumn).search($(this).val()).draw();
        } else {
            table.search($(this).val()).draw();
        }
    });
    $(".headerDropdown").on("change", function () {
        $(".filterInput").val("");
        table.search("").columns().search("").draw();
    });
});