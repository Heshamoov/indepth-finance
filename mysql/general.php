<?php

include ('../config/db.php');

$start = $_REQUEST["start"];
$end = $_REQUEST["end"];

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
guardians.first_name  parent,
students.last_name student,
students.familyid,
-- ROUND(sum(finance_fees.particular_total),0) balance,
ROUND((finance_fees.particular_total),0) balance,
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE guardians.familyid = 12656 AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start'

";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
        echo "
        	<thead>
            <tr class='w3-light-grey'>
        		<th>#</th>
        		<th>Family ID</th>
        		<th>Parent</th>
                <th>Student</th>
                <th>Date</th>
                <th>Fee</th>
        		<th>Balance</th>
        	</tr>
            </thead>
        ";
    while ($row = $result->fetch_assoc()) {
        echo "
        	<tr class='w3-hover-green'>
        		<td>" . $rownumber 		 . "</td>
        		<td>" . $row['familyid'] . "</td>
        		<td>" . $row['parent']   . "</td>
                <td>" . $row['student']   . "</td>
                <td>" . $row['start_date']   . "</td>
                <td>" . $row['fee_name']   . "</td>
        		<td>" . $row['balance']  . "</td>
        	</tr>
        ";
        $rownumber++;
    }
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
