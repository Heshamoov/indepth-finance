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
students.admission_no admission_no,
finance_fees.particular_total expected,
finance_fees.balance balance,       
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date,
batches.name section, courses.course_name

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
INNER JOIN batches ON students.batch_id = batches.id
INNER JOIN courses ON batches.course_id = courses.id

WHERE guardians.familyid = $familyid AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
ORDER BY courses.course_name, finance_fee_collections.due_date


";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
    $params = array($start_date, $end_date, $familyid);

    echo "<a title='Go Back' style='padding-left: 20px; padding-right: 20px' onclick='general(" . json_encode($params) . ")'>
          <b>  <i class='material-icons'  style='color:red; font-weight: bolder' >arrow_back</i></b></a>";

$parent_header = true;
$first_name_old = "";
$second_table = false;
$total_expected = $total_balance = $total_paid = 0;

    while ($row = $result->fetch_assoc()) {
        if ($parent_header) {
            echo "<h4 > Parent: " .$row['familyid'] . " - " .  $row['parent'] . "</h4>";
            $parent_header = false;
        }
        
        $student = $row['student'];
        $first_name = explode(' ',trim($student));
        
        if ($first_name != $first_name_old) {
            if ($second_table) {
                echo "<tr><td colspan='3' align='center'><b>Total</b></td>
                            <td align='right'>".$total_expected."</td>
                            <td align='right'>".$total_paid."</td>
                            <td align='right'>".$total_balance."</td>
                            </tr>
                            </table><br>";
            }
            else
                $second_table = true;
            $total_expected = $total_balance = $total_paid = 0;
            echo "<table class='table table-sm table-striped table-bordered student_table' >";
            echo "
                <thead>
                    <tr>
                        <th colspan=6 align='center'> Student: <b>" .
                           $row['admission_no'] ." - ". $row['student']. "</b> &nbsp&nbsp Grade: <b>" .
                            $row['course_name'] . "</b> &nbsp&nbsp Section: <b>" .
                            $row['section'] . "</b></th>
                    </tr>
                    <tr class='w3-light-grey'>
                        <th>#</th>
                        <th>Date</th>
                        <th>Particulars</th>
                        <th>Fees Expected</th>
                        <th>Fees Paid</th>
                        <th>Fees Due</th>
                    </tr>
                </thead>
                ";
            $first_name_old = $first_name;
        }
        
        $balance = (float)$row['balance'];
        $total_balance+= $balance;

        $expected = (float)$row['expected'];
        $total_expected+= $expected;

        $paid = $expected - $balance;
        $total_paid+= $paid;
        echo "
        	<tr class='w3-hover-green' >
        		<td>" . $rownumber 		 . "</td>
                <td>" . $row['start_date']   . "</td>
                <td>" . $row['fee_name']   . "</td>
        		<td align='right'>" . (float)$row['expected']  . "</td>
        		<td align='right'>" . $paid . "</td>
        		<td align='right'>" . (float)$row['balance']  . "</td>
        	</tr>";
        $rownumber++;
    }
    echo "<tr><td colspan='3' align='center'>Total</td>
          <td align='right'>".$total_expected."</td><td align='right'>".$total_paid."</td><td align='right'>".$total_balance."</td></tr></table>";
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
