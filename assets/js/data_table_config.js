$(document).ready(function () {
    $(".table").DataTable({
        "ordering": true,
        "searching": true,
        "paging": false,
        "columnDefs": [
            {
                "targets": 0,
                "searchable": false,
                "visible": true,
            }
        ],
        "order": [[0, "asc"]]
    });
});