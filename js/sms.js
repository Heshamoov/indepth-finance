function search() {
    let date = new Date(document.querySelector("#start_date").value);
    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();
    let start_date = year + '-' + month + '-' + day;

    date = new Date(document.querySelector("#end_date").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let end_date = year + '-' + month + '-' + day;

    let parentsList = new XMLHttpRequest();
    parentsList.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            parentsDataTable();
        }
    };
    parentsList.open("GET", "mysql/parentsSmsList.php?start_date=" + start_date + "&end_date=" + end_date, false);
    parentsList.send();
}

function printTable() {
    document.getElementById('parentsSmsListPrintDiv').style.display = 'block';
    document.getElementById('headerDiv').style.display = 'inline-table';
    printJS({
        printable: 'parentsSmsListPrintDiv', type: 'html',showModal: true, css: 'css/print.css'
    });
    document.getElementById('parentsSmsListPrintDiv').style.display = 'none';
    document.getElementById('headerDiv').style.display = 'none';
}

function printSorted(table_name) {
    // var table = $('#parentsSmsList').DataTable({});
    //  table.destroy();
    document.getElementById('headerDiv').style.display = 'inline-table';
    printJS({printable: table_name, type: 'html' , repeatTableHeader : true, showModal : true,
        css: 'css/print.css'});
    document.getElementById('headerDiv').style.display = 'none';

    // parentsDataTable();
}