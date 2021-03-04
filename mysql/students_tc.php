<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

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
       CONCAT(c.course_name, ' - ', b.name)                                        'grade',
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
GROUP BY sid
ORDER BY archived_students.familyid, c.course_name, b.name
";



$result = $conn->query($archived_students);
if ($result->num_rows > 0) {
    echo "<table style='margin-top: 10px!important;' class='table table-bordered table-hover' id='archived_students'>
                <thead class='bg-green text-white'>
                    <tr>
                        <th>FAMILY ID</th>
                        <th>ADMISSION NO.</th>
                        <th>NAME</th>
                        <th>GRADE</th>
                        <th>Took TC</th>
                    </tr>
                </thead>
                <tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                    <td class='textCenter'>" . $row['familyid'] . "</td>
                    <td class='textCenter'>" . $row['admission_no'] . "</td>
                    <td class='textCenter'>" . $row['student_full_name'] . "</td>
                    <td class='text-left'>" . $row['grade'] . "</td>
                    <td class='text-left'>
                        <div class='form-check'>
                            <input class='form-check-input' type='checkbox' value='' id='flexCheckDefault'/>
                            <label class='form-check-label' for='flexCheckDefault'>
                                Default checkbox
                            </label>
                        </div>      
                    </td>
              </tr>";
    }
    echo "</body></table>";
} else {
    echo 'No Data Found! Try another search.';
}
