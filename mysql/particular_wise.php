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

if ($type == 'parent') {
    $group = ' GROUP BY familyid  order by familyid ';
    $name = 'parent';
    $balance = 'SUM(balance) + t2.opening_balance';
    $total = 'SUM(particular_total) ';
    $paid = 'SUM(particular_total - balance - discount_amount)';
    $revenue = '(SUM(particular_total) - SUM(discount_amount))';
    $discount = 'SUM(discount_amount)';
    $header = '<th>CHILDREN</th>';
    $sql_header = 'COUNT(DISTINCT s.id) children';
    $column_header = 'children';
} else {
    $group = ' GROUP BY sid ';
    $name = 'student';
    $header = "<th class='smallcol'>GRADE</th><th>FEE</th>";
    $balance = '(balance) + t2.opening_balance';
    $total = '(particular_total)';
    $paid = '(particular_total - balance - discount_amount)';
    $revenue = '(SUM(particular_total) - SUM(discount_amount))';
    $discount = '(discount_amount)';
    $header = '<th>Adm No.</th><th>Grade</th><th>Fee</th>';
    $sql_header = 's.admission_no';
    $column_header = 'admission_no';
}


$Fees_sql = "
SELECT t1.familyid,parent,student,t1.sid,student,grade,contact_no,
       $column_header,
       $total              total,
       $discount                discount,
       $revenue revenue,
       $paid paid,
       $balance balance,
       t2.opening_balance opening_balance,
       ffp_id, ffp_name, master_id, master_name,
       creation_date,amount,start_date,due_date,
       t2.opening_balance as            opening_balance
FROM (
      (SELECT s.id as sid,$sql_header,s.familyid,
              g.first_name                              'parent',      
              CONCAT(g.mobile_phone, ' ', g.office_phone1)   'contact_no',                 
              s.last_name AS 'student',
              CONCAT(c.course_name, ' ', b.name)        'grade',
              ffp.id                                    'ffp_id',
              ffp.name                                  'ffp_name',
              ffp.amount                                'amount',
              ffp.created_at                            'creation_date',
              ffc.start_date                            'start_date',
              ffc.due_date                              'due_date',
              SUM(ff.particular_total)                  particular_total,
              SUM(ff.discount_amount)                   discount_amount,
              SUM(ff.balance)                           balance, mfp.id 'master_id', mfp.name master_name
           FROM `finance_fees` ff
                inner join students s on ff.student_id = s.id and ff.batch_id in (select id
                                                                                  from batches
                                                                                  where start_date >= '2020-9-1'
                                                                                    AND end_date <= '2021-8-31')
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
                              INNER JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id

                LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
       WHERE (  s.is_active = 1 AND ffc.is_deleted = 0 AND b.start_date >= '2020-9-1' AND
              b.end_date <= '2021-8-31' 
                 $condition   )
        $group) as t1
         
         INNER JOIN (
              SELECT sum(balance) as opening_balance, s.familyid, s.id sid
              FROM `finance_fees` ff
              INNER JOIN students s on ff.student_id = s.id and ff.batch_id not in 
                   (select id from batches where start_date >= '$start_date' AND end_date <= '$end_date')
              INNER JOIN guardians g on s.immediate_contact_id = g.id
              LEFT JOIN batches b on s.batch_id = b.id
              INNER JOIN courses c on b.course_id = c.id
              INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
              INNER JOIN financial_years fy on ffc.financial_year_id = fy.id
              INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
              INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                (
                    (ffp.receiver_id = s.id and ffp.receiver_type = 'Student') or (ffp.receiver_id = s.student_category_id and
                     ffp.receiver_type = 'StudentCategory' and ffp.batch_id = ff.batch_id) or
                     (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                )
              INNER JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id
              LEFT JOIN finance_fee_discounts ffd ON ff.id = ffd.finance_fee_id
              WHERE (  s.is_active = 1 AND ffc.is_deleted = 0 AND b.start_date >= '$start_date' AND b.end_date <= '$end_date' 
              $condition)
                    $group
         ) as t2 on t1.familyid = t2.familyid AND t1.sid = t2.sid
    )
    $group
    ";


//echo $Fees_sql;
$result = $conn->query($Fees_sql);
$rowNumber = 1;
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-striped table-hover' id='DefaultersTable'>";
    echo "
    	<thead class='black white-text'>
        <tr>
    		<th>#</th>
    		<th width='20'>FAMILY ID</th>
            <th>NAME</th>
            <th width='20'>MOB. #</th>
            $header
    		<th>OPENING <BR> BALANCE</th>
    		<th>TOTAL</th>
    		<th>DISCOUNT</th>
    		<th>REVENUE</th>
    		<th>PAID</th>
    		<th>TOTAL BALANCE</th>
    	</tr>
        </thead>
        <tbody>
    ";
    while ($row = $result->fetch_assoc()) {
        if ($row['balance'] > 0) {
        echo "
    	<tr>
    		<td>" . $rowNumber . "</td>
    		<td  class='textLeft'>" . $row['familyid'] . "</td>
            <td  class='textLeft'>" . $row[$name] . "</td>
            <td>" . $row['contact_no'] . "</td>
            <td>" . $row[$column_header] . "</td>";

        if ($type == 'student') {
            echo "<td class='textLeft'>" . $row['grade'] . "</td>";
            echo "<td class='textLeft'>" . $row['master_name'] . "</td>";
        }


        echo "
        <td class='textRight'>" . number_format((float)$row['opening_balance'], 2) . "</td>
        <td class='textRight'>" . number_format((float)$row['total'], 2) . "</td>
        <td class='textRight'>" . number_format((float)$row['discount'], 2) . "</td>
        <td class='textRight'>" . number_format((float)$row['revenue'], 2) . "</td>
        <td class='textRight'>" . number_format((float)$row['paid'], 2) . "</td>
        <td class='textRight'>" . number_format((float)$row['balance'], 2) . "</td>";

        $rowNumber++;
    }}
    echo '</tbody></table>';
} else {
    echo 'No Data Found! try another search . ';
}
$conn->close();