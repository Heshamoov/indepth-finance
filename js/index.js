// function currentDate(){
//     let today = new Date().toISOString().substr(0, 10);
//     document.querySelector("#end_date").value = today;
// }

function printPDF(div_ID, pdf_header) {

    document.getElementById('printParentHeader').style.display = 'block';
    document.getElementById('headerDiv').style.display = 'inline-table';
    printJS({printable: div_ID, type: 'html', header: pdf_header,
        headerStyle: 'font-weight: 300px; margin: 40px;' , repeatTableHeader : true, showModal : true,
        ignoreElements: ['goback','printbtnMain','btnTransaction','btnFees','ParentsDiv'], css: 'css/print.css', targetStyles: '*'});
    document.getElementById('printParentHeader').style.display = 'none';
    document.getElementById('headerDiv').style.display = 'none';


}

function printPDFStatement(div_ID, pdf_header) {
    document.getElementById('headerDiv').style.display = 'inline-table';
    printJS({printable: div_ID, type: 'html', header: pdf_header,
        headerStyle: 'font-weight: 300px; margin: 40px;' , repeatTableHeader : true, showModal : true,
        ignoreElements: ['goback','printbtnMain','btnTransaction','btnFees','ParentsDiv'], css: 'css/print.css', targetStyles: '*'});
    document.getElementById('headerDiv').style.display = 'none';


}

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

    let general = new XMLHttpRequest();
    general.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            parentsDataTable();
        }
    };
    general.open("GET", "mysql/general.php?start_date=" + start_date + "&end_date=" + end_date, false);
    general.send();
}

function FamilyStatement(params) {
    window.scrollTo(0, 0);
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
            parentsDataTable();

        }
    };
    FamilyStatementRequest.open("GET", "mysql/general.php?start_date=" + start_date + "&end_date=" + end_date
        + "&familyid=" + familyid, false);
    FamilyStatementRequest.send();
}

function showTransaction() {
    document.getElementById('fee_table').style.display ='none';
    document.getElementById('statementTable').style.display  ='inline';

}
function showFees() {
    document.getElementById('fee_table').style.display ='block';
    document.getElementById('statementTable').style.display  ='none';

}

function showParentsDiv(){
    document.getElementById('headerDiv').style.display = 'inline-table';
    document.getElementById('ParentsDivPrint').style.display ='block';
    printJS({printable: 'ParentsDivPrint', type: 'html' , repeatTableHeader : true, showModal : true,
        css: 'css/print.css'});
    document.getElementById('headerDiv').style.display = 'none';
    document.getElementById('ParentsDivPrint').style.display ='none';
}


function printSortedStudentsFees(table_name) {
    // var table = $('#parentsSmsList').DataTable({});
    //  table.destroy();
    document.getElementById('headerDiv').style.display = 'inline-table';
    printJS({printable: table_name, type: 'html' , repeatTableHeader : true, showModal : true,
        css: 'css/print.css'});
    document.getElementById('headerDiv').style.display = 'none';

    // parentsDataTable();
}


