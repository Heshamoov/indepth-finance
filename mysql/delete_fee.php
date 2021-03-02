<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$fee_id = $_REQUEST['fee_id'];
echo $fee_id;

$delete_fee_sql = "
    DELETE FROM finance_fees WHERE id = $fee_id;
";

