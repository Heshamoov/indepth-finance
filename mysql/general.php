<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
//       GRADES LIST

$grades_query = "
SELECT courses.course_name grade,
       count(distinct students.last_name) 'No.Students',
       finance_fee_collections.name,
       SUM(finance_fees.particular_total) total,
       SUM(finance_fees.discount_amount)  discount,
       SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)  expected,
(SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)) - SUM(finance_fees.balance) paid,
       SUM(finance_fees.balance) balance,
       finance_fee_collections.start_date
from finance_fees

         inner join batches on finance_fees.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join students on finance_fees.student_id = students.id

WHERE STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date '
  AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') <= '$end_date' ";

$grades_list = $grades_query . 'group by courses.course_name';

echo "<div id='printUpperDiv' >";
echo "<div id='printParentHeader' style='display:none' >";
printHeader('Fee Details', $start_date, $end_date);
echo '</div>';
echo "<div class='row' id='topDiv' style='margin: 10px;'>";
echo '<h4><u>Grades List</u></h4>';
echo "<a id='printbtnMain' style='margin-left: 25px; margin-top: 5px' onclick=printPDF('printUpperDiv','')>
    <span class='fa fa-print' style='font-size: 20px' aria-hidden='true'></span>
</a>";
echo "<table class='table table-bordered table-striped table-hover' id='gradesList'>
            <thead class='black white-text'>
                <tr>
                    <th class='textCenter'>Grade</th>
                    <th class='textCenter'>Students</th>
                    <th class='textCenter'>Total</th>
                    <th class='textCenter'>Discount</th> 
                    <th class='textCenter'>Net Revenue</th>
                    <th class='textCenter' colspan=2>Paid</th>
                    <th class='textCenter' colspan=2>Balance</th>
                </tr>
            </thead>";

// echo $grades_list;
$result = $conn->query($grades_list);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr >
                <td class='textLeft bold'>" . $row['grade'] . "</td>
                <td class='textLeft'>" . $row['No.Students'] . "</td>
                <td class='textRight bold'>" . number_format((float)$row['total']) . "</td>
                <td class='textRight'>" . number_format((float)$row['discount']) . "</td>
                <td class='textRight'>" . number_format((float)$row['expected']) . "</td>
                <td class='textRight'>" . number_format((float)$row['paid']) . "</td>";
        if ($row['expected'] != null and $row['expected'] != 0) {
            echo "<td class='textRight'>" . round(($row['paid'] / $row['expected']) * 100, 1) . "%</td>
                  <td class='textRight'>" . number_format((float)$row['balance']) . "</td>
                  <td class='textRight'>" . round(($row['balance'] / $row['expected']) * 100) . '%</td>';
        } else {
            echo "<td class='textRight'>0</td>";
            echo "<td class='textRight'>0</td>";
            echo "<td class='textRight'>0</td>";
        }


        echo "</tr>";
    }
} else {
    echo '<tr ><td colspan="9" class="text-center bold"> No Data Found! try another search!</td></tr>';
}


$result = $conn->query($grades_query);
//echo $grades_query;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr>
                <td class='textLeft bold'>Total</td>
                <td class='textLeft bold'>" . $row['No.Students'] . "</td>
                <td class='textRight bold'>" . number_format((float)$row['total']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['discount']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['expected']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['paid']) . "</td> ";
        if ($row['expected'] != null and $row['expected'] != 0) {
            echo "<td class='textRight bold'>" . round(($row['paid'] / $row['expected']) * 100, 1) . "%</td>
                  <td class='textRight bold'>" . number_format((float)$row['balance']) . "</td>
                  <td class='textRight bold'>" . round(($row['balance'] / $row['expected']) * 100, 1) . ' %</td > ';
        } else {
            echo "<td class='textRight bold'>0</td>";
            echo "<td class='textRight bold'>0</td>";
            echo "<td class='textRight bold'>0</td>";
        }
        echo ' </tr > ';
        echo '</table > ';
    }
} else {
    echo '<tr ><td colspan="9" class="text-center bold"> No Data Found! try another search!</td></tr>';
}
echo '</div > ';  // End of Grades List


//                  FEES LIST

$fees_list = "
SELECT courses.course_name grade,
       count(distinct students.last_name) 'No. Students',
       finance_fee_collections.name,
       SUM(finance_fees.particular_total) total,
       SUM(finance_fees.discount_amount)  discount,
       SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)  expected,
(SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)) - SUM(finance_fees.balance) paid,
       SUM(finance_fees.balance) balance,
       finance_fee_collections.start_date
from finance_fees

         inner join batches on finance_fees.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join students on finance_fees.student_id = students.id

WHERE STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') <= '$end_date'
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
    public function __construct($fee, $studentsNumber, $total, $discount, $expected, $paid, $balance)
    {
        $this->fee = $fee;
        $this->studentsNumber = $studentsNumber;
        $this->total = $total;
        $this->discount = $discount;
        if (round($expected) == 0) {
            $this->expected = 1;
        }
         else {
             $this->expected = $expected;
         }

        $this->paid = $paid;
        $this->balance = $balance;
    }

    public function print_fee()
    {

        echo "<tr>
                <th class='textLeft'>" . $this->fee . "</th>
                <td class='textLeft'>" . $this->studentsNumber . "</td>
                <td class='textRight'>" . number_format((float)$this->total) . "</td>
                <td class='textRight'>" . number_format((float)$this->discount) . "</td>
                <td class='textRight'>" . number_format((float)$this->expected) . "</td>
                <td class='textRight'>" . number_format((float)$this->paid) . "</td>
                <td class='textRight'>" . round(($this->paid / $this->expected) * 100, 1) . "%</td>
                <td class='textRight'>" . number_format((float)$this->balance) . "</td>
                <td class='textRight'>" . round(($this->balance / $this->expected) * 100, 1) . ' %</td >
            </tr> ';
    }
}

$fees_array = array();

//echo $fees_list;
$result = $conn->query($fees_list);
echo "<div class='row' >";
echo "<div class='col-8'>";
echo "<h4><u>Fees list</u></h4 >";

if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-striped  table-hover' id='feesList'>
            <thead class=\"black text-white\">
                <tr>
                    <th class='textCenter'>Fee</th>
                    <th class='textCenter'>Students</th>
                    <th class='textCenter'>Total</th>
                    <th class='textCenter'>Discount</th> 
                    <th class='textCenter'>Expected</th>
                    <th class='textCenter' colspan=2>Paid</th>
                    <th class='textCenter' colspan=2>Balance</th>                   
                </tr>
            </thead>";
    while ($row = $result->fetch_assoc()) {

        if (stripos($row['name'], 'book') !== false) {
            $fee = new Fee('Books', $row['No . Students'], $row['total'], $row['discount'], $row['expected'], $row['paid'], $row['balance']);
        } elseif (stripos($row['name'], 'bus') !== false) {
            $fee = new Fee('Bus', $row['No. Students'], $row['total'], $row['discount'], $row['expected'], $row['paid'], $row['balance']);
        } elseif (stripos($row['name'], 'installment') !== false) {
            $fee = new Fee('Tuition', $row['No. Students'], $row['total'], $row['discount'], $row['expected'], $row['paid'], $row['balance']);
        } elseif (stripos($row['name'], 'uniform') !== false) {
            $fee = new Fee('Uniform', $row['No. Students'], $row['total'], $row['discount'], $row['expected'], $row['paid'], $row['balance']);
        } else {
            $fee = new Fee('Other', $row['No. Students'], $row['total'], $row['discount'], $row['expected'], $row['paid'], $row['balance']);
        }

        $pushed = false;
        foreach ($fees_array as $fee_array) {
            if ($fee_array->fee === $fee->fee) {
                $fee_array->expected += $fee->expected;
                $fee_array->paid += $fee->paid;
                $fee_array->balance += $fee->balance;
                $pushed = true;
            }
        }
        if (!$pushed) {
            $fees_array[] = $fee;
        }
    }

    function cmp($a, $b)
    {
        return strcmp($a->fee, $b->fee);
    }

    uasort($fees_array, 'cmp');

    foreach ($fees_array as $fee) {
        $fee->print_fee();
    }


    $fees_list_sammury = "
SELECT courses.course_name grade,
       count(distinct students.last_name) 'No.Students',
       finance_fee_collections.name,
       SUM(finance_fees.particular_total) total,
       SUM(finance_fees.discount_amount)  discount,
       SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)  expected,
(SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)) - SUM(finance_fees.balance) paid,
       SUM(finance_fees.balance) balance,
       finance_fee_collections.start_date
from finance_fees

         inner join batches on finance_fees.batch_id = batches.id
         inner join courses on batches.course_id = courses.id
         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
         inner join students on finance_fees.student_id = students.id

WHERE STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date '
  AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') <= '$end_date' ";


    $result = $conn->query($fees_list_sammury);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <th class='textLeft'>Total</th>
                <td class='textLeft bold'>" . $row['No.Students'] . "</td>
                <td class='textRight bold'>" . number_format((float)$row['total']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['discount']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['expected']) . "</td>
                <td class='textRight bold'>" . number_format((float)$row['paid']) . "</td>";
            if ($row['expected'] != null and $row['expected'] != 0) {
                echo "<td class='textRight bold'>" . round(($row['paid'] / $row['expected']) * 100, 1) . "%</td>
                          <td class='textRight bold'>" . number_format((float)$row['balance']) . "</td>
                          <td class='textRight bold'>" . round(($row['balance'] / $row['expected']) * 100, 1) . ' %</td >';
            } else {
                echo "<td class='text-right'>0</td>";
                echo "<td class='text-right'>0</td>";
                echo "<td class='text-right'>0</td>";

            }
            echo "</tr>";
        }
        echo '</table ></div>';
    } else {
        echo 'No Data Found! try another search!';
    }
} else {
    echo 'No Data Found! try another search!</div>';
}

//                                                         PAYMENT MODE

echo "<div class='col'>";
include_once 'paymentModeSummary.php';
echo ' </div > ';  // col End
echo '</div > ';  // row End
echo '</div > ';  // End PrintUpperDiv


$general = "
SELECT 
guardians.first_name  parent,
students.last_name student,
COUNT(DISTINCT students.id) NumberOfStudents,
students.familyid,
SUM(finance_fee_discounts.discount_amount) discount,
SUM(finance_fees.particular_total) expected,
(SUM(finance_fees.particular_total)  - SUM(finance_fees.balance)) paid,
SUM(finance_fees.balance) balance,
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
LEFT JOIN finance_fee_discounts ON finance_fees.id = finance_fee_discounts.finance_fee_id

WHERE STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') <= '$end_date'
GROUP BY guardians.familyid
ORDER BY REPLACE(guardians.first_name,' ', '')
";
// echo $general;

$parents_list_sql = "
SELECT familyid,
       parent_name,
       NumberOfStudents,
       SUM(particular_total) expected,
       SUM(discount_amount)  discount,
       (SUM(particular_total) - SUM(balance)) paid, SUM(balance) balance,
       ffp_id,
       ffp_name,
       creation_date,
       amount,
       start_date,
       due_date
FROM (
         SELECT s.id                                   as sid,
                s.admission_no,
                COUNT(DISTINCT s.id)              NumberOfStudents,
                s.familyid,
                g.first_name                              'parent_name',
                CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                CONCAT(c.course_name, ' ', b.name)        'grade',
                ffp.id                                    'ffp_id',
                ffp.name                                  'ffp_name',
                ffp.amount                                'amount',
                ffp.created_at                            'creation_date',
                ffc.start_date                            'start_date',
                ffc.due_date                              'due_date',
                ff.particular_total, ffd.discount_amount,ff.balance

         FROM `finance_fees` ff
                  inner join students s on ff.student_id = s.id
                  inner join guardians g on s.familyid = g.familyid
                  inner join batches b on s.batch_id = b.id
                  inner join courses c on b.course_id = c.id
                  inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
                  inner join financial_years fy on ffc.financial_year_id = fy.id
                  inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
                  INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                            (
                                                                    (ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                                    (ffp.receiver_id = s.student_category_id and
                                                                     ffp.receiver_type = 'StudentCategory' and
                                                                     ffp.batch_id = ff.batch_id) or
                                                                    (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                                                                )
                  LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
         WHERE (ffp.is_reregistration != '1' AND s.is_active = 1 AND ffc.is_deleted = 0 AND
                b.start_date >= '$start_date' AND b.end_date <= '$end_date')
         UNION ALL
         SELECT s.id                                   as sid,
                s.admission_no,
                s.familyid,
                g.first_name                              'parent_name',
                CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                CONCAT(c.course_name, ' ', b.name)        'grade',
                ffp.id                                    'ffp_id',
                ffp.name                                  'ffp_name',
                ffp.amount                                'amount',
                ffp.created_at                            'creation_date',
                ffc.start_date                            'start_date',
                ffc.due_date                              'due_date',
                ff.particular_total,
                ffd.discount_amount, ff.balance
         FROM `finance_fees` ff
                  inner join students s on ff.student_id = s.id
                  inner join guardians g on s.familyid = g.familyid
                  inner join batch_students bs on s.id = bs.student_id
                  inner join batches b on bs.batch_id = b.id
                  inner join courses c on b.course_id = c.id
                  inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
                  inner join financial_years fy on ffc.financial_year_id = fy.id
                  inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
                  INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                            (
                                                                    (ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                                    (ffp.receiver_id = s.student_category_id and
                                                                     ffp.receiver_type = 'StudentCategory' and
                                                                     ffp.batch_id = ff.batch_id) or
                                                                    (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                                                                )
                  LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
         WHERE (ffp.is_reregistration != '1' AND s.is_active = 1 AND ffc.is_deleted = 0 AND
                b.start_date >= '$start_date' AND b.end_date <= '$end_date')
     ) t
group by familyid
order by familyid;
";


$parents_list_sql_current_section = "
SELECT t.familyid,
       parent_name,
       contact_no,
       NumberOfStudents,
       (particular_total)               expected,
       (discount_amount)                discount,
       ((particular_total) - (balance)) paid,
       (balance)                        balance,
       ffp_id,
       ffp_name,
       creation_date,
       amount,
       start_date,
       due_date,
       t2.opening_balance as            opening_balance
FROM ((
          SELECT s.id                                   as sid,
                 s.admission_no,COUNT(DISTINCT s.id)              NumberOfStudents,
                 s.familyid,
                 g.first_name                              'parent_name',      
                 CONCAT(g.mobile_phone, ' ', g.office_phone1)   'contact_no',                 
                 CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                 CONCAT(c.course_name, ' ', b.name)        'grade',
                 ffp.id                                    'ffp_id',
                 ffp.name                                  'ffp_name',
                 ffp.amount                                'amount',
                 ffp.created_at                            'creation_date',
                 ffc.start_date                            'start_date',
                 ffc.due_date                              'due_date',
                 SUM(ff.particular_total)                  particular_total,
                 SUM(ff.discount_amount)                   discount_amount,
                 SUM(ff.balance)                           balance
          FROM `finance_fees` ff
                   inner join students s on ff.student_id = s.id and ff.batch_id in (select id
                                                                                     from batches
                                                                                     where start_date >= '$start_date'
                                                                                       AND end_date <= '$end_date')
                   inner join guardians g on s.immediate_contact_id = g.id
                   left join batches b on s.batch_id = b.id
                   inner join courses c on b.course_id = c.id
                   inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
                   inner join financial_years fy on ffc.financial_year_id = fy.id
                   inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
                   INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                             ((ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                              (ffp.receiver_id = s.student_category_id and
                                                               ffp.receiver_type = 'StudentCategory' and
                                                               ffp.batch_id = ff.batch_id) or
                                                              (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch'))
                   LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
          WHERE (ffp.is_reregistration != '1' AND  s.is_active = 1 AND ffc.is_deleted = 0 AND
                 b.start_date >= '$start_date' AND
                 b.end_date <= '$end_date')
          group by familyid
          order by familyid) as t


         inner join (SELECT sum(balance) as opening_balance, s.familyid

                     FROM `finance_fees` ff
                              inner join students s on ff.student_id = s.id and ff.batch_id not in (select id
                                                                                                    from batches
                                                                                                    where start_date >= '$start_date'
                                                                                                      AND end_date <= '$end_date')
                              inner join guardians g on s.immediate_contact_id = g.id
                              left join batches b on s.batch_id = b.id
                              inner join courses c on b.course_id = c.id
                              inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
                              inner join financial_years fy on ffc.financial_year_id = fy.id
                              inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
                              INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                                        ((ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                                         (ffp.receiver_id = s.student_category_id and
                                                                          ffp.receiver_type = 'StudentCategory' and
                                                                          ffp.batch_id = ff.batch_id) or
                                                                         (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch'))
                              LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
                     WHERE ( ffp.is_reregistration != '1' AND s.is_active = 1 AND ffc.is_deleted = 0 AND
                            b.start_date >= '$start_date' AND b.end_date <= '$end_date')
                     group by familyid
                     order by familyid
) as t2 on t.familyid = t2.familyid


)
group by familyid
order by familyid
";


//echo $parents_list_sql_current_section;

$result = $conn->query($parents_list_sql_current_section);
$rowNumber = 1;
if ($result->num_rows > 0) {
    echo "<div id='ParentsDiv' class='row'>";
    echo "<div class='col'>";
    echo '<h4><u>Parents list</u></h4>';
    echo "<table class='table table-bordered table-striped table-hover' id='ParentsTable'>";
    echo "
    	<thead class='black white-text'>
        <tr>
    		<th>#</th>
    		<th width='20'>FamilyID</th>
    		<th>Parent</th>
    		<th>Mob. #</th>
            <th class='smallcol'>Children</th>
    		<th>Expected</th>
    		<th>Discount</th>
            <th>Paid</th>
            <th>Balance</th>
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        $row['paid'] -= $row['discount'];
        $params = array($start_date, $end_date, $row['familyid']);
        echo "
    	<tr onclick='FamilyStatement(" . json_encode($params) . ")'>
    		<td>" . $rowNumber . "</td>
    		<td  class='textLeft'>" . $row['familyid'] . ' </td >
    		<td > ' . $row['parent_name'] . "</td>
    		<td>". $row['contact_no'] ."</td>
            <td  class='textLeft'>" . $row['NumberOfStudents'] . "</td>
    		<td class='textRight'>" . number_format((float)$row['expected'] + $row['opening_balance'] ,2 ) . "</td>
    		<td class='textRight'>" . number_format((float)$row['discount'],2) . "</td>
            <td class='textRight'>" . number_format((float)$row['paid'],2) . "</td>
            <td class='textRight'>" . number_format((float)$row['balance']+ $row['opening_balance'],2) . ' </td >
    	</tr > ';
        $rowNumber++;
    }
    echo '</tbody></table></div></div> ';
} else {
    echo 'No Data Found! try another search . ';
}

$conn->close();