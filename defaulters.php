<?php
include('config/db.php');
include_once 'functions.php';
session_start();
checkLoggedIn()
?>

<!doctype html>
<html lang="en">
<head>
    <title>InDepth Finance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/metrostyle.css">
    <link rel="icon" href="assets/indepth-logo.png">

    <!--    MD Boostrap styling CDN -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.10.1/css/mdb.min.css" rel="stylesheet">

    <!--MD Bootstrap js-->
    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.10.1/js/mdb.min.js"></script>


    <!--    MULTISELECT-->
    <link href="https://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
          rel="stylesheet" type="text/css"/>
    <script src="https://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
            type="text/javascript"></script>

    <!--    data table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>


    <script src="js/init_fees.js"></script>

    <script>
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
    </script>

    <title>InDepth Finance</title>
</head>

<script>
    $(function () {
        $('#fees').multiselect({includeSelectAllOption: false});
    });
</script>
<body>
<?php include('navbar.php'); ?>
<h6 class="active" style="color:black">FEE BALANCE REPORT</h6>
<?php include('uppernav.php'); ?>

<div id="debug"></div>
<div class="col-sm" style="margin-top: -30px">
    <div id="userInputDiv" class="row">
        <table class="table">
            <thead>
            <tr>
                <th class="text-center">START</th>
                <th class="text-center">END</th>
                <th class="text-center">TYPE</th>
                <th class="text-center">FEE</th>
            </tr>
            <tr>
                <td class="text-center">
                    <input type="text" data-role="calendarpicker" data-calendar-wide="false"
                           class="w3-input w3-card"
                           data-clear-button="true" data-clear-button-icon="<i class='fas fa-times'></i>"
                           data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                           data-input-format="%d-%m-%y" data-format="%d-%B-%Y" value="01-09-2020"
                           id="start_date" onchange="search()"/>

                </td>
                <td class="text-center">
                    <input data-clear-button="true"
                           data-clear-button-icon="<i class='fas fa-times'></i>"
                           data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                           data-calendar-wide="true" class="w3-input w3-card" type="text"
                           data-role="calendarpicker" id="end_date" onchange="search()"
                           data-input-format="%d-%m-%y" data-format="%d %B %Y" value="31-08-2021"/>

                </td>
                <td class="text-center">
                    <select id="type" onchange="search()">
                        <option value="parent">Parent Wise</option>
                        <option value="student">Student Wise</option>
                    </select>
                </td>
                <td class="text-center">
                    <select id="fees" onchange="search()"></select>
                </td>
            </tr>
            </thead>
        </table>
    </div>
    <div id="result"></div>
</div>

<script>
    document.getElementById('navDefaulters').classList.add('active');
    document.getElementById('navDefaulters').classList.add('active-tab');
</script>

<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/calender.js"></script>

</body>
</html>