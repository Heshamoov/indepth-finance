function currentDate(){
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#to").value = today;
}

// document.getElementById("search").onclick = function() {search()};

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
    
    var general = new XMLHttpRequest();
    general.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    general.open("GET", "mysql/general.php?fromdate=" + fromdate + "&todate=" + todate, false);
    general.send();
}


$(function() {
    $("td[colspan=3]").find("p").hide();
    $("table").click(function(event) {
        event.stopPropagation();
        var $target = $(event.target);
        if ( $target.closest("td").attr("colspan") > 1 ) {
            $target.slideUp();
        } else {
            $target.closest("tr").next().find("p").slideToggle();
        }                    
    });
});