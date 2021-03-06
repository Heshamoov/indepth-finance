<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');


$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$table = 't' . $_REQUEST['name'];
$mode = $_REQUEST['mode'];


//echo $start_date . ' => ' . $end_date . '<br>';

$start_date = date('Y-m-d', strtotime($start_date));
$end_date = date('Y-m-d', strtotime($end_date));


// PAYMENT MODE

$payment_mode = "
SELECT ft.transaction_date,
       SUM(ft.amount) 'amount',
       ft.payment_mode 'mode',
       stds.last_name 'student',
       grds.first_name 'parent',
       grds.familyid
FROM finance_transactions ft
    inner join (
        select id, last_name, immediate_contact_id from students
        union
        select former_id, last_name, immediate_contact_id from archived_students
    ) as stds on ft.payee_id = stds.id
    inner join (
    select id, first_name, familyid from guardians
    union
    select former_id, first_name, familyid from archived_guardians
) as grds on stds.immediate_contact_id = grds.id

WHERE ft.finance_type = 'FinanceFee'
  AND STR_TO_DATE(ft.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(ft.transaction_date, '%Y-%m-%d') <= '$end_date'
  AND payment_mode = '$mode'
GROUP BY DATE_FORMAT(ft.transaction_date, '%Y%m'), payment_mode, grds.familyid
ORDER BY transaction_date;
";


$totalPayments = $id = $row_id = 0;

//echo $payment_mode;
$result = $conn->query($payment_mode);
if ($result->num_rows > 0) {
    echo "<td><button type='button' onclick=excel_download($table) class='btn btn-primary btn-sm noExl' title='Download as Excel'><i class='fas fa-download'></i></button></td>";
    echo "<table class='table table-sm table-hover' id='$table' style='font-weight: normal;'>
                <tr class='table-light'>
                    <td colspan='5' class='textCenter bold'>Payments as $mode from " . date('d-m-Y', strtotime($start_date)) . " to " . date('d-m-Y', strtotime($end_date)) . "</td>
                </tr>
                <tr>
                    <th class='textCenter'>No.</th>
                    <th class='textCenter'>Family ID</th>
                    <th class='textCenter'>Parent</th>
                    <th class='textCenter'>Date</th>
                    <th class='textCenter'>Amount</th>
                </tr>
            ";

    while ($row = $result->fetch_assoc()) {
        $id++;
        echo "<tr>
                <td class='textLeft'>" . ++$row_id . "</td>
                <td class='textLeft'>" . $row['familyid'] . "</td>
                <td class='textLeft'>" . $row['parent'] . "</td>
                <td class='textLeft'>" . $row['transaction_date'] . "</td>
                <td class='textRight'>" . number_format((float)$row['amount'], 2) . "</td>
             </tr>";
        $totalPayments += $row['amount'];
    }
    echo "<tr class='table-light'>
                <th colspan='4 class='text-center'>Total</th>
                <th class='textRight'>" . number_format((float)$totalPayments, 2) . "</th>
           </tr>";
    echo '</table>';
} else {
    echo 'No Data Found! Try another search.';
}
