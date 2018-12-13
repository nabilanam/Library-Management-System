$(document).ready(function () {
    $(".table").DataTable({
        "ordering": true,
        "searching": true,
        "paging": true,
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

$('.ui.dropdown')
    .dropdown({
        allowAdditions: false,
        fullTextSearch: true
    });