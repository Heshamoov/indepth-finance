<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$id = $_REQUEST['id'];
$status = $_REQUEST['status'];


$check_if_record_exists = "select * from student_tc where former_id = $id";
$result = $conn->query($check_if_record_exists);
if ($result->num_rows > 0) {
    if ($conn->query("UPDATE student_tc set took_tc = $status WHERE former_id = $id") === TRUE) {
        echo 'Updated';
        echo "<input type=text data-role=calendarpicker data-calendar-wide=false
                               class='w3-input w3-card' data-clear-button='true' 
                               data-input-format='%d-%m-%y' data-format='%d-%B-%Y' value='01-09-2020'
                               id='leaving_date'/>";
    } else {
        echo "Error: <br>" . $conn->error;
    }

} else {
    $insert_tc = "INSERT INTO student_tc (former_id, took_tc) values ($id,$status);";
    if ($conn->query($insert_tc) === TRUE)
        echo 'inserted';
    else
        echo "Error: <br>" . $conn->error;
}


