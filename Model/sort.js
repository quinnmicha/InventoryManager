function sortBy(n) {
    var table, i, x, y, array = [];

    table = document.getElementById("invTable");
    var rows = table.rows;

    for (i = 1; i < (rows.length - 1); i++) {

        x = rows[i].cells[n];
        
        array.push(x.innerHTML);


    }

}

