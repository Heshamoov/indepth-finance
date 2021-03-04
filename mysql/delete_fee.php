<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$fee_id = $_REQUEST['fee_id'];

function get_total($id)
{
    include('../config/db.php');

    $get_total = "SELECT * FROM finance_fees WHERE ID = $id;";
    $result = $conn->query($get_total);
    if ($result->num_rows > 0) {
        $total = 0;
        while ($row = $result->fetch_assoc()) {
            $total = $row["particular_total"];
        }
        return number_format($total,2);
    }
}
function get_balance($id)
{
    include('../config/db.php');

    $get_balance = "SELECT * FROM finance_fees WHERE ID = $id;";
    $result = $conn->query($get_balance);
    if ($result->num_rows > 0) {
        $balance = 0;
        while ($row = $result->fetch_assoc()) {
            $balance = $row["balance"];
        }
        return number_format($balance,2);
    }
}


echo "TOTAL = " . get_total($fee_id) . " - BALANCE = " . get_balance($fee_id);

