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



    <!--    data table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>


    <!-- Exporting table as excel -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/table2excel.js"></script>

    <title>InDepth Finance</title>
</head>
<script src="js/payment_mode.js"></script>

<body>
<?php include('navbar.php'); ?>
<h6 class="active" style="color:black">PAYMENT MODE MONTHLY REPORT</h6>
<?php include('uppernav.php'); ?>

<!--<div class="col" style="margin-top: -30px">-->
    <div class="row " style="margin-top: -20px" >
            <div class="col-xl-6" class="textRight"><input id="start_date" type="date" onchange="search()" value="2020-09-01"/></div>
<!--            <div class="col-sm-1" align="center">&nbsp To &nbsp</div>-->
<!--                <div class="col">To</div>-->
            <div class="col-xl-6" align="textRight">To &nbsp&nbsp&nbsp &nbsp&nbsp   <input id="end_date" type="date" onchange="search()" value="2021-08-31"/></div>

    </div>
    <div id="result"></div>
<!--</div>-->

<script>
    document.getElementById('navPaymentMode').classList.add('active');
    document.getElementById('navPaymentMode').classList.add('active-tab');
</script>

<script src="js/bootstrap.min.js"></script>
<script src="js/calender.js"></script>
</body>
</html>