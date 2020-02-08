<?php

include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

$payment_mode = "
SELECT SUM(amount) amount, payment_mode mode
FROM finance_transactions

WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'

GROUP BY finance_transactions.payment_mode;
";

$payment_mode_total = "
SELECT SUM(amount) amount, payment_mode mode
FROM finance_transactions

WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'
";


$totalPayments = 0;
$result = $conn->query($payment_mode_total);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalPayments = $row['amount'];
    }
}else {
    echo 'No Data Found! Try another search.';
}

class Payment_Mode {
    public function __construct($mode, $amount, $total)
    {
        $this->mode = $mode;
        $this->amount = $amount;
        $this->total = $total;
    }

    public function print_payments(){
        echo " <tr>
                    <th class='textLeft bold'>" . $this->mode . "</th>
                    <th class='textRight bold'>" . number_format((float)$this->amount) . "</th>
                    <th class='textRight bold'>" . round(($this->amount / $this->total) * 100, 2) . '%</th>
              </tr>';
    }
}
//echo $payment_mode;
$result = $conn->query($payment_mode);
if ($result->num_rows > 0) {
    echo '<h4><u>Payment Mode</u></h4>';
    echo "<table class='table table-bordered table-striped table-hover' id='paymentMode'>
            <thead class=\"black text-white\">
                <tr>
                    <th class='textCenter'><b>Mode</b></th>
                    <th class='textCenter'><b>Amount</b></th>
                    <th class='textCenter'><b>%</b></th>
                </tr>
            </thead>";

    $paymentsArray = array();
    while ($row = $result->fetch_assoc()) {

        if (stripos($row['mode'], 'card') !== false) {
            $transaction = new Payment_Mode('Credit Card', $row['amount'], $totalPayments);
        }
        elseif (stripos($row['mode'], 'cash') !== false) {
            $transaction = new Payment_Mode('Cash', $row['amount'], $totalPayments);
        }
        elseif (stripos($row['mode'], 'cheque') !== false) {
            $transaction = new Payment_Mode('Cheque', $row['amount'], $totalPayments);
        }
        elseif (stripos($row['mode'], 'online') !== false) {
            $transaction = new Payment_Mode('Online', $row['amount'], $totalPayments);
        }
        else {
            $transaction = new Payment_Mode('Other', $row['amount'], $totalPayments);
        }

        $pushed = false;
        foreach ($paymentsArray as $t) {
            if ($t->mode === $transaction->mode) {
                $t->amount += $transaction->amount;
                $pushed = true;
            }
        }
        if (!$pushed) {
            $paymentsArray[] = $transaction;
        }
    }

    function tcmp($a, $b)
    {
        return strcmp($a->mode, $b->mode);
    }
    uasort($paymentsArray, 'tcmp');
    echo '<tbody>';
    foreach ($paymentsArray as $t) {
        $t->print_payments();
    }
} else {
    echo 'No Data Found! Try another search.';
}

$payment_mode_total = "
SELECT SUM(amount) amount, payment_mode mode
FROM finance_transactions

WHERE finance_transactions.finance_type = 'FinanceFee'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_transactions.transaction_date, '%Y-%m-%d') <= '$end_date'
";



$result = $conn->query($payment_mode_total);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr>
                <th class='textLeft bold'>Total</th>
                <th class='textRight'><strong>" . number_format((float) $row['amount']) . '</strong></th>
                <th  class="textCenter"> - </th></tr>';
    }
    echo '</body></table>';
} else {
    echo 'No Data Found! Try another search.';
}
