<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

//echo $start_date . ' to ' . $end_date;
//get the first day of the month
//$start_date = date('Y-m-01', strtotime($start_date));
//get the last day of the month
//$end_date = date('Y-m-t', strtotime($_REQUEST['end_date']));


//echo '<h4  style="margin-top: 20px; font-size: 20px" class="text-center">Archived Students FROM ' . date_format(date_create(($start_date)), "d-F-Y") . ' to ' . date_format(date_create(($end_date)), "d-F-Y") . '</h4>';
//echo '<h4  style="margin-top: 20px; font-size: 20px" class="text-center">Archived Students</h4>';

$rowspan_sql = "
SELECT archived_students.former_id                                            as sid,
       archived_students.admission_no,
       count(archived_students.admission_no) 'rowspan',
       CONCAT(archived_students.first_name, ' ', archived_students.last_name) AS 'student_full_name',
       CONCAT(c.course_name, ' ', b.name)                                        'grade',
       sum(ff.balance)                                                           'Pending',
       ffp.id                                                                    'ffp_id',
       ffp.name                                                                  'ffp_name',
       ffp.amount                                                                'amount',
       ffp.created_at                                                            'creation_date',
       ffc.start_date                                                            'start_date',
       ffc.due_date                                                              'due_date',
       archived_students.date_of_leaving
FROM `finance_fees` ff
         inner join archived_students on ff.student_id = archived_students.former_id
         inner join batches b on ff.batch_id = b.id
         inner join courses c on b.course_id = c.id
         inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
         inner join financial_years fy on ffc.financial_year_id = fy.id
         inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
         INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                   ((ffp.receiver_id = archived_students.former_id and
                                                     ffp.receiver_type = 'Student') or
                                                    (ffp.receiver_id = archived_students.student_category_id and
                                                     ffp.receiver_type = 'StudentCategory' and
                                                     ffp.batch_id = ff.batch_id) or
                                                    (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch'))
WHERE ffc.is_deleted = 0
  and ff.balance > 0
  AND STR_TO_DATE(archived_students.date_of_leaving, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(archived_students.date_of_leaving, '%Y-%m-%d') <= '$end_date'
group by sid
ORDER BY archived_students.former_id, archived_students.date_of_leaving;
";

$rowspan = [];
$rowspan_result = $conn->query($rowspan_sql);
if ($rowspan_result->num_rows > 0) {
    while ($row = $rowspan_result->fetch_assoc()) {
        $rowspan[$row['sid']] = $row['rowspan'];
    }
}


$archived_students = "
SELECT archived_students.former_id                                            as sid,
       archived_students.admission_no,
       CONCAT(archived_students.first_name, ' ', archived_students.last_name) AS 'student_full_name',
       CONCAT(c.course_name, ' ', b.name)                                        'grade',
       archived_students.familyid,
       ffp.id                                                                    'ffp_id',
       ff.id fee_id,
       ffp.name                                                                  'ffp_name',
       ffp.amount                                                                'amount',
       ffp.created_at                                                            'creation_date',
       ffc.start_date                                                            'start_date',
       ffc.due_date                                                              'due_date',
       archived_students.date_of_leaving,
       ff.particular_total 'total',
       (ff.particular_total - ff.balance) 'paid',
       ff.balance
FROM `finance_fees` ff
         inner join archived_students on ff.student_id = archived_students.former_id
         inner join batches b on ff.batch_id = b.id
         inner join courses c on b.course_id = c.id
         inner join finance_fee_collections ffc on ff.fee_collection_id = ffc.id
         inner join financial_years fy on ffc.financial_year_id = fy.id
         inner join collection_particulars cp on ffc.id = cp.finance_fee_collection_id
         INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and
                                                   (
                                                           (ffp.receiver_id = archived_students.former_id and
                                                            ffp.receiver_type = 'Student') or
                                                           (ffp.receiver_id = archived_students.student_category_id and
                                                            ffp.receiver_type = 'StudentCategory' and
                                                            ffp.batch_id = ff.batch_id) or
                                                           (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
                                                       )

WHERE ffc.is_deleted = 0 and ff.balance > 0
  AND STR_TO_DATE(archived_students.date_of_leaving, '%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(archived_students.date_of_leaving, '%Y-%m-%d') <= '$end_date'
GROUP BY sid, ff.batch_id,ffp_id
ORDER BY archived_students.former_id,due_date, archived_students.date_of_leaving
";


$totalPayments = $id = 0;

//echo $archived_students;
//echo "<br>";
//echo $rowspan_sql;
$result = $conn->query($archived_students);
if ($result->num_rows > 0) {
    $new_student = '';
    $total_pending_fees = @$total_pending_fees_student = 0;
    $first_row = true;
    while ($row = $result->fetch_assoc()) {
        $rowspan_no = 0;

        if ($first_row) {
            echo "<table style='margin-top: 10px!important;' class='table table-bordered table-hover' id='archived_students'>
                        <thead class='bg-green text-white'>
                            <tr>
                                <td class='textCenter bold' colspan='2'>FAMILY ID: " . $row['familyid'] . "</td>
                                <td class='textCenter bold'>NAME: ". $row['student_full_name'] . "</td>
                                <td class='textCenter bold' colspan='2'>ADMISSION NO: " . $row['admission_no'] . "</td>
                                <td class='text-left bold' colspan='2'>LEAVING DATE: " . $row['date_of_leaving'] . "</td>
                            </tr>
                        </thead>
                        ";

            echo "<thead class='bg-green text-white'>
                    <tr>
                        <th class='textCenter'><b>GRADE</b></th>
                        <th class='textCenter'><b>Due Date</b></th>
                        <th class='textCenter'><b>FEE</b></th>
                        <th class='textCenter'><b>TOTAL</b></th>
                        <th class='textCenter'><b>PAID</b></th>
                        <th class='textCenter'><b>PENDING</b></th>
                        <th class='textCenter'><b>DELETE</b></th>
                    </tr>
                  </thead>";

            $first_row = false;
            $new_student = $row['sid'];
            $rowspan_no = $rowspan[$new_student];
        }

        if ($new_student != $row['sid']) {
//            echo "<tr class='bg-lightYellow'><th class='bold text-center ' colspan='3'>TOTAL PENDING AMOUNT</th><th class='bold text-right'>" . number_format((float)$total_pending_fees_student, 2) . "</th></>";
            echo "<tr style='background-color: white; border-bottom: 2px black '><th colspan=3 class='bold text-center'><h3>&nbsp</h3></th></tr>";
            $total_pending_fees_student = 0;
            $new_student = $row['sid'];
            $rowspan_no = $rowspan[$new_student];
            echo "
                 <thead class='bg-green text-white'>
                            <tr>
                                <td class='textCenter bold' colspan='2'>FAMILY ID: " . $row['familyid'] . "</td>
                                <td class='textCenter bold'>NAME: ". $row['student_full_name'] . "</td>
                                <td class='textCenter bold' colspan='2'>ADMISSION NO: " . $row['admission_no'] . "</td>
                                <td class='text-left bold' colspan='2'>LEAVING DATE: " . $row['date_of_leaving'] . "</td>
                            </tr>
                </thead>
            ";

            echo "<thead class='bg-green text-white'>
                            <tr>
                                <th class='textCenter'><b>GRADE</b></th>
                                <th class='textCenter'><b>DUE DATE</b></th>
                                <th class='textCenter'><b>FEE</b></th>
                                <th class='textCenter'><b>TOTAL</b></th>
                                <th class='textCenter'><b>PAID</b></th>
                                <th class='textCenter'><b>PENDING</b></th>
                                <th class='textCenter'><b>DELETE</b></th>
                            </tr>
                        </thead>";
        }

        echo "
            <tr>
                <th class='text-center' > " . $row['grade'] . " </th>
                <th class='text-center' > " . $row['due_date'] . " </th>
                <th class='text-center' > " . $row['ffp_name'] . " </th>
                <th class='text-center' > " . number_format((float)$row['total'],2)  . " </th>
                <th class='text-center' > " . number_format((float)$row['paid'] ,2) . " </th>
                <th class='textRight' > " . number_format((float)$row['balance'], 2) . " </th>
                <th class='textCenter bold'><a class='delete-btn' id='". $row['fee_id'] . "'><i class='fas fa-trash-alt' title='Delete Fee'></i></a></th>                
              </tr> ";

        $total_pending_fees_student += $row['balance'];
        $total_pending_fees += $row['balance'];


    }
    echo '</body></table>';

} else {
    echo 'No Data Found! Try another search.';
}
