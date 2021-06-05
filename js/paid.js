function studentsDataTable() {
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // let date = new Date(document.querySelector("#start_date").value);
    // day = date.getDate();
    // month = monthNames[date.getMonth()];
    // year = date.getFullYear();
    // let start_date = day + '-' + month + '-' + year;
    //
    // date = new Date(document.querySelector("#end_date").value);
    // day = date.getDate();
    // month = monthNames[date.getMonth()];
    // year = date.getFullYear();
    // let end_date = day + '-' + month + '-' + year;


    $('#PaidTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy',
            {
                extend: 'excelHtml5',
                title: 'Al Sanawbar School \n Paid Fees Report'
            },
            {
                extend: 'pdfHtml5',
                title: 'Al Sanawbar School \n Paid Fees Report'

            },
            {
                extend: 'csv',
                title: 'Al Sanawbar School \n Paid Fees Report'
            },
            {
                extend: 'print',
                title: '',
                messageTop: ' <h4 align="center">Al Sanawbar School </h4> <h6 align="center"> Paid Fees Report'
            }

        ]
    });
    $('.dataTables_length').addClass('bs-select');
}

function fill_years() {
    let HttpYears = new XMLHttpRequest();
    HttpYears.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('financial_years').innerHTML += this.responseText;
        }
    };
    HttpYears.open("GET", "mysql/financial_years.php", false);
    HttpYears.send();
}

function fill_fees() {
    // let end_date = new Date(document.querySelector("#end_date").value);
    // let start_date = new Date(document.querySelector("#start_date").value);
    let checked = document.querySelectorAll('#financial_years :checked');
    let years = [...checked].map(option => option.value);

    // let day1 = start_date.getDate();
    // let day2 = end_date.getDate();
    //
    // let month1 = start_date.getMonth() + 1;
    // let month2 = end_date.getMonth() + 1;
    //
    // let year1 = start_date.getFullYear();
    // let year2 = end_date.getFullYear();
    //
    // start_date = year1 + '-' + month1 + '-' + day1;
    // end_date = year2 + '-' + month2 + '-' + day2;

    let select = document.getElementById('fees');
    while (select.length > 0) select.remove(0);
    $(select).multiselect('destroy');


    let HttpFees = new XMLHttpRequest();
    HttpFees.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById('fees').innerHTML += this.responseText;
        }
    };
    HttpFees.open("GET", "mysql/fees_particular.php?year=" + years, false);
    HttpFees.send();
    // $("#fees").multiselect('refresh');
    $('#fees').multiselect({
        includeSelectAllOption: true
    });
    $("#fees").multiselect('selectAll', false);
    $("#fees").multiselect('updateButtonText');
}

function search() {
    // let date = new Date(document.querySelector("#start_date").value);
    // day = date.getDate();
    // month = date.getMonth() + 1;
    // year = date.getFullYear();
    // let start_date = year + '-' + month + '-' + day;
    //
    // date = new Date(document.querySelector("#end_date").value);
    // day = date.getDate();
    // month = date.getMonth() + 1;
    // year = date.getFullYear();
    // let end_date = year + '-' + month + '-' + day;

    let checked = document.querySelectorAll('#financial_years :checked');
    let years = [...checked].map(option => option.value);

    checked = document.querySelectorAll('#fees :checked');
    let fees_selected = [...checked].map(option => option.value);

    let type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            studentsDataTable();
        }
    };
    payments.open("GET", "mysql/paid.php?master_ids=" + fees_selected + "&type=" + type + "&years=" + years, false);
    payments.send();
}

$(document).ready(function () {
    fill_years();
    fill_fees();
    search();
});
