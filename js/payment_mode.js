function search() {
    let start_date = new Date(document.querySelector("#start_date").value);
    let day = start_date.getDate();
    let month = start_date.getMonth() + 1;
    let year = start_date.getFullYear();
    start_date = year + '-' + month + '-' + day;

    let end_date = new Date(document.querySelector("#end_date").value);
    day = end_date.getDate();
    month = end_date.getMonth() + 1;
    year = end_date.getFullYear();
    end_date = year + '-' + month + '-' + day;

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
        }
    };
    payments.open("GET", "mysql/payment_mode.php?start_date=" + start_date + "&end_date=" + end_date, false);
    payments.send();

    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function () {
        $(this).prev(".main-div").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        //alert('show-hide');
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse").on('show.bs.collapse', function () {
        $(this).prev(".main-div").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        //alert('show');
    }).on('hide.bs.collapse', function () {
        $(this).prev(".main-div").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        //alert('hide');
    });
}


window.onload = function (){
    search();
}

function test(id) {
    let start_date = document.getElementById(id).getAttribute("data-startdate");
    let end_date = document.getElementById(id).getAttribute("data-enddate");

    let t_mode = document.getElementById(id).getAttribute("data-mode");

    let table_data = "<table class='table table-dark table-sm padding: 0px'>" +
        "<th>" +
        "<td>" + id + "</td>" +
        "<td>" + start_date + "</td>" +
        "<td>" + end_date + "</td>" +
        "<td>" + t_mode + "</td>" +
        "</th>" +
        "</table>";
    document.getElementById(id).innerHTML = table_data;
    let div_data = document.getElementById(id);

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            div_data.innerHTML = this.responseText;
        }
    };

    payments.open("GET", "mysql/payment_mode_inline.php?start_date=" + start_date + "&end_date=" + end_date + "&name=" + id + "&mode=" + t_mode, false);
    payments.send();
}

$(document).ready(function () {
    $('.showinfo').click(function (e) {
        e.preventDefault();
        id = $(this).closest('th').find(".PMD")[0].id;
        let start_date = $(this).closest('th').find(".PMD")[0].getAttribute("data-startdate");
        let end_date = $(this).closest('th').find(".PMD")[0].getAttribute("data-enddate");
        let t_mode = $(this).closest('th').find(".PMD")[0].getAttribute("data-mode");

        ////alert(t_date);

        $table_data = "<table class='table table-dark table-sm padding: 0px'>" +
            "<th>" +
            "<td>" + id + "</td>" +
            "<td>" + start_date + "</td>" +
            "<td>" + end_date + "</td>" +
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
        payments.open("GET", "mysql/payment_mode_inline.php?start_date=" + start_date + "&end_date=" + end_date + "&name=" + id + "&mode=" + t_mode, false);
        payments.send();
    });


});
$("#download").click(function () {

});

function excel_download(id) {
    $(id).table2excel({
        // exclude CSS class
        exclude: ".noExl",
        name: "Payment Mode",
        filename: "PaymentMode", //do not include extension
        fileext: ".xls",// file extension
    });
}