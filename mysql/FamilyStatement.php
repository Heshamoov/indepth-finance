<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$familyid = $_REQUEST['familyid'];

$general = "
SELECT 
guardians.first_name  parent,
students.last_name student,
students.familyid,
students.admission_no admission_no,
finance_fees.particular_total expected,
finance_fees.balance balance,       
finance_fee_discounts.discount_amount discount,
finance_fee_collections.name fee_name,
finance_fee_collections.start_date start_date,
finance_fee_collections.end_date end_date,
finance_fee_collections.due_date due_date,
batches.name section, courses.course_name

FROM guardians 

INNER JOIN students ON guardians.familyid = students.familyid
INNER JOIN finance_fees ON students.id = finance_fees.student_id
INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
LEFT JOIN finance_fee_discounts ON finance_fees.id = finance_fee_discounts.finance_fee_id
INNER JOIN batches ON students.batch_id = batches.id
INNER JOIN courses ON batches.course_id = courses.id

WHERE guardians.familyid = $familyid AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
ORDER BY courses.course_name, finance_fee_collections.due_date


";

$general = "
SELECT familyid,
       parent_name,
       sid, student_full_name,admission_no,course_name,section,
       particular_total expected,
       discount_amount discount,
       balance,
       ffp_id,
       ffp_name fee_name,
       creation_date,
       amount,
       start_date,
       due_date
FROM (
         SELECT s.id                                   as sid,
                s.admission_no,
                s.familyid,
                g.first_name                              'parent_name',
                CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                c.course_name 'course_name',
                b.name 'section',
                ffp.id                                    'ffp_id',
                ffp.name                                  'ffp_name',
                ffp.amount                                'amount',
                ffp.created_at                            'creation_date',
                ffc.start_date                            'start_date',
                ffc.due_date                              'due_date',
                ff.particular_total,
                ffd.discount_amount,
                ff.balance

         FROM `finance_fees` ff
                  inner join students s on ff.student_id = s.id
                  inner join guardians g on s.familyid = g.familyid
                  inner join batches b on s.batch_id = b.id AND ff.batch_id = b.id
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
                s.familyid = '$familyid' AND
                b.start_date >= '$start_date' AND b.end_date <= '$end_date')
         UNION ALL
         SELECT s.id                                   as sid,
                s.admission_no,
                s.familyid,
                g.first_name                              'parent_name',
                CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                c.course_name 'course_name',
                b.name 'section',
                ffp.id                                    'ffp_id',
                ffp.name                                  'ffp_name',
                ffp.amount                                'amount',
                ffp.created_at                            'creation_date',
                ffc.start_date                            'start_date',
                ffc.due_date                              'due_date',
                ff.particular_total,
                ffd.discount_amount,
                ff.balance
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
                s.familyid = '$familyid' AND
                b.start_date >= '$start_date' AND b.end_date <= '$end_date')
     ) t

";


$general_current_section = "
SELECT familyid,
       parent_name,
       sid, student_full_name,admission_no,course_name,section,
       particular_total expected,
       discount_amount discount,
       balance,
       ffp_id,
       ffp_name fee_name,
       creation_date,
       amount,
       start_date,
       due_date
FROM (
         SELECT s.id                                   as sid,
                s.admission_no,
                s.familyid,
                g.first_name                              'parent_name',
                CONCAT(s.first_name, ' ', s.last_name) AS 'student_full_name',
                c.course_name 'course_name',
                b.name 'section',
                ffp.id                                    'ffp_id',
                ffp.name                                  'ffp_name',
                ffp.amount                                'amount',
                ffp.created_at                            'creation_date',
                ffc.start_date                            'start_date',
                ffc.due_date                              'due_date',
                ff.particular_total,
                ff.discount_amount,
                ff.balance

         FROM `finance_fees` ff
                  inner join students s on ff.student_id = s.id
                  inner join guardians g on s.familyid = g.familyid
                  inner join batches b on s.batch_id = b.id AND ff.batch_id = b.id
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
                s.familyid = '$familyid' AND
                b.start_date >= '$start_date' AND b.end_date <= '$end_date')
     ) t
ORDER BY course_name,sid
";
//echo $general_current_section;

$result = $conn->query($general_current_section);
$rowNumber = 1;
if ($result->num_rows > 0) {
    $params = array($start_date, $end_date, $familyid);
    echo "<div id='parentStatementDiv' class='row' style='margin-top: 40px!important;' >";
    echo "<div class='row' style='width: 100%; padding-left: 20px; margin-left: 20px'>";
    printHeader('Parent Statement', $start_date, $end_date);
    echo '</div>';

    echo "<a id='goback' title='Go Back' style='padding-left: 20px; padding-right: 20px' onclick='general(" . json_encode($params) . ")'>
          <b> <i class=\"fa fa-arrow-left\" aria-hidden=\"true\"></i></b></a>";


    $parent_header = true;
    $first_name_old = "";
    $second_table = false;
    $total_expected = $total_balance = $total_paid = $total_discount = 0;

    while ($row = $result->fetch_assoc()) {
        if ($parent_header) {
            echo '<h4 id="parent_heading" style="padding-left: 30px" > Parent: ' . $row['familyid'] . ' - ' . $row['parent_name'] . '</h4>';
            echo "<a  style='margin-left: 25px; margin-top: 5px'
                               onclick=printPDFStatement('parentStatementDiv','')>
                                <span class='fa fa-print' style='font-size: 20px' aria-hidden='true'></span>
                            </a>";
            echo '<a  id="btnTransaction" style="margin-left:auto; margin-right: 20px" type="button" href="#transaction_heading" class="btn btn-sm btn-blue-grey " >View Transactions</a>';

            $parent_header = false;
        }

        $student = $row['student_full_name'];
        $first_name = explode(' ', trim($student));

        if ($first_name != $first_name_old) {
            if ($second_table) {
                echo "<tr><td colspan='3' align='center'><b>Total</b></td>
                            <td align='right'>" . number_format($total_expected,2) . "</td>
                            <td align='right'>" . number_format($total_discount,2) . "</td>
                            <td align='right'>" . number_format($total_paid,2) . "</td>
                            <td align='right'>" . number_format($total_balance,2) . '</td>
                      </tr>
                      </table><br>';
            } else
                $second_table = true;
            $total_expected = $total_balance = $total_paid = $total_discount = 0;
            echo "<table id='fee_table' style='margin-top: -5px!important; ' class='table table-sm table-striped table-hover table-bordered student_table' >";
            echo "
                <thead>
                    <tr>
                        <th colspan=7 align='center'  class=\"black  white-text\"> Student: <b>" .
                $row['admission_no'] . ' - ' . $row['student_full_name'] . '</b> &nbsp&nbsp Grade: <b>' .
                $row['course_name'] . '</b> &nbsp&nbsp Section: <b>' .
                $row['section'] . '</b></th>
                    </tr>
                    <tr >
                        <th>#</th>
                        <th>Date</th>
                        <th>Fee Description</th>
                        <th>Expected</th>
                         <th>Discount</th>
                        <th>Paid</th>
                        <th>Due</th>
                    </tr>
                </thead>
                ';
            $first_name_old = $first_name;
        }

        $balance = (float)$row['balance'];
        $total_balance += $balance;

        $expected = (float)$row['expected'];
        $total_expected += $expected;

        $discount = (float)$row['discount'];
        $total_discount += $discount;

        $paid = $expected - $discount - $balance;

        $total_paid += $paid;
        echo "
        	<tr class='w3-hover-green' >
        		<td>" . $rowNumber . '</td>
                <td>' . $row['start_date'] . '</td>
                <td>' . $row['fee_name'] . "</td>
        		<td align='right'>" . number_format((float)$row['expected']) . "</td>
        		<td align='right'>" . number_format((float)$row['discount']) . "</td>
        		<td align='right'>" . number_format($paid) . "</td>
        		<td align='right'>" . number_format((float)$row['balance']) . '</td>
        	</tr>';
        $rowNumber++;
    }
    echo "<tr><td colspan='3' align='center'>Total</td>
          <td align='right'>" . number_format($total_expected) . "</td>
          <td align='right'>" . number_format($total_discount) . "</td>
          <td align='right'>" . number_format($total_paid) . "</td>
          <td align='right'>" . number_format($total_balance) . '</td>
          </tr></table>';
} else {
    echo 'No Data Found! Try another search.';
}
include_once 'parentStatement.php';
echo '</div>';
$conn->close();
