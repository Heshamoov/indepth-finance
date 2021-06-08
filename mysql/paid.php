<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

//$start_date = $_REQUEST['start_date'];
//echo $start_date;

//echo "           ";
//$end_date = $_REQUEST['end_date'];
//echo $end_date;
$master_ids = $_REQUEST['master_ids'];
//echo $master_ids;

if ($master_ids == '')
    $condition = '';
else
    $condition = ' AND mfp.id in (' . $master_ids . ') ';

$type = $_REQUEST['type'];

$year = $_REQUEST['years'];
$transactions_date = '';

if ($year != "") {
    $getStartDateEndDateFromFinancial_year = "SELECT start_date,end_date from financial_years WHERE id in ($year)";
//    echo $getStartDateEndDateFromFinancial_year;

    $result = $conn->query($getStartDateEndDateFromFinancial_year);
    $start_date = $end_date = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($start_date == '') $start_date = $row['start_date'];
            if ($start_date > $row['start_date']) $start_date = $row['start_date'];
            if ($end_date < $row['end_date']) $end_date = $row['end_date'];
        }
    }
    $transactions_date = " AND ( ft.transaction_date between '$start_date' and '$end_date')";
    $condition .= " AND fy.id in ( $year) ";
}

//echo $transactions_date;

$sql_header = '';

if ($type == 'parent') {
    $group = ' GROUP BY familyid ';
    $order = ' ORDER BY familyid ';
    $name = 'parent';
    $header = '<th>PARENT</th><th>CHILDREN</th>';
    $sql_header = ',COUNT(DISTINCT s.id) children';
    $grade_children = ' children,';
    $feesJoin = ' WHERE s.familyid = student_parent_info.familyid ';
} else {
    $header = "<th>ADMIN NO.</th><th>STUDENT</th><th>GRADE</th>";
    $group = ' GROUP BY sid ';
    $order = ' ORDER BY familyid, grade ';
    $name = 'student';
    $grade_children = " (SELECT CONCAT(c.course_name, '-', b.name) FROM batches b
                               INNER JOIN courses c ON b.course_id = c.id
                          WHERE b.id = student_parent_info.batch_id) grade,";
    $feesJoin = ' WHERE s.id = student_parent_info.sid ';
}

$student_sql = "
SELECT student_parent_info.familyid,student_parent_info.parent,$grade_children
       student_parent_info.sid,student_parent_info.student,student_parent_info.admission_no,contact_no,is_active,

       (SELECT SUM(t.amount)
        FROM (SELECT particular_total amount, student_id FROM finance_fees ff
              UNION ALL
              SELECT amount, payee_id student_id FROM instant_fees WHERE payee_type = 'Student') t
              INNER JOIN students s ON t.student_id = s.id
        $feesJoin) FEES,

       (SELECT SUM(discount_amount) FROM finance_fees
        INNER JOIN students s ON student_id = s.id
        $feesJoin) DISCOUNT,
       
       (SELECT SUM(t.amount)
        FROM (SELECT (particular_total - discount_amount) amount, student_id FROM finance_fees ff
              UNION ALL
              SELECT amount, payee_id student_id FROM instant_fees
              WHERE payee_type = 'Student'
            ) t
            INNER JOIN students s ON t.student_id = s.id
        $feesJoin) REVENUE,
       
       (SELECT SUM(amount) FROM finance_transactions
        INNER JOIN students s ON payee_id = s.id
        $feesJoin AND payee_type = 'Student') PAID,
  
       (SELECT SUM(balance) FROM finance_fees
        INNER JOIN students s ON student_id = s.id
        $feesJoin 
       ) BALANCE,
       
       (SELECT SUM(amount)
        FROM (
          SELECT ff.particular_total, ffp.amount, ff.student_id, s.id, s.familyid, ffp.is_reregistration, ff.registration_deducted
          FROM finance_fees ff
                INNER JOIN (
                                SELECT id, student_category_id, familyid, immediate_contact_id, admission_no
                                FROM students
                                UNION ALL
                                SELECT former_id, student_category_id, familyid, immediate_contact_id, admission_no
                                FROM archived_students
                          ) s on ff.student_id = s.id
               INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
               INNER JOIN financial_years fy on ffc.financial_year_id = fy.id
               INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
               INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                             (
                                 (ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or
                                  (ffp.receiver_id = s.student_category_id and
                                   ffp.receiver_type = 'StudentCategory' and
                                   ffp.batch_id = ff.batch_id) or
                                    (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                             )
            WHERE is_reregistration = 1 AND registration_deducted = 0 AND is_paid = 1 
      ) s
 $feesJoin) REG       

FROM (
      (
          SELECT s.id sid,s.batch_id, s.is_active, s.last_name student, s.admission_no, s.familyid, g.mobile_phone contact_no, g.first_name parent $sql_header
          FROM (
                               SELECT id, familyid, last_name, admission_no, batch_id, immediate_contact_id,is_active
                               FROM students
                               UNION ALL
                               SELECT former_id, familyid, last_name, admission_no, batch_id, immediate_contact_id, is_active
                               FROM archived_students
               ) s

               INNER JOIN (
                              SELECT id, first_name, mobile_phone FROM guardians
                              UNION ALL
                              SELECT former_id, first_name, mobile_phone FROM archived_guardians
                          ) g ON s.immediate_contact_id = g.id

          $group ) as student_parent_info)
    $order
";
//echo $student_sql;
$result = $conn->query($student_sql);
$rows_count = 0;
$rowNumber = 1;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!is_null($row['FEES']) || !is_null($row['REG'])) $rows_count++;
    }
    echo "<h5>Number of rows <span class='badge badge-info'>$rows_count</span></h5>";
    $result->data_seek(0);
    $total = 0.0;
    echo "<table class='table table-bordered table-striped table-hover table-sm' id='PaidTable'>";
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
    		<th>BALANCE</th>    		
    		<th>REGISTRATION FOR NEXT YEAR</th>    		
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        if (!is_null($row['FEES']) || !is_null($row['REG'])) {

            echo "<tr><td>" . $rowNumber . "</td>
    		      <td class='textLeft'>" . $row['familyid'] . "</td>
                  <td>" . $row['contact_no'] . "</td>";

            if ($type == 'student') {
                echo "<td>" . $row['admission_no'] . "</td>";
                if ($row['is_active'])
                    echo "<td>" . $row['student'] . "</td>";
                else
                    echo "<td>" . $row['student'] . " <label style='background-color: yellow;'>ARCHIVED</label></td>";

                echo "<td>" . $row['grade'] . "</td>";
            } else
                echo "<td>" . $row['parent'] . "</td><td>" . $row['children'] . "</td>";

            echo "
                    <td class='textRight'>" . number_format((float)$row['FEES'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['DISCOUNT'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['REVENUE'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['PAID'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['BALANCE'], 2) . "</td>
                    <td class='textRight'>" . number_format((float)$row['REG'], 2) . "</td>";
            $rowNumber++;
            $total = $total + $row['PAID'];
        }
    }
    echo '</tbody></table>';

    echo "<h2> TOTAL: AED " . $total . "</h2>";
} else {
    echo 'No Data Found! try another search . ';
}
$conn->close();