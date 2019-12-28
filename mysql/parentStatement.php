<?php
include('../config/db.php');

$sql = " SELECT finance_fees.balance                  balance,
       finance_fees.particular_total         total,
       finance_transactions.amount           amount,
       finance_transactions.payment_mode     mode,
       finance_transactions.transaction_date transaction_date,
       finance_transactions.payment_note     note,
       finance_transactions.reference_no     reference_no,
       finance_transactions.title     title,
       finance_fee_discounts.discount_amount discount,
       students.admission_no                 admission_no,
       students.last_name                    student_name,
       finance_fee_collections.name          fee_name,
       fee_invoices.invoice_number           invoice,
       transaction_receipts.receipt_number   receipt

FROM guardians

         INNER JOIN students ON guardians.familyid = students.familyid
         INNER JOIN finance_fees ON students.id = finance_fees.student_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
         inner join fee_invoices on finance_fees.id = fee_invoices.fee_id
         inner join finance_transactions on finance_fees.id = finance_transactions.finance_id
         inner join finance_transaction_receipt_records on finance_transactions.id = finance_transaction_receipt_records.finance_transaction_id
         inner join  transaction_receipts on finance_transaction_receipt_records.transaction_receipt_id = transaction_receipts.id
         LEFT JOIN finance_fee_discounts ON finance_fees.id = finance_fee_discounts.finance_fee_id


where students.familyid = '$familyid'
AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date'
ORDER BY finance_fee_collections.name,transaction_receipts.receipt_number DESC";

//echo $sql;
$result = $conn->query($sql);
$net_total = $net_discount = $net_paid = $net_balance = 0;
if ($result->num_rows > 0) {
    echo '<h4 align="center" id="transaction_heading"><u>Transaction Statement</u></h4>';
    echo '<a  id="btnFees" style="margin-left:auto; margin-right: 20px" type="button" onclick="window.scrollTo(0, 0);" class="btn btn-sm btn-blue-grey " >View Fees</a>';
    echo "<table id='statementTable' class='table table-sm table-striped table-bordered parent-statement table-hover' style='padding: 0px !important; margin-top: 20px'>";
    echo '<thead class="black  white-text">
            <th scope="col">Transaction Date</th>
            <th scope="col">Invoice #</th>
            <th scope="col">Receipt #</th>
            <th scope="col">Ref #</th>
            <th scope="col">Student</th>
            <th scope="col">Fee Name</th>
            <th scope="col">Total</th>
            <th scope="col">Discount</th>
            <th scope="col">Paid</th>
            <th scope="col">Balance</th>
            <th scope="col">Mode</th>
            <th scope="col">Notes</th>
        </thead>';
    $prev_fee = '';
    $prev_bal = 0;
    $prev_student = 0;
    while ($row = $result->fetch_assoc()) {

        $balance = $row['total'] - $row['amount'] - $row['discount'];
        $total = $row['total'];

        if ($row['fee_name'] === $prev_fee && $row['admission_no'] === $prev_student) {
            $total = $prev_bal;
            $balance = $total - $row['amount']  - $row['discount'];
        }

//        $net_total += $total;
//        $net_paid += $row['amount'];
//        $net_discount += $row['discount'];
//        $net_balance += $balance;

        echo '<tr>
                <td>' . $row['transaction_date'] . '</td>
                <td>' . $row['invoice'] . '</td>                
                <td>' . $row['receipt'] . '</td>                
                <td>' . $row['reference_no'] . '</td>
                <td>' . $row['student_name'] . '</td>
                <td>' . $row['fee_name'] . '</td>
                <td class="textRight">' . (float)$total . '</td>
                <td class="textRight">' . (float)$row['discount'] . '</td>
                <td class="textRight">' . (float)$row['amount'] . '</td>
                <td class="textRight">' . (float)$balance . '</td>
                <td>' . $row['mode'] . '</td>
                <td>' . $row['note'] . '</td>
                </tr>';

        $prev_fee = $row['fee_name'];
        $prev_bal = $balance;
        $prev_student = $row['admission_no'];
    }
//    echo '<tr>
//            <td colspan="6" align="center">Total</td>
//            <td class="textRight">'.$net_total.'</td>
//            <td class="textRight">'.$net_discount.'</td>
//            <td class="textRight">'.$net_paid.'</td>
//            <td class="textRight">'.$net_balance.'</td>
//            <td></td><td></td>
//            </tr>';
    echo '</table>';
}