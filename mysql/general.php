<?php

include ('../config/db.php');

$start_date = $_REQUEST["start_date"];
$end_date = $_REQUEST["end_date"];
// echo $start_date;

$general= "
SELECT 
guardians.first_name  parent,
students.last_name student,
students.familyid,
ROUND(SUM(finance_fees.particular_total),0) balance,
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE guardians.familyid = 12656 AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
GROUP BY students.id;
";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<table class='w3-table-all w3-card w3-centered'>";
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
        $params = array($start_date, $end_date, $row['familyid']);
        echo "
    	<tr class='w3-hover-green' onclick='FamilyStatement(" . json_encode($params) . ")'>
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
    echo "</table>";    
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
