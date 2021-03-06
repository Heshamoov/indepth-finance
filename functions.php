<?php
date_default_timezone_set('Asia/Dubai');

function checkLoggedIn()
{
    if (!isset($_SESSION['login'])) {
        $_SESSION['notloggedin'] = 1;
        header('Location: index.php');
    }
}

function printHeader($table_name, $start_date, $end_date)
{
    echo ' 

        <table id="headerDiv" style="padding-top: 20px; margin-bottom: 30px; display:none">
        <tr>
        <td rowspan="2" class="textLeft" id="logoTd" ><img src="assets/sanawbar-logo.jpeg" width="50" class="logoImage" alt="sanawbar logo"></td> 
        <td class="school-name">AL SANAWBAR SCHOOL</td> 
        <td class="textRight table-name" style="font-size: 20px"><u>' . $table_name . '</u></td>
        </tr>
        <tr>
        <td>Al Manaseer</td>
      
        <td class="textRight">From ' . date("d-M-Y", strtotime($start_date)) . ' To ' . date("d-M-Y", strtotime($end_date)) . ' </td>
        </tr>
        <tr><td colspan="3"><hr style="min-width:100%"></td></tr>
        <tr>
        <td>Issued By: ' . $_SESSION['name'] . '  </td>
        <td></td>
        <td class="textRight">Printed on: ' . date("d-m-Y h:i A") . ' </td>
         </tr>
        </table>';
}

function can_access_finance()
{
    include("config/db.php");
    $finance_access = "
        SELECT * FROM privileges_users pu
            RIGHT JOIN users u on pu.user_id = u.id
            WHERE (pu.privilege_id = 26 AND pu.user_id = $_SESSION[user_id]) or (u.admin = 1  AND u.id = $_SESSION[user_id])
        ";

    $result = $conn->query($finance_access);
    if ($result->num_rows > 0)
        return true;
    else return false;
}


