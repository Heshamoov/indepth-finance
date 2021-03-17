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
    echo "<table class='table table-bordered table-striped' id='$table'>
            <thead  class=\"bg-green text-white\">
                <tr>
                    <th colspan='5' class='textCenter bold'>Payments as $mode from " . date('d-m-Y', strtotime($start_date))." to ".date('d-m-Y', strtotime($end_date))."</th>
                </tr>
                <tr>
                    <th class='textCenter'><b>No.</b></th>
                    <th class='textCenter'><b>Family ID</b></th>
                    <th class='textCenter'><b>Parent</b></th>
                    <th class='textCenter'><b>Date</b></th>
                    <th class='textCenter'><b>Amount</b></th>
                </tr>
            </thead>";

    while ($row = $result->fetch_assoc()) {
        $id++;
        echo "<tr>
                <th class='textLeft'>" . ++$row_id . "</th>
                <th class='textLeft'>" . $row['familyid'] . "</th>
                <th class='textLeft'>" . $row['parent'] . "</th>
                <th class='textLeft'>" . $row['transaction_date'] . "</th>
                <th class='textRight'>" . number_format((float)$row['amount'], 2) . "</th>
             </tr>";
        $totalPayments += $row['amount'];
    }
    echo "<tr>
                <th colspan='4 class='text-center bold'>Total</th>
                <th class='textRight bold'>" . number_format((float)$totalPayments, 2) . "</th>
           </tr>";
    echo '</body></table>';

} else {
    echo 'No Data Found! Try another search.';
}
