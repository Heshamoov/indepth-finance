<?php

include('../config/db.php');

$start_date = $_REQUEST["start_date"];
$end_date = $_REQUEST["end_date"];

$transactions = "
SELECT payment_mode mode, ROUND(SUM(amount),0) amount
FROM guardians
    
INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
INNER JOIN finance_transactions ON finance_fees.id = finance_transactions.finance_id

WHERE
STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'

GROUP BY payment_mode 
";
class Transaction {
    function __construct($mode, $amount)
    {
        $this->mode = $mode;
        $this->amount = $amount;
    }

    function print_transactions(){
        echo " <tr>
                <th class='textLeft'><strong>" . $this->mode . "</strong></th>
                <th class='textRight'><strong>" . $this->amount . '</strong></th>
              </tr>';
    }
}
//echo $transactions;
$result = $conn->query($transactions);
if ($result->num_rows > 0) {
    echo '<h4><u>Payment Mode</u></h4>';
    echo "<table class='table table-bordered table-striped table-hover' id='paymentMode'>
            <thead>
                <tr>
                    <th class='textLeft'><b>Mode</b></th>
                    <th class='textLeft'><b>Amount</b></th>
                </tr>
            </thead>";

    $transactionsArray = array();
    while ($row = $result->fetch_assoc()) {

        if (strstr(strtolower($row['mode']),'card'))
            $transaction = new Transaction('Card', $row['amount']);
        elseif (strstr(strtolower($row['mode']),'cash'))
            $transaction = new Transaction('Cash', $row['amount']);
        elseif (strstr(strtolower($row['mode']),'cheque'))
            $transaction = new Transaction('Cheque', $row['amount']);
        elseif (strstr(strtolower($row['mode']),'online'))
            $transaction = new Transaction('Online', $row['amount']);
        else
            $transaction = new Transaction('Other', $row['amount']);

        $pushed = false;
        foreach ($transactionsArray as $t) {
            if ($t->mode == $transaction->mode) {
                $t->amount += $transaction->amount;
                $pushed = true;
            }
        }
        if (!$pushed)
            array_push($transactionsArray, $transaction);
    }

    function tcmp($a, $b)
    {
        return strcmp($a->mode, $b->mode);
    }
    uasort($transactionsArray, "tcmp");
    echo '<tbody>';
    foreach ($transactionsArray as $t) {
        $t->print_transactions();
    }
} else {
    echo 'No Data Found! Try another search.';
}

$totalTransactions = "
SELECT payment_mode mode, ROUND(SUM(amount), 0) amount
FROM guardians
         INNER JOIN students ON guardians.familyid = students.familyid
         INNER JOIN finance_fees ON students.id = finance_fees.student_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
         INNER JOIN finance_transactions ON finance_fees.id = finance_transactions.finance_id

WHERE STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date';
";

//echo $totalTransactions;
$result = $conn->query($totalTransactions);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr>
                <th class='textLeft'><strong>Total</strong></th>
                <th class='textRight'><strong>" . $row['amount'] . '</strong></th>
              </tr>';
    }
    echo '</body></table>';
} else {
    echo 'No Data Found! Try another search.';
}
