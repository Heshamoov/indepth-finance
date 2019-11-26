<?php

include ('../config/db.php');

$fromdate = $_REQUEST["fromdate"];
$todate = $_REQUEST["todate"];

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
SELECT guardians.familyid FamilyID, guardians.first_name Parent,
       COUNT(IF(students.familyid = guardians.familyid, 1, NULL)) 'No. of Students'
FROM students
	INNER JOIN guardians ON students.familyid = guardians.familyid        

GROUP BY guardians.id
ORDER BY guardians.familyid DESC;
";
// echo $sql;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
        echo "
        	<tr>
        		<th>#</th>
        		<th>Family ID</th>
        		<th>Parent</th>
        		<th>No. of Students</th>
        	</tr>
        ";
    while ($row = $result->fetch_assoc()) {
        echo "
        	<tr>
        		<td>" . $rownumber 				. "</td>
        		<td>" . $row['FamilyID']  . "</td>
        		<td>" . $row['Parent']       . "</td>
        		<td>" . $row['No. of Students']    . "</td>
        	</tr>
        ";
        $rownumber++;
    }
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
