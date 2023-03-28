<?php
require_once 'support_file.php';
$now=date("Y-m-d H:i:s");
$table="user_permission_matrix_warehouse";
if(prevent_multi_submit()) {
    extract($_POST);
    $warehouse_id = mysqli_real_escape_string($conn, $warehouse_id);
    $status = mysqli_real_escape_string($conn, $status);

    $warehouse_id_in_database=find_a_field(''.$table.'','COUNT(warehouse_id)','warehouse_id='.$warehouse_id.' and user_id="'.$_SESSION['MIS_permission_matrix'].'"');
    if($warehouse_id>0){
    if($warehouse_id_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE ".$table." SET status='".$status."',powerby='".$_SESSION['userid']."',powerdate='".$now."',ip='".$ip."' WHERE warehouse_id='".$warehouse_id."' and user_id='" . $_SESSION['MIS_permission_matrix'] . "'");
    } else {
        $sql = mysqli_query($conn, "INSERT INTO ".$table." (warehouse_id,user_id,powerby,powerdate,status,section_id,company_id,ip) 
        VALUES ('$warehouse_id','".$_SESSION['MIS_permission_matrix']."','".$_SESSION['userid']."','$now','1','".$_SESSION['sectionid']."','".$_SESSION['companyid']."','$ip')");
    }}}

?>