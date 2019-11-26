function currentDate(){
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#to").value = today;
}

document.getElementById("search").onclick = function() {search()};

function search() {
    let date = new Date(document.querySelector("#from").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let fromdate = year + '-' + month + '-' + day;


    date = new Date(document.querySelector("#to").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let todate = year + '-' + month + '-' + day;

    document.getElementById("fromCell").innerHTML = fromdate;
    document.getElementById("toCell").innerHTML = todate;

    
    var terms = new XMLHttpRequest();
    terms.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    terms.open("GET", "mysql/terms.php?fromdate=" + fromdate + "&todate=" + todate, false);
    terms.send();
}