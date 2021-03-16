<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');
$start_date = $_REQUEST['start_date'];
//echo $start_date;
$start_date = date('Y-m-01', strtotime($start_date));
//$end_date = $_REQUEST['end_date'];
$end_date = date('Y-m-t', strtotime($_REQUEST['end_date']));

echo '<h4  style="margin-top: 20px; font-size: 20px" class="text-center">PAYMENTS FROM '.date_format(date_create(($start_date)), "d-F-Y").' to '.date_format(date_create(($end_date)), "d-F-Y").'</h4>';

// PAYMENT MODE


$rowspan_sql = "SELECT DATE_FORMAT(transaction_date,'%Y-%m') month, count(distinct (payment_mode)) rowspan, SUM(amount) amount, payment_mode mode
FROM finance_transactions
WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'
GROUP BY DATE_FORMAT(transaction_date, '%Y%m');";

$rowspan = [];
$rowspan_result = $conn->query($rowspan_sql);
if ($rowspan_result->num_rows > 0) {
    while ($row = $rowspan_result->fetch_assoc()) {
        $rowspan[$row['month']] = $row['rowspan'];
    }
}


$payment_mode = "
SELECT transaction_date, DATE_FORMAT(transaction_date,'%Y-%m') month, SUM(amount) amount, payment_mode mode
FROM finance_transactions
WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'
GROUP BY DATE_FORMAT(transaction_date, '%Y%m'), finance_transactions.payment_mode
";


$totalPayments = $id = 0;

//echo $payment_mode;
//echo "<br>";
//echo $rowspan_sql;
$result = $conn->query($payment_mode);
if ($result->num_rows > 0) {
    echo "<button type='button' id='download' class='btn btn-primary btn-sm' title='Download as Excel'><i class='fas fa-download'></i></button>";

    echo "
<table style='margin-top: 10px!important;' class='table table-bordered' id='paymentMode'>
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
        $rowspan_no = 0;

        if ($first_row) {
            $first_row = false;
            $new_month = date_format(date_create(($row['transaction_date'])), "Y-F");
            $rowspan_no = $rowspan[$row['month']];
//            echo "<tr><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";
        }

        $id++;

        if ($new_month != date_format(date_create(($row['transaction_date'])), "Y-F")) {
            echo "<tr><th colspan='2' class='bold text-center'>Total payments in " . $new_month . "</th><th class='bold text-right'>" . number_format((float)$total_month_income,2) . "</th></tr>";
            $new_month = date_format(date_create(($row['transaction_date'])), "Y-F");
            echo "<tr style='background-color: white; border-bottom: 2px black '><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";
            $total_month_income = 0;
            $rowspan_no = $rowspan[$row['month']];
        }

        echo "<tr>";

        if ($rowspan_no != 0)
            echo " <th rowspan='$rowspan_no' class='textLeft text-center align-middle'>" . date_format(date_create(($row['transaction_date'])), "Y-F") . "</th>";

        echo "<th class='textLeft'>
                <div id='accordionExample'>
                    <div class='main-div showinfo'>                                
                        <button onclick='test($id)' type='button' style='background: none; border: none!important' data-toggle='collapse' data-target='#collapse$id'>
                            <i class='fa fa-plus'></i> " . $row['mode'] . "</button>
                        </div>
                        <div id='collapse$id' class='collapse' aria-labelledby='headingOne' data-parent='#accordionExample'>
                            <div class='card-body'>
                                <div style='max-height: 200px; overflow: scroll' id='$id' data-date= '" . $row['transaction_date'] . "' data-mode='" . $row['mode'] . "' class='PMD'></div>
                            </div>
                        </div>
                    </div>
                </div>                
            </th>
            <th class='textRight'>" . number_format((float)$row['amount'],2) . "</th>
         </tr>";

        $total_month_income += $row['amount'];
        $totalPayments += $row['amount'];


    }
    echo "<tr><th colspan='2' class='bold text-center'>Total payments in " . $new_month . "</th><th class='bold text-right'>" . number_format((float)$total_month_income,2) . "</th></tr>";


    echo "<tr style='background-color: white; border-bottom: 2px black '><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";

    echo "<tr class='bg-lightYellow'>
                <th colspan='2' class='textLeft text-center bold '>Total</th>
                <th class='textRight bold'>" . number_format((float)$totalPayments,2) . "</th>
           </tr>";
    echo '</body></table>';

} else {
    echo 'No Data Found! Try another search.';
}
