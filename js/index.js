function currentDate(){
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#end").value = today;
}

function search() {
    let date = new Date(document.querySelector("#start").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let start = year + '-' + month + '-' + day;

    date = new Date(document.querySelector("#end").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let end = year + '-' + month + '-' + day;
    
    var general = new XMLHttpRequest();
    general.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    general.open("GET", "mysql/general.php?start=" + start + "&end=" + end, false);
    general.send();
}