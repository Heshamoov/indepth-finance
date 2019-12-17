<?php

include ('../config/db.php');

$start_date = $_REQUEST["start_date"];
$end_date = $_REQUEST["end_date"];
// echo $start_date;


$installments = "
SELECT
    finance_fee_collections.name name,
    ROUND(SUM(finance_fees.particular_total),0) balance,
    finance_fee_collections.start_date start_date,
    CURDATE() today

FROM guardians

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE 
STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '2019/09/01'
AND
    (
        finance_fee_collections.name like '%Installment%'  OR 
        finance_fee_collections.name like '%BUS%'  OR 
        finance_fee_collections.name like '%BOOK%' OR
        finance_fee_collections.name like '%uniform%' OR
        finance_fee_collections.name like '%'

    ) 

GROUP BY 
finance_fee_collections.name like  '%Installment%',
finance_fee_collections.name like  '%BUS%',
finance_fee_collections.name like '%BOOK%',
finance_fee_collections.name like '%uniform%',
finance_fee_collections.name like '%'
    
ORDER BY finance_fee_collections.name

";

class Fee {
    public function __construct($name, $amount) {
        $this->name = $name;
        $this->amount = $amount;
    }
    
    public function print_fee() {
        echo "<tr class='w3-hover-green'>
                <th>" . $this->name . "</th>
                <th>" . $this->amount . "</th>
              </tr>";
    }
}

$fees_array = array();

// echo $installments;
$result = $conn->query($installments);
if ($result->num_rows > 0) {
   echo "<div id='StatisticsDiv' class='w3-col'>";
   echo "<table class='w3-table w3-centered w3-table-all' id='StatisticsTable'>
            <thead>
                <tr>
                    <th class='tableHeader'>FEE</th>
                    <th class='tableHeader'>AMOUNT</th>
                </tr>
            </thead>";
    while ($row = $result->fetch_assoc()) {
        if (strstr(strtolower($row['name']), 'book'))
            $fee = new Fee("Books", $row['balance']);
        elseif (strstr(strtolower($row['name']), 'bus'))
            $fee = new Fee("Bus", $row['balance']);
        elseif (strstr(strtolower($row['name']), 'installment'))
            $fee = new Fee("Tuition", $row['balance']);
        elseif (strstr(strtolower($row['name']), 'uniform'))
            $fee = new Fee("Uniform", $row['balance']);
        else
            $fee = new Fee("Other", $row['balance']);

        $pushed = false;
        foreach ($fees_array as $fee_array) {
            if ($fee_array->name == $fee->name) {
                $fee_array->amount += $fee->amount;
                $pushed = true;
            }
        }
        if (!$pushed) 
            array_push($fees_array, $fee);
    }
    
    function cmp($a, $b) {
        return strcmp($a->name, $b->name);
    }
    uasort($fees_array, "cmp");

    foreach ($fees_array as $fee) {
        $fee->print_fee();
    }

}else 
    echo "No Data Found! Try another search.";



$statistics= "
SELECT 
COUNT(DISTINCT guardians.first_name) parents, COUNT(DISTINCT students.last_name) students,
ROUND(SUM(finance_fees.particular_total),0) balance,
finance_fee_collections.start_date start_date,
CURDATE() today

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
";

// echo $statistics;
$result = $conn->query($statistics);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr class='w3-hover-green'>
                <td>Total Balance</td><td class='textRight'>". $row['balance']  . "</td>
              </tr>
              <thead ><tr style='background-color:#4CAF50; color:white;  text-align: center !important;'><td><b>Statistics</b></td><td><b>Count</b></td></tr></thead> 
              <tr class='w3-hover-green'>
                <td>Number of Parents</td><td class='textRight'>" . $row['parents']  . "</td>
              </tr>
              <tr class='w3-hover-green'>
                <td>Number of Students</td><td class='textRight'>" . $row['students'] . "</td>
             </tr>";
    }
    echo "</table></div>";
}else 
    echo "No Data Found! Try another search.";





$general= "
SELECT 
guardians.first_name  parent,
students.last_name student,
COUNT(DISTINCT students.id) NumberOfStudents,
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

WHERE STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
GROUP BY guardians.familyid
ORDER BY REPLACE(guardians.first_name,' ', '')
";
// echo $general;
$result = $conn->query($general);
$rownumber = 1;
if ($result->num_rows > 0) {
    echo "<div id='ParentsDiv' class='w3-col'>";
    echo "<table class='w3-card w3-centered w3-table-all' id='ParentsTable'>";
    echo "
    	<thead>
        <tr>
    		<th>#</th>
    		<th width=20>FamilyID</th>
    		<th>Parent</th>
            <th>Children</th>
    		<th>Balance</th>
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        $params = array($start_date, $end_date, $row['familyid']);
        echo "
    	<tr class='w3-hover-green' onclick='FamilyStatement(" . json_encode($params) . ")'>
    		<td>" . $rownumber 		 . "</td>
    		<td  class='textRight'>" . $row['familyid'] . "</td>
    		<td>" . $row['parent']   . "</td>
            <td  class='textRight'>" . $row['NumberOfStudents']   . "</td>
    		<td class='textRight'>" . $row['balance']  . "</td>
    	</tr>
        ";
        $rownumber++;
    }
    echo "</tbody></table></div>";    
} else {
    echo "No Data Found! Try another search.";
}
$conn->close();
