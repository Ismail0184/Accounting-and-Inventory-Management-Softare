<?php require_once 'base.php';

$sql = mysqli_query($conn, "Select a.ledger_id,a.ledger_name,i.client_id from accounts_ledger a,acc_intercompany i where a.ledger_id=i.ledger_id");
$results = array();
while ($row = mysqli_fetch_array($sql)) {
    $results[] = array(
        'ledger_id' => $row['ledger_id'],
        'ledger_name' => $row['ledger_name'],
        'client_id' => $row['client_id']
    );
}
echo json_encode($results);
