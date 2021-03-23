<?php
session_start();
date_default_timezone_set('Asia/Dubai');
include_once '../functions.php';
include('../config/db.php');

$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$year = $_REQUEST['year'];
if ($year != "") {$year = "AND fy.id =  $year ";}

$get_fees = "
SELECT ffc.name,ffcat.name, ffcat.is_master, mfp.id master_id,mfp.name master_fee,mfp.id
FROM `finance_fees` ff
    INNER JOIN finance_fee_collections ffc on ff.fee_collection_id = ffc.id
    INNER JOIN finance_fee_categories ffcat ON ffc.fee_category_id = ffcat.id
    INNER JOIN financial_years fy on ffc.financial_year_id = fy.id
    INNER JOIN collection_particulars cp on ffc.id = cp.finance_fee_collection_id
    INNER JOIN finance_fee_particulars ffp ON ffp.id = cp.finance_fee_particular_id and (ffp.receiver_id = ff.batch_id and ffp.receiver_type = 'Batch')
    INNER JOIN master_fee_particulars mfp ON ffp.master_fee_particular_id = mfp.id
WHERE ffc.is_deleted = 0 AND ffc.start_date >= $start_date $year
group by mfp.id;";


$result = $conn->query($get_fees);
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result))
        echo "<option value='$row[master_id]'>" . $row['master_fee'] . "</option>";

}
$conn->close();