<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];


$archived_students_with_balance = "
SELECT archived_students.former_id                                            as sid,
       archived_students.admission_no, archived_students.first_name,archived_students.last_name,
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
       SUM(ff.particular_total)                                                       'total',
       SUM(ff.particular_total - ff.balance)                                        'paid',
       SUM(ff.balance) balance
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
ORDER BY archived_students.familyid, c.course_name, b.name, balance
";

//echo $archived_students_with_balance;

$row_no = 0;

$result = $conn->query($archived_students_with_balance);
if ($result->num_rows > 0) {
    echo "<table class='table table-bordered table-hover' id='archived_students'>
                <thead class='bg-green text-white'>
                    <tr>
                        <th>NO.</th>
                        <th>FAMILY ID</th>
                        <th>ADMISSION NO.</th>
                        <th>NAME</th>
                        <th>GRADE</th>";

    if (can_access_finance()) {
        echo "<th>TOTAL</th>
              <th>PAID</th>
              <th>BALANCE</th>";
    }

    echo "<th>TOOK TC</th>
                    </tr>
                </thead>
                <tbody>";
    while ($row = $result->fetch_assoc()) {
        $row_no++;
        echo "<tr>
                    <td class='textCenter'>" . $row_no . "</td>
                    <td class='textCenter'>" . $row['familyid'] . "</td>
                    <td class='textCenter'>" . $row['admission_no'] . "</td>
                    <td class='textCenter'>
                    <div>" . $row['last_name'] . "</div>
                    <div>" . $row['first_name'] . "</div>
                    </td>
                    <td class='text-left'>" . $row['grade'] . "</td>";

        if (can_access_finance()) {
            echo "<td class='text-left'>" . number_format($row['total'], 2) . "</td>
                  <td class='text-left'>" . number_format($row['paid'], 2) . "</td>
                  <td class='text-left'>" . number_format($row['balance'], 2) . "</td>";
        }


        echo "<td>";
        $tc_check = $conn->query("select * from student_tc where former_id = $row[sid]");
        if ($tc_check->num_rows > 0) {
            while ($s_row = $tc_check->fetch_assoc()) {
                if ($s_row['took_tc'])
                    echo "<div class='form-check form-switch' ><input class='form-check-input' type = 'checkbox' id = '" . $row['sid'] . "' checked ></div> ";
                else
                    echo "<div class='form-check form-switch' ><input class='form-check-input' type = 'checkbox' id = '" . $row['sid'] . "' ></div> ";
                echo "<div ><label class='form-check-label' for='flexSwitchCheckDefault' id = l" . $row['sid'] . " ></label ></div>
                    </td ></tr > ";
            }
        } else {
            echo "
                <div class='form-check form-switch' ><input class='form-check-input' type = 'checkbox' id = '" . $row['sid'] . "' ></div>
                <div ><label class='form-check-label' for='flexSwitchCheckDefault' id = l" . $row['sid'] . " ></label ></div>
                </td ></tr > ";
        }
    }
    echo "</body></table> ";
} else {
    echo 'No Data Found! Try another search.';
}
