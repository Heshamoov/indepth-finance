<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

// PAYMENT MODE

$payment_mode = "
SELECT transaction_date, SUM(amount) amount, payment_mode mode
FROM finance_transactions
WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'
GROUP BY DATE_FORMAT(transaction_date, '%Y%m'), finance_transactions.payment_mode
";


$totalPayments = $id = 0;

//echo $payment_mode;
$result = $conn->query($payment_mode);
if ($result->num_rows > 0) {
    echo "<div class='container ' >
<table style='margin-top: 30px!important;' class='table table-bordered table-striped' id='paymentMode'>
            <thead class=\"black text-white\">
                <tr>
                    <th class='textCenter'><b>Month</b></th>
                    <th class='textCenter'><b>Mode</b></th>
                    <th class='textCenter'><b>Amount</b></th>
                </tr>
            </thead>";

    while ($row = $result->fetch_assoc()) {
        $id++;

        echo "<tr >
                <th class='textLeft'>" . date_format(date_create(($row['transaction_date'])), "Y-F") . "</th>
                <th  class='textLeft showinfo' >
                    <a class='showinfo'>" . $row['mode'] . "</a>
                    <a style='float: right' id='toggler_icon' class='flex-column showinfo toggler_icon'><i class=' showinfo fa fa-plus'></i></a>
                    <div style='display: none; padding: 20px; max-height: 200px; overflow: scroll' id='$id' data-date= '" . $row['transaction_date'] . "' data-mode='" . $row['mode'] . "' class='PMD'></div>
                </th>
                
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
