$(document).ready(function () {
    let end_date = new Date(document.querySelector("#end_date").value);
    let start_date = new Date(document.querySelector("#start_date").value);

    let day1 = start_date.getDate();
    let day2 = end_date.getDate();

    let month1 = start_date.getMonth() + 1;
    let month2 = end_date.getMonth() + 1;

    let year1 = start_date.getFullYear();
    let year2 = end_date.getFullYear();

    start_date = year1 + '-' + month1 + '-' + day1;
    end_date = year2 + '-' + month2 + '-' + day2;

    document.getElementById('fees').innerHTML = "<option>All</option>";

    let HttpFees = new XMLHttpRequest();
    HttpFees.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('fees').innerHTML += this.responseText;
        }
    };
    HttpFees.open("GET", "mysql/fees.php?start_date=" + start_date + "&end_date=" + end_date, false);
    HttpFees.send();
});

function search() {
    let date = new Date(document.querySelector("#start_date").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let start_date = year + '-' + month + '-' + day;

    date = new Date(document.querySelector("#end_date").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let end_date = year + '-' + month + '-' + day;

    let master_id = document.getElementById('fees').options[document.getElementById('fees').selectedIndex].value;
    let type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;


    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            studentsDataTable();
        }
    };
    payments.open("GET", "mysql/particular_wise.php?start_date=" + start_date + "&end_date=" + end_date + "&master_id=" + master_id + "&type=" + type, false);
    payments.send();
}