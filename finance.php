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

    <!--    data table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <!--    print.js-->
    <script type="text/javascript" charset="utf8" src="js/print.min.js"></script>
    <link rel="stylesheet" href="css/print.min.css">

    <!-- Exporting table as excel -->
<!--    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <script src="js/table2excel.js"></script>



    <script>
        function parentsDataTable() {
            $('#ParentsTable').DataTable({});
            $('.dataTables_length').addClass('bs-select');
        }

        $(document).ready(function () {
            // we call the function
            search();
        });
    </script>

    <title>InDepth Finance</title>
</head>


<body>
<?php include('navbar.php'); ?>
<h4 style="color:black">STUDENT FEES</h4>
<?php include('uppernav.php'); ?>

<div class="col-sm" style="margin-top: -30px">
    <div id="userInputDiv" class="row">
        <div class="col-sm"></div>
        <div class="col-sm-6">
            <table id="userInputTable" align="center">
                <thead>
                <tr>
                    <td><input data-clear-button="true"
                               data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-calendar-wide="true" class="w3-input w3-card" type="text"
                               data-role="calendarpicker" id="start_date" onchange="search()"
                               data-input-format="%d-%m-%y" data-format="%d %B %Y" value="01-09-2020"/>

                    </td>
                    <td>&nbsp To &nbsp</td>
                    <td><input data-clear-button="true"
                               data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-calendar-wide="true" class="w3-input w3-card" type="text"
                               data-role="calendarpicker" id="end_date" onchange="search()"
                               data-input-format="%d-%m-%y" data-format="%d %B %Y" value="31-08-2021"/>
                    </td>

                </tr>
                </thead>

            </table>
        </div>
        <div class="col-sm"></div>
    </div>
    <div id="result" style="margin-top: -30px"></div>
</div>

<script>
    document.getElementById('navStudentFees').classList.add('active');
    document.getElementById('navStudentFees').classList.add('active-tab');
</script>
<script src="js/index.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/calender.js"></script>
</body>
</html>