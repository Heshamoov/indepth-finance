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

    <script src="https://kit.fontawesome.com/88009e5251.js" crossorigin="anonymous"></script>

    <!--    data table-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <!--    print.js-->
    <script type="text/javascript" charset="utf8" src="js/print.min.js"></script>
    <link rel="stylesheet" href="css/print.min.css">

    <script>
        $(document).ready(function () {
            // Icon Color
            document.getElementById('daybook').style.color = '#25221E';

            // todays Date
            let today = new Date().toISOString().substr(0, 10);
            document.getElementById("todays_date").innerText = today + " - ";
            // document.getElementById("out").innerHTML = "Today " + new Date().toUTCString();
        });
    </script>
    <script>
        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('todays_time').innerHTML =
                h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i
            }
            ;  // add zero in front of numbers < 10
            return i;
        }
    </script>
</head>

<body onload="startTime()">

<?php include('navbar.php'); ?>
<h4 style="color:black">Daybook</h4>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="nav navbar-nav ml-auto">
        <li class="nav-item ">
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

<div class="container">
    <div class="row todays_date">
        <h4 id="todays_date"></h4>
        <h4 id="todays_time"></h4>
    </div>
</div>

<div class="row todays_transactions">
    <table class="table table-hover">
        <thead class="black white-text">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Payment Mode</th>
            <th scope="col">Amount</th>
            <th scope="col">Balance</th>
            <th scope="col">Grade</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
        </tr>
        </tbody>
    </table>
</div>
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