<?php

include('../config/db.php');

$start_date = $_REQUEST["start_date"];
$end_date = $_REQUEST["end_date"];

$installments = "
SELECT
    finance_fee_collections.name name,
    SUM(finance_fees.particular_total) expected,
    SUM(finance_fees.particular_total) - SUM(finance_fees.balance) paid,
    SUM(finance_fees.balance) balance,
    finance_fee_collections.start_date start_date
    
FROM guardians
    
INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id

WHERE 
STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
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

class Fee
{
    public function __construct($name, $expected, $paid, $balance)
    {
        $this->name = $name;
        $this->expected = $expected;
        $this->paid = $paid;
        $this->balance = $balance;
    }

    public function print_fee()
    {
        echo '<tr>
                <td class="textLeft">' . $this->name . '</td>
                <td class="textRight">' . number_format((float)$this->expected) . '</td>
                <td class="textRight">' . number_format((float)$this->paid) . '</td>
                <td class="textRight">' . number_format((float)$this->balance) . '</td>
            </tr>';
    }
}

$fees_array = array();

// echo $installments;
$result = $conn->query($installments);
if ($result->num_rows > 0) {
    echo "<div class='row'>";
    echo "<div  class='col-sm' id='leftDiv'>";
    echo "<div class='row' id='feesListDiv'>";
    echo '<h4><u>Fees List</u></h4>';
    echo "<table class='table  table-bordered table-striped  table-hover' id='feesList'>
            <thead>
                <tr>
                    <th class='textLeft'><b>FEE</b></th>
                    <th class='textLeft'><b>Expected</b></th>
                    <th class='textLeft'><b>Paid</b></th>
                    <th class='textLeft'><b>Balance</b></th>
                </tr>
            </thead>";
    while ($row = $result->fetch_assoc()) {

        if (strstr(strtolower($row['name']), 'book'))
            $fee = new Fee("Books", $row['expected'], $row['paid'], $row['balance']);
        elseif (strstr(strtolower($row['name']), 'bus'))
            $fee = new Fee("Bus", $row['expected'], $row['paid'], $row['balance']);
        elseif (strstr(strtolower($row['name']), 'installment'))
            $fee = new Fee("Tuition", $row['expected'], $row['paid'], $row['balance']);
        elseif (strstr(strtolower($row['name']), 'uniform'))
            $fee = new Fee("Uniform", $row['expected'], $row['paid'], $row['balance']);
        else
            $fee = new Fee("Other", $row['expected'], $row['paid'], $row['balance']);

        $pushed = false;
        foreach ($fees_array as $fee_array) {
            if ($fee_array->name == $fee->name) {
                $fee_array->expected += $fee->expected;
                $fee_array->paid += $fee->paid;
                $fee_array->balance += $fee->balance;
                $pushed = true;
            }
        }
        if (!$pushed)
            array_push($fees_array, $fee);
    }

    function cmp($a, $b)
    {
        return strcmp($a->name, $b->name);
    }

    uasort($fees_array, "cmp");
    echo '<tbody>';
    foreach ($fees_array as $fee) {
        $fee->print_fee();
    }

} else
    echo "No Data Found! Try another search.</div>";


$statistics = "
SELECT 
COUNT(DISTINCT guardians.first_name) parents, COUNT(DISTINCT students.last_name) students,
SUM(finance_fees.particular_total) expected,
SUM(finance_fees.particular_total) - SUM(finance_fees.balance) paid,
SUM(finance_fees.balance) balance,
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
        echo " <tr >
                <th><strong>Total</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['expected']) . "</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['paid']) . "</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['balance']) . '</strong></th>
              </tr>';
    }
    echo '</table></div>';
} else {
    echo 'No Data Found! Try another search. </div>';
}

echo "<div class='row' id='paymentModeDiv'>";

include_once 'paymentModeSummary.php';

echo "</div></div>";


//--------------------------grades table------------------------------------------
$grades = "
SELECT
    courses.course_name grade,
    SUM(finance_fees.particular_total) expected,
    SUM(finance_fees.particular_total) - SUM(finance_fees.balance) paid,
    SUM(finance_fees.balance) balance,
    finance_fee_collections.start_date start_date,
    CURDATE() today

FROM guardians

         INNER JOIN students ON guardians.familyid = students.familyid
         INNER JOIN finance_fees ON students.id = finance_fees.student_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
         INNER JOIN batches ON students.batch_id = batches.id
         INNER JOIN courses ON batches.course_id = courses.id

WHERE STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
GROUP BY courses.course_name
";

echo "<div class='col-sm' id='gradesListDiv'>";
echo '<h4><u>Grades List</u></h4>';
echo "<table class='table  table-bordered table-striped  table-hover' id='gradesList'>
            <thead>
                <tr>
                    <th class='textLeft'><b>Grade</b></th>
                    <th class='textLeft'><b>Expected</b></th>
                    <th class='textLeft'><b>Paid</b></th>
                    <th class='textLeft'><b>Balance</b></th>
                </tr>
            </thead>";

// echo $grades;
$result = $conn->query($grades);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr >
                <td class='textLeft'>" . $row['grade'] . "</td>
                <td class='textRight'>" . number_format((float)$row['expected']) . "</td>
                <td class='textRight'>" . number_format((float)$row['paid']) . "</td>
                <td class='textRight'>" . number_format((float)$row['balance']) . '</td>
              </tr>';
    }
} else {
    echo 'No Data Found! Try another search.';
}


// echo $statistics;
$result = $conn->query($statistics);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr >
                <th><strong>Total</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['expected']) . "</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['paid']) . "</strong></th>
                <th class='textRight'><strong>" . number_format((float)$row['balance']) . '</strong></th>
              </tr>';
    }
    echo '</table></div></div>';
} else {
    echo 'No Data Found! Try another search.';
}


$general = "
SELECT 
guardians.first_name  parent,
students.last_name student,
COUNT(DISTINCT students.id) NumberOfStudents,
students.familyid,
SUM(finance_fees.particular_total) expected,
SUM(finance_fees.particular_total) - SUM(finance_fees.balance) paid,
SUM(finance_fees.balance) balance,
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
$rowNumber = 1;
if ($result->num_rows > 0) {

    echo "<div class='row'>";
    echo "<div id='ParentsDiv' class='col-sm' >";
    echo '<h4><u>Parents List</u></h4>';
    echo "<table class='table  table-bordered table-striped  table-hover ' id='ParentsTable'>";
    echo '
    	<thead>
        <tr>
    		<th>#</th>
    		<th  width="20" >FamilyID</th>
    		<th>Parent</th>
            <th class="smallcol">Children</th>
    		<th>Expected</th>
            <th>Paid</th>
            <th>Balance</th>
    	</tr>
        </thead>
        <tbody>
    ';
    while ($row = $result->fetch_assoc()) {
        $params = array($start_date, $end_date, $row['familyid']);
        echo "
    	<tr  onclick='FamilyStatement(" . json_encode($params) . ")'>
    		<td>" . $rowNumber . "</td>
    		<td  class='textLeft'>" . $row['familyid'] . "</td>
    		<td>" . $row['parent'] . "</td>
            <td  class='textRight'>" . $row['NumberOfStudents'] . "</td>
    		<td class='textRight'>" . (float)$row['expected'] . "</td>
            <td class='textRight'>" . (float)$row['paid'] . "</td>
            <td class='textRight'>" . (float)$row['balance'] . "</td>
    	</tr>
        ";
        $rowNumber++;
    }
    echo "</tbody></table></div></div>";
} else {
    echo "No Data Found! Try another search.";
}


$conn->close();