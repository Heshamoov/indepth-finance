<?php
include('../config/db.php');

$sql=" SELECT finance_fees.balance                  balance,
       finance_fees.particular_total         total,
       finance_transactions.amount           amount,
       finance_transactions.payment_mode     mode,
       finance_transactions.transaction_date transaction_date,
       finance_transactions.payment_note     note,
       finance_transactions.receipt_no     receipt,
       finance_transactions.reference_no     reference_no,
       finance_transactions.title     title,
       students.admission_no                 admission_no,
       students.last_name                    student_name,
       finance_fee_collections.name          fee_name
from finance_fees
         inner join students on finance_fees.student_id = students.id
         inner join finance_fee_categories on finance_fees.fee_collection_id = finance_fee_categories.id
         inner join finance_transactions on finance_fees.id = finance_transactions.finance_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
where students.familyid = '$familyid'
  AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date'";

//echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table id='statementTable' class='table table-sm table-striped table-bordered student_table' style='padding: 0px !important;'>";
    echo '<thead>
            <th>Transaction Date</th>
            <th>Receipt #</th>
            <th>Ref #</th>
            <th>Student</th>
            <th>Fee Name</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Balance</th>
            <th>Mode</th>
            <th>Title</th>
            <th>Notes</th>
        </thead>';
    while ($row = $result->fetch_assoc()) {
    echo '<tr>
                <td>' .$row['transaction_date']. '</td>
                <td>' .$row['receipt']. '</td>                
                <td>' .$row['reference_no']. '</td>
                <td>' .$row['student_name']. '</td>
                <td>' .$row['fee_name']. '</td>
                <td>' .$row['total']. '</td>
                <td>' .$row['amount']. '</td>
                <td>' .$row['balance']. '</td>
                <td>' .$row['mode']. '</td>
                <td>' .$row['title']. '</td>
                <td>' .$row['note']. '</td>
                </tr>';
    }
    echo '</table>';
}