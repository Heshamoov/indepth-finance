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

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            parentsDataTable();
        }
    };
    payments.open("GET", "mysql/payment_mode.php?start_date=" + start_date + "&end_date=" + end_date, false);
    payments.send();
}

// function details($id) {
//     document.getElementById($id).innerHTML += '<tr><td colspan="3"><div><h3>Hello</h3></div></td></tr>';
// }


$(document).ready(function () {
    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function () {
        $(this).prev(".main-div").find(".fa").addClass("fa-minus").removeClass("fa-plus");
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse").on('show.bs.collapse', function () {
        $(this).prev(".main-div").find(".fa").removeClass("fa-plus").addClass("fa-minus");
    }).on('hide.bs.collapse', function () {
        $(this).prev(".main-div").find(".fa").removeClass("fa-minus").addClass("fa-plus");
    });
});


$(function () {
    $('.showinfo').click(function (e) { alert('df');
        e.preventDefault();
        id = $(this).closest('th').find(".PMD")[0].id;
        t_date = $(this).closest('th').find(".PMD")[0].getAttribute("data-date");
        t_mode = $(this).closest('th').find(".PMD")[0].getAttribute("data-mode");

        $table_data = "<table class='table table-dark table-sm padding: 0px'>" +
            "<th>" +
            "<td>" + id + "</td>" +
            "<td>" + t_date + "</td>" +
            "<td>" + t_mode + "</td>" +
            "</th>" +
            "</table>";
        $(this).closest('th').find(".PMD")[0].innerHTML = $table_data;
        $div_data = $(this).closest('th').find(".PMD")[0];

        let payments = new XMLHttpRequest();
        payments.onreadystatechange = function () {
            if (this.readyState === 4) {
                $div_data.innerHTML = this.responseText;
            }
        };
        payments.open("GET", "mysql/payment_mode_inline.php?t_date=" + t_date + "&mode=" + t_mode, false);
        payments.send();


    });
});

function showinfo(id) {
    let x = id;

}


function excel_current_page_download() {
    $("#ParentsTable").table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "Finance",
        filename: "Parents-List", //do not include extension
        fileext: ".xls"// file extension
        // preserveColors:true
    });
}

function excel_download() {
    $("#ParentsTablePrint").table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "Finance",
        filename: "Parents-List", //do not include extension
        fileext: ".xls"// file extension
        // preserveColors:true
    });
}