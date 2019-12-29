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
