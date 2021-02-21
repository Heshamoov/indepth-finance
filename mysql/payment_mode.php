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
    echo "
<table style='margin-top: 30px!important;' class='table table-light table-bordered table-striped' id='paymentMode'>
            <thead class=\"bg-green text-white\">
                <tr>
                    <th class='textCenter' style='width: 15%;'><b>Month</b></th>
                    <th class='textCenter'><b>Mode</b></th>
                    <th class='textCenter' style='width: 15%;'><b>Amount</b></th>
                </tr>
            </thead>";

    $new_month = '';
    $total_month_income = 0;
    $first_row = true;
    while ($row = $result->fetch_assoc()) {
        if ($first_row) {
            $first_row = false;
            $new_month = date_format(date_create(($row['transaction_date'])), "Y-F");
//            echo "<tr><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";
        }

        $id++;

        if ($new_month != date_format(date_create(($row['transaction_date'])), "Y-F")) {
            echo "<tr><th colspan='2' class='bold text-center'>TOTAL income in " . $new_month . "</th><th class='bold text-right'>" . number_format((float)$total_month_income) . "</th></tr>";

            $new_month = date_format(date_create(($row['transaction_date'])), "Y-F");
            echo "<tr><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";
            $total_month_income = 0;
        }


        echo "<tr>

                <th class='textLeft'>" . date_format(date_create(($row['transaction_date'])), "Y-F") . "</th>
                <th  class='textLeft'>
                
                <div>
                    <div class='accordion' id='accordionExample'>
                            <div class='main-div'>                                
                                <button type='button' style='background: none; border: none!important' class='  showinfo' data-toggle='collapse' data-target='#collapse$id'>
                                <i class='fa fa-plus'></i> " . $row['mode'] . "</button>
                            </div>
                            <div id='collapse$id' class='collapse' aria-labelledby='headingOne' data-parent='#accordionExample'>
                                <div class='card-body'>
                                    <div style='max-height: 200px; overflow: scroll' id='$id' 
                                    data-date= '" . $row['transaction_date'] . "' data-mode='" . $row['mode'] . "' class='PMD'></div>
                                </div>
                            </div>
                        </div>
                    
                </div>
                </th>
                
                <th class='textRight'>" . number_format((float)$row['amount']) . "</th>
             </tr>";
        $total_month_income += $row['amount'];
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
