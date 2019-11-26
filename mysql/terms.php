<?php

include ('../config/db.php');

$fromdate = $_REQUEST["fromdate"];
$todate = $_REQUEST["todate"];

echo "<td>$fromdate</td><td>$todate</td>";
