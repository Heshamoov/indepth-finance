<?php
date_default_timezone_set('Asia/Dubai');
include_once  '../functions.php';
include('../config/db.php');

$sql = "SELECT finance_fees.balance                  balance,
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
         INNER JOIN finance_fees ON students.id = finance_fees.student_id AND s.batch_id = ff.batch_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
         inner join fee_invoices on finance_fees.id = fee_invoices.fee_id
         inner join finance_transactions on finance_fees.id = finance_transactions.finance_id
         inner join finance_transaction_receipt_records on finance_transactions.id = finance_transaction_receipt_records.finance_transaction_id
         inner join  transaction_receipts on finance_transaction_receipt_records.transaction_receipt_id = transaction_receipts.id
         LEFT JOIN finance_fee_discounts ON finance_fees.id = finance_fee_discounts.finance_fee_id


where students.familyid = '$familyid'
AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date'
ORDER BY finance_fee_collections.name,transaction_receipts.receipt_number DESC";


$sql = "
SELECT ft.id,
       ft.payee_id,
       ft.transaction_date,
       ft.cheque_date,
       ft.bank_name,
       ft.amount,
       ft.payment_mode,
       ft.reference_no,
       ffc.due_date,
       ffp.is_reregistration,
       ff.balance                            balance,
       ff.particular_total                   total,
       ft.payment_note                       note,
       ft.title                              title,
       finance_fee_discounts.discount_amount discount,
       s.admission_no                        admission_no,
       SUBSTRING_INDEX(s.last_name, ' ', 1 ) student_name,
       ffc.name                              fee_name,
       fee_invoices.invoice_number           invoice,
       transaction_receipts.receipt_number   receipt
FROM finance_transactions ft
         INNER JOIN students s on ft.payee_id = s.id
         INNER JOIN finance_fees ff On ft.finance_id = ff.id AND s.batch_id = ff.batch_id
         INNER JOIN fee_invoices on ff.id = fee_invoices.fee_id
         LEFT JOIN finance_transaction_receipt_records
                   on ft.id = finance_transaction_receipt_records.finance_transaction_id
         LEFT JOIN transaction_receipts
                   on finance_transaction_receipt_records.transaction_receipt_id = transaction_receipts.id
         LEFT JOIN finance_fee_discounts ON ff.id = finance_fee_discounts.finance_fee_id
         INNER JOIN finance_fee_collections ffc ON ff.fee_collection_id = ffc.id
         INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
         INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                   (
                       (ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                       (ffp.receiver_id = s.student_category_id and
                        ffp.receiver_type = 'StudentCategory' and
                        ffp.batch_id = ff.batch_id) or
                       (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                   )
WHERE s.familyid = '$familyid' AND ft.transaction_date between '$start_date' AND '$end_date'
ORDER BY s.last_name, ft.transaction_date
";

//echo $sql;
$result = $conn->query($sql);
$net_total = $net_discount = $net_paid = $net_balance = 0;
if ($result->num_rows > 0) {
    echo '<h4 align="center" id="transaction_heading" class="bold"><u>Payment Statement</u></h4>';
    echo '<a  id="btnFees" style="margin-left:auto; margin-right: 20px" type="button" onclick="window.scrollTo(0, 0);" class="btn btn-sm btn-blue-grey " >View Fees</a>';
    echo "<table id='statementTable' class='table table-sm table-striped table-hover table-bordered student_table' style='padding: 0px !important; margin-top: 20px'>";
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

        echo '<tr>
                <td>' . $row['transaction_date'] . '</td>
                <td>' . $row['invoice'] . '</td>                
                <td>' . $row['receipt'] . '</td>                
                <td>' . $row['reference_no'] . '</td>
                <td>' . $row['student_name'] . '</td>
                <td>' . $row['fee_name'] . '</td>
                <td class="textRight">' . number_format((float)$total) . '</td>
                <td class="textRight">' . number_format((float)$row['discount'] ). '</td>
                <td class="textRight">' . number_format((float)$row['amount'] ). '</td>
                <td class="textRight">' . number_format((float)$balance) . '</td>
                <td>' . $row['payment_mode'] . '</td>
                <td>' . $row['note'] . '</td>
                </tr>';

        $prev_fee = $row['fee_name'];
        $prev_bal = $balance;
        $prev_student = $row['admission_no'];
    }
    echo '</table>';
}