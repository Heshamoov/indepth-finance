<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

$get_years = "SELECT id, name FROM financial_years";

$result = $conn->query($get_years);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result))
        echo "<option value='$row[id]'>" . $row['name'] . "</option>";

}
$conn->close();