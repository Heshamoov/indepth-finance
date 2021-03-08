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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.10.1/css/mdb.min.css" rel="stylesheet">
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.10.1/js/mdb.min.js"></script>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

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

    <script>
        function archived_students_datatable() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            let date = new Date(document.querySelector("#start_date").value);
            let day = date.getDate();
            let month = monthNames[date.getMonth()];
            let year = date.getFullYear();
            let start_date = day + '-' + month + '-' + year;

            date = new Date(document.querySelector("#end_date").value);
            day = date.getDate();
            month = monthNames[date.getMonth()];
            year = date.getFullYear();
            let end_date = day + '-' + month + '-' + year;

            $('#archived_students').DataTable({

                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'excelHtml5',
                        title: 'Al Sanawbar School \n Archived Students with Pending Fees \n (' + start_date + ' to ' + end_date + ')',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Al Sanawbar School \n Archived Students with Pending Fees \n (' + start_date + ' to ' + end_date + ')',
                        className: 'btn btn-primary btn-sm'

                    },
                    {
                        extend: 'csv',
                        title: 'Al Sanawbar School \n Archived Students with Pending Fees \n (' + start_date + ' to ' + end_date + ')',
                        className: 'btn btn-primary btn-sm'
                    },
                    {
                        extend: 'print',
                        title: '',
                        messageTop: ' <h4 align="center">Al Sanawbar School</h4> <h6 align="center"> Archived Students with Pending Fees (' + start_date + ' to ' + end_date + ') </h6>',
                        className: 'btn btn-primary btn-sm'
                    }

                ]
            });
            $('.dataTables_length').addClass('bs-select');
        }

        $(document).ready(function () {
            search();
        });
    </script>

    <title>InDepth Finance</title>
</head>


<body>
<?php include('navbar.php'); ?>
<h6 class="active" style="color:black">STUDENTS TRANSFER CERTIFICATE</h6>
<?php include('uppernav.php'); ?>

<div class="col-sm" style="margin-top: -30px">
    <div id="userInputDiv" class="row">
        <div class="col-sm"></div>
        <div class="col-sm-6">
            <table id="userInputodTable" align="center">
                <thead>
                <tr>
                    <td colspan="3" align="center"><h4>Archived Students with Balance</h4></td>
                </tr>
                <tr>
                    <td><input type="text" data-role="calendarpicker" data-calendar-wide="false"
                               class="w3-input w3-card"
                               data-clear-button="true" data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-input-format="%d-%m-%y" data-format="%d-%B-%Y" value="01-09-2020"
                               id="start_date" onchange="search()"/>

                    </td>
                    <td>&nbsp To &nbsp</td>
                    <td><input data-clear-button="true"
                               data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-calendar-wide="false" class="w3-input w3-card" type="text"
                               data-role="calendarpicker" id="end_date" onchange="search()"
                               data-input-format="%d-%m-%y" data-format="%d-%B-%Y" value="31-08-2021"/>
                    </td>
                </tr>
                </thead>

            </table>
        </div>
        <div class="col-sm"></div>
    </div>
    <div id="result" style="padding-top:10px;"></div>
</div>

<script>
    document.getElementById('navArchived').classList.add('bold');
    document.getElementById('navStudentsTC').classList.add('active');
    document.getElementById('navStudentsTC').classList.add('active-tab');
</script>
<script src="js/students_tc.js"></script>
<script src="js/calender.js"></script>
</body>
</html>