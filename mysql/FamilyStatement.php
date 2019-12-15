<?php

include ('../config/db.php');

$start_date = $_REQUEST["start_date"];
$end_date = $_REQUEST["end_date"];
$familyid = $_REQUEST["familyid"];

$general= "
SELECT 
guardians.first_name  parent,
students.last_name student,
students.familyid,
ROUND((finance_fees.particular_total),0) balance,
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE guardians.familyid = $familyid AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'


";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
    $params = array($start_date, $end_date, $familyid);

    echo "<button class='w3-button' onclick='general(" . json_encode($params) . ")'>
            <i class='material-icons'>arrow_back</i></button>";

$perent_header = true;
$first_name_old = "";
$second_table = false;
    while ($row = $result->fetch_assoc()) {
        if ($perent_header) {
            echo "<h2>" . $row['parent'] . " - " . $row['familyid'] . "</h2>";
            $perent_header = false;
        }
        
        $student = $row['student'];
        $first_name = explode(' ',trim($student));
        
        if ($first_name != $first_name_old) {
            if ($second_table)
                echo "</table><br>";
            else
                $second_table = true;
            echo "<table class='w3-table-all w3-card w3-centered'>";
            echo "
                <thead>
                    <tr>
                        <th colspan=4 align='center'>" . $first_name[0] . "</th>
                    </tr>
                    <tr class='w3-light-grey'>
                        <th>#</th>
                        <th>Date</th>
                        <th>Fee</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                ";
            $first_name_old = $first_name;
        }
        echo "
        	<tr class='w3-hover-green'>
        		<td>" . $rownumber 		 . "</td>
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
