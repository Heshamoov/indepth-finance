function currentDate(){
    let today = new Date().toISOString().substr(0, 10);
    document.querySelector("#end").value = today;
}

function search() {
    let date = new Date(document.querySelector("#start").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let start_date = year + '-' + month + '-' + day;

    date = new Date(document.querySelector("#end").value);
    day = date.getDate();
    month = date.getMonth() + 1;
    year = date.getFullYear();
    let end_date = year + '-' + month + '-' + day;
    
    var general = new XMLHttpRequest();
    general.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    general.open("GET", "mysql/general.php?start_date=" + start_date + "&end_date=" + end_date, false);
    general.send();
}

function FamilyStatement(params) {
    start_date = params[0];
    end_date = params[1];
    familyid = params[2];
    // document.getElementById("debug").innerHTML = start_date + " " + familyid;

    var FamilyStatementRequest = new XMLHttpRequest();
    FamilyStatementRequest.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    FamilyStatementRequest.open("GET", "mysql/FamilyStatement.php?start_date=" + start_date + "&end_date=" + end_date
                                         + "&familyid=" + familyid, false);
    FamilyStatementRequest.send();
}

function general(params) {
    start_date = params[0];
    end_date = params[1];
    familyid = params[2];
    // document.getElementById("debug").innerHTML = start_date + " " + familyid;

    var FamilyStatementRequest = new XMLHttpRequest();
    FamilyStatementRequest.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    FamilyStatementRequest.open("GET", "mysql/general.php?start_date=" + start_date + "&end_date=" + end_date
                                         + "&familyid=" + familyid, false);
    FamilyStatementRequest.send();
}