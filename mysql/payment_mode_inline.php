<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');
$t_date = $_REQUEST['t_date'];
$mode = $_REQUEST['mode'];

$start_date = date('Y-m-01', strtotime($t_date));
$end_date = date('Y-m-t', strtotime($t_date));



// PAYMENT MODE

$payment_mode = "
SELECT ft.transaction_date, SUM(ft.amount) as 'amount', ft.payment_mode mode, s.last_name, g.first_name
FROM finance_transactions ft
INNER JOIN students s on ft.payee_id = s.id
INNER JOIN guardians g ON s.immediate_contact_id = g.id

WHERE ft.finance_type = 'FinanceFee'
  AND STR_TO_DATE(ft.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(ft.transaction_date, '%Y-%m-%d') <= '$end_date'
  AND payment_mode = '$mode'
GROUP BY g.id
";


$totalPayments = $id = $row_id = 0;

//echo $payment_mode;
$result = $conn->query($payment_mode);
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-striped' id='paymentMode'>
            <thead  class=\"bg-green text-white\">
                <tr>
                    <th class='textCenter'><b>SI No.</b></th>
                    <th class='textCenter'><b>Parent</b></th>
                    <th class='textCenter'><b>Amount</b></th>
                </tr>
            </thead>";

    while ($row = $result->fetch_assoc()) {
        $id++;
        echo "<tr>
                <th class='textLeft'>" . ++$row_id . "</th>
                <th class='textLeft'>" . $row['first_name'] . "</th>
                <th class='textRight'>" . number_format((float)$row['amount']) . "</th>
             </tr>";
        $totalPayments += $row['amount'];
    }
    echo "<tr>
                <th colspan='2' class='textLeft bold'>Total</th>
                <th class='textRight bold'>" . number_format((float)$totalPayments) . "</th>
           </tr>";
    echo '</body></table>';

} else {
    echo 'No Data Found! Try another search.';
}
