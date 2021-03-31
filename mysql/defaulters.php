<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$master_ids = $_REQUEST['master_ids'];
//echo $master_ids;

if ($master_ids == '')
    $condition = '';
else
    $condition = ' AND mfp.id in (' . $master_ids . ') ';

$type = $_REQUEST['type'];
$type = $_REQUEST['type'];
$year = $_REQUEST['years'];
if ($year != "") {
    $condition .= "AND fy.id in ( $year) ";
}

$sql_header = '';

if ($type == 'parent') {
    $group = ' GROUP BY familyid  order by familyid ';
    $join = ' student_parent_info.familyid = current_fees.familyid ';
    $join_with_opening_balance = ' student_parent_info.familyid = prev_balance.familyid ';
    $name = 'parent';
    $header = '<th>PARENT</th><th>CHILDREN</th>';
    $sql_header = 'COUNT(DISTINCT s.id) children,';
    $grade_children = 'children';
} else {
    $header = "<th>ADMIN NO.</th><th>STUDENT</th><th>GRADE</th>";
    $group = ' GROUP BY sid ';
    $join = ' student_parent_info.sid = current_fees.sid ';
    $join_with_opening_balance = ' student_parent_info.sid = prev_balance.sid ';
    $name = 'student';
    $grade_children = 'grade';
}

$student_sql = "
SELECT 
       student_parent_info.familyid, parent,student,admission_no,contact_no,$grade_children,
       total,discount,revenue,paid,balance,IFNULL(opening_balance,0) opening_balance,(IFNULL(balance,0) + IFNULL(opening_balance,0)) 'net_balance'
FROM (
      (SELECT s.id                                         sid,
              s.familyid,
              
              g.first_name                                 'parent',$sql_header
              
              s.last_name 'student',
              s.admission_no,
              CONCAT(c.course_name, ' ', b.name)           'grade',
              
              CONCAT(g.mobile_phone, ' ', g.office_phone1) 'contact_no'
       FROM students s
                INNER JOIN guardians g on s.immediate_contact_id = g.id
                INNER JOIN batches b on s.batch_id = b.id
                INNER JOIN courses c on b.course_id = c.id
       WHERE (s.is_active = 1)
          $group
      ) as student_parent_info
      
         LEFT JOIN (SELECT s.id     sid,s.familyid,
                           ffc.due_date,
                           IFNULL(SUM(ff.particular_total),'0') total,
                           IFNULL(SUM(ffp.amount),'0') amount,
                           IFNULL(SUM(ff.discount_amount),'0') discount,
                           IFNULL(SUM(ff.particular_total - ff.discount_amount),'0') 'revenue',
                           IFNULL(SUM(ff.particular_total - ff.balance - ff.discount_amount),'0') 'paid',
                           IFNULL(SUM(ff.balance),'0') balance,
                           mfp.id 'master_id',
                           mfp.name master_name
                    FROM `finance_fees` ff
                             INNER JOIN students s on ff.student_id = s.id and s.batch_id in
                                   (SELECT id FROM batches WHERE start_date >= '$start_date' AND end_date <= '$end_date')
                             INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
                             INNER JOIN financial_years fy on ffc.financial_year_id = fy.id
                             INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
                             INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                                       ((ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                                        (ffp.receiver_id = s.student_category_id and
                                                                         ffp.receiver_type = 'StudentCategory' and
                                                                         ffp.batch_id = ff.batch_id) or
                                                                        (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch'))
                             INNER JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id
                             LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
                    WHERE (ffc.is_deleted = 0 $condition)
                    $group) as current_fees ON $join
         LEFT JOIN (
    SELECT IFNULL(SUM(balance),'0') opening_balance, s.familyid, s.id sid
    FROM `finance_fees` ff
             INNER JOIN students s on ff.student_id = s.id and ff.batch_id not in
                   (SELECT id FROM batches WHERE start_date >= '$start_date' AND end_date <= '$end_date')
             INNER JOIN guardians g on s.immediate_contact_id = g.id
             LEFT JOIN batches b on ff.batch_id = b.id
             INNER JOIN courses c on b.course_id = c.id
             INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
             LEFT JOIN financial_years fy on ffc.financial_year_id = fy.id
             INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
             INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                       ((ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                                        (ffp.receiver_id = s.student_category_id and
                                                         ffp.receiver_type = 'StudentCategory' and
                                                         ffp.batch_id = ff.batch_id) or
                                                        (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch'))
             LEFT JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id
             LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
    WHERE (s.is_active = 1 AND ffc.is_deleted = 0)
    $group
) as prev_balance ON $join_with_opening_balance
    )
";
//echo $student_sql;
$result = $conn->query($student_sql);
$rowNumber = 1;
if ($result->num_rows > 0) {

    $rows_count = 0;
    while ($row = $result->fetch_assoc()) {
        if (($row['balance'] > 0) or ($row['opening_balance'] > 0)) {
            $rows_count++;
        }
    }
    echo "<h5>Number of rows <span class='badge badge-info'>$rows_count</span></h5>";

    $result->data_seek(0);

    echo "<table class='table table-bordered table-striped table-hover' id='DefaultersTable'>";
    echo "
    	<thead class='black white-text'>
        <tr>
    		<th>#</th>
    		<th width='20'>FAMILY ID</th>
            <th width='20'>MOB. #</th>
            $header
    		<th>TOTAL</th>
    		<th>DISCOUNT</th>
    		<th>REVENUE</th>
    		<th>PAID</th>
    		<th>DUE</th>    		
    		<th>OPENING <BR> BALANCE</th>
    		<th>NET BALANCE</th>
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        if (($row['balance'] > 0) or ($row['opening_balance'] > 0)) {
            echo "
    	        <tr>
    		        <td>" . $rowNumber . "</td>
    		        <td  class='textLeft'>" . $row['familyid'] . "</td>
                    <td>" . $row['contact_no'] . "</td>";

            if ($type == 'student')
                echo "<td>" . $row['admission_no'] . "</td><td>" . $row['student'] . "</td><td>" . $row['grade'] . "</td>";
            else
                echo "<td>" . $row['parent'] . "</td><td>" . $row['children'] . "</td>";

            echo "
                    <td class='textRight'>" . number_format((float)$row['total'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['discount'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['revenue'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['paid'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['balance'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['opening_balance'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['net_balance'], 2) . "</td>";
            $rowNumber++;
        }
    }
    echo '</tbody></table>';
} else {
    echo 'No Data Found! try another search . ';
}
$conn->close();