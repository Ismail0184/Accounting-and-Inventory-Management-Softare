<?php
require_once 'support_file.php';
$now=date("Y-m-d H:i:s");
$unique='id';
$table="user_permissions";
$crud      =new crud($table);
if(prevent_multi_submit()) {
    extract($_POST);
    $zonecode = mysqli_real_escape_string($conn, $zonecode);
    $status = mysqli_real_escape_string($conn, $status);

    $zonecode_in_database=find_a_field(''.$table.'','COUNT(zonecode)','zonecode='.$zonecode.' and user_id="'.$_SESSION['MIS_permission_matrix'].'"');
    if($zonecode>0){
    if($zonecode_in_database>0) {
        $sql = mysqli_query($conn, "UPDATE ".$table." SET status='$status',powerby='$_SESSION[userid]',powerdate='".$now."',ip='".$ip."' WHERE zonecode='" . $zonecode . "' and user_id='" . $_SESSION['MIS_permission_matrix'] . "'");
    } else {
        $sql = mysqli_query($conn, "INSERT INTO ".$table." (zonecode,user_id,powerby,powerdate,status,section_id,companyid,ip) 
        VALUES ('$zonecode','".$_SESSION['MIS_permission_matrix']."','".$_SESSION['userid']."','$now','1','".$_SESSION['sectionid']."','".$_SESSION['companyid']."','$ip')");
    }}}

?>
