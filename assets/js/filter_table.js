function filterTable(event) {
    var filter = event.target.value.toLowerCase();
    var rows = document.querySelector("#table tbody").rows;

    var display = false;
    for (var i = 0; i < rows.length; i++) {
        display = false;
        for (var j=0; j<rows[i].cells; j++){
            var col = rows[i].cells[j].textContent.toLowerCase();
            if (col.indexOf(filter) > -1) {
                display = true;
                break;
            }
        }
        if (display === false){
            rows[i].style.display = "none";
        }
    }
}

document.querySelector('#custom_search').addEventListener('keyup', filterTable, false);