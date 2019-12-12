<?php

include ('../config/db.php');

$fromdate = $_REQUEST["fromdate"];
$todate = $_REQUEST["todate"];
// echo $fromdate;
// echo $todate;
// $sql= "
// select fee_invoices.invoice_number, students.last_name, students.admission_no,
//        courses.course_name, batches.name, finance_fee_collections.name,  finance_fees.particular_total,
//        DATE_FORMAT(finance_fee_collections.created_at,'%Y-%m-%d') Invoice_Date,
//        DATE_FORMAT(finance_fee_collections.due_date,'%Y-%m-%d') Due_Date
// FROM courses 
// INNER JOIN batches ON courses.id = batches.course_id
//     INNER JOIN students ON batches.id = students.batch_id
//         INNER JOIN finance_fees ON students.id = finance_fees.student_id
//             INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
//                 INNER JOIN fee_transactions ON finance_fees.id = fee_transactions.finance_fee_id
//                  INNER JOIN fee_invoices ON fee_transactions.id = fee_invoices.fee_id

// ORDER BY courses.course_name, batches.name, students.last_name, Due_Date;
// ";


$general= "
SELECT 
finance_fees.id `F#`, finance_transactions.finance_id `T#`,
finance_fees.student_id,
students.last_name student,
guardians.familyid familyid, 
guardians.first_name parent,
finance_fees.balance,
finance_transactions.amount,
finance_fee_collections.name,
finance_transactions.payee_id, finance_transactions.finance_id,
ROUND(SUM(balance), 0) balance,
ROUND(SUM(amount), 0) paid

FROM `finance_fees`

LEFT JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
LEFT JOIN finance_transactions ON finance_fees.id = finance_transactions.finance_id
INNER JOIN students ON finance_fees.student_id = students.id
INNER JOIN guardians ON students.immediate_contact_id = guardians.id



GROUP BY guardians.familyid
ORDER BY REPLACE(guardians.first_name, ' ','')

";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
        echo "
        	<tr>
        		<th>#</th>
        		<th>Family ID</th>
        		<th>Parent</th>
        		<th>Balance</th>
        		<th>Paid<th>
        	</tr>
        ";
    while ($row = $result->fetch_assoc()) {
        echo "
        	<tr class='w3-hover-green'>
        		<td>" . $rownumber 		 . "</td>
        		<td>" . $row['familyid'] . "</td>
        		<td>" . $row['parent']   . "</td>
        		<td>" . $row['balance']  . "</td>
        		<td>" . $row['paid']     . "</td>
        	</tr>
            <tr>
                <td colspan='5'>
                    <p>HESHAM</p>
                </td>
            </tr>
        ";
        $rownumber++;
    }
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
