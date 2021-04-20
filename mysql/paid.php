<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
//echo $start_date;

//echo "           ";
$end_date = $_REQUEST['end_date'];
//echo $end_date;
$master_ids = $_REQUEST['master_ids'];
//echo $master_ids;

if ($master_ids == '')
    $condition = '';
else
    $condition = ' AND mfp.id in (' . $master_ids . ') ';

$type = $_REQUEST['type'];
$year = $_REQUEST['years'];
if ($year != "") {
    $condition .= "AND fy.id in ( $year) ";
}

$sql_header = '';

if ($type == 'parent') {
    $group = ' GROUP BY familyid ';
    $order = ' ORDER BY familyid ';
    $name = 'parent';
    $header = '<th>PARENT</th><th>CHILDREN</th>';
    $sql_header = 'COUNT(DISTINCT s.id) children, ';
    $grade_children = 'children';
} else {
    $header = "<th>ADMIN NO.</th><th>STUDENT</th><th>GRADE</th>";
    $group = ' GROUP BY sid ';
    $order = ' ORDER BY familyid, grade ';
    $name = 'student';
    $grade_children = 'grade';
}

$student_sql = "
SELECT 
       student_parent_info.familyid, parent,student,admission_no,contact_no,$grade_children,current_fees.fee_name,
       total,discount,revenue,paid,balance
FROM (
      (SELECT s.id                                         sid,
              s.familyid,
              g.first_name                                 'parent',
              $sql_header
              s.last_name 'student',
              s.admission_no,
              CONCAT(c.course_name, ' ', b.name)           'grade',
              g.mobile_phone 'contact_no'
       FROM(select id, familyid, last_name, admission_no, batch_id, immediate_contact_id
             from students
             union all
             select former_id, familyid, last_name, admission_no, batch_id, immediate_contact_id
             from archived_students) s
                inner JOIN
            (select id, first_name, mobile_phone
             from guardians
             union
             select former_id, first_name, mobile_phone
             from archived_guardians) g on s.immediate_contact_id = g.id
                INNER JOIN batches b on s.batch_id = b.id
                INNER JOIN courses c on b.course_id = c.id    
           GROUP BY s.id
      ) as student_parent_info
         INNER JOIN (SELECT s.id     sid,s.familyid,ffc.name fee_name,
                           ffc.due_date,
                           IFNULL(SUM(ff.particular_total),'0') total,
                           IFNULL(SUM(ffp.amount),'0') amount,
                           IFNULL(SUM(ff.discount_amount),'0') discount,
                           IFNULL(SUM(ff.particular_total - ff.discount_amount),'0') 'revenue',
                           IFNULL(SUM(ft.amount),'0') 'paid',
                           IFNULL(SUM(ff.balance),'0') balance,
                           mfp.id 'master_id',
                           mfp.name master_name
                    FROM finance_transactions ft
             inner join(
        select id, student_category_id, familyid, immediate_contact_id
        from students
        union
        select former_id, student_category_id, familyid, immediate_contact_id
        from archived_students) s on ft.payee_id = s.id
             INNER JOIN `finance_fees` ff on ft.finance_id = ff.id
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
                    WHERE (ffc.is_deleted = 0 AND ff.balance != ff.particular_total $condition 
                    AND (ff.particular_total - ff.balance - ff.discount_amount > 0)                       
                         and ft.payee_type = 'Student' and
                           ( ft.transaction_date between '$start_date' and '$end_date'))
                      $group) as current_fees ON student_parent_info.familyid = current_fees.familyid and student_parent_info.sid = current_fees.sid
                    
    ) $order
";
//echo $student_sql;
$result = $conn->query($student_sql);
$rowNumber = 1;
if ($result->num_rows > 0) {
    $total = 0.0;
//    echo '<h4>No. ' . mysqli_num_rows($result) . '</h4>';
    echo "<table class='table table-bordered table-striped table-hover' id='PaidTable'>";
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
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
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
                    <td class='textRight'>" . number_format((float)$row['balance'], 2) . "</td>";
        $rowNumber++;
        $total = $total + $row['paid'];
    }
    echo '</tbody></table>';

    echo "<h2> TOTAL: AED " . $total . "</h2>";
} else {
    echo 'No Data Found! try another search . ';
}
$conn->close();