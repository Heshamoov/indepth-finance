function studentsDataTable() {
    const monthNames = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    let date = new Date(document.querySelector("#start_date").value);
    day = date.getDate();
    month = monthNames[date.getMonth()];
    year = date.getFullYear();
    let start_date = day + '-' + month + '-' + year;

    date = new Date(document.querySelector("#end_date").value);
    day = date.getDate();
    month = monthNames[date.getMonth()];
    year = date.getFullYear();
    let end_date = day + '-' + month + '-' + year;


    $('#DefaultersTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy',
            {
                extend: 'excelHtml5',
                title: 'Al Sanawbar School \n Defaulters Students Report \n (' + start_date + ' to ' + end_date + ')'
            },
            {
                extend: 'pdfHtml5',
                title: 'Al Sanawbar School \n Defaulters Students Report \n (' + start_date + ' to ' + end_date + ')',

            },
            {
                extend: 'csv',
                title: 'Al Sanawbar School \n Defaulters Students Report \n (' + start_date + ' to ' + end_date + ')'
            },
            {
                extend: 'print',
                title: '',
                messageTop: ' <h4 align="center">Al Sanawbar School </h4> <h6 align="center"> Defaulters Students Report  (' + start_date + ' to ' + end_date + ') </h6>'
            }

        ]
    });
    $('.dataTables_length').addClass('bs-select');
}

$(document).ready(function () {

    let end_date = new Date(document.querySelector("#end_date").value);
    let start_date = new Date(document.querySelector("#start_date").value);

    day1 = start_date.getDate();
    day2 = end_date.getDate();

    month1 = start_date.getMonth() + 1;
    month2 = end_date.getMonth() + 1;

    year1 = start_date.getFullYear();
    year2 = end_date.getFullYear();

    start_date = year1 + '-' + month1 + '-' + day1;
    end_date = year2 + '-' + month2 + '-' + day2;

    // we call the function
    search();
});



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

    let checked = document.querySelectorAll('#fees :checked');
    let fees_selected = [...checked].map(option => option.value);

    let type = document.getElementById('type').options[document.getElementById('type').selectedIndex].value;

    let payments = new XMLHttpRequest();
    payments.onreadystatechange = function () {
        if (this.readyState === 4) {
            document.getElementById("result").innerHTML = this.responseText;
            studentsDataTable();
        }
    };
    payments.open("GET", "mysql/particular_wise.php?start_date=" + start_date + "&end_date=" + end_date + "&master_ids=" + fees_selected + "&type=" + type, false);
    payments.send();
}