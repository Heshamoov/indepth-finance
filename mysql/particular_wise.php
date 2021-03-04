<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$master_id = $_REQUEST['master_id'];
$type = $_REQUEST['type'];

if ($master_id == 'All') $condition = ''; else $condition = ' AND mfp.id = ' . $master_id . ' ';

if ($type == 'parent'){
    $group = ' group by s.familyid ';
    $name = 'parent';
    $balance = 'SUM(ff.balance)';
    $total = 'SUM(ff.particular_total) ';
    $paid = 'SUM(ff.particular_total - ff.balance - ff.discount_amount)';
    $discount = 'SUM(ff.discount_amount)';
    $grade_header = $admission_no = '';
}
else{
    $group = ' ';
    $name = 'student';
    $grade_header = "<th class='smallcol'>GRADE</th>";
    $balance = '(ff.balance)';
    $total = '(ff.particular_total)';
    $paid = '(ff.particular_total - ff.balance - ff.discount_amount)';
    $discount = '(ff.discount_amount)';
}




$get_fees = "
SELECT s.familyid,
       g.first_name                                 'parent',
       CONCAT(g.mobile_phone, ' ', g.office_phone1) 'contact_no',
       CONCAT(IFNULL(s.last_name,s.first_name), ' (' , s.admission_no, ')') as student,
       CONCAT(c.course_name, ' ', b.name)           'grade',
       mfp.name                                     master_fee,
       $total total,
       $paid paid, 
       $discount 'discount',
       $balance balance,
       ffc.name,
       ffcat.name,
       ffcat.is_master,
       mfp.id, s.admission_no
FROM `finance_fees` ff
         INNER JOIN students s on ff.student_id = s.id and ff.batch_id in (select id
                                                                           from batches
                                                                           where start_date >= '2020-9-1'
                                                                             AND end_date <= '2021-8-31')
         INNER JOIN guardians g on s.immediate_contact_id = g.id
         INNER JOIN batches b on s.batch_id = b.id
         INNER JOIN courses c on b.course_id = c.id
         INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
         INNER JOIN finance_fee_categories ffcat ON ffc.fee_category_id = ffcat.id
         INNER JOIN financial_years fy on ffc.financial_year_id = fy.id
         INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
         INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                   (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
         INNER JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id
WHERE ffc.is_deleted = 0
  AND STR_TO_DATE(ffc.start_date, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(ffc.due_date, '%Y-%m-%d') <= '$end_date'
  $condition
  AND balance > 0
$group
order by s.familyid,ff.student_id  ;
      ";

//echo $get_fees;
$result = $conn->query($get_fees);
$rowNumber = 1;
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-striped table-hover' id='DefaultersTable'>";
    echo "
    	<thead class='black white-text'>
        <tr>
    		<th>#</th>
    		<th width='20'>FAMILY ID</th>
            <th class='smallcol'>NAME</th>
            <th>MOB. #</th>
            $grade_header
            <th>FEE</th>
    		<th>TOTAL</th>
    		<th>PAID</th>
    		<th>DISCOUNT</th>
    		<th>BALANCE</th>
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        echo "
    	<tr>
    		<td>" . $rowNumber . "</td>
    		<td  class='textLeft'>" . $row['familyid'] . "</td>
            <td  class='textLeft'>" . $row[$name] . "</td>
            <td>". $row['contact_no'] ."</td>";

        if ($type == 'student')
            echo "<td  class='textLeft'>" . $row['grade'] . "</td>";

            echo "<td  class='textLeft'>" . $row['master_fee'] . "</td>
            <td class='textRight'>" . number_format((float)$row['total'],2) . "</td>
            <td class='textRight'>" . number_format((float)$row['paid'],2) . "</td>
            <td class='textRight'>" . number_format((float)$row['discount'],2) . "</td>
            <td class='textRight'>" . number_format((float)$row['balance'],2) . ' </td >
    	</tr > ';
        $rowNumber++;
    }
    echo '</tbody></table>';
} else {
    echo 'No Data Found! try another search . ';
}
$conn->close();