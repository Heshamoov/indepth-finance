<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$sql = "
SELECT finance_transactions.id,
       finance_transactions.title,
       finance_transactions.amount,
       finance_transactions.created_at,
       finance_transactions.transaction_date,
       finance_transactions.finance_id,
       finance_transactions.payee_id,
       finance_transactions.receipt_no,
       finance_transactions.payment_mode,
       finance_transactions.payment_note,
       finance_fee_categories.name,
       finance_transactions.user_id,
       users.first_name
FROM finance_transactions
         INNER JOIN finance_fee_categories on finance_transactions.category_id = finance_fee_categories.id
         INNER JOIN users on finance_transactions.payee_id = users.id
WHERE STR_TO_DATE(finance_transactions.created_at, '%Y-%m-%d') = '2019-10-07'
ORDER BY finance_transactions.created_at DESC;
";


$result = $conn->query($sql);

$row_number = 0;
if ($result->num_rows > 0) {

    echo "<table id='trans_table'>
    <thead>
    <tr>
        <th>#</th>
        <th>FEE</th>
        <th>AMOUNT</th>
        <th>PAYMENT</th>
        <th>DESCRIPTION</th>
        <th>NAME</th>
    </tr>
    </thead>
    <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "
            <tr>
                <td>" . ++$row_number . "</td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['amount'] . "</td>
                <td>" . $row['payment_mode'] . "</td>
                <td>" . $row['title'] . "</td>
                <td>" . $row['first_name'] . "</td>
            </tr>
        ";
    }
    echo "</tbody></table >";
}