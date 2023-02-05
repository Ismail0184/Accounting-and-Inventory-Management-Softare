<?php require_once 'base.php';

$sql = mysqli_query($conn, "Select * from dealer_info where canceled in ('Yes')");
$results = array();
while ($row = mysqli_fetch_object($sql)) {
    $results[] = array(
        'dealer_name_e' => $row->dealer_name_e,
        'account_code' => $row->account_code
    );
}
echo json_encode($results);
