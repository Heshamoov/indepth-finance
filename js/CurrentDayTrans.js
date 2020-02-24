function search() {
    let TodayTrans = new XMLHttpRequest();
    TodayTrans.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            trans_table();
        }
    };
    TodayTrans.open("GET", "mysql/DayBook.php", false);
    TodayTrans.send();
}