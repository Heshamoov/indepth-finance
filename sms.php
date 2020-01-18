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
    <link rel="stylesheet" href="css/sms.css">
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

    <script>
        function parentsDataTable() {
            $('#parentsSmsList').DataTable({});
            $('.dataTables_length').addClass('bs-select');
        }

        $(document).ready(function () {
            search();
        });
    </script>

    <title>SMS</title>
</head>
<?php include('navbar.php'); ?>
<h4 style="color:black">Follow-up List</h4>
<?php include('uppernav.php'); ?>
<body>


<div class="col-sm" style="margin-top: -30px">
    <div id="userInputDiv" class="row">
        <div class="col-sm"></div>
        <div class="col-sm-6">
            <table id="userInputTable" align="center">
                <tr>
                    <th><label for="start_date">Start</label></th>
                    <th><label for="end_date">End</label></th>
                    <!--                        <th>Search</th>-->
                <tr>
                    <!--                            <th><input class="w3-input w3-card" type="date" id="start" onchange="search()"-->
                    <!--                                       value="2019-09-01"/></th>-->
                    <th><input data-clear-button="true"
                               data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-calendar-wide="true" class="w3-input w3-card" type="text"
                               data-role="calendarpicker" id="start_date" onchange="search()"
                               data-input-format="%d-%m-%y" data-format="%d %b %Y" value="01-09-2019"/>

                    </th>
                    <th><input data-clear-button="true"
                               data-clear-button-icon="<i class='fas fa-times'></i>"
                               data-calendar-button-icon="<i class='far fa-calendar-alt'></i>"
                               data-calendar-wide="true" class="w3-input w3-card" type="text"
                               data-role="calendarpicker" id="end_date" onchange="search()"
                               data-input-format="%d-%m-%y" data-format="%d %b %Y" value="31-08-2020"/>
                    </th>
                    <th style="float: right; ">
                        <a id='printbtnMain' style='margin-left: 20px;'
                           onclick="printTable();">
                            <span class="fa fa-print" style="font-size: 20px" aria-hidden="true"></span>
                        </a></th>

                </tr>
            </table>
        </div>
        <div class="col-sm"></div>
    </div>
    <div id="result"></div>
</div>
</div> <!--navbar-->
</div> <!--navbar-->


<script>
    document.getElementById('navFollowUp').style.color = '#25221E';
    $(document).ready(function () {
        search();
    });
</script>

<script src="js/sms.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/calender.js"></script>
</body>
</html>