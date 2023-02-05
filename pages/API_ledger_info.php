<?php require_once 'base.php';

$sql = mysqli_query($conn, "Select * from accounts_ledger");
$results = array();
while ($row = mysqli_fetch_array($sql)) {
    $results[] = array(
        'ledger_id' => $row['ledger_id'],
        'ledger_name' => $row['ledger_name']
    );
}
echo json_encode($results);
