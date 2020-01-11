<?php

include('../config/db.php');

////                                                           GRADES LIST
//
//$grades_query = "
//SELECT courses.course_name grade,
//       count(distinct students.last_name) 'No.Students',
//       finance_fee_collections.name,
//       SUM(finance_fees.particular_total) total,
//       SUM(finance_fees.discount_amount)  discount,
//       SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)  expected,
//(SUM(finance_fees.particular_total) - SUM(finance_fees.discount_amount)) - SUM(finance_fees.balance) paid,
//       SUM(finance_fees.balance) balance,
//       finance_fee_collections.start_date
//from finance_fees
//
//         inner join batches on finance_fees.batch_id = batches.id
//         inner join courses on batches.course_id = courses.id
//         inner join finance_fee_collections on finance_fees.fee_collection_id = finance_fee_collections.id
//         inner join students on finance_fees.student_id = students.id
//
//WHERE STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') >= '$start_date '
//  AND STR_TO_DATE(finance_fee_collections.start_date, '%Y-%m-%d') <= '$end_date' ";
//
//$grades_list = $grades_query . "group by courses.course_name";

$fees = array(120,66,78);
$hello = "hesham";
