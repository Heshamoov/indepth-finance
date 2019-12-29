<?php

include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];

$parentsSmsList = "
SELECT
    guardians.first_name  parent,
    guardians.mobile_phone,
    COUNT(DISTINCT students.id) students,
    students.familyid,
    SUM(finance_fees.particular_total) total,
    SUM(finance_fee_discounts.discount_amount) discount,
    SUM(finance_fees.particular_total) expected,
    (SUM(finance_fees.particular_total)  - SUM(finance_fees.balance)) paid,
    SUM(finance_fees.balance) balance,
    finance_fee_collections.name fee_name,
    finance_fee_collections.start_date start_date,
    finance_fee_collections.end_date end_date,
    finance_fee_collections.due_date due_date

FROM guardians

         INNER JOIN students ON guardians.familyid = students.familyid
         INNER JOIN finance_fees ON students.id = finance_fees.student_id
         INNER JOIN finance_fee_collections ON finance_fees.fee_collection_id = finance_fee_collections.id
         LEFT JOIN finance_fee_discounts ON finance_fees.id = finance_fee_discounts.finance_fee_id

WHERE STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') >= '$start_date'
  AND STR_TO_DATE(finance_fee_collections.start_date,'%Y-%m-%d') <= '$end_date'

GROUP BY guardians.familyid
ORDER BY REPLACE(guardians.first_name,' ', '')
";



echo "<div class='row-sm' id='topDiv'>";
echo '<h4><u>Parents List</u></h4>';
echo "<table class='table table-bordered table-striped table-hover' id='parentsSmsList'>
            <thead class='black white-text'>
                <tr>
                    <th class='textLeft'>Parent</th>
                    <th class='textLeft'>Mobile</th>
                    <th class='textCenter'>Students</th>
                    <th class='textCenter'>FamilyID</th> 
                    <th class='textCenter'>Total</th> 
                    <th class='textCenter'>Discount</th> 
                    <th class='textCenter'>Expected</th>
                    <th class='textCenter'>Paid</th>
                    <th class='textCenter'>Balance</th>                    
                </tr>
            </thead>";

$result = $conn->query($parentsSmsList);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo " <tr >
                <td class='textLeft bold'>" . $row['parent'] . "</td>
                <td class='textLeft bold'>" . $row['mobile_phone'] . "</td>
                <td class='textLeft'>" . $row['students'] . "</td>
                <td class='textLeft'>" . $row['familyid'] . "</td>
                <td class='textLeft'>" . number_format((float)$row['total']) . "</td>
                <td class='textLeft'>" . number_format((float)$row['discount']) . "</td>
                <td class='textLeft'>" . number_format((float)$row['expected']) . "</td>
                <td class='textLeft'>" . number_format((float)$row['paid'])."</td>
                <td class='textRight'>" . number_format((float)$row['balance'])."</td>
              </tr>";
    }
    echo "</table></div>";
} else {
    echo 'No Data Found! Try another search.';
}