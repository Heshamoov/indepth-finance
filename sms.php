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

    <title>InDepth Finance</title>
</head>
<body>

<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar" class="active" style="background-color: darkred!important;">
        <h2><a href="finance.php" class="logo"><img src="assets/indepth-logo.jpg" width="60" class="logoImage"></a></h2>
        <ul class="list-unstyled components mb-5">
            <li class="active">
                <a href="dashboard.php"><span class="fa fa-home"></span> Home</a>
            </li>
            <li>
                <a href="sms.php"><span class="fa fa-sms"></span>SMS</a>
            </li>
            <li>
                <a href="#"><span class="fa fa-sticky-note"></span> Ledger</a>
            </li>
            <li>
                <a href="#"><span class="fa fa-cogs"></span> Services</a>
            </li>
            <li>
                <a href="#"><span class="fa fa-paper-plane"></span> Messages</a>
            </li>
        </ul>

        <div class="footer">
            <p>
                Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                All rights reserved by <a href="https://indepth.ae" target="_blank"
                                          style="color: black; font-weight: bold">INDEPTH</a>
            </p>
        </div>
    </nav>

    <!-- Page Content  -->
    <div id="content" class="p-4 p-md-3">
        <nav class="navbar navbar-expand-lg navbar-light btn-sm btn-outline-light">
            <div class="container-fluid">

                <button type="button" id="sidebarCollapse" class="btn btn-sm " style="background-color: darkred">
                    <i class="fa fa-bars" style="color: white;"></i>
                    <span class="sr-only">Toggle Menu</span>
                </button>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto btn-sm " style="background-color: darkred"
                        type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars" style="color: white;"></i>
                </button>
                <h4 style="color:black">INDEPTH FINANCE</h4>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="finance.php">Student Fees</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ledgers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-sm btn-danger" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="col-sm" style="margin-top: -30px">
            <div id="userInputDiv" class="row">
                <div class="col-sm"></div>
                <div class="col-sm-6">
                    <table id="userInputTable" align="center">
                        <tr>
                            <th><label for="star_date">Start</label></th>
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

                            <!--                            <th><input class="w3-input w3-card" type="date" id="end" onkeyup="search()"-->
                            <!--                                       value="2019-12-22"/>-->
                            <!--                            </th>-->
                            <th style="float: right;">
                                <!--                            <button class="btn btn-sm aqua-gradient" id="search" onclick="search()" accesskey="q">-->
                                <!--                                Overall Report-->
                                <!--                            </button>-->
                                <a id='printbtnMain' style='margin-left: 20px;'
                                   onclick="printJS({printable: 'result', type: 'html', header: 'Fees Details',
                headerStyle: 'font-weight: 300px; margin: 40px;' , repeatTableHeader : true, showModal : true,
                ignoreElements: ['goback','printbtnMain','btnTransaction','btnFees'], targetStyles: '*'})">
                                    <span class="fa fa-print" style="font-size: 20px" aria-hidden="true"></span>
                                </a></th>

                        </tr>
                    </table>
                </div>
                <div class="col-sm"></div>
            </div>
            <div id="result"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        search();
    });
</script>
<script src="js/sms.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main-finance.js"></script>
<script src="js/calender.js"></script>
</body>
</html>